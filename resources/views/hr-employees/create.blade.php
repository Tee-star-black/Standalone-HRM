<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    New Employee
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Create a new employee profile.
                </p>
            </div>

            <a href="{{ route('hr-employees.index') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Back to Employees
            </a>
        </div>
    </x-slot>

    <div style="max-width:900px; margin:0 auto;">
        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:14px; border-radius:12px; margin-bottom:18px;">
                <strong>Please fix the following:</strong>
                <ul style="margin:8px 0 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('hr-employees.store') }}"
              style="background:white; padding:26px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05); display:grid; gap:18px;">
            @csrf

            <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;" class="dashboard-grid">
                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Employee Number</label>
                    <input name="employee_number" value="{{ old('employee_number') }}" required
                           placeholder="EMP001"
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Status</label>
                    <select name="status" required
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">First Name</label>
                    <input name="first_name" value="{{ old('first_name') }}" required
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Last Name</label>
                    <input name="last_name" value="{{ old('last_name') }}" required
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Phone</label>
                    <input name="phone" value="{{ old('phone') }}"
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Job Title</label>
                    <input name="job_title" value="{{ old('job_title') }}"
                           placeholder="Practice Manager"
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Employment Type</label>
                    <select name="employment_type"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="">Select type</option>
                        <option value="full_time" {{ old('employment_type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('employment_type') === 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('employment_type') === 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="temporary" {{ old('employment_type') === 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Hire Date</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" required
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                           style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Gender</label>
                    <select name="gender"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="">Select gender</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Department</label>
                    <select name="department_id"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="">Select department</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Establishment</label>
                    <select name="establishment_id"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="">Select establishment</option>
                        @foreach($establishments ?? [] as $establishment)
                            <option value="{{ $establishment->id }}" {{ old('establishment_id') == $establishment->id ? 'selected' : '' }}>
                                {{ $establishment->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-weight:900; display:block; margin-bottom:6px;">Manager</label>
                    <select name="manager_id"
                            style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        <option value="">No manager</option>
                        @foreach($managers ?? [] as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label style="font-weight:900; display:block; margin-bottom:6px;">Address</label>
                <textarea name="address" rows="3"
                          style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">{{ old('address') }}</textarea>
            </div>

            <button style="background:#2563eb; color:white; padding:14px 16px; border:0; border-radius:12px; font-weight:900; cursor:pointer;">
                Create Employee
            </button>
        </form>
    </div>
</x-app-layout>