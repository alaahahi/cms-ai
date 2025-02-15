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

class DashboardController extends Controller
{
    
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
        $user=  User::all()->count();
        $wallet = Wallet::where('user_id',$authUser->id)->first();
        
        $profile=  Profile::all();
        $profileUser=  $profile->where('user_id', $authUser->id)->count();
        return Inertia::render('Dashboard', ['url'=>$this->url,'user'=> $user,'profile'=> $profile->count(),'cardCompany'=>$wallet->card??'','comp'=> $profile->where('user_accepted','!=',null)->count(),'working'=> $profile->where('user_accepted',null)->count(),'cardRegister'=>$profileUser,'balance'=>$wallet->balance??'']);   

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
}
