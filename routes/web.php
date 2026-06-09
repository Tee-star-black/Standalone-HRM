<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CompanyCalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\DocumentWizardController;
use App\Http\Controllers\GeneratedDocumentController;
use App\Http\Controllers\HrAttendanceController;
use App\Http\Controllers\HrDocumentController;
use App\Http\Controllers\HrEmployeeController;
use App\Http\Controllers\HrPayslipController;
use App\Http\Controllers\HrReportController;
use App\Http\Controllers\LeaveCalendarController;
use App\Http\Controllers\ManagerLeaveController;
use App\Http\Controllers\MyDocumentController;
use App\Http\Controllers\MyLeaveController;
use App\Http\Controllers\MyPayslipController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/preferences', [PreferencesController::class, 'index'])->name('preferences.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    Route::get('/company-calendar', [CompanyCalendarController::class, 'index'])->name('company-calendar.index');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle-theme', [SettingsController::class, 'toggleTheme'])->name('settings.toggle-theme');

    Route::middleware(['role:Employee|Manager|HR Admin|Super Admin'])->group(function () {
        Route::get('/my-leave', [MyLeaveController::class, 'index'])->name('my-leave.index');
        Route::post('/my-leave', [MyLeaveController::class, 'store'])->name('my-leave.store');
        Route::get('/my-leave/{leaveRequest}/document', [MyLeaveController::class, 'document'])->name('my-leave.document');

        Route::get('/leave-calendar', [LeaveCalendarController::class, 'index'])->name('leave-calendar.index');

        Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile.index');
        Route::post('/my-profile/emergency-contacts', [MyProfileController::class, 'storeEmergencyContact'])->name('my-profile.emergency-contacts.store');

        Route::get('/my-documents', [MyDocumentController::class, 'index'])->name('my-documents.index');
        Route::post('/my-documents', [MyDocumentController::class, 'store'])->name('my-documents.store');
        Route::get('/my-documents/{document}/preview', [MyDocumentController::class, 'preview'])->name('my-documents.preview');
        Route::get('/my-documents/{document}/download', [MyDocumentController::class, 'download'])->name('my-documents.download');
        Route::get('/my-documents/{document}/file', [MyDocumentController::class, 'download'])->name('my-documents.file');
        Route::delete('/my-documents/{document}/delete', [MyDocumentController::class, 'destroy'])->name('my-documents.destroy');

        Route::get('/my-payslips', [MyPayslipController::class, 'index'])->name('my-payslips.index');
        Route::get('/my-payslips/{payslip}', [MyPayslipController::class, 'show'])->name('my-payslips.show');
        Route::get('/my-payslips/{payslip}/download', [MyPayslipController::class, 'download'])->name('my-payslips.download');

        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
        Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

        Route::get('/generated-documents', [GeneratedDocumentController::class, 'index'])->name('generated-documents.index');
        Route::post('/generated-documents/employment-confirmation', [GeneratedDocumentController::class, 'generateEmploymentConfirmation'])->name('generated-documents.employment-confirmation');
        Route::get('/generated-documents/{generatedDocument}/download', [GeneratedDocumentController::class, 'download'])->name('generated-documents.download');
    });

    Route::middleware(['role:Manager|HR Admin|Super Admin'])->group(function () {
        Route::get('/manager/leave', [ManagerLeaveController::class, 'index'])->name('manager-leave.index');
        Route::post('/manager/leave/{leaveRequest}/approve', [ManagerLeaveController::class, 'approve'])->name('manager-leave.approve');
        Route::post('/manager/leave/{leaveRequest}/reject', [ManagerLeaveController::class, 'reject'])->name('manager-leave.reject');
    });

    Route::middleware(['role:HR Admin|Super Admin'])->group(function () {
        Route::get('/hr-documents', [HrDocumentController::class, 'index'])->name('hr-documents.index');
        Route::post('/hr-documents', [HrDocumentController::class, 'store'])->name('hr-documents.store');

        Route::get('/hr/payslips', [HrPayslipController::class, 'index'])->name('hr-payslips.index');
        Route::post('/hr/payslips', [HrPayslipController::class, 'store'])->name('hr-payslips.store');
        Route::post('/hr/payslips/{payslip}/publish', [HrPayslipController::class, 'publish'])->name('hr-payslips.publish');

        Route::get('/hr/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
        Route::post('/hr/recruitment/vacancies', [RecruitmentController::class, 'storeVacancy'])->name('recruitment.vacancies.store');
        Route::post('/hr/recruitment/candidates', [RecruitmentController::class, 'storeCandidate'])->name('recruitment.candidates.store');
        Route::post('/hr/recruitment/applications', [RecruitmentController::class, 'storeApplication'])->name('recruitment.applications.store');
        Route::post('/hr/recruitment/applications/{application}/stage', [RecruitmentController::class, 'updateApplicationStage'])->name('recruitment.applications.stage');
        Route::post('/hr/recruitment/applications/{application}/hire', [RecruitmentController::class, 'hire'])->name('recruitment.applications.hire');
        Route::post('/hr/recruitment/applications/{application}/offer-letter', [RecruitmentController::class, 'generateOfferLetter'])->name('recruitment.applications.offer-letter');
        Route::get('/hr/recruitment/documents/{document}/file', [RecruitmentController::class, 'candidateFile'])->name('recruitment.documents.file');
        Route::get('/hr/recruitment/documents/{document}/download', [RecruitmentController::class, 'candidateDownload'])->name('recruitment.documents.download');
        Route::get('/hr/recruitment/export', [RecruitmentController::class, 'exportApplications'])->name('recruitment.export');

        Route::get('/hr/attendance', [HrAttendanceController::class, 'index'])->name('hr-attendance.index');

        Route::get('/hr/employees', [HrEmployeeController::class, 'index'])->name('hr-employees.index');
        Route::get('/hr/employees/create', [HrEmployeeController::class, 'create'])->name('hr-employees.create');
        Route::post('/hr/employees', [HrEmployeeController::class, 'store'])->name('hr-employees.store');
        Route::get('/hr/employees/{employee}/edit', [HrEmployeeController::class, 'edit'])->name('hr-employees.edit');
        Route::post('/hr/employees/{employee}', [HrEmployeeController::class, 'update'])->name('hr-employees.update');
        Route::get('/hr/employees/{employee}', [HrEmployeeController::class, 'show'])->name('hr-employees.show');

        Route::get('/hr/reports', [HrReportController::class, 'index'])->name('hr-reports.index');
        Route::get('/hr/reports/attendance/export', [HrReportController::class, 'exportAttendance'])->name('hr-reports.attendance.export');
        Route::get('/hr/reports/payroll/export', [HrReportController::class, 'exportPayroll'])->name('hr-reports.payroll.export');
        Route::get('/hr/reports/employees/export', [HrReportController::class, 'exportEmployees'])->name('hr-reports.employees.export');
        Route::get('/hr/reports/documents/export', [HrReportController::class, 'exportDocuments'])->name('hr-reports.documents.export');

        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        Route::get('/hr/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/hr/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');

        Route::post('/company-calendar', [CompanyCalendarController::class, 'store'])->name('company-calendar.store');
        Route::delete('/company-calendar/{companyEvent}', [CompanyCalendarController::class, 'destroy'])->name('company-calendar.destroy');

        Route::get('/hr/document-templates', [DocumentTemplateController::class, 'index'])->name('document-templates.index');
        Route::post('/hr/document-templates', [DocumentTemplateController::class, 'store'])->name('document-templates.store');
        Route::post('/hr/document-templates/company-settings', [DocumentTemplateController::class, 'updateCompanySettings'])->name('document-templates.company-settings');
        Route::post('/hr/document-templates/{template}/generate', [DocumentTemplateController::class, 'generate'])->name('document-templates.generate');
        Route::get('/hr/document-templates/{template}/download', [DocumentTemplateController::class, 'downloadTemplate'])->name('document-templates.download');
        Route::delete('/hr/document-templates/{template}', [DocumentTemplateController::class, 'destroy'])->name('document-templates.destroy');

        Route::get('/hr/document-wizard', [DocumentWizardController::class, 'index'])->name('document-wizard.index');
        Route::post('/hr/document-wizard/generate', [DocumentWizardController::class, 'generate'])->name('document-wizard.generate');
    });
});

require __DIR__.'/auth.php';