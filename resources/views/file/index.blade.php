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
        <div class="card card-success card-outline">
          <div class="card-body">

            <div class="row">
              <div class="col-lg-3">
                <button type="button" class="btn btn-sm bg-gradient-success px-3 rounded-pill mb-2 shadow"
                  id="button-add" data-toggle="modal" data-target="#modalAdd">
                  <i class="fa fa-plus"></i> Tambah Baru
                </button>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-hover table-sm" id="table-files">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 75px" class="border-left py-2">NO</th>
                        <th style="width: 100px">KODE</th>
                        <th style="">DOKUMEN</th>
                        <th style="width: 150px">JUMLAH PENYEBAB</th>
                        <th style="width: 150px">JUMLAH KLAIM</th>
                        <th style="width: 140px" class="border-right">AKSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($files as $file)
                      <?php
                        $deletable  = true;
                        $causes     = 0;
                        if( $file->cause_files->count() > 0 ){
                          $deletable  = false;
                          $causes     = $file->cause_files->count();
                        }
                      ?>
                      <tr>
                        <td class="text-center border-left">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <b>{{ $file->code }}</b>
                        </td>
                        <td>{{ $file->name }}</td>
                        <td class="text-center">{{ $causes }}</td>
                        <td class=" text-center">
                          {{ mt_rand(1,100) }}
                        </td>
                        <td class="text-center border-right">
                          <a id="{{ $file->id }}" href="{{ route('file.edit', ['file' => $file->id])}}"
                            class="my-1 btn btn-xs rounded-pill px-2 bg-gradient-info btn-edit {{ $deletable ? '' : 'px-4' }}">
                            <i class="fa fa-edit"></i> Ubah
                          </a>
                          @if ($deletable)
                          <form method="POST" class='d-inline' action={{ route('file.destroy', ['file'=> $file->id])}}>
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                              class="my-1 px-2 btn btn-xs rounded-pill px-2 bg-gradient-danger btn-hapus"
                              data-name="{{$file->name}}">
                              <i class="fa fa-trash"></i> Hapus
                            </button>
                          </form>
                          @endif
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="6">
                          Belum Ada Data Berkas..
                        </td>
                      </tr>
                      @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /.card -->
        <br><br><br><br><br>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->

  <input type="hidden" id="modal_name" value="{{ session()->get('modal_name') ?? null}}">

  {{-- Modal Add --}}
  <div class="modal fade" id="modalAdd" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalAddLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('file.store') }}" method="POST">
          @csrf
          <div class="modal-header bg-gradient-success">
            <span class="font-weight-bold">
              <i class="fa fa-plus"></i> TAMBAH DATA BERKAS
            </span>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="code">KODE <span class="text-danger">*</span></label>
              <input type="text" class="form-control spaceless code @error('code') is-invalid @enderror" id="code"
                name="code" placeholder="Contoh: F-001" value="{{ old('code') ?? '' }}" autocomplete="off">
              @error('code')
              <div class="invalid-feedback font-weight-bold">
                {{ $message }}!
              </div>
              @enderror
            </div>
            <div class="form-group">
              <label for="name">NAMA <span class="text-danger">*</span></label>
              <input type="text" class="form-control name @error('name') is-invalid @enderror" id="name" name="name"
                placeholder="Contoh: Kartu Keluarga / KTP / Paspor" value="{{ old('name') ?? '' }}" autocomplete="off">
              @error('name')
              <div class="invalid-feedback font-weight-bold">
                {{ $message }}!
              </div>
              @enderror
            </div>
            <div class="form-group">
              <label for="description">KETERANGAN </label>
              <textarea class="form-control" name="description" id="description"
                rows="3">{{ old('description') ?? '' }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm rounded-pill px-4 bg-gradient-danger btn-cancel"
              data-dismiss="modal">
              <i class="fa fa-times-circle"></i> Batal
            </button>
            <button type="submit" class="btn btn-sm rounded-pill px-4 bg-gradient-success btn-submit">
              <i class="fa fa-check-circle"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End of Modal Edit --}}

  {{-- Modal Edit --}}
  <div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalEditLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST" id="edit_action">
          @method('PUT')
          @csrf
          <div class="modal-header bg-gradient-info">
            <span class="font-weight-bold">
              <i class="fa fa-edit"></i> PERBARUI DATA BERKAS
            </span>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="new_code">KODE <span class="text-danger">*</span></label>
              <input type="text" class="form-control spaceless new_code @error('new_code') is-invalid @enderror"
                id="new_code" name="new_code" placeholder="Contoh: F-001" value="{{ old('new_code') ?? '' }}"
                autocomplete="off">
              @error('new_code')
              <div class="invalid-feedback font-weight-bold">
                {{ $message }}!
              </div>
              @enderror
            </div>
            <div class="form-group">
              <label for="new_name">NAMA <span class="text-danger">*</span></label>
              <input type="text" class="form-control new_name @error('new_name') is-invalid @enderror" id="new_name"
                name="new_name" placeholder="Contoh: Kartu Keluarga / KTP / Paspor" value="{{ old('new_name') ?? '' }}"
                autocomplete="off">
              @error('new_name')
              <div class="invalid-feedback font-weight-bold">
                {{ $message }}!
              </div>
              @enderror
            </div>
            <div class="form-group">
              <label for="new_description">KETERANGAN </label>
              <textarea class="form-control" name="new_description" id="new_description" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm rounded-pill px-4 bg-gradient-danger btn-cancel"
              data-dismiss="modal">
              <i class="fa fa-times-circle"></i> Batal
            </button>
            <button type="submit" class="btn btn-sm rounded-pill px-4 bg-gradient-info btn-submit">
              <i class="fa fa-check-circle"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End of Modal Edit --}}

</div>
<!-- /.content -->
@endsection

@push('scripts')

<script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.all.js"></script>

<script>
  $(function() {
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");

    let modal = $("#modal_name").val();
    if (modal) {
      $('#' + modal).modal('show');
    }

  });

  $("#button-add").click(function (e) { 
    e.preventDefault();
    $(".form-control").val("");
  });

  $(".btn-hapus").click(function(e) {
    e.preventDefault();

    if( event.target.getAttribute('data-name') != 'null' ){
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Hapus data Berkas "' + event.target.getAttribute('data-name') + '"',
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

  $('body').on('click', '.btn-edit', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $.get("{{ route('files') }}" + '/' + id + '/edit', function(data) {
      console.log(data);
      $('#modalEdit').modal('show');
    
      let action = "{{ url('') . '/files/' }}" + data.id;
      $('#new_code').val(data.code);
      $('#new_name').val(data.name);
      $('#new_description').val(data.description);
      $('#form_action').val(action);
      $('#edit_action').attr('action', action);
    });
  });

  $(".btn-cancel").click(function(e) {
    e.preventDefault();
    $(".is-invalid").removeClass("is-invalid");
    $(".btn-submit").attr('disabled', true);
  });


</script>
@endpush