<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Specify the fields that are mass assignable
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'status',
        'state',
        "link",
        "token",
        "transactionId",
        "3DSecureId",
        'card_number',
        'salse',
        'name',
        'family_name',
        'phone',
        'address'
    ];
}
