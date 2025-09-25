<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    protected $table = 'ap_files';

    public function cause_files()
    {
        return $this->hasMany(Cause_file::class);
    }
}
