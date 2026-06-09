<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Company Calendar
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Company events, holidays, training, meetings, and important HR dates.
                </p>
            </div>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('company-calendar.index', ['month' => $previousMonth]) }}"
                   style="background:white; border:1px solid #d1d5db; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    ‹ Previous
                </a>

                <a href="{{ route('company-calendar.index', ['month' => now()->format('Y-m')]) }}"
                   style="background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Today
                </a>

                <a href="{{ route('company-calendar.index', ['month' => $nextMonth]) }}"
                   style="background:white; border:1px solid #d1d5db; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Next ›
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:minmax(0, 1fr) 340px; gap:24px;" class="calendar-layout">

        <section style="background:white; border-radius:22px; box-shadow:0 12px 30px rgba(15,23,42,.08); border:1px solid #e5e7eb; overflow:hidden;">

            <div style="display:flex; justify-content:space-between; align-items:center; padding:24px 28px; border-bottom:1px solid #e5e7eb; gap:16px; flex-wrap:wrap;">
                <div>
                    <h3 style="font-size:30px; font-weight:900; color:#111827; margin:0;">
                        {{ $currentMonth->format('F Y') }}
                    </h3>
                    <p style="color:#6b7280; font-size:14px; margin-top:6px;">
                        Public company events and schedules.
                    </p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(7, 1fr); background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                    <div style="padding:14px 8px; text-align:center; color:#6b7280; font-size:12px; font-weight:900; text-transform:uppercase;">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>

            <div style="display:grid; grid-template-columns:repeat(7, 1fr);">
                @foreach ($calendarDays as $day)
                    <div style="
                        min-height:145px;
                        padding:10px;
                        border-right:1px solid #e5e7eb;
                        border-bottom:1px solid #e5e7eb;
                        background:{{ $day['is_current_month'] ? '#ffffff' : '#f9fafb' }};
                        color:{{ $day['is_current_month'] ? '#111827' : '#9ca3af' }};
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
                                background:{{ $day['date']->isToday() ? '#2563eb' : 'transparent' }};
                                color:{{ $day['date']->isToday() ? '#ffffff' : 'inherit' }};
                            ">
                                {{ $day['date']->day }}
                            </div>
                        </div>

                        <div style="display:grid; gap:6px;">
                            @foreach($day['events']->take(3) as $event)
                                <div style="
                                    background:#dbeafe;
                                    color:#1e40af;
                                    border-left:4px solid #2563eb;
                                    padding:7px 8px;
                                    border-radius:9px;
                                    font-size:12px;
                                    line-height:1.2;
                                ">
                                    <div style="font-weight:900; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $event->title }}
                                    </div>
                                    <div style="opacity:.85;">
                                        {{ str_replace('_', ' ', ucfirst($event->type)) }}
                                    </div>
                                </div>
                            @endforeach

                            @if($day['events']->count() > 3)
                                <div style="font-size:12px; color:#6b7280;">
                                    +{{ $day['events']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <aside style="display:grid; gap:18px; align-content:start;">

            @if(session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:12px;">
                    {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
                <div style="background:white; border-radius:20px; padding:22px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                    <h3 style="font-size:18px; font-weight:900; margin:0 0 14px;">
                        Create Event
                    </h3>

                    <form method="POST" action="{{ route('company-calendar.store') }}" style="display:grid; gap:10px;">
                        @csrf

                        <input name="title" required placeholder="Event title"
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <select name="type" required style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                            <option value="company_event">Company Event</option>
                            <option value="public_holiday">Public Holiday</option>
                            <option value="training">Training</option>
                            <option value="meeting">Meeting</option>
                            <option value="performance_review">Performance Review</option>
                            <option value="recruitment_interview">Recruitment Interview</option>
                        </select>

                        <input type="date" name="start_date" required
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <input type="date" name="end_date"
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <input type="time" name="start_time"
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <input type="time" name="end_time"
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <input name="location" placeholder="Location"
                               style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                        <textarea name="description" rows="4" placeholder="Description"
                                  style="padding:10px; border:1px solid #d1d5db; border-radius:10px;"></textarea>

                        <label style="display:flex; gap:8px; align-items:center; font-weight:800;">
                            <input type="checkbox" name="is_public" value="1" checked>
                            Public event
                        </label>

                        <button style="background:#2563eb; color:white; padding:11px 14px; border:0; border-radius:10px; font-weight:900;">
                            Create Event
                        </button>
                    </form>
                </div>
            @endif

            <div style="background:white; border-radius:20px; padding:22px; box-shadow:0 8px 24px rgba(15,23,42,.06); border:1px solid #e5e7eb;">
                <h3 style="font-size:18px; font-weight:900; margin:0 0 14px;">
                    This Month
                </h3>

                @if($events->isEmpty())
                    <p style="color:#6b7280;">No events this month.</p>
                @else
                    <div style="display:grid; gap:10px;">
                        @foreach($events as $event)
                            <div style="border:1px solid #e5e7eb; border-radius:14px; padding:13px;">
                                <strong>{{ $event->title }}</strong>
                                <div style="font-size:13px; color:#6b7280; margin-top:4px;">
                                    {{ $event->start_date->format('M d') }}
                                    @if($event->end_date && !$event->end_date->equalTo($event->start_date))
                                        – {{ $event->end_date->format('M d') }}
                                    @endif
                                </div>

                                <div style="font-size:12px; color:#2563eb; margin-top:4px; font-weight:800;">
                                    {{ str_replace('_', ' ', ucfirst($event->type)) }}
                                </div>

                                @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
                                    <form method="POST" action="{{ route('company-calendar.destroy', $event) }}" style="margin-top:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button style="background:#fee2e2; color:#991b1b; border:0; padding:7px 10px; border-radius:10px; font-weight:800;">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </aside>
    </div>
</x-app-layout>