<?php

namespace App\Listeners\Subscription;

use App\Events\Subscription\ProfileRegistered;
use Illuminate\Support\Facades\Log;

class LogProfileRegistered
{
    public function handle(ProfileRegistered $event): void
    {
        Log::info('subscription.profile_registered', [
            'profile_id' => $event->profile->id,
            'card_number' => $event->profile->card_number,
            'user_id' => $event->profile->user_id,
        ]);
    }
}
