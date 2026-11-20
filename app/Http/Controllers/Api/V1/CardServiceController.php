<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CardServiceResource;
use App\Services\Catalog\ServiceCatalogService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class CardServiceController extends BaseApiController
{
    public function __construct(private ServiceCatalogService $services)
    {
    }

    public function active(Request $request)
    {
        if (! $request->filled('card_id')) {
            return $this->error('Card ID is required.', [], 400);
        }

        $locale = $request->header('Accept-Language', 'ar');
        $result = $this->services->activeGroupedByCategory((int) $request->card_id, $locale);

        if ($result->isEmpty()) {
            return $this->error('No active card services found.', [], 404);
        }

        return $this->success($result);
    }

    public function search(Request $request)
    {
        $locale = $request->header('Accept-Language', 'ar');

        try {
            $results = $this->services->search((string) $request->input('search_term', ''), $locale);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage(), [], 400);
        }

        return $this->success(CardServiceResource::collection($results));
    }

    public function byCard(int $cardId)
    {
        return $this->success(
            CardServiceResource::collection($this->services->listByCard($cardId))
        );
    }
}
