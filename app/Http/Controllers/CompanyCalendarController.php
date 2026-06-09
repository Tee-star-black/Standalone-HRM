<?php

namespace App\Http\Controllers;

use App\Models\CompanyEvent;
use App\Models\HrNotification;
use App\Models\User;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyCalendarController extends Controller
{
    public function index(Request $request)
    {
        $monthInput = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();

        $startCalendar = $currentMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endCalendar = $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $events = CompanyEvent::where(function ($query) use ($startCalendar, $endCalendar) {
                $query->whereBetween('start_date', [$startCalendar->toDateString(), $endCalendar->toDateString()])
                    ->orWhereBetween('end_date', [$startCalendar->toDateString(), $endCalendar->toDateString()])
                    ->orWhere(function ($q) use ($startCalendar, $endCalendar) {
                        $q->where('start_date', '<=', $startCalendar->toDateString())
                          ->where('end_date', '>=', $endCalendar->toDateString());
                    });
            })
            ->orderBy('start_date')
            ->get();

        $calendarDays = collect();

        for ($date = $startCalendar->copy(); $date->lte($endCalendar); $date->addDay()) {
            $dayEvents = $events->filter(function ($event) use ($date) {
                $eventEnd = $event->end_date ?: $event->start_date;

                return $date->betweenIncluded(
                    Carbon::parse($event->start_date),
                    Carbon::parse($eventEnd)
                );
            });

            $calendarDays->push([
                'date' => $date->copy(),
                'is_current_month' => $date->month === $currentMonth->month,
                'events' => $dayEvents,
            ]);
        }

        return view('company-calendar.index', [
            'currentMonth' => $currentMonth,
            'previousMonth' => $currentMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $currentMonth->copy()->addMonth()->format('Y-m'),
            'calendarDays' => $calendarDays,
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_public' => ['nullable'],
        ]);

        $event = CompanyEvent::create([
            'created_by' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? $data['start_date'],
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'location' => $data['location'] ?? null,
            'is_public' => $request->boolean('is_public', true),
        ]);

        foreach (User::all() as $user) {
            HrNotification::create([
                'user_id' => $user->id,
                'type' => 'info',
                'title' => 'New calendar event',
                'message' => $event->title,
                'url' => route('company-calendar.index'),
            ]);
        }

        AuditLogger::log(
            'company_event_created',
            'Created company calendar event: ' . $event->title,
            $event,
            [
                'event_id' => $event->id,
                'type' => $event->type,
                'start_date' => $event->start_date?->format('Y-m-d'),
                'end_date' => $event->end_date?->format('Y-m-d'),
            ]
        );

        return redirect()
            ->route('company-calendar.index', ['month' => Carbon::parse($event->start_date)->format('Y-m')])
            ->with('status', 'Company event created.');
    }

    public function destroy(CompanyEvent $companyEvent)
    {
        AuditLogger::log(
            'company_event_deleted',
            'Deleted company calendar event: ' . $companyEvent->title,
            $companyEvent,
            [
                'event_id' => $companyEvent->id,
                'title' => $companyEvent->title,
            ]
        );

        $companyEvent->delete();

        return redirect()
            ->route('company-calendar.index')
            ->with('status', 'Company event deleted.');
    }
}