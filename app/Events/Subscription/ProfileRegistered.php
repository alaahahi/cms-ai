<?php

namespace App\Events\Subscription;

use App\Models\Profile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(public Profile $profile)
    {
    }
}
