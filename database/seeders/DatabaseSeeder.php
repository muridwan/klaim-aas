<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\File;
use App\Models\Limit;
use App\Models\Occupation;
use App\Models\Cause;
use App\Models\Cause_file;
use App\Models\Institution;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\User_position;
use App\Models\User_role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */

  public function run(): void
  {
    DB::beginTransaction();
    try {

      $roles = [
        ['ADM_PARTNER',    'Admin Sumbis',           'Melakukan pengajuan klaim'],
        ['VIEW_PARTNER',  'Region & Kanwil',         'Melihat data klaim sesuai region yang dibawahinya'],
        ['VIEW',           'Pemasaran3 & KP Sumbis',  'Melihat Semua klaim yg diajukan'],
        ['ADM_AAS',        'Kantor Pemasar',         'Mengajukan, dan Analisis klaim berdasarkan areanya'],
        ['APV_STAFF',      'Analisis Klaim',         'Analisis Klaim dan memberikan rekomendasi'],
        ['APV_HEAD',      'Penyelsaian Klaim',       'Penyelesaian klaim sesuai wewenang & limit'],
      ];

      foreach ($roles as $i) {
        $role                = new Role();
        $role->uuid         = Str::uuid()->toString() ?? null;
        $role->code         = $i[0] ?? null;
        $role->name         = $i[1] ?? null;
        $role->description   = $i[2] ?? null;
        $role->save();
      }

      // USER X ROLE
      $all    = User::with('main_position', 'main_position.office')->get();

      // Kepala Cabang
      $kacabs = $all->filter(function ($item) {
        return str_contains($item, 'Kepala Cabang');
      });
      foreach ($kacabs as $kacab) {
        $user_role            = new User_role();
        $user_role->uuid      = Str::uuid()->toString();
        $user_role->role_id   = 6;
        $user_role->user_id   = $kacab->id;
        $user_role->category   = 1;
        $user_role->save();
      }

      // Pelaksana Cabang
      $cabangs = $all->filter(function ($item) {
        return $item['main_position']->contains(function ($position) {
          return $position->rank_id === 9 && $position->office->category === 2;
        });
      });
      foreach ($cabangs as $cabang) {
        $user_role            = new User_role();
        $user_role->uuid      = Str::uuid()->toString();
        $user_role->role_id   = 5;
        $user_role->user_id   = $cabang->id;
        $user_role->category   = 1;
        $user_role->save();
      }

      // Staff Pemasaran
      $pemasarans = $all->filter(function ($item) {
        return $item['main_position']->contains(function ($position) {
          return  $position->office->category === 3;
        });
      });

      foreach ($pemasarans as $pemasaran) {
        $user_role            = new User_role();
        $user_role->uuid      = Str::uuid()->toString();
        $user_role->role_id   = 4;
        $user_role->user_id   = $pemasaran->id;
        $user_role->category   = 1;
        $user_role->save();
      }

      // Kantor Pusat, Klaim
      $pusat = $all->filter(function ($item) {
        return $item['main_position']->contains(function ($position) {
          return  $position->office->category === 1;
        });
      });

      $done = [];
      foreach ($pusat as $key => $value) {
        if (str_contains($value->main_position[0]->name, 'Pelaksana Klaim Asport 2')) {
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 5;
          $user_role->user_id   = $value->id;
          $user_role->category   = 1;
          $user_role->save();
        }

        if (str_contains($value->main_position[0]->name, 'Pemasaran 3')) {
          $user_role            = new User_role();
          $user_role->uuid      = Str::uuid()->toString();
          $user_role->role_id   = 3;
          $user_role->user_id   = $value->id;
          $user_role->category   = 1;
          $user_role->save();
        }

        // Kepala Pusat
        $heads = [73, 56, 24];
        foreach ($heads as $head) {
          if (!in_array($head, $done)) {
            $user_role            = new User_role();
            $user_role->uuid      = Str::uuid()->toString();
            $user_role->role_id   = 6;
            $user_role->user_id   = $head;
            $user_role->category   = 1;
            $user_role->save();
            $done[] = $head;
          }
        }
      }

      // Institutions
      $institutions = [
        [
          'code'         => 'PGD',
          'name'         => 'Pegadaian',
          'phone'       => '021-80635162',
          'email'       => 'customer.care@pegadaian.co.id',
          'address'     => 'Jl. Kramat Raya 162 Jakarta Pusat 10430 Indonesia',
          'description' => 'PT Pegadaian adalah anak usaha dari Bank Rakyat Indonesia yang terutama bergerak di bidang gadai.',
        ],
        [
          'code'         => 'NAGARI',
          'name'         => 'Bank Nagari',
          'phone'       => '075131577',
          'email'       => 'sekper@banknagari.co.id',
          'address'     => 'Jl. Pemuda No.21, Padang, Sumatera Barat',
          'description' => 'Bank Nagari adalah satu-satunya bank milik pemerintah daerah Sumatera Barat yang bertujuan untuk meningkatkan perekonomian masyarakat khususnya di Sumatera Barat.',
        ],
        [
          'code'         => 'DKI',
          'name'         => 'Bank DKI',
          'phone'       => '02180655555',
          'email'       => 'corsec@bankdki.co.id',
          'address'     => 'Gedung Prasada Sasana Karya Jl. Suryopranoto No.8 Jakarta Pusat 10130',
          'description' => 'Bank DKI merupakan Bank Umum KBMI II yang kepemilikan sahamnya dimiliki oleh Pemerintah Provinsi DKI Jakarta (99,98%) dan Perumda Pasar Jaya (0,02%).',
        ],
      ];
      foreach ($institutions as $key => $value) {
        $institution              = new Institution();
        $institution->uuid         = Str::uuid()->toString() ?? null;
        $institution->code        = $value['code'] ?? null;
        $institution->name        = $value['name'] ?? null;
        $institution->phone        = $value['phone'] ?? null;
        $institution->email        = $value['email'] ?? null;
        $institution->address      = $value['address'] ?? null;
        $institution->description  = $value['description'] ?? null;
        $institution->save();
      }


      // REQ FILES
      $files = [
        'Analisa Klaim Sementara (PLA)',
        'Analisa Cabang',
        'Surat Pengajuan Klaim',
        'Berita Acara Kronologis Macet',
        'KTP & KK',
        'Akad Pembiayaan',
        'Polis Asuransi',
        'Kartu Register',
        'Rekening Koran',
        'Jadwal Angsuran',
        'Surat Peringatan Pembiayaan 1-3',
        'Kronologis Kematian dari ahli waris diatas materai',
        'Surat Keterangan Ahli Waris',
        'Akta Kematian',
        'Resume Medis & Surat Keterangan Dokter',
        'Surat Pengangkatan Pegawat Tetap',
        'Surat Pemutusan Hubungan Kerja',
        'Surat Peringatan / Teguran Kerja 1-3'
      ];

      foreach ($files as $key => $value) {
        $file           = new File();
        $file->uuid     = Str::uuid()->toString() ?? null;
        $file->code     = 'F00' . $key + 1 ?? null;
        $file->name     = $value ?? null;
        $file->save();
      }


      $COBs = [
        ['COB-001', 'Kebakaran'],
        ['COB-002', 'Kendaraan'],
        ['COB-003', 'Kecelakaan Diri (PA)'],
        ['COB-004', 'Travel Umroh'],
        ['COB-005', 'CIS / CIT / CICB'],
        ['COB-006', 'Kebongkaran'],
        ['COB-007', 'Pengangkutan'],
        ['COB-008', 'Rekayasa (CAR/EAR/MB/CPM/EEI)'],
        ['COB-009', 'Tanggung Gugat'],
        ['COB-010', 'Marnine Hull'],
        ['COB-011', 'Asprot'],
      ];
      foreach ($COBs as $i) {
        $business                  = new Business();
        $business->uuid           = Str::uuid()->toString() ?? null;
        $business->code           = $i[0] ?? null;
        $business->name           = $i[1] ?? null;
        $business->effective_date  = date('Y-m-d H:i');
        $business->save();
      }

      $causes = [
        ['COL-001', 'Kebakaran', 1],
        ['COL-002', 'Kendaraan', 2],
        ['COL-003', 'Kecelakaan Diri (PA)', 3],
        ['COL-004', 'Travel Umroh', 4],
        ['COL-005', 'CIS / CIT / CICB', 5],
        ['COL-006', 'Kebongkaran', 6],
        ['COL-007', 'Pengangkutan', 7],
        ['COL-008', 'Rekayasa (CAR/EAR/MB/CPM/EEI)', 8],
        ['COL-009', 'Tanggung Gugat', 9],
        ['COL-010', 'Marnine Hull', 10],
        ['COL-011', 'Meninggal Dunia', 11],
        ['COL-012', 'PHK / PAW / Macet', 11],
      ];

      foreach ($causes as $i) {
        $cause                  = new Cause();
        $cause->uuid             = Str::uuid()->toString() ?? null;
        $cause->code             = $i[0] ?? null;
        $cause->name             = $i[1] ?? null;
        $cause->business_id     = $i[2] ?? null;
        $cause->effective_date   = date('Y-m-d H:i');
        $cause->institution_id  = 1;
        $cause->save();

        // foreach ($files as $key => $value) {
        for ($j = 1; $j <= 5; $j++) {
          $cause_file               = new Cause_file();
          $cause_file->uuid         = Str::uuid()->toString() ?? null;
          $cause_file->cause_id     = $cause->id;
          // $cause_file->file_id 			= $key + 1;
          $cause_file->file_id       = $j;
          $cause_file->save();
        }
      }


      // LIMIT
      // Selain meninggal
      $offices   = [2, 3, 4, 5, 11, 12, 13, 15, 20, 23, 26, 27];
      $amounts   = [40000000, 10000000, 12500000, 15000000, 25000000, 12500000, 12500000, 12500000, 5000000, 0, 30000000];
      foreach ($offices as $office) {

        // Kepala Cabang
        $head   = Position::where('office_id', $office)->where('code', 'like', '%BRANCH_MGR%')->first();

        foreach ($amounts as $key => $value) {
          $lim                   = new Limit();
          $lim->uuid             = Str::uuid()->toString() ?? null;
          $lim->amount           = $value;
          $lim->cause_id         = $key + 1;
          $lim->office_id       = $office;
          $lim->position_id     = $head->id ?? null;
          $lim->effective_date   = date('Y-m-d H:i:s');
          $lim->save();
        }
      }

      // Meninggal
      $deaths = [
        [
          'cause'    => 12,
          'amount'  => 175000000,
          'offices'  => [2],
        ],
        [
          'cause'    => 12,
          'amount'  => 100000000,
          'offices'  => [12, 3, 26, 5, 27],
        ],
        [
          'cause'    => 12,
          'amount'  => 75000000,
          'offices'  => [11, 15, 13, 14, 20, 23],
        ],
      ];
      foreach ($deaths as $death) {
        foreach ($death['offices'] as $office) {

          // Kepala Cabang
          $head   = Position::select('id')->where('office_id', $office)->where('code', 'like', '%BRANCH_MGR%')->first();

          $lim                   = new Limit();
          $lim->uuid             = Str::uuid()->toString() ?? null;
          $lim->amount           = $death['amount'];
          $lim->cause_id         = $death['cause'];
          $lim->office_id       = $office;
          $lim->position_id     = $head->id ?? null;
          $lim->effective_date   = date('Y-m-d H:i:s');
          $lim->save();
        }
      }

      // Limit Pusat
      // Kasie [DokIm] , Kabag [Bu Ira], Kadiv [Bpk Bedjo]
      $heads = [
        [
          'nirp'     => '03930093',
          'limits'  => [100, 40, 20, 20, 40, 20, 20, 20, 20, 20, 150, 100]
        ],
        [
          'nirp'     => '03780049',
          'limits'  => [200, 80,  60, 60, 80, 40, 40, 40, 40, 40, 300, 200]
        ],
        [
          'nirp'     => '03690024',
          'limits'  => [500, 200, 100, 100, 150, 100, 150, 200, 100, 100, 500, 300]
        ]
      ];

      foreach ($heads as $head) {
        $user           = User::select('id')->where('code', $head['nirp'])->first();
        $user_position   = User_position::with('position')->where('user_id', $user->id)->first();

        foreach ($head['limits'] as $key => $value) {
          $lim                   = new Limit();
          $lim->uuid             = Str::uuid()->toString() ?? null;
          $lim->amount           = $value * 1000000;
          $lim->cause_id         = $key + 1;
          $lim->office_id       = $user_position->position->office_id;
          $lim->position_id     = $user_position->position->id;
          $lim->effective_date   = date('Y-m-d H:i:s');
          $lim->save();
        }
      }

      // Pekerjaan
      $occupations = [
        [
          'code'  => 'DPRD',
          'name'  => 'Anggota DPRD'
        ],
        [
          'code'  => 'BUMD',
          'name'  => 'Pegawai BUMD'
        ],
        [
          'code'  => 'BUMN',
          'name'  => 'Pegawai BUMN'
        ],
        [
          'code'  => 'SWASTA',
          'name'  => 'Pegawai Swasta / Badan Usaha Swasta Bonafit'
        ],
        [
          'code'  => 'TNI-POLRI',
          'name'  => 'Tentara / Polisi'
        ],
        [
          'code'  => 'PRAPEN',
          'name'  => 'Prapensiunan'
        ],
        [
          'code'  => 'PNS',
          'name'  => 'Pegawai Negeri Sipil'
        ],
        [
          'code'  => 'HON-KON-OUTS',
          'name'  => 'Pegawai Honorer / Kontrak / Outsourcing'
        ],
        [
          'code'  => 'CPNS',
          'name'  => 'Calon Pegawai Negeri Sipil'
        ],
        [
          'code'  => 'KEPLING',
          'name'  => 'Kepala Lingkungan'
        ],
        [
          'code'  => 'PERDES',
          'name'  => 'Perangkat Desa'
        ],
        [
          'code'  => 'KADES',
          'name'  => 'Kepala Desa'
        ],
        [
          'code'  => 'PROFESI',
          'name'  => 'Profesional / Wiraswasta'
        ],
        [
          'code'  => 'BLU-BLUD',
          'name'  => 'Badan Layanan Umum / Badan Layanan Umum Daerah'
        ],
        [
          'code'  => 'PPPK',
          'name'  => 'Pegawai Pemerintah dengan Perjanjian Kerja'
        ],

      ];

      foreach ($occupations as $occ) {
        $occupation           = new Occupation();
        $occupation->uuid     = Str::uuid()->toString() ?? null;
        $occupation->code     = $occ['code'];
        $occupation->name      = $occ['name'];
        $occupation->save();
      }

      // $claims = [
      // 	[
      // 		'code'					=> 'APP-001',
      // 		'name'					=> 'Naruto Uzumaki',
      // 		'work'					=> 'Hokage',
      // 		'start_date'		=> '2023-01-01',
      // 		'end_date'			=> '2033-01-01',
      // 		'incident_date'	=> '2024-01-01',
      // 		'amount'				=> 100000000,
      // 		'description'		=> null,
      // 		'response'			=> null,
      // 		'status'				=> 0,
      // 		'decision'			=> null,
      // 		'cause_id'			=> 12,
      // 		'decided_by'		=> null,
      // 		'office_id'			=> null,
      // 		'created_by'		=> 1203,
      // 		'created_at'		=> '2024-12-31 14:00:00.000',
      // 		'updated_at'		=> '2024-12-31 14:00:00.000'
      // 	],
      // 	[
      // 		'code'					=> 'APP-002',
      // 		'name'					=> 'Sasuke Uchiha',
      // 		'work'					=> 'Anbu',
      // 		'start_date'		=> '2023-12-12',
      // 		'end_date'			=> '2033-12-12',
      // 		'incident_date'	=> '2024-12-12',
      // 		'amount'				=> 75000000,
      // 		'description'		=> null,
      // 		'response'			=> null,
      // 		'status'				=> 0,
      // 		'decision'			=> null,
      // 		'cause_id'			=> 12,
      // 		'decided_by'		=> null,
      // 		'office_id'			=> null,
      // 		'created_by'		=> 1202,
      // 		'created_at'		=> '2024-12-31 14:00:00.000',
      // 		'updated_at'		=> '2024-12-31 14:00:00.000'
      // 	]
      // ];

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
    }
  }
}
