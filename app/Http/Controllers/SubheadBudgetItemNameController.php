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
            $request->validate([
                'name' => 'required|string|max:255|unique:expenditure_categories,name'
            ]);

            SubheadBudgetItemName::updateOrCreate(['name' => $request->name]);
            return response()->json('Category created successfully.', 200);
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
