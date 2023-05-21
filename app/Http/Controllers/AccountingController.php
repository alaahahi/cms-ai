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
use App\Models\Transactions;
use App\Models\Results;
use App\Models\DoctorResults;
use App\Models\SystemConfig;
use App\Models\Wallet;
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




class AccountingController extends Controller
{
    public function __construct(){
        $this->url = env('FRONTEND_URL');
         $this->userAdmin =  UserType::where('name', 'admin')->first()->id;
         $this->userDateEntry =  UserType::where('name', 'data_entry')->first()->id;
         $this->userSeles =  UserType::where('name', 'seles')->first()->id;
         $this->userDoctor =  UserType::where('name', 'doctor')->first()->id;
         $this->useCourt=  UserType::where('name', 'account')->first()->id;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    
    public function paySelse(Request $request,$id)
    {
        $user=  User::with('wallet')->find($id);
        $transactions =Transactions ::where('wallet_id', $user?->wallet?->id)->where('is_pay',0);
        $amount=$transactions->sum('amount');
        $transactions->update(['is_pay' => 1]);
        $profile_count = Profile::where('user_id', $user?->id)->where('results',1)->update(['results' => 2]);
        $this->decreaseWallet($amount*-1,' تسليم مبلغ '.$amount.' دينار عراقي ',$user->id);
        return Response::json('ok', 200);

    }
    public function receiveCard(Request $request)
    {
        $authUser = auth()->user();

        $profile_id = $_GET['id'] ?? 0;

        $profile = Profile::find($profile_id);
        
        $profile->update(['results'=>1,'user_accepted'=>$authUser->id]);

        $wallet = Wallet::where('user_id', $profile->user_id)->first();

        $card = Card::find($profile->card_id);

        $user = User::find($profile->user_id);

        $old_card = $wallet->card; 

        $old_balance = $wallet->balance;

        $card_price = $card->price;

        $percentage = $user->percentage;

        $new_balance =  $old_balance + $percentage;

        $this->increaseWallet($percentage,' نسبة على البطاقة رقم '.$profile?->card_number,$user->id);

        $wallet->update(['card' => $old_card-1,'balance'=>$new_balance]);

        return Response::json($new_balance, 200);

    }
    public function increaseWallet(int $amount,$desc,$user_id) 
    {
        $user=  User::with('wallet')->find($user_id);
      
        if($id = $user->wallet->id){
        $transactionDetils = ['type' => 'in','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount,];
        Transactions::create($transactionDetils);
        $wallet = Wallet::find($id);
        $wallet->increment('balance', $amount);
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $wallet;
    }

    public function decreaseWallet(int $amount,$desc,$user_id) 
    {
        $user=  User::with('wallet')->find($user_id);

        if($id = $user->wallet->id){
 
        $wallet = Wallet::find($id);
        if( $wallet->balance   >= $amount){
            $wallet->decrement('balance', $amount);
            $transactionDetils = ['type' => 'out','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount,'is_pay'=>1];
            Transactions::create($transactionDetils);
         
        }
        else{
            return null;
        }
        
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $wallet;
    }
    
    }