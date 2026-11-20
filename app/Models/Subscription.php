<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'subscription_number',
        'subscription_type_id',
        'subscriber_id',
        'start_date',
        'end_date',
        'status',
        'phone_number',
        'address',
        'invoice_number',
        'source',
        'legacy_profile_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function type()
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_type_id');
    }

    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id')->withTrashed();
    }

    public function members()
    {
        return $this->hasMany(SubscriptionMember::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function renewals()
    {
        return $this->hasMany(RenewalHistory::class);
    }

    public function legacyProfile()
    {
        return $this->belongsTo(Profile::class, 'legacy_profile_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function primaryMember()
    {
        return $this->hasOne(SubscriptionMember::class)->where('relation', 'self');
    }
}
