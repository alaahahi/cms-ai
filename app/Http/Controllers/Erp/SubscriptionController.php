<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StoreSubscriptionRequest;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptions)
    {
    }

    public function index(Request $request)
    {
        return Inertia::render('Erp/Subscriptions/Index', [
            'subscriptions' => $this->subscriptions->listAll($request->get('status')),
            'types' => $this->subscriptions->listActiveTypes(),
            'filters' => [
                'status' => $request->get('status'),
            ],
        ]);
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['results'] = 1;
        $data['created'] = now()->format('Y-m-d');
        $data['source'] = $data['source'] ?? 'erp';

        $profile = $this->subscriptions->createFromLegacyPayload($data);
        $subscription = $this->subscriptions->findByCardNumber($profile->card_number);

        return redirect()
            ->route('erp.subscriptions.index')
            ->with('success', 'تم إنشاء الاشتراك #'.($subscription->subscription_number ?? $profile->card_number));
    }

    public function show(int $id)
    {
        $subscription = $this->subscriptions->find($id);
        abort_unless($subscription instanceof Subscription, 404);

        return Inertia::render('Erp/Subscriptions/Show', [
            'subscription' => $subscription->loadMissing(['members', 'type', 'subscriber', 'documents']),
        ]);
    }
}
