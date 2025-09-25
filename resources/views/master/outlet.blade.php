@extends('_layout.app')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
            <div class="row border-bottom pb-3 mb-3">
           
              <div class="col my-1">
                <select class="custom-select select2" id="institution" name="institution">
                  <option value="">Pilih SumBis:</option>
                  @foreach ($institutions as $institution)
                  <option @selected($institution->code == $reqs->institution) value="{{ $institution->code }}">
                    {{ $institution->name }}
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="col my-1">
                <select class="custom-select select2" id="region" name="region">
                  <option value="">Pilih Kanwil:</option>
                  @foreach ($regions as $region)
                  <option @selected($region->code == $reqs->region) value="{{ $region->code }}">
                    {{ $region->name }}
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="col my-1">
                <select class="custom-select select2" id="area" name="area">
                  <option value="">Pilih Area:</option>
                  @foreach ($areas as $area)
                  <option @selected($area->code == $reqs->area) value="{{ $area->code }}">
                    {{-- {{ "[$area->code] " . $area->name }} --}}
                    {{ $area->name }}
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="col my-1">
                <select class="custom-select select2" id="cbm" name="cbm">
                  <option value="">Pilih CBM:</option>
                  @foreach ($cbms as $cbm)
                  <option @selected($cbm->code == $reqs->cbm) value="{{ $cbm->code }}">
                    {{ $cbm->name }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col my-1">
                <select class="custom-select select2" id="ubm" name="ubm">
                  <option value="">Pilih UBM:</option>
                  @foreach ($ubms as $ubm)
                  <option @selected($ubm->code == $reqs->ubm) value="{{ $ubm->code }}">{{ $ubm->name }}</option>
                  @endforeach
                </select>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover" id="data-table">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 30px" class="border-left py-2">NO </th>
                        <th style="width: 125px">KODE </th>
                        <th>KANTOR/OUTLET </th>
                        <th>CABANG AAS </th>

                        <?php if ($level <= 1): ?>
                        <th style="width: 100px">&Sigma; AREA</th>
                        <?php endif; ?>
                        <?php if ($level <= 2): ?>
                        <th style="width: 100px">&Sigma; CBM</th>
                        <?php endif; ?>
                        <?php if ($level <= 3): ?>
                        <th style="width: 100px">&Sigma; UBM</th>
                        <?php endif; ?>
                        <?php if ($level <= 4): ?>
                        <th style="width: 100px">&Sigma; OUTLET</th>
                        <?php endif; ?>
                        <th style="width: 100px" class="border-right">&Sigma; KLAIM</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sum_level2 = 0;
                        $sum_level3 = 0;
                        $sum_level4 = 0;
                        $sum_level5 = 0;
                        $sum_level6 = 0;
                        // $office     = $outlets->first()->office->name ?? '-';
                        ?>
                      @forelse ($outlets as $outlet)
                      <?php
                        // Menghitung jumlah level 2
                        if( !isset($_GET['ubm']) ){
                          $level2Count = count($outlet->childs);
                        }

                        // Menghitung jumlah level 3
                        if( !isset($_GET['cbm']) ){
                          $level3Count = 0;
                          foreach ($outlet->childs as $child) {
                            if (isset($child->childs)) {
                              $level3Count  += count($child->childs);
                            }
                          }
                        }

                        // Menghitung jumlah level 4
                        if( !isset($_GET['area']) ){
                          $level4Count = 0;
                          foreach ($outlet->childs as $child) {
                            if (isset($child->childs)) {
                              foreach ($child->childs as $subChild) {
                                if (isset($subChild->childs)) {
                                  $level4Count += count($subChild->childs);
                                }
                              }
                            }
                          }
                        }

                        // Menghitung jumlah level 5
                        if( !isset($_GET['region']) ){
                          $level5Count = 0;
                          foreach ($outlet->childs as $child) {
                            if (isset($child->childs)) {
                              foreach ($child->childs as $subChild) {
                                if (isset($subChild->childs)) {
                                  foreach ($subChild->childs as $subSubChild) {
                                    if (isset($subSubChild->childs)) {
                                      $level5Count += count($subSubChild->childs);
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }

                        $sum_level2 += $level2Count ?? 0;
                        $sum_level3 += $level3Count ?? 0;
                        $sum_level4 += $level4Count ?? 0;
                        $sum_level5 += $level5Count ?? 0;

                        $level6Count = mt_rand(1, 100);
                        $sum_level6 += $level6Count ?? 0;
                        ?>
                      <tr>
                        <td class="text-center border-left">{{ $loop->iteration }}</td>
                        <td class="text-center"><b>{{ $outlet->code ?? '-' }}</b></td>
                        <td>{{ $outlet->name ?? '-' }}</td>
                        {{-- <td>{{ nama_kantor($outlet->office->name) ?? '-' }}</td> --}}
                        <td class="text-center">{{ nama_kantor($outlet->office->name) ?? nama_kantor($office->name) }}
                        </td>
                        <?php if ($level <= 4): ?>
                        <td class="text-center">{{ $level2Count ?? 0 }} </td>
                        <?php endif ?>
                        <?php if ($level <= 3): ?>
                        <td class="text-center">{{ $level3Count ?? 0 }} </td>
                        <?php endif ?>
                        <?php if ($level <= 2): ?>
                        <td class="text-center">{{ $level4Count ?? 0 }} </td>
                        <?php endif ?>
                        <?php if ($level <= 1): ?>
                        <td class="text-center">{{ $level5Count ?? 0 }} </td>
                        <?php endif ?>
                        <td class="text-center border-right">{{$level6Count . " D" }} </td>
                      </tr>
                      @empty
                      @endforelse
                    <tfoot class="text-center bg-light font-weight-bold">
                      <tr>
                        <td colspan="4"></td>
                        <?php if ($level <= 4): ?>
                        <td>{{ $sum_level2 }}</td>
                        <?php endif ?>
                        <?php if ($level <= 3): ?>
                        <td>{{ $sum_level3 }}</td>
                        <?php endif ?>
                        <?php if ($level <= 2): ?>
                        <td>{{ $sum_level4 }}</td>
                        <?php endif ?>
                        <?php if ($level <= 1): ?>
                        <td>{{ $sum_level5 }}</td>
                        <?php endif ?>
                        <td class="text-center">{{ $sum_level6 . " D" }} </td>
                      </tr>
                    </tfoot>
                    </tbody>
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
<!-- DataTable -->
<script src="{{ asset('adminLTE') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('adminLTE') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<!-- Select2 -->
<script src="{{ asset('adminLTE') }}/plugins/select2/js/select2.full.min.js"></script>

<script>
  $(function() {
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%'
    });

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

  $("#institution").change(function(e) {
    e.preventDefault();
    const selectedValue = $(this).val();
    if (selectedValue) {
      $("#modalSpinner").modal('show');
      // Buat URL berdasarkan route Laravel
      const baseUrl = "{{ url('/outlets') }}";
      const url     = `${baseUrl}?institution=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });

  $("#region").change(function(e) {
    e.preventDefault();
    const selectedValue = $(this).val();
    const institution   = $("#institution").val();
    if (selectedValue) {
      $("#modalSpinner").modal('show');
      // Buat URL berdasarkan route Laravel
      const baseUrl = "{{ url('/outlets') }}";
      const url     = `${baseUrl}?institution=${institution}&region=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });

  $("#area").change(function(e) {
    e.preventDefault();
    const selectedValue = $(this).val();
    const institution   = $("#institution").val();
    const region        = $("#region").val();;
    if (selectedValue) {
      $("#modalSpinner").modal('show');
      // Buat URL berdasarkan route Laravel
      const baseUrl = "{{ url('/outlets') }}";
      const url     = `${baseUrl}?institution=${institution}&region=${region}&area=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });

  $("#cbm").change(function(e) {
    e.preventDefault();
    const selectedValue = $(this).val();
    const institution   = $("#institution").val();
    const region        = $("#region").val();
    const area          = $("#area").val();
    if (selectedValue) {
      $("#modalSpinner").modal('show');
      const baseUrl = "{{ url('/outlets') }}";
      const url     = `${baseUrl}?institution=${institution}&region=${region}&area=${area}&cbm=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });

  $("#ubm").change(function(e) {
    e.preventDefault();
    const selectedValue = $(this).val();
    const institution   = $("#institution").val();
    const region        = $("#region").val();
    const area          = $("#area").val();
    const cbm           = $("#cbm").val();
    if (selectedValue) {
      $("#modalSpinner").modal('show');
      // Buat URL berdasarkan route Laravel
      const baseUrl = "{{ url('/outlets') }}";
      const url = `${baseUrl}?institution=${institution}&region=${region}&area=${area}&cbm=${cbm}&ubm=${selectedValue}`; // URL dinamis
      window.location.href = url; // Redirect ke URL yang dihasilkan
    }
  });
</script>
@endpush