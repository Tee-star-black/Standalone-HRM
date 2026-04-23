<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'period',
        'evaluation_date',
        'status',
        'summary',
        'strengths',
        'areas_for_improvement',
        'manager_comments',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(EvaluationItem::class);
    }
}