<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WheelItem extends Model
{
    protected $fillable = ['label', 'color', 'probability', 'position'];
}