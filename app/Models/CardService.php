<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardService extends Model
{
    use HasFactory;

    protected $table = 'card_services';

    protected $fillable = [
        'card_id',
        'service_name_ar',
        'description_ar',
        'price',
        'working_days',
        'working_hours',
        'appointments_per_day',
        'expir_date',
        'show_on_app',
        'specialty_ar',
        'service_name_en',
        'description_en',
        'specialty_en',

    ];
    protected $appends = ['service_name','description','specialty'];

    public function getServiceAttribute()
    {
        $language =  app()->getLocale();
        switch ($language) {
            case 'ar':
                return $this->service_name_ar;
                break;
            default:
                return $this->service_name_en;
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

    public function getSpecialtyAttribute()
    {
        $language =  app()->getLocale();
        switch ($language) {
            case 'ar':
                return $this->specialty_ar;
                break;
            default:
                return $this->specialty_en;
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
    public function getWorkingDaysAttribute($value)
    {
        return explode(',', $value);
    }
    public function getWorkingHoursAttribute($value)
    {
        $hours = explode('-', $value);
        return [
            'start' => $hours[0], // Start time
            'end' => $hours[1],   // End time
        ];
    }
    /**
     * Boot method for default ordering.
     */

}
