@extends('layouts.app')

@section('title', 'Dojo')

@section('content')



<div class="ki-dashboard ki-box-admin">
    <div class="container-xxl px-xxl-0">
        <div class="row">
            <div class="col-md-8">
                <h2 class="m-0 mb-0 title">Edit Dojo</h2>
                <p>Update an Dojo</p>
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" class="form-ki" action="{{ route('dojos.update', $dojo->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-3">
                        <label for="dname" class="col-form-label col-sm-4">Dojo Name</label>
                        <div class="col-sm-5">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $dojo->name }}" required autocomplete="name" autofocus>

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
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required autofocus rows="3">{{ $dojo->address }}</textarea>

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
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $dojo->email }}" required autocomplete="email">

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
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $dojo->phone }}" required autocomplete="phone" autofocus>

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="org" class="col-form-label col-sm-4">Organization</label>
                        <div class="col-sm-5">
                            <select id="organization_id" class="form-select @error('organization_id') is-invalid @enderror" name="organization_id" value="{{ old('organization_id') }}" autofocus>
                                <option value="">None</option>
                                @foreach ($org_list as $k => $v)
                                <option value="{{ $k }}" @if($k==$dojo->organization_id) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('organization_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sub-org" class="col-form-label col-sm-4">Sub Organization</label>
                        <div class="col-sm-5">
                            <select id="sub_organization_id" class="form-select @error('sub_organization_id') is-invalid @enderror" name="sub_organization_id" value="{{ old('sub_organization_id') }}" autofocus>
                                <option value="">None</option>
                                @foreach ($sub_org_list as $k => $v)
                                <option value="{{ $k }}" @if($k==$dojo->sub_organization_id) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('sub_organization_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="country" class="col-form-label col-sm-4"> Country</label>
                        <div class="col-sm-5">
                            <select id="country" class="form-select @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" autofocus>
                                @foreach ($countries as $k => $v)
                                <option value="{{ $v }}" @if($v==$dojo->country) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dojoid" class="col-form-label col-sm-4">Dojo ID</label>
                        <div class="col-sm-5">
                            <input id="dojo_id" type="text" class="form-control @error('dojo_id') is-invalid @enderror" name="dojo_id" value="{{ $dojo->dojo_id }}" required autocomplete="org_id" autofocus>

                            @error('dojo_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-form-label col-sm-4">Status</label>
                        <div class="col-sm-5">
                            <select name="status" id="status" class="form form-select @error('status') is-invalid @enderror" required autocomplete="status">
                                <option value="active" @if($dojo->status == "active") selected="selected" @endif>Active</option>
                                <option value="closed" @if($dojo->status == "closed") selected="selected" @endif>Closed</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 py-2 px-4 cta text-uppercase">Update Dojo</button>
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
                <div class="card-header"><strong>{{ __('Edit Dojo') }}</strong></div>

                <div class="card-body">
                    <p>Update an Dojo</p>
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dojos.update', $dojo->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label">{{ __('Dojo Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $dojo->name }}" required autocomplete="name" autofocus>

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
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required autofocus rows="3">{{ $dojo->address }}</textarea>

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $dojo->email }}" required autocomplete="email">

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
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $dojo->phone }}" required autocomplete="phone" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="organization_id" class="col-md-2 col-form-label">{{ __('Organization') }}</label>

                            <div class="col-md-6">
                                <select id="organization_id" class="form-control @error('organization_id') is-invalid @enderror" name="organization_id" value="{{ old('organization_id') }}" autofocus>
                                    <option value="">None</option>
                                    @foreach ($org_list as $k => $v)
                                        <option value="{{ $k }}" @if($k == $dojo->organization_id) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>

                                @error('organization_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sub_organization_id" class="col-md-2 col-form-label">{{ __('Sub Organization') }}</label>

                            <div class="col-md-6">
                                <select id="sub_organization_id" class="form-control @error('sub_organization_id') is-invalid @enderror" name="sub_organization_id" value="{{ old('sub_organization_id') }}" autofocus>
                                @foreach ($sub_org_list as $k => $v)
                                    <option value="{{ $k }}" @if($k == $dojo->sub_organization_id) selected @endif>{{ $v }}</option>
                                @endforeach
                                </select>

                                @error('sub_organization_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country" class="col-md-2 col-form-label">{{ __('Country') }}</label>

                            <div class="col-md-6">
                                <select id="country" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" autofocus>
                                    @foreach ($countries as $k => $v)
                                        <option value="{{ $v }}" @if($v == $dojo->country) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dojo_id" class="col-md-2 col-form-label">{{ __('Dojo ID') }}</label>

                            <div class="col-md-6">
                                <input id="dojo_id" type="text" class="form-control @error('dojo_id') is-invalid @enderror" name="dojo_id" value="{{ $dojo->dojo_id }}" required autocomplete="org_id" autofocus>

                                @error('dojo_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-5 mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit Dojo') }}
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