<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Establishment;
use Illuminate\Http\Request;
use App\Services\AuditLogger;

class HrEmployeeController extends Controller
{
    public function index()
    {
        return view('hr-employees.index', [
            'employees' => Employee::latest()->get(),
        ]);
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'documents',
            'payslips',
            'leaveRequests.leaveType',
            'attendances',
            'emergencyContacts',
        ]);

        return view('hr-employees.show', [
            'employee' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        return view('hr-employees.edit', [
            'employee' => $employee,
        ]);
    }

    public function create()
{
    return view('hr-employees.create', [
        'departments' => Department::orderBy('name')->get(),
        'establishments' => Establishment::orderBy('name')->get(),
        'managers' => Employee::orderBy('first_name')->get(),
    ]);
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
        'hire_date' => ['required', 'date'],
        'gender' => ['nullable', 'string', 'max:255'],
        'employment_type' => ['nullable', 'string', 'max:255'],
        'status' => ['required', 'string', 'max:255'],
        'job_title' => ['nullable', 'string', 'max:255'],
        'department_id' => ['nullable', 'exists:departments,id'],
        'establishment_id' => ['nullable', 'exists:establishments,id'],
        'manager_id' => ['nullable', 'exists:employees,id'],
        'address' => ['nullable', 'string'],
    ]);

    $employee = Employee::create($data);

    return redirect()
        ->route('hr-employees.show', $employee)
        ->with('status', 'Employee created successfully.');
}

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['nullable'],
            'job_title' => ['nullable'],
            'employment_type' => ['nullable'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $employee->update($data);

                AuditLogger::log(
            'employee_updated',
            'Updated employee profile for ' . $employee->full_name,
            $employee,
            [
                'employee_id' => $employee->id,
                'employee_number' => $employee->employee_number,
                'updated_fields' => array_keys($data),
            ]
        );
        return redirect()
            ->route('hr-employees.show', $employee)
            ->with('status', 'Employee updated.');
    }
}