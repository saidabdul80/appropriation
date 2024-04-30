<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\Wallet;
use Illuminate\Http\Request;
use stdClass;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getWalletsBalance(Request $request)
    {   
        $request->validate([
            'owner_id' => "required",
            'owner_type' => "required",
            'fund_category' => "required"            
        ]);
    
        $id = $request->get('owner_id');
    
        if ($request->get('owner_type') == 'appropriation') {
            $response = Appropriation::with(['wallet' => function ($query) use ($request) {
                $query->where('fund_category', $request->get('fund_category'))
                      ->where('owner_type', 'App\\Models\\Appropriation');
            }])->whereIn('id', $id)->get();
            $response = $response?->sum('wallet.balance');  // Use first() to get a single model instance
    
            return response($response?? 0, 200);

        }
    
        // Handle other cases or return an appropriate response
        return response("Invalid owner_type", 400);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
