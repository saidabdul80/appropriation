<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Unit;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function departmentIndex()
    {
        return view('department');
    }

    public function createUpdate(Request $request){
        try {
            
        
            $type = $request->get('type');
            $short_name = $request->get('short_name');
            $name = $request->get('name');
            $unit_ids = $request->get('unit_ids');
            $id = $request->get('id');
            if($type=='department'){
                //Department
                
                if($id ==''){
                    if(Department::where('short_name', $short_name)->exists()){
                        return response('Department short name already exists',409);
                    }
                    $department = Department::create([
                        'name'=>$name,
                        'short_name'=>$short_name
                    ]);
                    
                    Unit::whereIn('id',$unit_ids)->update(['department_id'=>$department->id]);
                    return response('Department Created',200);
                }else{
                    if(Department::whereNot('id', $id)->where('short_name', $short_name)->exists()){
                        return response('Department short name already exists',409);
                    }
                    Department::where('id',$id)->update([
                        'name'=>$name,
                        'short_name'=>$short_name
                    ]);
                    Unit::whereIn('id',$unit_ids)->update(['department_id'=>$id]);
                    return response('Department Update',200);
                }
            }else{
                //unit
                
                if($id ==''){
                    if(Unit::where('short_name', $short_name)->exists()){
                        return response('Unit short name already exists',409);
                    }
                    Unit::insert([
                        'name'=>$name,
                        'short_name'=>$short_name
                    ]);
                    return response('Unit Created',200);
                }else{
                    if(Unit::whereNot('id', $id)->where('short_name', $short_name)->exists()){
                        return response('Unit short name already exists',409);
                    }
                    Unit::where('id',$id)->update([
                        'name'=>$name,
                        'short_name'=>$short_name
                    ]);
                    return response('Unit Update',200);
                }
            }
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
}
