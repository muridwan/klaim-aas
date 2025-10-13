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
    <h3 class="mb-4">Edit Subrogasi Klaim</h3>

    <form action="{{ route('subrogations.update', $subrogation->id) }}" method="POST">
        @csrf @method('PUT')

        {{-- <div class="mb-3">
            <label>Klaim</label>
            <select name="claim_id_sel" class="form-select" required>
                @foreach($claims as $claim)
                    <option value="{{ $claim->id }}" {{ $subrogation->claim_id == $claim->id ? 'selected' : '' }}>
                        {{ $claim->claimno }} - {{ $claim->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        {{-- Input Cari Klaim --}}
        <div class="mb-3 position-relative">
            <label>Cari Nomor Klaim</label>
            <input type="text" id="search-claim" class="form-control" placeholder="Ketik nomor klaim atau nama tertanggung" value="{{ $subrogation->claim->claimno . ' - ' . $subrogation->claim->name  ?? '' }}" ><!-- isi otomatis -->
            <input type="hidden" name="claim_id" id="claim_id" value="{{ $subrogation->claim_id ?? '' }}"> {{-- ID klaim tersembunyi --}}

            {{-- Dropdown hasil pencarian --}}
            <div id="claim-results" class="list-group position-absolute w-100 shadow" style="z-index:1000; display:none; max-height:200px; overflow-y:auto;"></div>
        </div>

        <div class="mb-3">
            <label>Nama Pihak Ketiga</label>
            <input type="text" id="third_party_name" name="third_party_name" value="{{ $subrogation->third_party_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tipe Pihak Ketiga</label>
            <input type="text" name="third_party_type" value="{{ $subrogation->third_party_type }}" class="form-control">
        </div>

        {{-- <div class="mb-3">
            <label>Jumlah Subrogasi</label>
            <input type="number" step="0.01" name="subrogation_amount" value="{{ $subrogation->subrogation_amount }}" class="form-control" required>
        </div> --}}
        <div class="mb-3">
            <label>Jumlah Subrogasi</label>
            <input type="text" 
                id="subrogation_amount_display" 
                value="{{ $subrogation->subrogation_amount }}"
                class="form-control text-right" 
                placeholder="Masukkan jumlah subrogasi" 
                required 
                style="text-align: right;">
            <input type="hidden" name="subrogation_amount" id="subrogation_amount" value="{{ $subrogation->subrogation_amount }}">
        </div>
        {{-- <div class="mb-3">
            <label>Jumlah Tertagih</label>
            <input type="number" step="0.01" name="recovered_amount" value="{{ $subrogation->recovered_amount }}" class="form-control">
        </div> --}}

        <div class="mb-3">
            <label>Jumlah Tertagih</label>
            <input type="text" 
                id="recovered_amount_display" 
                value="{{ $subrogation->recovered_amount }}"
                class="form-control text-right" 
                placeholder="Masukkan jumlah tertagih" 
                required 
                style="text-align: right;">
            <input type="hidden" name="recovered_amount" id="recovered_amount" value="{{ $subrogation->recovered_amount }}">
        </div>

        <div class="mb-3">
            <label>Tanggal Jatuh Tempo</label>
            <input type="date" name="due_date" value="{{ $subrogation->due_date }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="draft" {{ $subrogation->status == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ $subrogation->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="paid" {{ $subrogation->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="closed" {{ $subrogation->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="notes" class="form-control">{{ $subrogation->notes }}</textarea>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('subrogations.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const $display = $('#subrogation_amount_display');
    const $hidden = $('#subrogation_amount');

    const $display_recovered = $('#recovered_amount_display');
    const $hidden_recovered = $('#recovered_amount');

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
    $display_recovered.on('input', function () {
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
        $hidden_recovered.val(cleanVal);
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

    $display_recovered.on('blur', function () {
        let val = $(this).val();

        if (val === '') return;

        // ubah semua titik dan koma jadi format angka
        let clean = val.replace(/\./g, '').replace(',', '.');
        let num = parseFloat(clean);

        if (!isNaN(num)) {
            // tampilkan dalam format ribuan dengan 2 desimal
            let formatted = num.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            $(this).val(formatted);
            $hidden_recovered.val(num.toFixed(2));
        }
    });

     // Saat halaman pertama kali dimuat → format ulang tampilan
    let initValue = parseFloat($('#subrogation_amount').val());
    if (!isNaN(initValue)) {
        $('#subrogation_amount_display').val(initValue.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }

    let initValue_recovered = parseFloat($('#recovered_amount').val());
    if (!isNaN(initValue_recovered)) {
        $('#recovered_amount_display').val(initValue_recovered.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }
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
        const amount = $(this).data('amount');

        $('#claim_id').val(id);
        $('#search-claim').val(`${number} - ${name}`);
        $('#third_party_name').val(`${name}`)
        $('#claim-results').slideUp(100);
        $('#subrogation_amount_display').val(`${Number(amount).toLocaleString()}`);
        $('#subrogation_amount').val(`${Number(amount).toLocaleString()}`);
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