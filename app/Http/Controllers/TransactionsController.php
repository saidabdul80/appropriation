<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\MainWallet;
use App\Models\Wallet;
use App\Models\Scheme;
use App\Models\SubHeadBudget;
use App\Models\Transaction;
use App\Models\Transactions;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            ]);

            $user = $request->user();
            $permissions = $user->permissions->pluck('name')->toArray();


            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            $appropriations = Appropriation::where('scheme_id',$scheme_id)->get();
            if($fund_category){
                foreach($appropriations as $key => &$appropriation){
                    if (!array_intersect(explode(',', $appropriation->department), $permissions)) {
                        unset($appropriations[$key]);
                    }else{
                        $totalAmount = Transaction::where(['owner_id'=>$appropriation->id, 'owner_type'=> 'appropriation', 'fund_category'=>$fund_category])->sum('amount');
                        $appropriation->expenditure_total_amount = $totalAmount;
                    }
                }
            }else{
                foreach($appropriations as $key => &$appropriation){
                    if (!array_intersect(explode(',', $appropriation->department), $permissions)) {
                        unset($appropriations[$key]);
                    }else{
                        $totalAmount = Transaction::where(['owner_id'=>$appropriation->id, 'owner_type'=> 'appropriation'])->sum('amount');
                        $appropriation->expenditure_total_amount = $totalAmount;
                    }
                }
            }
            return response($appropriations->values(),200);
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
   /*  public function transactions(Request $request)
    {
        try{
            $request->validate([
                "owner_id"=>"required",
                "owner_type"=>"required",
            ]);

            if($request->get('owner_type')!='scheme'){
                $filters = json_decode(json_encode($request->get('filters')));
                if(!empty($filters->date)){
                    if(count($filters->date) <2 || $filters->date_type =='') throw new \Exception('Invlide Date Range Selected');
                    $dateRange = [
                        Carbon::parse($filters->date[0])->format('Y-m-d'),
                        Carbon::parse($filters->date[1])->format('Y-m-d')
                    ];
                    //return $dateRange;
                    $column = 'data->'.$filters->date_type."->value";
                    if($filters->date_type =='created_at'){
                        $column = "created_at";
                    }

                    if($request->get('fund_category')){

                        $response = Transaction::whereIn('owner_id',$request->get('owner_id'))
                                        ->whereBetween($column, $dateRange)
                                        ->where(['fund_category'=>$request->get('fund_category')])->orderBy('id','desc')->paginate(300);
                    }else{
                        $response = Transaction::whereIn('owner_id',$request->get('owner_id'))
                                            ->whereDate($column, $dateRange)
                                            ->where(["owner_type"=>$request->get('owner_type')])->orderBy('id','desc')->paginate(300);
                    }
                    return response($response);
                }
                if($request->get('fund_category')){
                    $response = Transaction::whereIn('owner_id',$request->get('owner_id'))
                                    ->where(['fund_category'=>$request->get('fund_category')])->orderBy('id','desc')->paginate(300);
                }else{
                    $response = Transaction::whereIn('owner_id',$request->get('owner_id'))
                                        ->where(["owner_type"=>$request->get('owner_type')])->orderBy('id','desc')->paginate(300);
                }
                return response($response);
            }

            if($request->get('fund_category')){

                $response_credit = Transaction::where(['owner_id'=>$request->get('owner_id'),
                                            "owner_type"=>$request->get('owner_type'),
                                            'fund_category'=>$request->get('fund_category'),
                                            'action'=>'credit'
                                            ])->orderBy('id','desc')->paginate(300);
                $response_undo = Transaction::where(['owner_id'=>$request->get('owner_id'),
                                            "owner_type"=>$request->get('owner_type'),
                                            'fund_category'=>$request->get('fund_category'),
                                            'action'=>'undo credit'
                                            ])->orderBy('id','desc')->paginate(300);
            }else{
                $response_credit = Transaction::where(['owner_id'=>$request->get('owner_id'),
                                            "owner_type"=>$request->get('owner_type'),
                                            'action'=>'credit'
                                            ])->orderBy('id','desc')->paginate(300);

                $response_undo = Transaction::where(['owner_id'=>$request->get('owner_id'),
                                            "owner_type"=>$request->get('owner_type'),
                                            'action'=>'undo credit'
                                            ])->orderBy('id','desc')->paginate(300);
            }
            return response([
                'credit'=>$response_credit,
                'undo'=>$response_undo
            ],200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    } */

    public function transactions(Request $request)
    {
        try {
            $request->validate([
                "owner_id" => "required",
                "owner_type" => "required",
            ]);

            $ownerId = $request->get('owner_id');
            $ownerType = $request->get('owner_type');
            $fundCategory = $request->get('fund_category');
            $filters = json_decode(json_encode($request->get('filters')));

            if ($ownerType != 'scheme') {
                $query = Transaction::select('*','data->Beneficiary->value as Beneficiary')->whereIn('owner_id', $ownerId)
                    ->where('owner_type', $ownerType);

                if (!empty($filters->date)) {
                    $dateRange = [
                        Carbon::parse($filters->date[0])->format('Y-m-d'),
                        Carbon::parse($filters->date[1])->format('Y-m-d')
                    ];

                    $column = $filters->date_type == 'created_at' ? 'created_at' : 'data->'.$filters->date_type.'->value';
                    $query->whereBetween($column, $dateRange);
                }


                if ($fundCategory) {
                    $query->where('fund_category', $fundCategory);
                }

                $response = $query->orderBy('id', 'desc')->get();

                if (!empty($filters->group) && $filters->group == 'vote_book') {
                    $wallet = Wallet::whereIn('owner_id', $ownerId)
                                    ->where('fund_category', $fundCategory)
                                    ->where('owner_type', 'App\\Models\\Appropriation')
                                    ->sum('total_collection');

                    $responseGrouped = $response->groupBy('Beneficiary');
                    $total = 0;
                    $results = [];

                    $responseGrouped->each(function ($group, $key) use (&$total, $wallet, &$results) {
                        $amount = $group->sum('amount');
                        $total += $amount;
                        $results[] = [
                            "name" => $key, // Assuming $key holds the Beneficiary name
                            "amount" => $amount,
                            "total" => $total,
                            "balance" => $wallet - $total, // Adjusted the calculation here
                            "Account_Number" => $group->first()->data['Account_Number']['value'],
                            "payment_date" => $group->first()->data['Payment_Date']['value'],
                        ];
                    });

                    $response = $results;
                }


                // Now, $responseSummed contains the summed amounts grouped by 'Beneficiary'


                return response(["data"=>$response ]);
            }

            $response_credit = Transaction::where([
                'owner_id' => $ownerId,
                'owner_type' => $ownerType,
                'action' => 'credit'
            ])->orderBy('id', 'desc')->paginate(300);

            $response_undo = Transaction::where([
                'owner_id' => $ownerId,
                'owner_type' => $ownerType,
                'action' => 'undo credit'
            ])->orderBy('id', 'desc')->paginate(300);

            if ($fundCategory) {
                $response_credit = $response_credit->where('fund_category', $fundCategory)->paginate(300);
                $response_undo = $response_undo->where('fund_category', $fundCategory)->paginate(300);
            }

            return response([
                'credit' => $response_credit,
                'undo' => $response_undo
            ], 200);
        } catch (ValidationException $e) {
            return response($e->getMessage(), 400);
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return response($e->getMessage(), 400);
            } else {
                return response('Failed', 400);
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
    /* public function deleteAppropriationTransaction(Request $request)
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
    } */

    public function deleteAppropriationTransaction(Request $request)
    {
        try {
            $request->validate([
                "transaction_id" => "required",
                "owner_id" => "required",
            ]);

            $transactionId = $request->get("transaction_id");
            $ownerId = $request->get('owner_id');
            $fundCategory = $request->get('fund_category');

            DB::beginTransaction();
            $scheme_id =  Appropriation::find($ownerId)?->scheme_id;
            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;

            $appropriation = Appropriation::withWallet($fundCategory,$scheme_fund_category)->where('id', $ownerId)->first();

            $mainWallet = MainWallet::where(['owner_id' => $appropriation->id, 'owner_type' => 'appropriation'])->first();

            $deletedTransaction = Transaction::find($transactionId);

            $this->undoTransactionChanges($deletedTransaction, $appropriation, $mainWallet);

            $deletedTransaction->delete();

            DB::commit();

            return response("Transaction deleted successfully", 200);
        } catch (\Exception $e) {
            DB::rollBack();

            if (env('APP_DEBUG')) {
                return response($e->getMessage(), 400);
            } else {
                return response('Failed to delete transaction', 400);
            }
        }
    }

    private function undoTransactionChanges($transaction, $appropriation, $mainWallet)
    {
        $appropriation->wallet->balance += $transaction->amount;
        $appropriation->wallet->save();

        $mainWallet->balance += $transaction->amount;
        $mainWallet->save();
    }


    public function saveAppropriationTransaction(Request $request)
    {
        try {
            $request->validate([
                "owner_id" => "required",
                "fund_category" => "required",
                "transaction" => "required",
            ]);

            $transactionData = $request->get("transaction");
            $fundCategory = $request->get('fund_category');
            $ownerId = $request->get('owner_id');

            DB::beginTransaction();
            $scheme_id =  Appropriation::find($ownerId)?->scheme_id;
            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;
            $appropriation = Appropriation::withWallet($fundCategory,$scheme_fund_category)->where('id', $ownerId)->first();

            $mainWallet = MainWallet::where(['owner_id' => $appropriation->id, 'owner_type' => 'appropriation'])->first();

            if ($transactionData['id']??'' != '') {
                $response = $this->updateTransaction($transactionData, $appropriation, $mainWallet, $request);
                DB::commit();
                return response()->json("Transaction Updated", 200);
            } else {
                $response =  $this->createTransaction($transactionData, $appropriation, $mainWallet, $request);
                DB::commit();
                return response()->json("Transaction Completed", 200);
            }


        } catch (ValidationException $e) {
            return response()->json($e->getMessage(), 400);
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return response()->json($e->getMessage(), 400);
            } else {
                return response()->json('failed', 400);
            }
        }
    }

    public function uploadAppropriationTransaction(Request $request)
    {
        try {                    
            $request->validate([
                "owner_id" => "required",
                "fund_category" => "required",
                "subhead_item_id"=>"required",
                'file' => 'required|mimes:csv,txt', 
            ]);

            // Check if the file is uploaded
            if ($request->hasFile('file')) {
                // Store the file temporarily                
                Log::info("start");
                $path = Storage::put('file.jpg', $request->file);
                Log::info("start 1");
                $fileContent = Storage::get($path);
                Log::info("start");
                // Convert the file content to an array of records
                $data = $this->parseCsv($fileContent);             

                $transactionData = $request->get("transaction");
                $fundCategory = $request->get('fund_category');
                $ownerId = $request->get('owner_id');
    
                DB::beginTransaction();
                $scheme_id =  Appropriation::find($ownerId)?->scheme_id;
                $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;
                $appropriation = Appropriation::withWallet($fundCategory,$scheme_fund_category)->where('id', $ownerId)->first();
    
                $mainWallet = MainWallet::where(['owner_id' => $appropriation->id, 'owner_type' => 'appropriation'])->first();
    
                foreach($data["data"] as $record ){
                    $transactionData =[
                        "total_amount"=> $record["Amount"],
                        "data"=>$record
                    ];
                    $response =  $this->createTransaction($transactionData, $appropriation, $mainWallet, $request);

                }
                DB::commit();
                return response()->json('success');
            }

            throw new \Exception ('File upload failed');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }

    private function parseCsv($content)
    {

        if (substr($content, 0, 3) === "\ufeff") {
            $content = substr($content, 3);
        }

        $lines = explode(PHP_EOL, $content);
        $header = null;
        $data = [];
        $sumAmount = 0;

        foreach ($lines as $line) {
            $row = str_getcsv($line);

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            if (!$header) {
                $header = $row;
                if (substr($header[0], 0, 3) === "\ufeff") {
                    $header[0] = substr($header[0], 3);
                }
            } else {
                if (count($header) == count($row)) {
                    // Combine the header and row into an associative array
                    $combined = array_combine($header, $row);

                    // Filter out entries with empty keys or values
                    $combined = array_filter($combined, function($value, $key) {
                        return $key !== "" && $value !== "";
                    }, ARRAY_FILTER_USE_BOTH);

                    // Ensure 'Amount' is properly handled
                    if (isset($combined['Amount'])) {
                        $combined['Amount'] = floatval($combined['Amount']);
                        $sumAmount += $combined['Amount'];
                    }

                    $data[] = $combined;
                }
            }
        }

        return ["data" => $data, "total" => $sumAmount];
    }



    private function updateTransaction($transactionData, $appropriation, $mainWallet, $request)
    {
        $oldTransaction = Transaction::find($transactionData['id']);
        $currentWalletBalance = $appropriation->wallet->balance + $oldTransaction->amount - $transactionData['total_amount'];
        $currentMainWalletBalance = $mainWallet->balance + $oldTransaction->amount - $transactionData['total_amount'];

        if($appropriation->budget_location == 'head'){
            if (($mainWallet->balance + $oldTransaction->amount) < $transactionData['total_amount']) {
                throw new \Exception('Insufficient balance');
            }
        }else{
            BudgetService::validateTransaction(
                $transactionData['subhead_item_id'], //subhead_budget_id
                $oldTransaction->amount,
                $transactionData['total_amount']
            );
        }

        $oldTransaction->amount = $transactionData['total_amount'];
        $oldTransaction->data = $transactionData['data'];
        $oldTransaction->save();

        $appropriation->wallet->balance = $currentWalletBalance;
        $appropriation->wallet->save();

        $mainWallet->balance += $currentMainWalletBalance;
        $mainWallet->save();

        return $oldTransaction;
    }

    private function createTransaction($transactionData, $appropriation, $mainWallet,$request)
    {

        if($appropriation->budget_location =='head'){
            if ($appropriation->wallet->balance < $transactionData['total_amount']) {
                throw new \Exception('Insufficient balance');
            }
        }else{

            BudgetService::validateTransaction(
                $request->get('subhead_item_id'), //subhead_budget_id
                0,
                $transactionData['total_amount']
            );
        }

        $appropriation->wallet->balance -= $transactionData['total_amount'];
        $appropriation->wallet->save();

        $mainWallet->balance -= $transactionData['total_amount'];
        $mainWallet->save();

        $response = Transaction::create([
            'owner_id' => $request->get('owner_id'),
            'owner_type' => 'appropriation',
            'account_type' => 'balance',
            'action' => 'debit',
            'description' => '',
            'amount' => $transactionData['total_amount'],
            'fund_category' => $request->get('fund_category'),
            'performed_by' => 1,
            'subhead_id'=> $request->get('subhead_id'),
            'subhead_item_id'=>$request->get('subhead_item_id'),
            'data' => $transactionData['data'],
        ]);

        return $response;
    }

   /*  public function saveAppropriationTransaction(Request $request)
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
    } */

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
