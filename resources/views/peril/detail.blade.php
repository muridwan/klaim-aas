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
          <li class="breadcrumb-item"><a href="{{ route('perils') }}">Risiko</a></li>
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

    @if ( $peril->claims->count() == 0 )
    <div class="row mb-2">
      <div class="col-lg-3">
        <form method="POST" class='d-inline' action="{{ route('peril.destroy', ['peril' => $peril->id]) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-xs bg-gradient-danger btn-hapus px-4 rounded-pill"
            data-name="{{$peril->code}}">
            <i class="fa fa-trash"></i> HAPUS DATA RISIKO
          </button>
        </form>
      </div>
    </div>
    @endif

    <div class="row">
      <div class="col-lg-12">
        <div class="card card-success card-outline">
          <div class="card-body">

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

            <form action="{{ route('peril.update', ['peril'=> $peril->id]) }}" method="POST" autocomplete="off">
              @method('PUT')
              @csrf
              <div class="row">
                <div class="col-lg-6">
                  <div class="px-2">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="code">KODE <span class="reqs">*</span></label>
                          <input type="text" class="form-control spaceless @error('code') is-invalid @enderror"
                            name="code" id="code" value="{{ old('code') ?? $peril->code }}">
                          @error('code')
                          <div class="invalid-feedback font-weight-bold">
                            {{ $message }}!
                          </div>
                          @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="effective_date">TANGGAL EFEKTIF <span class="reqs">*</span></label>
                          <input type="date" class="form-control @error('effective_date') is-invalid @enderror"
                            name="effective_date" id="effective_date"
                            value="{{ old('effective_date') ?? date('Y-m-d', strtotime($peril->effective_date)) }}">
                          @error('effective_date')
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
                          <label for="name">RISIKO <span class="reqs">*</span></label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" value="{{ old('name') ?? $peril->name  }}">
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
                          <label for="description">KETERANGAN</label>
                          <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            id="description" rows="4">{{ old('description') ?? $peril->description }}</textarea>
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
                            @checked(empty($peril->inactive_date))>
                          <label class="form-check-label font-weight-bold" for="is_active">Aktif</label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="offset-lg-6 col-lg-3">
                        <a href="" class="btn btn-block bg-gradient-secondary btn-sm rounded-pill">
                          <i class="fa fa-undo"></i> Batal
                        </a>
                      </div>
                      <div class="col-lg-3">
                        <button type="submit" class="btn btn-block bg-gradient-success btn-sm rounded-pill btn-submit">
                          <i class="fa fa-check"></i> Simpan
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="px-2">
                    <table class="table table-sm table-hover shadow-sm">
                      <thead class="text-center bg-light">
                        <tr>
                          <th style="width: 100px" class="border-left">WAJIB?</th>
                          <th class="border-right">DOKUMEN</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($files as $file)
                        <?php
                            $check = false;
                            if( in_array($file->id, $checkeds) ){
                              $check = true;
                            }
                          ?>
                        <tr>
                          <td class="text-center">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" class="form-check-input " name="peril_file[]" value="{{ $file->id }}"
                              @checked($check)>
                          </td>
                          <td>
                            {{ $file->name ?? '-'}}
                          </td>
                        </tr>
                        @empty
                        @endforelse
                      </tbody>
                      <tfoot class="text-right bg-light">
                        <tr>
                          <td colspan="2" class="py-2">
                            <a href="{{ route('files') }}" class="badge badge-info">
                              <i class="fa fa-plus"></i> Tambah Dokumen Baru
                            </a>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection

@push('scripts')
<script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.all.js"></script>

<script>
  $(".btn-hapus").click(function(e) {
    e.preventDefault();
    if( event.target.getAttribute('data-name') != null ){
      Swal.fire({
        title: 'Hapus Risiko "' + event.target.getAttribute('data-name') + '"?',
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