<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\User_pgd;
use App\Models\User_role;
use Illuminate\Http\Request;

class UserController extends Controller
{

	public function login()
	{
		return view('login');
	}

	public function login_action(Request $request)
	{
		$username	= $request->username;
		$password	= md5($request->password);
		$user_pgd = User_pgd::where('username', $username)->where('password', $password)->first();
		$user_aas	= User::where('username', $username)->where('password', $password)->first();

		// PEGADAIAN
		if ($user_pgd) {
			$user_role = User_role::with('user', 'role')->where('user_id', $user_pgd->id)->where('category', 2)->first();

			session(
				[
					'is_logged' => true,
					'user_uuid' => $user_pgd->uuid,
					'user_data' => $user_pgd,
					'user_role' => $user_role
				]
			);
			$this->add_log("Login System");
			return redirect()->route('businesses');
		}
		// AAS
		else if ($user_aas) {
			$user_role = User_role::with('user', 'role')->where('user_id', $user_aas->id)->where('category', 1)->first();

			// IN SYSTEM
			if ($user_role) {
				session(
					[
						'is_logged' => true,
						'user_uuid' => $user_aas->uuid,
						'user_data' => $user_aas,
						'user_role' => $user_role
					]
				);
				$this->add_log("Login System");
				return redirect()->route('businesses');
			} else {
				return redirect()->route('login')->with('pesan_error', "Akun anda tidak memiliki Otoritas");
			}
		} else {
			return redirect()->route('login')->with('pesan_error', "Kombinasi username dan password tidak sesuai");
		}
	}

	public function logout()
	{
		$this->add_log("Logout System");
		session()->flush();
		return redirect('login')->with('pesan_success', 'Logout berhasil');
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		if ($request->role == 'ADM_PARTNER') {
			$users = User_pgd::with([
				'outlet:id,code,name,level,parent_id,office_id',
				'outlet.office:id,code,name',
				'user_role:id,role_id,user_id',
				'user_role.role:id,code,name',
			])->select('id', 'code', 'name', 'outlet_id')->whereRelation('user_role.role', 'code', $request->role)->orderBy('code')->get();
		} else if ($request->role == 'VIEW_PARTNER') {
			$users = User_pgd::with([
				'outlet:id,code,name,level,parent_id,office_id',
				'outlet.office:id,code,name',
				'user_role:id,role_id,user_id',
				'user_role.role:id,code,name',
			])->select('id', 'code', 'name', 'outlet_id')->whereRelation('user_role.role', 'code', $request->role)->orderBy('code')->get();
		} else if (in_array($request->role, ['ADM_AAS', 'APV_STAFF', 'APV_HEAD'])) {
			$users = User::with(
				'user_role:id,role_id,user_id',
				'user_role.role:id,code,name',
				'positions',
				'positions.office'
			)->select('id', 'code', 'name')->whereRelation('user_role.role', 'code', $request->role)->orderBy('code')->get();

			$this->debug($users->toArray());
			die;
			// Ambil Main Position
			$users->transform(function ($user) {
				$user->main_position = $user->positions->first()->toArray() ?? []; // Jika tidak ada posisi, set kosong
				return $user;
			});
		}

		$data	= [
			'url'   => 'perils',
			'menu' 	=> 'risiko',
			'title'	=> "data risiko",
			'users'	=> $users,
		];

		return view('user.index', $data);
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
	public function show(User $user)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(User $user)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, User $user)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		//
	}
}
