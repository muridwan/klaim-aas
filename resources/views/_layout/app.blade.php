<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucwords($title) }} | Klaim Core</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/dist/css/adminlte.min.css">
    <link rel="icon" href="{{ asset('AdminLTE') }}/dist/img/icon-aas.png" type="image/png">

    {{-- STYLE --}}
    <style>
      .alert-primary-bs {
        background-color: #cce5ff;
        color: black;
      }

      .alert-secondary-bs {
        background-color: #e2e3e5;
        color: black;
      }

      .alert-success-bs {
        background-color: #d4edda;
        color: black;
      }

      .alert-danger-bs {
        background-color: #f8d7da;
        color: black;
      }

      .alert-warning-bs {
        background-color: #fff3cd;
        color: black;
      }

      .alert-info-bs {
        background-color: #d1ecf1;
        color: black;
      }
    </style>
    {{-- End of STYLE --}}

    <!-- Addons styles -->
    @stack('styles')
  </head>

  <body class="control-sidebar-slide-open sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper">

      <!-- Navbar -->
      @include('_layout.template.navbar')
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      @include('_layout.template.sidebar')
      <!-- /.main-sidebar-container -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        @yield('section')
        <br><br><br>
      </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
          Powered By IT DEV of AAS
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2025 <a href="https://askridasyariah.co.id">Askrida Syariah</a>.</strong> All rights reserved.
      </footer>
      <!-- /.main-footer -->

    </div>
    <!-- ./wrapper -->

    {{-- Modal --}}
    <div class="modal fade" id="modalSpinner" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body text-center">
            <div class="spinner-border text-success my-3" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE') }}/dist/js/adminlte.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery-3.6.0.min.js"></script>

    <script>
    $(".reqs").addClass('text-danger');

    $('.rupiah').keyup(function(e) {
      this.value = formatRupiah(this.value);
    });

    $('.spaceless').keyup(function(e) {
      this.value = $(this).val().replace(/ /g, "");
    });

    $('.is-invalid').keyup(function(e) {
      e.preventDefault();
      $(this).removeClass('is-invalid');
    });

    $(".is-invalid").change(function(e) {
      e.preventDefault();
      $(this).removeClass('is-invalid');
    });

    $('.form-control').keyup(function(e) {
      e.preventDefault();
      $(".btn-submit").removeAttr("disabled");
    });

    $('.form-control').change(function(e) {
      e.preventDefault();
      $(".btn-submit").removeAttr("disabled");
    });

    $('.form-control-file').change(function(e) {
      e.preventDefault();
      $(".btn-submit").removeAttr("disabled");
    });

    $('.form-check-input').change(function(e) {
      e.preventDefault();
      $(".btn-submit").removeAttr("disabled");
    });

    $(".btn-submit").click(function (e) { 
      $("#modalSpinner").modal('show');
    });

    $('.btn-delete').click(function(e) {
      e.preventDefault();
      $(".btn-submit").removeAttr("disabled");
    });

    $(".btn-submit").attr('disabled', true);

    $(document).on('select2:open', () => {
      document.querySelector('.select2-search__field').focus();
    });

    $('.rupiah').keyup(function(e) {
      this.value = formatRupiah(this.value);
    });

    formatRupiah = (angka, prefix) => {
      var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   = number_string.split(','),
        sisa    = split[0].length % 3,
        rupiah  = split[0].substr(0, sisa),
        ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }
    </script>

    <!-- Addons Scripts -->
    @stack('scripts')

  </body>

</html>