<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $table = 'ap_businesses';

    public function causes()
    {
        return $this->hasMany(Cause::class);
    }
}
