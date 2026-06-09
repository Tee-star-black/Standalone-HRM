<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Employee;
use App\Models\JobApplication;
use App\Models\LeaveRequest;
use App\Models\Payslip;
use App\Models\Vacancy;
use App\Models\CompanyEvent;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'employeesCount' => Employee::count(),
            'activeEmployeesCount' => Employee::where('status', 'active')->count(),
            'pendingLeaveCount' => LeaveRequest::where('status', 'pending')->count(),
            'approvedLeaveCount' => LeaveRequest::where('status', 'approved')->count(),
            'todayAttendanceCount' => Attendance::whereDate('date', now()->toDateString())->count(),
            'documentsCount' => Document::count(),
            'publishedPayslipsCount' => Payslip::where('status', 'published')->count(),
            'openVacanciesCount' => Vacancy::where('status', 'open')->count(),
            'candidatesCount' => Candidate::count(),
            'activeApplicationsCount' => JobApplication::where('status', 'active')->count(),

            'recentAnnouncements' => Announcement::where('status', 'published')
                ->orderByDesc('is_pinned')
                ->latest('published_at')
                ->take(3)
                ->get(),

            'recentEmployees' => Employee::latest()->take(3)->get(),

            'recentLeaveRequests' => LeaveRequest::with(['employee', 'leaveType'])
                ->latest()
                ->take(3)
                ->get(),

            'recentApplications' => JobApplication::with(['candidate', 'vacancy'])
                ->latest()
                ->take(3)
                ->get(),

            'recentAttendances' => Attendance::with('employee')
                ->latest()
                ->take(3)
                ->get(),

            'upcomingCompanyEvents' => CompanyEvent::whereDate('start_date', '>=', now()->toDateString())
                ->orderBy('start_date')
                ->take(5)
                ->get(),
        ]);
    }
}