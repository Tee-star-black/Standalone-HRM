<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        $leaveTypes = LeaveType::all();

        foreach ($employees as $employee) {

            foreach ($leaveTypes as $leaveType) {

                $cycleStart = now()->startOfYear();
                $cycleEnd = now()->endOfYear();

                if ($leaveType->cycle_months == 36) {
                    $cycleStart = now()->startOfYear();
                    $cycleEnd = now()->copy()->addMonths(36);
                }

                LeaveBalance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => now()->year,
                    ],
                    [
                        'allocated_days' => $leaveType->default_days,
                        'used_days' => 0,
                        'carried_forward_days' => 0,
                        'remaining_days' => $leaveType->default_days,
                        'cycle_start' => $cycleStart,
                        'cycle_end' => $cycleEnd,
                    ]
                );
            }
        }
    }
}