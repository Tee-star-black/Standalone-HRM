<?php

namespace App\Services;

use App\Mail\EmployeeWelcomeMail;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployeeOnboardingService
{
    public function onboard(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $temporaryPassword = $data['password'] ?? Str::password(12);

            $user = User::create([
                'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                'email' => $data['email'],
                'password' => Hash::make($temporaryPassword),
            ]);

            if (! empty($data['role']) && method_exists($user, 'assignRole')) {
                $user->assignRole($data['role']);
            }

            $employee = Employee::create([
                'user_id' => $user->id,
                'establishment_id' => $data['establishment_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'employee_number' => $this->generateEmployeeNumber(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'hire_date' => $data['hire_date'] ?? now()->toDateString(),
                'gender' => $data['gender'] ?? null,
                'employment_type' => $data['employment_type'] ?? 'Full-time',
                'status' => 'active',
                'job_title' => $data['job_title'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            if (! empty($data['job_id'])) {
                Position::create([
                    'employee_id' => $employee->id,
                    'job_id' => $data['job_id'],
                    'department_id' => $data['department_id'] ?? null,
                    'establishment_id' => $data['establishment_id'] ?? null,
                    'title' => $data['job_title'] ?? 'Employee',
                    'start_date' => $data['hire_date'] ?? now()->toDateString(),
                    'status' => 'active',
                    'is_primary' => true,
                ]);
            }

            $this->createLeaveBalances($employee);

            if (($data['send_welcome_email'] ?? true) === true) {
                Mail::to($user->email)->send(new EmployeeWelcomeMail($employee, $temporaryPassword));
            }

            return [
                'user' => $user,
                'employee' => $employee->load(['department', 'establishment', 'manager']),
                'temporary_password' => $temporaryPassword,
            ];
        });
    }

    private function generateEmployeeNumber(): string
    {
        $nextId = (Employee::max('id') ?? 0) + 1;

        do {
            $employeeNumber = 'EMP' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            $nextId++;
        } while (Employee::where('employee_number', $employeeNumber)->exists());

        return $employeeNumber;
    }

    private function createLeaveBalances(Employee $employee): void
    {
        $year = (int) now()->format('Y');

        LeaveType::where('is_active', true)->get()->each(function (LeaveType $leaveType) use ($employee, $year) {
            LeaveBalance::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $year,
                ],
                [
                    'allocated_days' => $leaveType->default_days,
                    'used_days' => 0,
                    'carried_forward_days' => 0,
                    'remaining_days' => $leaveType->default_days,
                ]
            );
        });
    }
}