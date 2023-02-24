<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Scheme;
use App\Models\TblRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFund(Request $request)
    {
        
        try{

            $request->validate([            
                "scheme_id"=>"required",
                "fund_category"=>"required",
            ]);
            $date = Carbon::now();            
            $lastMonth = $date->subMonth()->format('Y-m');
            $fund_date = $request->get('fund_category');
            $fund  = Fund::where(['scheme_id'=>$request->get('scheme_id'),'fund_category'=>$fund_date,'status'=>'used'])->first();
            if(!empty($fund)){
                throw new \Exception('This Fund has been used already', 400);
            }                        
            $total = TblRequest::where(DB::raw('LEFT(payment_date,7)'), '=', $fund_date )->where('payment_status','paid')->sum('amount');                         
            return response($total,200);

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function show(Fund $fund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function edit(Fund $fund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fund $fund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fund $fund)
    {
        //
    }
}
