@extends('layouts.app')

@section('title', 'Page Title')

@section('content')

<div class="ki-dashboard ki-box-admin">
    <div class="container-xxl px-xxl-0">
        <div class="row">
            <div class="col-md-8">
                <h2 class="m-0 mb-0 title">Edit Member Info</h2>
                <p>Update the member info</p>
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('memberships.update', $membershipCard->id) }}" class="form-ki">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-3">
                        <label for="first name" class="col-form-label col-sm-4">First Name</label>
                        <div class="col-sm-5">
                            <input id="firstname" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $membershipCard->first_name }}" required autocomplete="first_name" autofocus>

                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="last name" class="col-form-label col-sm-4">Last Name</label>
                        <div class="col-sm-5">
                            <input id="lastname" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $membershipCard->last_name }}" required autocomplete="last_name" autofocus>

                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="membership date" class="col-form-label col-sm-4">Membership Date</label>
                        <div class="col-sm-5">
                            <input id="membership_date" type="date" class="form-control @error('membership_date') is-invalid @enderror" name="membership_date" value="{{ date('Y-m-d', strtotime($membershipCard->membership_date)) }}" required autocomplete="membership_date" autofocus>

                            @error('membership_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dojo" class="col-form-label col-sm-4">Dojo</label>
                        <div class="col-sm-5">
                            <select id="dojo" class="form-select @error('dojo') is-invalid @enderror" name="dojo" value="{{ old('dojo') }}" required autofocus>
                                @foreach ($dojo_list as $k => $v)
                                <option value="{{ $k }}" @if($k==$membershipCard->dojo_id) selected @endif>{{ $v }}</option>
                                @endforeach
                            </select>

                            @error('dojo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="parent_id" class="col-form-label col-sm-4">Parent Organization</label>
                        <div class="col-sm-5">
                            <select id="parent_id" class="form-select @error('organization_id') is-invalid @enderror" name="organization_id" required autofocus>
                                <option value="0">None</option>
                                @foreach ($org_list as $k => $v)
                                <option value="{{ $k }}" @if ($k==$membershipCard->organization_id) selected @endif>{{ $v }}</option>
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
                        <label for="sub_organization_id" class="col-form-label col-sm-4">Sub Organization</label>
                        <div class="col-sm-5">
                            <select id="sub_organization_id" class="form-select @error('sub_organization_id') is-invalid @enderror" name="sub_organization_id" value="{{ old('sub_organization_id') }}" autofocus>
                                @foreach ($sub_org_list as $k => $v)
                                <option value="{{ $k }}" @if($k==$membershipCard->sub_organization_id) selected @endif>{{ $v }}</option>
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
                        <label for="old member id" class="col-form-label col-sm-4">Old Member ID</label>
                        <div class="col-sm-5">
                            <input id="old_member_id" type="text" class="form-control @error('old_member_id') is-invalid @enderror" name="old_member_id" value="{{ $membershipCard->member_id }}" required autocomplete="old_member_id" autofocus>

                            @error('old_member_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="new member id" class="col-form-label col-sm-4">New Member ID</label>
                        <div class="col-sm-5">
                            <input id="new_member_id" type="text" class="form-control @error('new_member_id') is-invalid @enderror" name="new_member_id" value="{{ $membershipCard->id }}" required autocomplete="new_member_id" autofocus disabled>

                            @error('new_member_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="rank" class="col-form-label col-sm-4">Aikido Rank</label>
                        <div class="col-sm-5">
                            <select id="rank" class="form-select @error('rank') is-invalid @enderror" name="rank" required autofocus>
                                <option value="">None</option>
                                <option value="10 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="10 Kyu" ) selected @endif>10 Kyu</option>
                                <option value="9 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="9 Kyu" ) selected @endif>9 Kyu</option>
                                <option value="8 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="8 Kyu" ) selected @endif>8 Kyu</option>
                                <option value="7 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="7 Kyu" ) selected @endif>7 Kyu</option>
                                <option value="6 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="6 Kyu" ) selected @endif>6 Kyu</option>
                                <option value="5 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="5 Kyu" ) selected @endif>5 Kyu</option>
                                <option value="4 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="4 Kyu" ) selected @endif>4 Kyu</option>
                                <option value="3 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="3 Kyu" ) selected @endif>3 Kyu</option>
                                <option value="2 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="2 Kyu" ) selected @endif>2 Kyu</option>
                                <option value="1 Kyu" @if(ucwords(strtolower($membershipCard->rank))=="1 Kyu" ) selected @endif>1 Kyu</option>
                                <option value="Shodan" @if(ucwords(strtolower($membershipCard->rank)) == "Shodan") selected @endif>Shodan</option>
                                <option value="Nidan" @if(ucwords(strtolower($membershipCard->rank)) == "Nidan") selected @endif>Nidan</option>
                                <option value="Sandan" @if(ucwords(strtolower($membershipCard->rank)) == "Sandan") selected @endif>Sandan</option>
                                <option value="Yondan" @if(ucwords(strtolower($membershipCard->rank)) == "Yondan") selected @endif>Yondan</option>
                                <option value="Godan" @if(ucwords(strtolower($membershipCard->rank)) == "Godan") selected @endif>Godan</option>
                                <option value="Rokudan" @if(ucwords(strtolower($membershipCard->rank)) == "Rokudan") selected @endif>Rokudan</option>
                                <option value="Nanadan" @if(ucwords(strtolower($membershipCard->rank)) == "Nanadan") selected @endif>Nanadan</option>
                                <option value="Hachidan" @if(ucwords(strtolower($membershipCard->rank)) == "Hachidan") selected @endif>Hachidan</option>
                                <option value="Kudan" @if(ucwords(strtolower($membershipCard->rank)) == "Kudan") selected @endif>Kudan</option>
                                <option value="Judan" @if(ucwords(strtolower($membershipCard->rank)) == "Judan") selected @endif>Judan</option>

                            </select>
                            @error('rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ki_rank" class="col-form-label col-sm-4">Ki Rank</label>
                        <div class="col-sm-5">
                            <select id="ki_rank" class="form-select @error('ki_rank') is-invalid @enderror" name="ki_rank" required autofocus>
                                <option value="">None</option>
                                <option value="shokyu" @if(ucwords(strtolower($membershipCard->ki_rank))=="Shokyu" ) selected @endif>Shokyu</option>
                                <option value="chukyu" @if(ucwords(strtolower($membershipCard->ki_rank))=="Chukyu" ) selected @endif>Chukyu</option>
                                <option value="jokyu" @if(ucwords(strtolower($membershipCard->ki_rank))=="Jokyu" ) selected @endif>Jokyu</option>
                                <option value="shoden" @if(ucwords(strtolower($membershipCard->ki_rank))=="Shoden" ) selected @endif>Shoden</option>
                                <option value="chuden" @if(ucwords(strtolower($membershipCard->ki_rank))=="Chuden" ) selected @endif>Chuden</option>
                                <option value="joden" @if(ucwords(strtolower($membershipCard->ki_rank))=="Joden" ) selected @endif>Joden</option>
                                <option value="Kaiden" @if(ucwords(strtolower($membershipCard->ki_rank))=="Kaiden" ) selected @endif>Kaiden</option>
                                <option value="okuden" @if(ucwords(strtolower($membershipCard->ki_rank))=="Okuden" ) selected @endif>Okuden</option>

                            </select>
                            @error('ki_rank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="card type" class="col-form-label col-sm-4">Card Type</label>
                        <div class="col-sm-5">
                            <select id="card-type" class="form-select @error('card_type') is-invalid @enderror" name="card_type" value="{{ old('card_type') }}" required autofocus>
                                <option value="New" @if(ucwords(strtolower($membershipCard->card_type)) == 'New') selected @endif>New</option>
                                <option value="Replacement" @if(ucwords(strtolower($membershipCard->card_type)) == 'Replacement') selected @endif>Replacement</option>
                            </select>

                            @error('card_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="program" class="col-form-label col-sm-4">Program</label>
                        <div class="col-sm-5">
                            <select id="program" class="form-select @error('new_program') is-invalid @enderror" name="program" required autofocus>
                                <option value="">None</option>
                                <option value="adult aikido" @if(ucwords(strtolower($membershipCard->program)) == "Adult Aikido") selected @endif>Adult Aikido</option>
                                <option value="children's aikido" @if(ucwords(strtolower($membershipCard->program)) == "Children's Aikido") selected @endif>Children's Aikido</option>
                                <option value="teen aikido" @if(ucwords(strtolower($membershipCard->program)) == "Teen Aikido") selected @endif>Teen Aikido</option>
                                <option value="ki development" @if(ucwords(strtolower($membershipCard->program)) == "Ki Development") selected @endif>Ki Development</option>
                                <option value="kiatsu" @if(ucwords(strtolower($membershipCard->program)) == "Kiatsu") selected @endif>Kiatsu</option>
                            </select>
                            @error('program')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="url" value=@if(URL::previous()==URL::current())" /memberships " @else " {{ URL::previous() }}" @endif>
                    <div class="mt-4"><a href="{{ route('membership.userDetail', $membershipCard->id) }}" style="color: #1B7400; font-size: 17px;">Click to see Public Profile</a></div>
                    <div class="cta-row d-flex mt-4">
                        <button type="submit" class="border-0 py-2 px-4 cta text-uppercase">Update Member Info</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection