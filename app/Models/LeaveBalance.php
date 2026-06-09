<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'allocated_days',
        'used_days',
        'carried_forward_days',
        'remaining_days',
        'cycle_start',
        'cycle_end',
    ];

    protected $casts = [
        'year' => 'integer',
        'allocated_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'carried_forward_days' => 'decimal:2',
        'remaining_days' => 'decimal:2',
        'cycle_start' => 'date',
        'cycle_end' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getCalculatedRemainingDaysAttribute()
    {
        return max(
            0,
            ($this->allocated_days + $this->carried_forward_days) - $this->used_days
        );
    }
}