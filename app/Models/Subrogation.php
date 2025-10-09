<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Subrogation extends Model
{
    use HasFactory, HasUuids;
    protected $table = "ap_subrogations";

    protected $fillable = [
        'claim_id', 'third_party_name', 'third_party_type',
        'subrogation_amount', 'recovered_amount', 'submission_date',
        'due_date', 'status', 'notes'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function histories()
    {
        return $this->hasMany(SubrogationHistory::class);
    }

    public function documents()
    {
        return $this->hasMany(SubrogationDocument::class);
    }

    public function payments()
    {
        return $this->hasMany(SubrogationPayment::class);
    }
}
