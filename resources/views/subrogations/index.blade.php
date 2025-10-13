@extends('_layout.app')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<style>
#loader {
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

#loader.show-loader {
  opacity: 1;
  pointer-events: auto;
}
</style>

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

<div class="container">
    <h3 class="mb-4">Daftar Subrogasi Klaim</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
      <a href="{{ route('subrogations.create') }}" class="btn btn-primary shadow-sm">
          <i class="fa fa-plus"></i> Tambah Subrogasi
      </a>

      <div class="d-flex flex-wrap gap-2 align-items-center">
        <input type="text" id="search" class="form-control" 
              placeholder="Cari No Klaim / Pihak Ketiga..." style="width: 240px;">

        <select id="status" class="form-control" style="width: 160px;">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="submitted">Submitted</option>
            <option value="paid">Paid</option>
            <option value="closed">Closed</option>
        </select>
      </div>
    </div>

    <div id="table-container" class="position-relative">
      <div id="table-container" class="position-relative">
      {{-- Loader --}}
      <div id="loader"
          class="d-flex justify-content-center align-items-center bg-white bg-opacity-75"
          style="
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
          ">
          <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
              <span class="visually-hidden">Loading...</span>
          </div>
      </div>


      <div id="table-data">
          @include('subrogations.partials.table', ['subrogations' => $subrogations])
      </div>
    </div>



    {{-- <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>No Klaim</th>
                <th>Pihak Ketiga</th>
                <th>Jumlah Subrogasi</th>
                <th>Status</th>
                <th>Jatuh Tempo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($subrogations as $key => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->claim->claimno ?? '-' }}</td>
                <td>{{ $item->third_party_name }}</td>
                <td>Rp {{ number_format($item->subrogation_amount, 2, ',', '.') }}</td>
                <td><span class="badge bg-secondary">{{ $item->status }}</span></td>
                <td>{{ $item->due_date ?? '-' }}</td>
                <td>
                    <a href="{{ route('subrogations.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('subrogations.destroy', $item->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Belum ada data subrogasi</td></tr>
        @endforelse
        </tbody>
    </table> --}}

    {{-- {{ $subrogations->links() }} --}}
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('adminLTE') }}/plugins/jquery/jquery.min.js"></script> --}}
<script>
$(document).ready(function() {    
    // === Fungsi tampil/sembunyi loader ===
    function showLoader(show = true) {
      if (show) {
        $('#loader').addClass('show-loader');
      } else {
        $('#loader').removeClass('show-loader');
      }
    }

    // === Fungsi ambil data dari server ===
    function fetchData(page = 1) {
        showLoader(true);

        let q = $('#search').val();
        let status = $('#status').val();

        $.ajax({
            url: "{{ route('subrogations.search') }}",
            type: "GET",
            data: { page, q, status },
            success: function (data) {
                $('#table-data').html(data);
            },
            error: function (xhr) {
                console.error("Error:", xhr.responseText);
                alert("Terjadi kesalahan saat memuat data (status " + xhr.status + ")");
            },
            complete: function () {
                showLoader(false); // selalu hilangkan loader
            }
        });
    }

    // === Event real-time search ===
    let timer = null;
    $('#search').on('keyup', function() {
        clearTimeout(timer);
        timer = setTimeout(() => fetchData(1), 400);
    });

    // === Event filter status ===
    $('#status').on('change', function() {
        fetchData(1);
    });

    // === Pagination (AJAX) ===
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchData(page);
    });
    
});
</script>
@endpush