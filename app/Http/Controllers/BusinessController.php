<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Cause;
use App\Models\Cause_file;
use App\Models\Institution;
use App\Models\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$businesses = Business::with('causes:id,business_id')->select('id', 'uuid', 'code', 'name')->orderBy('code')->get();
		$data				= [
			'url'     		=> 'businesses',
			'menu'    		=> 'kelas bisnis',
			'title'				=> "data kelas bisnis",
			'businesses'	=> $businesses ?? [],
		];

		return view('business.index', $data);
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
				'code'				=> 'required|min:3|unique:ap_businesses,code,NULL,id,deleted_at,NULL',
				'name' 				=> 'required|min:3',
				'description'	=> '',
			);
			$attributeNames = array(
				'code' 				=> 'Kode',
				'name'				=> 'Nama Kelas Bisnis',
				'description'	=> 'Keterangan Kelas Bisnis'
			);
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($attributeNames);
			if (!$validator->fails()) {
				$business        					= new Business();
				$business->uuid  					= Str::uuid()->toString();
				$business->code						= $request->code ?? null;
				$business->name						= $request->name ?? null;
				$business->description		= $request->description ?? null;
				$business->effective_date	= date('Y-m-d H:i:s');
				$business->save();

				// Add Logs
				$this->add_log("Menambah Kelas Bisnis [$business->uuid]");
				DB::commit();
				return redirect()->route('businesses')->with('pesan_success', "Penambahan Kelas Bisnis '" . $business->name . "' Berhasil");
			} else {
				DB::rollback();
				$data_session = [
					'pesan_error' => "Penambahan Kelas Bisnis Gagal",
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
	public function show(Business $business)
	{
		//
	}

	public function detail(Request $request, $uuid)
	{
		$business = Business::select('id', 'uuid', 'code', 'name', 'description', 'effective_date', 'inactive_date')
			->with([
				'causes',
				'causes.institution',
				'causes.cause_files'
			])->where('uuid', $uuid)->firstOrFail();

		if ($request->institution) {
			$causes = $business->causes->filter(function ($cause) use ($request) {
				return $cause->institution && $cause->institution->code === $request->institution;
			});
		}

		$institutions = Institution::orderBy('name')->get();
		$data					= [
			'url'     					=> 'businesses',
			'menu'    					=> 'kelas bisnis',
			'title'							=> "kelas bisnis",
			'business'					=> $business ?? collect(),
			'institutions'			=> $institutions ?? collect(),
			'institution_code'	=> $request->institution ?? collect(),
			'causes'						=> $causes ?? collect(),
		];

		return view('business.detail', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Business $business)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Business $business)
	{
		DB::beginTransaction();
		try {
			$business->delete();
			$rules = array(
				'code'						=> 'required|min:3|max:16|unique:ap_businesses,code,NULL,id,deleted_at,NULL',
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
				$new_business             		= new Business();
				$new_business->uuid       		= $business->uuid ?? Str::uuid()->toString();
				$new_business->code       		= $request->code;
				$new_business->name       		= $request->name;
				$new_business->description		= $request->description;
				$new_business->effective_date	= $business->effective_date;
				$new_business->inactive_date 	= (empty($request->is_active)) ? date('Y-m-d H:i') : null;
				$new_business->save();

				// Update Limit
				Cause::where('business_id', $business->id)->update([
					'business_id' => $new_business->id
				]);

				// Add Logs
				$this->add_log("Pembaruan Data Kelas Bisnis [$business->uuid]");
				DB::commit();
				return redirect()->route('business.detail', ['uuid' => $new_business->uuid])->with('pesan_success', "Data Kelas Bisnis <b>" . $new_business->code . "</b> Berhasil Diperbarui");
			} else {
				DB::rollback();
				return back()->withErrors($validator)->withInput();
			}
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('businesses')->with('pesan_error', "Pembaruan Data Kelas Bisnis Gagal");
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Business $business)
	{
		DB::beginTransaction();
		try {
			$causes = Cause::select('id', 'business_id')->where('business_id', $business->id)->pluck('id')->toArray();
			Cause::where('business_id', $business->id)->delete();
			Cause_file::whereIn('cause_id', $causes)->delete();
			Limit::whereIn('cause_id', $causes)->delete();
			$business->delete();

			$this->add_log('Menghapus Data Kelas Bisnis = ' .  $business->code . " [ " . $business->uuid . " ]");
			DB::commit();
			return redirect()->route('businesses')->with('pesan_success', "Hapus Data Kelas Bisnis '$business->code' Berhasil");
		} catch (\Exception $e) {
			DB::rollback();
			print($e);
			die;
			return redirect()->route('businesses')->with('pesan_error', "Hapus Data Kelas Bisnis Gagal");
		}
	}
}
