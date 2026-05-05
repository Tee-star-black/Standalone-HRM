<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    protected $fillable = [
        'job_id',
        'title',
        'code',
        'description',
        'department',
        'location',
        'employment_type',
        'open_positions',
        'closing_date',
        'status',
    ];

    protected $casts = [
        'closing_date' => 'date',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}