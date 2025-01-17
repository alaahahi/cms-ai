<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Card;
use App\Models\CardService;

class CardsController extends Controller
{

    public function activeCards()
    {
        // Fetch active cards
        $cards = Card::active()->get();

        return response()->json([
            'status' => 'success',
            'data' => $cards,
        ]);
    }

    public function activeCardServices(Request $request)
    {
        // تحديد البطاقات الفعالة فقط باستخدام scope 'Active'
        $cards = Card::active()->get();

    
        // الحصول على الخدمات المرتبطة بالبطاقات الفعالة
        $activeCardServices = CardService::where('card_id', $request->card_id )
            ->where('expir_date', '>=', now())  // فقط الخدمات التي لم تنته صلاحيتها
            ->get();
    
        // التحقق من وجود خدمات فعالة
        if ($activeCardServices->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active card services found.',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $activeCardServices,
        ], 200);
    }
    
    
    
}