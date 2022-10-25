<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Organization;
use App\Models\Dojo;
use Illuminate\Http\Request;
use App\Models\MembershipCard;
use \App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('order_cards', 'store', 'getDojos', 'getInvoice', 'getDojosOrg', 'existing');
    }

    public function order_cards(Request $request)
    {
        // dd($request->session());
        $lOrder = '';
        if (isset($request->large_order_csv)) {

            $request->session()->forget('order');

            $validated = $request->validate([
                'large_order_csv' => 'required|mimes:csv'
            ]);
            $lOrder = $request->file('large_order_csv')->getClientOriginalName();

            $header = null;
            $data = array();
            if (($handle = fopen($request->file('large_order_csv')->getRealPath(), 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';exit;

            $order = new Order;
            $order->name = $request->order_name;
            $order->country = $request->country;
            $order->organization_id = $request->organization_id;
            $order->sub_organization_id = $request->sub_organization_id;
            $order->ship_to = $request->ship_to;
            $order->invoice_to = $request->invoice_to;
            $order->status = 'pending';
            $order->no_of_cards = count($data);

            $order->save();

            for ($i = 0; $i < count($data); $i++) {

                // $membership = MembershipCard::updateOrCreate(
                //     ['first_name' => $data[$i]['First Name'], 'last_name' => $data[$i]['Last Name'], 'member_id' => $data[$i]['Member ID']],
                //     ['membership_date' => date('Y-m-d', strtotime($data[$i]['Membership Date'])), 'dojo_id' => $request->ship_to, 'organization_id' => $request->organization_id, 'sub_organization_id' => $request->sub_organization_id, 'program' => $data[$i]['Program'], 'rank' => $data[$i]['Rank'], 'card_type' => $data[$i]['Card Type'], 'order_id' => $order->id ]
                // );

                $home_dojo = Dojo::where('name', 'LIKE', "{$data[$i]['Home Dojo']}")->pluck('id');

                $membership = new MembershipCard;
                $membership->first_name = $data[$i]['First Name'];
                $membership->last_name = $data[$i]['Last Name'];
                $membership->membership_date = date('Y-m-d', strtotime($data[$i]['Membership Date']));
                if(!$home_dojo->isEmpty()) {
                    $membership->dojo_id = $home_dojo[0];
                }
                $membership->organization_id = $request->organization_id;
                $membership->sub_organization_id = $request->sub_organization_id;
                $membership->program = $data[$i]['Program'];
                $membership->member_id = $data[$i]['Member ID'];
                $membership->rank = $data[$i]['Aikido Rank'];
                $membership->ki_rank = $data[$i]['Ki Rank'];    
                $membership->card_type = $data[$i]['Card Type'];

                $membership->save();
                $membership->orders()->attach($order, ['card_type' => $data[$i]['Card Type']]);

            }
        } else {
            // $request->session()->flush();
            if ($request->has('save_data')) {
                //validation
                $validated = $request->validate([
                    'country' => 'required',
                    'organization_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'membership_date' => 'required',
                    'dojo_id' => 'required',
                    'program' => 'required',
                    'member_id' => 'required',
                    'rank' => 'required',
                    'ki_rank' => 'required',
                    'card_type' => 'required'
                ]);

                $membership = [];
                $membership['order_name'] = $request->order_name;
                $membership['country'] = $request->country;
                $membership['org'] = $request->organization_id;
                $membership['sub_org'] = $request->sub_organization_id;
                $membership['ship_to'] = $request->ship_to;
                $membership['invoice_to'] = $request->invoice_to;

                $request->session()->put('membership', $membership);

                $exist_membership = MembershipCard::where('first_name', $request->first_name)->where('last_name', $request->last_name)->get();
                if(!$exist_membership->isEmpty())
                {
                    // dd($request->all());
                    $org_list = Organization::pluck('name', 'id');
                    $sub_org_list = Organization::where('parent_id', '<>', 0)->pluck('name', 'id');
                    $dojo_list = Dojo::pluck('name', 'id');
                    $invoice_ship = [];
                    foreach($org_list as $key=>$org) {
                        $invoice_ship[] = $org;
                    }
                    foreach($sub_org_list as $key=>$sub_org) {
                        $invoice_ship[] = $sub_org;
                    }
                    foreach($dojo_list as $key=>$dojo) {
                        $invoice_ship[] = $dojo;
                    }
                    $countries = $this->country_list();
                    $request->session()->put('temp', $request->all());
                    return view('exist', compact('exist_membership', 'org_list', 'sub_org_list', 'invoice_ship', 'countries'));
                }
                $data = $request->all();
                $request->session()->push('data', $data);
                session()->save();
            }
            if ($request->has('delete_card_list_id')) {
                $data = $request->session()->get('data');
                unset($data[$request->delete_card_list_id]);
                // dd($data);
                $request->session()->forget('data');
                $request->session()->put('data', $data);
                // dd($request->session());
            }
            if ($request->has('update_data')) {
                $data = $request->session()->get('data');
                $key = $request->update_data;
                $data[$key]['first_name'] = $request->new_first_name;
                $data[$key]['last_name'] = $request->new_last_name;
                $data[$key]['membership_date'] = $request->new_membership_date;
                $data[$key]['dojo_id'] = $request->new_dojo_id;
                $data[$key]['program'] = $request->new_program;
                $data[$key]['member_id'] = $request->new_member_id;
                $data[$key]['rank'] = $request->new_rank;
                $data[$key]['ki_rank'] = $request->ki_rank;
                $data[$key]['card_type'] = $request->new_card_type;
                $data[$key]['organization_id_card'] = $request->new_organization_id_card;
                $data[$key]['sub_organization_id_card'] = $request->new_sub_organization_id_card;

                $request->session()->forget('data');
                $request->session()->put('data', $data);
            }
        }
        $s_id = '';
        $edit_data = '';
        if ($request->has('edit_card_list_id')) {
            $data = $request->session()->get('data');
            $edit_data = $data[$request->edit_card_list_id];
            $s_id = $request->edit_card_list_id;
        }

        // $request->session()->flush();
        $org_list = Organization::orderBy('name')->pluck('name', 'id');
        $sub_org_list = Organization::orderBy('name')->where('parent_id', '<>', 0)->pluck('name', 'id');
        $dojo_list = Dojo::pluck('name', 'id');
        $invoice_ship = [];
        foreach($org_list as $key=>$org) {
            $invoice_ship[] = $org;
        }
        foreach($sub_org_list as $key=>$sub_org) {
            $invoice_ship[] = $sub_org;
        }
        foreach($dojo_list as $key=>$dojo) {
            $invoice_ship[] = $dojo;
        }
        $countries = $this->country_list();

        return view('order_cards', compact('org_list', 'sub_org_list', 'dojo_list', 'countries', 'edit_data', 's_id', 'lOrder', 'invoice_ship'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->session());
        //Data in Orders table
        // $dojo = Dojo::where('id', $request->session()->get('membership')['ship_to'])->pluck('name');
        // $org = Organization::where('id', $request->session()->get('membership')['invoice_to'])->pluck('name');

        $order = new Order;
        $order->name = $request->session()->get('membership')['order_name'];
        $order->country = $request->session()->get('membership')['country'];
        $order->organization_id = $request->session()->get('membership')['org'];
        $order->sub_organization_id = $request->session()->get('membership')['sub_org'];
        $order->ship_to = $request->session()->get('membership')['ship_to'];
        $order->invoice_to = $request->session()->get('membership')['invoice_to'];
        $order->status = 'pending';
        $order->no_of_cards = count($request->session()->get('data'));

        $order->save();

        //Data in Memberships table
        foreach ($request->session()->get('data') as $key => $value) {
            if (!isset($value['sub_organization_id'])) {
                $value['sub_organization_id'] = NULL;
            }
            // dd($value);
            if($value['card_type'] == 'replacement')
            {
                $membershipCard = MembershipCard::where('id', $value['id'])->update([
                    'first_name' => $value['first_name'],
                    'last_name' => $value['last_name'],
                    'membership_date' => $value['membership_date'],
                    'organization_id' => $value['organization_id'],
                    'sub_organization_id' => $value['sub_organization_id'],
                    'member_id' => $value['member_id'],
                    'dojo_id' => $value['dojo_id'],
                    'card_type' => $value['card_type'],
                    'rank' => $value['rank'],
                    'ki_rank' => $value['ki_rank'],
                    'program' => $value['program'],
                ]);
                $membership_old = MembershipCard::find($value['id']);
                $membership_old->orders()->attach($order, ['card_type' => $value['card_type']]);
            }

            else
            {
                $membership = new MembershipCard;
                $membership->first_name = $value['first_name'];
                $membership->last_name = $value['last_name'];
                $membership->membership_date = $value['membership_date'];
                $membership->dojo_id = $value['dojo_id'];
                $membership->organization_id = $value['organization_id_card'];
                $membership->sub_organization_id = $value['sub_organization_id_card'];
                $membership->program = $value['program'];
                $membership->member_id = $value['member_id'];
                $membership->rank = $value['rank'];
                $membership->ki_rank = $value['ki_rank'];
                $membership->card_type = $value['card_type'];

                $membership->save();
                $membership->orders()->attach($order, ['card_type' => $value['card_type']]);
            }

            

            $final_info[$key] = array(
                'first_name' => $value['first_name'],
                'last_name' => $value['last_name'],
                'card_type' => $value['card_type']
            );
        }

        $request->session()->forget(['data', 'membership']);

        $request->session()->put('order', $final_info);

        // if($request->has('password')){
        //     $details['password'] = $request->password;
        $admins = User::all('email');
        // dd($order);

        //invoice to
        $i_dojo = Dojo::where('name', $order->invoice_to)->first();
        $i_organization = Organization::where('name', $order->invoice_to)->first();
        $details = [];
        if(isset($i_dojo))
        {
            $details['invoice'] = $i_dojo;
        }
        else
        {
            $details['invoice'] = $i_organization;
        }

        //ship to
        $s_dojo = Dojo::where('name', $order->ship_to)->first();
        $s_organization = Organization::where('name', $order->ship_to)->first();

        if(isset($s_dojo))
        {
            $details['ship'] = $s_dojo;
        }
        else
        {
            $details['ship'] = $s_organization;
        }

        $details['order'] = $order;

        Mail::to($admins)->send(new \App\Mail\OrderMail($details));
        // }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function getDojos($id, $s_id) {
        if($s_id == 'null')
        {
            $dojos = Dojo::where('organization_id', $id)->where('status', 'active')->pluck('name');
            $orgs = Organization::where('id', $id)->pluck('name');
            $sub_orgs = Organization::where('parent_id', $id)->pluck('name');
        }
        else 
        {
            $dojos = Dojo::where('sub_organization_id', $s_id)->where('status', 'active')->pluck('name');
            $orgs = Organization::whereIn('id', [$id, $s_id])->pluck('name');
        }
        
        // array_push($dojos, $orgs);
        foreach($orgs as $org) {
            $dojos[] = $org;
        }
        if(isset($sub_orgs)){
            foreach($sub_orgs as $sub_org) {
                $dojos[] = $sub_org;
            }
        }
        
        return response()->json($dojos);
    }

    public function getInvoice($id, $s_id) {
        if($s_id == 'null')
        {
            $orgs = Organization::where('id', $id)->pluck('name', 'id');
        }
        else 
        {
            $orgs = Organization::whereIn('id', [$id, $s_id])->pluck('name', 'id');
        }
        
        return response()->json($orgs);
    }

    public function getDojosOrg($id, $s_id) {
        if($s_id == 'null')
        {
            $dojos = Dojo::where('organization_id', $id)->where('status', 'active')->pluck('name', 'id');
            if($dojos->isEmpty()){
                $dojos = Dojo::where('sub_organization_id', $id)->where('status', 'active')->pluck('name', 'id');
            }
        }
        else 
        {
            $dojos = Dojo::where('organization_id', $id)->where('sub_organization_id', $s_id)->where('status', 'active')->pluck('name', 'id');
        }
        return response()->json($dojos);
    }

    public function existing(Request $request)
    {
        $validated = $request->validate([
            'checkbox' => 'required',
        ]);
        // echo "<pre>";print_r($request->session());
        if($request->checkbox == 'none')
        {
            $temp = $request->session()->get('temp');
            $request->session()->push('data', $temp);
            $request->session()->forget('temp');
            // $request->session()->flush();
        }
        else
        {
            $user = MembershipCard::find($request->checkbox);
            $user->card_type = 'replacement';
            $user->organization_id_card = $user->organization_id;
            $user->sub_organization_id_card = $user->sub_organization_id;
            $user->membership_date = date('Y-m-d', strtotime($user->membership_date));
            // dd($user->getattributes());
            $request->session()->push('data', $user->getattributes());
            $request->session()->forget('temp');
        }
        
        // dd($request->session());
        // $request->session()->flush();
        return redirect()->route('order_cards');
    }
}
