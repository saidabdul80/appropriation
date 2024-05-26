<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\Appropriation;
use App\Models\AppropriationHistory;
use App\Models\AppropriationType;
use App\Models\Department;
use App\Models\TblRequest;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
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

            $user = $request->user();
            $permissions = $user->permissions->pluck('name')->toArray();

            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            //return $fund_category;
            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;
            $appropriation_histories = AppropriationHistory::where(['owner_id'=>$scheme_id,"owner_type"=>'scheme','fund_category'=>$fund_category])->orderBy('id','desc')->get();
            $appropriations = Appropriation::withWallet($fund_category, $scheme_fund_category)->where('scheme_id', $scheme_id)->get();

                    // Initialize response arrays
            $filtered_appropriations = [];
            $filtered_appropriation_histories = [];

            // Process each appropriation
            foreach ($appropriations as $appropriation) {
                $departmentList = explode(',', $appropriation->department);

                // Check if user has permission for this appropriation's department
                if (array_intersect($departmentList, $permissions)) {
                    $filtered_appropriations[] = $appropriation;
                }
            }

            // Process each appropriation history
            foreach ($appropriation_histories as $history) {

                $appropriations = $history->appropriation; // Assuming AppropriationHistory has a relation 'appropriation'
                if ($appropriations) {
                    foreach ($history->appropriation as $key =>$appropriation) {
                        $departmentList = explode(',', $appropriation['department']);
                        if (array_intersect($departmentList, $permissions)) {
                          //  $filtered_appropriation_histories[] = $history;
                        }else{
                            unset($history->appropriation[$key]);
                        }
                    }
                    $departmentList = explode(',', $appropriation['department']);
                }
            }

            $perPage = 100;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $paginatedHistories = new LengthAwarePaginator(
                array_slice($filtered_appropriation_histories, ($currentPage - 1) * $perPage, $perPage),
                count($filtered_appropriation_histories),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            return response(['appropriations'=>$filtered_appropriations, 'appropriations_histories'=>$paginatedHistories],200);
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

   public function fundCategoryAppropriations(Request $request){
        try{
            $request->validate([
                "scheme_id"=>"required",
            ]);
            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;
            //return $fund_category;
            if(!empty($fund_category)){

                $appropriations = Appropriation::with(['subheads'=>function($query) use($fund_category){
                    $query->where('fund_category',$fund_category);
                }])->withWallet(null, $scheme_fund_category)->where('scheme_id', $scheme_id)->get();
            }else{
                $appropriations = Appropriation::with(['subheads'])->withWallet($fund_category,$scheme_fund_category)->where('scheme_id', $scheme_id)->get();
            }

            return response($appropriations,200);
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
            $scheme = Scheme::find($scheme_id);
            $scheme_fund_category = $scheme?->fund_category;

            if($scheme_fund_category =='year'){
                $appropriation_histories = AppropriationHistory::where([
                    'owner_id' => $scheme_id,
                    'owner_type' => 'scheme',
                    'fund_category' => $fund_category,
                    ])->get();
            }else{
                $appropriation_histories = AppropriationHistory::where([
                    'owner_id' => $scheme_id,
                    'owner_type' => 'scheme'
                    ])->get();
            }

            $result1 = [];

            foreach ($appropriation_histories as $history) {
                foreach ($history->appropriation as $appr) {
                    $result1[$appr['name']] = ($result1[$appr['name']] ?? 0) + $appr['amount'];
                }
            }


            $appropriations = Appropriation::withWallet($fund_category, $scheme_fund_category)->where('scheme_id', $scheme_id)->get();

            $result2 = [];

            foreach ($appropriations as $appropriation) {
                $totalAmount = $appropriation->wallet?->balance ?? 0;
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
            $response = AppropriationHistory::where([
                'owner_id' => $request->get('owner_id'),
                'owner_type' => $request->get('owner_type')
            ])
            ->orderBy('fund_category', 'desc')
            ->orderBy('owner_id', 'desc')  // Add more columns if needed
            ->paginate(1);

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

    public function getApproTypes(){
        try{

            $app_types = AppropriationType::all();
            return response()->json($app_types);
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
