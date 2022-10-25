@extends('layouts.guest')

@section('content')
<section class="wrap">
    <form class="form-ki orders" method="POST" action="{{ route('orders.existing') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="session_data" value="0">
        <div class="ki-box mb-4">
            <div class="container-xxl px-xxl-0">
                <div class="row">
                    <div class="col-md-8">
                        @if($errors->first('large_order_csv'))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{$errors->first('large_order_csv')}}</strong>
                        </div>
                        
                        @endif
                        <script>
                            setTimeout(function(){
                                alert('A member with this First and Last name already exists and has been issued a card. Please verify that this is a replacement card or a brand new member that needs a new card');
                            }, 50);
                        </script>
                        <h2 class="mb-5 text-uppercase">ORDER MEMBERSHIP CARDS FOR YOUR ORGANIZATION</h2>
                        <!-- <form class="form-ki membership" method="POST" action="{{ route('order_cards') }}"> -->
                        <div class="row mb-3">
                            <label for="country" class="col-form-label col-sm-4 text-uppercase">COUNTRY:</label>
                            <div class="col-sm-5">
                                <select id="country" class="form-select @error('country') is-invalid @enderror" name="country" required autofocus>
                                    @foreach ($countries as $k => $v)
                                    <option value="{{ $v }}" @if(session()->has('membership')) @if(Session::get('membership')["country"] == $v) selected @endif @endif>{{ $v }}</option>
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
                            <label for="organize" class="col-form-label col-sm-4 text-uppercase">ORGANIZATION:</label>
                            <div class="col-sm-5">
                                <select id="organization_id" class="form-select @error('organization_id') is-invalid @enderror" name="organization_id" required autofocus>
                                    <option value="">None</option>
                                    @foreach ($org_list as $k => $v)
                                    <option value="{{ $k }}" @if(session()->has('membership')) @if(Session::get('membership')["org"] == $k) selected @endif @endif>{{ $v }}</option>
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
                            <label for="suborg" class="col-form-label col-sm-4 text-uppercase">SUB ORG:</label>
                            <div class="col-sm-5">
                                <select id="sub_organization_id" class="form-select @error('sub_organization_id') is-invalid @enderror" name="sub_organization_id" autofocus>
                                    <option value="">None</option>
                                    @foreach ($sub_org_list as $k => $v)
                                    <option value="{{ $k }}" @if(session()->has('membership')) @if(Session::get('membership')["sub_org"] == $k) selected @endif @endif>{{ $v }}</option>
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
                            <label for="ship" class="col-form-label col-sm-4 text-uppercase">SHIP TO:</label>
                            <div class="col-sm-5">
                                <select id="ship_to" class="form-select @error('ship_to') is-invalid @enderror" name="ship_to" autofocus>
                                    <option value="">None</option>
                                    @foreach ($invoice_ship as $k => $v)
                                    <option value="{{ $v }}" @if(session()->has('membership')) @if(Session::get('membership')["ship_to"] == $v) selected @endif @endif>{{ $v }}</option>
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
                            <label for="invoice" class="col-form-label col-sm-4 text-uppercase">INVOICE:</label>
                            <div class="col-sm-5">
                                <select id="invoice_to" class="form-select @error('invoice_to') is-invalid @enderror" name="invoice_to" autofocus>
                                    <option value="">None</option>
                                    @foreach ($invoice_ship as $k => $v)
                                    <option value="{{ $v }}" @if(session()->has('membership')) @if(Session::get('membership')["invoice_to"] == $v) selected @endif @endif>{{ $v }}</option>
                                    @endforeach
                                </select>

                                @error('invoice_to')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <img src="html/images/card.png" alt="" class="w-100">
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-ki">
            <div class="tab-head">
                
            </div>
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td scope="col" width="4"></td>
                        <td scope="col">First Name</td>
                        <td scope="col">Last name</td>
                        <td scope="col">Member ID</td>
                        <td scope="col">Country</td>
                        <td scope="col">ORG</td>
                        <td scope="col">Dojo</td>
                        <td scope="col">Membership date</td>
                    </tr>
                    @foreach ($exist_membership as $e)
                    <tr>
                        <td scope="row" width="4"><input type="radio" class="singlechkbox" name="checkbox" value="{{$e->id}}" /></td>
                        <td scope="row">{{ $e->first_name }}</td>
                        <td scope="row">{{ $e->last_name }}</td>
                        <td scope="row">{{ $e->member_id }}</td>
                        <td scope="row">{{ $e->dojo->country }}</td>
                        <td scope="row">{{ $e->organization->name }}</td>
                        <td scope="row">@if ($e->dojo) {{ $e->dojo->name }} @endif</td>
                        <td scope="row">{{ date_format(new DateTime($e->membership_date),'d M Y') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td scope="row"><input type="radio" class="" value="none" name="checkbox" data-id="" required/></td>
                        <td scope="row">None of the above</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="submit" class="border-0 cta text-uppercase">CONFIRM</button>
    </form>
</section>
@endsection