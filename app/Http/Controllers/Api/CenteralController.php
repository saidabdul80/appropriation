<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appropriation;
use App\Models\AppropriationHistory;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\Transaction;
use App\Models\Unit;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CenteralController extends Controller
{

    public function getSchemes() {
        $schemes = Scheme::all();
        foreach($schemes as &$scheme) {
            foreach ( $scheme->appropriations as $key => $appropriation) {

                    $appropriation->main_balance = $appropriation->load('appropriation_histories')->appropriation_histories->sum(function ($history) use ($appropriation) {
                        foreach ($history->appropriation as $item) {
                            if ($item['id'] == $appropriation->id) {
                                return $item['amount'];
                            }
                        }
                        return 0;
                    });

                }
        }
        return $schemes;
    }


    public function expenditureDetails(Request $request)
    {
        try{
            $request->validate([
                "scheme_id"=>"required",
            ]);
            $scheme_id = $request->get('scheme_id');
            $fund_category = $request->get('fund_category');
            $appropriations = Appropriation::where('scheme_id',$scheme_id)->get();
            if($fund_category){
                foreach($appropriations as $key => &$appropriation){
                    $totalAmount = Transaction::where(['owner_id'=>$appropriation->id, 'owner_type'=> 'appropriation', 'fund_category'=>$fund_category])->sum('amount');
                    $appropriation->expenditure_total_amount = $totalAmount;
                }
            }else{
                foreach($appropriations as $key => &$appropriation){
                    $totalAmount = Transaction::where(['owner_id'=>$appropriation->id, 'owner_type'=> 'appropriation'])->sum('amount');
                    $appropriation->expenditure_total_amount = $totalAmount;
                }
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


    public function accountAnalytics(Request $request){
        try{


            $schemeIncomes = Scheme::with(['appropriations.wallets' => function ($query) {
                $query->where('owner_type', 'App\\Models\\Appropriation');
            }])
            ->get()
            ->map(function ($scheme) {
            /*     $scheme->balance = $scheme->appropriations->reduce(function ($carry, $appropriation) {
                    return $carry + $appropriation->wallets->where('owner_type', 'App\\Models\\Appropriation')->sum('balance');
                }, 0);

                $scheme->income = $scheme->appropriations->reduce(function ($carry, $appropriation) {
                    return $carry + $appropriation->wallets->where('owner_type', 'App\\Models\\Appropriation')->sum('total_collection');
                }, 0);
 */
                // Group by fund_category
                $scheme->categories = $scheme->appropriations->flatMap(function ($appropriation) {
                    return $appropriation->wallets->where('owner_type', 'App\\Models\\Appropriation')->map(function ($wallet) {
                        return [
                            'fund_category' => $wallet->fund_category,
                            "name"=>$wallet->owner->name,
                            "id"=>$wallet->owner_id,
                            'balance' => $wallet->balance,
                            'income' => $wallet->total_collection,
                        ];
                    });
                })->groupBy('fund_category');
                return  [
                    "id"=>$scheme->id,
                    "name"=>$scheme->name,
                    "categories"=>count($scheme->categories)<1? (object)[]:$scheme->categories,
                    "income"=>$scheme->total_collection,
                    "balance"=>$scheme->balance,
                ];
               // return $scheme;
            });


            $trasactions = DB::select("SELECT
                                JSON_UNQUOTE(JSON_EXTRACT(t.data, '$.Subject.value')) AS title,
                                JSON_UNQUOTE(JSON_EXTRACT(t.data, '$.Approval_Date.value')) AS approval_date,
                                JSON_UNQUOTE(JSON_EXTRACT(t.data, '$.Amount.value')) AS amount,
                                JSON_UNQUOTE(JSON_EXTRACT(t.data, '$.Gross_Amount.value')) AS gross_amount,
                                JSON_UNQUOTE(JSON_EXTRACT(t.data, '$.Beneficiary.value')) AS beneficiary,
                                t.fund_category
                            FROM
                                transactions t
                            WHERE  t.owner_type = 'appropriation' AND `action` = 'debit'
                            ORDER BY t.id desc
                            LIMIT 0, 25;
                            ");

            return response()->json([
                'schemes'=>$this->getSchemes(),
                'scheme_income'=> $schemeIncomes,
                'transactions'=> $trasactions
            ]);
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }



}
