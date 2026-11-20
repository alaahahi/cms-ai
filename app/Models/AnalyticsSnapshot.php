<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_key',
        'metric_value',
        'meta',
        'snapshot_date',
    ];

    protected $casts = [
        'metric_value' => 'decimal:2',
        'meta' => 'array',
        'snapshot_date' => 'date',
    ];
}
