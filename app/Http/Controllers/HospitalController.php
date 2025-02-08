<?php
   
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserType;
use App\Models\Appointment;
use App\Models\DoctorResults;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Massage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;

class HospitalController extends Controller
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
    public function index(Request $request)
    {
        $hospital = $request->input('hospital');
        // Load hospital-specific data and views
        return view('hospital.index', compact('hospital'));
    }
    public function hospital(Request $request)
    {
        $users = User::where('type_id',$this->userDoctor)->get();
        return Inertia::render('Hospital/Index', ['url'=>$this->url,'users'=>$users]);
    }
    public function show ()
    {
        return Inertia::render('Hospital/Index', ['url'=>$this->url]);
    }
    public function getIndex()
    {
        $user_id = $_GET['user_id'] ?? 0;
        if($user_id){
            $data = Appointment::with('user')->where('user_id',$user_id)->orderBy('id','desc')->paginate(10);
        }else{
            $data = Appointment::with('user')->orderBy('id','desc')->paginate(10);
        }
        return Response::json($data, 200);
    }
    public function create()
    {
        $userDoctor = User::where('type_id',$this->userDoctor)->get();
        return Inertia::render('Hospital/Add', ['url'=>$this->url,'userDoctor'=>$userDoctor]);
    }
    public function store(Request $request)
    {

        $userDoctor = User::where('type_id',$this->userDoctor)->get();
        $users = User::where('type_id',$this->userDoctor)->get();

        Validator::make($request->all(), [
        'user_id'=> 'required|int|max:50000',
        'card_id'=> 'required|int|max:50000',
       ])->validate();

        $profile = Profile::where('card_number',$request->card_id)->first();
        if($profile){
            $appointment = Appointment::create([
                'user_id' =>$request->user_id,
                'card_id' => $profile->card_number,
                'note' => $request->note,
                'start' => $this->convertToTimestamp($request->start),
                'end' => $this->convertToTimestamp($request->end),
                 ]);
                 return Inertia::render('Hospital/Index', ['url'=>$this->url,'users'=>$users])->with('success', 'شكراّ,تمت العملية بنجاح');
        }else{
            $new = Profile::create([
                'name' =>$request->note,
                'user_id' => $request->user_id,
                'card_number' => $request->card_id,
                'source' => 'appointment',
                 ]);
                 $appointment = Appointment::create([
                    'user_id' =>$request->user_id,
                    'card_id' => $new->card_number,
                    'note' => $request->note,
                    'start' => $this->convertToTimestamp($request->start),
                    'end' => $this->convertToTimestamp($request->end),
                     ]);
            return Inertia::render('Hospital/Index', ['url'=>$this->url,'users'=>$users])->with('success', 'شكراّ,تمت العملية بنجاح');
        }

    }
    public function edit(Request $request,$id)
    {
        $userDoctor = User::where('type_id',$this->userDoctor)->get();
        $appointment=Appointment::find($id);
        return Inertia::render('Hospital/Edit', ['url'=>$this->url,'userDoctor'=>$userDoctor,'appointment'=>$appointment]);
    }
    public function livesearchAppointment(Request $request)
    {
        $term = $request->get('q');
        if($term){
            $data = Appointment::with('user')->where('card_id',$term)->paginate(10);
        }
        else{
            $data = Appointment::with('user')->paginate(10);

        }
        return response()->json($data); 

    }
    public function storeEdit(Request $request)
    {
        $userDoctor = User::where('type_id',$this->userDoctor)->get();
        $users = User::where('type_id',$this->userDoctor)->get();

        Validator::make($request->all(), [
        'user_id'=> 'required|int|max:50000',
        'card_id'=> 'required|int|max:50000',

       ])->validate();

        $profile = Profile::where('card_number',$request->card_id)->first();
        if($profile){
            $appointment = Appointment::find($request->id)->update([
                'user_id' =>$request->user_id,
                'card_id' => $request->card_id,
                'start' => $this->convertToTimestamp($request->start),
                'end' => $this->convertToTimestamp($request->end),
                'note' => $request->note,

                'is_come' => 1,
                 ]);
                 return Inertia::render('Hospital/Index', ['url'=>$this->url,'users'=>$users])->with('success', 'شكراّ,تمت التعديل بنجاح');
        }else
        return Inertia::render('Hospital/Edit', ['url'=>$this->url,'userDoctor'=>$userDoctor])->with('success', 'رقم البطاقة غير صالح او لم يتم تسجيل البطاقة');
    }
    public function convertToTimestamp($datetime)
    {
        $carbon = Carbon::parse($datetime);
        $timestamp = $carbon;
        return $timestamp;
    }
    public function appointmentCancel()
    {
        $id = $_GET['id'] ?? 0;
        $appointment = Appointment::find($id)->update([
            'is_come' =>0,
             ]);
             return Response::json($appointment, 200);
            
    }
    public function appointmentCome()
    {
        $id = $_GET['id'] ?? 0;
        $appointment = Appointment::find($id)->update([
            'is_come' =>2,
             ]);
             return Response::json($appointment, 200);
    }
    public function hospitalPrint(){
        $start = $_GET['start']??0;
        $end = $_GET['end']??0;
        if($start&&$end){
            $doctor =Appointment::with('user')->whereBetween('created_at', [$start, $end])->groupBy('user_id')
            ->select('user_id', \DB::raw('count(*) as count'))->get();
            $appointment = Appointment::with('user')->with('profile')->orderBy('card_id')->whereBetween('created_at', [$start, $end])->get();
            return view('printHospital',compact('appointment','doctor'));
    
        }else{
            $doctor =Appointment::with('user')->groupBy('user_id')
            ->select('user_id', \DB::raw('count(*) as count'))->get();
            $appointment = Appointment::with('user')->with('profile')->orderBy('card_id')->get();
            return view('printHospital',compact('appointment','doctor'));
        }
    }

    public function storeAppointment(Request $request)
    {
        // الحصول على المستخدم المصادق
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }

        // التحقق من المدخلات
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required|int|max:50000',
            'note' => 'nullable|string|max:500',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }


        $profile = Profile::find($request->profile_id);

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'not have active cards',
            ], 422);
        }

        

        $appointment = Appointment::create([
            'user_id' => $user->id, // استخدام id المستخدم المصادق
            'card_id' => $profile->card_id,
            'note' => $request->note,
            'start' => $this->convertToTimestamp($request->start),
            'end' => $this->convertToTimestamp($request->end),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment booked successfully',
            'appointment' => $appointment,
        ], 201);
    }
    
    public function appointment(Request $request)
    {
        // الحصول على المستخدم المصادق
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $appointment = Appointment::where('user_id',$user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment list successfully',
            'appointment' => $appointment,
        ], 201);
    }
    public function updateAppointment(Request $request, $id)
    {
        // الحصول على المستخدم المصادق
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
    
        // التحقق من المدخلات
        $validator = Validator::make($request->all(), [
            'note' => 'nullable|string|max:500',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // البحث عن الموعد
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found',
            ], 404);
        }
    
        // التأكد من أن التعديل يتم على موعد مستقبلي فقط
        if (Carbon::parse($appointment->start)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot update past appointments',
            ], 403);
        }
    
        // تحديث بيانات الموعد
        $appointment->update([
            'note' => $request->note,
            'start' => $this->convertToTimestamp($request->start),
            'end' => $this->convertToTimestamp($request->end),
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment,
        ], 200);
    }
    
    public function deleteAppointment($id)
    {
        // الحصول على المستخدم المصادق
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
    
        // البحث عن الموعد
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found',
            ], 404);
        }
    
        // التأكد من أن الحذف يتم على موعد مستقبلي فقط
        if (Carbon::parse($appointment->start)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete past appointments',
            ], 403);
        }
    
        // حذف الموعد
        $appointment->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Appointment deleted successfully',
        ], 200);
    }
    
    }