<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:22px; font-weight:700;">
            HR Attendance Report
        </h2>
    </x-slot>

    <div style="display:grid; gap:24px;">
        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">
                Filters
            </h3>

            <form method="GET" action="{{ route('hr-attendance.index') }}" style="display:grid; grid-template-columns:1fr 1fr auto; gap:12px; align-items:end;">
                <div>
                    <label style="font-weight:600;">Employee</label>
                    <select name="employee_id" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        <option value="">All employees</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected((string) $selectedEmployeeId === (string) $employee->id)>
                                {{ $employee->employee_number }} - {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-weight:600;">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}"
                           style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                </div>

                <button type="submit"
                        style="background:#1f2937; color:white; padding:11px 18px; border-radius:6px; border:0; font-weight:600;">
                    Filter
                </button>
            </form>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">
                Attendance Records
            </h3>

            @if ($attendances->isEmpty())
                <p>No attendance records found.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Employee</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Date</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Clock In</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Clock Out</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Hours</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td style="padding:10px;">
                                    {{ $attendance->employee->full_name }}
                                </td>
                                <td style="padding:10px;">
                                    {{ $attendance->date->format('Y-m-d') }}
                                </td>
                                <td style="padding:10px;">
                                    {{ $attendance->clock_in?->format('H:i') ?? '-' }}
                                </td>
                                <td style="padding:10px;">
                                    {{ $attendance->clock_out?->format('H:i') ?? '-' }}
                                </td>
                                <td style="padding:10px;">
                                    @if ($attendance->total_minutes)
                                        {{ round($attendance->total_minutes / 60, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="padding:10px;">
                                    {{ ucfirst($attendance->status) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>