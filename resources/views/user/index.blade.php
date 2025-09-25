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
          <li class="breadcrumb-item"><a href="{{ route('perils') }}"> Risiko </a> </li>
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
                        <th style="width: 150px">JUMLAH RISIKO</th>
                        <th style="width: 150px">JUMLAH KLAIM</th>
                        <th style="width: 140px">AKSI</th>
                      </tr>
                    </thead>

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