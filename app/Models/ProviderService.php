<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'service_category_id',
        'subscription_type_id',
        'price',
        'currency',
        'working_days',
        'working_hours',
        'appointments_per_day',
        'expir_date',
        'show_on_app',
        'is_popular',
        'legacy_card_service_id',
    ];

    protected $casts = [
        'expir_date' => 'date',
        'show_on_app' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expir_date')->orWhere('expir_date', '>=', now());
        })->where('show_on_app', true);
    }
}
