<?php

namespace App\Http\Controllers;

use App\Models\LeaveApproval;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approver', 'approvals'])->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string'],
        ]);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $daysRequested = $start->diffInDays($end) + 1;

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $data['employee_id'],
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_requested' => $daysRequested,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json(
            $leaveRequest->load(['employee', 'leaveType']),
            201
        );
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return $leaveRequest->load(['employee', 'leaveType', 'approver', 'approvals']);
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string'],
            'manager_comment' => ['nullable', 'string'],
        ]);

        $leaveRequest->update($data);

        return $leaveRequest->load(['employee', 'leaveType', 'approver', 'approvals']);
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return response()->json(['message' => 'Leave request deleted']);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $data = $request->validate([
            'approver_id' => ['required', 'exists:employees,id'],
            'comment' => ['nullable', 'string'],
        ]);

        $leaveRequest->update([
            'approver_id' => $data['approver_id'],
            'status' => 'approved',
            'manager_comment' => $data['comment'] ?? null,
            'approved_at' => now(),
        ]);

        LeaveApproval::create([
            'leave_request_id' => $leaveRequest->id,
            'approver_id' => $data['approver_id'],
            'action' => 'approved',
            'comment' => $data['comment'] ?? null,
            'acted_at' => now(),
        ]);

        $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', (int) Carbon::parse($leaveRequest->start_date)->format('Y'))
            ->first();

        if ($balance) {
            $balance->used_days += $leaveRequest->days_requested;
            $balance->remaining_days -= $leaveRequest->days_requested;
            $balance->save();
        }

        return response()->json(
            $leaveRequest->load(['employee', 'leaveType', 'approver', 'approvals']),
            200
        );
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $data = $request->validate([
            'approver_id' => ['required', 'exists:employees,id'],
            'comment' => ['nullable', 'string'],
        ]);

        $leaveRequest->update([
            'approver_id' => $data['approver_id'],
            'status' => 'rejected',
            'manager_comment' => $data['comment'] ?? null,
            'rejected_at' => now(),
        ]);

        LeaveApproval::create([
            'leave_request_id' => $leaveRequest->id,
            'approver_id' => $data['approver_id'],
            'action' => 'rejected',
            'comment' => $data['comment'] ?? null,
            'acted_at' => now(),
        ]);

        return response()->json(
            $leaveRequest->load(['employee', 'leaveType', 'approver', 'approvals']),
            200
        );
    }
}