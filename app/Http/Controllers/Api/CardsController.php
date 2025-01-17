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
    
        // إذا تم إرسال card_id في الطلب، استخدمه، إذا لم يكن هناك card_id، استخدم جميع البطاقات الفعالة
        $cardIds = $request->has('card_id') ? $request->card_id : $cards->pluck('id');
    
        // الحصول على الخدمات المرتبطة بالبطاقات الفعالة
        $activeCardServices = CardService::whereIn('card_id', $cardIds)
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