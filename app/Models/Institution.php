<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    protected $table = 'ap_institutions';

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function causes()
    {
        return $this->hasMany(Cause::class);
    }
}
