<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cause_file extends Model
{
    use SoftDeletes;

    protected $table = 'ap_cause_files';

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
}
