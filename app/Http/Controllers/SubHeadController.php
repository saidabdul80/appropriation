<?php

namespace App\Http\Controllers;

use App\Models\SubHead;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubHeadController extends Controller
{

    /* public function index()
    {
        $SubHead = SubHead::all();
        return response()->json($SubHead);
    } */

    public function index(Request $request){
        if($request->has('search')){
            $SubHead = SubHead::where('name','Like',"%$request->search%")->take(100)->latest()->get();
        }else{
            $SubHead = SubHead::take(100)->latest()->get();
        }

        return response()->json($SubHead);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:expenditure_categories,name'
            ]);

            SubHead::updateOrCreate(['name' => $request->name]);
            return response()->json('Category created successfully.', 200);
        }catch(ValidationException $e){
            return response(collect($e->getMessage())->first() ?? $e->getMessage() ,400);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category', 'message' => $e->getMessage()], 400);
        }
    }

    // Update the specified category in storage
    public function update(Request $request, SubHead $SubHead)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:expenditure_categories,name'
            ]);

            $SubHead->update($request->all());
            return response()->json('Category updated successfully.', 200);
        }catch(ValidationException $e){
            return response(collect($e->getMessage())->first() ?? $e->getMessage() ,400);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        try {
            SubHead::find(intval($id))->delete();
            return response()->json('Subhead deleted successfully.', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
