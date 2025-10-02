@extends('_layout.app')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('section')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid border-bottom pb-1">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="mt-1">{{ ucwords($title) }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item"><a href="{{ route('claims') }}">Klaim</a></li>
          <li class="breadcrumb-item active">{{ ucwords($title) }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">


    @if(session()->has('pesan_success'))
    <div class="row">
      <div class="col-12">
        <div class="alert alert-success-bs alert-dismissible fade show" role="alert">
          <strong>Perhatian!</strong> {!! session()-> get('pesan_success')!!}
          <i class="fa fa-check-circle text-success"></i>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    @endif

    @if(session()->has('pesan_error'))
    <div class="row">
      <div class="col-12">
        <div class="alert alert-danger-bs alert-dismissible fade show" role="alert">
          <strong>Perhatian!</strong> {!! session()->get('pesan_error')!!}
          <i class="fa fa-times-circle text-danger"></i>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    @endif

    @if ($claim->status == 0)
    <div class="row mb-2">
      <div class="col-lg-3">
        <form method="POST" class='d-inline' action="{{ route('claim.destroy', ['claim' => $claim->id]) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-xs bg-gradient-danger btn-hapus px-4 rounded-pill"
            data-name="{{$claim->code}}">
            <i class="fa fa-times-circle"></i> BATALKAN PENGAJUAN KLAIM
          </button>
        </form>
      </div>
    </div>
    @endif

    <div class="row">
      <div class="col-lg-12">
        <form action="{{ route('claim.update', ['claim' => $claim->id]) }}" method="POST" autocomplete="off">
          @method('PUT')
          @csrf
          <input type="hidden" name="uuid" value="{{ $claim->uuid }}">
          <div class="card card-success card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tabs-home-tab" data-toggle="pill" href="#tabs-home" role="tab"
                    aria-controls="tabs-home" aria-selected="false">
                    <i class="fas fa-id-card"></i> Polis Debitur
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " id="tabs-profile-tab" data-toggle="pill" href="#tabs-profile" role="tab"
                    aria-controls="tabs-profile" aria-selected="true">
                    <i class="fas fa-paste"></i> Dokumen Pendukung
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " id="tabs-statement-tab" data-toggle="pill" href="#tabs-statement" role="tab"
                    aria-controls="tabs-statement" aria-selected="false">
                    <i class="fas fa-exclamation-circle"></i> Pernyataan
                  </a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="tabs-tabContent">
                {{-- POLICY TAB --}}
                <div class="tab-pane fade show active" id="tabs-home" role="tabpanel" aria-labelledby="tabs-home-tab">
                  {{-- Info --}}
                  <div class="row">
                    <div class="col-12">
                      <div class="alert alert-info-bs alert-dismissible fade show" role="alert">
                        <strong> <i class="fa fa-info-circle"></i> INFO</strong>
                        <ul>
                          <li> Bidang bertanda <span class="reqs">*</span> Wajib diisi,</li>
                          <li> Bidang dengan warna latar abu-abu terisi otomatis berdasarkan nomor polis, </li>
                          <li><a href="" class="text-primary">Segarkan</a> halaman ini untuk mendapatkan data
                            terbaru.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  {{-- End of Info --}}
                  <div class="row">
                    <div class="col-lg-6 px-3">
                      <div class="form-group row my-2">
                        <label class="col-sm-5">KODE PENGAJUAN </label>
                        <div class="col-sm-4">
                          <span class="font-weight-bold" style="font-size: 16px">
                           <u> {{ $claim->code }}</u>
                          </span>
                        </div>
                      </div>
                      <div class="form-group row my-2">
                        <label class="col-sm-5">TANGGAL PENGAJUAN </label>
                        <div class="col-sm-4">
                          <span class="font-weight-bold" style="font-size: 16px">
                            {{ date('d F Y', strtotime($claim->created_at)) }}
                          </span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="policy" class="col-sm-5 col-form-label">NOMOR POLIS</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="policy"
                            value="{{ $claim->policy }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="certificate" class="col-sm-5 col-form-label">NOMOR SERTIFIKAT</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="certificate" name=""
                            value="{{ $claim->certificate != '' ? $claim->certificate : '-' }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="name" class="col-sm-5 col-form-label">NAMA PESERTA</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="name" name=""
                            value="{{ $claim->name ?? '-' }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="occupation" class="col-sm-5 col-form-label">PEKERJAAN</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="occupation" name=""
                            value="{{ $claim->occupation->name ?? '-' }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="brach_name" class="col-sm-5 col-form-label"> CABANG POLIS</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="brach_name" name=""
                            value="{{ '[' . $claim->office->code . '] ' . $claim->office->name }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="toc" class="col-sm-5 col-form-label">JENIS PEMBIAYAAN</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control form-control text-dark" id="toc" name=""
                            value="{{ responseToString($claim->response)->TOC }}" readonly>
                        </div>
                      </div>

                    </div>
                    <div class="col-lg-6 px-3">

                      <div class="form-group row">
                        <label for="period" class="col-sm-5 col-form-label">JANGKA WAKTU</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="period" name=""
                            value="{!! date('d F Y', strtotime($claim->start_date)) . '&nbsp;&nbsp; s.d &nbsp;&nbsp;' . date('d F Y', strtotime($claim->end_date)) !!}"
                            readonly>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="incident_date" class="col-sm-5 col-form-label">
                          TANGGAL KEJADIAN<span class="reqs">*</span>
                        </label>
                        <div class="col-sm-4">
                          {{-- <input type="text"
                            value="{{ old('incident_date') ?? date('d-m-Y', strtotime($claim->incident_date)) }}"
                            class="form-control @error('incident_date') is-invalid @enderror" id="incident_date"
                            name="incident_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy"
                            data-mask> --}}
                            <input type="hidden" id="start_date" value="{{ $claim->start_date }}">
                            <input type="hidden" id="end_date"  value="{{ $claim->end_date }}">
                            <input type="date" onkeydown="return false;" value="{{ old('incident_date') ?? $claim->incident_date }}" class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date">
                          @error('incident_date')
                          <div class="invalid-feedback font-weight-bold">
                            {{ $message }}!
                          </div>
                          @enderror
                        </div>
                        <div class="col-sm-3 mt-1">
                          <span style="font-size: 14px">(tanggal-bulan-tahun)</span>
                        </div>
                      </div>



                      <div class="form-group row">
                        <label for="tsi" class="col-sm-5 col-form-label">PLAFON PEMBIAYAAN (Rp)</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control form-control text-dark" id="tsi" name=""
                            value="{{ number_format($claim->tsi_amount, 0, ',', '.') }}" readonly>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="submission_amount" class="col-sm-5 col-form-label">
                          NILAI PENGAJUAN (Rp)<span class="reqs">*</span>
                        </label>
                        <div class="col-sm-4">
                          <input type="text"
                            class="form-control form-control bg-gradient-white text-dark rupiah @error('submission_amount') is-invalid @enderror"
                            id="submission_amount" name="submission_amount"
                            value="{{ old('submission_amount') ??  number_format($claim->claim_amount, 0, ',', '.') }}">
                          @error('submission_amount')
                          <div class="invalid-feedback font-weight-bold">
                            {{ $message }}!
                          </div>
                          @enderror
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="cause" class="col-sm-5 col-form-label">PENYEBAB KLAIM</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="cause" name=""
                            value="{{ $claim->cause->name }}" readonly>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="description" class="col-sm-5 col-form-label">
                          KETERANGAN PENYEBAB KLAIM<span class="reqs">*</span>
                        </label>
                        <div class="col-sm-7">
                          <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            id="description" rows="5">{{ old('description') ?? $claim->description }}</textarea>
                          @error('description')
                          <div class="invalid-feedback font-weight-bold">
                            {{ $message }}!
                          </div>
                          @enderror
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="location" class="col-sm-5 col-form-label">LOKASI KLAIM</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control form-control text-dark" id="location" name=""
                            value="{{ $claim->location->loc_desc }}" readonly>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="loss_loc_desc" class="col-sm-5 col-form-label">
                          KETERANGAN LOKASI KLAIM<span class="reqs">*</span>
                        </label>
                        <div class="col-sm-7">
                          <textarea class="form-control @error('loss_loc_desc') is-invalid @enderror" name="loss_loc_desc"
                            id="loss_loc_desc" rows="5">{{ old('loss_loc_desc') ?? $claim->loss_loc_desc }}</textarea>
                          @error('loss_loc_desc')
                          <div class="invalid-feedback font-weight-bold">
                            {{ $message }}!
                          </div>
                          @enderror
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                {{-- End of POLICY TAB --}}

                {{-- DOCUMENTs TAB --}}
                <div class="tab-pane fade " id="tabs-profile" role="tabpanel" aria-labelledby="tabs-profile-tab">
                  {{-- Info --}}
                  <div class="row">
                    <div class="col-12">
                      <div class="alert alert-info-bs alert-dismissible fade show" role="alert">
                        <strong> <i class="fa fa-info-circle"></i> INFO</strong>
                        <ul>
                          <li> File yang diunggah wajib berekstensi .pdf dan berukuran maksimal 2MB, selain itu
                            akan menyebabkan error,</li>
                          <li> File akan diunggah otomatis ketika sudah dipilih, klik tombol <b
                              class="text-danger">Hapus</b> untuk
                            membatalkan, </li>


                          <li><a href="" class="text-primary">Segarkan</a> halaman ini untuk mendapatkan data
                            terbaru.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  {{-- End of Info --}}
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                      <thead class="text-center bg-gradient-success">
                        <tr>
                          <th style="width: 50px" class="border-left py-2">NO</th>
                          <th>DOKUMEN</th>
                          <th>BERKAS</th>
                          <th>STATUS</th>
                          <th class="border-right">KETERANGAN</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($claim->documents as $document)
                        <tr>
                          <td class="text-center border">{{ $loop->iteration }}</td>
                          <td>
                            <span id="file-name-{{$loop->iteration}}">
                              {{ $document->cause_file->file->name ?? '' }}
                            </span>
                          </td>
                          <td>
                            <input type="file" class="file-input" data-row="{{ $loop->iteration }}">
                            <input type="hidden" id="claim-{{$loop->iteration}}" value="{{ $claim->uuid }}">
                            <input type="hidden" id="document-{{$loop->iteration}}" value="{{ $document->uuid }}">

                            @if ($document->document)
                            <br><small id="info-default-{{$loop->iteration}}">*Biarkan bidang ini bila tidak ingin
                              mengganti file</small>
                            @endif

                            <div class="row">
                              <div class="col-sm-9">
                                <div class="progress-container mt-2" id="progress-container-{{$loop->iteration}}">
                                  <div class="progress">
                                    <div id="progress-bar-{{$loop->iteration}}"
                                      class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                      role="progressbar" style="width: 0%;">0%
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <button type="button" class="btn px-2 py-0 btn-xs btn-danger cancel-button"
                                  id="cancelButton-{{$loop->iteration}}" style="display: none;"
                                  data-row="{{$loop->iteration}}"> <i class="fa fa-times"></i> Hapus
                                </button>
                              </div>
                            </div>
                          </td>

                          <td class="text-center border-left">
                            @if ($document->document)
                            <a target="_blank" data-lable="{{ $document->cause_file->file->name }}"
                              href="{{ asset('storage/uploads/'. $document->document) }}"
                              class="badge badge-success px-3 modalFile" id="status-default-{{$loop->iteration}}">
                              <i class="fa fa-file"></i> File
                            </a>
                            @endif
                            <span id="status-text-{{$loop->iteration}}"></span>
                          </td>

                          <td>
                            <textarea data-row="{{ $loop->iteration }}" class="form-control file_description"
                              id="file-description-{{$loop->iteration}}"
                              rows="2">{{{ $document->description ?? '' }}}</textarea>
                          </td>
                        </tr>
                        @empty

                        @endforelse

                      </tbody>
                    </table>
                  </div>
                </div>
                {{-- End of DOCUMENTs TAB --}}

                {{-- POLICY TAB --}}
                <div class="tab-pane fade " id="tabs-statement" role="tabpanel" aria-labelledby="tabs-statement-tab">
                  {{-- Info --}}
                  <div class="row">
                    <div class="col-12">
                      <div class="alert alert-info-bs alert-dismissible fade show" role="alert">
                        <strong> <i class="fa fa-info-circle"></i> INFO</strong>
                        <ul>
                          <li> Bidang dengan warna latar abu-abu terisi otomatis berdasarkan nomor polis, </li>
                          <li> Pastikan tiap bidang dan dokumen sudah diisi dengan benar, </li>
                          <li><a href="" class="text-primary">Segarkan</a> halaman ini untuk mendapatkan data
                            terbaru.</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  {{-- End of Info --}}
                  <div class="row">
                    <div class="col-lg-6 px-3">
                      <div class="form-group row">
                        <label for="outlet_code" class="col-sm-4 col-form-label">KODE OUTLET</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control form-control text-dark" id="outlet_code" name=""
                            value="{{ $claim->outlet->code }}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="outlet_name" class="col-sm-4 col-form-label">NAMA OUTLET</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control form-control text-dark" id="outlet_name" name=""
                            value="{{ $claim->outlet->name != '' ? $claim->outlet->name : '-' }}" readonly>
                        </div>
                      </div>

                    </div>
                    <div class="col-lg-6 px-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="statement" id="statement">
                        <label class="form-check-label" for="statement">
                          Dengan ini menyatakan bahwa formulir klaim sudah diisi dengan sebenar-benarnya.
                        </label>
                      </div>
                      <div class="row mt-2">
                        <div class="col-lg-3">
                          <button type="submit" id="btn-statement"
                            class="btn btn-sm bg-gradient-success px-4 rounded-pill btn-submit" disabled>
                            <i class="fa fa-check"></i> Kirim
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                {{-- End of POLICY TAB --}}
              </div>
            </div>
            <!-- /.card -->
          </div>
        </form>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Modal -->
<div class="modal fade" id="detailFile" tabindex="-1" aria-labelledby="detailFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title">
          <i class="fa fa-file mr-1"></i> <span id="detailFileLabel"></span>
        </h5>

      </div>
      <div class="modal-body">
        <embed id="detailFileEbd" src="#" width="100%" height="600px" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm rounded-pill px-3 btn-secondary" data-dismiss="modal"><i
            class="fa fa-times-circle"></i>
          Tutup
        </button>
        <a href="#" id="detailFileUrl" download type="button"
          class="btn btn-sm rounded-pill px-3 bg-gradient-success"><i class="fa fa-download"></i> Unduh </a>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->

@endsection

@push('scripts')

<!-- Sweetalert -->
<script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.all.js"></script>

<!-- InputMask -->
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
<script>
  $(document).ready(function () {
    $('.rupiah').each(function () { 
      let value = $(this).val();
      $(this).val(formatRupiah(value));
    });
    
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");

    $(".progress-container").hide();
    showModal();
  });

  // $('#incident_date').inputmask('dd-mm-yyyy', {
  //   'placeholder': 'dd-mm-yyyy'
  // })
  
  $('#incident_date').on('change', function() {
    $(this).removeClass('is-invalid');
    let inputValue = $(this).val();

    if (inputValue != '') {
      const selectedDate  = new Date($(this).val());
      const startDate     = new Date($("#start_date").val());
      const endDate       = new Date($("#end_date").val());

      // Samakan waktu jadi 00:00:00 agar perbandingan hanya berdasarkan tanggal
      selectedDate.setHours(0,0,0,0);
      startDate.setHours(0,0,0,0);
      endDate.setHours(0,0,0,0);

      if (selectedDate < startDate || selectedDate > endDate) {
        alert("Tanggal Pengajuan Tidak Valid!");
        $(this).addClass('is-invalid');
        $(this).val('');
      } else{
        console.log('Tanggal valid');
      }
    }
  });

  $('#submission_amount').on('keyup', function() {
    $(this).removeClass('is-invalid');
    let inputValue = $(this).val();
    let camparedValue = $("#tsi").val();

    if (inputValue != '') {
      // Menghapus titik dan mengkonversi string menjadi angka
      let newValue = parseInt(inputValue.replace(/\./g, ''), 10);
      let compared = parseInt(camparedValue.replace(/\./g, ''), 10);

      // Pastikan nilai yang dibandingkan adalah angka
      if (!isNaN(newValue) && !isNaN(compared)) {
        if (newValue > compared) {
        alert("Nilai Pengajuan Melebihi Plafon Pembiayaan"+newValue+compared);
        $(this).addClass('is-invalid');
        $(this).val('');
        }
      } else {
        alert("Input tidak valid");
      }
    }
  });

  function showModal(){
    $(".modalFile").click(function (e) { 
      e.preventDefault();
      let file = $(this).attr('href');
      let lable = $(this).attr('data-lable');
      $("#detailFile").modal('show');
      $("#detailFileLabel").text(lable);
      $("#detailFileEbd").attr('src', file)
      $("#detailFileUrl").attr('href', file)
    });
  }

  let xhrRequest    = {}; // Menyimpan objek xhr untuk membatalkan upload berdasarkan row
  let uploadedFiles = {}; // Menyimpan file path yang telah diupload

  $(".file-input").change(function () {
    let row = $(this).data("row"); // Ambil nomor baris
    let file = this.files[0];
    if (!file) return;

    let formData = new FormData();
    formData.append("file", file);
    formData.append("claim", $(`#claim-${row}`).val());
    formData.append("document", $(`#document-${row}`).val());

    let progressBar       = $(`#progress-bar-${row}`);
    let progressContainer = $(`#progress-container-${row}`);
    let statusText        = $(`#status-text-${row}`);
    let cancelButton      = $(`#cancelButton-${row}`);
    let infoDefault       = $(`#info-default-${row}`);
    let statusDefault     = $(`#status-default-${row}`);
    let fileName          = $(`#file-name-${row}`);

    progressBar.width("0%").text("0%");
    progressContainer.show();
    statusText.html("");
    cancelButton.hide(); // Sembunyikan tombol batal selama upload

    // Melakukan upload
    xhrRequest[row] = $.ajax({
      url: "{{ route('upload.file') }}",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      xhr: function () {
        let xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
          if (evt.lengthComputable) {
            infoDefault.hide();
            statusDefault.hide();
            let percentComplete = Math.round((evt.loaded / evt.total) * 100);
            progressBar.width(percentComplete + "%").text(percentComplete + "%");
              if( percentComplete >= 100 ){
                progressBar.text('100%');
                progressBar.removeClass("bg-danger").removeClass("bg-primary").addClass("bg-success");
              }
          }
        }, false);
        return xhr;
      },
      success: function (response) {
        if (response.success) {
          uploadedFiles[row] = response.file_path; // Menyimpan path file yang diupload
          statusText.html(`<div class="badge badge-success text-white">${response.message} <br><br>
            <a href="${response.file_path}" class="modalFile text-warning" data-lable="${fileName.text()}" >
              <i class="fa fa-search"></i> Lihat File
            </a>
          </div>`);
          progressBar.removeClass("bg-success").addClass("bg-primary");
          cancelButton.show(); // Tampilkan tombol batal setelah upload selesai
          showModal();
        }
      },
      error: function (xhr) {
        // if (xhr.statusText !== 'abort') {
          let errorMessage = xhr.responseJSON?.message || "Upload gagal!";
          // statusText.html(`<div class="badge badge-danger">${errorMessage}</div>`);
          statusText.html(`<div class="badge badge-danger">Gagal Menggungah!</div>`);
          progressBar.text('error');
          progressBar.removeClass("bg-success").addClass("bg-danger");
        // }
      }
    });
  });

  // Event klik tombol batal
  $(document).on("click", ".cancel-button", function () {
    let $this               = $(this);
    let row                 = $this.data("row"); // Ambil nomor baris
    let $progressContainer  = $(`#progress-container-${row}`);
    let $cancelButton       = $(`#cancelButton-${row}`);
    let $statusText         = $(`#status-text-${row}`);

    if (xhrRequest[row]) {
      xhrRequest[row].abort(); 

      if (uploadedFiles[row]) {
        $.ajax({
          url: "{{ route('upload.delete') }}",
          type: "POST",
          data: {
            file_path: uploadedFiles[row],
            claim: $(`#claim-${row}`).val(),
            document: $(`#document-${row}`).val(),
          },
          headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
          success: function () {
            $progressContainer.hide();
            $statusText.html("<div class='badge badge-warning'>file berhasil dihapus.</div>");
            $cancelButton.hide();
            $(`input[data-row="${row}"]`).val("");
          },
          error: function () {
            $statusText.html("<div class='badge badge-danger'>Gagal menghapus file.</div>");
          }
        });
      }
    }
  });

  // Event mengisi keterangan
  $(".file_description").blur(function (e) { 
    let $this       = $(this);
    let value       = $(this).val();
    let row         = $this.data("row"); // Ambil nomor baris
    let description = $(`#file-description-${row}`).val();
    
    if( description != '' ){
      $.ajax({
        url: "{{ route('file_description') }}",
        type: "POST",
        data: {
          claim: $(`#claim-${row}`).val(),
          document: $(`#document-${row}`).val(),
          description: description,
        },
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
        success: function () {
          // console.log(result);
        },
        error: function () {
        }
      });
    }
  });

  $("#statement").change(function() {
    if ($(this).prop("checked")) {
      $("#btn-statement").prop('disabled', false); // Gunakan .prop() untuk mengaktifkan tombol
    } else {
      $("#btn-statement").prop('disabled', true);
    }
  });

  $(".btn-hapus").click(function(e) {
    e.preventDefault();
    if( event.target.getAttribute('data-name') != null ){
      Swal.fire({
        title: 'Batalkan Pengajuan Klaim \n"' + event.target.getAttribute('data-name') + '"?',
        text: 'Data yang sudah dihapus tidak dapat dibatalkan',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: "Tidak, Batalkan",
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          e.target.parentElement.submit();
        }
      })
    }
  });
</script>
@endpush