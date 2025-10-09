<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubrogationDocument extends Model
{
    use HasFactory;
    protected $table = "ap_subrogation_documents";
    protected $fillable = [
        'subrogation_id', 'document_name', 'file_path', 'document_type'
    ];

    public function subrogation()
    {
        return $this->belongsTo(Subrogation::class);
    }
}

