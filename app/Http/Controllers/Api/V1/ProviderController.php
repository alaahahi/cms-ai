<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ProviderResource;
use App\Http\Resources\V1\ProviderServiceResource;
use App\Models\Provider;
use App\Models\ProviderService;
use App\Services\Provider\CoverageVerificationService;
use App\Services\Provider\LegacyProviderSyncService;
use Illuminate\Http\Request;

class ProviderController extends BaseApiController
{
    public function __construct(
        private LegacyProviderSyncService $sync,
        private CoverageVerificationService $coverage
    ) {
    }

    public function index()
    {
        $providers = Provider::active()->with(['services' => fn ($q) => $q->active()])->orderBy('id')->get();

        if ($providers->isEmpty()) {
            // Lazy sync a batch from legacy if new tables empty
            \App\Models\CardService::query()->limit(20)->get()->each(fn ($s) => $this->sync->syncCardService($s));
            $providers = Provider::active()->with(['services' => fn ($q) => $q->active()])->orderBy('id')->get();
        }

        return $this->success(ProviderResource::collection($providers));
    }

    public function show(int $id)
    {
        $provider = Provider::with(['services.category', 'contracts'])->find($id);

        if (! $provider) {
            return $this->error('Provider not found', [], 404);
        }

        return $this->success(new ProviderResource($provider));
    }

    public function services(Request $request)
    {
        $query = ProviderService::with('provider')->active();

        if ($request->filled('subscription_type_id')) {
            $query->where('subscription_type_id', $request->subscription_type_id);
        }

        if ($request->filled('card_id')) {
            $typeId = \App\Models\SubscriptionType::where('legacy_card_id', $request->card_id)->value('id');
            if ($typeId) {
                $query->where('subscription_type_id', $typeId);
            }
        }

        return $this->success(ProviderServiceResource::collection($query->orderBy('id')->get()));
    }

    public function verifyCoverage(Request $request)
    {
        $data = $request->validate([
            'subscription_number' => 'required|string',
            'provider_service_id' => 'required|integer',
        ]);

        $result = $this->coverage->verify($data['subscription_number'], (int) $data['provider_service_id']);

        return $this->success($result, $result['covered'] ? 'Covered' : 'Not covered');
    }
}
