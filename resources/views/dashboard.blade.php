<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:800; color:#111827; margin:0;">
                    HR Command Center
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    A quick overview of people, leave, payroll, recruitment, attendance, and announcements.
                </p>
            </div>

            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <a href="{{ route('attendance.index') }}"
                   style="background:#2563eb; color:white; padding:11px 15px; border-radius:10px; text-decoration:none; font-weight:700;">
                    Clock In / Out
                </a>

                <span style="background:white; color:#374151; border:1px solid #e5e7eb; padding:11px 15px; border-radius:10px; font-weight:700;">
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:18px;">
            <div style="background:white; padding:22px; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0; font-size:14px;">Employees</p>
                <h3 style="font-size:34px; font-weight:900; margin:8px 0 0;">{{ $employeesCount }}</h3>
                <p style="color:#16a34a; font-size:13px; margin-top:12px;">
                    {{ $activeEmployeesCount }} active employees
                </p>
            </div>

            <div style="background:white; padding:22px; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0; font-size:14px;">Leave Pending</p>
                <h3 style="font-size:34px; font-weight:900; margin:8px 0 0;">{{ $pendingLeaveCount }}</h3>
                <p style="color:#6b7280; font-size:13px; margin-top:12px;">
                    {{ $approvedLeaveCount }} approved requests
                </p>
            </div>

            <div style="background:white; padding:22px; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0; font-size:14px;">Attendance Today</p>
                <h3 style="font-size:34px; font-weight:900; margin:8px 0 0;">{{ $todayAttendanceCount }}</h3>
                <p style="color:#6b7280; font-size:13px; margin-top:12px;">
                    Clock-in records today
                </p>
            </div>

            <div style="background:white; padding:22px; border-radius:18px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0; font-size:14px;">Open Vacancies</p>
                <h3 style="font-size:34px; font-weight:900; margin:8px 0 0;">{{ $openVacanciesCount }}</h3>
                <p style="color:#6b7280; font-size:13px; margin-top:12px;">
                    {{ $activeApplicationsCount }} active applications
                </p>
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:16px;">
                <div>
                    <h3 style="font-size:20px; font-weight:900; margin:0; color:#111827;">
                        Company Announcements
                    </h3>
                    <p style="color:#6b7280; margin-top:4px;">
                        Latest company updates and internal notices.
                    </p>
                </div>

                <a href="{{ route('announcements.index') }}"
                   style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    View All
                </a>
            </div>

            @if($recentAnnouncements->isEmpty())
                <p style="color:#6b7280;">No announcements yet.</p>
            @else
                <div style="display:grid; gap:12px;">
                    @foreach($recentAnnouncements as $announcement)
                        <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:16px;">
                            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                <div>
                                    @if($announcement->is_pinned)
                                        <span style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:999px; font-size:11px; font-weight:900;">
                                            Pinned
                                        </span>
                                    @endif

                                    <h4 style="font-size:17px; font-weight:900; margin:8px 0 4px; color:#111827;">
                                        {{ $announcement->title }}
                                    </h4>

                                    <p style="color:#6b7280; margin:0; line-height:1.5;">
                                        {{ \Illuminate\Support\Str::limit($announcement->body, 140) }}
                                    </p>
                                </div>

                                <div style="font-size:12px; color:#9ca3af; white-space:nowrap;">
                                    {{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:16px;">
        <div>
            <h3 style="font-size:20px; font-weight:900; margin:0; color:#111827;">
                Upcoming Company Events
            </h3>
            <p style="color:#6b7280; margin-top:4px;">
                Meetings, training, holidays, and important HR dates.
            </p>
        </div>

        <a href="{{ route('company-calendar.index') }}"
           style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
            Open Calendar
        </a>
    </div>

    @if($upcomingCompanyEvents->isEmpty())
        <p style="color:#6b7280;">No upcoming company events.</p>
    @else
        <div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:12px;" class="dashboard-grid">
            @foreach($upcomingCompanyEvents as $event)
                <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:16px; padding:16px;">
                    <div style="font-size:12px; color:#2563eb; font-weight:900; text-transform:uppercase;">
                        {{ str_replace('_', ' ', $event->type) }}
                    </div>

                    <h4 style="font-size:16px; font-weight:900; margin:8px 0 6px; color:#111827;">
                        {{ $event->title }}
                    </h4>

                    <div style="color:#6b7280; font-size:13px;">
                        {{ $event->start_date->format('M d, Y') }}
                    </div>

                    @if($event->location)
                        <div style="color:#9ca3af; font-size:12px; margin-top:6px;">
                            {{ $event->location }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
        <div style="background:#111827; color:white; padding:24px; border-radius:20px; box-shadow:0 12px 30px rgba(17,24,39,.18);">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <div>
                    <h3 style="font-size:20px; font-weight:800; margin:0;">Quick Actions</h3>
                    <p style="color:#9ca3af; margin-top:5px;">Jump into common HR tasks.</p>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="{{ route('hr-employees.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Employees
                    </a>

                    <a href="{{ route('manager-leave.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Leave Approvals
                    </a>

                    <a href="{{ route('recruitment.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Recruitment
                    </a>

                    <a href="{{ route('hr-payslips.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Payroll
                    </a>

                    <a href="{{ route('hr-attendance.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Attendance
                    </a>

                    <a href="{{ route('hr-reports.index') }}" style="background:white; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                        Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:18px;">
            <div style="background:white; padding:18px; border-radius:16px; border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0;">Documents</p>
                <strong style="font-size:28px;">{{ $documentsCount }}</strong>
            </div>

            <div style="background:white; padding:18px; border-radius:16px; border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0;">Published Payslips</p>
                <strong style="font-size:28px;">{{ $publishedPayslipsCount }}</strong>
            </div>

            <div style="background:white; padding:18px; border-radius:16px; border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0;">Candidates</p>
                <strong style="font-size:28px;">{{ $candidatesCount }}</strong>
            </div>

            <div style="background:white; padding:18px; border-radius:16px; border:1px solid #e5e7eb;">
                <p style="color:#6b7280; margin:0;">Applications</p>
                <strong style="font-size:28px;">{{ $activeApplicationsCount }}</strong>
            </div>
        </div>

        <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:24px;">

            <div style="background:white; padding:24px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 6px 18px rgba(15,23,42,.04);">
                <h3 style="font-size:18px; font-weight:800; margin-bottom:16px;">Recent Employees</h3>

                @forelse($recentEmployees as $employee)
                    <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div>
                            <strong>{{ $employee->full_name }}</strong>
                            <p style="color:#6b7280; font-size:13px; margin:4px 0 0;">{{ $employee->job_title ?? 'No job title' }}</p>
                        </div>
                        <span style="font-size:12px; color:#166534; background:#dcfce7; padding:5px 9px; border-radius:999px; align-self:start;">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                @empty
                    <p style="color:#6b7280;">No employees yet.</p>
                @endforelse
            </div>

            <div style="background:white; padding:24px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 6px 18px rgba(15,23,42,.04);">
                <h3 style="font-size:18px; font-weight:800; margin-bottom:16px;">Recent Leave Requests</h3>

                @forelse($recentLeaveRequests as $leave)
                    <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div>
                            <strong>{{ $leave->employee->full_name }}</strong>
                            <p style="color:#6b7280; font-size:13px; margin:4px 0 0;">{{ $leave->leaveType?->name ?? 'Leave' }}</p>
                        </div>
                        <span style="font-size:12px; color:#92400e; background:#fef3c7; padding:5px 9px; border-radius:999px; align-self:start;">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                @empty
                    <p style="color:#6b7280;">No leave requests yet.</p>
                @endforelse
            </div>

            <div style="background:white; padding:24px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 6px 18px rgba(15,23,42,.04);">
                <h3 style="font-size:18px; font-weight:800; margin-bottom:16px;">Recent Applications</h3>

                @forelse($recentApplications as $application)
                    <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div>
                            <strong>{{ $application->candidate->full_name }}</strong>
                            <p style="color:#6b7280; font-size:13px; margin:4px 0 0;">{{ $application->vacancy->title }}</p>
                        </div>
                        <span style="font-size:12px; color:#1e40af; background:#dbeafe; padding:5px 9px; border-radius:999px; align-self:start;">
                            {{ ucfirst($application->stage) }}
                        </span>
                    </div>
                @empty
                    <p style="color:#6b7280;">No applications yet.</p>
                @endforelse
            </div>

            <div style="background:white; padding:24px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 6px 18px rgba(15,23,42,.04);">
                <h3 style="font-size:18px; font-weight:800; margin-bottom:16px;">Recent Attendance</h3>

                @forelse($recentAttendances as $attendance)
                    <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div>
                            <strong>{{ $attendance->employee->full_name }}</strong>
                            <p style="color:#6b7280; font-size:13px; margin:4px 0 0;">{{ $attendance->date->format('Y-m-d') }}</p>
                        </div>
                        <span style="font-size:12px; color:#374151; background:#f3f4f6; padding:5px 9px; border-radius:999px; align-self:start;">
                            {{ $attendance->clock_in?->format('H:i') ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p style="color:#6b7280;">No attendance records yet.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>