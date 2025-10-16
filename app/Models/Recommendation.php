<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recommendation extends Model
{
  use SoftDeletes;
  protected $table = "ap_recommendations";

  public function position()
  {
    return $this->belongsTo(Position::class);
  }

  public function creater()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function histories()
  {
      return $this->hasMany(RecommendationHistory::class);
  }
}
