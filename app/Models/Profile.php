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
        'user_add',
        'cardHolder_id',
        'user_doctor',
        'user_accepted',
        'created_at',
        'updated_at',
        'created',
        'source',
        'cloud_image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();;
    }
    public function appointment()
    {
        return $this->hasMany(Appointment::class,'card_id','card_number');
    }
    protected $casts = [
        'expir_date' => 'date', // Automatically cast to Carbon instance
        'show_on_app' => 'boolean', // Cast to boolean
    ];

    /**
     * Scope for filtering active cards (not expired and visible on the app).
     */
    public function scopeActive($query)
    {
        return $query->where('expir_date', '>=', now())
                     ->where('show_on_app', true);
    }

    /**
     * Scope for filtering expired cards.
     */
    public function scopeExpired($query)
    {
        return $query->where('expir_date', '<', now());
    }
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    /**
     * Accessor for formatted expiry date.
     */
    public function getFormattedExpirDateAttribute()
    {
        return $this->expir_date->format('d-m-Y');
    }
  }