<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        $attendances = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->latest()
            ->take(30)
            ->get();

        return view('attendance.index', [
            'employee' => $employee,
            'todayAttendance' => $todayAttendance,
            'attendances' => $attendances,
        ]);
    }

    public function clockIn()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => today()->toDateString(),
            ],
            [
                'clock_in' => now(),
                'status' => $this->calculateStatus(now()),
            ]
        );

        if ($attendance->wasRecentlyCreated === false && $attendance->clock_in) {
            return back()->withErrors([
                'attendance' => 'You have already clocked in today.',
            ]);
        }

        if (! $attendance->clock_in) {
            $attendance->update([
                'clock_in' => now(),
                'status' => $this->calculateStatus(now()),
            ]);
        }

        AuditLogger::log(
            'attendance_clock_in',
            'Clocked in for attendance',
            $attendance,
            [
                'employee_id' => $employee->id,
                'clock_in' => $attendance->clock_in,
            ]
        );

        return back()->with('status', 'Clocked in successfully.');
    }

    public function clockOut()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if (! $attendance || ! $attendance->clock_in) {
            return back()->withErrors([
                'attendance' => 'You must clock in before clocking out.',
            ]);
        }

        if ($attendance->clock_out) {
            return back()->withErrors([
                'attendance' => 'You have already clocked out today.',
            ]);
        }

        $clockIn = Carbon::parse($attendance->clock_in);
        $clockOut = now();

        $attendance->update([
            'clock_out' => $clockOut,
            'total_minutes' => $clockIn->diffInMinutes($clockOut),
        ]);

        AuditLogger::log(
            'attendance_clock_out',
            'Clocked out for attendance',
            $attendance,
            [
                'employee_id' => $employee->id,
                'clock_out' => $attendance->clock_out,
                'total_minutes' => $attendance->total_minutes,
            ]
        );

        return back()->with('status', 'Clocked out successfully.');
    }

    private function calculateStatus(Carbon $clockIn): string
    {
        $workStart = today()->setTime(8, 0);
        $gracePeriodMinutes = 10;

        return $clockIn->greaterThan($workStart->copy()->addMinutes($gracePeriodMinutes))
            ? 'late'
            : 'present';
    }
}