<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\AppropriationHistory;
use App\Models\Appropriation;
use App\Models\Fund;
use App\Models\FundCategory;
use App\Models\MainWallet;
use App\Models\SubHeadBudget;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('scheme');
    }

    public function schemeScreen()
    {
        return view('main_scheme');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAppropriation(Request $request)
    {
        try{

            $request->validate([
                "appropriation_type_id"=>"required",
                "scheme_id"=>"required",
                "department_id"=>"required",
                "percentage_dividend"=>"required"
            ]);

            $data = $request->only(['scheme_id', 'appropriation_type_id', 'department_id', 'percentage_dividend']);
            if($request->has('id')){
                $appropriation = Appropriation::where('id', $request->id)->update([
                        'scheme_id' => $data['scheme_id'],
                        'appropriation_type_id' => $data['appropriation_type_id'],
                        'department_id' => $data['department_id'],
                        'percentage_dividend' => $data['percentage_dividend']
                    ]);
                    return response()->json(['appropriation' => $appropriation, 'msg' => 'Updated Successfully'], 200);
            }

            $appropriation = Appropriation::create([
                'scheme_id' => $data['scheme_id'],
                'appropriation_type_id' => $data['appropriation_type_id'],
                'department_id' => $data['department_id'],
                'percentage_dividend' => $data['percentage_dividend']
            ]);

            return response()->json(['appropriation' => $appropriation, 'msg' => 'Created Successfully'], 200);

        } catch(ValidationException $e){
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

    private function createSchemeWallet($scheme_id){
        $wallet_number = date('ymdhis');
        $scheme = Scheme::where('id', $scheme_id)->first();
        $token = walletToken($scheme_id,'scheme');
        if(empty($scheme->wallet_number)){
            $wallet =  Wallet::create([
                "owner_id"=>$scheme_id,
                "wallet_number"=>$wallet_number,
                "owner_type"=>'App\\Models\\Scheme',
                "balance"=>0.00,
                "total_collection"=>0.00,
                "token"=>$token
            ]);

            return $wallet;
        }else{
            if(!Wallet::where('wallet_number', $scheme->wallet_number)->exists()){
                $wallet =  Wallet::create([
                    "owner_id"=>$scheme_id,
                    "wallet_number"=>$wallet_number,
                    "owner_type"=>'App\\Models\\Scheme',
                    "balance"=>0.00,
                    "total_collection"=>0.00,
                    "token"=>$token
                ]);
                return $wallet;
            }
        }
        return false;
    }

    public function addScheme(Request $request)
    {
        try{

            $request->validate([
                "name"=>"required"
            ]);


            $data = $request->all();
            $id = $data['id'];
            if( $id == ''){

                DB::beginTransaction();
                    $res = Scheme::create(["name"=>$data['name']]);
                    $wallet = $this->createSchemeWallet($res->id);
                    if($wallet){
                        $res->wallet_number =  $wallet->wallet_number;
                        $res->save();
                    }else{
                        throw new \Exception('Failed to Create Scheme Wallet: Try again');
                    }

                DB::commit();
                return response(["scheme"=>Scheme::find($res->id), "msg" =>'Created Successfuly'],200);
            }else{

                unset($data['created_at']);
                unset($data['updated_at']);
                unset($data['appropriation']);

                Scheme::where('id',$id)->update(["name"=>$data['name']]);
                return response('Updated Successfuly',200);
            }
        } catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }

    public function fundProgramme(Request $request){
        try{
            $request->validate([
                "amount"=>"required",
                "scheme_id"=>"required",
                "source_id"=>"required",
                "fund_month_year"=>"required"
            ]);

            $amount = $request->get('amount');
            $scheme_id = $request->get('scheme_id');
            $source_id = $request->get('source_id');
            $description = $request->get('description');
            $fund_date = $request->get('fund_month_year');
            $fund_month_year = Carbon::parse($request->get('fund_month_year'))->format('Y-m');


            if(Fund::where(['scheme_id'=>$scheme_id,'fund_category'=>$fund_month_year,'status'=>'used'])->exists()){
                throw new \Exception('This fund has already been used');
            }

            DB::beginTransaction();




                $scheme = Scheme::find($scheme_id);
                $wallet = $this->createSchemeWallet($scheme_id);
                if($wallet){
                    $scheme->wallet_number =  $wallet->wallet_number;
                    $scheme->save();
                    $scheme->fresh();
                }


                $wallet = Wallet::find($scheme->wallet->id);
                $fundCategoryValue = substr($fund_month_year,0,4);// $this->monthYearFormatter($fund_month_year,$scheme);

                if($scheme->fund_category == 'year'){
                    if($wallet->balance > 0 && $wallet->fund_category != $fundCategoryValue){
                        throw new \Exception("Invalid Funding: you cannot top up fund with different funding year ");
                    }
                }

                if($scheme->fund_category == 'month'){
                    if($wallet->balance > 0 && $wallet->fund_month_year != $fund_month_year){
                        throw new \Exception("Invalid Funding: you cannot top up fund with different funding date ");
                    }
                }
                //$wallet->safe_balance += $wallet->balance;

                $wallet->balance += $amount;
                $wallet->fund_category = $fundCategoryValue; //shows the last fund category
                $wallet->fund_month_year = $fund_month_year;
                $wallet->total_collection += $amount;
                $wallet->source_id = $source_id;
                $wallet->description = $description;
                $wallet->save();

                $exists = FundCategory::where([
                    'scheme_id' => $scheme_id,
                    'fund_category' => $fundCategoryValue,
                ])->exists();

                // Create the record only if it does not exist
                if (!$exists) {
                    FundCategory::create([
                        'scheme_id' => $scheme_id,
                        'fund_category' => $fundCategoryValue,
                        'fund_month_year' => $fund_month_year
                    ]);
                }

                if($scheme->fund_type=='api'){
                    Fund::where(['scheme_id'=>$scheme->id,'fund_category'=>$fund_month_year])->update([
                        'amount'=> $amount,
                        'status'=>'used'
                    ]);
                }

                $trasaction = Transaction::create([
                    "owner_id"=> $scheme_id,
                    "owner_type"=>'scheme',
                    "account_type"=>'balance',
                    "action"=>'credit',
                    "description"=>$description,
                    "source_id"=>$source_id,
                    "amount"=>$amount,
                    "fund_category"=>$fund_month_year,
                    "performed_by"=>1,
                    "state"=>'unused',
                    "fund_date"=>$fund_date,
                    //"performed_by"=>auth()->user()->id,
                ]);
            DB::commit();
            $scheme = Scheme::find($scheme_id);
            return response(["scheme"=>$scheme, "msg"=>'funded'],200);
        } catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }

    public function reverseFundProgramme(Request $request)
    {
        try {
            $request->validate([
                "transaction_id" => "required",
            ]);

            $transaction_id = $request->get('transaction_id');

            DB::beginTransaction();

            $transaction = Transaction::find($transaction_id);

            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            // Reverse the transaction
            $transaction->action = 'undo credit';

            $scheme = Scheme::find($transaction->owner_id);
            $wallet = Wallet::find($scheme->wallet->id);

            if($wallet->balance < $transaction->amount){
                throw new \Exception('Reverse failed');
            }

            $fundCategoryValue = intval(substr($transaction->fund_category, 0, 4))-1;

            // Reverse the wallet operations
            $wallet->fund_category = $fundCategoryValue;
            $wallet->balance -= $transaction->amount;
            $wallet->total_collection -= $transaction->amount;
            $wallet->save();
            $transaction->save();

            // Reverse the Fund entry if scheme type is 'api'
            if ($scheme->fund_type == 'api') {
                Fund::where([
                    'scheme_id' => $scheme->id,
                    'fund_category' => $transaction->fund_category,
                ])->update([
                    'amount' => 0,
                    'status' => 'unused',
                ]);
            }

            DB::commit();

            $scheme = Scheme::find($transaction->owner_id);

            return response(["scheme" => $scheme, "msg" => 'Fund reversed successfully'], 200);
        } catch (ValidationException $e) {
            return response($e->getMessage(), 400);
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return response($e->getMessage(), 400);
            } else {
                return response('Failed to reverse fund', 400);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppropriationHistory  $appropriationHistory
     * @return \Illuminate\Http\Response
     */
    public function appropriate(Request $request)
    {
        try{
            $request->validate([
                "scheme_id"=>"required",
                "appropriation_ids" =>"required"
            ]);
            $scheme = Scheme::find($request->get('scheme_id'));
            $appropriation_ids = $request->get('appropriation_ids');
            if($scheme->wallet->balance <1){
                throw new Exception('Insufficent balance',400);
            }

            if(count($scheme->appropriations) <1){
                throw new Exception('No appropriation available',400);
            }

            $amount=  0;
            $total_amount=  0;
            $appropriations = [];
            $fundCategoryValue = $scheme->wallet->fund_category;
            $fund_month_year = $scheme->wallet->fund_month_year;
            DB::beginTransaction();
            if($scheme->fund_category == 'year'){

                $lastSubheadBudgetEntries = SubHeadBudget::whereIn('appropriation_id', $appropriation_ids)
                ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('sub_head_budgets')
                    ->groupBy('appropriation_id');
                })
                ->get();

                $lastSubheadBudgetEntries->map(function ($entry) use ($fundCategoryValue ) {
                    if($entry->fund_category !== $fundCategoryValue ){
                        $newEntry = $entry->replicate(['id'])->fill([
                            "amount"=>0,
                            "fund_category"=> $fundCategoryValue
                        ]);
                        $newEntry->save();
                    }
                });
            }

            /* SubHeadBudget::whereIn('appropriation_id',$appropriation_ids)->where('fund_category', $request->get('fund_category')); */

            foreach($scheme->appropriations as $appropriation){
                if(in_array($appropriation->id, $appropriation_ids)){
                    $amount = ($scheme->wallet->balance* $appropriation->percentage_dividend) /100;
                    $total_amount += round($amount,2);
                    $appropriations[]=[
                        "id"=>$appropriation->id,
                        "name"=>$appropriation->name,
                        "department"=>$appropriation->department,
                        "amount"=>$amount,
                        "percentage_dividend" => $appropriation->percentage_dividend
                    ];
                    $token = walletToken($appropriation->id,$fundCategoryValue);

                    $appropriationWithWallet = Appropriation::with(['wallet'=>function($query) use($token, $scheme){
                        if($scheme->fund_category == 'year'){
                            $query->where(['token'=>$token]);
                        }
                    }])->where('id', $appropriation->id)->first();

                    if(!is_null($appropriationWithWallet->main_wallet)){
                        $mainWallet = MainWallet::where(['owner_id'=>$appropriation->id,'owner_type'=>'appropriation'])->first();
                        $mainWallet->balance += $amount;
                        $mainWallet->total_collection += $amount;
                        $mainWallet->save();
                    }else{
                        MainWallet::create([
                            "owner_id"=>$appropriation->id,
                            "owner_type"=>'appropriation',
                            "balance"=>$amount,
                            "total_collection"=>$amount
                        ]);
                    }

                    if(!is_null($appropriationWithWallet->wallet)){
                        $appropriationWithWallet->wallet->balance += $amount;
                        $appropriationWithWallet->wallet->total_collection += $amount;
                        $appropriationWithWallet->wallet->save();
                    }else{
                    Wallet::create([
                            "owner_id"=>$appropriation->id,
                            "owner_type"=>'App\\Models\\Appropriation',
                            "balance"=>$amount,
                            "fund_category"=>$fundCategoryValue,
                            "fund_month_year"=>$fund_month_year,
                            "total_collection"=>$amount,
                            "token"=>$token
                        ]);
                    }
                }
            }

            $left_balance = $scheme->wallet->balance-$total_amount;

            $appropriationHistory =  AppropriationHistory::create([
                "owner_id"=> $scheme->id,
                "owner_type"=> 'scheme',
                "description"=> $scheme->wallet->description,
                "source_id"=> $scheme->wallet->source_id,
                "amount"=> $scheme->wallet->balance,
                "appropriation" => $appropriations,//object
                "fund_category" => $fundCategoryValue,
                "fund_month_year"=>$fund_month_year,
            ]);

            Wallet::where('id',$scheme->wallet->id)->update([
                "balance"=> 0.00,
                "safe_balance"=> $left_balance+ $scheme->wallet->safe_balance,
            ]);

            $trasaction = Transaction::create([
                "owner_id"=> $scheme->id,
                "owner_type"=> 'scheme',
                "action"=>'debit',
                "account_type"=>'balance',
                "description"=>$scheme->wallet->description,
                "source_id"=>$scheme->wallet->source_id,
                "appropriation_history_id"=> $appropriationHistory->id,
                "amount"=>$total_amount,
                "performed_by"=>1,
                //"performed_by"=>auth()->user()->id,
            ]);
            Transaction::where(["owner_id"=> $scheme->id,"owner_type"=> 'scheme'])->update(['state'=>'used']);
            DB::commit();
            $scheme = Scheme::find($scheme->id);
            return response(["appropriationHistory"=>$appropriationHistory,"scheme"=>$scheme],200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e,400);
            }else{
                return response('failed',400);
            }
        }
    }

    public function undoAppropriate($appropriationHistoryId) {
        try {
            DB::beginTransaction();

            // Step 1: Fetch the appropriation history record
            $appropriationHistory = AppropriationHistory::findOrFail($appropriationHistoryId);

            // Step 2: Revert wallet balances
            $scheme = Scheme::find($appropriationHistory->owner_id);

            // Step 3: Delete appropriation entries created during the appropriation process
            foreach ($appropriationHistory->appropriation as $appropriation) {
                $appropriationId = $appropriation['id'];
                 // Delete related Wallet entries
                 $wallet = Wallet::where('owner_id', $appropriationId)->where('owner_type', 'App\\Models\\Appropriation')->first();
                 if( $wallet->balance < $appropriation['amount']){
                    throw new \Exception('Cannot Rollback this Appropriation');
                 }
            }

            foreach ($appropriationHistory->appropriation as $appropriation) {
                    $wallet->balance -= $appropriation->amount;
                    $wallet->total_collection -= $appropriation->amount;
                    $wallet->save();

                    // Delete related MainWallet entries
                    $mainWallet = MainWallet::where('owner_id', $appropriationId)->where('owner_type', 'appropriation')->first();
                    $mainWallet->balance -= $appropriation->amount;
                    $mainWallet->total_collection -= $appropriation->amount;
                    $mainWallet->save();
            }

            $schemeWallet = $scheme->wallet;
            $schemeWallet->balance -= $appropriationHistory->amount;
            $schemeWallet->safe_balance -= ($schemeWallet->balance - $appropriationHistory->amount); // Adjust safe balance
            $schemeWallet->save();
            $appropriationHistory->delete();

            // Commit the transaction
            DB::commit();

            return response("Appropriation undone successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 400);
        }
    }

    private function monthYearFormatter($date, $scheme){
        $fundCategoryValue = '';
        if($scheme->fund_category == 'month'){
            $fundCategoryValue = Carbon::parse($date)->format('m-Y');
        }else{
            $fundCategoryValue = Carbon::parse($date)->format('Y');
        }
        return $fundCategoryValue;
    }

    public function fetchFundCategories(Request $request){
        try{

            return response(FundCategory::where([
                'scheme_id' => $request->get('scheme_id')
            ])->get(),200);
        } catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }


}
