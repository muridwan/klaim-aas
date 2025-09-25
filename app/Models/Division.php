<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hr_divisions';

    // =======================================================================
    // ELOQUENTs
    // =======================================================================
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function sections()
    {
        return $this->hasManyThrough(Section::class, Department::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
