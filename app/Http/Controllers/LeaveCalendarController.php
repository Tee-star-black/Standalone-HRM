<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $startOfCalendar = $currentMonth->copy()->startOfWeek();
        $endOfCalendar = $currentMonth->copy()->endOfMonth()->endOfWeek();

        $approvedLeaves = LeaveRequest::with(['employee', 'leaveType'])
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endOfCalendar)
            ->whereDate('end_date', '>=', $startOfCalendar)
            ->orderBy('start_date')
            ->get();

        $calendarDays = collect();

        $day = $startOfCalendar->copy();

        while ($day <= $endOfCalendar) {
            $leavesForDay = $approvedLeaves->filter(function ($leave) use ($day) {
                return $day->betweenIncluded($leave->start_date, $leave->end_date);
            });

            $calendarDays->push([
                'date' => $day->copy(),
                'is_current_month' => $day->month === $currentMonth->month,
                'leaves' => $leavesForDay,
            ]);

            $day->addDay();
        }

        return view('leave-calendar.index', [
            'currentMonth' => $currentMonth,
            'previousMonth' => $currentMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $currentMonth->copy()->addMonth()->format('Y-m'),
            'calendarDays' => $calendarDays,
        ]);
    }
}