@extends('_layout.app')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
          <li class="breadcrumb-item"><a href="{{ route('roles') }}"> Pengguna Sistem </a> </li>
          <li class="breadcrumb-item active">{{ ucwords($title ) . ' : ' . $_GET['role'] }}</li>
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
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-hover table-sm" id="data-table">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 75px" class="border-left py-2">NO</th>
                        <th style="width: 100px">NIRP</th>
                        <th style="">NAMA</th>
                        <th style="">KANTOR</th>
                        <th style="">POSISI</th>
                        <th style="">AKSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($users as $user)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <b>{{ $user->code }}</b>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->main_position->office->name ?? '-' }}</td>
                        <td>{{ $user->main_position->name ?? '-' }}</td>
                        <td class="text-center">
                          <a id="" href="" class="my-1 btn btn-xs rounded-pill px-2 bg-gradient-info btn-edit disabled">
                            <i class="fa fa-edit"></i> Ubah
                          </a>
                          <form method="POST" class='d-inline' action="">
                            @csrf
                            @method('DELETE')
                            <button disabled type="submit"
                              class="my-1 px-2 btn btn-xs rounded-pill px-2 bg-gradient-danger btn-hapus" data-name="">
                              <i class="fa fa-trash"></i> Hapus
                            </button>
                          </form>
                        </td>
                      </tr>
                      @empty

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



</div>
<!-- /.content -->
@endsection

@push('scripts')
<script src="{{ asset('adminLTE') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
  $(function() {
    $('#data-table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,

    });

    $("th").addClass("align-middle");
    $("td").addClass("align-middle");
  });
</script>
@endpush