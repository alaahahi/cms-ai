<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use App\Models\SystemConfig;
use App\Models\Info;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//use thiagoalessio\TesseractOCR\TesseractOCR;
use Intervention\Image\Facades\Image;
use App\Models\ExtractedPhone;
use App\Enums\ContactStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Jobs\CheckWhatsAppNumber;
use Illuminate\Support\Facades\Cache;

class PhoneController extends Controller
{
    public $url;
    
    public function __construct(){
        $this->url = env('FRONTEND_URL');

     

    }

public function sort_phone()
{
    $users = User::where('type_id', 9)->get();
     return Inertia::render('Phone/SortPhone', [
        'users' => $users
    ]);
}
public function contact_phone()
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::Assigned->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::Assigned->value)->get();
    }
    return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}
public function unassignedNumbers()
{
    $numbers = ExtractedPhone::where('user_id', null)->where('status', ContactStatus::Unassigned->value)->get();
    return response()->json($numbers);
}
public function accept_offer(Request $request)
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::OfferAccepted->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::OfferAccepted->value)->get();
    }

     return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}  
public function reject_offer(Request $request)
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::OfferRejected->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::OfferRejected->value)->get();
    }

     return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);

 
}  
public function offer_accepted(Request $request)
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::OfferAccepted->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::OfferAccepted->value)->get();
    }

     return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);

 
}  
 
public function follow_up(Request $request)
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::FollowUp->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::FollowUp->value)->get();
    }
    return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}
public function busy(Request $request)
{
    $authUser = auth()->user();
    if ($authUser->type_id == 8) {
        $numbers = ExtractedPhone::where('status',  ContactStatus::Busy->value)->get();
    }
    elseif($authUser->type_id == 9) {
        $numbers = ExtractedPhone::where('user_id', $authUser->id)->where('status',  ContactStatus::Busy->value)->get();
    }
     return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}





public function assignNumbers(Request $request)
{
    $numbers = ExtractedPhone::whereIn('id', $request->numbers)->update(['user_id' => $request->user_id, 'status' => ContactStatus::Assigned->value]);
    return response()->json($numbers);
}


public function numberDecision(Request $request)
{
    $request->validate([
        'id' => 'required|exists:extracted_phones,id',
        'type' => 'required|string|in:accept,reject,busy,followup,edit',
        'name' => 'nullable|string|max:255',
        'note' => 'nullable|string|max:1000',
    ]);

    $phone = ExtractedPhone::find($request->id);

    // تحديث الاسم والملاحظة دائمًا
    $phone->name = $request->name;
    $phone->note = $request->note;

    // تحديد الحالة حسب نوع العملية
    switch ($request->type) {
        case 'accept':
            $phone->status = ContactStatus::OfferAccepted->value;
            break;
        case 'reject':
            $phone->status = ContactStatus::OfferRejected->value;
            break;
        case 'busy':
            $phone->status = ContactStatus::Busy->value;
            break;
        case 'followup':
            $phone->status = ContactStatus::FollowUp->value;
            break;
        case 'edit':
            // لا تغير الحالة، فقط الاسم والملاحظة
            break;
    }

    $phone->save();

    return response()->json(['success' => true, 'message' => 'تم تحديث البيانات بنجاح.']);
}
public function addUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'type_id' => 9,
    ]);
    return response()->json(['success' => true, 'message' => 'تم إضافة المستخدم بنجاح.']);
}   
public function new_phone()
{
    $numbers = ExtractedPhone::where('user_id', null)->where('status', ContactStatus::Unassigned->value)->get();
    return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}
private function extractPhonesFromText(string $text): array
{
    preg_match_all('/\b0[7-9][0-9]{9}\b/', $text, $matches);
    return array_unique(array_map(fn($num) => preg_replace('/[\s\-]/', '', $num), $matches[0]));
}

public function sendTextPhone(Request $request)
{
    $request->validate([
        'text' => 'required|string',
    ]);

    $rawText = $request->input('text');

    // تقسيم النص إلى أسطر بناءً على Enter أو \n
    $lines = preg_split('/\r\n|\r|\n/', $rawText);

    $phones = [];

    foreach ($lines as $line) {
        // استخلاص الأرقام من كل سطر
        $linePhones = $this->extractPhonesFromText($line);
        $phones = array_merge($phones, $linePhones);
    }

    $phones = array_unique($phones); // إزالة التكرار

    $savedPhones = [];

    foreach ($phones as $phone) {
        if (!ExtractedPhone::where('phone', $phone)->exists()) {
            ExtractedPhone::create([
                'phone' => $phone,
                'image_name' => 'from_text_input',
                'status' => 0,
            ]);
            $savedPhones[] = $phone;
        }
    }
 
    return response()->json([
        'success' => true,
        'phones' => $phones
    ]);
}

// التحقق من عدد من الأرقام دفعة واحدة
public function checkWhatsAppNumbers(Request $request)
{
    $request->validate([
        'phone_ids' => 'required|array',
        'phone_ids.*' => 'required|integer|exists:extracted_phones,id',
        'batch_size' => 'nullable|integer|min:1|max:100',
        'delay_seconds' => 'nullable|integer|min:1|max:30'
    ]);

    $phoneIds = $request->phone_ids;
    $batchSize = $request->batch_size ?? 10; // عدد الأرقام في كل دفعة
    $delaySeconds = $request->delay_seconds ?? 5; // التأخير بالثواني بين كل رقم
    
    $processedCount = 0;
    
    // معالجة الأرقام على دفعات لتجنب الضغط على API
    foreach ($phoneIds as $index => $phoneId) {
        $phone = ExtractedPhone::find($phoneId);
        
        if ($phone && !$phone->whatsapp_status) {
            // تأخير بين كل طلب لتجنب الحظر
            $delay = $index * $delaySeconds;
            
            // إرسال Job مع تأخير محدد
            CheckWhatsAppNumber::dispatch($phone->phone, $phoneId)
                ->delay(now()->addSeconds($delay));
            
            $processedCount++;
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => "تم بدء عملية التحقق من $processedCount رقم. سيتم التحقق تدريجياً لتجنب الحظر.",
        'processed_count' => $processedCount,
        'total_count' => count($phoneIds)
    ]);
}

// التحقق من رقم واحد فقط
public function checkSingleWhatsAppNumber(Request $request)
{
    $request->validate([
        'phone_id' => 'required|integer|exists:extracted_phones,id'
    ]);
    
    $phone = ExtractedPhone::find($request->phone_id);
    
    if (!$phone) {
        return response()->json([
            'success' => false,
            'message' => 'الرقم غير موجود'
        ], 404);
    }
    
    // إرسال Job للتحقق
    CheckWhatsAppNumber::dispatch($phone->phone, $phone->id);
    
    return response()->json([
        'success' => true,
        'message' => 'تم بدء عملية التحقق من الرقم'
    ]);
}

// جلب إحصائيات التحقق
public function getWhatsAppStats()
{
    $total = ExtractedPhone::count();
    $checked = ExtractedPhone::whereNotNull('whatsapp_status')->count();
    $onWhatsApp = ExtractedPhone::where('whatsapp_status', 1)->count();
    $notOnWhatsApp = ExtractedPhone::where('whatsapp_status', 0)->count();
    $pending = ExtractedPhone::whereNull('whatsapp_status')->count();
    
    return response()->json([
        'total_numbers' => $total,
        'checked_numbers' => $checked,
        'on_whatsapp' => $onWhatsApp,
        'not_on_whatsapp' => $notOnWhatsApp,
        'pending' => $pending
    ]);
}

// الحصول على حالة أرقام محددة
public function getPhonesStatus(Request $request)
{
    $request->validate([
        'phone_ids' => 'required|array',
        'phone_ids.*' => 'required|integer|exists:extracted_phones,id'
    ]);
    
    $phones = ExtractedPhone::whereIn('id', $request->phone_ids)
        ->select('id', 'phone', 'whatsapp_status', 'whatsapp_checked_at')
        ->get();
    
    return response()->json([
        'success' => true,
        'phones' => $phones
    ]);
}

// جلب الأرقام الغير محققة منها
public function getUncheckedPhones()
{
    $phones = ExtractedPhone::whereNull('whatsapp_status')
        ->select('id', 'phone', 'user_id', 'name')
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();
    
    return response()->json([
        'success' => true,
        'phones' => $phones,
        'count' => $phones->count()
    ]);
}

// استيراد أرقام من قائمة
public function importPhones(Request $request)
{
    $request->validate([
        'phones' => 'required|array',
        'phones.*' => 'required|string|max:20',
        'delay_seconds' => 'nullable|integer|min:1|max:30'
    ]);
    
    $phones = $request->phones;
    $delaySeconds = $request->delay_seconds ?? 10; // تغيير القيمة الافتراضية من 3 إلى 10 ثواني
    
    $addedCount = 0;
    $existingCount = 0;
    $toCheckCount = 0;
    
    foreach ($phones as $phone) {
        // تنظيف الرقم
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // إضافة 0 في البداية إذا لزم الأمر
        if (strlen($cleanPhone) === 9 && substr($cleanPhone, 0, 1) !== '0') {
            $cleanPhone = '0' . $cleanPhone;
        }
        
        // التحقق من صحة الرقم
        if (strlen($cleanPhone) >= 9 && strlen($cleanPhone) <= 12) {
            // التحقق من عدم وجود الرقم
            if (!ExtractedPhone::where('phone', $cleanPhone)->exists()) {
                $phoneRecord = ExtractedPhone::create([
                    'phone' => $cleanPhone,
                    'image_name' => 'imported_via_web',
                    'status' => 0,
                    'whatsapp_status' => null,
                ]);
                
                $addedCount++;
                
                // بدء عملية التحقق
                CheckWhatsAppNumber::dispatch($phoneRecord->phone, $phoneRecord->id)
                    ->delay(now()->addSeconds($addedCount * $delaySeconds));
                
                $toCheckCount++;
            } else {
                $existingCount++;
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => 'تم الاستيراد بنجاح',
        'count' => $addedCount,
        'existing' => $existingCount,
        'to_check' => $toCheckCount
    ]);
}
}
