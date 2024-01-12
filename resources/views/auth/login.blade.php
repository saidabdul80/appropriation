@extends('layouts/master')
@section('content')

<div themebg-pattern="theme1"  style="background:url('/images/parallax_05.png');height:100vh;">
  
    <section class="login-block" >
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12" >
                    <!-- Authentication card start -->

                    <form class="md-float-material form-material" action="{{ route('login')}}" method="POST">
                        {{ csrf_field()}}
                        <div class="text-center">
                            <img src="/images/logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box card" >
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Sign In</h3>
                                    </div>
                                </div>
                                @if(session('error'))
                                    <div class="alert alert-danger text-center">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <div class="form-group form-primary">
                                    <input type="text" class="form-control" placeholder="User ID (e.g NGSCHA/HMU/0001)" name="nicare_code" value="{{ old('nicare_code') }}" required>
                                    <span class="form-bar"></span>
                                    <label class="float-label"></label>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="password" placeholder="Password" name="password" class="form-control" password="password" required>
                                    <span class="form-bar"></span>
                                    <label class="float-label"></label>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12 mb-2">                                        
                                        <div class="checkbox-fade fade-in-primary d-">
                                            <label>
                                                <input type="checkbox" value="">
                                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Remember me</span>
                                            </label>
                                        </div>
                                        <div class="forgot-phone text-right f-right">
                                            <!--<a href="auth-reset-password.html" class="text-right f-w-600"> Forgot Password?</a>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign in</button>
                                    </div>
                                </div>
                                <hr/>
                               
                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
</div>

@endsection
