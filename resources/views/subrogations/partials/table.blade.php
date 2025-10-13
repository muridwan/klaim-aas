<table class="table table-bordered">
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
            <td>{{ $item->third_party_name ?? '-' }}</td>
            <td>Rp {{ number_format($item->subrogation_amount, 2, ',', '.') }}</td>
            <td><span class="badge bg-secondary">{{ $item->status ?? '-' }}</span></td>
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
</table>

<div class="mt-3">
    {{ $subrogations->links('pagination::bootstrap-4') }}{{-- {!! $subrogations->links() !!} --}}
</div>
