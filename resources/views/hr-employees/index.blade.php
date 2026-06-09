<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Employees
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Manage employee records and profiles.
                </p>
            </div>

            <a href="{{ route('hr-employees.create') }}"
               style="background:#2563eb; color:white; padding:11px 16px; border-radius:12px; text-decoration:none; font-weight:900;">
                + New Employee
            </a>
        </div>
    </x-slot>

    <div style="display:grid; gap:20px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
                <div>
                    <h3 style="font-size:20px; font-weight:900; margin:0; color:#111827;">
                        Employee Directory
                    </h3>
                    <p style="color:#6b7280; margin-top:4px;">
                        View, edit, and open employee profiles.
                    </p>
                </div>

                <div style="background:#f9fafb; padding:9px 12px; border-radius:999px; color:#374151; font-weight:800;">
                    {{ $employees->count() }} employee(s)
                </div>
            </div>

            @if($employees->isEmpty())
                <div style="background:#f9fafb; padding:24px; border-radius:16px; border:1px dashed #d1d5db; text-align:center;">
                    <strong>No employees found.</strong>
                    <p style="color:#6b7280; margin-top:6px;">Create your first employee profile.</p>

                    <a href="{{ route('hr-employees.create') }}"
                       style="display:inline-block; margin-top:12px; background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:900;">
                        + New Employee
                    </a>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; min-width:760px;">
                        <thead>
                            <tr>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Employee No</th>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Name</th>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Email</th>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Job</th>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Status</th>
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($employees as $emp)
                                <tr>
                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6; font-weight:800;">
                                        {{ $emp->employee_number }}
                                    </td>

                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6;">
                                        <strong>{{ $emp->full_name }}</strong>
                                    </td>

                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6; color:#6b7280;">
                                        {{ $emp->email }}
                                    </td>

                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6;">
                                        {{ $emp->job_title ?? '-' }}
                                    </td>

                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6;">
                                        @if($emp->status === 'active')
                                            <span style="background:#dcfce7; color:#166534; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:900;">
                                                Active
                                            </span>
                                        @else
                                            <span style="background:#fee2e2; color:#991b1b; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:900;">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td style="padding:12px; border-bottom:1px solid #f3f4f6;">
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            <a href="{{ route('hr-employees.show', $emp) }}"
                                               style="background:#eff6ff; color:#1e40af; padding:7px 10px; border-radius:10px; text-decoration:none; font-weight:800; font-size:13px;">
                                                View
                                            </a>

                                            <a href="{{ route('hr-employees.edit', $emp) }}"
                                               style="background:#dcfce7; color:#166534; padding:7px 10px; border-radius:10px; text-decoration:none; font-weight:800; font-size:13px;">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>