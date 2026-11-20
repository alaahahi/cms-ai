<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRep extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commission_rate',
        'is_active',
        'code',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
