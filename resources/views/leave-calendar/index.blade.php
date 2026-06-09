<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Leave Calendar
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Public out-of-office calendar. Private leave reasons are hidden.
                </p>
            </div>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('leave-calendar.index', ['month' => $previousMonth]) }}"
                   style="background:white; border:1px solid #d1d5db; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                    ‹ Previous
                </a>

                <a href="{{ route('leave-calendar.index', ['month' => now()->format('Y-m')]) }}"
                   style="background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                    Today
                </a>

                <a href="{{ route('leave-calendar.index', ['month' => $nextMonth]) }}"
                   style="background:white; border:1px solid #d1d5db; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                    Next ›
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns: minmax(0, 1fr) 320px; gap:24px;" class="calendar-layout">

        <!-- Calendar -->
        <section style="background:white; border-radius:22px; box-shadow:0 12px 30px rgba(15,23,42,.08); border:1px solid #e5e7eb; overflow:hidden;">

            <div style="display:flex; justify-content:space-between; align-items:center; padding:24px 28px; border-bottom:1px solid #e5e7eb; gap:16px; flex-wrap:wrap;">
                <div>
                    <h3 style="font-size:30px; font-weight:900; color:#111827; margin:0;">
                        {{ $currentMonth->format('F Y') }}
                    </h3>
                    <p style="color:#6b7280; font-size:14px; margin-top:6px;">
                        Approved leave is displayed as “Out of Office”.
                    </p>
                </div>

                <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:8px; color:#6b7280; font-size:13px;">
                        <span style="width:12px; height:12px; background:#2563eb; border-radius:999px; display:inline-block;"></span>
                        Out of Office
                    </div>

                    <div style="display:flex; align-items:center; gap:8px; color:#6b7280; font-size:13px;">
                        <span style="width:12px; height:12px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:999px; display:inline-block;"></span>
                        Other Month
                    </div>
                </div>
            </div>

            <!-- Day headers -->
            <div style="display:grid; grid-template-columns:repeat(7, 1fr); background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                    <div style="padding:14px 8px; text-align:center; color:#6b7280; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:.08em;">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar grid -->
            <div style="display:grid; grid-template-columns:repeat(7, 1fr);">
                @foreach ($calendarDays as $day)
                    <div style="
                        min-height:150px;
                        padding:10px;
                        border-right:1px solid #e5e7eb;
                        border-bottom:1px solid #e5e7eb;
                        background:{{ $day['is_current_month'] ? '#ffffff' : '#f9fafb' }};
                        color:{{ $day['is_current_month'] ? '#111827' : '#9ca3af' }};
                        position:relative;
                    ">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <div style="
                                width:32px;
                                height:32px;
                                border-radius:999px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-weight:900;
                                font-size:13px;
                                background:{{ $day['date']->isToday() ? '#2563eb' : 'transparent' }};
                                color:{{ $day['date']->isToday() ? '#ffffff' : 'inherit' }};
                            ">
                                {{ $day['date']->day }}
                            </div>

                            @if ($day['date']->isWeekend())
                                <span style="font-size:10px; color:#cbd5e1; font-weight:800; text-transform:uppercase;">
                                    Weekend
                                </span>
                            @endif
                        </div>

                        <div style="display:grid; gap:6px;">
                            @foreach ($day['leaves']->take(3) as $leave)
                                <div title="{{ $leave->employee->full_name }} - Out of Office"
                                     style="
                                        background:#dbeafe;
                                        color:#1e40af;
                                        border-left:4px solid #2563eb;
                                        padding:7px 8px;
                                        border-radius:9px;
                                        font-size:12px;
                                        line-height:1.2;
                                        overflow:hidden;
                                     ">
                                    <div style="font-weight:900; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $leave->employee->full_name }}
                                    </div>
                                    <div style="opacity:.85; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        Out of Office
                                    </div>
                                </div>
                            @endforeach

                            @if ($day['leaves']->count() > 3)
                                <div style="font-size:12px; color:#6b7280; padding-left:4px;">
                                    +{{ $day['leaves']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Side panel -->
        <aside style="display:grid; gap:18px; align-content:start;">

            <div style="background:#111827; color:white; border-radius:20px; padding:22px; box-shadow:0 12px 30px rgba(17,24,39,.18);">
                <h3 style="font-size:18px; font-weight:900; margin:0;">
                    Calendar Privacy
                </h3>
                <p style="color:#d1d5db; font-size:14px; line-height:1.5; margin-top:10px;">
                    This calendar only shows availability. Leave types and private reasons are not displayed to other employees.
                </p>
            </div>

            <div style="background:white; border-radius:20px; padding:22px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <h3 style="font-size:18px; font-weight:900; margin:0 0 14px;">
                    This Month
                </h3>

                @php
                    $upcoming = $calendarDays
                        ->flatMap(fn ($day) => $day['leaves'])
                        ->unique('id')
                        ->sortBy('start_date')
                        ->values();
                @endphp

                @if ($upcoming->isEmpty())
                    <p style="color:#6b7280;">No approved leave this month.</p>
                @else
                    <div style="display:grid; gap:10px;">
                        @foreach ($upcoming as $leave)
                            <div style="padding:13px; border:1px solid #e5e7eb; border-radius:14px;">
                                <div style="display:flex; justify-content:space-between; gap:10px;">
                                    <strong style="color:#111827;">{{ $leave->employee->full_name }}</strong>
                                    <span style="font-size:12px; color:#1e40af; background:#dbeafe; padding:4px 8px; border-radius:999px;">
                                        OOO
                                    </span>
                                </div>

                                <div style="color:#6b7280; font-size:13px; margin-top:6px;">
                                    {{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div style="background:white; border-radius:20px; padding:22px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <h3 style="font-size:18px; font-weight:900; margin:0 0 14px;">
                    Quick Links
                </h3>

                <div style="display:grid; gap:10px;">
                    <a href="{{ route('my-leave.index') }}"
                       style="background:#f3f4f6; color:#111827; padding:11px 13px; border-radius:12px; text-decoration:none; font-weight:800;">
                        My Leave
                    </a>

                    <a href="{{ route('manager-leave.index') }}"
                       style="background:#f3f4f6; color:#111827; padding:11px 13px; border-radius:12px; text-decoration:none; font-weight:800;">
                        Leave Approvals
                    </a>

                    <a href="{{ route('dashboard') }}"
                       style="background:#f3f4f6; color:#111827; padding:11px 13px; border-radius:12px; text-decoration:none; font-weight:800;">
                        Dashboard
                    </a>
                </div>
            </div>
        </aside>

    </div>

    <style>
        @media (max-width: 1100px) {
            .calendar-layout {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 768px) {
            .calendar-layout section > div:nth-child(3) > div {
                min-height: 115px !important;
                padding: 7px !important;
            }
        }
    </style>
</x-app-layout>