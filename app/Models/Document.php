<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'type',
        'path',
        'original_name',
        'uploaded_by',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
