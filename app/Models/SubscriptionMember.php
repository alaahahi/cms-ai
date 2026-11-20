<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'name',
        'relation',
        'birthdate',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
