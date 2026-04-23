<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Establishment;
use App\Models\Job;
use App\Models\Position;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $establishment = Establishment::firstOrCreate(
            ['code' => 'HQ001'],
            [
                'name' => 'Head Office',
                'country' => 'Botswana',
                'city' => 'Gaborone',
                'address' => 'Main Campus',
                'is_active' => true,
            ]
        );

        $department = Department::firstOrCreate(
            ['code' => 'ENG'],
            [
                'name' => 'Engineering',
                'establishment_id' => $establishment->id,
                'is_active' => true,
            ]
        );

        $job = Job::firstOrCreate(
            ['code' => 'SE001'],
            [
                'title' => 'Software Engineer',
                'summary' => 'Build and maintain software systems.',
                'description' => 'Responsible for backend and application development.',
                'grade' => 'P2',
                'employment_type' => 'Full-time',
                'is_active' => true,
            ]
        );

        $skillPhp = Skill::firstOrCreate(
            ['name' => 'PHP'],
            [
                'category' => 'Programming',
                'description' => 'PHP backend programming',
                'default_required_level' => 5,
                'is_active' => true,
            ]
        );

        $skillLaravel = Skill::firstOrCreate(
            ['name' => 'Laravel'],
            [
                'category' => 'Framework',
                'description' => 'Laravel framework development',
                'default_required_level' => 4,
                'is_active' => true,
            ]
        );

        $job->skills()->syncWithoutDetaching([
            $skillPhp->id => ['required_level' => 5],
            $skillLaravel->id => ['required_level' => 4],
        ]);

        $employee = Employee::firstOrCreate(
            ['employee_number' => 'EMP001'],
            [
                'establishment_id' => $establishment->id,
                'department_id' => $department->id,
                'first_name' => 'Tlotliso',
                'last_name' => 'Monareng',
                'email' => 'tlotliso@example.com',
                'employment_type' => 'Full-time',
                'status' => 'active',
                'job_title' => 'Software Engineer',
            ]
        );

        Position::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'job_id' => $job->id,
                'title' => 'Software Engineer',
            ],
            [
                'department_id' => $department->id,
                'establishment_id' => $establishment->id,
                'status' => 'active',
                'is_primary' => true,
            ]
        );

        $employee->skills()->syncWithoutDetaching([
            $skillPhp->id => [
                'proficiency_level' => 3,
                'assessed_at' => now()->toDateString(),
            ],
            $skillLaravel->id => [
                'proficiency_level' => 2,
                'assessed_at' => now()->toDateString(),
            ],
        ]);
    }
}