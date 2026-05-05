<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:22px; font-weight:700;">
            My Attendance
        </h2>
    </x-slot>

    <div style="padding:20px; display:grid; gap:20px;">

        @if (session('status'))
            <div style="background:#dcfce7; padding:10px; border-radius:6px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="background:white; padding:20px; border-radius:10px;">
            <h3 style="margin-bottom:10px;">Today</h3>

            <p><strong>Date:</strong> {{ $today->date }}</p>
            <p><strong>Clock In:</strong> {{ $today->clock_in ?? '-' }}</p>
            <p><strong>Clock Out:</strong> {{ $today->clock_out ?? '-' }}</p>

            <div style="margin-top:10px;">
                @if (! $today->clock_in)
                    <form method="POST" action="{{ route('attendance.clock-in') }}">
                        @csrf
                        <button style="background:#2563eb; color:white; padding:10px;">
                            Clock In
                        </button>
                    </form>
                @elseif (! $today->clock_out)
                    <form method="POST" action="{{ route('attendance.clock-out') }}">
                        @csrf
                        <button style="background:#16a34a; color:white; padding:10px;">
                            Clock Out
                        </button>
                    </form>
                @else
                    <span style="color:#16a34a;">Completed</span>
                @endif
            </div>
        </div>

        <div style="background:white; padding:20px; border-radius:10px;">
            <h3>Recent Attendance</h3>

            <table style="width:100%; margin-top:10px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->clock_in ?? '-' }}</td>
                            <td>{{ $row->clock_out ?? '-' }}</td>
                            <td>
                                @if ($row->total_minutes)
                                    {{ round($row->total_minutes / 60, 2) }} hrs
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>