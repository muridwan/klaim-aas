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
          <li class="breadcrumb-item active">{{ ucwords($menu) }}</li>
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
        <div class="card card-success card-outline">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover" id="data-table">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 50px" class="border-left py-2">NO</th>
                        <th style="width: 125px">KODE</th>
                        <th>KANTOR </th>
                        <th style="width: 150px">&Sigma; OUTLET</th>
                        <th style="width: 150px" class="border-right">&Sigma; KLAIM</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $sum_outlets = 0;
                        $sum_claims = 0; ?>
                      @forelse ($offices as $office)
                      <?php $claim = $office->claims->count();
                        $outlets = count($office->outlets) ?>
                      <tr>
                        <td class="text-center border-left">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <b class="text-success">{{ $office->code ?? '-' }}</b>
                        </td>
                        <td>{{ $office->name ?? '-' }}</td>
                        <td class="text-center">{{ $outlets }} </td>
                        <td class="text-center border-right">{{ $claim }} </td>
                      </tr>
                      <?php
                        $sum_outlets += $outlets;
                        $sum_claims  += $claim;
                        ?>
                      @empty
                      @endforelse

                    </tbody>
                    <tfoot class="text-center bg-light font-weight-bold">
                      <tr>
                        <td colspan="3"></td>
                        <td>{{ $sum_outlets }}</td>
                        <td>{{ $sum_claims }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div><!-- /.card -->
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
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