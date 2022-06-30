@extends('layouts.auth')

@section('content')
{{-- <script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
  </script> --}}
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>

    @if (Session::get('alert'))
        <script>
            swal("แจ้งเตือน", "Username หรือ Password ไม่ถูกต้อง กรุณา Login ใหม่อีกครั้ง", "warning");
        </script>
    @endif

    {{-- <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> ระบบจองบัญชีใช้งาน Zoom </div>
                <div class="panel-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'):
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('quickadmin.qa_email')</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('quickadmin.qa_password')</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('auth.password.reset') }}">@lang('quickadmin.qa_forgot_password')</a>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <label>
                                    <input type="checkbox" name="remember"> @lang('quickadmin.qa_remember_me')
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                    @lang('quickadmin.qa_login')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <style>
        /*
    /* Created by Filipe Pina
     * Specific styles of signin, register, component
     */
        /*
     * General styles
     */
        /* @font-face {
      font-family: 'psu-stidti-bold';
      src:  url('/font/psu-stidti-bold') format('woff2'),
            url('/font/psu-stidti-bold') format('woff');
    } */

        @font-face {
            font-family: psu-stidti-bold;
            src: url('{{ asset('font/psu-stidti-bold.tff') }}');
        }

        body,
        html {
            height: 100%;
            background-repeat: no-repeat;
            background-color: #d3d3d3;
            font-family: 'Oxygen', sans-serif;
            /* background-image: linear-gradient(to top, #d16ba5, #c66eb5, #b673c4, #a179d1, #8780dc, #6d8fea, #4b9cf4, #00a9f9, #00c0ff, #00d5ff, #00e9f9, #5ffbf1); */
            /* width: 100%;
        background-attachment: fixed;
        background-image: url('/img/3838986.jpg');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover; */
        }

        .main {
            /* margin-top: 70px; */
        }

        p.a {
            /* font-family: "psu-stidti-bold", sans-serif; */
            font-family: 'Kanit', sans-serif;
            font-size: x-large;
            color: #003c71;
            font-weight: bold;
        }

        h1.title {
            font-size: 50px;
            font-family: 'Passion One', cursive;
            font-weight: 400;
        }

        hr {
            width: 10%;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 15px;
        }

        input,
        input::-webkit-input-placeholder {
            font-size: 11px;
            padding-top: 3px;
        }

        .main-login {
            /* background-color: #dbdbdb47; */
            background-color: #ffffff30;
            backdrop-filter: blur(6px);
            /* background-color: #fff; */
            /* shadows and rounded borders */
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);

        }

        /* .main-center {
            margin-top: 30px;
            margin: 0 auto;
            max-width: 330px;
            padding: 60px 40px;
             margin-right: 25px;
        margin-top: -60px; 
        } */

        .login-button {
            margin-top: 5px;
        }

        .login-register {
            font-size: 11px;
            text-align: center;
        }

        /* CSS */
        .button-83 {
            appearance: button;
            background-color: transparent;
            /* background-image: linear-gradient(to bottom, #fff, #f8eedb); */
            background-image: linear-gradient(to bottom, #fff, #003c6f);
            border: 0 solid #e5e7eb;
            border-radius: .5rem;
            box-sizing: border-box;
            color: #482307;
            column-gap: 1rem;
            cursor: pointer;
            display: flex;
            font-family: ui-sans-serif, system-ui, -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 100%;
            font-weight: 700;
            line-height: 24px;
            margin: 0;
            outline: 2px solid transparent;
            padding: 1rem 1.5rem;
            text-align: center;
            text-transform: none;
            transition: all .1s cubic-bezier(.4, 0, .2, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            box-shadow: -6px 8px 10px rgba(81, 41, 10, 0.1), 0px 2px 2px rgba(81, 41, 10, 0.2);
            place-content: center;
        }

        .button-83:active {
            background-color: #f3f4f6;
            box-shadow: -1px 2px 5px rgba(81, 41, 10, 0.15), 0px 1px 1px rgba(81, 41, 10, 0.15);
            transform: translateY(0.125rem);
        }

        .button-83:focus {
            box-shadow: rgba(72, 35, 7, .46) 0 0 0 4px, -6px 8px 10px rgba(81, 41, 10, 0.1), 0px 2px 2px rgba(81, 41, 10, 0.2);
        }

        .button-83:hover {
            color: #482307;
        }

        video {
            object-fit: cover;
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }

        .viewport-header {
            position: relative;
            height: 100vh;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
    @if ((new \Jenssegers\Agent\Agent())->isDesktop())
        {{-- เช็คว่าอุปกรณ์เป็น Desktop --}}
        <style>
            .main-center {
                margin-top: 30px;
                margin: 0 auto;
                max-width: 330px;
                padding: 40px 40px;
            }

        </style>
        <video src="{{ url('video/login_desktop.mp4') }}" autoplay loop playsinline muted></video>
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isMobile())
        {{-- เช็คว่าอุปกรณ์เป็น Desktop --}}
        <style>
            .main-center {
                margin-top: 30px;
                margin: 0 auto;
                max-width: 330px;
                padding: 40px 40px;
                padding-top: 15px;
                margin-top: 120px;
                padding-bottom: 15px;
            }

        </style>
        <video src="{{ url('video/login_mobile.mp4') }}" autoplay loop playsinline muted></video>
    @endif



    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-8">

            {{-- <div class="login-logo">
                <a href="#"><b>ZBA</b>System</a>
            </div> --}}

            <div class="container">
                <div class="row main">
                    <div class="main-login main-center">
                        {{-- <h1 class="a">ระบบจองบัญชี zoom</h1> --}}
                        <p class="a">ระบบจองบัญชี ZOOM</p>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="username" class="cols-sm-2 control-label">ชื่อผู้ใช้</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user fa"
                                                aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                            placeholder="Username" required>
                                    </div>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="cols-sm-2 control-label">รหัสผ่าน</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock fa-lg"
                                                aria-hidden="true"></i></span>
                                        <input type="password" class="form-control" name="password" placeholder="Password"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <button type="submit" class="btn btn-primary btn-lg btn-block button-83">เข้าสู่ระบบ</button>
                            </div>
                            {{-- <div class="login-register">
                                <input type="checkbox" name="remember"> remember <a
                                    href="{{ route('auth.password.reset') }}">reset password</a>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
