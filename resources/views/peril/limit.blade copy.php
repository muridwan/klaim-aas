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
            {{-- Kantor Cabang --}}
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
                        <th style="width: 125px">AKSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($offices as $office)
                      <?php
                        $checked  = false;
                        $amount   = 0;
                        $description   = '';
                        if (!empty($office->limits)) {
                          foreach ($office->limits as $limit) {
                            if ($limit->peril_id == $peril->id) {
                              $checked      = empty($limit->inactive_date) ? true : false;
                              $amount       = $limit->amount;
                              $description  = $limit->description;
                              break;
                            }
                          }
                        }
                        ?>
                      <input type="hidden" id="uuid{{$loop->iteration}}" value="{{ $office->uuid }}">
                      <input type="hidden" id="office{{$loop->iteration}}" value="{{ $office->name }}">
                      <input type="hidden" id="amount{{$loop->iteration}}" value="{{ $amount ?? 0 }}">
                      <input type="hidden" id="status{{$loop->iteration}}" value="{{ ($checked) ? 1 : 0 }}">
                      <input type="hidden" id="description{{$loop->iteration}}" value="{{ $description }}">
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
                            class="btn btn-xs bg-gradient-warning btn-edit rounded-pill px-2" data-toggle="modal"
                            data-target="#limitModal">
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
            {{-- END of Kantor Cabang --}}

            {{-- Kantor Pusat --}}
            <div class="row">
              <div class="col-12">
                <hr>
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 50px" class="border-left py-2">NO</th>
                        <th>POSISI</th>
                        <th style="width: 125px">NOMINAL (Rp)</th>
                        <th style="width: 125px">AKTIF?</th>
                        <th style="width: 125px" class="border-right">AKSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($headquarters as $hq)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $hq->position->name }}</td>
                        <td class="text-right">{{ number_format($hq->amount) }}</td>
                        <td class="text-center">AKTIF?</td>
                        <td class="text-center">
                          <button id="{{$loop->iteration}}" type="button"
                            class="btn btn-xs bg-gradient-warning btn-edit rounded-pill px-2" data-toggle="modal"
                            data-target="#limitModal">
                            <i class="fa fa-edit"></i> Ubah
                          </button>
                        </td>
                      </tr>
                      @empty

                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            {{-- END of Kantor Pusat --}}
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
<form action="{{ route('update_limit') }}" method="POST" autocomplete="off">
  @csrf
  <input type="hidden" id="uuid" name="uuid" value="{{ $peril->uuid }}">
  <input type="hidden" id="office_uuid" name="office_uuid">

  <div class="modal fade" id="limitModal" tabindex="-1" data-backdrop="static" aria-labelledby="limitModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content ">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="limitModalLabel">
            <b> <i class="fa fa-edit"></i> PERBARUI LIMIT - {{ $peril->name }}</b>
          </h5>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="office_name" class="col-sm-4 col-form-label">KANTOR OPERASIONAL</label>
            <div class="col-sm-8">
              <input type="text" class="form-control-plaintext" id="office_name" value="Cabang Syariah Aceh" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label for="amount" class="col-sm-4 col-form-label">NOMINAL (RP)</label>
            <div class="col-sm-3">
              <input type="text" class="form-control rupiah" id="amount" name="amount">
            </div>
          </div>

          <div class="form-group row">
            <label for="description" class="col-sm-4 col-form-label">DESKRIPSI</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label for="isActive" class="col-sm-4 col-form-label">STATUS</label>
            <div class="col-sm-8">
              <div class="form-group form-check mt-2">
                <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1">
                <label class="form-check-label" for="isActive">AKTIF</label>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm rounded-pill px-3 btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
          </button>
          <button type="submit" class="btn btn-sm rounded-pill px-3 bg-gradient-success">
            <i class="fa fa-check"></i> SIMPAN
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

  $(".btn-edit").click(function(e) {
    e.preventDefault();
    // console.log(this.id);
    let id = this.id;
    let office_uuid = $("#uuid" + id).val();
    let office_name = $("#office" + id).val();
    let amount      = $("#amount" + id).val();
    let status      = $("#status" + id).val();
    let description = $("#description" + id).val();

    // Value of modals
    $("#office_uuid").val(office_uuid);
    $("#office_name").val(office_name);
    $("#description").val(description);
    $("#amount").val(amount);
    if (status == 1) {
      $("#isActive").attr("checked", true);
    } else {
      $("#isActive").removeAttr("checked");
    }

    $('.rupiah').each(function(i, obj) {
      this.value = formatRupiah(this.value);
    });
  });
</script>
@endpush