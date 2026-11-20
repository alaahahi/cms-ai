<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\VisitResource;
use App\Models\Visit;
use App\Services\Provider\VisitRecordingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends BaseApiController
{
    public function __construct(private VisitRecordingService $visits)
    {
    }

    public function index(Request $request)
    {
        $query = Visit::with(['provider', 'providerService'])->orderByDesc('id');

        if ($request->filled('subscription_id')) {
            $query->where('subscription_id', $request->subscription_id);
        }

        if ($request->filled('visit_type')) {
            $query->where('visit_type', $request->visit_type);
        }

        return $this->success(VisitResource::collection($query->limit(100)->get()));
    }

    public function show(int $id)
    {
        $visit = Visit::with(['provider', 'providerService'])->find($id);

        if (! $visit) {
            return $this->error('Visit not found', [], 404);
        }

        return $this->success(new VisitResource($visit));
    }

    public function storeLab(Request $request)
    {
        $data = $request->validate([
            'profile_id' => 'required|integer',
            'husband_hb' => 'nullable|string',
            'wife_hb' => 'nullable|string',
            'husband_hbs' => 'nullable|string',
            'wife_hbs' => 'nullable|string',
        ]);

        $visit = $this->visits->recordLab($data, Auth::id());

        return $this->success(new VisitResource($visit), 'Lab visit recorded', 201);
    }

    public function storeDoctor(Request $request)
    {
        $data = $request->validate([
            'profile_id' => 'required|integer',
            'husband_results' => 'nullable|string',
            'wife_results' => 'nullable|string',
            'husband_note' => 'nullable|string',
            'wife_note' => 'nullable|string',
        ]);

        $visit = $this->visits->recordDoctor($data, Auth::id());

        return $this->success(new VisitResource($visit), 'Doctor visit recorded', 201);
    }
}
