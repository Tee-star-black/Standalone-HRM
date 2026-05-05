<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class HrAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $date = $request->query('date');

        $query = Attendance::with('employee')->latest('date');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        if ($date) {
            $query->whereDate('date', $date);
        }

        return view('hr-attendance.index', [
            'attendances' => $query->get(),
            'employees' => Employee::orderBy('first_name')->get(),
            'selectedEmployeeId' => $employeeId,
            'selectedDate' => $date,
        ]);
    }
}