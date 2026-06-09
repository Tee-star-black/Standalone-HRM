<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'leaveTypes' => LeaveType::orderBy('name')->get(),
            'balances' => LeaveBalance::with('leaveType')
                ->where('employee_id', $employee->id)
                ->orderBy('leave_type_id')
                ->get(),
            'requests' => LeaveRequest::with('leaveType')
                ->where('employee_id', $employee->id)
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
            'supporting_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $leaveType = LeaveType::findOrFail($data['leave_type_id']);

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;

        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('year', now()->year)
            ->first();

        if (! $balance) {
            return back()->withErrors([
                'leave_type_id' => 'No leave balance exists for this leave type.',
            ]);
        }

        if (! $leaveType->allow_negative_balance && $days > $balance->calculated_remaining_days) {
            return back()->withErrors([
                'leave_type_id' => 'Insufficient leave balance. You only have ' . $balance->calculated_remaining_days . ' days remaining.',
            ]);
        }

        $documentRequired = $leaveType->requires_document;

        if (
            $leaveType->document_required_after_days &&
            $days >= $leaveType->document_required_after_days
        ) {
            $documentRequired = true;
        }

        if ($documentRequired && ! $request->hasFile('supporting_document')) {
            return back()->withErrors([
                'supporting_document' => 'Supporting document is required for this leave request.',
            ]);
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days' => $days,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');

            $leaveRequest->update([
                'supporting_document_path' => $file->store('leave-documents', 'public'),
                'supporting_document_original_name' => $file->getClientOriginalName(),
                'supporting_document_mime_type' => $file->getClientMimeType(),
                'supporting_document_size' => $file->getSize(),
            ]);
        }

        AuditLogger::log(
            'leave_requested',
            'Submitted leave request for ' . $leaveType->name,
            $leaveRequest,
            [
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'days' => $days,
            ]
        );

        return redirect()
            ->route('my-leave.index')
            ->with('status', 'Leave request submitted.');
    }

    public function document(LeaveRequest $leaveRequest)
    {
        $employee = Auth::user()->employee;

        if (! $employee || (int) $leaveRequest->employee_id !== (int) $employee->id) {
            abort(403);
        }

        if (! $leaveRequest->supporting_document_path || ! Storage::disk('public')->exists($leaveRequest->supporting_document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $leaveRequest->supporting_document_path,
            $leaveRequest->supporting_document_original_name
        );
    }
}