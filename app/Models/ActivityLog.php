<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_identifier',
        'event_type',
        'url_or_path',
        'window_title',
        'payload',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}