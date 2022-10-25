@extends('layouts.guest')

@section('content')

<section class="wrap">
    <div class="ki-box-admin mb-4">
        <div class="container-xxl px-xxl-0">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-5 title">Admin Login</h2>
                    <form method="POST" action="{{ route('login') }}" class="form-ki">
                        @csrf
                        <div class="row mb-3">
                            <label for="uname" class="col-form-label col-sm-4">E-mail Address</label>
                            <div class="col-sm-5">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-form-label col-sm-4">Password</label>
                            <div class="col-sm-5">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="cta-row d-flex mt-5">

                            <button type="submit" class="border-0 cta text-uppercase">Login</button>
                        </div>
                        <div class="link-row d-flex mt-5">
                            <ul class="nav">
                                <!-- <li><a href="#">create account </a></li> -->
                                @if (Route::has('password.request'))
                                <li>
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection