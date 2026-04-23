<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $table = 'hr_jobs';

    protected $fillable = [
        'title',
        'code',
        'summary',
        'description',
        'grade',
        'employment_type',
        'is_active',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skill')
            ->withPivot(['required_level'])
            ->withTimestamps();
    }
}