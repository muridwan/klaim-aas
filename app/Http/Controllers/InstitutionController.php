<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Institution;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$institutions	= Institution::select('id', 'uuid', 'code', 'name')->with([
			'outlets:id,institution_id',
			'causes:id,institution_id',
		])->orderBy('code')->get();
		$data     		= [
			'url'     => 'institutions',
			'menu'    => 'sumber bisnis',
			'title'   => "data sumber bisnis",
			'institutions' => $institutions,
		];

		return view('institution.index', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$data	= [
			'url'     		=> 'institutions',
			'menu'    		=> 'sumber bisnis',
			'title'				=> "tambah sumber bisnis",
		];

		return view('institution.create', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$rules = array(
				'code'						=> 'required|min:3|max:16|unique:ap_institutions,code,NULL,id,deleted_at,NULL',
				'name'						=> 'required|min:3',
				'description'			=> '',
			);
			$attributeNames = array(
				'code'						=> 'Kode',
				'name'						=> 'Nama Instansi',
				'description'			=> 'Deskripsi',
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$institution             	= new Institution();
				$institution->uuid       	= Str::uuid()->toString();
				$institution->code       	= $request->code 			?? null;
				$institution->name       	= $request->name 			?? null;
				$institution->description	= $request->description ?? null;
				$institution->phone				= $request->phone 		?? null;
				$institution->email 			= $request->email 		?? null;
				$institution->address 		= $request->address 	?? null;
				$institution->save();

				// Add Logs
				$this->add_log("Penambahan Data Sumber Bisnis [$institution->uuid]");
				DB::commit();
				return redirect()->route('institution.detail', ['uuid' => $institution->uuid])->with('pesan_success', "Data Sumber Bisnis <b>" . $institution->code . "</b> Berhasil Ditambah");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('institutions')->with('pesan_error', "Penambahan Data Sumber Bisnis Gagal");
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Institution $institution)
	{
		//
	}

	public function detail(Request $request, $uuid)
	{
		$institution = Institution::where('uuid', $uuid)->firstOrFail();

		$data	= [
			'url'     		=> 'institutions',
			'menu'    		=> 'sumber bisnis',
			'title'				=> "detail sumber bisnis",
			'institution'	=> $institution ?? collect(),
			'causes'			=> $causes ?? collect(),
		];

		return view('institution.detail', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Institution $institution)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Institution $institution)
	{
		DB::beginTransaction();
		try {
			$institution->delete();
			$rules = array(
				'code'						=> 'required|min:3|max:16|unique:ap_institutions,code,NULL,id,deleted_at,NULL',
				'name'						=> 'required|min:3',
				'description'			=> '',
			);
			$attributeNames = array(
				'code'						=> 'Kode',
				'name'						=> 'Nama Instansi',
				'description'			=> 'Deskripsi',
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$new_institution             	= new Institution();
				$new_institution->uuid       	= $institution->uuid	?? Str::uuid()->toString();
				$new_institution->code       	= $request->code 			?? null;
				$new_institution->name       	= $request->name 			?? null;
				$new_institution->description	= $request->description ?? null;
				$new_institution->phone				= $request->phone 		?? null;
				$new_institution->email 			= $request->email 		?? null;
				$new_institution->address 		= $request->address 	?? null;
				$new_institution->save();

				// Update Data Relation
				Outlet::where('institution_id', $institution->id)->update([
					'institution_id' => $new_institution->id
				]);

				Cause::where('institution_id', $institution->id)->update([
					'institution_id' => $new_institution->id
				]);

				// Add Logs
				$this->add_log("Pembaruan Data Sumber Bisnis [$institution->uuid]");
				DB::commit();
				return redirect()->route('institution.detail', ['uuid' => $new_institution->uuid])->with('pesan_success', "Data Sumber Bisnis <b>" . $new_institution->code . "</b> Berhasil Diperbarui");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('institutions')->with('pesan_error', "Pembaruan Data Sumber Bisnis Gagal");
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Institution $institution)
	{
		DB::beginTransaction();
		try {
			// Peril_file::where('peril_id', $institution->id)->delete();
			$institution->delete();

			$this->add_log('Menghapus Data Sumber Bisnis = ' .  $institution->code . " [ " . $institution->uuid . " ]");
			DB::commit();
			return redirect()->route('institutions')->with('pesan_success', "Hapus Data Sumber Bisnis '$institution->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('institutions')->with('pesan_error', "Hapus Data Sumber Bisnis Gagal");
		}
	}
}
