<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hr_offices';

    public function outlets()
    {
        return $this->hasMany(Outlet::class)->where('level', 5);
    }

    public function limits()
    {
        return $this->hasMany(Limit::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
