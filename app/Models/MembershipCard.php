<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class MembershipCard extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $fillable = [
        'first_name',
        'last_name',
        'membership_date',
        'dojo_id',
        'organization_id',
        'sub_organization_id',
        'program',
        'member_id',
        'rank',
        'card_type',
        'order_id'
    ];

    public $sortable = ['first_name', 'membership_date', 'member_id', 'id', 'rank', 'program'];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function sub_organization()
    {
        return $this->belongsTo('App\Models\Organization', 'sub_organization_id', 'id');
    }

    public function dojo()
    {
        return $this->belongsTo('App\Models\Dojo');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function customOrders() {

        return $this->hasMany(OrderItem::class, "membership_card_id");
    }

    public function orders()//$id)
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('card_type');//->wherePivot('order_id', '=', $id);

    }

}
