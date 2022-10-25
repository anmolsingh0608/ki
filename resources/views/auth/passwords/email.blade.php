@extends('layouts.guest')

@section('content')

<section class="wrap">
    <div class="ki-box-admin mb-4">
        <div class="container-xxl px-xxl-0">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-5 title">Reset Password</h2>
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}" class="form-ki">
                        @csrf
                        <div class="row mb-3">
                            <label for="email" class="col-form-label col-sm-4">E-mail Address</label>
                            <div class="col-sm-5">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="cta-row d-flex mt-5">

                            <button type="submit" class="border-0 cta text-uppercase">Send Password Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection