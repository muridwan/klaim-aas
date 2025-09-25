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
        <h1 class="mt-1">{{ ucwords($title . " - ". $peril->name) }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item"><a href="{{ route('perils') }}"> Risiko </a></li>
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
        <div class="card card-success card-outline">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover" id="">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 50px" class="border-left py-2">NO</th>
                        <th style="width: 125px">KODE</th>
                        <th>KANTOR OPERASIONAL </th>
                        <th style="width: 125px">NOMINAL (Rp)</th>
                        <th style="width: 125px">AKTIF?</th>
                        <th style="width: 125px">SIMPAN</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($offices as $office)
                      <?php 
                          $checked  = false;
                          $amount   = 0;
                          if( !empty($office->limit) ){
                            if( $office->limit->peril_id == $peril->id )
                            {
                              $checked = true;
                              $amount = $office->limit->amount;
                            }
                          }
                        ?>
                      <input type="hidden" id="uuid{{$loop->iteration}}" value="{{ $office->uuid }}">
                      <input type="hidden" id="office{{$loop->iteration}}" value="{{ $office->name }}">
                      <input type="hidden" id="amount{{$loop->iteration}}" value="{{ $amount ?? 0 }}">
                      <input type="hidden" id="status{{$loop->iteration}}" value="{{ ($checked) ? 1 : 0 }}">
                      <tr id="row{{$loop->iteration}}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <b>{{ $office->code ?? '-' }}</b>
                        </td>
                        <td>{{ $office->name ?? '-' }}</td>
                        <td class="text-right">
                          {{ number_format( $amount ) }}
                        </td>
                        <td class="text-center">
                          @if ($checked)
                          <i class="fa fa-check-circle text-success"></i>
                          @else
                          -
                          @endif
                        </td>
                        <td class="text-center">
                          <button id="{{$loop->iteration}}" type="button"
                            class="badge badge-sm bg-gradient-warning px-2 btn-edit" data-toggle="modal"
                            data-target="#exampleModal">
                            <i class="fa fa-edit"></i> Ubah
                          </button>
                        </td>
                      </tr>
                      @empty
                      @endforelse
                    <tfoot>
                      <tr>
                        <td colspan="6"></td>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="exampleModalLabel">Perbarui Limit - {{ $peril->name }}</h5>
      </div>
      <div class="modal-body">
        <form action="{{ route('limit.update', []) }}" method="POST" autocomplete="off">
          @csrf
          <div class="form-group row">
            <label for="lableOffice" class="col-sm-5 col-form-label">KANTOR OPERASIONAL</label>
            <div class="col-sm-7">
              <input type="text" class="form-control-plaintext" id="lableOffice" value="Cabang Syariah Aceh" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputAmount" class="col-sm-5 col-form-label">NOMINAL (RP)</label>
            <div class="col-sm-7">
              <input type="amount" class="form-control" id="inputAmount">
            </div>
          </div>
          <div class="form-group row">
            <label for="isInactive" class="col-sm-5 col-form-label">AKTIF</label>
            <div class="col-sm-7">
              <div class="form-group form-check mt-2">
                <input type="checkbox" class="form-check-input" id="isInactive" name="isInactive" value="1"
                  @checked(!empty($peril->inactive_date))>
                <label class="form-check-label" for="isInactive">Nonaktifkan Risiko Ini</label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm rounded-pill px-3 btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> BATAL
        </button>
        <button type="button" class="btn btn-sm rounded-pill px-3 bg-gradient-success">
          <i class="fa fa-check"></i> SIMPAN
        </button>
      </div>
    </div>
  </div>
</div>
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

  $(".btn-edit").click(function (e) { 
    e.preventDefault();
    // console.log(this.id);
    let id = this.id;
    let office = $("#office"+id).val();
    let amount = $("#amount"+id).val();
    let status = $("#status"+id).val();
    
    $("#lableOffice").val(office);
    $("#inputAmount").val(amount);
    if( status == 1 ){
      $("#isInactive").attr("checked", true);
    }else{
      $("#isInactive").removeAttr("checked");
    }
  });
</script>
@endpush