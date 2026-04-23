<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        return LeaveType::paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:leave_types,code'],
            'description' => ['nullable', 'string'],
            'default_days' => ['nullable', 'integer', 'min:0'],
            'requires_attachment' => ['nullable', 'boolean'],
            'is_paid' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $leaveType = LeaveType::create($data);

        return response()->json($leaveType, 201);
    }

    public function show(LeaveType $leaveType)
    {
        return $leaveType;
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50', 'unique:leave_types,code,' . $leaveType->id],
            'description' => ['nullable', 'string'],
            'default_days' => ['nullable', 'integer', 'min:0'],
            'requires_attachment' => ['nullable', 'boolean'],
            'is_paid' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $leaveType->update($data);

        return $leaveType;
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return response()->json(['message' => 'Leave type deleted']);
    }
}