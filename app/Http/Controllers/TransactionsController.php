<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\MainWallet;
use App\Models\Scheme;
use App\Models\Transaction;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expenditureDetails(Request $request)
    {
        try{
            $request->validate([                
                "scheme_id"=>"required",
                "fund_category"=>"required"
            ]);
            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            $appropriations = Appropriation::where('scheme_id',$scheme_id)->get();
            foreach($appropriations as $key => &$appropriation){
                $totalAmount = Transaction::where(['owner_id'=>$appropriation->id, 'owner_type'=> 'appropriation', 'fund_category'=>$fund_category])->sum('amount');
                $appropriation->expenditure_total_amount = $totalAmount;            
            }            
            return response($appropriations,200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions(Request $request)
    {
        try{
            $request->validate([                
                "owner_id"=>"required",
                "owner_type"=>"required"
            ]);
            $response = Transaction::where(['owner_id'=>$request->get('owner_id'),"owner_type"=>$request->get('owner_type')])->orderBy('id','desc')->paginate(20);
            return response($response,200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }   
    }

    public function appropriationTransactions(Request $request)
    {
        try{
            $request->validate([                
                "owner_id"=>"required",//
                "owner_type"=>"required",                
                "fund_category"=>"required",                
            ]);
            $owner_id = $request->get('owner_id');
            $owner_type = $request->get('owner_type');            
            $appropriations = Appropriation::where('scheme_id',$owner_id)->get();
            foreach($appropriations as $key => &$appropriation){
                $res = Transaction::where(['owner_id'=>$appropriation->id,"owner_type"=>$owner_type])->orderBy('id','desc')->get();
                $appropriation->transactions = $res;
            }
            return response($appropriations,200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAppropriationTransaction(Request $request)
    {
        $request->validate([                
            "id"=>"required"
        ]);
        DB::beginTransaction();
            $transaction = Transaction::find($request->get('id'));
            $appropriation = Appropriation::find($transaction->owner_id);
            //because we are deleting, we are returning balance to it former state
            $appropriation->wallet->balance = $appropriation->wallet->balance +$transaction->amount;
            $appropriation->wallet->save();
            $transaction->delete();
        DB::commit();
    }
    public function saveAppropriationTransaction(Request $request)
    {
        try{
            $request->validate([                
                "owner_id"=>"required",       
                "fund_category"=>"required",
                "transaction"=>"required"
            ]);

            $transaction = $request->get("transaction");        
            $fund_category = $request->get('fund_category');
            $owner_id = $request->get('owner_id');
            $appropriation = Appropriation::with(['wallet'=>function($query) use($fund_category,){
                $query->where(['fund_category'=>$fund_category, 'owner_type'=>'App\\Models\\Appropriation']);
            }])->where('id', $owner_id)->first();
            DB::beginTransaction();
                $mainWallet = MainWallet::where(['owner_id'=>$appropriation->id,'owner_type'=>'appropriation'])->first();
                if($transaction['id'] !=='' && !is_null($transaction['id']) ){

                        $oldTransaction = Transaction::find($transaction['id']);
                        $currentWalletBalance = $appropriation->wallet->balance + $oldTransaction->amount - $transaction['total_amount'];
                        $currentMainWalletBalance = $mainWallet->balance + $oldTransaction->amount - $transaction['total_amount'];
                    
                        if(($mainWallet->balance + $oldTransaction->amount) < $transaction['total_amount']){
                            throw new \Exception('Insufficient balance');                
                        }                                   

                        $oldTransaction->amount = $transaction['total_amount'];                    
                        $oldTransaction->data = $transaction['data'];
                        $oldTransaction->save();
                        
                        $appropriation->wallet->balance = $currentWalletBalance;
                        $appropriation->wallet->save();
                        
                        $mainWallet->balance += $currentMainWalletBalance;                    
                        $mainWallet->save();
                        
                        $response = $oldTransaction;
                }else{
                    if($appropriation->wallet->balance < $transaction['total_amount']){
                        throw new \Exception('Insufficient balance');                
                    }
                    
                    $appropriation->wallet->balance -= $transaction['total_amount'];
                    $appropriation->wallet->save();

                    $mainWallet->balance -= $transaction['total_amount'];                    
                    $mainWallet->save();

                    $response = Transaction::create([
                        'owner_id'=>$request->get('owner_id'),
                        "owner_type"=>'appropriation',
                        "account_type"=>'balance',
                        "action"=>'debit',
                        "description"=>'',
                        "amount"=>$transaction['total_amount'],
                        "fund_category"=> $fund_category,
                        "performed_by"=>1,
                        "data"=>$transaction['data']
                    ]);
                }
            DB::commit();
        
            return response($response,200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return $e;
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
 
}
