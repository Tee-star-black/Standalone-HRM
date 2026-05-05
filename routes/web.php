<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveCalendarController;
use App\Http\Controllers\ManagerLeaveController;
use App\Http\Controllers\MyLeaveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\MyDocumentController;
use App\Http\Controllers\HrDocumentController;
use App\Http\Controllers\MyPayslipController;
use App\Http\Controllers\HRPayslipController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HrAttendanceController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/my-leave', [MyLeaveController::class, 'index'])->name('my-leave.index');
    Route::post('/my-leave', [MyLeaveController::class, 'store'])->name('my-leave.store');

    Route::get('/manager/leave', [ManagerLeaveController::class, 'index'])->name('manager-leave.index');
    Route::post('/manager/leave/{leaveRequest}/approve', [ManagerLeaveController::class, 'approve'])->name('manager-leave.approve');
    Route::post('/manager/leave/{leaveRequest}/reject', [ManagerLeaveController::class, 'reject'])->name('manager-leave.reject');

    Route::get('/leave-calendar', [LeaveCalendarController::class, 'index'])->name('leave-calendar.index');
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('my-profile.index');
    Route::post('/my-profile/emergency-contacts', [MyProfileController::class, 'storeEmergencyContact'])->name('my-profile.emergency-contacts.store');
    Route::get('/my-documents', [MyDocumentController::class, 'index'])->name('my-documents.index');
    Route::post('/my-documents', [MyDocumentController::class, 'store'])->name('my-documents.store');
    Route::get('/my-documents/{document}/preview', [MyDocumentController::class, 'preview'])->name('my-documents.preview');
    Route::get('/my-documents/{document}/download', [MyDocumentController::class, 'download'])->name('my-documents.download');
    Route::delete('/my-documents/{document}/delete', [MyDocumentController::class, 'destroy'])->name('my-documents.destroy');
    Route::get('/hr-documents', [HrDocumentController::class, 'index'])->name('hr-documents.index');
    Route::post('/hr-documents', [HrDocumentController::class, 'store'])->name('hr-documents.store');
    Route::get('/my-payslips', [MyPayslipController::class, 'index'])->name('my-payslips.index');
    Route::get('/my-payslips/{payslip}', [MyPayslipController::class, 'show'])->name('my-payslips.show');
    Route::get('/my-payslips/{payslip}/download', [MyPayslipController::class, 'download'])->name('my-payslips.download');
    Route::get('/hr/payslips', [HrPayslipController::class, 'index'])->name('hr-payslips.index');
    Route::post('/hr/payslips', [HrPayslipController::class, 'store'])->name('hr-payslips.store');
    Route::post('/hr/payslips/{payslip}/publish', [HrPayslipController::class, 'publish'])->name('hr-payslips.publish');
    Route::get('/my-documents/{document}/file', [MyDocumentController::class, 'download'])->name('my-documents.file');
    Route::get('/hr/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
    Route::post('/hr/recruitment/vacancies', [RecruitmentController::class, 'storeVacancy'])->name('recruitment.vacancies.store');
    Route::post('/hr/recruitment/candidates', [RecruitmentController::class, 'storeCandidate'])->name('recruitment.candidates.store');
    Route::post('/hr/recruitment/applications', [RecruitmentController::class, 'storeApplication'])->name('recruitment.applications.store');
    Route::post('/hr/recruitment/applications/{application}/stage', [RecruitmentController::class, 'updateApplicationStage'])->name('recruitment.applications.stage');
    Route::get('/hr/recruitment/documents/{document}/file', [RecruitmentController::class, 'candidateFile'])->name('recruitment.documents.file');
    Route::get('/hr/recruitment/documents/{document}/download', [RecruitmentController::class, 'candidateDownload'])->name('recruitment.documents.download');
    Route::post('/hr/recruitment/applications/{application}/hire', [RecruitmentController::class, 'hire'])->name('recruitment.applications.hire');
    Route::post('/hr/recruitment/applications/{application}/offer-letter', [RecruitmentController::class, 'generateOfferLetter'])->name('recruitment.applications.offer-letter');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');  
    Route::get('/hr/attendance', [HrAttendanceController::class, 'index'])->name('hr-attendance.index');
});

require __DIR__.'/auth.php';