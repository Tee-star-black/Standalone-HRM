<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollExport;
use App\Exports\EmployeesExport;
use App\Http\Controllers\HrReportController;

class HrReportController extends Controller
{
    public function index()
    {
        return view('hr-reports.index');
    }

    public function exportAttendance()
    {
        return Excel::download(
            new AttendanceExport,
            'attendance-report.xlsx'
        );
    }

        public function exportPayroll()
    {
        return Excel::download(
            new PayrollExport,
            'payroll-report.xlsx'
        );
    }

    public function exportEmployees()
    {
        return Excel::download(
            new EmployeesExport,
            'employee-directory.xlsx'
        );
    }

        public function exportDocuments()
    {
        return Excel::download(
            new DocumentsExport,
            'document-audit-report.xlsx'
        );
    }
}