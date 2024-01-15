<?php

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\Wallet;

$scheme_id = request('scheme_id');

$scheme = Scheme::find($scheme_id);
if(empty($scheme)){
    return abort(404);
}
$ids = Appropriation::where('scheme_id', $scheme_id)->pluck('id');
$fund_categories = Wallet::whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category')->unique();
$departments = Department::all();
?>
<style>
    #checkboxes{top:34px;}
</style>
@extends('layouts/master')
@section('content')
<report-screen 
    :departments="{{json_encode($departments)}}"
    :selected_scheme="{{$scheme}}"
    :fund_categories="{{$fund_categories}}"
    agencyName="{{agencyName()}}"
/>
@endsection
