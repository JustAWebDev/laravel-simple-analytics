<?php
namespace JustAWebDev\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'type',
        'name',
        'meta',
        'method',
        'status',
        'duration_ms',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}