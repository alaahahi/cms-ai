<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'sales_rep_id',
        'subscription_id',
        'legacy_profile_id',
        'subscription_type_id',
        'amount',
        'cards_count',
        'sale_date',
        'status',
        'notes',
        'legacy_transaction_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sale_date' => 'date',
    ];

    public function salesRep()
    {
        return $this->belongsTo(SalesRep::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }
}
