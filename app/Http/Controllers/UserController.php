<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userIndex()
    {
        return view('users');
    }

    public function changePassword(Request $request){
        try{
    
            $id = $request->get('id');            
            $password = Hash::make($request->get('password'));
            User::where('id',$id)->update(['password'=>$password]);
            return response('Changed Successfully',200);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }
    }
    public function newUpdateUser(Request $request){
        try{
            $id = $request->get('id');
            $data = $request->all();
            unset($data['confirm_password']);
            if($id !=''){
                unset($data['password']);
                if(User::whereNot('id',$id)->where('email', $data['email'])->exists()){
                    return response('Email already exists',409);
                }
                User::where('id',$id)->update($data);
            }else{
                $data['password']= Hash::make($data['password']);
                if(User::where('email', $data['email'])->exists()){
                    return response('Email already exists',409);
                }
                User::insert($data);
                return response('Created Successfully',200);
            }
            return response('Done',200);
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

    public function assignRole(Request $request){
        try{
    
            $user = User::find($request->get('model_id'));            
            if($request->get('type')=='assign'){
                $user->assignRole($request->get('role'));
            }else{                
                $user->removeRole($request->get('role'));
            }
            return response('Updated Successfully',200);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }
    }

    public function assignPermission(Request $request){
        try{
    
            $user = User::find($request->get('model_id'));            
            if($request->get('type')=='assign'){
                $user->givePermissionTo($request->get('permission'));
            }else{
                $user->revokePermissionTo($request->get('permission'));
            }
            return response('Updated Successfully',200);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e->getMessage(),400);                
            }else{
                return response('failed',400);
            }
        }
    }
}
