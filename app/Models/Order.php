<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Order extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    public $sortable = ['id', 'created_at', 'name', 'no_of_cards', 'invoice_to', 'ship_to'];

    protected $fillable = [
        'country',
        'organization_id',
        'sub_organization_id',
        'ship_to',
        'invoice_to',
        'status',
        'no_of_cards'
    ];

    public function membership_card()
    {
        return $this->hasMany('App\Models\MembershipCard');
    }

    public function membership()
    {
        return $this->belongsToMany(MembershipCard::class, 'order_items')->withPivot('card_type');
    }
}
