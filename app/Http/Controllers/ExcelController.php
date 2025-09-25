<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Outlet;
use App\Models\User;
use App\Models\User_pgd;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{

  public function formExcel(Request $request)
  {
    return view('form-excel');
  }

  public function readExcelWithCustomRange($filePath, $startRow = 2, $endRow, $columns = [])
  {
    // Memuat file Excel
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();

    // Mendapatkan batas baris dan kolom
    if (empty($endRow)) {
      $highestRow     = $worksheet->getHighestRow(); // Baris terakhir
    } else {
      $highestRow     = $endRow; // Parameter
    }

    $highestColumn  = $worksheet->getHighestColumn(); // Kolom terakhir (misal: 'D')

    // Jika tidak ada kolom spesifik, gunakan semua kolom
    if (empty($columns)) {
      $columns = range('A', $highestColumn);
    }

    // Konversi setiap baris dalam rentang menjadi array
    $dataArray = [];
    for ($row = $startRow; $row <= $highestRow; $row++) {
      $rowData = [];
      foreach ($columns as $column) {
        $cellValue = $worksheet->getCell($column . $row)->getValue();
        $rowData[] = $cellValue;
      }
      $dataArray[] = $rowData;
    }

    return $dataArray;
  }

  public function uploadExcel(Request $request)
  {
    DB::beginTransaction();
    try {
      $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
      ]);

      $file = $request->file('file');
      $filePath = $file->getPathname();

      // Misalnya, hanya ingin mengambil kolom A, B, dst. mulai dari baris ke-3
      $columns  = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
      // $columns  = ['F', 'G'];
      $startRow = 2;
      $endRow   = null;

      $dataArray = $this->readExcelWithCustomRange($filePath, $startRow, $endRow, $columns);
      // $this->debug($dataArray);
      // die;

      // Nama-nama yang akan digunakan sebagai key
      $fruits = [
        "OTL_code",
        "OTL_name",
        "UBM_code",
        "UBM_name",
        "CBM_code",
        "CBM_name",
        "AREA",
        "KANWIL",
        "KOP_name",
        "KOP_code",
      ];
      // Inisialisasi array hasil
      $uniquePerIndex = [];

      // Loop untuk setiap elemen dalam array
      foreach ($dataArray as $row) {
        foreach ($row as $index => $value) {
          // Ganti key dengan nama 
          $fruitKey = $fruits[$index];  // Ganti key dengan nama
          if (!isset($uniquePerIndex[$fruitKey])) {
            $uniquePerIndex[$fruitKey] = [];
          }
          if (!in_array($value, $uniquePerIndex[$fruitKey])) {
            $uniquePerIndex[$fruitKey][] = $value;
          }
        }
      }

      // $kanwils    = $uniquePerIndex['KANWIL'];
      // $areas      = $uniquePerIndex['AREA'];
      // $cbm_codes  = $uniquePerIndex['CBM_code'];
      // $cbm_names  = $uniquePerIndex['CBM_name'];
      // $ubm_codes  = $uniquePerIndex['UBM_code'];
      // $ubm_names  = $uniquePerIndex['UBM_name'];
      // $otl_codes  = $uniquePerIndex['OTL_code'];
      // $otl_names  = $uniquePerIndex['OTL_name'];
      // $kop_codes  = $uniquePerIndex['KOP_code'];

      // Kanwil
      $kanwil_done  = [];
      $i            = 1;
      foreach ($dataArray as $key => $value) {
        if (!in_array($value[7], $kanwil_done)) {
          $counter    = $i;
          if ($i > 9) {
            $counter  = "0" . $i;
          } else {
            $counter  = "00" . $i;
          }

          $office                 = Office::select('id')->where('code', $dataArray[$key][9])->first();
          $kanwil                 = new Outlet();
          $kanwil->uuid           = Str::uuid()->toString();
          $kanwil->level          = 1;
          $kanwil->code           = "KANWIL-" . $counter;
          $kanwil->office_id      = $office->id ?? null;
          $kanwil->name           = $value[7];
          $kanwil->institution_id = 1;
          $kanwil->save();
          $kanwil_done[]      = $value[7];
          $i++;

          // Insert User / Acoount
          $user       = new User_pgd();
          $user->uuid = Str::uuid()->toString();
          $user->code = $kanwil->code;
          $user->name = "Pengguna " . $kanwil->name;
          $user->email = null;
          $user->phone = null;
          $user->username = trim(strtolower($kanwil->code));
          $user->password = md5($kanwil->code);
          $user->bypass_code = str_replace('-', '', Str::uuid()->toString());
          $user->get_notification = 1;
          $user->photo = null;
          $user->reset_at = null;
          $user->outlet_id = $kanwil->id;
          $user->save();

          // Role
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 2;
          $user_role->user_id   = $user->id;
          $user_role->category  = 2;
          $user_role->save();
        }
      }
      echo "KANWIL DONE <br>";


      // Area
      $areas_done = [];
      $i          = 1;
      foreach ($dataArray as $key => $value) {
        if (!in_array($value[6], $areas_done)) {
          $counter    = $i;
          if ($i > 9) {
            $counter  = "0" . $i;
          } else {
            $counter  = "00" . $i;
          }

          $parent               = Outlet::select('id', 'office_id', 'name')->where('name', $value[7])->first();
          $area                 = new Outlet();
          $area->uuid           = Str::uuid()->toString();
          $area->level          = 2;
          $area->code           = "AREA-" . $counter;
          $area->parent_id      = $parent->id ?? null;
          $area->office_id      = $parent->office_id ?? null;
          $area->name           = $value[6];
          $area->institution_id = 1;
          $area->save();
          $areas_done[]     = $value[6];
          $i++;

          // Insert User / Acoount
          $user       = new User_pgd();
          $user->uuid = Str::uuid()->toString();
          $user->code = $area->code;
          $user->name = "Pengguna " . $area->name;
          $user->email = null;
          $user->phone = null;
          $user->username = trim(strtolower($area->code));
          $user->password = md5($area->code);
          $user->bypass_code = str_replace('-', '', Str::uuid()->toString());
          $user->get_notification = 1;
          $user->photo = null;
          $user->reset_at = null;
          $user->outlet_id = $area->id;
          $user->save();

          // Role
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 2;
          $user_role->user_id   = $user->id;
          $user_role->category  = 2;
          $user_role->save();
        }
      }
      echo "AREA DONE <br>";

      // CBM
      $CBM_done = [];
      $i        = 1;
      foreach ($dataArray as $key => $value) {
        if (!in_array($value[4], $CBM_done)) {
          $parent               = Outlet::select('id', 'office_id', 'name')->where('name', $value[6])->first();
          $cbm                  = new Outlet();
          $cbm->uuid            = Str::uuid()->toString();
          $cbm->level           = 3;
          $cbm->code            = $value[4];
          $cbm->parent_id       = $parent->id ?? null;
          $cbm->office_id       = $parent->office_id ?? null;
          $cbm->name            = $value[5];
          $cbm->institution_id  = 1;
          $cbm->save();
          $CBM_done[]     = $value[4];
          $i++;

          // Insert User / Acoount
          $user       = new User_pgd();
          $user->uuid = Str::uuid()->toString();
          $user->code = $cbm->code;
          $user->name = "Pengguna " . $cbm->name;
          $user->email = null;
          $user->phone = null;
          $user->username = trim(strtolower($cbm->code));
          $user->password = md5($cbm->code);
          $user->bypass_code = str_replace('-', '', Str::uuid()->toString());
          $user->get_notification = 1;
          $user->photo = null;
          $user->reset_at = null;
          $user->outlet_id = $cbm->id;
          $user->save();

          // Role
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 2;
          $user_role->user_id   = $user->id;
          $user_role->category  = 2;
          $user_role->save();
        }
      }
      echo "CBM DONE <br>";


      // UBM
      $UBM_done = [];
      $i        = 1;
      foreach ($dataArray as $key => $value) {
        if (!in_array($value[2], $UBM_done)) {
          $parent               = Outlet::select('id', 'office_id', 'name')->where('code', $value[4])->first();
          $ubm                  = new Outlet();
          $ubm->uuid            = Str::uuid()->toString();
          $ubm->level           = 4;
          $ubm->code            = $value[2];
          $ubm->parent_id       = $parent->id ?? null;
          $ubm->office_id       = $parent->office_id ?? null;
          $ubm->name            = $value[3];
          $ubm->institution_id  = 1;
          $ubm->save();
          $UBM_done[]     = $value[2];
          $i++;

          // Insert User / Acoount
          $user       = new User_pgd();
          $user->uuid = Str::uuid()->toString();
          $user->code = $ubm->code;
          $user->name = "Pengguna " . $ubm->name;
          $user->email = null;
          $user->phone = null;
          $user->username = trim(strtolower($ubm->code));
          $user->password = md5($ubm->code);
          $user->bypass_code = str_replace('-', '', Str::uuid()->toString());
          $user->get_notification = 1;
          $user->photo = null;
          $user->reset_at = null;
          $user->outlet_id = $ubm->id;
          $user->save();

          // Role
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 2;
          $user_role->user_id   = $user->id;
          $user_role->category  = 2;
          $user_role->save();
        }
      }
      echo "UBM DONE <br>";

      // Outlet
      $OTL_done = [];
      $i        = 1;
      foreach ($dataArray as $key => $value) {
        if (!in_array($value[0], $OTL_done)) {
          $parent               = Outlet::select('id', 'office_id', 'name')->where('code', $value[2])->first();
          $otl                  = new Outlet();
          $otl->uuid            = Str::uuid()->toString();
          $otl->level           = 5;
          $otl->code            = $value[0];
          $otl->parent_id       = $parent->id ?? null;
          $otl->office_id       = $parent->office_id ?? null;
          $otl->name            = $value[1];
          $otl->institution_id  = 1;
          $otl->save();
          $OTL_done[]     = $value[0];
          $i++;

          // Insert User / Acoount
          $user       = new User_pgd();
          $user->uuid = Str::uuid()->toString();
          $user->code = $otl->code;
          $user->name = "Pengguna " . $otl->name;
          $user->email = null;
          $user->phone = null;
          $user->username = trim(strtolower($otl->code));
          $user->password = md5($otl->code);
          $user->bypass_code = str_replace('-', '', Str::uuid()->toString());
          $user->get_notification = 1;
          $user->photo = null;
          $user->reset_at = null;
          $user->outlet_id = $otl->id;
          $user->save();

          // Role
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 1;
          $user_role->user_id   = $user->id;
          $user_role->category  = 2;
          $user_role->save();
        }
      }
      echo "OUTLET DONE <br>";





      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
      return redirect()->route('causes')->with('pesan_error', "Penambahan Data Risiko gagal");
    }
  }
}
