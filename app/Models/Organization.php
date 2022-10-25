<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Organization extends Model
{
    use HasFactory,SoftDeletes,Sortable;

    public $sortable = ['name', 'org_id'];

    public function parent()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function sub_organizations()
    {
        return $this->hasMany('App\Models\Organization','parent_id','id');
    }
}
