<?php

namespace App\Http\Controllers;

use App\Models\SubheadBudgetItem;
use App\Models\Virement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VirementController extends Controller
{
    public function subheadBudgetItem(Request $request){
        try{
            $request->validate([
                'source_id'=>"required",
                'destination_id'=>"required",
                'amount'=> "required"
            ]);

           $source = SubheadBudgetItem::find($request->source_id);
           $destination = SubheadBudgetItem::find($request->destination_id);
           $amount  = $request->amount;
            if(!empty($source)){
                if($amount > $source->balance){
                    throw new \Exception('Insufficient Fund on Subhead Budget Item');
                }
            }

            DB::beginTransaction();
                $source->amount -= $amount;
                $source->save();

                $destination->amount += $amount;
                $destination->save();

                Virement::create([
                    'owner_type' => 'SubheadBudgetItem',
                    'source_id' => $source->id,
                    'destination_id' => $destination->id,
                    'amount' => $amount,
                    'created_by' => $request->user()->id,
                ]);
            DB::commit();
            return response()->json('success', 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }

    }
}
