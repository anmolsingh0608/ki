@extends('layouts.app')

@section('title', 'Admins')

@section('content')


<div class="ki-dashboard">
    <div class="container-xxl px-xxl-0">
        <div class="align-items-center d-flex mb-2 row">
            <div class="align-items-center col-md-6 d-flex">
                <h2 class="m-0 mb-0 title">Admins</h2>
                <a href="/users/create"><button class="border-0 cta ms-3 px-4 py-2">Add New</button></a>
            </div>
            <div class="col-md-6">
                <form class="align-items-center d-flex form-ki justify-content-end" action="{{ route('users.index') }}" method="GET">
                    <input class="form-control h-100 me-2" type="text" name="search" />
                    <button type="submit" class="btn cta px-4 py-2 w" style="white-space: nowrap;">Search</button>
                </form>
            </div>
        </div>
        <p>All ({{count($users)}})</p>
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
                        <a id="bulk-delete" class="dropdown-item" data-uri="{{ route('users.deleteall') }}">Delete Selected</a>
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
                        <td scope="col"><input type="checkbox" id="selectall" /> @sortablelink('name', 'Name')</td>
                        <td scope="col">@sortablelink('email', 'E-mail')</td>
                        <td scope="col">Actions</td>
                    </tr>
                    @foreach ($users as $user)
                    <tr>
                        <td scope="row"><input type="checkbox" class="singlechkbox" data-id="{{$user->id}}" /> {{ $user->name }} </td>
                        <td scope="row">{{ $user->email }}</td>
                        <td scope="row" class="d-flex">
                            <form></form>
                            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn" type="submit" onclick="return confirm('Do you want to delete this admin?')" style="padding: 0 .50rem;">
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
                        <a id="bulk-delete-2" class="dropdown-item" data-uri="{{ route('users.deleteall') }}">Delete Selected</a>
                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <button class="cta border-0 py-2 px-4">Apply</button>
                </div> -->
            </div>
        </form>
        {{ $users->links() }}
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