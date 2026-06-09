<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'company_logo_path',
        'ceo_name',
        'ceo_title',
        'ceo_signature_path',
        'company_address',
        'company_email',
        'company_phone',
    ];

    public static function current()
    {
        return static::firstOrCreate([
            'id' => 1,
        ], [
            'company_name' => 'TeleDoctor',
            'company_logo_path' => null,
            'ceo_title' => 'Chief Executive Officer',
        ]);
    }
}