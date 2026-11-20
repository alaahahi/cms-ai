<?php

namespace App\Services\Subscription;

use App\Models\Card;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\SubscriptionMember;
use App\Models\SubscriptionType;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class LegacySubscriptionSyncService extends BaseService
{
    public function syncAll(): array
    {
        $types = 0;
        $subscriptions = 0;

        Card::query()->orderBy('id')->chunkById(100, function ($cards) use (&$types) {
            foreach ($cards as $card) {
                $this->syncCard($card);
                $types++;
            }
        });

        Profile::query()->orderBy('id')->chunkById(100, function ($profiles) use (&$subscriptions) {
            foreach ($profiles as $profile) {
                $this->syncProfile($profile);
                $subscriptions++;
            }
        });

        return compact('types', 'subscriptions');
    }

    public function syncCard(Card $card): SubscriptionType
    {
        return SubscriptionType::updateOrCreate(
            ['legacy_card_id' => $card->id],
            [
                'name_ar' => $card->name_ar,
                'name_en' => $card->name_en,
                'day' => $card->day,
                'price' => $card->price,
                'currency' => $card->currency,
                'expir_date' => $card->expir_date,
                'show_on_app' => $card->show_on_app,
                'description_ar' => $card->description_ar,
                'description_en' => $card->description_en,
                'image' => $card->image,
            ]
        );
    }

    public function syncProfile(Profile $profile): Subscription
    {
        $typeId = null;
        if ($profile->card_id) {
            $type = SubscriptionType::where('legacy_card_id', $profile->card_id)->first();
            if (! $type && $profile->card) {
                $type = $this->syncCard($profile->card);
            }
            $typeId = $type?->id;
        }

        $subscription = Subscription::updateOrCreate(
            ['legacy_profile_id' => $profile->id],
            [
                'subscription_number' => $profile->card_number,
                'subscription_type_id' => $typeId,
                'subscriber_id' => $profile->user_id,
                'start_date' => $this->parseDate($profile->created) ?? $profile->created_at,
                'status' => $this->mapStatus($profile->results),
                'phone_number' => $profile->phone_number,
                'address' => $profile->address,
                'invoice_number' => $profile->invoice_number,
                'source' => $profile->source,
            ]
        );

        $this->syncMembers($subscription, $profile);

        return $subscription->fresh(['members', 'type']);
    }

    public function syncMembers(Subscription $subscription, Profile $profile): void
    {
        $subscription->members()->delete();

        SubscriptionMember::create([
            'subscription_id' => $subscription->id,
            'name' => $profile->name ?: 'Unknown',
            'relation' => 'self',
            'birthdate' => $profile->birthdate,
        ]);

        if (! empty($profile->family_name)) {
            foreach (explode(',', $profile->family_name) as $memberName) {
                $name = trim($memberName);
                if ($name === '') {
                    continue;
                }

                SubscriptionMember::create([
                    'subscription_id' => $subscription->id,
                    'name' => $name,
                    'relation' => 'family',
                ]);
            }
        }
    }

    public function mapStatus($results): string
    {
        return match ((int) $results) {
            1 => Subscription::STATUS_ACTIVE,
            0 => Subscription::STATUS_PENDING,
            default => Subscription::STATUS_CANCELLED,
        };
    }

    protected function parseDate($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
