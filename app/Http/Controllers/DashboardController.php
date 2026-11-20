<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\SystemConfig;
use App\Models\Info;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use App\Models\Message;
use App\Models\Appointment;
use App\Models\Card;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//use thiagoalessio\TesseractOCR\TesseractOCR;
use Intervention\Image\Facades\Image;
use App\Models\ExtractedPhone;
use App\Enums\ContactStatus;

class DashboardController extends Controller
{
    public $url;
    
    public function __construct(){
        $this->url = env('FRONTEND_URL');

     

    }
    public function __invoke(Request $request)
    {
        $results = null;
        $user=  User::all();
       // $client = new Client( $this->url, 'masterKey');
       // $results = $client->stats();
        //dd($results);
        return Inertia::render('dashboard', ['url'=>$this->url]);   

    }
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $results = null;
        $user = User::count();
        $wallet = Wallet::where('user_id', $authUser->id)->first();
        
        $profile = Profile::all();
        $profileUser = $profile->where('user_id', $authUser->id)->count();
    
        // إحصائيات الأرقام
        $numberStats = [];
    
        if ($authUser->type_id == 8) {
            // أدمن: جميع الأرقام
            $numberStats = [
                'total' => ExtractedPhone::count(),
                'Unassigned' => ExtractedPhone::where('status', ContactStatus::Unassigned->value)->count(),
                'Assigned' => ExtractedPhone::where('status', ContactStatus::Assigned->value)->count(),
                'Unknown' => ExtractedPhone::where('status', ContactStatus::Unknown->value)->count(),
                'Busy' => ExtractedPhone::where('status', ContactStatus::Busy->value)->count(),
                'OfferAccepted' => ExtractedPhone::where('status', ContactStatus::OfferAccepted->value)->count(),
                'OfferRejected' => ExtractedPhone::where('status', ContactStatus::OfferRejected->value)->count(),
                'FollowUp' => ExtractedPhone::where('status', ContactStatus::FollowUp->value)->count(),
            ];
        } elseif ($authUser->type_id == 9) {
            // مستخدم: الأرقام الخاصة به فقط
            $numberStats = [
                'total' => ExtractedPhone::where('user_id', $authUser->id)->count(),
                'Unassigned' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::Unassigned->value)->count(),
                'Assigned' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::Assigned->value)->count(),
                'Unknown' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::Unknown->value)->count(),
                'Busy' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::Busy->value)->count(),
                'OfferAccepted' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::OfferAccepted->value)->count(),
                'OfferRejected' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::OfferRejected->value)->count(),
                'FollowUp' => ExtractedPhone::where('user_id', $authUser->id)->where('status', ContactStatus::FollowUp->value)->count(),
            ];
        }
        
        // === إحصائيات البطاقات الجديدة ===
        
        // إحصائيات البطاقات بحسب النوع (Card)
        $cardsStats = DB::table('profile')
            ->join('card', 'profile.card_id', '=', 'card.id')
            ->select(
                'card.id',
                'card.name_ar',
                'card.name_en',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('card.id', 'card.name_ar', 'card.name_en')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name_ar' => $item->name_ar,
                    'name_en' => $item->name_en,
                    'count' => $item->count,
                ];
            });
        
        // إحصائيات البطاقات بحسب الحالة (results)
        $statusStats = [
            'pending_delivery' => Profile::where('results', 0)->count(), // إنتظار تسليم الصندوق
            'delivered' => Profile::where('results', 1)->count(), // تم التسليم
            'completed' => Profile::where('results', 2)->count(), // مكتمل
            'pending' => Profile::where('results', 3)->count(), // معلق
            'total' => Profile::count(),
        ];
        
        // إحصائيات البطاقات بحسب المندوب (User)
        $salesStats = DB::table('profile')
            ->join('users', 'profile.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(*) as count')
            )
            ->where('users.type_id', '!=', 8) // استثناء الأدمن
            ->groupBy('users.id', 'users.name')
            ->orderBy('count', 'desc')
            ->limit(10) // أفضل 10 مندوبين
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => $item->count,
                ];
            });
        
        // إحصائيات البطاقات بحسب المصدر (source)
        $sourceStats = DB::table('profile')
            ->select(
                'source',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('source')
            ->get()
            ->map(function ($item) {
                return [
                    'source' => $item->source ?? 'unknown',
                    'count' => $item->count,
                ];
            });
        
        // إحصائيات البطاقات بحسب الشهر (آخر 6 أشهر)
        // MySQL: DATE_FORMAT / SQLite: strftime
        $monthExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created)"
            : 'DATE_FORMAT(created, "%Y-%m")';

        $monthlyStats = DB::table('profile')
            ->select(
                DB::raw("{$monthExpr} as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => $item->count,
                ];
            });
        
        // === إحصائيات WhatsApp ===
        $whatsappStats = [
            'total' => Message::count(),
            'sent' => Message::where('status', 'sent')->count(),
            'failed' => Message::where('status', 'failed')->count(),
            'pending' => Message::where('status', 'pending')->orWhereNull('status')->count(),
            'batches' => Message::whereNotNull('batch_id')->distinct('batch_id')->count('batch_id'),
            'today' => Message::whereDate('created_at', today())->count(),
            'this_week' => Message::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Message::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        // === إحصائيات المواعيد ===
        $appointmentStats = [
            'total' => Appointment::count(),
            'confirmed' => Appointment::where('is_come', 2)->count(),
            'cancelled' => Appointment::where('is_come', 0)->count(),
            'pending' => Appointment::where('is_come', 1)->orWhereNull('is_come')->count(),
            'today' => Appointment::whereDate('start', today())->count(),
            'this_week' => Appointment::whereBetween('start', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Appointment::whereMonth('start', now()->month)
                ->whereYear('start', now()->year)
                ->count(),
        ];
        
        // === إحصائيات إضافية ===
        $additionalStats = [
            'total_users' => User::count(),
            'total_cards' => Card::count(),
            'active_cards' => Card::active()->count(),
            'total_wallets' => Wallet::count(),
            'total_balance' => Wallet::sum('balance'),
        ];
        
         return Inertia::render('Dashboard', [
            'url' => $this->url,
            'user' => $user,
            'profile' => $profile->count(),
            'cardCompany' => $wallet->card ?? '',
            'comp' => $profile->where('user_accepted', '!=', null)->count(),
            'working' => $profile->where('user_accepted', null)->count(),
            'cardRegister' => $profileUser,
            'balance' => $wallet->balance ?? '',
            'numbersStats' => $numberStats,
            // إحصائيات جديدة
            'cardsStats' => $cardsStats,
            'statusStats' => $statusStats,
            'salesStats' => $salesStats,
            'sourceStats' => $sourceStats,
            'monthlyStats' => $monthlyStats,
            'whatsappStats' => $whatsappStats,
            'appointmentStats' => $appointmentStats,
            'additionalStats' => $additionalStats,
        ]);

    }
    public function getcountComp(Request $request)
    {

        $config = SystemConfig::first();
        $start = $request->get('start');
        $end = $request->get('end');
        if($start && $end ){
            $profile =Profile::with('user')->groupBy('user_id')
            ->select('user_id', \DB::raw('count(*) as count'))
            ->whereBetween('created', [$start, $end])->get();

        return response()->json(['data'=>$profile,'count'=>Profile::whereBetween('created', [$start, $end])->count(),'config'=>$config]); 

        }
        $profile=   Profile::with('user')->groupBy('user_id')
        ->select('user_id', \DB::raw('count(*) as count'))
        ->get();
        return response()->json(['data'=>$profile,'count'=>Profile::count(),'config'=>$config]); 
    }
    
    public function car_check(Request $request)
    {

        return Inertia::render('CarCheck/index');   
    }
    public function searchVINs(Request $request)
    {
        $imageLinks = $request->input('image_links'); // استلام الروابط من الطلب

        foreach ($imageLinks as $imageLink) {
            // استخراج الرقم من الرابط
            preg_match('/(\d+)\.jpg$/', $imageLink, $matches);
            $imageNumber = $matches[1] ?? null;
    
            if ($imageNumber) {
                // البحث عن البروفايل الذي يحتوي على هذه الصورة
                $profile = Profile::where('image', 'like', "uploads/{$imageNumber}.png")->first();
    
                if ($profile) {
                    // تحديث حقل cloud_image إذا وجد البروفايل
                    $profile->update(['cloud_image' => $imageLink]);
                }
            }
        }
    
        return response()->json(['message' => 'تم تحديث الصور بنجاح']);
    }
    public function image(Request $request)
    {
        return Inertia::render('image_exporter');
    }
    public function extractPhonesFromImage1(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,bmp'
        ]);
    
        $results = [];
    
        foreach ($request->file('images') as $index => $image) {
            // حفظ الصورة
            $path = $image->store('images');
            $fullPath = Storage::path($path);
    
            if (!file_exists($fullPath)) {
                $results[] = [
                    'index' => $index,
                    'error' => 'Image file not found'
                ];
                continue;
            }
    
            // قراءة النص باستخدام Tesseract بدون أي تعديل على الصورة
            //$ocr = new TesseractOCR($fullPath);
            //$ocr->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe');
            //$ocr->lang('eng');
            //$ocr->config('tessedit_char_whitelist', '0123456789');
            // يمكن إزالة whitelist أو إضافتها حسب الحاجة
            // $ocr->config('tessedit_char_whitelist', '0123456789');
      

            //$text = $ocr->run();
           // if (trim($text) === '') {
           //     \Log::warning("OCR output was empty for image: {$fullPath}");
           //     $results[] = [
           //         'index' => $index,
           //         'error' => 'No text detected from OCR',
           //     ];
           //     continue;
            //}
            // استخراج أرقام الهواتف (ممكن تعديل regex حسب التنسيق)
            preg_match_all('/0[0-9](?:[\s\-]?\d){7,9}/', $text, $matches);

            $phones = array_map(fn($num) => preg_replace('/[\s\-]/', '', $num), $matches[0]);

            foreach ($phones as $phone) {
                // احفظ الرقم فقط إذا لم يكن موجودًا مسبقًا
                if (!ExtractedPhone::where('phone', $phone)->exists()) {
                    ExtractedPhone::create([
                        'phone' => $phone,
                        'image_name' => $image->getClientOriginalName(),
                        'status' => 1
                    ]);
                }
            }

            // حذف الصورة بعد الانتهاء
            Storage::delete($path);
    
            // تجميع النتائج
            $results[] = [
                'index' => $index,
                'phones' => $phones,
             ];
        }
    
        return response()->json([
            'results' => $results
        ]);
    }
    

   
    public function extractPhonesFromImage(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,bmp'
        ]);

        $results = [];

        foreach ($request->file('images') as $index => $image) {
            $originalName = $image->getClientOriginalName();
            $path = $image->storeAs('public/images', uniqid() . '_' . $originalName);
            $fullPath = Storage::path($path);

            try {
                if (!file_exists($fullPath)) {
                    $results[] = [
                        'index' => $index,
                        'error' => 'Image file not found'
                    ];
                    continue;
                }

                // ✅ قص الصورة
                $this->cropImageForPhoneExtraction($fullPath);

                 $apiKeySelect = env('OCR_SELECT');
                if ($apiKeySelect == 'OCR_SPACE_API_KEY') {
                    $text = $this->extractTextWithOCRSpace($fullPath);
                } else {
                    $text = $this->extractTextWithApi4Ai($fullPath);
                }
                 // 🔍 استخراج الأرقام
                $phones = $this->extractPhonesFromText($text);

                $uniquePhones = [];
                foreach ($phones as $phone) {
                    if (!ExtractedPhone::where('phone', $phone)->exists()) {
                        ExtractedPhone::create([
                            'phone' => $phone,
                            'image_name' => $originalName,
                            'status' => 0,
                        ]);
                        $uniquePhones[] = $phone;
                    }
                }

                $results[] = [
                    'uniquePhones' => $uniquePhones,
                    'index' => $index,
                    'phones' => $phones,
                    'image_name' => $originalName,
                    'text' => $text,
                ];

            } catch (\Exception $e) {
                \Log::error("Error processing image: $originalName, " . $e->getMessage());

                $results[] = [
                    'index' => $index,
                    'error' => 'Failed to process image: ' . $e->getMessage()
                ];
            } finally {
                // حذف الصورة المؤقتة
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }

        return response()->json([
            'results' => $results
        ]);
    }

    private function cropImageForPhoneExtraction(string $imagePath): void
    {
        $img = Image::make($imagePath)->orientate();
        $width = $img->width();
        $height = $img->height();
         // 🧩 قص النصف الأيسر
        $img->crop(intval($width / 2), $height, 0, 0);

        // 🔪 قص 15% من الأعلى والأسفل
        $topCut = intval($img->height() * 0.1);
        $bottomCut = intval($img->height() * 0.15);
        $newHeight = $img->height() - $topCut - $bottomCut;

        $img->crop($img->width(), $newHeight, 0, $topCut);

        // 💾 حفظ فوق نفس الصورة
        $img->save($imagePath);
    }
    private function extractTextWithOCRSpace(string $imagePath): string
    {
        $apiKey = env('OCR_SPACE_API_KEY');
        $imageData = base64_encode(file_get_contents($imagePath));

        $response = Http::timeout(30)->asForm()->post('https://api.ocr.space/parse/image', [
            'apikey' => $apiKey,
            'base64Image' => 'data:image/jpeg;base64,' . $imageData,
            'language' => 'eng',
            'detectOrientation' => 'true',
            'scale' => 'true',
            'OCREngine' => '2'
         ]);
         if ($response->ok() && !empty($response['ParsedResults'][0]['ParsedText'])) {
            return $response['ParsedResults'][0]['ParsedText'];
        }else{
            dd($response);
        }   

        return '';
    }
    private function extractTextWithApi4Ai(string $imagePath): string
    {
        $apiKey = env('API4AI_RAPIDAPI_KEY'); // تأكد من وضعه في .env
    
        $imageData = file_get_contents($imagePath);
    
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $apiKey,
            'X-RapidAPI-Host' => 'ocr43.p.rapidapi.com',
        ])->attach(
            'image', $imageData, basename($imagePath)
        )->post('https://ocr43.p.rapidapi.com/v1/results');
        
         if ($response->ok()) {
            $json = $response->json();
    
            try {
                return $json['results'][0]['entities'][0]['objects'][0]['entities'][0]['text'] ?? '';
            } catch (\Throwable $e) {
                // في حال كانت البنية مختلفة أو مفقودة
                return '';
            }
        }else{
           dd($response);
        }
    
        return '';
    }
    private function extractTextWithTesseract(string $imagePath): string
    {
        return (new TesseractOCR($imagePath))
            ->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe') // تأكد من صحة المسار
            ->lang('eng')
            ->run();
    }

    private function extractPhonesFromText(string $text): array
    {
        preg_match_all('/\b0[7-9][0-9]{9}\b/', $text, $matches);
        return array_unique(array_map(fn($num) => preg_replace('/[\s\-]/', '', $num), $matches[0]));
    }

    
    
    
}
