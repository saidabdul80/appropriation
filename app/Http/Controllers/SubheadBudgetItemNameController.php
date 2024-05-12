<?php

namespace App\Http\Controllers;

use App\Models\SubheadBudgetItem;
use App\Models\SubheadBudgetItemName;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubheadBudgetItemNameController extends Controller
{

    public function index(Request $request){
        if($request->has('search')){
            $SubheadBudgetItemName = SubheadBudgetItemName::where('name','Like',"%$request->search%")->take(100)->latest()->get();
        }else{
            $SubheadBudgetItemName = SubheadBudgetItemName::take(100)->latest()->get();
        }
        return response()->json($SubheadBudgetItemName, 200);
    }

    public function store(Request $request)
    {
        try {
                // Validate the request data
            $rules = [
                'name' => 'required|string|max:255|unique:expenditure_categories,name',
                'outcome' => 'required|string',
                'output' => 'required|string'
            ];

            // If updating an existing record, exclude the current ID from unique check
            if (!empty($request->id)) {
                $rules['name'] .= ',' . $request->id;
            }

            $request->validate($rules);


            if (!empty($request->id)) {
                SubheadBudgetItemName::where('id', $request->id)->update([
                    'name' => $request->name,
                    'output' => $request->output,
                    'outcome' => $request->outcome
                ]);
                return response()->json('Updated successfully.', 200);
            } else {
                SubheadBudgetItemName::create([
                    'name' => $request->name,
                    'output' => $request->output,
                    'outcome' => $request->outcome
                ]);
                return response()->json('Created successfully.', 200);
            }
        }catch(ValidationException $e){
            return response(collect($e->getMessage())->first() ?? $e->getMessage() ,400);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category', 'message' => $e->getMessage()], 400);
        }
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        try {
            if(!SubheadBudgetItem::where('item_name_id',$id)->exists()){
                throw new \Exception("Cannot delete: Record already in use");
            }
            SubheadBudgetItemName::find(intval($id))->delete();
            return response()->json('SubheadBudgetItemName deleted successfully.', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
