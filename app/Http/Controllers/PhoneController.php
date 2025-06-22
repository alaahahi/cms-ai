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
    $numbers = ExtractedPhone::where('user_id', '!=', null)->where('status', 1)->get();
    return Inertia::render('Phone/ContactPhone', [
        'numbers' => $numbers
    ]);
}
public function unassignedNumbers()
{
    $numbers = ExtractedPhone::where('user_id', null)->where('status', 1)->get();
    return response()->json($numbers);
}

public function assignNumbers(Request $request)
{
    $numbers = ExtractedPhone::whereIn('id', $request->numbers)->update(['user_id' => $request->user_id, 'status' => ContactStatus::Assigned->value]);
    return response()->json($numbers);
}



   
    
    
    
}
