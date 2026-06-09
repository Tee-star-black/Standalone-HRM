<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Attendance
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Clock in, clock out, and track your attendance history.
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $todayAttendance = $todayAttendance ?? null;
        $attendances = $attendances ?? collect();

        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $absentCount = $attendances->where('status', 'absent')->count();

        $totalCount = max(1, $attendances->count());
        $attendanceRate = round(($presentCount / $totalCount) * 100);
    @endphp

    <div style="display:grid; gap:24px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:12px;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:14px; border-radius:12px;">
                <strong>Please fix the following:</strong>
                <ul style="margin:8px 0 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px;" class="dashboard-grid">
            <div style="background:white; padding:20px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                <div style="color:#6b7280; font-weight:900; font-size:13px;">Present</div>
                <div style="font-size:32px; font-weight:900; color:#16a34a; margin-top:8px;">{{ $presentCount }}</div>
                <div style="color:#6b7280; font-size:13px;">record(s)</div>
            </div>

            <div style="background:white; padding:20px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                <div style="color:#6b7280; font-weight:900; font-size:13px;">Late</div>
                <div style="font-size:32px; font-weight:900; color:#f59e0b; margin-top:8px;">{{ $lateCount }}</div>
                <div style="color:#6b7280; font-size:13px;">record(s)</div>
            </div>

            <div style="background:white; padding:20px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                <div style="color:#6b7280; font-weight:900; font-size:13px;">Absent</div>
                <div style="font-size:32px; font-weight:900; color:#dc2626; margin-top:8px;">{{ $absentCount }}</div>
                <div style="color:#6b7280; font-size:13px;">record(s)</div>
            </div>

            <div style="background:white; padding:20px; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                <div style="color:#6b7280; font-weight:900; font-size:13px;">Attendance Rate</div>
                <div style="font-size:32px; font-weight:900; color:#2563eb; margin-top:8px;">{{ $attendanceRate }}%</div>
                <div style="color:#6b7280; font-size:13px;">based on records</div>
            </div>
        </section>

        <section style="display:grid; grid-template-columns:380px 1fr; gap:24px;" class="dashboard-grid">

            <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05); align-self:start;">
                <h3 style="font-size:21px; font-weight:900; color:#111827; margin:0 0 16px;">
                    Today
                </h3>

                <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:18px; padding:18px;">
                    <div style="font-size:13px; color:#6b7280; font-weight:900;">
                        {{ now()->format('l, d M Y') }}
                    </div>

                    <div style="font-size:36px; font-weight:900; color:#111827; margin-top:8px;">
                        {{ now()->format('H:i') }}
                    </div>

                    @if($todayAttendance)
                        <div style="display:grid; gap:10px; margin-top:18px;">
                            <div>
                                <div style="font-size:12px; color:#6b7280; font-weight:900;">Clock In</div>
                                <div style="font-size:18px; font-weight:900; color:#111827;">
                                    {{ $todayAttendance->clock_in ? \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') : 'Not clocked in' }}
                                </div>
                            </div>

                            <div>
                                <div style="font-size:12px; color:#6b7280; font-weight:900;">Clock Out</div>
                                <div style="font-size:18px; font-weight:900; color:#111827;">
                                    {{ $todayAttendance->clock_out ? \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') : 'Not clocked out' }}
                                </div>
                            </div>

                            <div>
                                <div style="font-size:12px; color:#6b7280; font-weight:900;">Status</div>
                                <div style="font-size:18px; font-weight:900; color:#2563eb;">
                                    {{ ucfirst($todayAttendance->status ?? 'present') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <p style="color:#6b7280; margin-top:14px;">
                            You have not clocked in today.
                        </p>
                    @endif
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:16px;">
                    <form method="POST" action="{{ route('attendance.clock-in') }}">
                        @csrf
                        <button style="width:100%; background:#16a34a; color:white; padding:12px 14px; border:0; border-radius:12px; font-weight:900; cursor:pointer;">
                            Clock In
                        </button>
                    </form>

                    <form method="POST" action="{{ route('attendance.clock-out') }}">
                        @csrf
                        <button style="width:100%; background:#111827; color:white; padding:12px 14px; border:0; border-radius:12px; font-weight:900; cursor:pointer;">
                            Clock Out
                        </button>
                    </form>
                </div>
            </div>

            <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                <h3 style="font-size:21px; font-weight:900; color:#111827; margin:0 0 16px;">
                    Recent Attendance
                </h3>

                @if($attendances->isEmpty())
                    <p style="color:#6b7280;">No attendance records yet.</p>
                @else
                    <div style="display:grid; gap:12px;">
                        @foreach($attendances->take(10) as $attendance)
                            @php
                                $status = $attendance->status ?? 'present';

                                $statusColors = [
                                    'present' => ['#dcfce7', '#166534'],
                                    'late' => ['#fef3c7', '#92400e'],
                                    'absent' => ['#fee2e2', '#991b1b'],
                                ];

                                [$bg, $fg] = $statusColors[$status] ?? ['#eff6ff', '#1e40af'];

                                $clockIn = $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in) : null;
                                $clockOut = $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out) : null;

                                $hours = $attendance->total_minutes
                                    ? $attendance->total_minutes / 60
                                    : null;
                            @endphp

                            <div style="border:1px solid #e5e7eb; border-radius:16px; padding:14px; display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                                <div>
                                    <div style="font-weight:900; color:#111827;">
                                        {{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('d M Y') : $attendance->created_at->format('d M Y') }}
                                    </div>

                                    <div style="font-size:13px; color:#6b7280; margin-top:4px;">
                                        In:
                                        <strong>{{ $clockIn ? $clockIn->format('H:i') : '-' }}</strong>
                                        · Out:
                                        <strong>{{ $clockOut ? $clockOut->format('H:i') : '-' }}</strong>

                                        @if($hours)
                                            · {{ number_format($hours, 2) }} hrs
                                        @endif
                                    </div>
                                </div>

                                <span style="background:{{ $bg }}; color:{{ $fg }}; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:900;">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>