@extends('_layout.app')


@section('section')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid border-bottom pb-1">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="mt-1">{{ $cause->name . " (". $cause->institution->code .") - " . ucwords($title)}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item">
            <a href="{{ route('businesses') }}">
              Data Kelas Bisnis
            </a>
          </li>
          <li class="breadcrumb-item">
            <a
              href="{{ route('business.detail', ['uuid' => $cause->business->uuid, 'institution' => $cause->institution->code]) }}">
              {{-- {{ $cause->business->code }} --}}
              {{ $cause->business->code ." (". $cause->institution->code .")"}}
            </a>
          </li>
          <li class="breadcrumb-item active">{{ ucwords($cause->code) }}</li>
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


        <div class="row mb-2">
          <div class="col-lg-3">
            <form method="POST" class='d-inline' action="{{ route('cause.destroy', ['cause' => $cause->id]) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-xs bg-gradient-danger btn-hapus px-3 rounded-pill"
                data-name="{{$cause->name}}">
                <i class=" fa fa-trash"></i> Hapus Penyebab Kerugian
              </button>
            </form>
          </div>
        </div>


        <div class="card card-success card-outline">
          <form action="{{ route('cause.update', ['cause'=> $cause->id]) }}" method="POST" autocomplete="off">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="code">KODE <span class="text-danger">*</span></label>
                        <input type="text" class="form-control spaceless code @error('code') is-invalid @enderror"
                          id="code" name="code" placeholder="Contoh: COB-001" value="{{ old('code') ?? $cause->code }}"
                          autocomplete="off">
                        @error('code')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="name">PENYEBAB KERUGIAN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control name @error('name') is-invalid @enderror" id="name"
                          name="name" placeholder="Contoh: Meninggal Dunia" value="{{ old('name') ?? $cause->name }}"
                          autocomplete="off">
                        @error('name')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="description">DESKRIPSI</label>
                        <textarea class="form-control" name="description" id="description"
                          rows="5">{{ old('description') ?? $cause->description }}</textarea>
                        @error('description')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                          @checked(empty($cause->inactive_date))>
                        <label class="form-check-label font-weight-bold" for="is_active">Aktif</label>
                        @if (!empty( $cause->inactive_date ))
                        <br>
                        <small>
                          Tidak Aktif Sejak:
                          <?= date('d F Y, H:i', strtotime( $cause->inactive_date )) . " WIB" ?>
                        </small>
                        @else
                        <br>
                        <small>
                          Aktif Sejak:
                          <?= date('d F Y, H:i', strtotime( $cause->effective_date )) . " WIB" ?>
                        </small>
                        @endif

                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3">
                      <a href="{{ route('cause.limits', ['uuid' => $cause->uuid]) }}"
                        class="btn btn-block btn-sm bg-gradient-warning rounded-pill">
                        <i class="fa fa-edit"></i> Atur Limit
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-lg-7 pl-3">
                  <div class="shadow p-2">
                    <div class="row ">
                      <div class="col-12">
                        <div class="table-responsive">
                          <table class="table table-hover table-sm">
                            <thead class="text-center bg-gradient-success">
                              <tr>
                                <th style="width: 100px" class="border-left py-2">WAJIB?</th>
                                <th>BERKAS</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse ($files as $cause)
                              <?php
                              $check = false;
                              if( in_array($cause->id, $checkeds) ){
                                $check = true;
                              }
                            ?>
                              <tr>
                                <td class="text-center">
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input type="checkbox" class="form-check-input " name="cause_file[]"
                                    value="{{ $cause->id }}" @checked($check)>
                                </td>
                                <td>{{ $cause->name }}</td>

                              </tr>
                              @empty
                              @endforelse
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="2"></td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-sm bg-gradient-info rounded-pill px-4 btn-submit">
                <i class="fa fa-check"></i> SIMPAN
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content -->
@endsection

@push('scripts')

<!-- Sweetalert -->
<script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.all.js"></script>

<script>
  $(function() {
   
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");
    
  });

  $(".btn-hapus").click(function(e) {
    e.preventDefault();
    if( event.target.getAttribute('data-name') != null ){
      Swal.fire({
        title: 'Hapus Penyebab Kerugian \n"' + event.target.getAttribute('data-name') + '"?',
        text: '*Data yang sudah dihapus tidak dapat dibatalkan',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: "Tidak, Batalkan",
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          e.target.parentElement.submit();
        }
      })
    }
  });

</script>
@endpush