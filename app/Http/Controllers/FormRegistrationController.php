<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserType;
use App\Models\Results;
use App\Models\DoctorResults;
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
         $this->userLab =  UserType::where('name', 'lab')->first()->id;
         $this->userDoctor =  UserType::where('name', 'doctor')->first()->id;
         $this->useCourt=  UserType::where('name', 'court')->first()->id;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('FormRegistration/Index', ['url'=>$this->url]);
    }

    public function formRegistrationEdit($id)
    {
        $data = Profile::where('id',$id)->first();
        return Inertia::render('FormRegistration/Edit', ['url'=>$this->url,'data'=> $data]);
    }

     
    public function saved()
    {
        return Inertia::render('FormRegistrationSaved', ['url'=>$this->url]);
    }
    public function court()
    {
        return Inertia::render('FormRegistrationCourt', ['url'=>$this->url]);
    }
    public function completed()
    {
        return Inertia::render('FormRegistrationCompleted', ['url'=>$this->url]);
    }
    public function show ()
    {
        return Inertia::render('Users/Index', ['url'=>$this->url]);
    }
    public function getIndex()
    {
        $data = Profile::where('user_accepted',null)->orderBy('no', 'DESC')->paginate(10);
        return Response::json($data, 200);
    }
    public function getIndexSaved()
    {
        $data = Profile::orderBy('no', 'DESC')->paginate(10);
        return Response::json($data, 200);
    }
    public function getIndexCompleted()
    {
        $data = Profile::where('results',3)->orderBy('no', 'DESC')->paginate(10);
        return Response::json($data, 200);
    }
    public function getIndexCourt()
    {
        $data = Profile::where('results',4)->orderBy('no', 'DESC')->paginate(10);
        return Response::json($data, 200);
    }
    public function create()
    {
        $usersType = UserType::all();
        return Inertia::render('FormRegistration', ['url'=>$this->url]);
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

        $user = Auth::user();
        Validator::make($request->all(), [
                    'card_number' =>'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'birthdate' => 'required|string|max:255',
                    'certification' => 'required|string|max:255',
                    'job' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'phone_number' => 'required|string|max:255',
                    'invoice_number' => 'required|string|max:255',
                    'relatives' => 'required|string|max:255',
                     ])->validate();
                $user = Profile::create([
                    'card_number'=> $request->card_number,
                    'name' => $request->name,
                    'birthdate' => $request->birthdate,
                    'certification' => $request->certification,
                    'job' => $request->job,
                    'address' => $request->address,
                    // 'image' =>  Image::make($request->image)->resize(100,75)->encode('data-url'),
                    'phone_number' => $request->phone_number,
                    'invoice_number' => $request->invoice_number,
                    'relatives' => $request->relatives,
                    'user_id' =>$user->id,
                    'family_name'=> $request->family_name,
                    'no'=> $no 
                     ]);
            
        return Inertia::render('FormRegistration/Index', ['url'=>$this->url]);
    }
    public function storeEdit(Request $request,$id)
    {
        $user = Auth::user();
        Validator::make($request->all(), [
            'card_number' =>'required|string|max:255',
            'name' => 'required|string|max:255',
            'birthdate' => 'required|string|max:255',
            'certification' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'invoice_number' => 'required|string|max:255',
            'relatives' => 'required|string|max:255',
                     ])->validate();
                Profile::where('id',$id)->update([
                    'card_number'=> $request->card_number,
                    'name' => $request->name,
                    'birthdate' => $request->birthdate,
                    'certification' => $request->certification,
                    'job' => $request->job,
                    'address' => $request->address,
                    // 'image' =>  Image::make($request->image)->resize(100,75)->encode('data-url'),
                    'phone_number' => $request->phone_number,
                    'invoice_number' => $request->invoice_number,
                    'relatives' => $request->relatives,
                    'user_id' =>$user->id,
                    'family_name'=> $request->family_name,
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
        $profile=Profile::where('id',$id)->first();
        $results = Results::where('profile_id',$id)->latest()->first();
        $resultsDoctor = DoctorResults::where('profile_id',$id)->latest()->first();
        $url=$this->url;
        //return view('PDF',compact('profile','results','resultsDoctor','url'));
        $pdf = PDF::loadView('PDF',compact('profile','results','resultsDoctor','url','config'));
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
        $data = Profile::orwhere('name', 'LIKE','%'.$term.'%')->where('user_accepted',null)->orwhere('wife_name', 'LIKE','%'.$term.'%')->where('user_accepted',null)->orwhere('invoice_number',$term)->where('user_accepted',null)->paginate(10);
        return response()->json($data); 

    }
    
    public function getProfilesSaved(Request $request)
    {
        $term = $request->get('q');
        $data = Profile::where('name', 'LIKE','%'.$term.'%')->orwhere('wife_name', 'LIKE','%'.$term.'%')->orwhere('invoice_number',$term)->paginate(10);
        return response()->json($data);
    }

    public function getProfilesCompleted(Request $request)
    {
        $term = $request->get('q');
        $data = Profile::where('name', 'LIKE','%'.$term.'%')->where('results',3)->orwhere('wife_name', 'LIKE','%'.$term.'%')->where('results',3)->orwhere('invoice_number',$term)->where('results',3)->paginate(10);
        return response()->json($data); 
    }

    
    }