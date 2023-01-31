<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\Appropriation;
use App\Models\AppropriationHistory;
use App\Models\Department;
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
    public function index()
    {
        //
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
            $expectedFund = 200000000;

            $scheme = Scheme::find($request->get('scheme_id'));
          
            if(count($scheme->appropriations) <1){
                throw new Exception('No appropriation available',400);                
            }

            $amount=  0;
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
            return response($record,200);
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
