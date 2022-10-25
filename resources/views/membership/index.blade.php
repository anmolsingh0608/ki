@extends('layouts.app')

@section('title', 'Memberships')

@section('content')


<div class="ki-dashboard">
    <div class="container-xxl px-xxl-0">
        <div class="align-items-center d-flex mb-2 row">
            <div class="align-items-center col-md-6 d-flex">
                <h2 class="m-0 mb-0 title">Membership Cards</h2>
            </div>
            <div class="col-md-6">
                <form class="align-items-center d-flex form-ki justify-content-end" action="{{ route('memberships.index') }}" method="GET">
                    <input class="form-control h-100 me-2" type="text" name="search" />
                    <button type="submit" class="btn cta px-4 py-2 w" style="white-space: nowrap;">Search</button>
                </form>
            </div>
        </div>
        <p>All ({{count($membershipCard)}})</p>
        <p>Newly added membership card requests. To process, select members and export a CSV for card printing.</p>
        <form class="checklist-form">
            <div class="row mb-3">
                <div class="col-sm-3 pe-md-0 bAction">

                    <button type="button" class="btn btn-light dropdown-bulk-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bulk Action
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="float: right; margin-top:3px">
                            <path fill-rule="evenodd" d="M12.78 6.22a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0L3.22 7.28a.75.75 0 011.06-1.06L8 9.94l3.72-3.72a.75.75 0 011.06 0z"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-1">
                        <a id="bulk-delete" class="dropdown-item" data-uri="{{ route('memberships.deleteall') }}">Delete Selected</a>
                        <a id="bulk-csv" class="dropdown-item" data-uri="{{ route('memberships.exportCSV') }}">Export Full Data CSV</a>
                        <!-- <a id="bulk-csv-Bodno" class="dropdown-item" data-uri="{{ route('memberships.exportCSVBodno') }}">Export Bodno CSV</a> -->
                        <a id="bulk-csv-K12" class="dropdown-item" data-uri="{{ route('memberships.exportCSVK12') }}">Export CSV For Printing Cards</a>
                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <button class="cta border-0 py-2 px-4">Apply</button>
                </div> -->
            </div>

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
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td scope="col"><input type="checkbox" id="selectall" /> @sortablelink('first_name', 'Name')</td>
                        <td scope="col">@sortablelink('membership_date', 'Membership Date')</td>
                        <td scope="col">@sortablelink('dojo.name', 'Dojo')</td>
                        <td scope="col">@sortablelink('organization.name', 'Organization')</td>
                        <td scope="col">@sortablelink('organization.name', 'Sub Organization')</td>
                        <td scope="col">@sortablelink('member_id', 'Old Member ID')</td>
                        <td scope="col">@sortablelink('id', 'New Member ID')</td>
                        <td scope="col">@sortablelink('rank', 'Aikido Rank')</td>
                        <td scope="col">@sortablelink('program', 'Program')</td>
                        <!-- <td scope="col">Actions</td> -->
                    </tr>
                    @foreach ($membershipCard as $membership)
                    <tr>
                        <td scope="row"><input type="checkbox" class="singlechkbox" data-id="{{$membership->id}}" /> <a href="{{ route('memberships.edit',$membership->id) }}" class="anchor">{{ $membership->first_name }} {{$membership->last_name}}</a></td>
                        <td scope="row">{{ date_format(new DateTime($membership->membership_date),'d M Y') }}</td>
                        <td scope="roe">@if ($membership->dojo) <a href="{{ route('dojos.edit',$membership->dojo->id) }}" class="anchor">{{ $membership->dojo->name }}</a> @endif</td>
                        <td scope="row"><a href="{{ route('organizations.edit',$membership->organization->id) }}" class="anchor">{{ $membership->organization->name }}</a></td>
                        <td scope="row">@if ($membership->sub_organization) <a href="{{ route('organizations.edit',$membership->sub_organization->id) }}" class="anchor">{{ $membership->sub_organization->name }}</a> @else None @endif</td>
                        <td scope="row">{{ $membership->member_id }}</td>
                        <td scope="row">{{ $membership->id }}</td>
                        <td scope="row">{{ $membership->rank }}</td>
                        <td scope="row">{{ ucwords($membership->program) }}</td>
                        <!-- <td scope="row" class="d-flex">

                            <a href="{{ route('memberships.edit',$membership->id) }}" class="btn" style="padding: 0 .50rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>
                            </a>
                            <form></form>
                            <form action="{{ route('memberships.destroy', $membership->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn" type="submit" onclick="return confirm('Do you want to delete this membership?')" style="padding: 0 .50rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg>
                                </button>
                            </form>
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row mb-3">
                <div class="col-sm-3 pe-md-0 bAction-2">

                    <button type="button" class="btn btn-light dropdown-bulk-action-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bulk Action
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="float: right; margin-top:3px">
                            <path fill-rule="evenodd" d="M12.78 6.22a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0L3.22 7.28a.75.75 0 011.06-1.06L8 9.94l3.72-3.72a.75.75 0 011.06 0z"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-2">
                        <a id="bulk-delete-2" class="dropdown-item" data-uri="{{ route('memberships.deleteall') }}">Delete Selected</a>
                        <a id="bulk-csv-2" class="dropdown-item" data-uri="{{ route('memberships.exportCSV') }}">Export Full Data CSV</a>
                        <!-- <a id="bulk-csv-Bodno-2" class="dropdown-item" data-uri="{{ route('newrequest.exportCSVBodno') }}">Export Bodno CSV</a> -->
                        <a id="bulk-csv-K12-2" class="dropdown-item" data-uri="{{ route('memberships.exportCSVK12') }}">Export CSV For Printing Cards</a>
                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <button class="cta border-0 py-2 px-4">Apply</button>
                </div> -->
            </div>
        </form>
        {{ $membershipCard->links() }}
    </div>
</div>
<style>
    a {
        color: #212529;
        text-decoration: none;
    }
    .anchor {
        color: #0d6efd;
        text-decoration: underline;
    }
</style>
@endsection