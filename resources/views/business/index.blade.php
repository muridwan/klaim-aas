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
                  <table class="table table-hover table-sm" id="data-table">
                    <thead class="text-center bg-gradient-success">
                      <tr>
                        <th style="width: 75px" class="border-left py-2">NO</th>
                        <th>KODE</th>
                        <th>NAMA</th>
                        <th style="width: 200px">&Sigma; PENYEBAB KERUGIAN</th>
                        <th style="width: 150px" class="border-right">&Sigma; KLAIM</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($businesses as $business)
                      <tr>
                        <td class="text-center border-left">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <a href="{{ route('business.detail', ['uuid' => $business->uuid]) }}">
                            <b>{{ $business->code ?? ''}}</b>
                          </a>
                        </td>
                        <td>{{ $business->name ?? '' }}</td>
                        <td class="text-center">{{ $business->causes->count() ?? 0 }}</td>
                        <td class="text-center border-right">{{ rand(1,100) }}</td>
                      </tr>
                      @empty
                      @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="5"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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
        <form action="{{ route('business.store') }}" method="POST">
          @csrf
          <div class="modal-header bg-gradient-success">
            <span class="font-weight-bold">
              <i class="fa fa-plus"></i> TAMBAH DATA KELAS BISNIS
            </span>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="code">KODE <span class="text-danger">*</span></label>
              <input type="text" class="form-control spaceless code @error('code') is-invalid @enderror" id="code"
                name="code" placeholder="Contoh: COB-001" value="{{ old('code') ?? '' }}" autocomplete="off">
              @error('code')
              <div class="invalid-feedback font-weight-bold">
                {{ $message }}!
              </div>
              @enderror
            </div>
            <div class="form-group">
              <label for="name">NAMA <span class="text-danger">*</span></label>
              <input type="text" class="form-control name @error('name') is-invalid @enderror" id="name" name="name"
                placeholder="Contoh: Kebakaran" value="{{ old('name') ?? '' }}" autocomplete="off">
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
    // dataTable
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

    // Modal
    let modal = $("#modal_name").val();
    if (modal) {
      $('#' + modal).modal('show');
    }

  });
</script>
@endpush