<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'default_required_level',
        'is_active',
    ];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_skill')
            ->withPivot(['required_level'])
            ->withTimestamps();
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_skill')
            ->withPivot(['proficiency_level', 'assessed_at'])
            ->withTimestamps();
    }
}