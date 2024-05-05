<?php

namespace App\Http\Controllers;

use App\Models\Appropriation;
use App\Models\Scheme;
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
            'owner_type' => "required"
        ]);

        $id = $request->get('owner_id');

        if ($request->get('owner_type') == 'appropriation') {
            try{
                $scheme_id =  Appropriation::find($id[0])?->scheme_id;
            }catch(\Exception $e){
                return response()->json("Select an Appropriation",400);
            }

            $scheme_fund_category = Scheme::find($scheme_id)?->fund_category;
            $response = Appropriation::withWallet( $request->get('fund_category'),$scheme_fund_category)->whereIn('id', $id)->get();
            $balance = $response?->sum('wallet.balance')??0;  // Use first() to get a single model instance
            $income = $response?->sum('wallet.total_collection')??0;  // Use first() to get a single model instance
            return response()->json([
                "balance"=>$balance,
                "income"=>$income,
            ], 200);
        }

        // Handle other cases or return an appropriate response
        return response()->json("Invalid owner_type", 400);
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
