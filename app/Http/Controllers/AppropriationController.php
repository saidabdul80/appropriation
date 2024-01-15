<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\Appropriation;
use App\Models\AppropriationHistory;
use App\Models\Department;
use App\Models\TblRequest;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppropriationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getPreparedData(Request $request)
    {
        try{
            $request->validate([                
                "scheme_id"=>"required",       
//                "fund_category"=>""         
            ]);
            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            //return $fund_category;
            $appropriation_histories = AppropriationHistory::where(['owner_id'=>$scheme_id,"owner_type"=>'scheme','fund_category'=>$fund_category])->orderBy('id','desc')->paginate(20);
            $appropriations = Appropriation::with(['wallet'=>function($query) use($fund_category){
                $query->where(['fund_category'=>$fund_category, 'owner_type'=>'App\\Models\\Appropriation']);
            }])->where('scheme_id', $scheme_id)->get();

            return response(['appropriations'=>$appropriations, 'appropriations_histories'=>$appropriation_histories],200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e,500);                
            }else{
                return response('failed',400);
            }
        }   
    }
    
    public function getAmountSummaryData(Request $request)
    {
        try{
            $request->validate([                
                "scheme_id"=>"required",       
//                "fund_category"=>""         
            ]);
            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            //return $fund_category;
            $appropriation_histories = AppropriationHistory::where([
                'owner_id' => $scheme_id,
                'owner_type' => 'scheme',
                'fund_category' => $fund_category,
            ])->get();
            
            $result1 = [];
            
            foreach ($appropriation_histories as $history) {
                foreach ($history->appropriation as $appr) {
                    $result1[$appr['name']] = ($result1[$appr['name']] ?? 0) + $appr['amount'];
                }
            }
            // Now $result is an associative array with 'name' as key and 'total_amount' as value
            
        
            $appropriations = Appropriation::with(['wallet' => function ($query) use ($fund_category) {
                $query->where(['fund_category' => $fund_category, 'owner_type' => 'App\\Models\\Appropriation']);
            }])->where('scheme_id', $scheme_id)->get();
            
            $result2 = [];
            
            foreach ($appropriations as $appropriation) {
                $totalAmount = $appropriation->wallet->balance;
                $result2[$appropriation->name] = $totalAmount;
            }
            
            // Now $result is an associative array with 'name' as key and 'total_amount' as value            

            return response(['balance'=>$result2, 'income'=>$result1],200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);   
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e,500);                
            }else{
                return response('failed',400);
            }
        }   
    }

    public function fundMonthYear(Request $request)
    {
        try{
            $request->validate([                
                "scheme_id"=>"required",                
            ]);
            $scheme_id = $request->get('scheme_id');          
            $ids = Appropriation::where('scheme_id', $scheme_id)->pluck('id');
            $response = Wallet::whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category')->unique();
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

    public function getAppropriations(Request $request)
    {
        try{
            $request->validate([                
                "owner_id"=>"required",
                "owner_type"=>"required"
            ]);

            $response = AppropriationHistory::where(['owner_id'=>$request->get('owner_id'),"owner_type"=>$request->get('owner_type')])->orderBy('id','desc')->paginate(20);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppropriationsProjection(Request $request){
        try{

            $request->validate([                
                "scheme_id"=>"required"
            ]);
            $scheme = Scheme::find($request->get('scheme_id'));
            if(empty($scheme)){
                throw new \Exception('Programme Not Found',404);
            }
            if($scheme->fund_type != 'api'){ //change this to entry
                throw new \Exception('There is no Projection for this programme', 400);
            }

            $date = Carbon::now();            
            $thisMonth = $date->year.'-'.$date->month;
            $thisMonth = '2020-07';
            $response = TblRequest::where(DB::raw('LEFT(payment_date,7)'), '=', $thisMonth )->where('payment_status','paid')->sum('amount');
            return response($response,400);
          
            if(count($scheme->appropriations) <1){
                throw new Exception('No appropriation available',400);                
            }


        /*     $amount=  0;
            $total_amount=  0;
            $record =[
                "amount"=>$expectedFund,
                "appropriations" =>[]
            ];
            
            foreach($scheme->appropriations as $appropriation){
                $amount = ($expectedFund * $appropriation->percentage_dividend) /100;                
                $total_amount += round($amount,2);
                $record["appropriations"][]=[
                    "id"=>$appropriation->id,
                    "name"=>$appropriation->name,
                    "department"=>$appropriation->department,
                    "amount"=>$amount,
                    "percentage_dividend" => $appropriation->percentage_dividend
                ];                
            }
            return response($record,200); */
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appropriation  $appropriation
     * @return \Illuminate\Http\Response
     */
    public function show(Appropriation $appropriation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appropriation  $appropriation
     * @return \Illuminate\Http\Response
     */
    public function edit(Appropriation $appropriation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appropriation  $appropriation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appropriation $appropriation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appropriation  $appropriation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appropriation $appropriation)
    {
        //
    }
}
