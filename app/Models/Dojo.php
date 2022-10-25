<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Dojo extends Model
{
    use HasFactory,SoftDeletes,Sortable;

    public $sortable = ['id', 'name', 'country', 'dojo_id'];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function sub_organization()
    {
        return $this->belongsTo('App\Models\Organization', 'sub_organization_id', 'id');
    }
}
