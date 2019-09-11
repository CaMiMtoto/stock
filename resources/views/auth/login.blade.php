<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | Stock</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('lib/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="login-logo">
            <a href="">
                SMS
            </a>
        </div>
        @if(Session::has('message'))
            <div class="alert alert-danger flat">
                <h5>
                    {{ Session::get('message') }}
                </h5>
            </div>
        @endif

        <p class="login-box-msg">Sign in to start your session</p>


        <form method="POST" action="{{ route('signIn') }}" novalidate>
            @csrf
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="{{ __('E-Mail Address') }}"
                       name="email"
                       value="{{ old('email') }}"
                       required autocomplete="email" autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group has-feedback">
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" placeholder="{{ __('Password') }}"
                       required autocomplete="current-password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>


            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <h4 class="text-center">Or</h4>
        <a class="btn btn-link btn-block" href="">
            {{ __('Forgot Your Password?') }}
        </a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
</html>



