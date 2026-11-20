<?php

namespace App\Services\Provider;

use App\Models\ProviderService;
use App\Models\Subscription;
use App\Services\BaseService;
use App\Services\Subscription\SubscriptionService;

class CoverageVerificationService extends BaseService
{
    public function __construct(private SubscriptionService $subscriptions)
    {
    }

    public function verify(string $subscriptionNumber, int $providerServiceId): array
    {
        $subscription = $this->subscriptions->findByCardNumber($subscriptionNumber);
        $providerService = ProviderService::with('provider')->find($providerServiceId);

        if (! $subscription) {
            return [
                'covered' => false,
                'reason' => 'subscription_not_found',
            ];
        }

        if ($subscription->status !== Subscription::STATUS_ACTIVE) {
            return [
                'covered' => false,
                'reason' => 'subscription_inactive',
                'subscription_id' => $subscription->id,
            ];
        }

        if (! $providerService || ! $providerService->provider?->is_active) {
            return [
                'covered' => false,
                'reason' => 'provider_service_not_found',
            ];
        }

        if ($providerService->expir_date && $providerService->expir_date->isPast()) {
            return [
                'covered' => false,
                'reason' => 'provider_service_expired',
                'provider_service_id' => $providerService->id,
            ];
        }

        $covered = (int) $providerService->subscription_type_id === (int) $subscription->subscription_type_id;

        return [
            'covered' => $covered,
            'reason' => $covered ? 'ok' : 'type_mismatch',
            'subscription_id' => $subscription->id,
            'subscription_type_id' => $subscription->subscription_type_id,
            'provider_service_id' => $providerService->id,
            'provider_id' => $providerService->provider_id,
        ];
    }
}
