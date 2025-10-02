@extends('_layout.app')

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
    <div class="row">
      <div class="col-lg-12">
        <form action="{{ route('claim.store') }}" method="POST" autocomplete="off">
          @csrf
          <div class="card card-success card-outline">
            <div class="card-body">
              {{-- Info --}}
              <div class="row mb-2">
                <div class="col-12">
                  <div class="alert alert-info-bs alert-dismissible fade show" role="alert">
                    <strong> <i class="fa fa-info-circle"></i> INFO</strong>
                    <ul>
                      <li> Bidang bertanda <span class="reqs">*</span> Wajib diisi</li>
                      <li> Bidang dengan warna latar abu-abu terisi otomatis berdasarkan nomor polis </li>
                    </ul>
                  </div>
                </div>
              </div>
              {{-- End of Info --}}

              <div class="px-2">
                <div class="row">
                  <div class="col-lg-12">
                    {{-- Fields --}}
                    <h5 class="">
                      <i class="fa fa-user"></i> DATA POLIS DEBITUR
                    </h5>
                    <hr class="mt-0">
                    <div class="row">
                      <div class="col-lg-6 px-3">
                        <div class="form-group row my-2">
                          <label class="col-sm-5">TANGGAL PENGAJUAN </label>
                          <div class="col-sm-4">
                            <span class="font-weight-bold" style="font-size: 16px">
                              {{ date('d F Y') }}
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="policy" class="col-sm-5 col-form-label">NOMOR POLIS</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control form-control text-dark" id="policy" name="policy"
                              value="{{ $data->NoPol }}" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="certificate" class="col-sm-5 col-form-label">NOMOR SERTIFIKAT</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control form-control text-dark" id="certificate"
                              name="certificate" value="{{ $data->NoCert != '' ? $data->NoCert : '-' }}" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="name" class="col-sm-5 col-form-label">NAMA PESERTA</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control form-control text-dark" id="name" name="name"
                              value="{{ $data->NamaPeserta ?? '-' }}" readonly>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="brach_name" class="col-sm-5 col-form-label"> CABANG POLIS</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control form-control text-dark" id="brach_name"
                              name="brach_name" value="{{ '[' . $data->IDBranch . '] ' . $data->BranchName }}" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="toc" class="col-sm-5 col-form-label">JENIS PEMBIAYAAN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control form-control text-dark" id="toc" name="toc"
                              value="{{ $data->TOC }}" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="tsi" class="col-sm-5 col-form-label">PLAFON PEMBIAYAAN (Rp)</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control form-control text-dark" id="tsi" name="tsi"
                              value="{{ number_format($data->TSI) }}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 px-3">
                        <div class="form-group row">
                          <label for="period" class="col-sm-5 col-form-label">JANGKA WAKTU</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control form-control text-dark" id="period" name="period"
                              value="{!! date('d-F-Y', strtotime($data->StartDate)) . '&nbsp; s/d &nbsp;' . date('d-F-Y', strtotime($data->EndDate)) !!}"
                              readonly>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="incident_date" class="col-sm-5 col-form-label">
                            TANGGAL KEJADIAN<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-4">
                            {{-- <input type="text" value="{{ old('incident_date') ?? '' }}"
                              class="form-control @error('incident_date') is-invalid @enderror" id="incident_date"
                              name="incident_date" data-inputmask-alias="datetime"
                              data-inputmask-inputformat="dd-mm-yyyy" data-mask> --}}
                            <input type="hidden" id="start_date" value="{{ $data->StartDate }}">
                            <input type="hidden" id="end_date"  value="{{ $data->EndDate }}">
                            <input type="date" onkeydown="return false;" value="{{ old('incident_date') ?? '' }}" class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date">
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
                          <label for="submission_amount" class="col-sm-5 col-form-label">
                            NILAI PENGAJUAN (Rp)<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-4">
                            <input type="text"
                              class="form-control text-dark rupiah @error('submission_amount') is-invalid @enderror"
                              id="submission_amount" name="submission_amount"
                              value="{{ old('submission_amount') ?? '' }}">
                            @error('submission_amount')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>


                        <div class="form-group row">
                          <label for="occupation" class="col-sm-5 col-form-label">
                            PEKERJAAN<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-7">
                            <select class="form-control @error('occupation') is-invalid @enderror" name="occupation"
                              id="occupation">
                              <option value="">Pilih:</option>
                              @foreach ($occupations as $occupation)
                              <option @selected($occupation->id == old('occupation')) value="{{ $occupation->id }}">
                                {{ $occupation->name }}
                              </option>
                              @endforeach
                            </select>
                            @error('occupation')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>



                        <div class="form-group row">
                          <label for="cause" class="col-sm-5 col-form-label">
                            PENYEBAB KLAIM<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-7">
                            <select class="form-control @error('cause') is-invalid @enderror" name="cause" id="cause">
                              <option value="">Pilih:</option>
                              @foreach ($causes as $cause)
                              <option @selected($cause->id == old('cause')) value="{{ $cause->id }}">
                                {{ $cause->name ?? '-' }}
                              </option>
                              @endforeach
                            </select>
                            @error('cause')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="description" class="col-sm-5 col-form-label">
                            KETERANGAN PENYEBAB KLAIM<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-7">
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                              id="description" rows="4">{{ old('description') ?? '' }}</textarea>
                            @error('description')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="cause" class="col-sm-5 col-form-label">
                            LOKASI KLAIM<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-7">
                            <select class="form-control @error('location') is-invalid @enderror" name="location" id="location">
                              <option value="">Pilih:</option>
                              @foreach ($locations as $location)
                              <option @selected($location->id == old('cause')) value="{{ $location->id }}">
                                {{ $location->loc_desc ?? '-' }}
                              </option>
                              @endforeach
                            </select>
                            @error('location')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="descriptionloc" class="col-sm-5 col-form-label">
                            KETERANGAN LOKASI KLAIM<span class="reqs">*</span>
                          </label>
                          <div class="col-sm-7">
                            <textarea class="form-control @error('descriptionloc') is-invalid @enderror" name="descriptionloc"
                              id="descriptionloc" rows="4">{{ old('descriptionloc') ?? '' }}</textarea>
                            @error('descriptionloc')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>

                      </div>
                    </div>
                    {{-- End of Fields --}}
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-sm bg-gradient-success px-4 rounded-pill btn-submit">
                <i class="fa fa-check"></i> KIRIM
              </button>
            </div>

          </div>

        </form>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@push('scripts')
<!-- InputMask -->
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
<script>
  $('#incident_date').inputmask('dd-mm-yyyy', {
    'placeholder': 'dd-mm-yyyy'
  })

  $('#submission_amount').on('keyup', function() {
    $(this).removeClass('is-invalid');
    let inputValue = $(this).val();
    let camparedValue = $("#tsi").val();

    if (inputValue != '') {
      // Menghapus titik dan mengkonversi string menjadi angka
      let newValue = parseInt(inputValue.replace(/\./g, ''), 10);
      let compared = parseInt(camparedValue.replace(/\,/g, ''), 10);

      // Pastikan nilai yang dibandingkan adalah angka
      if (!isNaN(newValue) && !isNaN(compared)) {
        if (newValue > compared) {
        alert("Nilai Pengajuan Melebihi Plafon Pembiayaan");
        $(this).addClass('is-invalid');
        $(this).val('');
        }
      } else {
        alert("Input tidak valid");
      }
    }
  });

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

  $(".btn-submit").click(function (e) { 
    $('.form-control').attr('readonly', true);
    $("#modalSpinner").modal('show');
    setTimeout(() => {
      $(this).attr('disabled', true);
      // $(this).text('loading....');

      let dots = "";
      setInterval(() => {
        dots = dots.length < 7 ? dots + "." : "";
        this.textContent = "Loading" + dots;
      }, 500);
    }, 300);
  });
</script>
@endpush