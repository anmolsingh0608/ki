@extends('layouts.guest')

@section('content')

<section class="wrap">
    <form class="form-ki orders" method="POST" action="{{ route('order_cards') }}" enctype="multipart/form-data">
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
                        <script>
                            setTimeout(function(){
                                $('.tab-large-order').click();
                            }, 50);
                        </script>
                        @endif
                        <h2 class="mb-5 text-uppercase">ORDER MEMBERSHIP CARDS FOR YOUR ORGANIZATION</h2>
                        <!-- <form class="form-ki membership" method="POST" action="{{ route('order_cards') }}"> -->
                        <div class="row mb-3">
                            <label for="country" class="col-form-label col-sm-4 text-uppercase">Order Name:</label>
                            <div class="col-sm-5">
                                <input id="order_name" type="text" class="form-control @error('order_name') is-invalid @enderror" name="order_name" value="@if(session()->has('membership')){{ Session::get('membership')['order_name'] }}@endif" required autocomplete="order_name" autofocus="">

                                @error('order_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
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
                <ul class="nav">
                    <li class="active tab-small-order" data-tab="so">SMALL ORDERS</li>
                    <li class="tab-large-order" data-tab="lbo">LARGE BATCH ORDERS</li>
                </ul>
            </div>
            @if(!empty($edit_data))
            <div class="tab-content small-order update-card-form">
                <div class="box" data-tab="so">
                    <div class="breadlinks mb-4">
                        <ul class="nav text-uppercase">
                            <li><a href="/" class="show-list">CARD LIST</a></li>
                            <li>&gt;</li>
                            <li><a href="javascript:void(0)">UPDATE CARD</a></li>
                        </ul>
                    </div>
                    <h2>UPDATE YOUR MEMBERSHIP CARD</h2>
                    <p>Use the fields below to update member information, then click “UPDATE CARD” to update to the order.</p>
                    <p>NOTE: All fields are required.</p>
                    <!-- <form class="form-ki mt-5 orders" method="POST" action="{{ route('order_cards') }}"> -->
                    <input type="hidden" name="update_data" value="{{ $s_id }}">
                    @foreach( session()->get('data') as $key => $data )
                    @if($key == $edit_data)
                    $edit_data = $data[$edit_data]
                    @endif
                    @endforeach
                    <div class="row mb-3">
                        <label for="fname" class="col-form-label col-sm-4 col-xl-3 text-uppercase">FIRST NAME</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="first_name" type="text" class="form-control @error('new_first_name') is-invalid @enderror" name="new_first_name" value="{{ $edit_data['first_name'] }}" required autocomplete="first_name" autofocus>

                            @error('new_first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lname" class="col-form-label col-sm-4 col-xl-3 text-uppercase">LAST NAME</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="last_name" type="text" class="form-control @error('new_last_name') is-invalid @enderror" name="new_last_name" value="{{ $edit_data['last_name'] }}" required autocomplete="last_name" autofocus>

                            @error('new_last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mdate" class="col-form-label col-sm-4 col-xl-3 text-uppercase">MEMBERSHIP DATE</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="membership_date" type="date" class="form-control @error('new_membership_date') is-invalid @enderror" name="new_membership_date" value="{{ $edit_data['membership_date'] }}" required autocomplete="membership_date" autofocus>

                            @error('new_membership_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="organization_id" class="col-form-label col-sm-4 col-xl-3 text-uppercase">ORGANIZATION:</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="new_organization_id_card" class="form-select small-order-input @error('organization_id') is-invalid @enderror" name="new_organization_id_card" required autofocus>
                                <option value="">None</option>
                                @foreach ($org_list as $k => $v)
                                <option value="{{ $k }}" @if($edit_data['organization_id_card'] == $k) selected="selected" @endif>{{ $v }}</option>
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
                        <label for="suborg" class="col-form-label col-sm-4 col-xl-3 text-uppercases">SUB ORG:</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="new_sub_organization_id_card" class="form-select small-order-input @error('sub_organization_id') is-invalid @enderror" name="new_sub_organization_id_card" autofocus>
                                <option value="">None</option>
                                @foreach ($sub_org_list as $k => $v)
                                <option value="{{ $k }}" @if($edit_data['sub_organization_id_card'] == $k) selected="selected" @endif>{{ $v }}</option>
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
                        <label for="homedojo" class="col-form-label col-sm-4 col-xl-3 text-uppercase">HOME DOJO</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="dojo_id" class="form-select @error('new_dojo_id') is-invalid @enderror" name="new_dojo_id" required autofocus>
                                <option value="">None</option>
                                @foreach ($dojo_list as $k => $v)
                                <option value="{{ $k }}" @if($k==$edit_data['dojo_id']) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('new_dojo_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="prgram" class="col-form-label col-sm-4 col-xl-3 text-uppercase">PROGRAM</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="program" class="form-select @error('new_program') is-invalid @enderror" name="new_program" required autofocus>
                                <option value="">None</option>
                                <option value="adult aikido" @if($edit_data['program'] == "adult aikido") selected @endif>Adult Aikido</option>
                                <option value="children's aikido" @if($edit_data['program'] == "children's aikido") selected @endif>Children's Aikido</option>
                                <option value="teen aikido" @if($edit_data['program'] == "teen aikido") selected @endif>Teen Aikido</option>
                                <option value="ki development" @if($edit_data['program'] == "ki development") selected @endif>Ki Development</option>
                                <option value="kiatsu" @if($edit_data['program'] == "kiatsu") selected @endif>Kiatsu</option>
                            </select>

                            @error('new_program')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mid" class="col-form-label col-sm-4 col-xl-3 text-uppercase">OLD MEMBER ID</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="member_id" type="text" class="form-control @error('new_member_id') is-invalid @enderror" name="new_member_id" value="{{ $edit_data['member_id'] }}" required autocomplete="member_id" autofocus>

                            @error('new_member_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="rank" class="col-form-label col-sm-4 col-xl-3 text-uppercase">AIKIDO RANK</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="rank" class="form-select @error('new_rank') is-invalid @enderror" name="new_rank" required autofocus>
                                <option value="">None</option>
                                <option value="10 Kyu" @if($edit_data['rank'] == "10 Kyu") selected @endif>10 Kyu</option>
                                <option value="9 Kyu" @if($edit_data['rank'] == "9 Kyu") selected @endif>9 Kyu</option>
                                <option value="8 Kyu" @if($edit_data['rank'] == "8 Kyu") selected @endif>8 Kyu</option>
                                <option value="7 Kyu" @if($edit_data['rank'] == "7 Kyu") selected @endif>7 Kyu</option>
                                <option value="6 Kyu" @if($edit_data['rank'] == "6 Kyu") selected @endif>6 Kyu</option>
                                <option value="5 Kyu" @if($edit_data['rank'] == "5 Kyu") selected @endif>5 Kyu</option>
                                <option value="4 Kyu" @if($edit_data['rank'] == "4 Kyu") selected @endif>4 Kyu</option>
                                <option value="3 Kyu" @if($edit_data['rank'] == "3 Kyu") selected @endif>3 Kyu</option>
                                <option value="2 Kyu" @if($edit_data['rank'] == "2 Kyu") selected @endif>2 Kyu</option>
                                <option value="1 Kyu" @if($edit_data['rank'] == "1 Kyu") selected @endif>1 Kyu</option>
                                <option value="Shodan" @if($edit_data['rank'] == "Shodan") selected @endif>Shodan</option>
                                <option value="Nidan" @if($edit_data['rank'] == "Nidan") selected @endif>Nidan</option>
                                <option value="Sandan" @if($edit_data['rank'] == "Sandan") selected @endif>Sandan</option>
                                <option value="Yondan" @if($edit_data['rank'] == "Yondan") selected @endif>Yondan</option>
                                <option value="Godan" @if($edit_data['rank'] == "Godan") selected @endif>Godan</option>
                                <option value="Rokudan" @if($edit_data['rank'] == "Rokudan") selected @endif>Rokudan</option>
                                <option value="Nanadan" @if($edit_data['rank'] == "Nanadan") selected @endif>Nanadan</option>
                                <option value="Hachidan" @if($edit_data['rank'] == "Hachidan") selected @endif>Hachidan</option>
                                <option value="Kudan" @if($edit_data['rank'] == "Kudan") selected @endif>Kudan</option>
                                <option value="Judan" @if($edit_data['rank'] == "Judan") selected @endif>Judan</option>
                            </select>

                            @error('new_rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ki_rank" class="col-form-label col-sm-4 col-xl-3 text-uppercase">KI RANK</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="ki_rank" class="form-select small-order-input @error('ki_rank') is-invalid @enderror" name="ki_rank" required autofocus>
                                <option value="">None</option>
                                <option value="shokyu" @if($edit_data['ki_rank'] == "shokyu") selected @endif>Shokyu</option>
                                <option value="chukyu" @if($edit_data['ki_rank'] == "chukyu") selected @endif>Chukyu</option>
                                <option value="jokyu" @if($edit_data['ki_rank'] == "jokyu") selected @endif>Jokyu</option>
                                <option value="shoden" @if($edit_data['ki_rank'] == "shoden") selected @endif>Shoden</option>
                                <option value="chuden" @if($edit_data['ki_rank'] == "chuden") selected @endif>Chuden</option>
                                <option value="joden" @if($edit_data['ki_rank'] == "joden") selected @endif>Joden</option>
                                <option value="Kaiden" @if($edit_data['ki_rank'] == "Kaiden") selected @endif>Kaiden</option>
                                <option value="okuden" @if($edit_data['ki_rank'] == "okuden") selected @endif>Okuden</option>
                            </select>

                            @error('ki_rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    @if($edit_data['card_type']=='replacement' )
                    <input class="" type="hidden" name="new_card_type" value="replacement">
                    @else
                    <input class="" type="hidden" name="new_card_type" value="new">
                    @endif
                    <!-- <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-4 col-xl-3 text-uppercase pt-0">CARD TYPE</legend>
                        <div class="col-sm-5 col-xl-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="new_card_type" value="new" @if($edit_data['card_type']=='new' ) checked="" @endif>
                                <label class="form-check-label" for="new">NEW CARD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="new_card_type" value="replacement" @if($edit_data['card_type']=='replacement' ) checked="" @endif>
                                <label class="form-check-label" for="REPLACEMENT">REPLACEMENT CARD (Existing Member)</label>
                            </div>

                            @error('new_card_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </fieldset> -->
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 cta text-uppercase">UPDATE CARD</button>
                    </div>

                </div>
            </div>
            @else
            <div class="tab-content small-order card-list">
                <div class="box" data-tab="so">
                    <h2>ORDER INDIVIDUAL OR REPLACEMENT CARDS</h2>
                    <p>Use this tool to order small amounts of cards for new members or to replace lost cards.</p>
                    <p>Click “Add Card” to add a membership card to your order.<br> When you are ready, click ORDER CARDS. You will receive a confirmation email.</p>
                </div>
                <div class="add-card mt-5">
                    <a href="javascript:void(0)" class="cta add-card">Add Card</a>
                </div>
                <div class="table-wrap">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="heading">
                            <th width="21%">MEMBER</th>
                            <th width="25%">CARD TYPE</th>
                            <th width="37%">HOME DOJO</th>
                            <th width="17%">&nbsp;</th>
                        </tr>
                        @if( session()->has('data') )
                        @foreach( session()->get('data') as $key => $data )
                        <tr>
                            <td>{{ $data['first_name'] }} {{ $data['last_name'] }}</td>
                            <td>{{ strtoupper($data['card_type']) }}</td>
                            <td>{{ $dojo_list[$data['dojo_id']] }}</td>
                            <td class="cta-table">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $key }}" class="card-list-item-edit cta d-flex justify-content-center">Edit</a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $key }}" class="card-list-item-delte cta d-flex justify-content-center red">Delete</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </table>
                    @if( session()->has('data') && !empty(session()->get('data')))
                    <div class="mt-5 order-card text-center">
                        <a href="javascript:void(0)" id="order-button" class="cta d-inline-flex">ORDER CARDS</a>
                    </div>
                    @elseif( session()->has('order') )
                    <div class="confirmation my-5 text-center">
                        <h2 class="green">SUCCESS!</h2>
                        <p>We have received your order for {{ count(session()->get('order')) }} membership cards.<br>Please check your email for a confirmation.</p>
                        <p>
                            @foreach( session()->get('order') as $key => $data )
                            {{ $data['first_name'] }} {{ $data['last_name'] }} - {{ $data['card_type'] }}<br>
                            @endforeach
                        </p>
                    </div>
                    @else
                    <div class="add-card-note text-center text-uppercase mt-5 py-5">
                        <h2>To begin your order, click “ADD CARD”</h2>
                    </div>
                    @endif
                </div>
            </div>
            <div class="tab-content add-card-form hidden">
                <div class="box" data-tab="so">
                    <div class="breadlinks mb-4">
                        <ul class="nav text-uppercase">
                            <li><a href="/" class="show-list">CARD LIST</a></li>
                            <li>&gt;</li>
                            <li><a href="javascript:void(0)">ADD CARD</a></li>
                        </ul>
                    </div>
                    <h2>ADD A MEMBERSHIP CARD TO YOUR ORDER</h2>
                    <p>Use the fields below to add member information, then click “ADD MEMBER” to add to the order.</p>
                    <p>NOTE: All fields are required.</p>
                    <!-- <form class="form-ki mt-5 orders" method="POST" action="{{ route('order_cards') }}"> -->
                    <input type="hidden" name="save_data" value="">
                    <div class="row mb-3">
                        <label for="fname" class="col-form-label col-sm-4 col-xl-3 text-uppercase">FIRST NAME</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="first_name" type="text" class="form-control small-order-input @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lname" class="col-form-label col-sm-4 col-xl-3 text-uppercase">LAST NAME</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="last_name" type="text" class="form-control small-order-input @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mdate" class="col-form-label col-sm-4 col-xl-3 text-uppercase">MEMBERSHIP DATE</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="membership_date" type="date" class="form-control small-order-input @error('membership_date') is-invalid @enderror" name="membership_date" value="{{ old('membership_date') }}" required autocomplete="membership_date" autofocus>

                            @error('membership_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="organization_id" class="col-form-label col-sm-4 col-xl-3 text-uppercase">ORGANIZATION:</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="organization_id_card" class="form-select small-order-input @error('organization_id') is-invalid @enderror" name="organization_id_card" required autofocus>
                                <option value="">None</option>
                                @foreach ($org_list as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
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
                        <label for="suborg" class="col-form-label col-sm-4 col-xl-3 text-uppercases">SUB ORG:</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="sub_organization_id_card" class="form-select small-order-input @error('sub_organization_id') is-invalid @enderror" name="sub_organization_id_card" autofocus>
                                <option value="">None</option>
                                @foreach ($sub_org_list as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
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
                        <label for="homedojo" class="col-form-label col-sm-4 col-xl-3 text-uppercase">HOME DOJO</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="dojo_id" class="form-select small-order-input @error('dojo_id') is-invalid @enderror" name="dojo_id" required autofocus>
                                <option value="">None</option>
                                @foreach ($dojo_list as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('dojo_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="prgram" class="col-form-label col-sm-4 col-xl-3 text-uppercase">PROGRAM</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="program" class="form-select small-order-input @error('program') is-invalid @enderror" name="program" required autofocus>
                                <option value="">None</option>
                                <option value="adult aikido">Adult Aikido</option>
                                <option value="children's aikido">Children's Aikido</option>
                                <option value="teen aikido">Teen Aikido</option>
                                <option value="ki development">Ki Development</option>
                                <option value="kiatsu">Kiatsu</option>
                            </select>

                            @error('program')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mid" class="col-form-label col-sm-4 col-xl-3 text-uppercase">OLD MEMBER ID</label>
                        <div class="col-sm-5 col-xl-4">
                            <input id="member_id" type="text" class="form-control small-order-input @error('member_id') is-invalid @enderror" name="member_id" value="{{ old('member_id') }}" required autocomplete="member_id" autofocus>

                            @error('member_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="rank" class="col-form-label col-sm-4 col-xl-3 text-uppercase">AIKIDO RANK</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="rank" class="form-select small-order-input @error('rank') is-invalid @enderror" name="rank" required autofocus>
                                <option value="">None</option>
                                <option value="10 Kyu">10 Kyu</option>
                                <option value="9 Kyu">9 Kyu</option>
                                <option value="8 Kyu">8 Kyu</option>
                                <option value="7 Kyu">7 Kyu</option>
                                <option value="6 Kyu">6 Kyu</option>
                                <option value="5 Kyu">5 Kyu</option>
                                <option value="4 Kyu">4 Kyu</option>
                                <option value="3 Kyu">3 Kyu</option>
                                <option value="2 Kyu">2 Kyu</option>
                                <option value="1 Kyu">1 Kyu</option>
                                <option value="Shodan">Shodan</option>
                                <option value="Nidan">Nidan</option>
                                <option value="Sandan">Sandan</option>
                                <option value="Yondan">Yondan</option>
                                <option value="Godan">Godan</option>
                                <option value="Rokudan">Rokudan</option>
                                <option value="Nanadan">Nanadan</option>
                                <option value="Hachidan">Hachidan</option>
                                <option value="Kudan">Kudan</option>
                                <option value="Judan">Judan</option>
                            </select>

                            @error('rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ki_rank" class="col-form-label col-sm-4 col-xl-3 text-uppercase">KI RANK</label>
                        <div class="col-sm-5 col-xl-4">
                            <select id="ki_rank" class="form-select small-order-input @error('ki_rank') is-invalid @enderror" name="ki_rank" required autofocus>
                                <option value="">None</option>
                                <option value="shokyu">Shokyu</option>
                                <option value="chukyu">Chukyu</option>
                                <option value="jokyu">Jokyu</option>
                                <option value="shoden">Shoden</option>
                                <option value="chuden">Chuden</option>
                                <option value="joden">Joden</option>
                                <option value="Kaiden">Kaiden</option>
                                <option value="okuden">Okuden</option>
                            </select>

                            @error('ki_rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="card_type" value="new" />
                    <!-- <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-4 col-xl-3 text-uppercase pt-0">CARD TYPE</legend>
                        <div class="col-sm-5 col-xl-6">
                            <div class="form-check">
                                <input class="form-check-input small-order-input" type="radio" name="card_type" value="new" checked="">
                                <label class="form-check-label" for="new">NEW CARD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input small-order-input" type="radio" name="card_type" value="replacement">
                                <label class="form-check-label" for="REPLACEMENT">REPLACEMENT CARD (Existing Member)</label>
                            </div>

                            @error('card_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </fieldset> -->
                    <div class="cta-row d-flex mt-5">
                        <button type="reset" class="border-0 cta me-md-3 red text-uppercase">CANCEL</button>
                        <button type="submit" class="border-0 cta text-uppercase" onclick="submitForms()">ADD CARD</button>
                    </div>

                </div>
            </div>
            @endif
            <div class="tab-content large-order hidden">
                <div class="box" data-tab="lbo">
                    <h2>ORDER A LARGE CARD BATCH FOR AN ENTIRE DOJO OR ORGANIZATION</h2>
                    <p>To order large amounts of cards for your entire organization or dojo, please use this form to submit a complete list of all members in CSV file format.</p>
                    <p><strong>NOTE:</strong> Use this downloadable template to format your member information correctly: <a href="/large-order.csv" class="cta d-md-inline-flex py-2 px-4">DOWNLOAD .CSV TEMPLATE</a></p>
                    @if(empty($lOrder))
                    <p><strong>READY TO UPLOAD & PLACE YOUR ORDER?</strong></p>
                    <ul class="list-unstyled mb-5 list-point">
                        <li>STEP 1) After downloading the provided template, fill out the required information completely.</li>
                        <li>STEP 2) Use the tool below to browse your computer to locate the CSV file.</li>
                        <li>STEP 3) Click “UPLOAD CSV” to attach the file</li>
                        <li>STEP 4) Click “ORDER CARDS” when you are ready to place your order.</li>
                        <li>STEP 5) Check your email for a confirmation of successful upload.</li>
                    </ul>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control lOrder lh-lg" name="large_order_csv" id="upcsv">
                        <label class="input-group-text cta text-uppercase border-0 @error('large_order_csv') is-invalid @enderror" for="upcsv">Upload csv</label>
                        @error('large_order_csv')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="cta-row d-flex mt-5">
                        <button type="submit" class="border-0 cta text-uppercase">ORDER CARDS</button>
                    </div>
                    @else
                    <div class="confirmation confirmation-large-order my-5 text-center">
                        <h2 class="green">SUCCESS!</h2>
                        <p>We have received your CSV order form.<br> Please check your email for a confirmation. <br> </p>
                        <p>{{ $lOrder }}</p>
                    </div>
                    <div class="mt-5 close-card text-center">
                        <a href="/" class="cta d-inline-flex">CLOSE</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </form>
    <form style="display: none;" action="{{ route('orders.store') }}" method="POST" class="final-order">
        @csrf
        <button type="submit"></button>
    </form>
    <form style="display: none;" action="{{ route('order_cards') }}" method="POST" class="card-list-delete">
        @csrf
        @method('DELETE')
        <input type="hidden" value="" name="delete_card_list_id" id="card-list-item-id">
        <button type="submit"></button>
    </form>
    <form style="display: none;" action="{{ route('order_cards') }}" method="POST" class="card-list-edit">
        @csrf
        <input type="hidden" value="" name="edit_card_list_id" id="edit-list-item-id">
        <button type="submit"></button>
    </form>
</section>


@endsection