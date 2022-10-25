<?php

namespace App\Http\Controllers;

use App\Models\MembershipCard;
use App\Models\Dojo;
use App\Models\Organization;
use App\Models\Order;
use Illuminate\Cache\MemcachedLock;
use Illuminate\Http\Request;

class MembershipCardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('add_card');
    }

    public function add_card(Request $request)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MembershipCard $membershipCard, Request $request, $id = NULL)
    {
        $search = $request->input('search');
        if ($id) {
            $membershipCard = MembershipCard::with(['orders'])->whereHas("orders", function($q) use($id) { $q->where('order_id', "=", $id); } )->paginate(15);
            
            return view('membership.index',compact('membershipCard'));
        }
        if($search){
            $membershipCard = MembershipCard::where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")->sortable()->paginate(15);
        }else{
            $membershipCard = MembershipCard::sortable()->paginate(15);
        }

        return view('membership.index',compact('membershipCard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MembershipCard  $membershipCard
     * @return \Illuminate\Http\Response
     */
    public function show(MembershipCard $membershipCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MembershipCard  $membershipCard
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $membershipCard = MembershipCard::find($id);
        $dojo_list = Dojo::pluck('name', 'id');
        $org_list = Organization::pluck('name','id');
        $sub_org_list = Organization::where('parent_id', $membershipCard->organization_id)->pluck('name','id');
        
        $countries = $this->country_list();
        return view('membership.edit', compact('dojo_list', 'org_list', 'countries', 'sub_org_list', 'membershipCard'));     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MembershipCard  $membershipCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MembershipCard $membershipCard, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'membership_date' => 'required',
            'dojo' => 'required',
            'organization_id' => 'required',
            'rank' => 'required',
            'card_type' => 'required',
            'program' => 'required',
        ]);

        $membershipCard = MembershipCard::where('id', $id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'membership_date' => $request->membership_date,
                'organization_id' => $request->organization_id,
                'sub_organization_id' => $request->sub_organization_id,
                'member_id' => $request->old_member_id,
                'dojo_id' => $request->dojo,
                'card_type' => $request->card_type,
                'rank' => $request->rank,
                'ki_rank' => $request->ki_rank,
                'program' => $request->program,
        ]);

        
        return redirect($request->input('url'))->with('success', 'Membership Card updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MembershipCard  $membershipCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(MembershipCard $membershipCard, $id)
    {
        // dd($membershipCard);
        MembershipCard::destroy($id);

        return redirect("/memberships")->with('success', 'Membership deleted!');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        MembershipCard::destroy(explode(",",$ids));
        $request->session()->flash('success', 'Membership deleted!');

        return response()->json(['success'=>"true"]);
    }

    public function userDetail($id)
    {
        $membershipInfo = MembershipCard::find($id);
        return view('membership.detail', compact('membershipInfo'));
    }

    public function exportCSV(Request $request)
    {
        $ids = $request->ids;
        $fileName = 'membership_card.csv';
        $memberships = MembershipCard::find(explode(",",$ids));
        // $request->session()->flash('success', $tasks);
        // return response()->json(['success'=>"true"]);
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('First name', 'Last name', 'Membership date', 'Dojo', 'Organization', 'Sub organization', 'Program', 'Old Member ID', 'New Member ID', 'Aikido Rank', 'Ki Rank', 'Card type');

        $callback = function() use($memberships, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($memberships as $membership) {
                $dojo[0] = '';
                if (isset($membership->dojo_id)) {
                    $dojo = Dojo::where('id', $membership->dojo_id)->pluck('name');
                }
                $org[0] = '';
                if(isset($membership->organization_id)){
                    $org = Organization::where('id', $membership->organization_id)->pluck('name');
                }
                $sub_org[0] = '';
                if(isset($membership->sub_organization_id)){
                    $sub_org = Organization::where('id', $membership->sub_organization_id)->pluck('name');
                }
                $row['First name']  = $membership->first_name;
                $row['Last name']    = $membership->last_name;
                $row['Membership date'] = date("m/d/Y",strtotime($membership->membership_date));
                $row['Dojo']  = $dojo[0];
                $row['Organization']  = $org[0];
                $row['Sub organization'] = $sub_org[0];
                $row['Program'] = ucwords($membership->program);
                $row['Old Member ID'] = $membership->member_id;
                $row['New Member ID'] = $membership->id;
                $row['Rank'] = ucwords($membership->rank);
                $row['Ki Rank'] = ucwords($membership->ki_rank);
                $row['Card type'] = ucwords($membership->card_type);

                fputcsv($file, array($row['First name'], $row['Last name'], $row['Membership date'], $row['Dojo'], $row['Organization'], $row['Sub organization'], $row['Program'], $row['Old Member ID'], $row['New Member ID'], $row['Rank'], $row['Ki Rank'], $row['Card type']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        // return response()->json(['success'=>$memberships]);
    }

    public function exportCSVBodno(Request $request)
    {
        $ids = $request->ids;
        $fileName = 'membership_card.csv';
        $memberships = MembershipCard::find(explode(",",$ids));
        // $request->session()->flash('success', $tasks);
        // return response()->json(['success'=>"true"]);
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('MEMBER NAME', 'MEMBER ID', 'COUNTRY', 'ORG', 'DOJO', 'MEMBER SINCE', 'QR CODE URL');

        $callback = function() use($memberships, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($memberships as $membership) {
                $dojo[0] = '';
                if (isset($membership->dojo_id)) {
                    $dojo = Dojo::where('id', $membership->dojo_id)->pluck('name');
                }
                $org[0] = '';
                if(isset($membership->organization_id)){
                    $org = Organization::where('id', $membership->organization_id)->pluck('name');
                }
                $sub_org[0] = '';
                if(isset($membership->sub_organization_id)){
                    $sub_org = Organization::where('id', $membership->sub_organization_id)->pluck('name');
                }
                $country = Order::where('id', $membership->order_id)->pluck('country');
                $row['MEMBER NAME']  = $membership->first_name . ' ' . $membership->last_name;
                $row['MEMBER ID'] = $membership->id;
                $row['COUNTRY'] = $country[0];
                $row['ORG']  = $org[0];
                $row['DOJO']  = $dojo[0];
                $row['MEMBER SINCE'] = date("m/d/Y",strtotime($membership->membership_date));
                $row['QR CODE URL'] = 'http://kiaikidocards.com/membership/userDetail/'.$membership->id;

                fputcsv($file, array($row['MEMBER NAME'], $row['MEMBER ID'], $row['COUNTRY'], $row['ORG'], $row['DOJO'], $row['MEMBER SINCE'], $row['QR CODE URL']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        // return response()->json(['success'=>$memberships]);
    }

    public function exportCSVK12(Request $request)
    {
        $ids = $request->ids;
        $fileName = 'membership_card.csv';
        $memberships = MembershipCard::find(explode(",",$ids));
        // $request->session()->flash('success', $tasks);
        // return response()->json(['success'=>"true"]);
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('MEMBER NAME', 'MEMBER ID', 'COUNTRY', 'ORG', 'DOJO', 'MEMBER SINCE', 'QR CODE URL');

        $callback = function() use($memberships, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($memberships as $membership) {
                $dojo[0] = '';
                if (isset($membership->dojo_id)) {
                    $dojo = Dojo::find($membership->dojo_id);
                }
                $org[0] = '';
                if(isset($membership->organization_id)){
                    $org = Organization::where('id', $membership->organization_id)->pluck('name');
                }
                $sub_org[0] = '';
                if(isset($membership->sub_organization_id)){
                    $sub_org = Organization::where('id', $membership->sub_organization_id)->pluck('name');
                }
                $country = $dojo->country;
                $row['MEMBER NAME']  = strtoupper( $membership->first_name . ' ' . $membership->last_name );
                $row['MEMBER ID'] = 'MEMBER ID: ' . $membership->id;
                $row['COUNTRY'] = 'COUNTRY: ' . $country;
                $row['ORG']  = 'ORG: ' . $org[0];
                $row['DOJO']  = 'DOJO: ' . $dojo->name;
                $row['MEMBER SINCE'] = 'MEMBER SINCE: ' . date("m/d/Y",strtotime($membership->membership_date));
                $row['QR CODE URL'] = 'http://kiaikidocards.com/membership/userDetail/'.$membership->id;

                fputcsv($file, array($row['MEMBER NAME'], $row['MEMBER ID'], $row['COUNTRY'], $row['ORG'], $row['DOJO'], $row['MEMBER SINCE'], $row['QR CODE URL']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        // return response()->json(['success'=>$memberships]);
    }
}
