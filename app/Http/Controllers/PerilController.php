<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Limit;
use App\Models\Office;
use App\Models\Peril;
use App\Models\Peril_file;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerilController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$perils = Peril::select('id', 'uuid', 'code', 'name')->with([
			'peril_files:id,peril_id',
			'claims:id,peril_id'
		])->orderBy('code')->get();
		$data		= [
			'url'     => 'perils',
			'menu'    => 'risiko',
			'title'		=> "data risiko",
			'perils'	=> $perils,
		];

		return view('peril.index', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$files 	= File::orderBy('code')->get();

		$data		= [
			'url'     => 'perils',
			'menu'    => 'risiko',
			'title'		=> "tambah baru",
			'files'		=> $files,
		];

		return view('peril.create', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$rules = array(
				'code'        		=> 'required|min:3|max:16|unique:ap_perils,code,NULL,id,deleted_at,NULL',
				'name'        		=> 'required|min:3',
				'effective_date' 	=> 'required',
				'description' 		=> '',
			);
			$attributeNames = array(
				'code'        		=> 'Kode',
				'name'        		=> 'Nama',
				'effective_date'	=> 'Tanggal Efektif',
				'description' 		=> 'Deskripsi',
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$peril             			= new Peril();
				$peril->uuid       			= Str::uuid()->toString();
				$peril->code       			= $request->code;
				$peril->name       			= $request->name;
				$peril->description			= $request->description;
				$peril->effective_date 	= $request->effective_date;
				$peril->save();

				// Peril - Files
				foreach ($request->peril_file as $file) {
					$peril_file						= new Peril_file();
					$peril_file->uuid 		= Str::uuid()->toString();
					$peril_file->peril_id = $peril->id;
					$peril_file->file_id 	= $file;
					$peril_file->save();
				}

				// Add Logs
				$this->add_log("Menambah Data Risiko [$peril->uuid]");
				DB::commit();
				return redirect()->route('peril.detail', ['uuid' => $peril->uuid])->with('pesan_success', "Data Risiko <b>" . $peril->code . "</b> Berhasil Ditambahkan");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('perils')->with('pesan_error', "Penambahan Data Risiko gagal");
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Peril $peril)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Peril $peril)
	{
		//
	}

	public function detail($uuid)
	{
		$peril		= Peril::with('peril_files')->where('uuid', $uuid)->first();
		$checkeds = $peril->peril_files->pluck('file_id')->toArray();
		$files 		= File::orderBy('code')->get();
		$data			= [
			'url'     	=> 'perils',
			'menu'    	=> 'risiko',
			'title'			=> "detail risiko",
			'peril'			=> $peril,
			'files'			=> $files,
			'checkeds'	=> $checkeds
		];

		return view('peril.detail', $data);
	}


	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Peril $peril)
	{
		DB::beginTransaction();
		try {
			Peril_file::where('peril_id', $peril->id)->delete();
			$peril->delete();
			$rules = array(
				'code'        		=> 'required|min:3|max:16|unique:ap_perils,code,NULL,id,deleted_at,NULL',
				'name'        		=> 'required|min:3',
				'effective_date' 	=> 'required',
				'description' 		=> '',
			);
			$attributeNames = array(
				'code'        		=> 'Kode',
				'name'        		=> 'Nama',
				'effective_date'	=> 'Tanggal Efektif',
				'description' 		=> 'Deskripsi',
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {

				$new_peril             			= new Peril();
				$new_peril->uuid       			= $peril->uuid ?? Str::uuid()->toString();
				$new_peril->code       			= $request->code;
				$new_peril->name       			= $request->name;
				$new_peril->description			= $request->description;
				$new_peril->effective_date 	= $request->effective_date;
				$new_peril->inactive_date 	= (empty($request->is_active)) ? date('Y-m-d H:i') : null;
				$new_peril->save();

				// Peril - Files
				foreach ($request->peril_file as $file) {
					$peril_file						= new Peril_file();
					$peril_file->uuid 		= Str::uuid()->toString();
					$peril_file->peril_id = $new_peril->id;
					$peril_file->file_id 	= $file;
					$peril_file->save();
				}

				// Update Limit
				Limit::where('peril_id', $peril->id)->update([
					'peril_id' => $new_peril->id
				]);

				// Add Logs
				$this->add_log("Pembaruan Data Risiko [$peril->uuid]");
				DB::commit();
				return redirect()->route('peril.detail', ['uuid' => $new_peril->uuid])->with('pesan_success', "Data Risiko <b>" . $peril->code . "</b> Berhasil Diperbarui");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('perils')->with('pesan_error', "Pembaruan Data Risiko gagal");
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Peril $peril)
	{
		DB::beginTransaction();
		try {
			Peril_file::where('peril_id', $peril->id)->delete();
			$peril->delete();

			$this->add_log('Menghapus Data Risiko = ' .  $peril->code . " [ " . $peril->uuid . " ]");
			DB::commit();
			return redirect()->route('perils')->with('pesan_success', "Hapus Data Risiko '$peril->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('perils')->with('pesan_error', "Hapus Data Risiko Gagal");
		}
	}

	public function limits($uuid)
	{
		$peril 		= Peril::with('limits')->where('uuid', $uuid)->firstOrFail();
		$limits 	= Limit::with(['position', 'peril', 'office'])
			->where('peril_id', $peril->id)
			->orderBy('amount')
			->get()
			->partition(fn($limit) => $limit->office_id == 1);

		$headquarters = $limits[0]->sortBy('amount');
		$limits 			= $limits[1]->sortBy(fn($item) => $item['office']['code'] ?? '');
		$data     		= [
			'url'     			=> 'perils',
			'menu'    			=> 'risiko',
			'title'   			=> "atur limit",
			'peril' 				=> $peril ?? collect(),
			'limits' 				=> $limits ?? collect(),
			'headquarters'	=> $headquarters ?? collect(),
		];

		return view('peril.limit', $data);
	}


	public function update_limit(Request $request)
	{
		$this->debug($request->all());
		die;
		DB::beginTransaction();
		try {
			$peril 		= Peril::select('id', 'uuid')->where('uuid', $request->uuid)->first();
			$office		= Office::select('id', 'code')->where('uuid', $request->office_uuid)->first();
			$position	= Position::select('id', 'code')->where('uuid', $request->position_uuid)->first();
			if (!empty($position)) {
				$limit 		= Limit::where('peril_id', $peril->id)->where('office_id', $office->id)
					->whereRelation('position', 'id', $position->id)->first();
			} else {
				$limit 		= Limit::where('peril_id', $peril->id)->where('office_id', $office->id)
					->first();
			}

			$new_limit             			= new Limit();
			$new_limit->uuid       			= $peril->uuid ?? Str::uuid()->toString();
			$new_limit->description 		= $request->description;
			$new_limit->amount					= str_replace('.', '', $request->amount);
			$new_limit->peril_id 				= $peril->id;
			$new_limit->office_id 			= $office->id;
			$new_limit->position_id 		= $limit->position_id ?? null;
			$new_limit->is_leaf 				= $limit->is_leaf ?? null;
			$new_limit->effective_date 	= date('Y-m-d H:i');
			$new_limit->inactive_date 	= ($request->is_active == 1) ? null :  date('Y-m-d H:i');
			$new_limit->save();
			$limit->delete();

			$this->add_log("Mengubah Data Limit [ " . $new_limit->uuid . " ]");
			DB::commit();
			return redirect()->route('limit.detail', ['uuid' => $peril->uuid])->with('pesan_success', "Mengubah Data Limit '$office->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('perils')->with('pesan_error', "Mengubah Data Limit Gagal");
		}
	}
}
