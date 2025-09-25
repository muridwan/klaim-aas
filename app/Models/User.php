<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
  use SoftDeletes;

  protected $table = 'hr_users';

  public function user_role()
  {
    return $this->hasOne(User_role::class, 'user_id');
  }

  public function main_position()
  {
    return $this->positions()->where('status', 1);
  }

  public function positions()
  {
    return $this->belongsToMany(Position::class, User_position::class, 'user_id', 'position_id');
  }

  public function role()
  {
    return $this->hasOne(User_role::class);
  }
}
