<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Card;
use App\Models\CardService;
use App\Models\Profile;
use App\Models\PendingRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Cache;

class CardsController extends Controller
{
    protected $whatsAppController;

    public function __construct(WhatsAppController $whatsAppController)
    {
        $this->whatsAppController = $whatsAppController; 

    }

    public function activeCards()
    {
        // Fetch active cards
        $cards = Card::active()->get();

        return response()->json([
            'status' => 'success',
            'data' => $cards,
        ]);
    }
    public function activeCardsMe()
    {
        $user = Auth::user();
        $phone_number = $user->phone_number;
        // Fetch active cards
        $cards = Profile::where('phone_number',$phone_number)->get();

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
    public function activeCardServicesPopular(Request $request)
    {
        // تحديد البطاقات الفعالة فقط باستخدام scope 'Active'
        $cards = Card::active()->get();

    
        // الحصول على الخدمات المرتبطة بالبطاقات الفعالة
        $activeCardServices = CardService::where('is_popular', 1 )
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
    public function requestCard(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Validate a single image file
            'card_number' => 'nullable|string', // Check if card_number is unique
            'family_members_names' => 'nullable|string',
            'is_admin' => 'nullable|boolean',
        ];
    
        // Validate input
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            // Handle image upload if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
            }
    
            // Get token and extract user_id if exists
            $token = $request->bearerToken();
            $user_id = null;
    
            if ($token) {
                // Extract user_id from the token
                $user = Auth::guard('api')->setToken($token)->user();
                if ($user) {
                    $user_id = $user->id;
                }
            }
    
            // If no token, use user_id from the request
            if (!$user_id) {
                $user_id = $request->user_id;
            }
    
            // If user_id is still null, save as a pending request
            if (!$user_id) {
                // Store in PendingRequest table for unauthenticated users
                $pendingRequest = PendingRequest::create([
                    'name' => $request->name,
                    'phone' => $request->phone_number,
                    'address' => $request->address,
                    'card_number' => $request->card_number,
                    'family_members_names' => $request->family_members_names,
                    'image' => $imagePath,
                    'source' => 'mobile',
                    'created_at' => now(),
                ]);
    
                // Send WhatsApp message
                $this->whatsAppController->sendWhatsAppMessage(
                    $request->phone_number,
                    'أهلاً وسهلاً بك..' . "\n\n" .
                    'تم استلام طلبك بنجاح. سيتم التواصل معك قريباً لاستكمال الإجراءات.' . "\n\n" .
                    'شكراً لتواصلك معنا!'
                );
    
                return response()->json([
                    'message' => 'Request submitted successfully. Our team will contact you shortly.',
                    'data' => $pendingRequest,
                ], 201);
            }
    
            // If card_number is provided, check if it already exists
            if ($request->card_number) {
                $existingCard = Profile::where('card_number', $request->card_number)->first();
                if ($existingCard) {
                    // If card_number exists, save as pending request instead of creating profile
                    $pendingRequest = PendingRequest::create([
                        'name' => $request->name,
                        'phone' => $request->phone_number,
                        'address' => $request->address,
                        'user_id' => $user_id,
                        'card_number' => $request->card_number,
                        'family_members_names' => $request->family_members_names,
                        'image' => $imagePath,
                        'source' => 'mobile',
                        'created_at' => now(),
                    ]);
    
                   

                    // Send WhatsApp message
                    $this->whatsAppController->sendWhatsAppMessage(
                        $request->phone_number,
                        'أهلاً وسهلاً بك..' . "\n\n" .
                        'رقم البطاقة الذي أدخلته موجود مسبقًا. سيتم التواصل معك قريباً لاستكمال الإجراءات.' . "\n\n" .
                        'شكراً لتواصلك معنا!'
                    );
    
                    return response()->json([
                        'message' => 'Card number already exists. Your request is being processed.',
                        'data' => $pendingRequest,
                    ], 201);
                }
            }
    
            // Handle max 'no' for new profile
            $maxNo = Profile::max('no');
            $no = $maxNo + 1;
            $user = User::where('phone_number', $request->phone_number)->first();
 
            if ($user) {
                
            } else {
                // إنشاء مستخدم جديد إذا لم يكن موجودًا
                $user = User::create([
                    'phone_number' => $request->phone_number,
                    'verification_user_type'=>'selas',
                    'user_type' => 7, // النوع 6
                ]);
            }
            // Store in Profile table for authenticated users
            $profile = Profile::create([
                'no' => $no,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_id' => $user_id,
                'card_number' => $request->card_number,
                'family_name' => $request->family_members_names,
                'image' => $imagePath,
                'results' => $request->is_admin ? 1 : 3,
                'user_add' => $user_id,
                'cardHolder_id'=>$user->id,
                'source' => 'mobile',
                'created' => now()->format('Y-m-d'),
            ]);
         
            // Send WhatsApp message
            $this->whatsAppController->sendWhatsAppMessage(
                $request->phone_number,
                'أهلاً وسهلاً بك..' . "\n\n" .
                'تم تسجيل طلبك بنجاح في حسابك. يمكنك متابعة التفاصيل من خلال التطبيق.' . "\n\n" .
                'شكراً لتواصلك معنا!'
            );
    
            return response()->json([
                'message' => 'Card created successfully',
                'data' => $profile,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process the request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function deletePendingRequest(Request $request)
    {
        // البحث عن الطلب المعلق
        $pendingRequest = PendingRequest::find($request->id);
    
        // التحقق من وجود الطلب
        if (!$pendingRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Pending Request found.',
            ], 404);
        }
    
        // حذف الطلب
        $pendingRequest->delete();
    
        // إزالة الكاش المرتبط
        $this->clearPendingRequestsCache();
    
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function AcceptePendingRequest(Request $request)
    {
        // البحث عن الطلب المعلق
        $pendingRequest = PendingRequest::find($request->id);

        $rules = [
            'card_number' => 'nullable|string|unique:profile,card_number', // تحقق من أن card_number فريد في جدول pending_requests
        ];
        
        // التحقق من المدخلات
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        $user_id = Auth::user()->id;     

        // التحقق من وجود الطلب
        if (!$pendingRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Pending Request found.',
            ], 404);
        }

        $maxNo = Profile::max('no');
        $no = $maxNo + 1;
        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user) {
            
        } else {
            // إنشاء مستخدم جديد إذا لم يكن موجودًا
            $user = User::create([
                'phone_number' => $request->phone_number,
                'verification_user_type'=>'selas',
                'user_type' => 7, // النوع 6
            ]);
        }
        // Store in Profile table for authenticated users
        $profile = Profile::create([
            'no' => $no,
            'name' => $request->name,
            'phone_number' => $request->phone,
            'address' => $request->address,
            'user_id' => $user_id,
            'card_number' => $request->card_number,
            'family_name' => $request->family_members_names,
            'image' => $request->image,
            'results' => $request->is_admin ? 1 : 3,
            'user_add' => $user_id,
            'cardHolder_id'=>$user->id,
            'source' => 'pendingRequest',
            'created' => now()->format('Y-m-d'),
        ]);

        if($profile){
            $pendingRequest->delete();
        }
    
        // إزالة الكاش المرتبط
        $this->clearPendingRequestsCache();
    
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function EditPendingRequest(Request $request)
    {
        // البحث عن الطلب المعلق باستخدام المعرف (ID)
        $pendingRequest = PendingRequest::find($request->id);

        // التحقق إذا كان الطلب المعلق موجودًا
        if (!$pendingRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pending Request not found.',
            ], 404);  // إرجاع استجابة خطأ إذا لم يتم العثور على الطلب
        }

        // تعديل البيانات حسب الطلب (مثال على بعض الحقول)
        $pendingRequest->card_number = $request->card_number;
        $pendingRequest->phone = $request->phone;
        $pendingRequest->address = $request->address;
        $pendingRequest->family_members_names = $request->family_members_names;

        // حفظ التغييرات
        $pendingRequest->save();

        // إزالة الكاش المرتبط
        $this->clearPendingRequestsCache();

        // إرجاع استجابة بنجاح
        return response()->json([
            'status' => 'success',
            'message' => 'Pending Request updated successfully.',
        ], 200);
    }

    
    /**
     * وظيفة لإزالة الكاش المرتبط بالطلبات المعلقة
     */
    protected function clearPendingRequestsCache()
    {
        // تحديد عدد الصفحات المحتملة في الكاش وإزالتها
        $page = 1;
        while (Cache::has("pending_requests_all_page_{$page}")) {
            Cache::forget("pending_requests_all_page_{$page}");
            $page++;
        }
    }

    
    

    
    
    
    
    
}