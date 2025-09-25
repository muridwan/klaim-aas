<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_pgd extends Model
{
    use SoftDeletes;
    protected $table = 'ap_users';

    public function user_role()
    {
        return $this->hasOne(User_role::class, 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
