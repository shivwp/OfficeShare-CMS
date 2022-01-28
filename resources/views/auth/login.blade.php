@extends('layouts.app')
@section('content')
<section class="login_section">

<div class="wrapper wrapper-full-page">
    <div class="page-header login-page header-filter">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-8 ml-auto mr-auto" id="login_card_wrapper">
                    {{-- <div class="card">
                        <div class="card-header card-header-primary text-center">
                            <h4 class="card-title">
                                <strong>Sign in to start your session</strong>
                            </h4>
                        </div>
                        <br>
                    </div>--}}

                    <div class="logo mb-5 mt-3">
                        <img src="{{asset('logo/logo_cms.png')}}">
                    </div>
                    <div class=" card-login mb-3">


                        <div class="card-body login-card-body">
                            @if(\Session::has('message'))
                            <p class="alert alert-info">
                                {{ \Session::get('message') }}
                            </p>
                            @endif

                            <form action="{{ route('login') }}" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group">
                                  <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.login_email') }}" name="email" value="{{ old('email', null) }}">
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}" name="password">
                                    @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                    @endif
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="icheck-primary">
                                            <input type="checkbox" name="remember" id="remember">
                                            <label for="remember">{{ trans('global.remember_me') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-link btn-lg">{{ trans('global.login') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.login-card-body -->
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<style type="text/css">
    .form-group input {
    background-color: transparent;
}
.card-footer.justify-content-center {
    padding: 6px;
    text-align: center;
    background-color: transparent;
    border: 0px;
    color: #fff;
}
.logo.mb-5.mt-3 {
    text-align: center;
    background-color: transparent;
}
.btn.btn-primary.btn-link {
    background-color: #0d324d;
    color: #fff;
    box-shadow: none;
    height: 40px;
    width: 100%;
}
.icheck-primary {
    padding-left: 10px;
}
[class*=icheck-]>input:first-child+input[type=hidden]+label::before, [class*=icheck-]>input:first-child+label::before{
    border: 1px solid #9e9e9e!important;
}
.btn.btn-primary.btn-link:hover, .btn.btn-primary.btn-link:focus, .btn.btn-primary.btn-link:active {
     background-color: #0d324d; 
      color: #fff;
      -webkit-box-shadow: 1px 3px 4px 0px rgba(50, 50, 50, 0.95);
-moz-box-shadow:    1px 3px 4px 0px rgba(50, 50, 50, 0.95);
box-shadow:         1px 3px 4px 0px rgba(50, 50, 50, 0.95);
}
.user-icon {
    color: #000;
font-size: 30px;
}
button.btn.btn-primary.btn-link.btn-lg {
    background: #fc6565;
}
</style>
@endsection