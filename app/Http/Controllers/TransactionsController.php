<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
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
    public function index()
    {
        //
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
                "owner_type"=>"required",
                "transaction"=>"required"
            ]);

            $transaction = $request->get("transaction");        
            $appropriation = Appropriation::find($request->get('owner_id'));            
            DB::beginTransaction();
            if($transaction['id'] !=='' && !is_null($transaction['id']) ){
                
                    $oldTransaction = Transaction::find($transaction['id']);
                    //because we are updating, we are returning balance to it former state then we will rededuct later
                    $appropriation->wallet->balance = $appropriation->wallet->balance +$oldTransaction->amount;
                    $appropriation->wallet->save();
                    if(($appropriation->wallet->balance +$oldTransaction->amount) < $transaction['total_amount']){
                        throw new \Exception('Insufficient balance');                
                    }                                                                                
                    $oldTransaction->amount = $transaction['total_amount'];                    
                    $oldTransaction->data = $transaction['data'];
                    $oldTransaction->save();
                    //we are rededucting
                    $appropriation->wallet->balance -= $transaction['total_amount'];
                    $appropriation->wallet->save();
                    $response = $oldTransaction;
                }else{
                    if($appropriation->wallet->balance < $transaction['total_amount']){
                        throw new \Exception('Insufficient balance');                
                    }
                    $appropriation->wallet->balance -= $transaction['total_amount'];
                    $appropriation->wallet->save();
                    $response = Transaction::create([
                        'owner_id'=>$request->get('owner_id'),
                        "owner_type"=>$request->get('owner_type'),
                        "account_type"=>'balance',
                        "action"=>'debit',
                        "description"=>'',
                        "amount"=>$transaction['total_amount'],
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
