<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = 'profile';
    protected $fillable = [
        'id',
        'no',
        'name',
        'birthdate',
        'certification',
        'job',
        'address',
        'image',
        'results',
        'family_name',
        'phone_number',
        'invoice_number',
        'card_number',
        'card_id',
        'user_id',
        'user_add_lab',
        'user_doctor',
        'user_accepted',
        'user_rejected',
        'results_id',
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
  }