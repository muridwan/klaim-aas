<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubrogationPayment extends Model
{
    use HasFactory;
    protected $table = "ap_subrogation_payments";

    protected $fillable = [
        'subrogation_id', 'payment_amount', 'payment_date',
        'payment_method', 'reference_number', 'remarks'
    ];

    public function subrogation()
    {
        return $this->belongsTo(Subrogation::class);
    }
}
