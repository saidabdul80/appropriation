<?php

namespace App\Http\Controllers;

use App\Models\SubHeadBudget;
use App\Models\SubheadBudgetItem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubheadBudgetItemController extends Controller
{
    public function index()
    {
        $subheadBudgetItems = SubheadBudgetItem::all();
        return response()->json($subheadBudgetItems);
    }

    public function updateCreate(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_name_id' => 'required',
                'subhead_budget_id' => 'required',
                'amount' => 'required|numeric',
            ]);

            // Fetch the total amount of the subhead budget
            $subheadBudgetBalance = SubHeadBudget::getBalanceAfterAllocation($validated['subhead_budget_id']);

            // Retrieve the existing subhead budget item
            $subheadItem = SubheadBudgetItem::where('item_name_id', $validated['item_name_id'])
                ->where('subhead_budget_id', $validated['subhead_budget_id'])
                ->first();

            if (!empty($subheadItem)) {
                //there is logic error hear because when updating amount and  there is still enough balance on  $subheadBudgetBalance it should work
                $usedAmount = SubheadBudgetItem::usedAmount($subheadItem->id);
                $balance  = $subheadItem->amount - $usedAmount;

                if ($validated['amount'] > $balance && $validated['amount'] > $subheadBudgetBalance) {
                    throw new \Exception('Insufficient Fund on Subhead Budget Item');
                }

            } else {
                // Check if the new amount exceeds the total subhead budget amount
                if ($validated['amount'] > $subheadBudgetBalance) {
                    throw new \Exception('Insufficient Fund on Subhead Budget');
                }
            }

            SubheadBudgetItem::updateOrCreate(
                ['item_name_id' => $validated['item_name_id'], 'subhead_budget_id' => $validated['subhead_budget_id']],
                ['amount' => $validated['amount'], 'actual_amount' => $validated['amount'],'outcome' => $request->outcome,'output' => $request->output]
            );

            return response()->json('SubheadBudgetItem saved or updated successfully');
        } catch (ValidationException $e) {
            return response(collect($e->getMessage())->first() ?? $e->getMessage(), 400);
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return response($e->getMessage(), 400);
            } else {
                return response('failed', 400);
            }
        }
    }


    public function show($id)
    {
        $subheadBudgetItem = SubheadBudgetItem::find($id);

        if (!$subheadBudgetItem) {
            return response()->json(['message' => 'SubheadBudgetItem not found'], 400);
        }

        return response()->json($subheadBudgetItem);
    }

    public function bySubheadBudgetId($id)
    {
        try{
            $subheadBudgetItem = SubheadBudgetItem::where('subhead_budget_id',$id)->get();
            return response()->json($subheadBudgetItem);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {
        try {
            SubheadBudgetItem::findOrFail($id)->delete();
            return response()->json('Subhead budget item deleted successfully.', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
