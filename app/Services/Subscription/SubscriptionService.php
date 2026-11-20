<?php

namespace App\Services\Subscription;

use App\Events\Subscription\ProfileRegistered;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Services\BaseService;
use Illuminate\Support\Collection;

/**
 * Subscription facade with parallel run:
 * - dual-write to Profile (legacy) + Subscription (new)
 * - read prefers new tables, falls back to legacy mapping
 */
class SubscriptionService extends BaseService
{
    public function __construct(private LegacySubscriptionSyncService $sync)
    {
    }

    public function createFromLegacyPayload(array $data): Profile
    {
        return $this->transaction(function () use ($data) {
            $profile = Profile::create($data);
            $this->sync->syncProfile($profile->load('card'));

            event(new ProfileRegistered($profile));

            return $profile->fresh(['card', 'user']);
        });
    }

    public function findByCardNumber(string $cardNumber): ?Subscription
    {
        $subscription = Subscription::with(['members', 'type', 'subscriber'])
            ->where('subscription_number', $cardNumber)
            ->first();

        if ($subscription) {
            return $subscription;
        }

        $profile = Profile::with('card')->where('card_number', $cardNumber)->first();

        return $profile ? $this->sync->syncProfile($profile) : null;
    }

    public function find($id): ?Subscription
    {
        $subscription = Subscription::with(['members', 'type', 'subscriber', 'documents'])->find($id);
        if ($subscription) {
            return $subscription;
        }

        $profile = Profile::with('card')->find($id);

        return $profile ? $this->sync->syncProfile($profile) : null;
    }

    public function listActiveTypes(): Collection
    {
        $types = SubscriptionType::active()->orderBy('id')->get();
        if ($types->isNotEmpty()) {
            return $types;
        }

        // Fallback + sync from legacy cards
        return \App\Models\Card::active()->get()->map(fn ($card) => $this->sync->syncCard($card));
    }

    public function listForSubscriber(int $userId): Collection
    {
        $items = Subscription::with(['members', 'type'])
            ->where('subscriber_id', $userId)
            ->orderByDesc('id')
            ->get();

        if ($items->isNotEmpty()) {
            return $items;
        }

        return Profile::with('card')
            ->where('user_id', $userId)
            ->get()
            ->map(fn (Profile $profile) => $this->sync->syncProfile($profile));
    }

    public function listAll(?string $status = null, int $limit = 100): Collection
    {
        $query = Subscription::with(['members', 'type', 'subscriber'])->orderByDesc('id');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->limit($limit)->get();
    }
}
