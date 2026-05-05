<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'vacancy_id',
        'candidate_id',
        'stage',
        'status',
        'score',
        'cover_letter',
        'notes',
        'applied_at',
        'converted_employee_id',
        'converted_at',
        'offer_letter_generated_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'converted_at' => 'datetime',
        'offer_letter_generated_at' => 'datetime',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function convertedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'converted_employee_id');
    }
}