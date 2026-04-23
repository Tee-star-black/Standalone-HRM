<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function index()
    {
        return LeaveBalance::with(['employee', 'leaveType'])->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'year' => ['required', 'integer'],
            'allocated_days' => ['nullable', 'numeric', 'min:0'],
            'used_days' => ['nullable', 'numeric', 'min:0'],
            'carried_forward_days' => ['nullable', 'numeric', 'min:0'],
            'remaining_days' => ['nullable', 'numeric', 'min:0'],
        ]);

        $balance = LeaveBalance::create($data);

        return response()->json($balance->load(['employee', 'leaveType']), 201);
    }

    public function show(LeaveBalance $leaveBalance)
    {
        return $leaveBalance->load(['employee', 'leaveType']);
    }

    public function update(Request $request, LeaveBalance $leaveBalance)
    {
        $data = $request->validate([
            'allocated_days' => ['nullable', 'numeric', 'min:0'],
            'used_days' => ['nullable', 'numeric', 'min:0'],
            'carried_forward_days' => ['nullable', 'numeric', 'min:0'],
            'remaining_days' => ['nullable', 'numeric', 'min:0'],
        ]);

        $leaveBalance->update($data);

        return $leaveBalance->load(['employee', 'leaveType']);
    }

    public function destroy(LeaveBalance $leaveBalance)
    {
        $leaveBalance->delete();

        return response()->json(['message' => 'Leave balance deleted']);
    }
}