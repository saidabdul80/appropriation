<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\AppropriationHistory;
use App\Models\Appropriation;
use App\Models\Fund;
use App\Models\MainWallet;
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
            

            $data = $request->all();
            $id = $data['id'];    
            $data['wallet_number'] = date('ymdhis');
            if($id == ''){
                DB::beginTransaction();
                
                $res = Appropriation::create([
                    "scheme_id"=>$data['scheme_id'],    
                    "appropriation_type_id"=>$data['appropriation_type_id'],
                    "department_id"=>$data['department_id'],
                    "percentage_dividend"=>$data['percentage_dividend']
                ]);                                    
                DB::commit();
                return response(["appropriation"=>$res, "msg" =>'Created Successfuly'],200);
            }else{                           
                DB::beginTransaction();
                    $res = Appropriation::find($id);
                    $res->scheme_id= $data['scheme_id'];
                    $res->appropriation_type_id = $data['appropriation_type_id'];
                    $res->department_id = $data['department_id'];
                    $res->percentage_dividend= $data['percentage_dividend'];
                    $res->save();   
                DB::commit();                               
                return response('Updated Successfuly',200);
            }
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

    public function addScheme(Request $request)
    {
        try{

            $request->validate([
                "name"=>"required"                
            ]);
            

            $data = $request->all();
            $id = $data['id'];        
            if( $id == ''){
                $data['wallet_number'] = date('ymdhis');
                DB::beginTransaction();
                    $res = Scheme::create(["name"=>$data['name']]);
                    $wallet =  Wallet::create([
                        "owner_id"=>$res->id,
                        "wallet_number"=>$data['wallet_number'],
                        "owner_type"=>'App\\Models\\Scheme',
                        "balance"=>0.00,
                        "total_collection"=>0.00,
                    ]);
                    
                    $res->wallet_number =  $wallet->wallet_number;
                    $res->save();

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
            $fund_month_year = $request->get('fund_month_year');

            if(Fund::where(['scheme_id'=>$scheme_id,'fund_category'=>$fund_month_year,'status'=>'used'])->exists()){
                throw new \Exception('This fund has already been used');
            }
            
            DB::beginTransaction();
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
                    //"performed_by"=>auth()->user()->id,
                ]);
                $scheme = Scheme::find($scheme_id);            
                $wallet = Wallet::find($scheme->wallet->id);
                $fundCategoryValue = substr($fund_month_year,0,4);// $this->monthYearFormatter($fund_month_year,$scheme);            
                //$wallet->safe_balance += $wallet->balance;
                $wallet->balance += $amount;
                $wallet->fund_category = $fundCategoryValue; //shows the last fund category
                $wallet->fund_month_year = $fund_month_year;
                $wallet->total_collection += $amount;
                $wallet->source_id = $source_id;
                $wallet->description = $description;
                $wallet->save();

                if($scheme->fund_type=='api'){
                    Fund::where(['scheme_id'=>$scheme->id,'fund_category'=>$fund_month_year])->update([
                        'amount'=> $amount,
                        'status'=>'used'
                    ]);
                }
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
                    $token = walletToken($fundCategoryValue,$appropriation->id);
                    $appropriationWithWallet = Appropriation::with(['wallet'=>function($query) use($token){
                        $query->where(['token'=>$token]);
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

    private function monthYearFormatter($date, $scheme){
        $fundCategoryValue = '';
        if($scheme->fund_category == 'month'){
            $fundCategoryValue = Carbon::parse($date)->format('m-Y');                            
        }else{
            $fundCategoryValue = Carbon::parse($date)->format('Y');                            
        }
        return $fundCategoryValue;
    }


 
}
