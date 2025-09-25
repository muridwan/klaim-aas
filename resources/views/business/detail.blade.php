@extends('_layout.app')


@section('section')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid border-bottom pb-1">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="mt-1">{{ ucwords($title . ' - ' . $business->name) }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item"><a href="{{ route('businesses') }}"> Data Kelas Bisnis </a></li>
          <li class="breadcrumb-item active">{{ ucwords($business->code) }}</li>
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
            <span>&times;</span>
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
            <span>&times;</span>
          </button>
        </div>
      </div>
    </div>
    @endif

    <div class="row">
      <div class="col-lg-12">

        <div class="row mb-2">
          <div class="col-lg-3">
            <form method="POST" class='d-inline'
              action="{{ route('business.destroy', ['business' => $business->id]) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-xs bg-gradient-danger btn-hapus px-3 rounded-pill"
                data-name="{{$business->name}}">
                <i class=" fa fa-trash"></i> Hapus Kelas Bisnis
              </button>
            </form>
          </div>
        </div>

        <div class="card card-success card-outline">
          <form action="{{ route('business.update', ['business'=> $business->id]) }}" method="POST" autocomplete="off">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="code">KODE <span class="text-danger">*</span></label>
                        <input type="text" class="form-control spaceless code @error('code') is-invalid @enderror"
                          id="code" name="code" placeholder="Contoh: F-001" value="{{ old('code') ?? $business->code }}"
                          autocomplete="off">
                        @error('code')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="name">NAMA KELAS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control name @error('name') is-invalid @enderror" id="name"
                          name="name" placeholder="Contoh: Kebakaran" value="{{ old('name') ?? $business->name }}"
                          autocomplete="off">
                        @error('name')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="description">DESKRIPSI</label>
                        <textarea class="form-control" name="description" id="description"
                          rows="3">{{ old('description') ?? $business->description }}</textarea>
                        @error('description')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                          @checked(empty($business->inactive_date))>
                        <label class="form-check-label font-weight-bold" for="is_active">Aktif </label>
                        @if (!empty( $business->inactive_date ))
                        <br>
                        <small>
                          Tidak Aktif Sejak:
                          <?= date('d F Y, H:i', strtotime( $business->inactive_date )) . " WIB" ?>
                        </small>
                        @else
                        <br>
                        <small>
                          Aktif Sejak:
                          <?= date('d F Y, H:i', strtotime( $business->effective_date )) . " WIB" ?>
                        </small>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-7 pl-3">
                  <div class="shadow px-2 pt-3">
                    <div class="row ">
                      <div class="col-12">
                        <h4 class="border-bottom">
                          <i class="fa fa-info-circle"></i> Penyebab Kerugian
                        </h4>

                        {{-- <div class="row my-3">
                          <div class="col-lg-12">
                            <select class="custom-select">
                              <option value="">Pilih Sumber Bisnis:</option>
                              @foreach ($institutions as $institution)
                              <option value="{{ $institution->code }}">{{ $institution->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div> --}}

                        <div class="form-group row my-3 px-1">
                          <label for="institution" class="col-sm-3 col-form-label">
                            SUMBER BISNIS <span class="reqs">*</span>
                          </label>
                          <div class="col-sm-9">
                            <select class="custom-select" name="institution" id="institution">
                              <option value="">Pilih :</option>
                              @foreach ($institutions as $institution)
                              <option @selected( $institution->code == $institution_code) value="{{
                                $institution->code }}">{{ $institution->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        @if (!empty($_GET['institution']))
                        <div class="table-responsive">
                          <table class="table table-hover table-sm">
                            <thead class="text-center bg-gradient-success">
                              <tr>
                                <th style="width: 50px" class="border-left py-2">NO</th>
                                <th style="width: 150px">KODE</th>
                                <th>NAMA</th>
                                <th style="width: 100px">&Sigma; BERKAS</th>
                                <th style="width: 100px">&Sigma; KLAIM</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse ($causes as $cause)
                              <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                  <a href="{{ route('cause.detail', ['uuid' => $cause->uuid]) }}">
                                    <b>{{ $cause->code ?? ''}}</b>
                                  </a>
                                </td>
                                <td>{{ $cause->name ?? '' }}</td>
                                <td class="text-center">{{ $cause->cause_files->count() }}</td>
                                <td class="text-center">{{ rand(1,100) }}</td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="5">Belum ada data...</td>
                              </tr>
                              @endforelse
                            </tbody>
                            <tfoot>
                              <tr>
                                <td td colspan="4"></td>
                                <td class="text-center">
                                  <a href="{{ route('cause.create', ['business' => $business->uuid, 'institution' => $_GET['institution'] ]) }}"
                                    class="btn btn-xs bg-gradient-success rounded-pill px-3">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                  </a>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <a href="" class="btn btn-sm bg-secondary rounded-pill px-4 mr-1">
                <i class="fa fa-undo "></i> BATAL
              </a>
              <button type="submit" class="btn btn-sm bg-gradient-info rounded-pill px-4 btn-submit">
                <i class="fa fa-check"></i> SIMPAN
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content -->
@endsection

@push('scripts')

<!-- Sweetalert -->
<script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.all.js"></script>

<script>
  $(function() {
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");

    $(".btn-hapus").click(function(e) {
      e.preventDefault();
      if( event.target.getAttribute('data-name') != null ){
        Swal.fire({
          title: 'Hapus Kelas Bisnis \n"' + event.target.getAttribute('data-name') + '"?',
          text: '*Data yang sudah dihapus tidak dapat dibatalkan',
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

  });

  $("#institution").change(function(e) {
    e.preventDefault();
    var fullUrl = window.location.href;
    var baseUrl = fullUrl.split('?')[0];
    let selectedValue = $(this).val();  
   
    if (selectedValue) {
      // Buat URL berdasarkan route Laravel
     $("#modalSpinner").modal('show');
      const url = `${baseUrl}?institution=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });


</script>
@endpush