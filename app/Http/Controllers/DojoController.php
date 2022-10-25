<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Dojo;
use Illuminate\Http\Request;

class DojoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            $dojos = Dojo::where('dojos.name', 'LIKE', "%{$search}%")->orwhere('dojo_id', 'LIKE', "{$search}%")->sortable()->paginate(15);
        }else{
            $dojos = Dojo::sortable()->paginate(15);
        }
        
        return view('dojo.index',compact('dojos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $org_list = Organization::pluck('name','id');
        $countries = $this->country_list();

        return view('dojo.create', compact('org_list','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|unique:dojos|max:255',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'organization_id' => 'required',
            //'sub_organization_id' => 'required',
            'country' => 'required',
            'dojo_id' => 'required',
            'status' => 'required',
        ]);

        $dojo = new Dojo;
        $dojo->name = $request->name;
        $dojo->address = $request->address;
        $dojo->email = $request->email;
        $dojo->phone = $request->phone;
        $dojo->organization_id = $request->organization_id;
        if(isset($request->sub_organization_id)){
            $dojo->sub_organization_id = $request->sub_organization_id;
        }
        $dojo->country = $request->country;
        $dojo->dojo_id = $request->dojo_id;
        $dojo->status = $request->status;
        
        $dojo->save();

        return redirect("/dojos")->with('success', 'Dojo created!');
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
    public function edit($id)
    {
        $dojo = Dojo::find($id);
        $org_list = Organization::pluck('name','id');
        $sub_org_list = Organization::where('parent_id', $dojo->organization_id)->pluck('name','id');
        
        $countries = $this->country_list();
        return view('dojo.edit', compact('dojo', 'org_list', 'countries', 'sub_org_list'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dojo $dojo)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'organization_id' => 'required',
            // 'sub_organization_id' => 'required',
            'country' => 'required',
            'dojo_id' => 'required',
            'status' => 'required'
        ]);

        // $dojo = Dojo::where('id', $id)->update([
        //     'name' => $request->name,
        //     'address' => $request->address,
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'organization_id' => $request->organization_id,
        //     'sub_organization_id' => $request->sub_organization_id,
        //     'country' => $request->country,
        //     'dojo_id' => $request->dojo_id
        // ]);

        $dojo->name = $request->name;
        $dojo->address = $request->address;
        $dojo->email = $request->email;
        $dojo->phone = $request->phone;
        $dojo->organization_id = $request->organization_id;
        
            $dojo->sub_organization_id = $request->sub_organization_id;
        
        $dojo->country = $request->country;
        $dojo->dojo_id = $request->dojo_id;
        $dojo->status = $request->status;
        $dojo->save();

        return redirect("/dojos")->with('success', 'Dojo updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dojo $dojo)
    {
        $dojo->delete();

        return redirect("/dojos")->with('success', 'Dojo deleted!');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Dojo::destroy(explode(",",$ids));
        $request->session()->flash('success', 'Dojos deleted!');

        return response()->json(['success'=>"true"]);
    }

    public function info($name)
    {
        $dojo = Dojo::where('name', $name)->first();
        $organization = Organization::where('name', $name)->first();
        
        $countries = $this->country_list();
        if(isset($dojo))
        {
            $org_list = Organization::pluck('name','id');
            $sub_org_list = Organization::where('parent_id', $dojo->organization_id)->pluck('name','id');
            return view('dojo.edit', compact('dojo', 'org_list', 'countries', 'sub_org_list'));
        }
        else
        {
            $org_list = Organization::pluck('name','id');

            return view('organization.edit', compact('org_list','organization'));
        }
        // return view('dojo.edit', compact('dojo', 'org_list', 'countries', 'sub_org_list'));
    }
}
