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
}