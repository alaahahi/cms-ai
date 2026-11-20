<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AnalyticsService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics)
    {
    }

    public function index()
    {
        return Inertia::render('Erp/Dashboard', [
            'kpis' => $this->analytics->dashboardKpis(),
            'costAnalysis' => $this->analytics->costAnalysis(),
            'alerts' => $this->analytics->alerts(),
            'flow' => [
                [
                    'key' => 'subscriptions',
                    'step' => 1,
                    'title' => 'الاشتراكات',
                    'description' => 'إنشاء وإدارة اشتراكات المشتركين',
                    'route' => 'erp.subscriptions.index',
                ],
                [
                    'key' => 'visits',
                    'step' => 2,
                    'title' => 'الزيارات والمزودين',
                    'description' => 'تحقق التغطية وتسجيل زيارات المختبر/الطبيب',
                    'route' => 'erp.visits.index',
                ],
                [
                    'key' => 'sales',
                    'step' => 3,
                    'title' => 'المبيعات',
                    'description' => 'تسجيل البيع وحساب العمولات والأهداف',
                    'route' => 'erp.sales.index',
                ],
                [
                    'key' => 'tickets',
                    'step' => 4,
                    'title' => 'خدمة العملاء',
                    'description' => 'التذاكر والمتابعة حتى الإغلاق',
                    'route' => 'erp.tickets.index',
                ],
                [
                    'key' => 'invoices',
                    'step' => 5,
                    'title' => 'المحاسبة',
                    'description' => 'الفواتير والدفع ومستحقات المزودين',
                    'route' => 'erp.invoices.index',
                ],
                [
                    'key' => 'analytics',
                    'step' => 6,
                    'title' => 'التحليلات',
                    'description' => 'مؤشرات الأداء والتكاليف والتنبيهات',
                    'route' => 'erp.analytics.index',
                ],
            ],
        ]);
    }
}
