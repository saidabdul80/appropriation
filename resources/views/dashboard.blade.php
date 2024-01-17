<?php

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\Wallet;
$schemes = Scheme::all();
/* $ids = Appropriation::where('scheme_id', $scheme_id)->pluck('id');
$fund_categories = Wallet::whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category')->unique();
$dpartments = Department::all(); */
?>
<style>
    #checkboxes{top:34px;}
</style>
@extends('layouts/master')
@section('content')
    <dashboard-screen 
        :schemes="{{json_encode($schemes)}}"
    />
@endsection