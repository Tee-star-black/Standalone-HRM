<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:22px; font-weight:700;">
                {{ $employee->full_name }}
            </h2>

            <div>
                <a href="{{ route('hr-employees.index') }}"
                   style="background:#e5e7eb; color:#111827; padding:10px 14px; border-radius:6px; text-decoration:none;">
                    Back
                </a>

                <a href="{{ route('hr-employees.edit', $employee) }}"
                   style="background:#2563eb; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Profile</h3>

                <p><strong>Employee No:</strong> {{ $employee->employee_number }}</p>
                <p><strong>Name:</strong> {{ $employee->full_name }}</p>
                <p><strong>Email:</strong> {{ $employee->email }}</p>
                <p><strong>Phone:</strong> {{ $employee->phone ?? '-' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p>
            </div>

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Employment</h3>

                <p><strong>Job Title:</strong> {{ $employee->job_title ?? '-' }}</p>
                <p><strong>Employment Type:</strong> {{ $employee->employment_type ?? '-' }}</p>
                <p><strong>Hire Date:</strong> {{ $employee->hire_date?->format('Y-m-d') ?? '-' }}</p>
                <p><strong>Manager:</strong> {{ $employee->manager?->full_name ?? '-' }}</p>
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Documents</h3>

            @if($employee->documents->isEmpty())
                <p>No documents found.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    @foreach($employee->documents as $document)
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #eee;">{{ $document->original_name }}</td>
                            <td style="padding:10px; border-bottom:1px solid #eee;">{{ $document->mime_type }}</td>
                            <td style="padding:10px; border-bottom:1px solid #eee;">{{ $document->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Payslips</h3>

                @if($employee->payslips->isEmpty())
                    <p>No payslips found.</p>
                @else
                    <table style="width:100%; border-collapse:collapse;">
                        @foreach($employee->payslips as $payslip)
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ $payslip->period }}</td>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ number_format($payslip->net_pay, 2) }}</td>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ ucfirst($payslip->status) }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Attendance</h3>

                @if($employee->attendances->isEmpty())
                    <p>No attendance records found.</p>
                @else
                    <table style="width:100%; border-collapse:collapse;">
                        @foreach($employee->attendances->take(10) as $attendance)
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ $attendance->date->format('Y-m-d') }}</td>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ $attendance->clock_in?->format('H:i') ?? '-' }}</td>
                                <td style="padding:10px; border-bottom:1px solid #eee;">{{ $attendance->clock_out?->format('H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Leave Requests</h3>

            @if($employee->leaveRequests->isEmpty())
                <p>No leave requests found.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    @foreach($employee->leaveRequests as $leave)
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #eee;">{{ $leave->leaveType?->name ?? '-' }}</td>
                            <td style="padding:10px; border-bottom:1px solid #eee;">
                                {{ $leave->start_date->format('Y-m-d') }} to {{ $leave->end_date->format('Y-m-d') }}
                            </td>
                            <td style="padding:10px; border-bottom:1px solid #eee;">{{ ucfirst($leave->status) }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>

    </div>
</x-app-layout>