<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "hr_positions";

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user_positions()
    {
        return $this->hasMany(User_position::class);
    }

    public function head_position()
    {
        return $this->hasOne(User_position::class)->where('status', 1);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, User_position::class, 'position_id', 'user_id');
    }
}
