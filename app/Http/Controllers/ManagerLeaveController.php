<?php

namespace App\Http\Controllers;

use App\Models\LeaveApproval;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $manager = $user->employee;

        if (! $manager) {
            abort(403, 'No employee profile linked to this user.');
        }

        $query = LeaveRequest::with(['employee', 'leaveType', 'approver'])
            ->where('status', 'pending')
            ->latest();

        if (! $user->hasAnyRole(['Super Admin', 'HR Admin'])) {
            $teamEmployeeIds = $manager->directReports()->pluck('id');
            $query->whereIn('employee_id', $teamEmployeeIds);
        }

        return view('manager-leave.index', [
            'employee' => $manager,
            'requests' => $query->get(),
            'isHr' => $user->hasAnyRole(['Super Admin', 'HR Admin']),
        ]);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $manager = $user->employee;

        if (! $manager) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (! $user->hasAnyRole(['Super Admin', 'HR Admin'])) {
            if ((int) $leaveRequest->employee->manager_id !== (int) $manager->id) {
                abort(403, 'You can only approve leave for your direct reports.');
            }
        }

        $data = $request->validate([
            'comment' => ['nullable', 'string'],
        ]);

        $leaveRequest->update([
            'approver_id' => $manager->id,
            'status' => 'approved',
            'manager_comment' => $data['comment'] ?? null,
            'approved_at' => now(),
        ]);

        LeaveApproval::create([
            'leave_request_id' => $leaveRequest->id,
            'approver_id' => $manager->id,
            'action' => 'approved',
            'comment' => $data['comment'] ?? null,
            'acted_at' => now(),
        ]);

        $year = (int) Carbon::parse($leaveRequest->start_date)->format('Y');

        $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', $year)
            ->first();

        if ($balance) {
            $balance->used_days += $leaveRequest->days_requested;
            $balance->remaining_days -= $leaveRequest->days_requested;
            $balance->save();
        }

        return redirect()
            ->route('manager-leave.index')
            ->with('status', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $manager = $user->employee;

        if (! $manager) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (! $user->hasAnyRole(['Super Admin', 'HR Admin'])) {
            if ((int) $leaveRequest->employee->manager_id !== (int) $manager->id) {
                abort(403, 'You can only reject leave for your direct reports.');
            }
        }

        $data = $request->validate([
            'comment' => ['nullable', 'string'],
        ]);

        $leaveRequest->update([
            'approver_id' => $manager->id,
            'status' => 'rejected',
            'manager_comment' => $data['comment'] ?? null,
            'rejected_at' => now(),
        ]);

        LeaveApproval::create([
            'leave_request_id' => $leaveRequest->id,
            'approver_id' => $manager->id,
            'action' => 'rejected',
            'comment' => $data['comment'] ?? null,
            'acted_at' => now(),
        ]);

        return redirect()
            ->route('manager-leave.index')
            ->with('status', 'Leave request rejected.');
    }
}