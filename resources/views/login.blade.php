<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Interkoneksi</title>
    <link rel="icon" href="https://askridasyariah.co.id/assets/uploads/media-uploader/favicon1660197560.png"
      type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/dist/css/adminlte.min.css">
    <style>
      .alert-success-bs {
        background-color: #d4edda;
        color: black;
      }

      .alert-danger-bs {
        background-color: #f8d7da;
        color: black;
      }

      body {
        border: 2px solid black;
        padding: 25px;
        background: url("{{ asset('adminLTE') }}/dist/img/background/invest.jpg");
        background-repeat: no-repeat;
        background-size: auto;
        background-size: cover;
        background-opacity: 0.6;
      }
    </style>
  </head>

  <body class="hold-transition login-page">
    <div class="login-box" style="margin-top: -80px">
      <!-- /.login-logo -->
      <div class="card card-outline card-success">
        <div class="card-header text-center">
          <a href="" class="h5 text-success"><b>INTERKONEKSI <br> ASKRIDA SYARIAH - PEGADAIAN</b></a>
        </div>
        <div class="card-body">
          <p class="login-box-msg">
            <img src="{{ asset('adminLTE') }}/dist/img/sidebar/PT_AAS.png" width="125" alt="">
          </p>

          @if(session()->has('pesan_success'))
          <div class="row">
            <div class="col-12">
              <div class="alert alert-success-bs alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> {{ session()->get('pesan_success')}} <i
                  class="fa fa-check-circle text-success"></i>
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
                <strong>Perhatian!</strong> <br> <small> {{ session()->get('pesan_error')}} <i
                    class="fa fa-times-circle text-danger"></i> </small>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          </div>
          @endif

          <form action="{{ route('login_action') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" value="03930093" placeholder="Username..."
                required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" placeholder="Password..." required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-block bg-gradient-success">
              Masuk <i class="ml-1 fas fa-sign-in-alt"></i>
            </button>
          </form>


          <p class="mb-1 mt-2">
            @php
            $message = "Hai...%0aSaya lupa username/password untuk aplikasi ..... Bisa bantu saya?%0aSaya dari Bagian
            *...
            (isi di sini])*";
            @endphp
            <a href="https://wa.me/6289692088395?text={{$message}}" target="_blank"
              onclick="return confirm('Tanyakan Via Whatsapp?')">Lupa Kata Sandi?</a>
          </p>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('adminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminLTE') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminLTE') }}/dist/js/adminlte.min.js"></script>
  </body>

</html>