<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnboardEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],

            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'manager_id' => ['nullable', 'exists:employees,id'],
            'job_id' => ['nullable', 'exists:hr_jobs,id'],
            'job_title' => ['nullable', 'string', 'max:255'],

            'role' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8'],
            'send_welcome_email' => ['nullable', 'boolean'],
        ];
    }
}