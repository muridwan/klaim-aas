<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
	use SoftDeletes;

	protected $table = 'ap_claims';

	public function recommendations()
	{
		return $this->hasMany(Recommendation::class);
	}

	public function documents()
	{
		return $this->hasMany(Document::class);
	}

	public function cause()
	{
		return $this->belongsTo(Cause::class);
	}

	public function occupation()
	{
		return $this->belongsTo(Occupation::class);
	}

	public function position()
	{
		return $this->belongsTo(Position::class);
	}

	public function office()
	{
		return $this->belongsTo(Office::class);
	}

	public function outlet()
	{
		return $this->belongsTo(Outlet::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class);
	}

	public function subrogations()
    {
        return $this->hasMany(Subrogation::class);
    }

	public function creater()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
