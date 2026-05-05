<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyLeaveController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        return view('my-leave.index', [
            'employee' => $employee,
            'leaveTypes' => LeaveType::where('is_active', true)->get(),
            'balances' => $employee->leaveBalances()->with('leaveType')->get(),
            'requests' => $employee->leaveRequests()
                ->with('leaveType', 'approver')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $data = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string'],
        ]);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        $daysRequested = 0;
        
        $current = $start->copy();
        
        while ($current->lte($end)) {
            if (! $current->isWeekend()) {
                $daysRequested++;
            }
            $current->addDay();
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_requested' => $daysRequested,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('my-leave.index')
            ->with('status', 'Leave request submitted successfully.');
    }
}