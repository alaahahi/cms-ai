<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'day',
        'price',
        'currency',
        'expir_date',
        'show_on_app',
        'description_ar',
        'description_en',
        'image',
        'legacy_card_id',
    ];

    protected $casts = [
        'expir_date' => 'date',
        'show_on_app' => 'boolean',
    ];

    protected $appends = ['name'];

    public function getNameAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function scopeActive($query)
    {
        return $query->where('expir_date', '>=', now())->where('show_on_app', true);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function legacyCard()
    {
        return $this->belongsTo(Card::class, 'legacy_card_id');
    }
}
