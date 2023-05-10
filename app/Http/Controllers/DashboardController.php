<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
//use MeiliSearch\Client;
use App\Models\Info;
use App\Models\User;
use App\Models\Profile;
use Inertia\Inertia;

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
        $results = null;
        $user=  User::all()->count();
        $profile=  Profile::all();

        return Inertia::render('Dashboard', ['url'=>$this->url,'user'=> $user,'profile'=> $profile->count(),'comp'=> $profile->where('user_accepted','!=',null)->count(),'working'=> $profile->where('user_accepted',null)->count()]);   

    }
    public function getcountComp(Request $request)
    {
        $profile=  Profile::all();
        $start = $request->get('start');
        $end = $request->get('end');
        if($start && $end ){
            $countComp =Profile::whereBetween('created_at', [$start, $end])->where('user_accepted','!=',null)->count();
        }
        else{
            $countComp =Profile::where('user_accepted','!=',null)->count();  
        }
        return response()->json($countComp); 
    }
    

    
}
