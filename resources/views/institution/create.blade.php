@extends('_layout.app')

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
          <li class="breadcrumb-item"><a href="{{ route('institutions') }}">{{ ucwords($menu) }}</a></li>
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
        <form action="{{ route('institution.store') }}" method="POST" autocomplete="off">
          @csrf
          <div class="card card-success card-outline">
            <div class="card-body">
              {{-- Info --}}
              <div class="row mb-2">
                <div class="col-12">
                  <div class="alert alert-info-bs alert-dismissible fade show" role="alert">
                    <strong> <i class="fa fa-info-circle"></i> INFO</strong>
                    <ul>
                      <li> Bidang bertanda <span class="reqs">*</span> Wajib diisi,</li>
                      <li> Pastikan Kode Unik dan tidak memiliki spasi</li>
                    </ul>
                  </div>
                </div>
              </div>
              {{-- End of Info --}}

              <div class="px-2">
                <div class="row">
                  <div class="col-lg-12">
                    {{-- Fields --}}
                    <div class="row">
                      <div class="col-lg-6 px-3">
                        <div class="form-group row">
                          <label for="code" class="col-sm-4 col-form-label">
                            KODE <span class="reqs">*</span>
                          </label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control spaceless @error('code') is-invalid @enderror"
                              id="code" name="code" value="{{ old('code') ?? '' }}">
                            @error('code')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="name" class="col-sm-4 col-form-label">
                            NAMA INSTANSI <span class="reqs">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                              name="name" value="{{ old('name') ?? '' }}">
                            @error('name')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="description" class="col-sm-4 col-form-label">
                            KETERANGAN
                          </label>
                          <div class="col-sm-8">
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                              id="description" rows="4">{{ old('description') ?? '' }}</textarea>
                            @error('description')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6 px-3">
                        <div class="form-group row">
                          <label for="phone" class="col-sm-4 col-form-label">
                            NOMOR TELEPON
                          </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                              name="phone" value="{{ old('phone') ?? ''  }}">
                            @error('phone')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="email" class="col-sm-4 col-form-label">
                            EMAIL
                          </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                              name="email" value="{{ old('email') ?? '' }}">
                            @error('email')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="address" class="col-sm-4 col-form-label">
                            ALAMAT
                          </label>
                          <div class="col-sm-8">
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                              id="address" rows="4">{{ old('address') ?? '' }}</textarea>
                            @error('address')
                            <div class="invalid-feedback font-weight-bold">
                              {{ $message }}!
                            </div>
                            @enderror
                          </div>
                        </div>

                      </div>
                    </div>
                    {{-- End of Fields --}}
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <a href="" class="btn btn-sm bg-gradient-danger px-4 rounded-pill btn-submit mr-1">
                <i class="fa fa-undo"></i> BATAL
              </a>
              <button type="submit" class="btn btn-sm bg-gradient-success px-4 rounded-pill btn-submit">
                <i class="fa fa-check"></i> SIMPAN
              </button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@push('scripts')

@endpush