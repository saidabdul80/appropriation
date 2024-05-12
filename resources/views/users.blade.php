<?php

use App\Models\Department;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;

$users = User::orderBy('id','desc')->paginate(6);
$departments = Department::all();
$roles = Role::all();
$permissions = Permission::where('description', 'Not Like','%- NEETS')->get();

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
