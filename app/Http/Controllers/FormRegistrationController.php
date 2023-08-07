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
use App\Models\Results;
use App\Models\DoctorResults;
use App\Models\Transactions;
use App\Models\SystemConfig;
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





class FormRegistrationController extends Controller
{
    public function __construct(){
        $this->url = env('FRONTEND_URL');
         $this->userAdmin =  UserType::where('name', 'admin')->first()->id;
         $this->userDateEntry =  UserType::where('name', 'data_entry')->first()->id;
         $this->userSeles =  UserType::where('name', 'seles')->first()->id;
         $this->userDoctor =  UserType::where('name', 'doctor')->first()->id;

         $this->userAccount=  UserType::where('name', 'account')->first()->id;
         $this->userHospital =  UserType::where('name', 'hospital')->first()->id;

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
            $users = User::where('type_id', $this->userSeles)->get();
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
        $data = Profile::with('user')->orderBy('no', 'DESC')->paginate(10);
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

        try {
            $authUser = auth()?->user();
            if($authUser){
                                //$usersType = UserType::all();
                $cards= Card::all();
                $sales = User::where('type_id', $this->userSeles)->get();
                return Inertia::render('FormRegistration', ['url'=>$this->url,'cards'=> $cards,'sales'=> $sales]);
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
                    'phone_number' => 'required|string|max:255',
                    'saler_id'=> 'required|int|max:255',

                     ])->validate();
         $profile = Profile::create([
                    'card_number'=> $request->card_number,
                    'name' => $request->name,
                    'address' => $request->address,
                    // 'image' =>  Image::make($request->image)->resize(100,75)->encode('data-url'),
                    'phone_number' => $request->phone_number,
                    'card_id' => 1,
                    'user_id' =>$request->saler_id,
                    'family_name'=> $request->family_name,
                    'user_add'=>$authUser?->id,
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
    public function labResults($id){
        $profile=Profile::where('id',$id)->first();
        
        return Inertia::render('FormRegistration/AddlabResults', ['url'=>$this->url,'profile_id'=>$id,'profile'=>$profile]);
    }
    public function labResultsEdit($id){
        $profile=Profile::where('id',$id)->first();
        $data = Results::where('profile_id',$id)->latest()->first();
        return Inertia::render('FormRegistration/EditlabResults', ['url'=>$this->url,'profile_id'=>$id,'profile'=>$profile,'data'=>$data]);
    }
    
    public function doctorResults($id){
        $profiles=Profile::where('id',$id)->first();
        $profile = Results::where('profile_id',$id)->latest()->first();
        return Inertia::render('FormRegistration/AddDoctorResults', ['url'=>$this->url,'is_doctor'=>true,'profile'=>$profile ,'profile_id'=>$id,'profiles'=>$profiles]);
    }
    public function doctorResultsEdit($id){
        $profiles=Profile::where('id',$id)->first();
        $profile = Results::where('profile_id',$id)->latest()->first();
        $data = DoctorResults::where('profile_id',$id)->latest()->first();
        return Inertia::render('FormRegistration/EditDoctorResults', ['url'=>$this->url,'is_doctor'=>true,'profile'=>$profile ,'profile_id'=>$id,'profiles'=>$profiles,'data'=>$data]);
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
    
    }