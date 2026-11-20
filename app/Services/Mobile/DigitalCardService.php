<?php

namespace App\Services\Mobile;

use App\Models\Subscription;
use App\Services\BaseService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\URL;

class DigitalCardService extends BaseService
{
    public function __construct(private SubscriptionService $subscriptions)
    {
    }

    public function build(string $subscriptionNumber): ?array
    {
        $subscription = $this->subscriptions->findByCardNumber($subscriptionNumber);
        if (! $subscription) {
            return null;
        }

        $subscription->loadMissing(['members', 'type', 'subscriber', 'primaryMember']);
        $verifyUrl = URL::to('/api/v1/subscriptions/by-number/'.$subscription->subscription_number);

        $qrSvg = null;
        try {
            if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
                $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($verifyUrl);
            }
        } catch (\Throwable $e) {
            $qrSvg = null;
        }

        $type = $subscription->type;

        return [
            'subscription_number' => $subscription->subscription_number,
            'status' => $subscription->status,
            'holder' => optional($subscription->primaryMember)->name
                ?? optional($subscription->members->first())->name,
            'type' => $type->name_ar ?? $type->name_en ?? $type->name ?? null,
            'start_date' => optional($subscription->start_date)->format('Y-m-d'),
            'end_date' => optional($subscription->end_date)->format('Y-m-d'),
            'members_count' => $subscription->members->count(),
            'verify_url' => $verifyUrl,
            'qr_svg' => $qrSvg ? (string) $qrSvg : null,
        ];
    }
}
