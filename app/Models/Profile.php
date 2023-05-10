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
        'husband_name',
        'husband_birthdate',
        'husband_certification',
        'husband_job',
        'husband_address',
        'husband_image',
        'wife_name',
        'wife_birthdate',
        'wife_certification',
        'wife_job',
        'wife_address',
        'wife_image',
        'phone_number',
        'invoice_number',
        'relatives',
        'user_id',
        'user_add_lab',
        'user_doctor',
        'user_accepted',
        'user_rejected',
        'results_id',
        'created_at',
        'updated_at'
    ];
  }