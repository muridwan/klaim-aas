@extends('_layout.app')

@section('section')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid border-bottom pb-1">
    <div class="row mb-2">
      <div class="col-sm-6">
        {{-- <h1 class="mt-1">{{ ucwords($business->name. " - " .$title) }}</h1> --}}
        <h1 class="mt-1">{{ $business->name . " (". $institution->code .")" . ' - ' . ucwords($title) }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item">
            <a href="{{ route('businesses') }}">
              Data Kelas Bisnis
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('business.detail', ['uuid' => $business->uuid, 'institution' => $institution->code]) }}">
              {{ $business->code . " (". $institution->code .")" }}
            </a>
          </li>
          <li class="breadcrumb-item active">{{ ucwords($title) }}</li>
        </ol>
      </div>
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
          <form action="{{ route('cause.store') }}" method="POST">
            @csrf
            <input type="hidden" name="business_uuid" value="{{ $business->uuid }}">
            <input type="hidden" name="institution_code" value="{{ $_GET['institution'] ?? '' }}">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="code">KODE <span class="text-danger">*</span></label>
                        <input type="text" class="form-control spaceless code @error('code') is-invalid @enderror"
                          id="code" name="code" placeholder="Contoh: COL-099" value="{{ old('code') ?? '' }}"
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
                        <label for="name">PENYEBAB KERUGIAN<span class="text-danger">*</span></label>
                        <input type="text" class="form-control name @error('name') is-invalid @enderror" id="name"
                          name="name" placeholder="Contoh: Meninggal Dunia" value="{{ old('name') ?? '' }}"
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
                        <label for="description">DESKRIPSI </label>
                        <textarea class="form-control" name="description" id="description"
                          rows="5">{{ old('description') ?? '' }}</textarea>
                        @error('description')
                        <div class="invalid-feedback font-weight-bold">
                          {{ $message }}!
                        </div>
                        @enderror
                      </div>
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
                              if (in_array($cause->id, old('cause_files', []))) { 
                                $check = true;
                              }
                            ?>
                              <tr>
                                <td class="text-center">
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input type="checkbox" class="form-check-input " name="cause_files[]"
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

<script>
  $(function() {
   
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");
  });
</script>
@endpush