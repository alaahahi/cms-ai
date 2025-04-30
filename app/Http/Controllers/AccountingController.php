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
         $currentDate = Carbon::now();
         $this->currentDatef = $currentDate->format('Y-m-d');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

   public function index()
   {
       $users = User::with('wallet')->where('type_id',$this->userSeles)->get();
       $cards = Card::orderBy('id', 'DESC')->get();

       $accounts = User::with('wallet')->where('type_id',$this->userHospital)->get();

       $boxes = User::with('wallet')->where('email', 'main@cms.com')->get();
       return Inertia::render('Accounting/Index', ['boxes'=>$boxes,'users'=>$users,'accounts'=>$this->mainAccount,'cards'=>$cards]);
   }
   public function getIndexAccounting(Request $request)
   {
    $user_id = $_GET['user_id'] ?? 0;
    $from =  $_GET['from'] ?? 0;
    $to =$_GET['to'] ?? 0;
    $card_id = $_GET['card_id'] ?? 0;
    $print =$_GET['print'] ?? 0;
    $transactions_id = $_GET['transactions_id'] ?? 0;
    $user = User::with('wallet')->where('id',$user_id)->first();
    if($from && $to ){
        $transactions = Transactions ::where('wallet_id', $user->wallet->id)->where('card_id', $card_id)->orderBy('id','desc')->whereBetween('created', [$from, $to]);

    }else{
        $transactions = Transactions ::where('wallet_id', $user->wallet->id)->where('card_id', $card_id)->orderBy('id','desc');
    }
    $allTransactions = $transactions->get();
    
    // Additional logic to retrieve client data
    $data = [
        'user' => $user,
        'transactions' => $allTransactions,
      
    ];

    if($print==1){
        $config=SystemConfig::first();
        return view('receiptPaymentTotal',compact('data','config'));
     }
     if($print==2){
        $config=SystemConfig::first();

        return view('receipt',compact('clientData','config','transactions_id'));
     }
     if($print==3){
        $config=SystemConfig::first();

        return view('receiptPayment',compact('clientData','config','transactions_id'));
     }
     if($print==4){
        $config=SystemConfig::first();

        return view('receiptPaymentTotal',compact('clientData','config','transactions_id'));
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
   
   public function salesDebt(Request $request)
   {
    $user_id= $request->user['id']??0;
    $note= $request->note??'';
    $amount= $request->amount??0;
    $desc=" سحب دفعة من حساب "." ".$request->user['name'].' '.$note;
    $authUser = auth()->user();
    $date= $request->date??0;
    $this->debt($amount,$desc,$user_id,$user_id,'App\Models\User',$authUser,$date);

    return Response::json($request, 200);

    }
    public function delTransactions(Request $request)
    {
        
        $transaction_id = $request->id ?? 0;
        $originalTransaction = Transactions::find($transaction_id);
        $wallet_id=$originalTransaction->wallet_id;
        $wallet=Wallet::find($wallet_id);
        $walletHospital=Wallet::where('user_id',$this->hospital->id)->first();

        $wallet->decrement('balance', $originalTransaction->amount);
        if (!$originalTransaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        if($originalTransaction){
          $all=  Transactions::where('parent_id',$transaction_id)->first();
          if($all){
            $wallet_id=$all->wallet_id;
            $wallet=Wallet::find($wallet_id);
            $wallet->decrement('balance', $all->amount);
            $wallet->decrement('card', $all->card);
            $walletHospital->decrement('card', $all->card);

            $all->delete();
          }
        }
        // // Create a new transaction for the refund
        // $refundTransaction = new Transactions();
        // $refundTransaction->wallet_id = $originalTransaction->wallet_id;
        // $refundTransaction->morphed_id = $originalTransaction->morphed_id;
        // $refundTransaction->morphed_type = $originalTransaction->morphed_type;
        // $refundTransaction->created =$this->currentDatef;
        // $refundTransaction->type = 'refund'; // Assuming you have a 'refund' transaction type
        // $refundTransaction->amount = -$originalTransaction->amount; // Make the refund negative
        // $refundTransaction->description = 'مرتجع حذف حركة';
        // $refundTransaction->save();
    
        // Delete the original transaction
        $originalTransaction->delete();
    
        return response()->json(['message' => 'Transaction deleted and refund created'], 200);
    }
    public function salesCard(Request $request)
    {
        $account_id= $this->mainAccount->id??0;
        $amount= $request->amount??0;
        $card_id = $request->card_id??0;
        $card= $request->card??0;
        $date= $request->date??0;
        $user_id= $request->user['id']??0;
        $box = $request->box??0;
        $card = $request->card??0;
        $hospital= $request->hospital??0;
        $doctor= $request->doctor??0;
        $wallet = Wallet::where('user_id', $user_id)->first();
        $wallet->increment('card',$card);
        $wallet->increment('card_total',$card);

        $walletHospital = Wallet::where('user_id', $this->hospital->id)->first();
        $walletDoctours = Wallet::where('user_id', $this->doctours->id)->first();

        $walletHospital->increment('card',$card);
        $walletHospital->increment('card_total',$card);

        $walletDoctours->increment('card',$card);
        $walletDoctours->increment('card_total',$card);

        
        $desc=" مبيعات المندوب"." ".$request->user['name'].' '.'عدد البطاقات '.$card.'نسبة المبيعات للبطاقة '.$request->user['percentage'];
        $transaction = $this->increaseWallet($box, $desc,$this->mainAccount->id,$this->mainAccount->id,'App\Models\User',$user_id, $date,0,0,$card_id);
        $this->increaseWallet($amount, $desc,$user_id,$user_id,'App\Models\User',$user_id, $date,$transaction->id,$card,$card_id);
        return Response::json($request, 200);
    }
    public function paySelse(Request $request,$id)
    {
        $authUser = auth()->user();
        $card_id = $request->card_id??1;

        try {
            DB::beginTransaction();
            // Perform your database operations with Eloquent
            $user=  User::with('wallet')->find($id);
            $transactions =Transactions ::where('wallet_id', $user?->wallet?->id)->where('is_pay',0);
            $amount=$transactions->sum('amount');
            $wallet = Wallet::where('user_id', $id)->first();
            if($amount>0){
            $wallet->update(['card' => 0]);
            $transactions->update(['is_pay' => 1]);
            $profile_count = Profile::where('user_id', $user?->id)->where('results',1)->update(['results' => 2]);
            $this->decreaseWallet($amount,' تسليم مبلغ '.$amount.' دينار عراقي ',$user->id,$user->id,'App\Models\User',$authUser->id,$this->currentDatef,0,0,$card_id);
            $Transactions =$this->decreaseWallet($amount,' تسليم مبلغ '.$amount.' دينار عراقي ',$this->mainAccount->id,$this->mainAccount->id,'App\Models\User',$authUser->id,$this->currentDatef,0,0,$card_id);

            // If everything is successful, commit the transaction
            DB::commit();
            }
            else
            return Response::json('no balance', 200);

            // Return a response or perform other actions
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return Response::json($e, 200);

            // Handle the exception or return an error response
        }
        return Response::json($Transactions, 200);

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
    public function increaseWallet(int $amount,$desc,$user_id,$morphed_id=0,$morphed_type='',$user_added=0,$created,$parent_id=0,$card=0,$card_id=1) 
    {
        $user=  User::with('wallet')->find($user_id);
        if($id = $user->wallet->id){
        $transactionDetils = ['type' => 'in','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount,'morphed_id'=>$morphed_id,'morphed_type'=>$morphed_type,'user_added'=>$user_added,'created'=>$created,'parent_id'=>$parent_id,'card'=>$card,'card_id'=>$card_id];
        $Transactions =Transactions::create($transactionDetils);
        $wallet = Wallet::find($id);
        $wallet->increment('balance', $amount);
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $Transactions;
    }

    public function decreaseWallet(int $amount,$desc,$user_id,$morphed_id=0,$morphed_type='',$user_added=0,$created,$parent_id=0,$card=0,$card_id=1) 
    {
        $user=  User::with('wallet')->find($user_id);
        if($id = $user->wallet->id){
        $wallet = Wallet::find($id);
            $wallet->decrement('balance', $amount);
            $transactionDetils = ['type' => 'out','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount*-1,'is_pay'=>1,'morphed_id'=>$morphed_id,'morphed_type'=>$morphed_type,'user_added'=>$user_added,'created'=>$created,'parent_id'=>$parent_id,'card'=>$card,'card_id'=>$card_id];
            $Transactions =Transactions::create($transactionDetils);
         
        
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $Transactions;
    }
    public function debt(int $amount,$desc,$user_id,$morphed_id=0,$morphed_type='',$user_added=0,$created,$parent_id=0) 
    {
        $user=  User::with('wallet')->find($user_id);
        if($id = $user->wallet->id){
        $wallet = Wallet::find($id);
        if($wallet->balance <= 0){
            return 'No balance';
        }
            $wallet->decrement('balance', $amount);
            $transactionDetils = ['type' => 'debt','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount*-1,'is_pay'=>0,'morphed_id'=>$morphed_id,'morphed_type'=>$morphed_type,'user_added'=>$user_added,'created'=>$created,'parent_id'=>$parent_id];
            $Transactions =Transactions::create($transactionDetils);
         
        
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $Transactions;
    }
    }