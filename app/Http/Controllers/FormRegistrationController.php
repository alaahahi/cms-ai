<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Card;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserType;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Results;
use App\Models\DoctorResults;
use App\Models\Transactions;
use App\Models\SystemConfig;
use App\Models\CardService;
use App\Models\PendingRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Massage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;





class FormRegistrationController extends Controller
{
    public function __construct(){
        $this->url = env('FRONTEND_URL');

        
        $this->userDateEntry = Cache::remember('user_type_data_entry_id', 60000, function () {
            return UserType::where('name', 'data_entry')->first()->id;
        });
        
        $this->userSeles = Cache::remember('user_type_seles_id', 60000, function () {
            return UserType::where('name', 'seles')->first()->id;
        });
        
        $this->userDoctor = Cache::remember('user_type_doctor_id', 60000, function () {
            return UserType::where('name', 'doctor')->first()->id;
        });
        
        $this->userAccount = Cache::remember('user_type_account_id', 60000, function () {
            return UserType::where('name', 'account')->first()->id;
        });
        
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {   
        try {
            $authUser = auth()?->user();
            if($authUser){
                $wallet = Wallet::where('user_id', $authUser->id)->first();
                $card = $wallet->card ??'';
                return Inertia::render('FormRegistration/Index', ['url'=>$this->url,'card'=>$card]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }
    }
    public function PendingRequest()
    {   
        try {
            $authUser = auth()?->user();
            if($authUser){
                $wallet = Wallet::where('user_id', $authUser->id)->first();
                $card = $wallet->card ??'';
                return Inertia::render('FormRegistration/PendingRequest', ['url'=>$this->url,'card'=>$card]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }
    }
    public function CardsMobile()
    {   
        return Inertia::render('FormRegistration/CardsMobile');
    }
    public function CategoryCardMobile()
    {   
        $card = Card::orderBy('id', 'DESC')->get();

        $parents = Category::whereNull('parent_id')->get();

        return Inertia::render('FormRegistration/CategoryCardMobile', ['card'=> $card,'parents'=>$parents]);
    }

    public function ServicesCardMobile()
    {   
        $card = Card::orderBy('id', 'DESC')->get();

        $category = Category::get();

        return Inertia::render('FormRegistration/ServicesCardMobile', ['card'=> $card,'category'=>$category]);
    }

    public function formRegistrationEdit($id)
    {
        $data = Profile::where('id',$id)->first();
        $sales = User::where('type_id', $this->userSeles)->get();

        return Inertia::render('FormRegistration/Edit', ['url'=>$this->url,'data'=> $data,'sales'=>$sales]);
    }

     
    public function saved()
    {
        $config = SystemConfig::first();
        $users = User::where('type_id', $this->userSeles)->get();
        return Inertia::render('FormRegistrationSaved', ['url'=>$this->url,'users'=>$users,'config'=>$config]);
    }
    public function court()
    {
        try {
            $authUser = auth()?->user();
            if($authUser){
                $users = User::with('wallet')
                ->whereIn('email', ['doctor@cms.com', 'hospital@cms.com', 'main@cms.com'])
                ->orWhere('type_id', $this->userSeles)
                ->whereHas('wallet', function ($query) {
                    $query->where('balance', '>', 0);
                })
                
                ->get();
            return Inertia::render('FormRegistrationCourt', ['url'=>$this->url,'users'=>$users]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }

    }
    public function completed()
    {
        try {
            $authUser = auth()?->user();
            if($authUser){
                $users = User::where('type_id', $this->userSeles)->get();
                return Inertia::render('FormRegistrationCompleted', ['url'=>$this->url,'users'=>$users]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }


    }
    public function show ()
    {
        try {
            $authUser = auth()?->user();
            if($authUser){
                return Inertia::render('Users/Index', ['url'=>$this->url]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }

    }
    public function getIndex()
    {
        $authUser = auth()->user();
        $from = $_GET['from'] ?? 0;
        $to = $_GET['to'] ?? 0;
        $print = $_GET['print'] ?? 0;
        $config = SystemConfig::first();


        $data = Profile::with('user')->orderBy('no', 'DESC');
        if ($from && $to) {
            $data->whereBetween('created', [$from, $to]);
        } 
        if($print){
            $data = $data->get();
            return view('printCards',compact('data','from','to','config'));  
        }

        return Response::json($data->paginate(10), 200);
    }
    public function getIndexPendingRequest()
    {
        $from = $_GET['from'] ?? 0;
        $to = $_GET['to'] ?? 0;
        $print = $_GET['print'] ?? 0;
        $term = $_GET['q'] ?? 0;
    
        // إنشاء مفتاح الكاش فقط إذا لم تكن هناك فلاتر
        $cacheKey = null;
        if (!$from && !$to && !$term) {
            $page = $_GET['page'] ?? 1; // لدعم التصفح
            $cacheKey = "pending_requests_all_page_{$page}";
        }
    
        if ($cacheKey) {
            // إذا لم يكن هناك فلاتر، نحاول الحصول على البيانات من الكاش
            $data = Cache::remember($cacheKey, 600, function () {
                return PendingRequest::with('card')->with('user')->orderBy('id', 'DESC')->paginate(25);
            });
        } else {
            // استعلام مباشر إذا كانت هناك فلاتر
            $query = PendingRequest::with('user')->orderBy('id', 'DESC');
            if ($from && $to) {
                $query->whereBetween('created', [$from, $to]);
            }
            if ($term) {
                $query->where('phone', 'LIKE', '%' . $term . '%')
                    ->orWhere('card_number', 'LIKE', '%' . $term . '%');
            }
            $data = $query->paginate(25);
        }
    
        // إذا كان خيار الطباعة موجودًا، نعيد الصفحة مع البيانات
        if ($print) {
            return view('printCards', compact('data', 'from', 'to'));
        }
    
        // إرجاع البيانات كـ JSON
        return Response::json($data, 200);
    }
    public function getIndexCardsMobile()
    {
        
        $data = Card::orderBy('id', 'DESC')->paginate(25);
         
        return Response::json($data, 200);
    }
    public function getIndexCategoryCardMobile(Request $request)
    {

        $card_id =  $request->card_id ?? 0;
        
        $data = Category::with('parent')->with('card')->where('card_id',$card_id)->orderBy('id', 'DESC')->paginate(25);
         
        return Response::json($data, 200);
    }
    public function getIndexServicesCardMobile(Request $request)
    {

        $card_id =  $request->card_id ?? 0;
        
        $data = CardService::with('category')->with('card')->where('card_id',$card_id)->orderBy('id', 'DESC')->paginate(25);
         
        return Response::json($data, 200);
    }
    public function getIndexSaved()
    {
        $data = Profile::with('user')->orderBy('no', 'DESC')->paginate(10);
        return Response::json($data, 200);
    }
    public function getIndexAccountsSelas()
    { 
        $user_id = $_GET['user_id'] ?? 0;
        $sales = User::with('wallet')->where('id', $user_id)->first();
        $transactions = Transactions::where('wallet_id', $sales?->wallet?->id)->where('is_pay',0)->get();
        
        $data = $transactions;
        $profile_count = Profile::where('user_id', $sales?->id)->where('results', 1)->count();
        
        // Calculate the total amount for transactions with pay == 0
        $totalAmount = $transactions->sum(function ($transaction) {
            return ($transaction->is_pay == 0 && $transaction->type=='in') ? $transaction->amount : 0;
        });

        $debt = $transactions->sum(function ($transaction) {
            return ($transaction->is_pay == 0 && $transaction->type=='debt') ? $transaction->amount : 0;
        });

        // Additional logic to retrieve sales data
        $salesData = [
            'debt'=>$debt,
            'totalAmount' =>  $totalAmount,
            'count' => $profile_count,
            'data' => $data,
            'sales'=>$sales,
            'date'=> Carbon::now()->format('Y-m-d')
        ];
        return Response::json($salesData, 200);
    }
    public function getIndexCompleted()
    {

        $user_id = $_GET['user_id'] ?? 0;
        if($user_id){
            $data = Profile::with('user')->where('user_id',$user_id)->where('results',0)->orderBy('no', 'DESC')->paginate(10);
        }else{
            $data = Profile::with('user')->orderBy('no', 'DESC')->where('results',0)->paginate(10);
        }
        return Response::json($data, 200);
    }
    public function getIndexCourt()
    {
        $user_id = $_GET['user_id'] ?? 0;
        if($user_id){
            $data = Profile::with('user')->where('user_id',$user_id)->orderBy('no', 'DESC')->paginate(10);
        }else{
            $data = Profile::with('user')->orderBy('no', 'DESC')->paginate(10);
        }
        return Response::json($data, 200);
    }
    public function create()
    {
        $config = SystemConfig::first();
        try {
            $authUser = auth()?->user();
            if($authUser){
                                //$usersType = UserType::all();
                $cards= Card::all();
                $sales = User::where('type_id', $this->userSeles)->get();
                $apiKey =$config->api_key;
                $third_title_ar =$config->third_title_ar;
                $phone =$config->phone;
                return Inertia::render('FormRegistration', ['url'=>$this->url,'cards'=> $cards,'sales'=> $sales,'apiKey'=>$apiKey,'third_title_ar'=>$third_title_ar,'phone'=>$phone]);
            }
            else {
                return Inertia::render('Auth/Login');
            }
        } catch (\Throwable $th) {
            return Inertia::render('Auth/Login');
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $maxNo = Profile::max('no');
        $no = $maxNo + 1;
        $authUser = auth()?->user();
        Validator::make($request->all(), [
                    'card_number' =>'required|string|max:255|unique:profile,card_number',
                    'name' => 'required|string|max:255',
             

                     ])->validate();

                     $base64Image = $request->image;

                    // Remove the data:image/{extension};base64, prefix from the base64 string
                    $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);

                    // Decode the base64 string into binary data
                    $imageData = base64_decode($base64Image);

                    // Specify the directory where you want to store the image
                    $imagePath = public_path('uploads');

                    // Create the directory if it doesn't exist
                    if (!file_exists($imagePath)) {
                        mkdir($imagePath, 0777, true);
                    }

                    // Generate a unique name for the image
                    $imageName = time() . '.png'; // You can specify a different image format if needed

                    // Save the decoded image data to the specified directory as an image file
                    file_put_contents("$imagePath/$imageName", $imageData);

                    // Save the image URL in the database
                    $imageUrl = 'uploads/' . $imageName;

         $profile = Profile::create([
                    'card_number'=> $request->card_number,
                    'name' => $request->name,
                    'address' => $request->address,
                    'image' =>  $imageUrl,
                    'phone_number' => $request->phone_number,
                    'card_id' => $request->card_id ?? 1,
                    'user_id' =>$request->saler_id,
                    'family_name'=> $request->family_name,
                    'user_add'=>$authUser?->id,
                    'source' => 'dashboard',
                    'created'=>$request->created,
                    'no'=> $no
                     ]);

        $sales = User::where('type_id', $this->userSeles)->get();
        return response()->json($profile);
    }
    public function storeEdit(Request $request,$id)
    {
        $user = Auth::user();
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
                     ])->validate();
                Profile::where('id',$id)->update([
                    'card_number'=> $request->card_number,
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'family_name'=> $request->family_name,
                    'user_id' =>$request->saler_id,
                    'created'=>$request->created,

                     ]);
            
        return Inertia::render('FormRegistration/Index', ['url'=>$this->url]);
    }


    public function Authorization($request){
        $token = substr($request->header('Authorization') ,7);
        try {
            $id = Crypt::decryptString($token) ;
        $authUser = User::where('id', $id) ? User::where('id', $id)->first() :0;
        if($authUser && !$authUser->is_band){
           return $authUser;
        }
        else
        return  Response::json(['status' => 401,'massage' => 'user not Authorize'],401);
        } catch (\Throwable $th) {
            return  Response::json(['status' => 401,'massage' => 'user not Authorize'],401);
        }
        }

        public function document($id)
    {
        $config=SystemConfig::first();
        $profile=Profile::with('user')->where('id',$id)->first();
        $url=$this->url;
        //return view('PDF',compact('profile','results','resultsDoctor','url'));
        $pdf = PDF::loadView('PDF',compact('profile','url','config'));
        return $pdf->download('pdf.pdf');

       
    }
    public function showfile($id)
    {
        $config=SystemConfig::first();
        $profile=Profile::where('id',$id)->first();
        $results = Results::where('profile_id',$id)->latest()->first();
        $resultsDoctor = DoctorResults::where('profile_id',$id)->latest()->first();
        $url=$this->url;
        return view('show',compact('profile','results','resultsDoctor','url','config'));  
    }

    public function sentToCourt($id)
    {
        Profile::where('id',$id)->update(['results'=>4]);
        return back()->with('success', 'شكراّ,تمت العملية بنجاح');
    }


    public function getProfiles(Request $request)
    {
        $term = $request->get('q');
        $data = Profile::with('user')->orwhere('name', 'LIKE','%'.$term.'%')->orwhere('card_number', 'LIKE','%'.$term.'%')->paginate(10);
        return response()->json($data); 

    }
    
    public function getProfilesSaved(Request $request)
    {
        $term = $request->get('q');
        $data = Profile::with('user')->where('name', 'LIKE','%'.$term.'%')->orwhere('card_number', 'LIKE','%'.$term.'%')->paginate(10);
        return response()->json($data);
    }

    public function getProfilesCompleted(Request $request)
    {
        $term = $request->get('q');
        $data = Profile::with('user')->where('name', 'LIKE','%'.$term.'%')->where('results',3)->orwhere('card_number', 'LIKE','%'.$term.'%')->where('results',3)->where('results',3)->paginate(10);
        return response()->json($data); 
    }
    public function checkCard()
    {
        try {
            $card_id = $_GET['card_id'] ?? 0;
            $profiles=Profile::with('user')->with('appointment.user')->where('card_number',$card_id)->first();
            if($profiles)
            return response()->json($profiles);
            else
            return response()->json(['error' => 'not found card'], 421);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 421);
        }

    }


    public function AddCardsMobile(Request $request)
        {
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'day' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:20',
                'expir_date' => 'required|date',
                'show_on_app' => 'nullable',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // الصورة

            ]);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath =$this->url .'/public/storage/'. $image->store('settings', 'public'); // يخزنها في storage/app/public/cards_images
                $validated['image'] = $imagePath;
            }
            $card = Card::create([
                'name_ar' => $validated['name_ar'],
                'name_en' => $validated['name_en'],
                'day' => $validated['day'],
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'expir_date' => $validated['expir_date'],
                'show_on_app' => $request->boolean('show_on_app'), // يتعامل مع checkbox
                'description_ar' => $validated['description_ar'] ?? '',
                'description_en' => $validated['description_en'] ?? '',
                'image' => $validated['image']
            ]);

            return response()->json([
                'message' => 'تم حفظ البطاقة بنجاح',
                'data' => $card,
            ], 201);
        }
    public function AddCategoryCardsMobile(Request $request)
        {
            // التحقق من البيانات المدخلة
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'color' => 'nullable|string', 
                'card_id' => 'required|numeric',
                'discount' => 'nullable|numeric|min:0|max:100', // التحقق من الخصم
            ]);
            
            // معالجة رفع الصورة إذا كانت موجودة
            if ($request->hasFile('icon')) {
                $image = $request->file('icon');
                $imagePath =  $this->url .'/public/storage/'. $image->store('categories_icons', 'public');
                $validated['icon'] = $imagePath;
            }
            
            // إذا كان id موجودًا في البيانات، نقوم بتحديث التصنيف
            if ($request->id??'') {
                // تحديث التصنيف
                $category = Category::findOrFail($request->id);
                $category->update([
                    'name_ar' => $validated['name_ar'],
                    'name_en' => $validated['name_en'],
                    'card_id' => $validated['card_id'],
                    'color' => $validated['color'] ?? '#fff',
                    'icon' => $validated['icon'] ?? $category->icon, // إذا لم يتم رفع أي صورة، الإبقاء على الصورة الحالية
                    'discount' => $validated['discount'] ?? $category->discount, // إذا لم يتم إدخال خصم، الإبقاء على الخصم القديم
                    'parent_id' => $request->parent_id, // إذا لم يتم إدخال parent_id، الإبقاء على التصنيف الأب القديم
                ]);
            } else {
                // إنشاء تصنيف جديد إذا لم يكن id موجودًا
                $category = Category::create([
                    'name_ar' => $validated['name_ar'],
                    'name_en' => $validated['name_en'],
                    'card_id' => $validated['card_id'],
                    'color' => $validated['color'] ?? '#fff',
                    'icon' => $validated['icon'] ?? null,
                    'discount' => $validated['discount'] ?? 0,
                    'parent_id' => $validated['parent_id'] ?? null, // إذا كان التصنيف فرعيًا، سيحتفظ بـ parent_id
                ]);
            }
            
            // إرجاع استجابة مع بيانات التصنيف
            return response()->json([
                'message' => $request->has('id') ? 'تم تعديل التصنيف بنجاح' : 'تم إضافة التصنيف بنجاح',
                'data' => $category,
            ], 201);
        }
        
    public function UpdateCardsMobile(Request $request, $id)
        {
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'day' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:20',
                'expir_date' => 'required|date',
                'show_on_app' => 'nullable',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
            ]);
        
            $card = Card::findOrFail($id);
        
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath =  $this->url .'/public/storage/'. $image->store('settings', 'public');
                $validated['image'] = $imagePath;
            }
        
            $card->update([
                'name_ar' => $validated['name_ar'],
                'name_en' => $validated['name_en'],
                'day' => $validated['day'],
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'expir_date' => $validated['expir_date'],
                'show_on_app' => $request->boolean('show_on_app'),
                'description_ar' => $validated['description_ar'] ?? '',
                'description_en' => $validated['description_en'] ?? '',
                'image' => $validated['image'] ?? $card->image, // إذا لم يتم رفع صورة جديدة، احتفظ بالقديمة
            ]);
        
            return response()->json([
                'message' => 'تم تعديل البطاقة بنجاح',
                'data' => $card,
            ], 200);
        }
        

    public function AddCardService(Request $request)
        {
            $validated = $request->validate([
                'service_name_ar' => 'required|string|max:255',
                'service_name_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'working_days' => 'nullable|string',
                'working_hours' => 'nullable|string',
                'appointments_per_day' => 'nullable|integer|min:0',
                'expir_date' => 'nullable|date',
                'currency' => 'required|string|max:10',
                'is_popular' => 'nullable',
                'category_id' => 'nullable|exists:categories,id',
                'card_id' => 'required',
                'review_rate' => 'nullable|numeric|min:0|max:5',
                'ex_year' => 'nullable|integer|min:0',
                'show_on_app' => 'nullable',
                'specialty_ar' => 'nullable|string|max:255',
                'specialty_en' => 'nullable|string|max:255',
            ]);
            $validated['show_on_app'] = $request->has('show_on_app') 
            ? (boolean) $request->input('show_on_app') 
            : false;

            $validated['is_popular'] = $request->has('is_popular') 
            ? (boolean) $request->input('is_popular') 
            : false;
            
            // معالجة الصورة
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path =  $this->url .'/public/storage/'. $image->store('card_services_images', 'public');

                $validated['image'] = $path;
            }
        
            $service = CardService::create($validated);
        
            return response()->json([
                'message' => 'تمت إضافة الخدمة بنجاح',
                'data' => $service,
            ], 201);
        }
        public function UpdateAddCardService(Request $request, $id)
        {
            $validated = $request->validate([
                'service_name_ar' => 'required|string|max:255',
                'service_name_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'working_days' => 'nullable|string',
                'working_hours' => 'nullable|string',
                'appointments_per_day' => 'nullable|integer|min:0',
                'expir_date' => 'nullable|date',
                'currency' => 'required|string|max:10',
                'is_popular' => 'nullable',
                'category_id' => 'nullable|exists:categories,id',
                'card_id' => 'required',
                'review_rate' => 'nullable|numeric|min:0|max:5',
                'ex_year' => 'nullable|integer|min:0',
                'show_on_app' => 'nullable',
                'specialty_ar' => 'nullable|string|max:255',
                'specialty_en' => 'nullable|string|max:255',
            ]);
        
            $validated['show_on_app'] = $request->has('show_on_app') 
                ? (boolean) $request->input('show_on_app') 
                : false;
        
            $validated['is_popular'] = $request->has('is_popular') 
                ? (boolean) $request->input('is_popular') 
                : false;
        
            // البحث عن الخدمة
            $service = CardService::findOrFail($id);
        
            // معالجة الصورة الجديدة إن وجدت
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path =  $this->url .'/public/storage/'. $image->store('card_services_images', 'public');
                $validated['image'] = $path;
            }
        
            // تحديث البيانات
            $service->update($validated);
        
            return response()->json([
                'message' => 'تم تحديث الخدمة بنجاح',
                'data' => $service,
            ]);
        }
        
    }