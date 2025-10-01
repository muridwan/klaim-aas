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

    <div class="row">
      <div class="col-lg-12">
        <div class="card card-success card-outline">
          <div class="card-body">

            @if ($menu == 'pengajuan')
            <div class="row mb-3">
              <div class="col-lg-3">
                <button type="button" class="btn btn-sm bg-gradient-success rounded-pill px-4" data-toggle="modal"
                  data-target="#limitModal">
                  <i class="far fa-paper-plane"></i> Ajukan Klaim
                </button>
              </div>
            </div>
            @endif

            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover" id="data-table">
                    <thead class="text-center bg-success" style="font-size: 15px">
                      <tr>
                        <th style="width: 30px" class="border-left py-3">NO</th>
                        <th style="width: 100px">KODE <br> PENGAJUAN</th>
                        <th>NOMOR <br> POLIS</th>
                        <th>NOMOR <br> SERTIFIKAT</th>
                        <th>KANTOR <br> OPERASIONAL</th>
                        <th>NAMA <br> PESERTA</th>
                        <th>NILAI <br>PENGAJUAN</th>
                        <th>TANGGAL <br> PENGAJUAN</th>
                        <th>TANGGAL <br> KEJADIAN</th>
                        <th>TANGGAL <br> PERUBAHAN</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($claims as $claim)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <a class="font-weight-bold" href="{{ route('claim.detail', ['uuid' => $claim->uuid]) }}">
                            {{ $claim->code ?? '-' }}
                          </a>
                        </td>
                        <td class="text-center">{{ $claim->policy }}</td>
                        <td class="text-center">{{ $claim->certificate ?? '-' }}</td>
                        <td class="text-center">{{ nama_kantor($claim->office->name) }}</td>
                        <td>{{ after_qq($claim->name) }}</td>
                        <td class="text-center">{{ number_format($claim->claim_amount) }}</td>
                        <td class="text-center">{{ $claim->incident_date }}</td>
                        <td class="text-center">{{ date('Y-m-d h:m:s', strtotime($claim->created_at)) }}</td>
                        <td class="text-center">{{ date('Y-m-d h:m:s', strtotime($claim->updated_at)) }}</td>
                      </tr>
                      @empty
                      @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="10"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Modal -->
<form action="{{ route('policy_validation') }}" method="POST" autocomplete="off">
  @csrf
  <div class="modal fade" id="limitModal" tabindex="-1" data-backdrop="static" aria-labelledby="limitModalLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content ">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="limitModalLabel">
            <b><i class="fa fa-info-circle"></i> VALIDASI KLAIM</b>
          </h5>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="policy" class="col-sm-5 col-form-label">NOMOR POLIS <span class="reqs">*</span></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="policy" name="policy" value="">
              <div class="invalid-feedback font-weight-bold">
                Nomor Polis wajib diisi
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="certificate" class="col-sm-5 col-form-label">NOMOR SERTIFIKAT <span
                class="reqs">*</span></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="certificate" name="certificate">
              <div class="invalid-feedback font-weight-bold">
                Nomor Sertifikat wajib diisi
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-form rounded-pill px-3 btn-secondary" id="btn-cancel"
            data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
          </button>
          <button type="submit" class="btn btn-sm btn-form rounded-pill px-3 bg-gradient-success btn-submit" id="btn-validation">
            <i class="fa fa-search"></i> VALIDASI
          </button>

        </div>
      </div>
    </div>
  </div>
</form>
<!-- End of Modal -->

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

  // Tombol cancel
  $("#btn-cancel").click(function (e) { 
    setTimeout(() => {     
      $(".form-control").removeClass('is-invalid');
    }, 1000);
  });

  // Tombol submit
  $("#btn-validation").click(function (e) {
    e.preventDefault(); 
    let policy      = $("#policy").val();
    let certificate = $("#certificate").val();

    if( policy != '' && certificate != '' ){
      $(".btn-form").attr('disabled', true);
      $(this).closest('form').submit();
      $("#modalSpinner").modal('show');

      let dots = "";
      let loadingText = document.getElementById("btn-validation");
      setInterval(() => {
        dots = dots.length < 7 ? dots + "." : "";
        loadingText.textContent = "Loading" + dots;
      }, 500);

      setTimeout(() => {
        $(".btn-form").removeAttr(disabled);
      }, 5000);
    }else{
      if( policy == '' ){
        $("#policy").addClass('is-invalid');
      }
      if( certificate == '' ){
        $("#certificate").addClass('is-invalid');
      }
    }

  });

  // Key Up
  $(".form-control").keyup(function (e) { 
    $(this).removeClass("is-invalid");
  });
</script>
@endpush