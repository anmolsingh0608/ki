@extends('layouts.app')

@section('title', 'Edit Card Request')

@section('content')

<div class="ki-dashboard ki-box-admin">
    <div class="container-xxl px-xxl-0">
        <div class="row">
            <div class="col-md-8">
                <h2 class="m-0 mb-0 title">Edit Card Request</h2>
                <p>Update a Card Request</p>
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('newrequest.update', $new_request->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-3">
                        <label for="country" class="col-form-label col-sm-4">Order Name</label>
                        <div class="col-sm-5">
                            <input id="order_name" type="text" class="form-control @error('order_name') is-invalid @enderror" name="order_name" value="{{ old('name', $new_request->name) }}" required autocomplete="order_name" autofocus="">
                            @error('order_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="country" class="col-form-label col-sm-4">Country</label>
                        <div class="col-sm-5">
                            <select id="country" class="form-select @error('country') is-invalid @enderror" name="country" required autofocus>
                                @foreach ($countries as $k => $v)
                                <option value="{{ $v }}" @if($v==$new_request->country) selected @endif>{{ $v }}</option>
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
                        <label for="cards requested" class="col-form-label col-sm-4">Cards Requested</label>
                        <div class="col-sm-5">
                            <input id="no-of-cards" type="text" class="form-control @error('Cards Requested') is-invalid @enderror" name="no_of_cards" value="{{ $new_request->no_of_cards }}" required autocomplete="no_of_cards" autofocus>

                            @error('no_of_cards')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ship_to" class="col-form-label col-sm-4">Ship To</label>
                        <div class="col-sm-5">
                            <select id="ship_to" class="form-select @error('ship_to') is-invalid @enderror" name="ship_to" autofocus>
                                <option value="">None</option>
                                @foreach ($dojo_list as $k => $v)
                                <option value="{{ $v }}" @if($v==$new_request->ship_to) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('ship_to')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="invoice to" class="col-form-label col-sm-4">Invoice To</label>
                        <div class="col-sm-5">
                            <select id="invoice_to" class="form-select @error('invoice_to') is-invalid @enderror" name="invoice_to" autofocus>
                                <option value="">None</option>
                                @foreach ($dojo_list as $k => $v)
                                <option value="{{ $v }}" @if($v==$new_request->invoice_to) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('invoice_to')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-form-label col-sm-4">Status</label>
                        <div class="col-sm-5">
                            <select id="status" class="form-select @error('status') is-invalid @enderror" name="status" required autofocus>
                                <option value="pending" @if($new_request->status == 'pending') selected @endif>Pending</option>
                                <option value="processed" @if($new_request->status == 'processed') selected @endif>Processed</option>
                            </select>

                            @error('Status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 py-2 px-4 cta text-uppercase">Update Card Request</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection