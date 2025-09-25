<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\File;
use App\Models\Cause_file;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$files = File::select('id', 'uuid', 'code', 'name')->with('cause_files:id,file_id')->orderBy('id')->get();
		$data     = [
			'url'     => 'causes',
			'menu'    => 'risiko',
			'title'   => "berkas diperlukan",
			'files'   => $files,
		];

		// return view('master.file', $data);
		return view('file.index', $data);
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
		DB::beginTransaction();
		try {
			$rules = array(
				'code'				=> 'required|min:3|unique:ap_files,code,NULL,id,deleted_at,NULL',
				'name' 				=> 'required|min:3',
				'description'	=> '',
			);
			$attributeNames = array(
				'code' 				=> 'Kode',
				'name'				=> 'Nama Berkas',
				'description'	=> 'Keterangan Berkas'
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$file        				= new file();
				$file->uuid  				= Str::uuid()->toString();
				$file->code					= $request->code ?? null;
				$file->name					= $request->name ?? null;
				$file->description	= $request->description ?? null;
				$file->save();

				// Add Logs
				$this->add_log("Menambah Berkas [$file->uuid]");
				DB::commit();
				return redirect()->route('files')->with('pesan_success', "Penambahan Berkas '" . $file->name . "' Berhasil");
			} else {
				DB::rollback();
				$data_session = [
					'pesan_error' => "Penambahan Berkas Gagal",
					'modal_name'	=> 'modalAdd',
					'form_action'	=> $request->form_action,
				];
				return back()->with($data_session)->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return back()->withErrors($validator->errors()->first())->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(File $file)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(File $file)
	{
		$data = File::select('id', 'code', 'name', 'description')->where('id', $file->id)->first();
		return response()->json($data);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, File $file)
	{
		DB::beginTransaction();
		try {
			$file->delete();

			$rules = array(
				'new_code'				=> 'required|min:3|unique:ap_files,code,NULL,id,deleted_at,NULL',
				'new_name' 				=> 'required|min:3',
				'new_description'	=> '',
			);
			$attributeNames = array(
				'new_code' 				=> 'Kode',
				'new_name'				=> 'Nama Berkas',
				'new_description'	=> 'Keterangan Berkas'
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$new_file        				= new file();
				$new_file->uuid  				= $file->uuid;
				$new_file->code					= $request->new_code ?? null;
				$new_file->name					= $request->new_name ?? null;
				$new_file->description	= $request->new_description ?? null;
				$new_file->created_at 	= $file->created_at ?? date('Y-m-d H:i');
				$new_file->save();

				// Update Data Peril file
				Cause_file::where('file_id', $file->id)->update(['file_id' => $new_file->id]);

				// Add Logs
				$this->add_log("Mengubah Berkas [$new_file->uuid]");
				DB::commit();
				return redirect()->route('files')->with('pesan_success', "Perubahan Berkas '" . $new_file->name . "' Berhasil");
			} else {
				DB::rollback();
				$data_session = [
					'pesan_error' => "Perubahan Berkas Gagal",
					'modal_name'	=> 'modalEdit',
					'form_action'	=> $request->form_action,
				];
				return back()->with($data_session)->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return back()->withErrors($validator->errors()->first())->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(File $file)
	{
		DB::beginTransaction();
		try {
			$file->delete();
			$this->add_log('Menghapus Data Berkas = ' .  $file->name . " [ " . $file->uuid . " ]");
			DB::commit();

			return redirect()->route('files')->with('pesan_success', "Hapus Data Berkas '$file->name' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('files')->with('pesan_error', "Hapus Data Berkas Gagal");
		}
	}
}
