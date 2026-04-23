<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return Position::with(['employee', 'job', 'department', 'establishment'])->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['nullable', 'exists:employees,id'],
            'job_id' => ['required', 'exists:hr_jobs,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:100'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $position = Position::create($data);

        return response()->json(
            $position->load(['employee', 'job', 'department', 'establishment']),
            201
        );
    }

    public function show(Position $position)
    {
        return $position->load(['employee', 'job', 'department', 'establishment']);
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'employee_id' => ['nullable', 'exists:employees,id'],
            'job_id' => ['sometimes', 'exists:hr_jobs,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:100'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $position->update($data);

        return $position->load(['employee', 'job', 'department', 'establishment']);
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return response()->json(['message' => 'Position deleted']);
    }
}