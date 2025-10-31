<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCv extends Model
{
    use HasFactory;

    protected $table = 'data_cv';

    protected $fillable = [
        'phone_number',
        'name',
        'address',
        'whatsapp_status',
        'whatsapp_checked_at',
    ];
}
