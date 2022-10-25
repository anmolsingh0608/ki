<?php

namespace App\Http\Controllers;

use App\Models\Dojo;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Organization;
use App\Models\MembershipCard;

class NewCardRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        if ($search) {
            // $new_requests = Order::where('id', 'LIKE', "%{$search}%")->orWhere('invoice_to', 'LIKE', "%{$search}%")->orWhere('ship_to', 'LIKE', "%{$search}%")->whereNull('processed_date')->paginate(15);
            $new_requests = Order::where('id', 'LIKE', "%{$search}%")
                                        ->orWhere(function ($query) use ($search) {
                                            $query->where('invoice_to', 'LIKE', "%{$search}%")
                                                ->orwhere('ship_to', 'LIKE', "%{$search}%");
                                        })->whereNull('processed_date')->sortable()->paginate(15);
        } else {
            $new_requests = Order::where('status', 'pending')->sortable()->paginate(15);
        }
        
        return view('new_request.index', compact('new_requests'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $new_request = Order::find($id);
        // $dojo_list = Dojo::where('organization_id', $new_request->organization_id)->pluck('name', 'id');
        // $org_list = Organization::pluck('name', 'id');
        // $sub_org_list = Organization::where('parent_id', $new_request->organization_id)->pluck('name', 'id');

        $sub_org_list = [];
        if($new_request->sub_organization_id == null)
        {
            $dojo_list = Dojo::where('organization_id',  $new_request->organization_id)->where('status', 'active')->pluck('name');
            $org_list = Organization::where('id', $new_request->organization_id)->pluck('name');
            $sub_org_list = Organization::where('parent_id', $new_request->organization_id)->pluck('name');
        }
        else 
        {
            $dojo_list = Dojo::where('sub_organization_id', $new_request->sub_organization_id)->where('status', 'active')->pluck('name');
            $org_list = Organization::whereIn('id', [$new_request->organization_id, $new_request->sub_organization_id])->pluck('name');
        }

        foreach($org_list as $org) {
            $dojo_list[] = $org;
        }
        if(isset($sub_org_list)){
            foreach($sub_org_list as $sub_org) {
                $dojo_list[] = $sub_org;
            }
        }

        $countries = $this->country_list();
        return view('new_request.edit', compact('new_request', 'org_list', 'countries', 'sub_org_list', 'dojo_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'country' => 'required',
            'no_of_cards' => 'required',
            'status' => 'required',
            'order_name' => 'required'
        ]);
        $processed_date = NULL;
        if($request->status == 'processed'){
            $processed_date = date("Y-m-d H:i:s");
            // dd($processed_date);
        }
        $order = Order::where('id', $id)->update([
            'name' => $request->order_name,
            'country' => $request->country,
            'no_of_cards' => $request->no_of_cards,
            'ship_to' => $request->ship_to,
            'invoice_to' => $request->invoice_to,
            'status' => $request->status,
            'processed_date' => $processed_date
        ]);

        return redirect("/newrequest")->with('success', 'Card Request Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::destroy($id);

        return redirect("/newrequest")->with('success', 'Card Request deleted!');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Order::destroy(explode(",", $ids));
        $request->session()->flash('success', 'Cards Requests deleted!');

        return response()->json(['success' => "true"]);
    }

    public function exportCSV(Request $request)
    {
        $ids = $request->ids;
        $fileName = 'new_card_requests.csv';
        $order = Order::with(['membership'])->whereIn('id', explode(",",$ids))->get();
        // dd($order);
        $memberships = $order[0]->membership;
        
        $order = Order::whereIn('id', explode(",",$ids))->update([
            'status' => 'processed',
            'processed_date' => date("Y-m-d H:i:s")
        ]);
        // dd($order);
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
        $fileName = 'new_card_requests.csv';
        $memberships = MembershipCard::whereIn('order_id', explode(",",$ids))->get();

        $order = Order::whereIn('id', explode(",",$ids))->update([
            'status' => 'processed',
            'processed_date' => date("Y-m-d H:i:s")
        ]);
        // dd($order);
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

                fputcsv($file, array($row['MEMBER NAME'], $row['MEMBER ID'], $row['COUNTRY'], $row['ORG'], $row['DOJO'], $row['QR CODE URL']));
                //$row['MEMBER SINCE'], 
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        // return response()->json(['success'=>$memberships]);
    }

    public function exportCSVK12(Request $request)
    {
        $ids = $request->ids;
        $fileName = 'new_card_requests.csv';
        $order = Order::with(['membership'])->whereIn('id', explode(",",$ids))->get();
        // dd($order);
        $memberships = $order[0]->membership;

        $order = Order::whereIn('id', explode(",",$ids))->update([
            'status' => 'processed',
            'processed_date' => date("Y-m-d H:i:s")
        ]);
        // dd($order);
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

                fputcsv($file, array($row['MEMBER NAME'], $row['MEMBER ID'], $row['COUNTRY'], $row['ORG'], $row['DOJO'], $row['QR CODE URL']));
                // $row['MEMBER SINCE'], 
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        // return response()->json(['success'=>$memberships]);
    }
}
