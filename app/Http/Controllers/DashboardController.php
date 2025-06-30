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

                // المرحلة الأولى: باستخدام OCR.Space
                $apiKeySelect = env('OCR_SELECT');
                if($apiKeySelect == 'OCR_SPACE_API_KEY'){
                    $text = $this->extractTextWithOCRSpace($fullPath);
                }else{
                    $text = $this->extractTextWithApi4Ai($fullPath);
                }
                 
                 // محاولة استخراج الأرقام
                $phones = $this->extractPhonesFromText($text);
                 // المرحلة الثانية: استخدام Tesseract إذا لم توجد أرقام
                    //if (count($phones) === 0) {
                    //    $textFallback = $this->extractTextWithTesseract($fullPath);
                    //    $phones = $this->extractPhonesFromText($textFallback);
                    //}

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
                    'index' => $index,
                    'phones' => $phones,
                    'image_name' => $originalName,
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
    private function extractTextWithOCRSpace(string $imagePath): string
    {
        $apiKey = env('OCR_SPACE_API_KEY');
        $imageData = base64_encode(file_get_contents($imagePath));

        $response = Http::asForm()->post('https://api.ocr.space/parse/image', [
            'apikey' => $apiKey,
            'base64Image' => 'data:image/jpeg;base64,' . $imageData,
            'language' => 'eng',
         ]);
         if ($response->ok() && !empty($response['ParsedResults'][0]['ParsedText'])) {
            return $response['ParsedResults'][0]['ParsedText'];
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
