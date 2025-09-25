<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directorate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hr_directorates';

    // =======================================================================
    // ELOQUENTs
    // =======================================================================
    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function sections()
    {
        return $this->hasManyThrough(Section::class, Department::class);
    }
}
