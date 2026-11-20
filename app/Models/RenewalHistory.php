<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalHistory extends Model
{
    use HasFactory;

    protected $table = 'renewal_history';

    protected $fillable = [
        'subscription_id',
        'renewed_at',
        'previous_end_date',
        'new_end_date',
        'amount',
        'renewed_by',
        'notes',
    ];

    protected $casts = [
        'renewed_at' => 'date',
        'previous_end_date' => 'date',
        'new_end_date' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
