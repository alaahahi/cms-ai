<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AnalyticsService;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analytics)
    {
    }

    public function index()
    {
        return Inertia::render('Erp/Analytics/Index', [
            'kpis' => $this->analytics->dashboardKpis(),
            'costAnalysis' => $this->analytics->costAnalysis(),
            'alerts' => $this->analytics->alerts(),
        ]);
    }

    public function snapshot()
    {
        $this->analytics->captureSnapshot();

        return back()->with('success', 'تم حفظ لقطة التحليلات');
    }
}
