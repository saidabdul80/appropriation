<?php
use App\Models\Configuration;

function walletToken($id,$fund_category){
    return 'app'.$fund_category.$id;
}
function agencyName(){
    return Configuration::where('name','agency_name')->first()?->value;
}