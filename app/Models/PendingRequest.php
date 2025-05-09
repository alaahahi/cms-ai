<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'user_id',
        'card_number',
        'family_members_names',
        'image',
        'source',
        'card_id'
    ];
    
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();;
    }
    
}
