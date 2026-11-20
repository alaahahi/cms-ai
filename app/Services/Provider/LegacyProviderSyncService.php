<?php

namespace App\Services\Provider;

use App\Models\CardService;
use App\Models\Category;
use App\Models\Contract;
use App\Models\DoctorResults;
use App\Models\Provider;
use App\Models\ProviderService;
use App\Models\Results;
use App\Models\ServiceCategory;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\Visit;
use App\Services\BaseService;
use App\Services\Subscription\LegacySubscriptionSyncService;

class LegacyProviderSyncService extends BaseService
{
    public function __construct(private LegacySubscriptionSyncService $subscriptionSync)
    {
    }

    public function syncAll(): array
    {
        $categories = 0;
        $providers = 0;
        $visits = 0;

        Category::query()->orderBy('id')->chunkById(100, function ($rows) use (&$categories) {
            foreach ($rows as $category) {
                $this->syncCategory($category);
                $categories++;
            }
        });

        // Re-link parents after all categories exist
        Category::query()->whereNotNull('parent_id')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $category) {
                $this->syncCategory($category);
            }
        });

        CardService::query()->orderBy('id')->chunkById(50, function ($rows) use (&$providers) {
            foreach ($rows as $service) {
                $this->syncCardService($service);
                $providers++;
            }
        });

        if (Results::query()->exists()) {
            Results::query()->orderBy('id')->chunkById(50, function ($rows) use (&$visits) {
                foreach ($rows as $row) {
                    $this->syncLabResult($row);
                    $visits++;
                }
            });
        }

        if (DoctorResults::query()->exists()) {
            DoctorResults::query()->orderBy('id')->chunkById(50, function ($rows) use (&$visits) {
                foreach ($rows as $row) {
                    $this->syncDoctorResult($row);
                    $visits++;
                }
            });
        }

        return compact('categories', 'providers', 'visits');
    }

    public function syncCategory(Category $category): ServiceCategory
    {
        $subscriptionTypeId = null;
        if ($category->card_id) {
            $type = SubscriptionType::where('legacy_card_id', $category->card_id)->first();
            if (! $type && $category->card) {
                $type = $this->subscriptionSync->syncCard($category->card);
            }
            $subscriptionTypeId = $type?->id;
        }

        $parentId = null;
        if ($category->parent_id) {
            $parentId = ServiceCategory::where('legacy_category_id', $category->parent_id)->value('id');
        }

        return ServiceCategory::updateOrCreate(
            ['legacy_category_id' => $category->id],
            [
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'icon' => $category->icon,
                'color' => $category->color,
                'discount' => $category->discount ?? 0,
                'parent_id' => $parentId,
                'subscription_type_id' => $subscriptionTypeId,
            ]
        );
    }

    public function syncCardService(CardService $service): ProviderService
    {
        $provider = Provider::updateOrCreate(
            ['legacy_card_service_id' => $service->id],
            [
                'name_ar' => $service->service_name_ar,
                'name_en' => $service->service_name_en,
                'specialty_ar' => $service->specialty_ar,
                'specialty_en' => $service->specialty_en,
                'description_ar' => $service->description_ar,
                'description_en' => $service->description_en,
                'image' => $service->image,
                'review_rate' => $service->review_rate ?? 5,
                'ex_year' => $service->ex_year ?? 0,
                'is_active' => true,
            ]
        );

        $subscriptionTypeId = null;
        if ($service->card_id) {
            $type = SubscriptionType::where('legacy_card_id', $service->card_id)->first();
            if (! $type && $service->card) {
                $type = $this->subscriptionSync->syncCard($service->card);
            }
            $subscriptionTypeId = $type?->id;
        }

        $categoryId = null;
        if ($service->category_id) {
            $category = ServiceCategory::where('legacy_category_id', $service->category_id)->first();
            if (! $category && $service->category) {
                $category = $this->syncCategory($service->category);
            }
            $categoryId = $category?->id;
        }

        // Store raw working days/hours strings from attributes (bypass accessors)
        $rawDays = $service->getAttributes()['working_days'] ?? null;
        $rawHours = $service->getAttributes()['working_hours'] ?? null;

        $providerService = ProviderService::updateOrCreate(
            ['legacy_card_service_id' => $service->id],
            [
                'provider_id' => $provider->id,
                'service_category_id' => $categoryId,
                'subscription_type_id' => $subscriptionTypeId,
                'price' => $service->price,
                'currency' => $service->currency,
                'working_days' => is_array($rawDays) ? implode(',', $rawDays) : $rawDays,
                'working_hours' => is_array($rawHours) ? (($rawHours['start'] ?? '').'-'.($rawHours['end'] ?? '')) : $rawHours,
                'appointments_per_day' => $service->appointments_per_day ?? 5,
                'expir_date' => $service->expir_date,
                'show_on_app' => $service->show_on_app ?? true,
                'is_popular' => $service->is_popular ?? false,
            ]
        );

        Contract::firstOrCreate(
            ['provider_id' => $provider->id, 'status' => 'active'],
            [
                'start_date' => now()->toDateString(),
                'end_date' => optional($service->expir_date)->format('Y-m-d'),
                'terms' => 'Synced from legacy card service #'.$service->id,
            ]
        );

        return $providerService->fresh(['provider', 'category']);
    }

    public function syncLabResult(Results $result): Visit
    {
        $subscriptionId = Subscription::where('legacy_profile_id', $result->profile_id)->value('id');

        return Visit::updateOrCreate(
            ['legacy_results_id' => $result->id],
            [
                'subscription_id' => $subscriptionId,
                'legacy_profile_id' => $result->profile_id,
                'visit_type' => Visit::TYPE_LAB,
                'recorded_by' => $result->user_id,
                'payload' => collect($result->getAttributes())->except(['id', 'created_at', 'updated_at'])->all(),
                'visited_at' => $result->created_at,
            ]
        );
    }

    public function syncDoctorResult(DoctorResults $result): Visit
    {
        $subscriptionId = Subscription::where('legacy_profile_id', $result->profile_id)->value('id');

        return Visit::updateOrCreate(
            ['legacy_doctor_results_id' => $result->id],
            [
                'subscription_id' => $subscriptionId,
                'legacy_profile_id' => $result->profile_id,
                'visit_type' => Visit::TYPE_DOCTOR,
                'recorded_by' => $result->user_id,
                'payload' => collect($result->getAttributes())->except(['id', 'created_at', 'updated_at'])->all(),
                'visited_at' => $result->created_at,
            ]
        );
    }
}
