<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\CardService;
use App\Models\Provider;
use App\Models\ProviderService;
use App\Services\Provider\CoverageVerificationService;
use App\Services\Provider\LegacyProviderSyncService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderController extends Controller
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
            CardService::query()->limit(30)->get()->each(fn ($s) => $this->sync->syncCardService($s));
            $providers = Provider::active()->with(['services' => fn ($q) => $q->active()])->orderBy('id')->get();
        }

        return Inertia::render('Erp/Providers/Index', [
            'providers' => $providers,
            'services' => ProviderService::with('provider')->active()->orderBy('id')->limit(100)->get(),
        ]);
    }

    public function verifyCoverage(Request $request)
    {
        $data = $request->validate([
            'subscription_number' => 'required|string',
            'provider_service_id' => 'required|integer|exists:provider_services,id',
        ]);

        $result = $this->coverage->verify($data['subscription_number'], (int) $data['provider_service_id']);

        return back()->with([
            'coverageResult' => $result,
            'success' => $result['covered'] ? 'التغطية فعالة' : 'غير مغطى',
        ]);
    }
}
