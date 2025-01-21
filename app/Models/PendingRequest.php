<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'card_number',
        'family_members_names',
        'image',
        'source',
    ];
}
