<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('jobs', JobController::class);
Route::apiResource('skills', SkillController::class);
Route::apiResource('positions', PositionController::class);
Route::apiResource('evaluations', EvaluationController::class);

Route::post('jobs/{job}/skills', [JobController::class, 'attachSkill']);
Route::post('employees/{employee}/skills', [EmployeeController::class, 'attachSkill']);
Route::get('employees/{employee}/skill-gap', [EmployeeController::class, 'skillGap']);

Route::apiResource('leave-types', LeaveTypeController::class);
Route::apiResource('leave-balances', LeaveBalanceController::class);
Route::apiResource('leave-requests', LeaveRequestController::class);

Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);