<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Institution;
use App\Models\Office;
use App\Models\Outlet;
use App\Models\Role;
use App\Models\User;
use App\Models\User_pgd;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterController extends Controller
{
  public function office(Request $request)
  {
    $offices  = Office::select('id', 'code', 'name')->with([
      'outlets:id,office_id',
      'claims:id,office_id'
    ])->orderBy('code')->get();
    $data     = [
      'url'     => 'offices',
      'menu'    => 'kantor operasional',
      'title'   => "data kantor operasional",
      'offices' => $offices,
    ];

    return view('master.office', $data);
  }

  public function outlet(Request $request)
  {
    $institutions = Institution::select('id', 'code', 'name')->orderBy('code')->get();

    $temp = Outlet::select('id', 'code', 'name', 'parent_id', 'level', 'office_id', 'institution_id')->with([
      'office:id,code,name',
      'institution:id,code,name',
      'childs' => function ($query) {
        $query->select('id', 'code', 'name', 'parent_id', 'level', 'office_id', 'institution_id')->with('office');
      },
      'childs.childs' => function ($query) {
        $query->select('id', 'code', 'name', 'parent_id', 'level', 'office_id', 'institution_id')->with('office');
      },
      'childs.childs.childs' => function ($query) {
        $query->select('id', 'code', 'name', 'parent_id', 'level', 'office_id', 'institution_id')->with('office');
      },
      'childs.childs.childs.childs' => function ($query) {
        $query->select('id', 'code', 'name', 'parent_id', 'level', 'office_id', 'institution_id')->with('office');
      }
    ])
      ->where('level', 1)
      ->orderBy('code')
      ->get();

    // $outlets  = $regions;
    $level    = 0;

    // Filter berdasarkan permintaan jika ada
    if (isset($request->institution)) {
      $institution  = $institutions->where('code', $request->institution)->first();
      $regions      = $temp->where('institution_id', $institution->id) ?? collect();
      $outlets      = $regions;
      $office       = $institution->office;
      $level        = 1;
    }

    if (isset($request->region)) {
      $region   = $temp->where('code', $request->region)->first();
      $areas    = $region?->childs ?? collect();
      $outlets  = $areas;
      $office   = $region->office;
      $level    = 2;
    }

    if (isset($request->area)) {
      $area     = $areas->where('code', $request->area)->first();
      $cbms     = $area?->childs ?? collect();
      $outlets  = $cbms;
      $office   = $area->office;
      $level    = 3;
    }

    if (isset($request->cbm)) {
      $cbm      = $cbms->where('code', $request->cbm)->first();
      $ubms     = $cbm?->childs ?? collect();
      $outlets  = $ubms;
      $office   = $cbm->office;
      $level    = 4;
    }

    if (isset($request->ubm)) {
      $ubm      = $ubms->where('code', $request->ubm)->first();
      $ults     = $ubm?->childs ?? collect();
      $outlets  = $ults;
      $office   = $ubm->office;
      $level    = 5;
    }



    $data     = [
      'url'           => 'outlets',
      'menu'          => 'outlet',
      'title'         => "data outlet pegadaian",
      'outlets'       => $outlets ?? collect(),
      'institutions'  => $institutions ?? collect(),
      'regions'       => $regions ?? collect(),
      'areas'         => $areas ?? collect(),
      'cbms'          => $cbms ?? collect(),
      'ubms'          => $ubms ?? collect(),
      'reqs'          => $request ?? collect(),
      'office'        => $office ?? collect(),
      'level'         => $level,
    ];

    return view('master.outlet', $data);
  }

  public function role(Request $request)
  {
    $roles  = Role::with('user_roles')->orderBy('id')->get();
    $data   = [
      'url'     => 'roles',
      'menu'    => 'pengguna',
      'title'   => "data pengguna sistem",
      'roles'   => $roles,
    ];

    return view('master.role', $data);
  }

  public function user(Request $request)
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
        'positions:id,name,office_id',
        'positions.office:id,code,name',
      )->select('id', 'code', 'name')->whereRelation('user_role.role', 'code', $request->role)->orderBy('code')->get();

      // Ambil Main Position
      $users->transform(function ($user) {
        $user->main_position = $user->positions->first() ?? []; // Jika tidak ada posisi, set kosong
        return $user;
      });

      // $users->sortBy('main_position.office');
    }

    $data  = [
      'url'     => 'roles',
      'menu'    => 'pengguna',
      'title'   => "hak akses",
      'users'   => $users ?? collect(),
    ];

    return view('user.askrida_user', $data);
  }
}
