@extends('layouts.app')

@section('title', 'Admin')

@section('content')


<div class="ki-dashboard ki-box-admin">
    <div class="container-xxl px-xxl-0">
        <div class="row">
            <div class="col-md-8">
                <h2 class="m-0 mb-0 title">Add New Admin</h2>
                <p>Create a new Admin</p>
                @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" class="form-ki" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <label for="name" class="col-form-label col-sm-4">Name</label>
                        <div class="col-sm-5">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-form-label col-sm-4">E-mail</label>
                        <div class="col-sm-5">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required autofocus autocomplete="email" value="{{ old('email') }}">

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
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" required autocomplete="password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="confirm_password" class="col-form-label col-sm-4">Confirm Password</label>
                        <div class="col-sm-5">
                            <input id="confirm_password" type="password" class="form-control @error('confirm_password') is-invalid @enderror" name="confirm_password" value="" required autocomplete="confirm_password">

                            @error('confirm_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 py-2 px-4 cta text-uppercase">Add New Admin</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection