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
use Illuminate\Support\Facades\DB;




class AccountingController extends Controller
{
    public function __construct(){
        $this->url = env('FRONTEND_URL');
         $this->userAdmin =  UserType::where('name', 'admin')->first()->id;
         $this->userDateEntry =  UserType::where('name', 'data_entry')->first()->id;
         $this->userSeles =  UserType::where('name', 'seles')->first()->id;
         $this->userDoctor =  UserType::where('name', 'doctor')->first()->id;
         $this->userAccount=  UserType::where('name', 'account')->first()->id;
         $this->userHospital =  UserType::where('name', 'hospital')->first()->id;
         $this->mainAccount= User::with('wallet')->where('type_id', $this->userAccount)->where('email','main@cms.com')->first();
         $this->inAccount= User::with('wallet')->where('type_id', $this->userAccount)->where('email','in@cms.com')->first();
         $this->outAccount= User::with('wallet')->where('type_id', $this->userAccount)->where('email','out@cms.com')->first();
         $this->hospital= User::with('wallet')->where('type_id', $this->userAccount)->where('email','hospital@cms.com')->first();
         $this->doctours= User::with('wallet')->where('type_id', $this->userAccount)->where('email','doctor@cms.com')->first();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

   public function index()
   {
       $users = User::where('type_id',$this->userSeles)->get();

       $accounts = User::where('type_id',$this->userHospital)->get();

       return Inertia::render('Accounting/Index', ['url'=>$this->url,'users'=>$users,'accounts'=>$this->mainAccount]);
   }
   public function getIndexAccounting(Request $request)
   {
    $term = $request->get('user_id');
    if($term){
        $data = User::with('transactions')->where('id',$term)->first();
    }
    return response()->json($data); 
    }
   
   public function payCard()
   {
    $id = $_GET['id'] ?? 0;
    $from = $_GET['from'] ?? 0;
    $to = $_GET['to'] ?? 0;
    $amount = $_GET['amount'] ?? 0;
       try {
           DB::beginTransaction();
           // Perform your database operations with Eloquent
           $walletSaler=  User::with('wallet')->find($from);
           $walletBox=  User::with('wallet')->find($to);

           //$transactions =Transactions ::where('wallet_id', $user?->wallet?->id)->where('is_pay',0);
           //$amount=$transactions->sum('amount');
           //$transactions->update(['is_pay' => 1]);
           $this->increaseWallet($percentage,' نسبة على البطاقة رقم '.$profile?->card_number,$user->id);

           //$profile_count = Profile::where('user_id', $user?->id)->where('results',1)->update(['results' => 2]);
           $this->decreaseWallet($amount*-1,' تسليم مبلغ '.$amount.' دينار عراقي ',$user->id);
           // If everything is successful, commit the transaction
           DB::commit();
           // Return a response or perform other actions
       } catch (\Exception $e) {
           // Something went wrong, rollback the transaction
           DB::rollBack();
           // Handle the exception or return an error response
       }
       return Response::json('ok', 200);
   }
    public function salesCard(Request $request)
    {
        $account_id= $request->account_id->id??0;
        $amount= $request->amount??0;
        $card= $request->card??0;
        $date= $request->date??0;
        $user_id= $request->user['id']??0;
        $box = $request->box??0;
        $hospital= $request->hospital??0;
        $doctor= $request->doctor??0;

        $desc=" مبيعات المندوب"." ".$request->user['name'].' '.'عدد البطاقات '.$card.'نسبة المبيعات للبطاقة '.$request->user['percentage'];
        $this->increaseWallet($amount, $desc,$user_id,$user_id,'App\Models\User');
        $this->increaseWallet($doctor, $desc,$this->doctours->id,$this->doctours->id,'App\Models\User');
        $this->increaseWallet($hospital, $desc,$this->hospital->id,$this->hospital->id,'App\Models\User');
        $this->increaseWallet($box, $desc,$this->mainAccount->id,$this->mainAccount->id,'App\Models\User');
        return Response::json($request, 200);
    }
    public function paySelse(Request $request,$id)
    {
        try {
            DB::beginTransaction();
            // Perform your database operations with Eloquent
            $user=  User::with('wallet')->find($id);
            $transactions =Transactions ::where('wallet_id', $user?->wallet?->id)->where('is_pay',0);
            $amount=$transactions->sum('amount');
            $transactions->update(['is_pay' => 1]);
            $profile_count = Profile::where('user_id', $user?->id)->where('results',1)->update(['results' => 2]);
            $this->decreaseWallet($amount*-1,' تسليم مبلغ '.$amount.' دينار عراقي ',$user->id);
            // If everything is successful, commit the transaction
            DB::commit();
            // Return a response or perform other actions
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            // Handle the exception or return an error response
        }
        return Response::json('ok', 200);

    }
    public function receiveCard(Request $request)
    {
        $authUser = auth()->user();
        $profile_id = $_GET['id'] ?? 0;

        $profile = Profile::find($profile_id);

        $wallet = Wallet::where('user_id', $profile->user_id)->first();

        $card = Card::find($profile->card_id);

        $user = User::find($profile->user_id);

        $old_card = $wallet->card; 

        $old_balance = $wallet->balance;

        $card_price = $card->price;

        $percentage = $user->percentage;

        $new_balance =  $old_balance + $percentage;
        $profile->update(['results'=>1,'user_accepted'=>$authUser->id]);

        try {
            DB::beginTransaction();

            $this->increaseWallet($percentage,' نسبة على البطاقة رقم '.$profile?->card_number,$user->id);
            $wallet->update(['card' => $old_card-1,'balance'=>$new_balance]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

        }

        return Response::json($new_balance, 200);

    }
    public function increaseWallet(int $amount,$desc,$user_id,$morphed_id=0,$morphed_type='',$user_added=0) 
    {
        $user=  User::with('wallet')->find($user_id);
        if($id = $user->wallet->id){
        $transactionDetils = ['type' => 'in','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount,'morphed_id'=>$morphed_id,'morphed_type'=>$morphed_type,'user_added'=>$user_added];
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

    public function decreaseWallet(int $amount,$desc,$user_id,$morphed_id=0,$morphed_type='',$user_added=0) 
    {
        $user=  User::with('wallet')->find($user_id);
        if($id = $user->wallet->id){
        $wallet = Wallet::find($id);
            $wallet->decrement('balance', $amount);
            $transactionDetils = ['type' => 'out','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount*-1,'is_pay'=>1,'morphed_id'=>$morphed_id,'morphed_type'=>$morphed_type,'user_added'=>$user_added];
            Transactions::create($transactionDetils);
         
        
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $wallet;
    }
    
    }