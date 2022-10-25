@extends('layouts.app')

@section('content')

<div class="wrap d-flex w-100">
    <div class="ki-box-admin d-flex mb-4 w-100">
        <div class="col-md-8">
            <ul class="list-unstyled mb-0 user-profile-meta">
                <li class="d-flex align-items-center"><span class="name">First Name</span><span>{{ $membershipInfo->first_name }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Last Name</span><span>{{ $membershipInfo->last_name }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Membership Date</span><span>{{ date_format(new DateTime($membershipInfo->membership_date), 'm/d/Y') }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Home Dojo</span><span>@if($membershipInfo->dojo){{ $membershipInfo->dojo->name }}@endif</span></li>
                <li class="d-flex align-items-center"><span class="name">Organization </span><span>{{ $membershipInfo->organization->name }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Sub Organization</span><span>@if(isset($membershipInfo->sub_organization->name)){{ $membershipInfo->sub_organization->name }}@endif</span></li>
                <li class="d-flex align-items-center"><span class="name">Program</span><span>{{ ucwords($membershipInfo->program) }} </span></li>
                <li class="d-flex align-items-center"><span class="name">Old Member ID</span><span>{{ $membershipInfo->member_id }}</span></li>
                <li class="d-flex align-items-center"><span class="name">New Member ID</span><span>{{ $membershipInfo->id }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Aikido Rank</span><span>{{ ucwords($membershipInfo->rank) }}</span></li>
                <li class="d-flex align-items-center"><span class="name">Ki Rank</span><span>{{ ucwords($membershipInfo->ki_rank) }}</span></li>
            </ul>
        </div>
    </div>
</div>

@endsection