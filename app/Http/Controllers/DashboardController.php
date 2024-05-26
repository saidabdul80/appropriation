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

            $user = $request->user();
            $permissions = $user->permissions->pluck('name')->toArray();


            $programme_id = $request->get("programme_id");
            /* $appropriations = collect(DB::select(DB::raw("SELECT  b.name, w.balance, w.total_collection, w.fund_category, a.department FROM appropriations a
                                                            JOIN appropriation_types b ON b.id=a.appropriation_type_id JOIN wallets w ON
                                                            (a.id= w.owner_id AND w.owner_type = 'App\\\Models\\\Appropriation') WHERE a.scheme_id = $programme_id")))
                            ->groupBy('name'); */
                $appropriations = Appropriation::where('scheme_id', $programme_id)
                ->join('appropriation_types', 'appropriation_types.id', '=', 'appropriations.appropriation_type_id')
                ->join('wallets', function($join) {
                    $join->on('wallets.owner_id', '=', 'appropriations.id')
                            ->where('wallets.owner_type', 'App\\Models\\Appropriation');
                })
                ->select('appropriation_types.name','appropriations.*', 'wallets.balance', 'wallets.total_collection', 'wallets.fund_category')
                ->get()
                ->groupBy('name');

                $response = [];
                foreach ($appropriations as $key => $appropriationGroup) {
                    foreach ($appropriationGroup as $key2 => $approp) {
                        unset($approp->name);
                        $used = [
                         $approp->balance,
                         $approp->total_collection,
                         $approp->fund_category,
                        ];

                        // Check permissions
                        $departmentList = explode(',', $approp->department);
                        if (array_intersect($departmentList, $permissions)) {
                            $response[$key][] = $used;
                        }
                    }
                }


            return  response(["appropriations" =>$response],200);
        }catch(ValidationException $e){
            return response($e->getMessage(),400);
        }catch(\Exception $e){
            if(env('APP_DEBUG')){
                return response($e,400);
            }else{
                return response('failed',400);
            }
        }
    }
}
