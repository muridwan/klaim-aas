<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Cause;
use App\Models\Cause_file;
use App\Models\Claim;
use App\Models\Document;
use App\Models\File;
use App\Models\Institution;
use App\Models\Limit;
use App\Models\Office;
use App\Models\Position;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CauseController extends Controller
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
	public function create(Request $request)
	{
		$institution 	= Institution::select('code')->where('code', $request->institution)->firstOrFail();
		$business 		= Business::where('uuid', $request->business)->firstOrFail();
		$files 				= File::orderBy('name')->get();
		$data			= [
			'url'  				=> 'causes',
			'menu' 				=> 'kelas bisnis',
			'title'				=> "tambah penyebab kerugian",
			'cause'				=> $cause ?? collect(),
			'files'				=> $files ?? collect(),
			'business'		=> $business ?? collect(),
			'institution'	=> $institution ?? collect(),
		];

		return view('cause.create', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$rules = array(
				'code'				=> 'required|min:3|unique:ap_causes,code,NULL,id,deleted_at,NULL',
				'name' 				=> 'required|min:3',
				'description'	=> '',
			);
			$attributeNames = array(
				'code' 				=> 'Kode',
				'name'				=> 'Nama Penyebab Kerugian',
				'description'	=> 'Keterangan Penyebab Kerugian'
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$business 							= Business::select('id')->where('uuid', $request->business_uuid)->firstOrFail();
				$institution 						= Institution::select('id')->where('code', $request->institution_code)->firstOrFail();
				$cause        					= new Cause();
				$cause->uuid  					= Str::uuid()->toString();
				$cause->code						= $request->code ?? null;
				$cause->name						= $request->name ?? null;
				$cause->description			= $request->description ?? null;
				$cause->business_id			= $business->id ?? null;
				$cause->effective_date 	= date('Y-m-d H:i:s');
				$cause->save();

				// Add Files
				foreach ($request->cause_files as $file) {
					$cause_file 								= new Cause_file();
					$cause_file->uuid 					= Str::uuid()->toString();
					$cause_file->cause_id 			= $cause->id;
					$cause_file->file_id 				= $file;
					$cause_file->institution_id = $institution->id ?? null;
					$cause_file->save();
				}

				// Add Limits
				$heads = User_role::with([
					'user_aas:id',
					'user_aas.positions:id,office_id'
				])->where('role_id', 6)->get();
				foreach ($heads as $head) {
					$limit 									= new Limit();
					$limit->uuid 						= Str::uuid()->toString();
					$limit->amount					= 0;
					$limit->cause_id 				= $cause->id;
					$limit->office_id				= $head->user_aas->positions->first()->office_id ?? null;
					$limit->position_id			= $head->user_aas->positions->first()->id ?? null;
					$limit->institution_id 	= $institution->id ?? null;
					$limit->save();
				}

				// Add Logs
				$this->add_log("Menambah Penyebab Kerugian [$cause->uuid]");
				DB::commit();
				return redirect()->route('cause.detail', ['uuid' => $cause->uuid])->with('pesan_success', "Penambahan Penyebab Kerugian '" . $cause->name . "' Berhasil");
			} else {
				DB::rollback();
				$data_session = [
					'pesan_error' => "Penambahan Penyebab Kerugian Gagal",
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
	public function show(Cause $cause)
	{
		//
	}

	public function detail($uuid)
	{
		$cause = Cause::select('id', 'uuid', 'code', 'name', 'description', 'business_id', 'institution_id', 'effective_date', 'inactive_date')->with([
			'cause_files',
			'institution:id,code',
			'business:id,uuid,code'
		])->where('uuid', $uuid)->firstOrFail();


		$checkeds =  (!empty($cause->cause_files)) ? $cause->cause_files->pluck('file_id')->toArray() : [];
		$files 		= File::orderBy('name')->get();
		$data			= [
			'url'  			=> 'causes',
			'menu'    	=> 'kelas bisnis',
			'title'			=> "ubah penyebab kerugian",
			'checkeds'	=> $checkeds ?? collect(),
			'cause'			=> $cause ?? collect(),
			'files'			=> $files ?? collect(),
		];

		return view('cause.detail', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Cause $cause)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Cause $cause)
	{
		DB::beginTransaction();
		try {
			$cause->delete();
			$rules = array(
				'code'						=> 'required|min:3|max:16|unique:ap_causes,code,NULL,id,deleted_at,NULL',
				'name'						=> 'required|min:3',
				'description'			=> '',
			);
			$attributeNames = array(
				'code'						=> 'Kode',
				'name'						=> 'Nama',
				'description'			=> 'Deskripsi',
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$new_cause             			= new Cause();
				$new_cause->uuid       			= $cause->uuid;
				$new_cause->code       			= $request->code;
				$new_cause->name       			= $request->name;
				$new_cause->description			= $request->description;
				$new_cause->effective_date	= $cause->effective_date;
				$new_cause->inactive_date 	= (empty($request->is_active)) ? date('Y-m-d H:i') : null;
				$new_cause->business_id 		= $cause->business_id ?? null;
				$new_cause->save();

				$causeFile = $request->cause_file ?? [];

				// if (count($causeFile) > 0) {
				$existingIds 			= Cause_file::where('cause_id', $cause->id)->pluck('file_id')->toArray();
				$missingIndexes 	= array_values(array_diff($causeFile, $existingIds));
				$removingIndexes 	= array_values(array_diff($existingIds, $causeFile));

				// New Files
				if (count($missingIndexes) > 0) {
					foreach ($missingIndexes as $file) {
						$cause_file 					= new Cause_file();
						$cause_file->uuid 		= Str::uuid()->toString();
						$cause_file->cause_id = $cause->id;
						$cause_file->file_id 	= $file;
						$cause_file->save();
					}
				}

				// Remove Files
				if (count($removingIndexes) > 0) {
					Cause_file::where('cause_id', $cause->id)->whereIn('file_id', $removingIndexes)->delete();
					Document::where('cause_id', $cause->id)->whereIn('file_id', $removingIndexes)->delete();
				}
				// }

				Cause_file::where('cause_id', $cause->id)->update(['cause_id' => $new_cause->id]);
				Limit::where('cause_id', $cause->id)->update(['cause_id' => $new_cause->id]);
				Claim::where('cause_id', $cause->id)->update(['cause_id' => $new_cause->id]);
				Cause::where('id', $cause->id)->update(['id' => $new_cause->id]);

				// Add Logs
				$this->add_log("Pembaruan Data Penyebab Kerugian [$cause->uuid]");
				DB::commit();
				return redirect()->route('cause.detail', ['uuid' => $new_cause->uuid])->with('pesan_success', "Data Penyebab Kerugian <b>" . $new_cause->code . "</b> Berhasil Diperbarui");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('causes')->with('pesan_error', "Pembaruan Data Penyebab Kerugian gagal");
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Cause $cause)
	{
		DB::beginTransaction();
		try {
			Cause_file::where('cause_id', $cause->id)->delete();
			Limit::where('cause_id', $cause->id)->delete();
			$cause->delete();

			$this->add_log('Menghapus Data Penyebab Kerugian = ' .  $cause->code . " [ " . $cause->uuid . " ]");
			DB::commit();
			return redirect()->route('business.detail', ['uuid' => $cause->business->uuid])->with('pesan_success', "Hapus Data Penyebab Kerugian '$cause->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('business.detail', ['uuid' => $cause->business->uuid])->with('pesan_error', "Hapus Data Penyebab Kerugian Gagal");
		}
	}

	public function limits($uuid)
	{
		$cause 		= Cause::with('limits')->where('uuid', $uuid)->firstOrFail();
		$limits 	= Limit::with(['position', 'cause', 'office'])
			->where('cause_id', $cause->id)
			->orderBy('amount')
			->get()
			->partition(fn($limit) => $limit->office_id == 1);

		$headquarters = $limits[0]->sortBy('amount');
		$limits 			= $limits[1]->sortBy(fn($item) => $item['office']['code'] ?? '');
		$data     		= [
			'url'     			=> 'causes',
			'menu'    			=> 'kelas bisnis',
			'title'   			=> "atur limit",
			'cause' 				=> $cause 				?? collect(),
			'limits' 				=> $limits 				?? collect(),
			'headquarters'	=> $headquarters 	?? collect(),
		];

		return view('cause.limit', $data);
	}

	public function update_limit(Request $request)
	{
		DB::beginTransaction();
		try {
			$cause 		= Cause::select('id', 'uuid')->where('uuid', $request->uuid)->first();
			$office		= Office::select('id', 'code')->where('uuid', $request->office_uuid)->first();
			$position	= Position::select('id', 'code')->where('uuid', $request->position_uuid)->first();
			if (!empty($position)) {
				$limit 		= Limit::where('cause_id', $cause->id)->where('office_id', $office->id)
					->whereRelation('position', 'id', $position->id)->first();
			} else {
				$limit 		= Limit::where('cause_id', $cause->id)->where('office_id', $office->id)->first();
			}

			$new_limit             			= new Limit();
			$new_limit->uuid       			= $cause->uuid ?? Str::uuid()->toString();
			$new_limit->description 		= $request->description;
			$new_limit->amount					= str_replace('.', '', $request->amount);
			$new_limit->cause_id 				= $cause->id;
			$new_limit->office_id 			= $office->id;
			$new_limit->position_id 		= $limit->position_id ?? null;
			$new_limit->is_leaf 				= $limit->is_leaf ?? null;
			$new_limit->effective_date 	= date('Y-m-d H:i');
			$new_limit->inactive_date 	= ($request->is_active == 1) ? null :  date('Y-m-d H:i');
			$new_limit->save();
			$limit->delete();

			$this->add_log("Mengubah Data Limit [ " . $new_limit->uuid . " ]");
			DB::commit();
			return redirect()->route('cause.limits', ['uuid' => $cause->uuid])->with('pesan_success', "Mengubah Data Limit '$office->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('causes')->with('pesan_error', "Mengubah Data Limit Gagal");
		}
	}
}
