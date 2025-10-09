@extends('_layout.app')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('section')
<div class="container">
    <h3 class="mb-4">Edit Subrogasi Klaim</h3>

    <form action="{{ route('subrogations.update', $subrogation->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Klaim</label>
            <select name="claim_id" class="form-select" required>
                @foreach($claims as $claim)
                    <option value="{{ $claim->id }}" {{ $subrogation->claim_id == $claim->id ? 'selected' : '' }}>
                        {{ $claim->claim_number }} - {{ $claim->insured_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nama Pihak Ketiga</label>
            <input type="text" name="third_party_name" value="{{ $subrogation->third_party_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tipe Pihak Ketiga</label>
            <input type="text" name="third_party_type" value="{{ $subrogation->third_party_type }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Jumlah Subrogasi</label>
            <input type="number" step="0.01" name="subrogation_amount" value="{{ $subrogation->subrogation_amount }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Tertagih</label>
            <input type="number" step="0.01" name="recovered_amount" value="{{ $subrogation->recovered_amount }}" class="form-control">
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
@endsection
