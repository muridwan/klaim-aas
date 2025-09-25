<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ap_user_roles';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User_pgd::class);
    }

    public function user_aas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
