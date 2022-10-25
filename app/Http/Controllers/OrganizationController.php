<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('getSubOrganizations');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        if($search){
            $organizations = Organization::where('name', 'LIKE', "%{$search}%")->orwhere('org_id', 'LIKE', "{$search}%")->sortable()->paginate(15);
        }else{
            $organizations = Organization::sortable()->paginate(15);
        }
        
        return view('organization.index',compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $org_list = Organization::pluck('name','id');

        return view('organization.create', compact('org_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:organizations|max:255',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'parent_id' => 'required',
            'org_id' => 'required'
        ]);

        $organization = new Organization;
        $organization->name = $request->name;
        $organization->address = $request->address;
        $organization->email = $request->email;
        $organization->phone = $request->phone;
        $organization->parent_id = $request->parent_id;
        $organization->org_id = $request->org_id;
        $organization->save();

        return redirect("/organizations")->with('success', 'Organization created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        $org_list = Organization::pluck('name','id');

        return view('organization.edit', compact('org_list','organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => "required|unique:organizations,name,{$organization->id}|max:255",
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'parent_id' => 'required',
            'org_id' => 'required'
        ]);

        $organization->name = $request->name;
        $organization->address = $request->address;
        $organization->email = $request->email;
        $organization->phone = $request->phone;
        $organization->parent_id = $request->parent_id;
        $organization->org_id = $request->org_id;
        $organization->save();

        return redirect("/organizations")->with('success', 'Organization updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect("/organizations")->with('success', 'Organization deleted!');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Organization::destroy(explode(",",$ids));
        $request->session()->flash('success', 'Organizations deleted!');

        return response()->json(['success'=>"true"]);
    }

    public function getSubOrganizations(Organization $organization)
    {
        $sub_organizations = $organization->sub_organizations()->pluck('name','id');

        return response()->json($sub_organizations);
    }
}
