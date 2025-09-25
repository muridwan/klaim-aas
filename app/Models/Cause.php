<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cause extends Model
{
    use SoftDeletes;

    protected $table = 'ap_causes';

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function cause_files()
    {
        return $this->hasMany(Cause_file::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function limits()
    {
        return $this->hasMany(Limit::class);
    }
}
