<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'card';

    protected $fillable = [
        'name_ar',
        'name_en',
        'day',
        'price',
        'currency',
        'expir_date',
        'show_on_app',
        'description_ar',
        'description_en',
        'image'

    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $language =  app()->getLocale();
        switch ($language) {
            case 'ar':
                return $this->name_ar;
                break;
            default:
                return $this->name_en;
                break;
        }
    }
    public function getDescriptionAttribute()
    {
        $language =  app()->getLocale();
        switch ($language) {
            case 'ar':
                return $this->description_ar;
                break;
            default:
                return $this->description_en;
                break;
        }
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

    /**
     * Accessor for formatted expiry date.
     */
    public function getFormattedExpirDateAttribute()
    {
        return $this->expir_date->format('d-m-Y');
    }

    /**
     * Boot method for default ordering.
     */
    protected static function boot()
    {
        parent::boot();

        // Default ordering by expiration date (earliest first)
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('expir_date');
        });
    }

    public function services()
    {
        return $this->hasMany(CardService::class);
    }
}
