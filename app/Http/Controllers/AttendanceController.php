<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $todayDate = now()->toDateString();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $todayDate)
            ->first();

        if (! $todayAttendance) {
            $todayAttendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $todayDate,
            ]);
        }

        $history = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->limit(10)
            ->get();

        return view('attendance.index', [
            'today' => $todayAttendance,
            'history' => $history,
        ]);
    }

    public function clockIn()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $todayDate = now()->toDateString();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $todayDate)
            ->first();

        if (! $attendance) {
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $todayDate,
            ]);
        }

        if (! $attendance->clock_in) {
            $attendance->update([
                'clock_in' => now(),
                'status' => 'present',
            ]);
        }

        return back()->with('status', 'Clocked in successfully.');
    }

    public function clockOut()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $todayDate = now()->toDateString();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $todayDate)
            ->first();

        if ($attendance && ! $attendance->clock_out) {
            $minutes = $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes(now())
                : 0;

            $attendance->update([
                'clock_out' => now(),
                'total_minutes' => $minutes,
            ]);
        }

        return back()->with('status', 'Clocked out successfully.');
    }
}