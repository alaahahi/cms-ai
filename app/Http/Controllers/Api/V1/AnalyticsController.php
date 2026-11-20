<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Analytics\AnalyticsService;

class AnalyticsController extends BaseApiController
{
    public function __construct(private AnalyticsService $analytics)
    {
    }

    public function dashboard()
    {
        return $this->success([
            'kpis' => $this->analytics->dashboardKpis(),
            'cost_analysis' => $this->analytics->costAnalysis(),
            'alerts' => $this->analytics->alerts(),
        ]);
    }

    public function snapshot()
    {
        return $this->success(
            $this->analytics->captureSnapshot(),
            'Analytics snapshot captured'
        );
    }
}
