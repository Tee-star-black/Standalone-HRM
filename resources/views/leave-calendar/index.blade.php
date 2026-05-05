<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Leave Calendar
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Approved leave schedule
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('leave-calendar.index', ['month' => $previousMonth]) }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ‹
                </a>

                <a href="{{ route('leave-calendar.index', ['month' => now()->format('Y-m')]) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-semibold text-white hover:bg-blue-700">
                    Today
                </a>

                <a href="{{ route('leave-calendar.index', ['month' => $nextMonth]) }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ›
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $currentMonth->format('F Y') }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Leave overview by month
                        </p>
                    </div>

                    <div class="hidden md:flex items-center gap-3 text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-blue-500"></span>
                            <span>Approved leave</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-gray-300"></span>
                            <span>Other month</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                    @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                        <div class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7">
                    @foreach ($calendarDays as $day)
                        <div class="relative min-h-36 border-r border-b border-gray-100 p-2
                            {{ $day['is_current_month'] ? 'bg-white' : 'bg-gray-50' }}
                            hover:bg-blue-50/40 transition
                        ">
                            <div class="flex items-center justify-between mb-2">
                                <div class="
                                    h-7 w-7 flex items-center justify-center rounded-full text-sm font-semibold
                                    {{ $day['date']->isToday()
                                        ? 'bg-blue-600 text-white'
                                        : ($day['is_current_month'] ? 'text-gray-800' : 'text-gray-400') }}
                                ">
                                    {{ $day['date']->day }}
                                </div>

                                @if ($day['date']->isWeekend())
                                    <span class="hidden lg:inline text-[10px] uppercase tracking-wide text-gray-300">
                                        Weekend
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-1">
                                @foreach ($day['leaves']->take(3) as $leave)
                                    <div class="group rounded-md bg-blue-100 border border-blue-200 px-2 py-1 text-xs text-blue-800 shadow-sm">
                                        <div class="font-semibold truncate">
                                            {{ $leave->employee->full_name }}
                                        </div>
                                        <div class="truncate text-blue-600">
                                            {{ $leave->leaveType->name }}
                                        </div>
                                    </div>
                                @endforeach

                                @if ($day['leaves']->count() > 3)
                                    <div class="text-xs text-gray-500 px-2">
                                        +{{ $day['leaves']->count() - 3 }} more
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Upcoming Approved Leave</h4>

                    @php
                        $upcoming = $calendarDays
                            ->flatMap(fn ($day) => $day['leaves'])
                            ->unique('id')
                            ->sortBy('start_date')
                            ->values();
                    @endphp

                    @if ($upcoming->isEmpty())
                        <p class="text-sm text-gray-500">No approved leave in this month.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($upcoming as $leave)
                                <div class="flex items-center justify-between rounded-xl border border-gray-100 p-4 hover:bg-gray-50">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $leave->employee->full_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $leave->leaveType->name }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $leave->start_date->format('M d') }}
                                            –
                                            {{ $leave->end_date->format('M d') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $leave->days_requested }} day(s)
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Calendar Notes</h4>

                    <div class="space-y-3 text-sm text-gray-600">
                        <p>
                            This calendar shows only approved leave requests.
                        </p>
                        <p>
                            Pending requests appear in Manager Approvals.
                        </p>
                        <p>
                            Weekend days are marked but still shown for visibility.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>