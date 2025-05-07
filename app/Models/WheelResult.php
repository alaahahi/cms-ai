<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WheelResult extends Model
{
    protected $fillable = [
        'user_id',
        'wheel_item_id',
        'is_claimed',
        'claimed_at',
        'note',
    ];

    protected $casts = [
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wheelItem()
    {
        return $this->belongsTo(WheelItem::class);
    }
}
