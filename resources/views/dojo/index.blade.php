@extends('layouts.app')

@section('title', 'Dojo')

@section('content')
<div class="ki-dashboard">
    <div class="container-xxl px-xxl-0">
        <div class="align-items-center d-flex mb-2 row">
            <div class="align-items-center col-md-6 d-flex">
                <h2 class="m-0 mb-0 title">Dojos </h2>
                <a href="/dojos/create"><button class="border-0 cta ms-3 px-4 py-2">Add New</button></a>
            </div>
            <div class="col-md-6">
                <form class="align-items-center d-flex form-ki justify-content-end" action="{{ route('dojos.index') }}" method="GET">
                    <input class="form-control h-100 me-2" type="text" name="search" />
                    <button type="submit" class="btn cta px-4 py-2 w" style="white-space: nowrap;">Search Dojos</button>
                </form>
            </div>
        </div>
        <p>All ({{count($dojos)}})</p>


        <div class="row mb-3">
            <div class="col-sm-3 pe-md-0 bAction">

                <button type="button" class="btn btn-light dropdown-bulk-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Action
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="float: right; margin-top:3px">
                        <path fill-rule="evenodd" d="M12.78 6.22a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0L3.22 7.28a.75.75 0 011.06-1.06L8 9.94l3.72-3.72a.75.75 0 011.06 0z"></path>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-1">
                    <a id="bulk-delete" class="dropdown-item" data-uri="{{ route('dojos.deleteall') }}">Delete Selected</a>
                </div>

                <!-- <select id="actions" class="form-select h-100 ">
                        <option selected="">Choose...</option>
                        <option>Bulk Actions</option>
                    </select> -->
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
                    <td scope="col"><input type="checkbox" id="selectall" />@sortablelink('name', 'Dojo Name')</td>
                    <td scope="col">@sortablelink('organization.name', 'Organization')</td>
                    <td scope="col">@sortablelink('organization.name', 'Sub Organization')</td>
                    <td scope="col">@sortablelink('country', 'Country')</td>
                    <td scope="col">@sortablelink('dojo_id', 'Dojo ID')</td>
                    <td width="10%" scope="col">Action</td>
                </tr>
                @foreach ($dojos as $dojo)
                <tr>
                    <td scope="row"><input type="checkbox" class="singlechkbox" data-id="{{$dojo->id}}" /> {{ $dojo->name }}</td>
                    <td scope="row"><a href="{{ route('organizations.edit', $dojo->organization->id) }}" class="anchor">{{ $dojo->organization->name }}</a></td>
                    <td scope="row">@if ($dojo->sub_organization) <a href="{{ route('organizations.edit', $dojo->sub_organization->id) }}" class="anchor">{{ $dojo->sub_organization->name }}</a> @else None @endif</td>
                    <td scope="row">{{ $dojo->country }}</td>
                    <td scope="row">{{ $dojo->dojo_id }}</td>
                    <td scope="row" class="d-flex">

                        <a href="{{ route('dojos.edit',$dojo->id) }}" class="btn" style="padding: 0 .50rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </a>
                        <form></form>
                        <form action="{{ route('dojos.destroy', $dojo->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn" type="submit" onclick="return confirm('Do you want to delete this dojo?')" style="padding: 0 .50rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                </svg>
                            </button>
                        </form>

                    </td>
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
                    <a id="bulk-delete-2" class="dropdown-item" data-uri="{{ route('dojos.deleteall') }}">Delete Selected</a>
                </div>
            </div>
            <!-- <div class="col-sm-3">
                <button class="cta border-0 py-2 px-4">Apply</button>
            </div> -->
        </div>
        {{ $dojos->links() }}
    </div>
</div>

<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <strong>{{ __('Dojos') }}</strong>
                        <div class="btn-group ml-4">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bulk Action
                            </button>
                            <div class="dropdown-menu">
                                <a id="bulk-delete" class="dropdown-item" data-uri="{{ route('dojos.deleteall') }}">Delete Selected</a>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <form class="form-inline" action="{{ route('dojos.index') }}" method="GET">
                            
                            <div class="form-group mx-sm-3 mb-2">
                                <input class="form-control" type="text" name="search" />
                            </div>
                            <button class="btn btn-primary mb-2" type="submit">Search</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectall" /></th>
                                <th scope="col">Dojo Name</th>
                                <th scope="col">Organization</th>
                                <th scope="col">Sub Organization</th>
                                <th scope="col">Country</th>
                                <th scope="col">Dojo ID</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dojos as $dojo)
                            <tr>
                                <td scope="row"><input type="checkbox" class="singlechkbox" data-id="{{$dojo->id}}" /></td>
                                <th scope="row">{{ $dojo->name }}</th>
                                <th scope="row">{{ $dojo->organization->name }}</th>
                                <td scope="row">@if ($dojo->sub_organization) {{ $dojo->sub_organization->name }} @else None @endif</td>
                                <th scope="row">{{ $dojo->country }}</th>
                                <td scope="row">{{ $dojo->dojo_id }}</td>
                                <td scope="row">
                                    <div class="float-left">
                                        <a href="{{ route('dojos.edit',$dojo->id) }}" class="btn btn-primary">Edit</a>
                                    </div>
                                    <div class="ml-2 float-left">
                                        <form action="{{ route('dojos.destroy', $dojo->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Do you want to delete this dojo?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $dojos->links() }}
                </div>
            </div>
        </div>
    </div>
</div> -->
<style>
    a {
        color: #212529;
        text-decoration: none;
    }
</style>
@endsection