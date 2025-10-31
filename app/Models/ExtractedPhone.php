<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractedPhone extends Model
{
    protected $fillable = [
        'phone',
        'image_name',
        'status',
        'user_id',
        'name',
        'note',
        'whatsapp_status',
        'whatsapp_checked_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
