<?php

namespace App\Models;

use App\Models\Massage;
use App\Models\UserType;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type_id',
        'percentage',
        'device',
        'verification_code',
        'phone_number',
        'verification_date',
        'verification_user_type',
        'family_members_names',
        'birth_date',
        'weight',
        'height',
        'gender',
        'token',
        'network',
        'fcm_token',
        'can_lots'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return (int)$this->type_id === 4; // Ensure type_id is cast to integer if needed
    }

    /**
     * The boot method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // You can add model event hooks here if needed.
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_band',
        'verification_code',
        'deleted_at',
        'morphed_id',
        'morphed_type',
        'email',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function morphed()
    {
        return $this->morphTo();
    }

    public function transactions()
    {
        return $this->morphMany(Transactions::class, 'morphed');
    }

    public function massage()
    {
        return $this->hasMany(Massage::class, 'sender_id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'type_id');
    }

    public function getParentUser()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getChildUser()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getTokenAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Usually the primary key
    }

    public function getJWTCustomClaims()
    {
        return []; // Add custom claims if necessary
    }
}
