<?php
   
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Card;
use App\Models\UserType;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Massage;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(){
         $this->url = env('FRONTEND_URL');
         $this->userAdmin =  UserType::where('name', 'admin')->first()->id;
         $this->userDateEntry =  UserType::where('name', 'data_entry')->first()->id;
         $this->userSeles =  UserType::where('name', 'seles')->first()->id;
         $this->userDoctor =  UserType::where('name', 'doctor')->first()->id;
         $this->userHospital =  UserType::where('name', 'hospital')->first()->id;
         $this->userAccount=  UserType::where('name', 'account')->first()->id;
         $this->userMobile=  UserType::where('name', 'mobile')->first()->id;

         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

     public function sendVerificationCode(Request $request)
     {
    
         $validator = Validator::make($request->all(), [
            'phone_number' => 'required',//|digits:10

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

         $phoneNumber = $request->phone_number;
         $verificationCode = 111111;//rand(100000, 999999);
 
         // تحقق من وجود المستخدم
         $user = User::where('phone_number', $phoneNumber)->first();
 
         if ($user) {
             // تحديث الكود إذا كان المستخدم موجودًا
            $user->update(['verification_code' => $verificationCode]);
         } else {
             // إنشاء مستخدم جديد إذا لم يكن موجودًا
             $user = User::create([
                 'phone_number' => $phoneNumber,
                 'verification_code' => $verificationCode,
                 'verification_user_type'=>'whatsapp',
                 'type_id' => $this->userMobile, // النوع 6
                ]);
         }
         $config = SystemConfig::first();
         // إرسال الكود باستخدام API الواتساب
         $baseUrl = 'https://api.textmebot.com/send.php';
         $apiKey =$config->api_key;
         $textMessage = 'اهلاً وسهلاً بك..' . "\n\n" .
                        'من فريق الهدف المباشر، كل المحبة ودعواتنا بتمام الصحة والعافية.' . "\n\n" .
                        'رمز التحقق الخاص بك هو: ' . $verificationCode;
 
         $response = Http::get($baseUrl, [
             'recipient' => $phoneNumber,
             'apikey' => $apiKey,
             'text' => $textMessage,
             'json' => 'yes',
         ]);
 
         return response()->json([
             'message' => 'رمز التحقق تم إرساله بنجاح.',
             'status' => 'ok',
         ]);
     }
     public function verifyCode(Request $request)
     {
         $request->validate([
             'phone_number' => 'required',
             'verification_code' => 'required|digits:6',
         ]);
         $user = User::where('phone_number', $request->phone_number)
                     ->where('verification_code', $request->verification_code)
                     ->first();
 
         if (!$user) {
             return response()->json(['message' => 'رمز التحقق غير صحيح.'], 401);
         }
         $user->update(['verification_date' => Carbon::now()->format('Y-m-d'),'verification_user_type'=>'whatsapp']);

         // إنشاء التوكن
         $token = JWTAuth::fromUser($user);
 
         return response()->json([
            'message' => 'تم التحقق بنجاح.',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
            'is_admin' => $user->type_id== $this->userHospital ? true : false
        ]);
     }
     public function verifyCodeSms(Request $request)
     {  

 
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required', 
            'verification_code' => 'required|digits:6',
            'sms' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $phoneNumber = $request->phone_number;
        $verificationCode = 111111;//rand(100000, 999999);

          // تحقق من وجود المستخدم
          $user = User::where('phone_number', $phoneNumber)->first();
 
          if ($user) {
              // تحديث الكود إذا كان المستخدم موجودًا
             $user->update(['verification_code' => $verificationCode,'verification_user_type'=>'sms']);
          } else {
              // إنشاء مستخدم جديد إذا لم يكن موجودًا
              $user = User::create([
                  'phone_number' => $phoneNumber,
                  'verification_code' => $verificationCode,
                  'type_id' => $this->userMobile, // النوع 6
                  'verification_user_type'=>'sms'
              ]);
          }

 
      
 
         if (!$user) {
             return response()->json(['message' => 'رمز التحقق غير صحيح.'], 401);
         }
         $user->update(['verification_date' => Carbon::now()->format('Y-m-d')]);

         // إنشاء التوكن
         $token = JWTAuth::fromUser($user);
 
         return response()->json([
             'message' => 'تم التحقق بنجاح.',
             'token' => $token,
             'token_type' => 'bearer',
             'expires_in' => auth('api')->factory()->getTTL() * 60,
             'user' => $user,
             'is_admin' => $user->type_id== $this->userHospital ? true : false
         ]);
     }
    public function index()
    {
        $cards= Card::all();
        return Inertia::render('Users/Index', ['url'=>$this->url,'cards'=>$cards]);
    }
    public function show ()
    {
        return Inertia::render('Users/Index', ['url'=>$this->url]);
    }
    public function getIndex()
    {
        $data = User::with('userType:id,name','wallet')->where('type_id',$this->userSeles);
        return Response::json($data, 200);
    }
    public function create()
    {
        $usersType = UserType::all();
        $userSeles=$this->userSeles;
        $userDoctor =  $this->userDoctor;
        $userHospital = $this->userHospital;
        return Inertia::render('Users/Create',['usersType'=>$usersType,'userSeles'=>$userSeles,'userDoctor'=>$userDoctor,'userHospital'=>$userHospital]);
    }
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'userType' => 'required'
           ])->validate();
           //$userChief_id =User::where('type_id',  $this->userChief)->first()->id ?? 0 ;
            $user = User::create([
                'name' => $request->name,
                'type_id' => $request->userType,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'percentage' => $request->percentage
            ]);
            Wallet::create(['user_id' => $user->id]);
        return Inertia::render('Users/Index', ['url'=>$this->url]);
    }

    public function getCoordinator(Request $request)
    {
        $user =User::where('type_id', $request->id);
        return Response::json(['status' => 200,'massage' => 'users found','data' => $user->get()],200);
    }
    
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function edit(User $User)
    {
        $usersType = UserType::all();
        $userSeles=$this->userSeles;
        $userDoctor =  $this->userDoctor;
        $userHospital = $this->userHospital;
       //$coordinators =User::where('type_id', $this->userCoordinator )->get();
       // $chief =User::where('type_id', $this->userChief )->get();
        return Inertia::render('Users/Edit', [
            'user' => $User,'usersType'=>$usersType,'userSeles'=>$userSeles,'userDoctor'=>$userDoctor,'userHospital'=>$userHospital
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $username = User::where('id', $id)->first()->email;

        switch ($username) {
            case $request->email:
                if ($request->password) {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'password' => [Rules\Password::defaults()],
                    ]);
                    $user = User::find($id)->update([
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                        'type_id' => $request->userType,
                        'percentage' => $request->percentage
                    ]);
                } else {
                    $request->validate([
                        'name' => 'required|string|max:255',
                    ]);
                    $user = User::find($id)->update([
                        'name' => $request->name,
                        'type_id' => $request->userType,
                        'percentage' => $request->percentage
                    ]);
                }
                break;
                
            default:
                if ($request->password) {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|max:255|unique:users',
                    ]);
                    $user = User::find($id)->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'percentage' => $request->percentage
                    ]);
                } else {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|max:255|unique:users',
                        'password' => [Rules\Password::defaults()],
                    ]);
                    $user = User::find($id)->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'percentage' => $request->percentage
                    ]);
                }
                break;
        }
        
        return Inertia::render('Users/Index', ['url'=>$this->url]);

    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy($id)
    {   
     
       // User::where('parent_id',$id)->update(['parent_id' =>null]);
        User::find($id)->delete();
     
        return Inertia::render('Users/Index', ['url'=>$this->url]); 
    }
    public function ban($id)
    {
        $user=User::find($id);
        $user->update(['is_band' => 1]);
        $user->delete();
        return Inertia::render('Users/Index', ['url'=>$this->url]); 
    }
    public function unban($id)
    {
        User::find($id)->update(['is_band' => 0]);
        return Inertia::render('Users/Index', ['url'=>$this->url]); 
    }
    public function login(LoginRequest $request)
    {
        try {
             $request->authenticate();
             $user =User::where('email', $request->email)->first();
             $publickey_receiver =  User::find($user->parent_id)->public_key ?? 0;
             if( $user->device){
                $request->device = $user->device.' | '.$request->device;
             }
             $user->append(['token']);
             if(!$user->is_band){
                if( $user->type_id == $this->userChief){
                    if($request->public_key){
                        $user->update(['public_key' => $request->public_key,'device' =>  $request->device,'publickey_receiver'=> $publickey_receiver]);
                    }
                    return Response::json(['status' => 200,'massage' => 'user found','data' => $user,'token'=> Crypt::encryptString($user->first()->id)],200); 
                }else{
                    if($publickey_receiver){
                    if($request->public_key){
                        $user->update(['public_key' => $request->public_key,'device' => $request->device,'publickey_receiver'=> $publickey_receiver]);
                    }
                       return Response::json(['status' => 200,'massage' => 'user found','data' => $user,'token'=> Crypt::encryptString($user->first()->id)],200); 
                    }else
                    return Response::json(['status' => 407,'massage' => 'user found but publickey for parent notfound'],407); 

                }
             }
             else  return Response::json(['status' => 403,'massage' => 'user is band'],403);
            
             //else  return Response::json(['status' => 407,'massage' => 'user parent dont have public key'],407);
        } catch (\Throwable $th) {
              return   Response::json(['status' => 400,'massage' => 'user not found','error' =>  $th ],400);
        }
        
    }
 
 
    public function userLocation($id)
    {
        // date('Y-m-d H:i:s', strtotime($data['datetime']))
            try {
                $massage =Massage::where('sender_id','=',$id)->get();
                $massage = $massage->map(function ($massage) {
                    return ['lat' => floatval(Crypt::decryptString($massage->Lat)),'lng' => floatval(Crypt::decryptString($massage->lng))] ;
                });          
                 return Response::json(['status' => 200,'massage' => 'massage','data' =>   $massage],200);

            } catch (\Throwable $th) {
                return $th;
                    return  Response::json(['status' => 400,'massage' => 'massage not found'],400);
            }
    }
    public function addUserCard($card_id,$card,$user_id)
    {
        // date('Y-m-d H:i:s', strtotime($data['datetime']))
            try {
                $wallet = Wallet::where('user_id', $user_id)->first();
                $old_card = $wallet->card; 
                $new_card = $card; 
                $wallet->update(['card' => $old_card+$new_card]);
            
                 return Response::json(['status' => 200,'massage' => 'massage','data' =>   $wallet],200);

            } catch (\Throwable $th) {
                return $th;
                    return  Response::json(['status' => 400,'massage' => 'massage not found'],400);
            }
    }

    public function profile()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
            

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
    public function delProfile()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
            

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
    public function profileUpdate(Request $request)
    {
        // الحصول على المستخدم الحالي
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
            
            // التحقق من المدخلات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'family_members_names' => 'nullable|string',
            'birth_date' => 'nullable|date_format:Y-m-d', // التحقق من أن تاريخ الميلاد بصيغة Y-m-d
            'weight' => 'nullable|numeric|min:1|max:500', // الوزن
            'height' => 'nullable|numeric|min:30|max:300', // الطول
            'gender' => 'nullable|in:1,2', // 1 ذكر، 2 أنثى
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // تحديث بيانات المستخدم
        $user->fill([
            'name' => $request->name,
            'address' => $request->address,
            'family_members_names' => $request->family_members_names,
            'birth_date' => $request->birth_date,
            'weight' => $request->weight,
            'height' => $request->height,
            'token' => $request->token,
            'fcm_token'=>$request->fcm_token,
            'network' => $request->network,
            'device' => $request->device,
            'gender' => $request->gender, // 1 أو 2
        ]);

        // حفظ التغييرات
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user->only(['name', 'address', 'family_members_names','birth_date','weight','height','gender','token','fcm_token','network','device']), // إعادة البيانات المحدثة فقط

        ]);


    }
  
    }