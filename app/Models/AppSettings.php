<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class AppSettings extends Model
{
    use HasFactory;
   // use Searchable;
    protected $table = 'app_settings';
    protected $fillable = ['key', 'value', 'description', 'type'];
}
