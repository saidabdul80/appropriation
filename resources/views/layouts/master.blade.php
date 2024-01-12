<?php


?>
@extends('/layouts/links')
@section('body')
@if(request()->path() !== 'login')
@include('layouts.nav')
@endif
<div id="loaderHtml" class="w-100 position-absolute xloader">
            <div class="middle">
                <div class="bar bar1"></div>
                <div class="bar bar2"></div>
                <div class="bar bar3"></div>
                <div class="bar bar4"></div>
                <div class="bar bar5"></div>
                <div class="bar bar6"></div>
                <div class="bar bar7"></div>
                <div class="bar bar8"></div>
            </div>
</div>   
@yield('vueScript')
<div class="row w-100 m-0" id="appIn">    
    <div class="col-xs-12 col-md-12 col-lg-12 pb-0 bg-white position-relative p-0" id="app">
           
        @yield('content')        
    </div>
</div>
@yield('newscript')
</div>
@endsection