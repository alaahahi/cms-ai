<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'specialty_ar',
        'specialty_en',
        'description_ar',
        'description_en',
        'image',
        'review_rate',
        'ex_year',
        'is_active',
        'legacy_card_service_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name', 'specialty'];

    public function getNameAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getSpecialtyAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->specialty_ar : $this->specialty_en;
    }

    public function services()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
