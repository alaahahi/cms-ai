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
    
    
    
}
