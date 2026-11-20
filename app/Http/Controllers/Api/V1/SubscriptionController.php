<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\SubscriptionResource;
use App\Http\Resources\V1\SubscriptionTypeResource;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseApiController
{
    public function __construct(private SubscriptionService $subscriptions)
    {
    }

    public function types()
    {
        return $this->success(
            SubscriptionTypeResource::collection($this->subscriptions->listActiveTypes())
        );
    }

    public function index()
    {
        $user = Auth::user();

        return $this->success(
            SubscriptionResource::collection(
                $this->subscriptions->listForSubscriber($user->id)
            )
        );
    }

    public function show($id)
    {
        $subscription = $this->subscriptions->find($id);

        if (! $subscription) {
            return $this->error('Subscription not found', [], 404);
        }

        return $this->success(new SubscriptionResource($subscription->loadMissing(['members', 'type', 'subscriber'])));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'card_number' => 'required|string|max:100|unique:profile,card_number',
            'card_id' => 'nullable|integer|exists:card,id',
            'address' => 'nullable|string',
            'family_name' => 'nullable|string',
            'birthdate' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'source' => 'nullable|string',
            'results' => 'nullable|integer',
        ]);

        $data['user_id'] = Auth::id();
        $data['results'] = $data['results'] ?? 1;
        $data['created'] = now()->format('Y-m-d');
        $data['source'] = $data['source'] ?? 'api';

        $profile = $this->subscriptions->createFromLegacyPayload($data);
        $subscription = $this->subscriptions->findByCardNumber($profile->card_number);

        return $this->success(
            new SubscriptionResource($subscription->load(['members', 'type', 'subscriber'])),
            'Subscription created',
            201
        );
    }

    public function findByNumber(string $number)
    {
        $subscription = $this->subscriptions->findByCardNumber($number);

        if (! $subscription) {
            return $this->error('Subscription not found', [], 404);
        }

        return $this->success(new SubscriptionResource($subscription->loadMissing(['members', 'type'])));
    }
}
