<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'subject',
        'description',
        'priority',
        'status',
        'category_id',
        'created_by',
        'assigned_to',
        'subscription_id',
        'legacy_pending_request_id',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withTrashed();
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
