<?php

use App\Models\Configuration;
use App\Models\School;
use App\Models\Session;
use App\Models\StaffInfo;
use App\Models\Term;
use Illuminate\Support\Facades\Auth;

function user(){
    if (Auth::guard('admin')->check()) {
        return Auth::guard('admin')->user();
    } else if (Auth::guard('staff')->check()) {
        return Auth::guard('staff')->user();
    }
}
function current_session(){
    return Session::where('status','1')->first();
}

function current_term(){
    return Term::where('status','1')->first();
}

function school(){
    return School::find(1);
}
function swithPageLogic(){
    return `if(type == 1){
        $("#appIn").css('visibility','visible')                
        $("#loader").css('dispaly','none');
    }else{
        $("#loader").css('dispaly','flex');
        $("#appIn").css('visibility','hidden')
    }`;
}

function generateSerialNumber($start, $number){
    return $start.str_pad($number,4,'0',STR_PAD_LEFT);
}

