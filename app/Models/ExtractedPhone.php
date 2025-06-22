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
        'note'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
