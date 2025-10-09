<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubrogationHistory extends Model
{
    use HasFactory;
    protected $table = "ap_subrogation_histories";

    protected $fillable = [
        'subrogation_id', 'status_before', 'status_after', 'remarks', 'changed_by'
    ];

    public function subrogation()
    {
        return $this->belongsTo(Subrogation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

