<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
	public function debug($string)
	{
		return print("<pre>" . print_r($string, true) . "</pre>");
	}

	public function get_data_user()
	{
		$user = User::where('uuid', session('user_uuid'))->first();
		return $user;
	}

	public function add_log($desc)
	{
		// $user = $this->get_data_user();
		DB::table('ap_logs')->insert([
			'description'   => $desc,
			// 'created_by'    => null,
			'created_at'    => now(),
		]);
	}
}
