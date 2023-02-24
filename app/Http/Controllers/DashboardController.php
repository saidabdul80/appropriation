<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Unit;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class DashboardController extends Controller
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

    public function byProgramme(Request $request){
        try {
            $request->validate([
                "programme_id"=>"required"
            ]);
            $programme_id = $request->get("programme_id");
            $appropriations = collect(DB::select(DB::raw("SELECT  b.name, w.balance, w.total_collection, w.fund_category FROM appropriations a JOIN appropriation_types b ON b.id=a.appropriation_type_id JOIN wallets w ON (a.id= w.owner_id AND w.owner_type = 'App\\\Models\\\Appropriation') WHERE a.scheme_id = $programme_id")))
                            ->groupBy('name');
            foreach($appropriations as $key => &$approps){
                foreach($approps as $key2 => &$approp){
                    unset($approp->name);
                    $approps[$key2] = array_values((array) $approp);
                }                
            }
                            
            return  response(["appropriations" =>$appropriations],200);             
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
