<?php

namespace App\Http\Controllers;

use App\Models\Dojo;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Organization;

class ProcessedCardsController extends Controller
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
            // $processed_cards = Order::where('id', 'LIKE', "%{$search}%")->orWhere('invoice_to', 'LIKE', "%{$search}%")->orWhere('ship_to', 'LIKE', "%{$search}%")->whereNull('processed_date')->paginate(15);
            $processed_cards = Order::where('id', 'LIKE', "%{$search}%")
                                        ->orWhere(function ($query) use ($search) {
                                            $query->where('invoice_to', 'LIKE', "%{$search}%")
                                                ->orwhere('ship_to', 'LIKE', "%{$search}%");
                                        })->whereNotNull('processed_date')->sortable()->paginate(15);
        } else {
            $processed_cards = Order::where('status', 'processed')->sortable()->paginate(15);
        }
    
        return view('processed_request.index', compact('processed_cards'));
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
        $processed_card = Order::find($id);
        // $dojo_list = Dojo::pluck('name', 'id');
        // $org_list = Organization::pluck('name', 'id');
        // $sub_org_list = Organization::where('parent_id', $processed_card->organization_id)->pluck('name', 'id');
        // dd($processed_card->sub_organization_id);
        $sub_org_list = [];
        if($processed_card->sub_organization_id == null)
        {
            $dojo_list = Dojo::where('organization_id',  $processed_card->organization_id)->pluck('name');
            $org_list = Organization::where('id', $processed_card->organization_id)->pluck('name');
            $sub_org_list = Organization::where('parent_id', $processed_card->organization_id)->pluck('name');
        }
        else 
        {
            $dojo_list = Dojo::where('sub_organization_id', $processed_card->sub_organization_id)->pluck('name');
            $org_list = Organization::whereIn('id', [$processed_card->organization_id, $processed_card->sub_organization_id])->pluck('name');
        }
        
        // array_push($dojos, $orgs);
        foreach($org_list as $org) {
            $dojo_list[] = $org;
        }
        if(isset($sub_org_list)){
            foreach($sub_org_list as $sub_org) {
                $dojo_list[] = $sub_org;
            }
        }

        $countries = $this->country_list();
        return view('processed_request.edit', compact('processed_card', 'org_list', 'countries', 'sub_org_list', 'dojo_list'));
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
            'order_name' => 'required',
            'country' => 'required',
            'no_of_cards' => 'required',
            'ship_to' => 'required',
            'invoice_to' => 'required',
            'status' => 'required'
        ]);

        if($request->status == 'pending'){
            Order::where('id', $id)->update([
                'processed_date' => NULL,
            ]);
        }
        $order = Order::where('id', $id)->update([
            'name' => $request->order_name,
            'country' => $request->country,
            'no_of_cards' => $request->no_of_cards,
            'ship_to' => $request->ship_to,
            'invoice_to' => $request->invoice_to,
            'status' => $request->status
        ]);

        return redirect("/processed_cards")->with('success', 'Card Request Updated!');
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

        return redirect("/processed_cards")->with('success', 'Card deleted!');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Order::destroy(explode(",", $ids));
        $request->session()->flash('success', 'Cards deleted!');

        return response()->json(['success' => "true"]);
    }
}
