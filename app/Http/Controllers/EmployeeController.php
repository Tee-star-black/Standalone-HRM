<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return Employee::with(['department', 'establishment', 'manager'])->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_number' => ['required', 'string', 'max:255', 'unique:employees,employee_number'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
        ]);

        $employee = Employee::create($data);

        return response()->json(
            $employee->load(['department', 'establishment', 'manager']),
            201
        );
    }

    public function show(Employee $employee)
    {
        return $employee->load([
            'department',
            'establishment',
            'manager',
            'positions.job',
            'skills',
            'evaluations',
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_number' => ['sometimes', 'string', 'max:255', 'unique:employees,employee_number,' . $employee->id],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:employees,email,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
        ]);

        $employee->update($data);

        return $employee->load(['department', 'establishment', 'manager']);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }

    public function attachSkill(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency_level' => ['required', 'integer', 'min:1', 'max:10'],
            'assessed_at' => ['nullable', 'date'],
        ]);

        $employee->skills()->syncWithoutDetaching([
            $data['skill_id'] => [
                'proficiency_level' => $data['proficiency_level'],
                'assessed_at' => $data['assessed_at'] ?? null,
            ],
        ]);

        return response()->json(
            $employee->load('skills'),
            200
        );
    }

    public function skillGap(Employee $employee)
    {
        $employee->load(['positions.job.skills', 'skills']);

        $currentPosition = $employee->positions()
            ->where('is_primary', true)
            ->with('job.skills')
            ->latest()
            ->first();

        if (! $currentPosition || ! $currentPosition->job) {
            return response()->json([
                'message' => 'Employee has no primary position with a linked job.',
            ], 404);
        }

        $job = $currentPosition->job;

        $employeeSkills = $employee->skills->keyBy('id');

        $gapReport = $job->skills->map(function ($jobSkill) use ($employeeSkills) {
            $employeeSkill = $employeeSkills->get($jobSkill->id);

            $requiredLevel = (int) $jobSkill->pivot->required_level;
            $currentLevel = $employeeSkill ? (int) $employeeSkill->pivot->proficiency_level : 0;
            $gap = max($requiredLevel - $currentLevel, 0);

            return [
                'skill_id' => $jobSkill->id,
                'skill_name' => $jobSkill->name,
                'required_level' => $requiredLevel,
                'employee_level' => $currentLevel,
                'gap' => $gap,
                'meets_requirement' => $gap === 0,
            ];
        });

        return response()->json([
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->full_name,
            ],
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
            ],
            'gaps' => $gapReport,
        ]);
    }
}