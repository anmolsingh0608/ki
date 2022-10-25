@extends('layouts.app')

@section('title', 'Page Title')

@section('content')

<div class="ki-dashboard ki-box-admin">
    <div class="container-xxl px-xxl-0">
        <div class="row">
            <div class="col-md-8">
                <h2 class="m-0 mb-0 title">Edit Organization</h2>
                <p>Update an Organization</p>
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                <form method="POST" action="{{ route('organizations.update', $organization->id) }}" class="form-ki">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-3">
                        <label for="Oname" class="col-form-label col-sm-4">Org Name</label>
                        <div class="col-sm-5">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $organization->name }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="address" class="col-form-label col-sm-4">Address</label>
                        <div class="col-sm-5">
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required autofocus rows="3">{{ $organization->address }}</textarea>

                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-form-label col-sm-4">Email</label>
                        <div class="col-sm-5">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $organization->email }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="phone" class="col-form-label col-sm-4">Phone</label>
                        <div class="col-sm-5">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $organization->phone }}" required autocomplete="phone" autofocus>

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="org" class="col-form-label col-sm-4">Parent Organization</label>
                        <div class="col-sm-5">
                            <select id="parent_id" class="form-select @error('parent_id') is-invalid @enderror" name="parent_id" autofocus>
                                <option value="0">None</option>
                                @foreach ($org_list as $k => $v)
                                <option value="{{ $k }}" @if ($k==$organization->parent_id) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('parent_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dojoid" class="col-form-label col-sm-4">ORG ID</label>
                        <div class="col-sm-5">
                            <input id="org_id" type="text" class="form-control @error('org_id') is-invalid @enderror" name="org_id" value="{{ $organization->org_id }}" required autocomplete="org_id" autofocus>

                            @error('org_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 py-2 px-4 cta text-uppercase">Update Organization</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>



<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>{{ __('Edit Organization') }}</strong></div>

                <div class="card-body">
                    <p>Update an Organization</p>
                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('organizations.update', $organization->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label">{{ __('Org Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $organization->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-2 col-form-label">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required autofocus rows="3">{{ $organization->address }}</textarea>

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $organization->email }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-2 col-form-label">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $organization->phone }}" required autocomplete="phone" autofocus>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="parent_id" class="col-md-2 col-form-label">{{ __('Parent Organization') }}</label>

                            <div class="col-md-6">
                                <select id="parent_id" class="form-control @error('parent_id') is-invalid @enderror" name="parent_id" autofocus>
                                    <option value="0">None</option>
                                    @foreach ($org_list as $k => $v)
                                    <option value="{{ $k }}" @if ($k==$organization->parent_id) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>

                                @error('parent_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="org_id" class="col-md-2 col-form-label">{{ __('Org ID') }}</label>

                            <div class="col-md-6">
                                <input id="org_id" type="text" class="form-control @error('org_id') is-invalid @enderror" name="org_id" value="{{ $organization->org_id }}" required autocomplete="org_id" autofocus>

                                @error('org_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-5 mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit Org') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection