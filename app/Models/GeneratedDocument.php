<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'generated_by',
        'type',
        'title',
        'disk',
        'path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}