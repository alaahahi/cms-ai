<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_rep_id',
        'year',
        'month',
        'target_amount',
        'target_cards',
        'achieved_amount',
        'achieved_cards',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'achieved_amount' => 'decimal:2',
    ];

    public function salesRep()
    {
        return $this->belongsTo(SalesRep::class);
    }

    public function progressPercent(): float
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return round(((float) $this->achieved_amount / (float) $this->target_amount) * 100, 2);
    }
}
