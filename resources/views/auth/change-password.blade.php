@extends('_layout.app')

@section('section')
<div class="content-header">
  <div class="container-fluid border-bottom pb-2">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="mt-1">{{ ucwords($title ?? 'Ubah Password') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item active">Ubah Password</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <!-- Card -->
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-key mr-1"></i> Ubah Password</h5>
          </div>
          <div class="card-body">

            <!-- Pesan sukses -->
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST">
              @csrf

              <!-- Password lama -->
              <div class="form-group">
                <label for="current_password">Password Lama</label>
                <div class="input-group">
                  <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                  <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#current_password">
                      <i class="fa fa-eye"></i>
                    </button>
                  </div>
                  @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Password baru -->
              <div class="form-group">
                <label for="new_password">Password Baru</label>
                <div class="input-group">
                  <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                  <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password">
                      <i class="fa fa-eye"></i>
                    </button>
                  </div>
                  @error('new_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Konfirmasi password -->
              <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                <div class="input-group">
                  <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                  <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password_confirmation">
                      <i class="fa fa-eye"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Tombol simpan -->
              <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-save mr-1"></i> Simpan Perubahan
              </button>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Script Show/Hide Password -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function () {
        let target = document.querySelector(this.dataset.target);
        let icon = this.querySelector('i');
        if (target.type === 'password') {
          target.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          target.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      });
    });
  });
</script>
@endsection
