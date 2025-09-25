<?php

namespace App\Http\Controllers;

use App\Models\Limit;
use App\Models\Office;
use App\Models\Peril;
use Illuminate\Http\Request;

class LimitController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		// 
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Limit $limit)
	{
		//
	}

	public function detail($uuid)
	{
		$peril 		= Peril::with('limits')->where('uuid', $uuid)->first();
		$offices 	= Office::with('limits')->where('category', 2)->orderBy('code')->get();

		$data     = [
			'url'     => 'perils',
			'menu'    => 'risiko',
			'title'   => "atur limit",
			'peril' 	=> $peril,
			'offices' => $offices,
		];

		return view('peril.limit', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Limit $limit)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Limit $limit)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Limit $limit)
	{
		//
	}
}
