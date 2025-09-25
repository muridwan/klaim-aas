<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
	use SoftDeletes;

	protected $table = 'ap_documents';

	public function claim()
	{
		return $this->belongsTo(Claim::class);
	}

	public function cause_file()
	{
		return $this->belongsTo(Cause_file::class);
	}
}
