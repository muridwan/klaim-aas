<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use SoftDeletes;

    protected $table = 'ap_outlets';

    public function parent()
    {
        return $this->hasMany(Outlet::class, 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(Outlet::class, 'parent_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
