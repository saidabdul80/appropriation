<?php

use App\Models\Department;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$users = User::orderBy('id','desc')->paginate(6);
$departments = Department::all();
$roles = Role::all();
$permissions = Permission::all();

?>
@extends('layouts/master')
@section('content')
<user-screen 
:departments="{{json_encode($departments)}}"
:def_users="{{json_encode($users)}}"
:permissions="{{json_encode($permissions)}}"
:roles="{{json_encode($roles)}}"
/>
@endsection