<?php

namespace App\Services\Provider;

use App\Models\DoctorResults;
use App\Models\Results;
use App\Models\Subscription;
use App\Models\Visit;
use App\Services\BaseService;

class VisitRecordingService extends BaseService
{
    public function __construct(private LegacyProviderSyncService $sync)
    {
    }

    public function recordLab(array $data, ?int $recordedBy = null): Visit
    {
        return $this->transaction(function () use ($data, $recordedBy) {
            $legacy = Results::create(array_merge($data, [
                'user_id' => $recordedBy ?? ($data['user_id'] ?? null),
            ]));

            return $this->sync->syncLabResult($legacy);
        });
    }

    public function recordDoctor(array $data, ?int $recordedBy = null): Visit
    {
        return $this->transaction(function () use ($data, $recordedBy) {
            $legacy = DoctorResults::create(array_merge($data, [
                'user_id' => $recordedBy ?? ($data['user_id'] ?? null),
            ]));

            return $this->sync->syncDoctorResult($legacy);
        });
    }

    public function listForSubscription(int $subscriptionId): \Illuminate\Support\Collection
    {
        return Visit::with(['provider', 'providerService'])
            ->where('subscription_id', $subscriptionId)
            ->orderByDesc('visited_at')
            ->get();
    }

    public function listRecent(int $limit = 100): \Illuminate\Support\Collection
    {
        return Visit::with(['provider', 'providerService', 'subscription'])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function attachSubscription(Visit $visit): Visit
    {
        if ($visit->subscription_id || ! $visit->legacy_profile_id) {
            return $visit;
        }

        $subscriptionId = Subscription::where('legacy_profile_id', $visit->legacy_profile_id)->value('id');
        if ($subscriptionId) {
            $visit->update(['subscription_id' => $subscriptionId]);
        }

        return $visit->fresh();
    }
}
