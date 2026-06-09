<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:22px; font-weight:700;">Edit Employee</h2>
    </x-slot>

    <div style="padding:20px; max-width:600px;">

        <form method="POST" action="{{ route('hr-employees.update', $employee) }}" style="display:grid; gap:12px;">
            @csrf

            <input name="first_name" value="{{ $employee->first_name }}" placeholder="First name">
            <input name="last_name" value="{{ $employee->last_name }}" placeholder="Last name">
            <input name="email" value="{{ $employee->email }}" placeholder="Email">
            <input name="phone" value="{{ $employee->phone }}" placeholder="Phone">

            <input name="job_title" value="{{ $employee->job_title }}" placeholder="Job Title">
            <input name="employment_type" value="{{ $employee->employment_type }}" placeholder="Employment Type">

            <select name="status">
                <option value="active" @selected($employee->status === 'active')>Active</option>
                <option value="inactive" @selected($employee->status === 'inactive')>Inactive</option>
            </select>

            <button style="background:#2563eb; color:white; padding:10px;">
                Save Changes
            </button>
        </form>

    </div>
</x-app-layout>