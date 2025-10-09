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
          <li class="breadcrumb-item"><a href="{{ route('subrogations.index') }}">Subrogations</a></li>
          <li class="breadcrumb-item active">{{ ucwords($menu) }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="container">
    <h3 class="mb-4">Tambah Subrogasi Klaim</h3>

    <form action="{{ route('subrogations.store') }}" method="POST">
        @csrf

        {{-- Input Cari Klaim --}}
        <div class="mb-3 position-relative">
            <label>Cari Nomor Klaim</label>
            <input type="text" id="search-claim" class="form-control" placeholder="Ketik nomor klaim atau nama tertanggung">
            <input type="hidden" name="claim_id" id="claim_id"> {{-- ID klaim tersembunyi --}}

            {{-- Dropdown hasil pencarian --}}
            <div id="claim-results" class="list-group position-absolute w-100 shadow" style="z-index:1000; display:none; max-height:200px; overflow-y:auto;"></div>
        </div>

        <div class="mb-3">
            <label>Nama Pihak Ketiga</label>
            <input type="text" name="third_party_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tipe Pihak Ketiga</label>
            <input type="text" name="third_party_type" class="form-control">
        </div>
        <div class="mb-3">
            <label>Jumlah Subrogasi</label>
            <input type="text" 
                id="subrogation_amount_display" 
                class="form-control text-right" 
                placeholder="Masukkan jumlah subrogasi" 
                required 
                style="text-align: right;">
            <input type="hidden" name="subrogation_amount" id="subrogation_amount">
        </div>
        <div class="mb-3">
            <label>Tanggal Jatuh Tempo</label>
            <input type="date" name="due_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('subrogations.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- Script Live Search --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {
    const $display = $('#subrogation_amount_display');
    const $hidden = $('#subrogation_amount');

    // Event: Ketika user mengetik
    $display.on('input', function () {
        let val = $(this).val();

        // izinkan hanya angka dan koma
        val = val.replace(/[^0-9,]/g, '');

        // deteksi kalau koma di akhir
        const hasCommaAtEnd = val.endsWith(',');
        let parts = val.split(',');
        let integerPart = parts[0].replace(/\D/g, '');
        let decimalPart = parts[1] !== undefined ? parts[1].replace(/\D/g, '') : '';

        // format ribuan
        let formattedInt = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // gabung kembali
        let formattedVal = formattedInt;
        if (hasCommaAtEnd) {
            formattedVal += ',';
        } else if (decimalPart.length > 0) {
            formattedVal += ',' + decimalPart.substring(0, 2);
        }

        // tampilkan di input (tetap koma tampil)
        $(this).val(formattedVal);

        // ubah ke format angka untuk server (hapus titik, ubah koma jadi titik)
        let cleanVal = formattedVal.replace(/\./g, '').replace(',', '.');
        if (cleanVal.endsWith('.')) cleanVal = cleanVal.slice(0, -1);
        $hidden.val(cleanVal);
    });

    // Event: Ketika keluar dari input, format jadi 2 desimal fix
    $display.on('blur', function () {
        let val = $(this).val();

        if (val === '') return;

        // ubah semua titik dan koma jadi format angka
        let clean = val.replace(/\./g, '').replace(',', '.');
        let num = parseFloat(clean);

        if (!isNaN(num)) {
            // tampilkan dalam format ribuan dengan 2 desimal
            let formatted = num.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            $(this).val(formatted);
            $hidden.val(num.toFixed(2));
        }
    });
});

$(function() {
    let timer = null;

    $('#search-claim').on('keyup', function() {
        clearTimeout(timer);
        const query = $(this).val();

        if (query.length < 2) {
            $('#claim-results').hide();
            return;
        }

        timer = setTimeout(() => {
            $.get("{{ route('claims.search') }}", { q: query }, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(claim => {
                        html += `<a href="#" class="list-group-item list-group-item-action claim-item"
                                  data-id="${claim.id}" 
                                  data-number="${claim.claimno}"
                                  data-name="${claim.name}"
                                  data-amount="${claim.claim_amount}">
                                  <strong>${claim.claimno}</strong> - ${claim.name} (${claim.policy} - ${claim.certificate})
                                  <span class="float-end">Rp ${Number(claim.claim_amount).toLocaleString()}</span>
                                  </a>`;
                    });
                } else {
                    html = `<div class="list-group-item text-muted">Tidak ditemukan</div>`;
                }
                $('#claim-results').html(html).slideDown(100);
            });
        }, 300);
    });

    // Ketika user memilih klaim
    $(document).on('click', '.claim-item', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const number = $(this).data('number');
        const name = $(this).data('name');

        $('#claim_id').val(id);
        $('#search-claim').val(`${number} - ${name}`);
        $('#claim-results').slideUp(100);
    });

    // Klik di luar dropdown
    $(document).click(function(e) {
        if (!$(e.target).closest('#search-claim, #claim-results').length) {
            $('#claim-results').slideUp(100);
        }
    });
});



</script>

@endsection
