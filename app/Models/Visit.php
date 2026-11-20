<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public const TYPE_LAB = 'lab';
    public const TYPE_DOCTOR = 'doctor';

    protected $fillable = [
        'subscription_id',
        'legacy_profile_id',
        'provider_id',
        'provider_service_id',
        'visit_type',
        'recorded_by',
        'payload',
        'visited_at',
        'legacy_results_id',
        'legacy_doctor_results_id',
    ];

    protected $casts = [
        'payload' => 'array',
        'visited_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function providerService()
    {
        return $this->belongsTo(ProviderService::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
