<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\Scheme;
use App\Models\SubHeadBudget;
use App\Models\SubheadBudgetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubHeadBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subHeadBudgets = SubHeadBudget::all();
        return response()->json($subHeadBudgets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required|string|max:199|unique:subHeadBudgets,name,NULL,id,appropriation_id,' . $request->appropriation_id,
                'appropriation_id' => 'required|integer|exists:departments,id',
            ]);

            $subHeadBudget = SubHeadBudget::create($validated);
            return response()->json( 'SubHeadBudget created successfully');
        }catch(ValidationException $e){
            return response(collect($e->getMessage())->first() ?? $e->getMessage() ,400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subHeadBudget = SubHeadBudget::find($id);

        if (!$subHeadBudget) {
            return response()->json(['message' => 'SubHeadBudget not found'], 404);
        }

        return response()->json($subHeadBudget);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCreate(Request $request)
    {
        try{
            $validated = $request->validate([
                'subhead_id'=>'required|integer',
                'amount' => 'required|numeric',
                'fund_category'=>'required',
                'appropriation_id' => 'required|integer',
            ]);
            DB::beginTransaction();


            $scheme_id =  Appropriation::find($validated['appropriation_id'])?->scheme_id;
            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;

            $head = Appropriation::withWallet($validated['fund_category'],$scheme_fund_category)->where('id',$validated['appropriation_id'])->first();
            if($head?->wallet?->balance < $validated['amount']){
                throw new \Exception('Insufficient Fund on '. ($head->name?? 'the selected Head'). ' (Fund Available: '.number_format($head->wallet->balance,2).')');
            }

            $subHeadBudget = SubHeadBudget::updateOrCreate(
                ['subhead_id' => $validated['subhead_id'], 'appropriation_id' => $validated['appropriation_id'], 'fund_category'=>$validated['fund_category']],
                ['amount' => $validated['amount']]
            );

            $subHeadBudget->update($validated);

            if($head->budget_location === 'head'){
                $head->budget_location = 'subhead';
                $head->save();
            }

            DB::commit();
            return response()->json('SubHeadBudget updated successfully');
        }catch(ValidationException $e){
            return response(collect($e->getMessage())->first()[0] ?? $e->getMessage() ,400);
        }catch(\Exception $e){
            DB::rollBack();
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }

    public function getSubHeadBudgetByAppropriation($id,$fund_category)
    {
        try{
            $subHeadBudgets = SubHeadBudget::where('appropriation_id',$id)->where('fund_category',$fund_category)->get();
            return response()->json($subHeadBudgets);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);
            }else{
                return response('failed',400);
            }
        }
    }

    public function destroy($id)
    {
        try {
            //condition that subheadbudget not having subheadBudgetItem\
            if(SubheadBudgetItem::where('subhead_budget_id',$id)->exists()){
                throw new \Exception('Cannot delete this subhead budget');
            }
            SubHeadBudget::find(intval($id))->delete();
            return response()->json('Subhead budget deleted successfully.', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
