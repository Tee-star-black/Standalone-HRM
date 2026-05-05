<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Job;
use App\Models\Position;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function index()
    {
        $employeesCount = Employee::count();
        $jobsCount = Job::count();
        $positionsCount = Position::count();
        $skillsCount = Skill::count();

        $employees = Employee::with(['positions.job.skills', 'skills'])->get();

        $gapEmployees = $employees->map(function ($employee) {
            $currentPosition = $employee->positions
                ->where('is_primary', true)
                ->sortByDesc('created_at')
                ->first();

            if (! $currentPosition || ! $currentPosition->job) {
                return null;
            }

            $employeeSkills = $employee->skills->keyBy('id');

            $totalGap = $currentPosition->job->skills->sum(function ($jobSkill) use ($employeeSkills) {
                $employeeSkill = $employeeSkills->get($jobSkill->id);

                $required = (int) $jobSkill->pivot->required_level;
                $current = $employeeSkill ? (int) $employeeSkill->pivot->proficiency_level : 0;

                return max($required - $current, 0);
            });

            return [
                'employee' => $employee,
                'job' => $currentPosition->job,
                'total_gap' => $totalGap,
            ];
        })
        ->filter()
        ->sortByDesc('total_gap')
        ->values()
        ->take(5);

        return view('dashboard', [
            'employeesCount' => $employeesCount,
            'jobsCount' => $jobsCount,
            'positionsCount' => $positionsCount,
            'skillsCount' => $skillsCount,
            'gapEmployees' => $gapEmployees,
        ]);
    }
}