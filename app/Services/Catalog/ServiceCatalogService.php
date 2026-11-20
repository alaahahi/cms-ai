<?php

namespace App\Services\Catalog;

use App\Models\CardService;
use App\Models\Category;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ServiceCatalogService extends BaseService
{
    public function activeGroupedByCategory(int $cardId, string $locale = 'ar'): Collection
    {
        app()->setLocale($locale);

        $categories = Category::with([
            'children.services' => function ($query) use ($cardId) {
                $query->where('card_id', $cardId)
                    ->where('expir_date', '>=', now())
                    ->withCount('appointments');
            },
        ])
            ->where('card_id', $cardId)
            ->whereNull('parent_id')
            ->get();

        return $categories->map(function ($category) use ($locale) {
            return [
                'category_id' => $category->id,
                'category_name' => $locale === 'ar' ? $category->name_ar : $category->name_en,
                'category_icon' => $category->icon,
                'category_color' => $category->color,
                'category_discount' => $category->discount,
                'subcategories' => $category->children->map(function ($subcategory) use ($locale) {
                    return [
                        'subcategory_id' => $subcategory->id,
                        'subcategory_name' => $locale === 'ar' ? $subcategory->name_ar : $subcategory->name_en,
                        'services' => $subcategory->services->map(function ($service) use ($locale) {
                            return [
                                'id' => $service->id,
                                'card_id' => $service->card_id,
                                'service_name' => $locale === 'ar' ? $service->service_name_ar : $service->service_name_en,
                                'description' => $locale === 'ar' ? $service->description_ar : $service->description_en,
                                'specialty' => $locale === 'ar' ? $service->specialty_ar : $service->specialty_en,
                                'price' => $service->price,
                                'image' => $service->image,
                                'working_days' => $service->working_days,
                                'working_hours' => $service->working_hours,
                                'appointments_per_day' => $service->appointments_per_day,
                                'expir_date' => $service->expir_date,
                                'show_on_app' => $service->show_on_app ?? 1,
                                'review_rate' => $service->review_rate ?? 5,
                                'ex_year' => $service->ex_year ?? 0,
                                'appointments_count' => $service->appointments_count,
                            ];
                        })->values(),
                    ];
                })->filter(fn ($subcategory) => $subcategory['services']->isNotEmpty())->values(),
            ];
        })->filter(fn ($category) => $category['subcategories']->isNotEmpty())->values();
    }

    public function search(string $searchTerm, string $locale = 'ar'): Collection
    {
        if ($searchTerm === '') {
            throw new InvalidArgumentException('Search term is required.');
        }

        app()->setLocale($locale);

        return CardService::with(['card', 'category'])
            ->where(function ($query) use ($searchTerm, $locale) {
                if ($locale === 'en') {
                    $query->where('service_name_en', 'LIKE', '%'.$searchTerm.'%')
                        ->orWhere('description_en', 'LIKE', '%'.$searchTerm.'%')
                        ->orWhere('specialty_en', 'LIKE', '%'.$searchTerm.'%');
                } else {
                    $query->where('service_name_ar', 'LIKE', '%'.$searchTerm.'%')
                        ->orWhere('description_ar', 'LIKE', '%'.$searchTerm.'%')
                        ->orWhere('specialty_ar', 'LIKE', '%'.$searchTerm.'%');
                }
            })
            ->where('expir_date', '>=', now())
            ->get();
    }

    public function listByCard(int $cardId): Collection
    {
        return CardService::with(['card', 'category'])
            ->where('card_id', $cardId)
            ->where('expir_date', '>=', now())
            ->get();
    }
}
