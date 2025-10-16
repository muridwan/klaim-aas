<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendationHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "ap_recommendation_histories";

    protected $fillable = ['uuid','recommendation_id', 'claim_id', 'user_id', 'note'];

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
