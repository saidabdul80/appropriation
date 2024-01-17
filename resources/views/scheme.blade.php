
<?php
use App\Models\AppropriationType;
use App\Models\Department;
use App\Models\Scheme;
$schemes = Scheme::all();
$departments = Department::all();
$appropriationTypes = AppropriationType::all();
$dyear = 2020;
$cyear = date('Y');
$years = range($dyear, $cyear);
$logedInUser = auth()->user();
$permissions = $logedInUser->permissions->pluck('name')->toArray();
?>
@extends('layouts.master') {{-- Assuming your master layout file is named master.blade.php --}}
@section('content')
    <scheme-screen
        :permissions="{{ json_encode($permissions) }}"
        :schemes="{{ json_encode($schemes) }}"
        :appropriationtypes="{{ json_encode($appropriationTypes) }}"
        :departments="{{ json_encode($departments) }}"
        :dyear="{{ $dyear }}"
        :cyear="{{ $cyear }}"
        :years="{{ json_encode($years) }}"
    />
@endsection