<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StoreVisitRequest;
use App\Models\Subscription;
use App\Services\Provider\VisitRecordingService;
use Inertia\Inertia;

class VisitController extends Controller
{
    public function __construct(private VisitRecordingService $visits)
    {
    }

    public function index()
    {
        return Inertia::render('Erp/Visits/Index', [
            'visits' => $this->visits->listRecent(),
            'subscriptions' => Subscription::with('type')->orderByDesc('id')->limit(50)->get(),
        ]);
    }

    public function store(StoreVisitRequest $request)
    {
        $data = $request->validated();
        $type = $data['visit_type'];
        unset($data['visit_type'], $data['notes']);

        if ($type === 'lab') {
            $this->visits->recordLab($data, $request->user()->id);
        } else {
            $this->visits->recordDoctor([
                'profile_id' => $data['profile_id'],
                'husband_note' => $request->input('notes'),
            ], $request->user()->id);
        }

        return redirect()
            ->route('erp.visits.index')
            ->with('success', 'تم تسجيل الزيارة');
    }
}
