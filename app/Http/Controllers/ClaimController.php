<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Cause_file;
use App\Models\Claim;
use App\Models\Document;
use App\Models\Limit;
use App\Models\Occupation;
use App\Models\Office;
use App\Models\Position;
use App\Models\Recommendation;
use App\Models\User;
use App\Models\User_role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $statuses = ['submission', 'review', 'decision', 'payment'];

    Session::forget('validation_data');
    if (strtolower($request->status) == 'submission') {
      $url     = route('claims', ['status' => 'submission']);
      $menu   = 'pengajuan';
      $title   = 'data pengajuan klaim';
      $claims = Claim::with('office:id,name')->where('status', 0)->orderBy('code')->get();
    } else if (strtolower($request->status) == 'review') {
      $url     = route('claims', ['status' => 'review']);
      $menu   = 'peninjauan';
      $title   = 'data peninjauan klaim';
      $claims = Claim::with('office:id,name')->where('status', 1)->orderBy('code')->get();
    } else if (strtolower($request->status) == 'decision') {
      $url     = route('claims', ['status' => 'decision']);
      $menu   = 'keputusan';
      $title   = 'data keputusan klaim';
      $claims = Claim::with('office:id,name')->where('status', 2)->orderBy('code')->get();
    } else if (strtolower($request->status) == 'payment') {
      $url     = route('claims', ['status' => 'payment']);
      $menu   = 'pembayaran';
      $title   = 'data pembayaran klaim';
      $claims = Claim::with('office:id,name')->where('status', 3)->orderBy('code')->get();
    } else {
      return redirect()->route('claims', ['status' => 'submission']);
    }
    $data   = [
      'url'     => $url ?? '',
      'menu'    => $menu ?? '',
      'title'   => $title ?? '',
      'claims'  => $claims,
    ];

    return view('claim.index', $data);
  }

  public function process(Request $request)
  {
    // 
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $validation_data = session('validation_data');

    if (empty($validation_data)) {
      return redirect()->route('claims');
    }
    $occupations  = Occupation::select('id', 'name')->orderBy('name')->get();
    $causes       = Cause::select('id', 'name', 'business_id')->with('business:id,name')->orderBy('name')->get();

    $data  = [
      'url'         => 'offices',
      'menu'        => 'pengajuan',
      'title'       => "pengajuan klaim",
      'data'        => $validation_data,
      'occupations' => $occupations ?? [],
      'causes'      => $causes      ?? [],
    ];

    return view('claim.create', $data);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {
      $rules = array(
        // 'incident_date'     => 'required|date_format:d-m-Y',
        'incident_date'     => 'required',
        'submission_amount' => 'required',
        'occupation'        => 'required',
        'cause'             => 'required',
        'description'       => 'required'
      );
      $attributeNames = array(
        'incident_date'     => 'Tanggal Kejadian',
        'submission_amount' => 'Nilai Pengajuan',
        'occupation'        => 'Pekerjaan',
        'cause'             => 'Penyebab Klaim',
        'description'       => 'Keterangan Penyebab Klaim'
      );
      $validator = Validator::make($request->all(), $rules);
      $validator->setAttributeNames($attributeNames);
      if (!$validator->fails()) {

        // Cek Data
        $claim_amount     = (int)str_replace('.', '', $request->submission_amount);
        // $incident_date     = Carbon::createFromFormat('d-m-Y', $request->incident_date)->format('Y-m-d');
        $validation_data  = session('validation_data');
        $office           = Office::select('id', 'code', 'category')->where('code', $validation_data->IDBranch)->first();
        $order            = Claim::select('order')->orderByDesc('order')->withTrashed()->first()->order ?? 0;
        if ($office->category == 3) {
          $branch          = Limit::with([
            'position',
            'office'
          ])->where('cause_id', $request->cause)
            ->whereRelation('office', 'code', '=', '000')->first();
        } else {
          $branch         = Limit::with('position')->where('cause_id', $request->cause)->where('office_id', $office->id)->first();
        }

        // PENENTUAN PENANGANAN KLAIM
        if ($branch->amount >= $claim_amount) {
          $position = $branch->position;
        } else {
          $code   = '000';
          $heads  = Limit::with('position')
            ->where('cause_id', $request->cause)
            ->whereRelation('office', 'code', '=', $code)
            ->orderBy('amount')->get();

          $head   = $heads->first(function ($item) use ($claim_amount) {
            return $item['amount'] >= $claim_amount;
          });

          // Jika Pengajuan Melebihi limit siapapun
          if (empty($head)) {
            $last = $heads->last();
            if ($last->is_leaf == 1) {
              $position =  $last->position;
            } else {
              $position  = Position::select('id')->where('code', 'DIROPS')->first();
            }
          } else {
            $position    = $head->position;
          }
        }


        // Insert Data
        $claim                = new Claim();
        $claim->uuid          = Str::uuid()->toString();
        $claim->code          = $this->regist_number($order + 1)  ?? null;
        $claim->policy        = $validation_data->NoPol           ?? null;
        $claim->certificate   = ($validation_data->NoCert != '') ? $validation_data->NoCert : null;
        $claim->name          = $validation_data->NamaPeserta ?? null;
        $claim->start_date    = $validation_data->StartDate   ?? null;
        $claim->end_date      = $validation_data->EndDate     ?? null;
        $claim->incident_date = $request->incident_date       ?? null;
        $claim->tsi_amount    = $validation_data->TSI         ?? null;
        $claim->claim_amount  = $claim_amount                 ?? null;
        $claim->description   = $request->description         ?? null;
        $claim->response      = json_encode($validation_data) ?? null;
        $claim->status        =  0  ?? null;
        $claim->decision      =  null;
        $claim->sequence      =  null;
        $claim->order         = $order + 1;
        $claim->cause_id      = $request->cause       ?? null;
        $claim->position_id   = $position->id          ?? null;
        $claim->office_id     = $office->id           ?? null;
        $claim->occupation_id = $request->occupation   ?? null;
        $claim->outlet_id     = session('user_data')['outlet_id'] ?? null;//3855 ?? null;
        $claim->created_by    = session('user_data')['id'] ?? null;//3855 ?? null;
        // $claim->created_by 		= $this->get_data_user()->id ?? null;
        $claim->save();

        // Insert Reqs Documents
        $files = Cause_file::select('id')->where('cause_id', $claim->cause_id)->get();
        foreach ($files as $file) {
          $document                 = new Document();
          $document->uuid           = Str::uuid()->toString();
          $document->document       = null;
          $document->description    = null;
          $document->remarks        = null;
          $document->claim_id       = $claim->id;
          $document->cause_file_id  = $file->id;
          $document->created_at     = null;
          $document->updated_at     = null;
          $document->save();
        }

        // Insert Recommendations
        $childs     = $this->childs($position->code);
        $arrayCount = count($childs);
        $i           = 0;

        foreach ($childs as $key => $value) {
          $isLast                 = ($i === $arrayCount - 1);
          $recommed               = new Recommendation();
          $recommed->uuid         = Str::uuid()->toString();
          $recommed->code         = null;
          $recommed->sequence     = $i + 1;
          $recommed->suggestion   = null;
          $recommed->description   = null;
          $recommed->is_decider   = ($isLast) ? 1 : 0;
          $recommed->claim_id     = $claim->id;
          $recommed->position_id   = $value->first()->position_id ?? null;
          $recommed->created_at   = null;
          $recommed->updated_at   = null;
          // $recommed->position_id 	= $value[0]->position_id ?? null;
          $recommed->save();
          $i++;
        }

        $this->add_log('Menambahkan data klaim');
        DB::commit();
        return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_success', "Penambahaan Data Klaim Berhasil");
      } else {
        echo "Error";
        DB::rollback();
        return back()->withErrors($validator)->withInput();
      }
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
      return redirect()->route('claims')->with('pesan_error', "Penambahaan Data Klaim Gagal");
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Claim $claim)
  {
    //
  }

  public function detail($uuid)
  {
    $claim = Claim::with([
      'office:id,code,name',
      'occupation:id,code,name',
      'cause:id,code,name',
      'position',
      'position.head_position',
      'position.head_position.user',
      'documents',
      'documents.cause_file.file',
      'recommendations'
    ])->where('uuid', $uuid)->firstOrFail();

    $isChecedkAll = !Document::where('claim_id', $claim->id)->where('is_accepted', '!=', 1)->exists();

    $data  = [
      'url'           => 'claims',
      'menu'          => ($claim->status == 1) ? 'peninjauan' : 'pengajuan',
      'title'         => ($claim->status == 1) ? 'peninjauan klaim' : 'pengajuan klaim',
      'claim'         => $claim ?? [],
      'isChecedkAll'  => ($isChecedkAll) ? true : false,
    ];

    if ($claim->status == 0) {
      return view('claim.detail', $data);
    } else {
      return view('claim.review', $data);
    }
  }

  public function policy_validation(Request $request)
  {
    //dd(openssl_get_cert_locations());
    try {
      $url = 'https://uatassist.askridasyariah.com:2811/aas/ClaimPolicyPegadaian';
      //$url = 'https://assist.askridasyariah.co.id:2810/aas/ClaimPolicyPegadaian';
      $response = Http::withHeaders([
        'Content-Type'   => 'application/json',
        'Accept'         => 'application/json',
      ])->withOptions([
        'verify' => storage_path('cert/pem_star_askridasyariah_com_2025.pem'),
      ])->post($url, [
        'NoPol'     => $request->policy ?? '',
        'NoCert'   => $request->certificate ?? '',
      ]);

      if ($response->successful()) {
        $array   = $response->json();
        $object  = (object) $array;

        $existing = Claim::select('policy')->where('policy', $request->policy)->first();
        if ($existing) {
          return redirect()->route('claims', ['status' => 'submission'])->with('pesan_error', "Polis sudah pernah diajukan klaim sebelumnya");
        }

        if ($object->IDPeserta) {
          session([
            'validation_data' => $object
          ]);
          return redirect()->route('claim.create');
        } else {
          return redirect()->route('claims', ['status' => 'submission'])->with('pesan_error', "Kombinasi Nomor Polis & Sertifikat tidak ditemukan");
        }
      } else {
        return redirect()->route('claims', ['status' => 'submission'])->with('pesan_error', "Kombinasi Nomor Polis & Sertifikat tidak ditemukan");
      }
    } catch (\Exception $e) {
      print($e);
      die;
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  public function review_action(Request $request)
  {
    // $this->debug($request->all());
    // die;
    DB::beginTransaction();
    try {
      $uuid       = $request->uuid;
      $recom_uuid = $request->recom_uuid;
      $recom_note = $request->recom_note;
      // $documents  = $request->documents;
      // $decisions  = $request->decisions;
      // $remarks    = $request->remarks;

      // Records
      $claim       = Claim::where('uuid', $uuid)->firstOrFail();
      $recommend   = Recommendation::where('uuid', $recom_uuid)->firstOrFail();
      $next       = $request->all_done ?? 0;
      $last       = false;

      if ($recommend->is_decider == 1) {
        // 
      } else {
        if ($next == 1) {
          $next_recom = Recommendation::select('claim_id', 'position_id')->where('claim_id', $claim->id)->where('sequence', $recommend->sequence + 1)->first();
          if ($next_recom->position_id == $claim->position_id) {
            $last = true;
          }
        }
      }

      // Update Documents
      // Diganti dengan AJAX
      // if ($recommend->sequence == 1) {
      //   for ($i = 0; $i < count($documents); $i++) {
      //     Document::where('uuid', $documents[$i])->update([
      //       'is_accepted'  => $decisions[$i]  ?? 0,
      //       'remarks'      => $remarks[$i]   ?? null,
      //     ]);
      //   }
      // }

      // Update Recommendation
      Recommendation::where('uuid', $recom_uuid)->update([
        'description'    => $recom_note                 ?? null,
        'created_by'    => $this->get_data_user()->id ?? null,
        'suggestion'    => ($next) ? 1 : 0,
        'created_at'    => date('Y-m-d H:i'),
      ]);

      // Update Claim
      Claim::where('uuid', $uuid)->update([
        'reviewed_at'    => date('Y-m-d H:i'),
        'reviewed_by'    => $this->get_data_user()->id ?? null,
        'sequence'      => ($next) ? $claim->sequence + 1 : $claim->sequence,
        'status'        => ($last) ? $claim->status + 1 :  $claim->status,
      ]);

      DB::commit();
      return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_success', "Pengajuan Klaim '$claim->code' Berhasil");
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  public function childs($parent)
  {
    try {
      // URL endpoint
      $url    = 'http://umum.askridasyariah.com:2810/digimail/API/HR/get-childs';

      // Parameter
      $params = [
        'parent' => $parent,
      ];

      $response   = Http::get($url, $params);

      if ($response->successful()) {
        $data     = $response->json();

        // CHILDs
        $childs   = collect($data['data'])->map(function ($item) {
          return (object) $item;
        });

        // NIRPs
        $codes     = $childs->pluck('code')->unique()->toArray();
        $integers  = array_map('intval', $codes);

        // USERS
        $users     = User::select('id')->whereIn('code', $integers)->pluck('id');

        // STAFFs
        $staffs   = User_role::select('id', 'user_id')->with('user_aas:id,code')->whereIn('role_id', [5, 6])->whereIn('user_id', $users)->orderBy('role_id')->get();

        // Rows ?
        $filtered  = $staffs->pluck('user_aas')->pluck('code')->toArray();

        return $childs->whereIn('code', $filtered)->groupBy('position_code')->reverse();
      } else {
        return response()->json([
          'error'   => 'Failed to fetch data',
          'status'   => $response->status(),
          'message' => $response->body(),
        ]);
      }
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Claim $claim)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Claim $claim)
  {
    DB::beginTransaction();
    try {
      $rules = array(
        // 'incident_date'     => 'required|date_format:d-m-Y',
        'incident_date'     => 'required',
        'submission_amount' => 'required',
        'description'       => 'required'
      );
      $attributeNames = array(
        'incident_date'     => 'Tanggal Kejadian',
        'submission_amount' => 'Nilai Pengajuan',
        'description'       => 'Keterangan Penyebab Klaim'
      );

      $validator = Validator::make($request->all(), $rules);
      $validator->setAttributeNames($attributeNames);
      if (!$validator->fails()) {

        $claim_amount    = str_replace('.', '', $request->submission_amount);
        // $incident_date  = Carbon::createFromFormat('d-m-Y', $request->incident_date)->format('Y-m-d');
        $description     = $request->description;
        $documents       = Document::select('id', 'document', 'description', 'claim_id')->where('claim_id', $claim->id)->whereNull('document')->get()->count();

        // UPDATE STATUS
        if ($documents > 0) {
          return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_error', "Pengajuan Klaim Gagal, Dokumen Pendukung Masih Belum Dilengkapi");
        } else if ($request->statement != 1) {
          return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_error', "Gagal Mengajukan Klaim!");
        } else {
          Claim::where('uuid', $request->uuid)->update([
            'claim_amount'  => $claim_amount            ?? null,
            'incident_date'  => $request->incident_date ?? null,
            'description'    => $description            ?? null,
            'submitted_at'  => date('Y-m-d H:i'),
            'sequence'      => 1,
            'status'        => 1,
          ]);
        }

        $this->add_log('Pernyataan & Pengajuan Klaim = ' .  $claim->code);
        DB::commit();
        return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_success', "Pengajuan Klaim '$claim->code' Berhasil");
      } else {
        DB::rollback();
        return back()->withErrors($validator)->withInput();
      }
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
      return redirect()->route('claim.detail', ['uuid' => $claim->uuid])->with('pesan_error', "Pengajuan Klaim Gagal");
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Claim $claim)
  {
    DB::beginTransaction();
    try {
      Document::where('claim_id', $claim->id)->delete();
      Recommendation::where('claim_id', $claim->id)->delete();
      $claim->delete();

      $this->add_log('Menghapus Data Klaim = ' .  $claim->code . " [ " . $claim->uuid . " ]");
      DB::commit();
      return redirect()->route('claims')->with('pesan_success', "Hapus Data Klaim '$claim->code' Berhasil");
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
      return redirect()->route('claims')->with('pesan_error', "Hapus Data Klaim Gagal");
    }
  }

  public function upload_form()
  {
    // return view('upload');
  }

  public function upload(Request $request)
  {
    // Validasi file harus berformat PDF
    $validated = $request->validate([
      'file' => 'required|mimes:pdf|max:2048' // Maksimal 2MB
    ]);

    if ($request->hasFile('file')) {

      $file = $request->file('file');
      $extension = $file->getClientOriginalExtension();

      // Jika file bukan PDF, kirimkan error custom
      if ($extension !== 'pdf') {
        return response()->json([
          'success' => false,
          'message' => 'Format file harus PDF.'
        ], 400);
      }

      // delete Old FIle
      $document     = Document::select('document', 'claim_id', 'cause_file_id')->with('claim', 'cause_file')
        ->where('uuid', $request->document)->first();
      $oldFilePath   = "uploads/" . $document->document;
      if (Storage::disk('public')->exists($oldFilePath)) {
        Storage::disk('public')->delete($oldFilePath);
        $this->add_log("Menghapus Dokumen [$document->document]");
      }

      // Upload New File
      $fileName = $document->claim->code . '_' . $document->cause_file->file->code . '_' . uniqid() . '.' . $extension;
      $filePath = $file->storeAs('uploads', $fileName, 'public');

      // Update Table
      Document::where('uuid', $request->document)->whereRelation('claim', 'uuid', '=', $request->claim)->update([
        'document'    => $fileName,
        'created_at'  => date('Y-m-d H:i:s'),
      ]);

      $this->add_log("Menambahkan Dokumen [$fileName]");

      return response()->json([
        'success' => true,
        'message' => 'Berhasil mengunggah!',
        'file_path' => asset("storage/$filePath")
      ]);
    }

    // Jika tidak ada file yang diupload
    return response()->json([
      'success' => false,
      'message' => 'File gagal diupload.'
    ], 400);
  }

  // Method untuk menghapus file
  public function deleteFile(Request $request)
  {
    // Ambil path file dari request
    $fileUrl   = $request->input('file_path');
    $filePath = str_replace(url('storage') . '/', '', $fileUrl);
    // Gunakan Storage::disk('public') untuk file yang ada di public storage
    if (Storage::disk('public')->exists($filePath)) {
      Document::where('uuid', $request->document)->whereRelation('claim', 'uuid', '=', $request->claim)->update([
        'document' => null,
      ]);

      $this->add_log("Menghapus Dokumen [$filePath]");
      Storage::disk('public')->delete($filePath);
      return response()->json(['success' => true, 'message' => 'File berhasil dihapus.']);
    }

    return response()->json(['success' => false, 'message' => 'File tidak ditemukan di storage.'], 404);
  }

  public function file_description(Request $request)
  {
    DB::beginTransaction();
    try {
      $document  = Document::select('id', 'document', 'description', 'claim_id')->with('claim')->where('uuid', $request->document)->first();
      if ($document->description != trim($request->description)) {
        Document::where('id', $document->id)->update([
          'description' => $request->description,
          'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->add_log("Menambahkan Keterangan Dokumen [$document->uuid]");
        DB::commit();
      }
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
    }
  }

  public function file_decision(Request $request)
  {
    DB::beginTransaction();
    try {
      $document  = Document::select('id', 'document', 'remarks', 'claim_id')
        ->with('claim:id,uuid')
        ->where('uuid', $request->document)
        ->whereRelation('claim', 'uuid', '=', $request->claim)->first();

      if ($document->decision != trim($request->decision)) {
        Document::where('id', $document->id)->update([
          'is_accepted' => $request->decision,
          'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->add_log("Menambahkan decision Dokumen [$document->uuid]");
        DB::commit();

        if ($request->decision == 1) {
          $isChecedkAll = !Document::where('claim_id', $document->claim->id)->where('is_accepted', '!=', 1)->exists();
          return response()->json(['isChecedkAll' => ($isChecedkAll) ? true : false]);
        }
      }
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
    }
  }

  public function file_remarks(Request $request)
  {
    DB::beginTransaction();
    try {
      $document  = Document::select('id', 'document', 'remarks', 'claim_id')->with('claim')->where('uuid', $request->document)->first();
      $this->debug($request->all());
      if ($document->remarks != trim($request->remarks)) {
        Document::where('id', $document->id)->update([
          'remarks'     => $request->remarks,
          'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->add_log("Menambahkan Remarks Dokumen [$document->uuid]");
        DB::commit();
      }
    } catch (\Exception $e) {
      DB::rollback();
      print($e);
      die;
    }
  }

  public function regist_number($order)
  {
    $prefix         = 'CLM';
    $year           = date('y');
    $number         = '';
    if ($order >= 1000) {
      $number = $order;
    } else if ($order >= 100) {
      $number = "0" . $order;
    } else if ($order >= 10) {
      $number = "00" . $order;
    } else {
      $number = "000" . $order;
    }

    return $prefix . '-' . $year . '-' . $number;
  }
}
