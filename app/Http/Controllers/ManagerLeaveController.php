<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\AuditLogger;

class ManagerLeaveController extends Controller
{
    public function index()
    {
        return view('manager-leave.index', [
            'requests' => LeaveRequest::with(['employee', 'leaveType'])
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
        ]);

        AuditLogger::log(
            'leave_approved',
            'Approved leave request for ' . $leaveRequest->employee->full_name,
            $leaveRequest,
            [
                'employee_id' => $leaveRequest->employee_id,
                'leave_request_id' => $leaveRequest->id,
                'status' => 'approved',
            ]
        );

        return redirect()
            ->route('manager-leave.index')
            ->with('status', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
        ]);

        AuditLogger::log(
            'leave_rejected',
            'Rejected leave request for ' . $leaveRequest->employee->full_name,
            $leaveRequest,
            [
                'employee_id' => $leaveRequest->employee_id,
                'leave_request_id' => $leaveRequest->id,
                'status' => 'rejected',
            ]
        );

        return redirect()
            ->route('manager-leave.index')
            ->with('status', 'Leave request rejected.');
    }
}