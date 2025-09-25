@extends('_layout.app')

@section('section')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid border-bottom pb-1">
      <div class="row mb-2 ">
        <div class="col-sm-6">
          <h1 class="m-0 mt-1 font-weight-bold">STARTER PAGE</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right shadow px-2 bg-white mt-1">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item active">Starter Page</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Col-lg-12 -->
        <div class="col-lg-12">
          <div class="card card-success card-outline">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-sm">
                  <thead class="bg-success font-weight-bold text-center">
                    <tr>
                      <td class="py-2">NO</td>
                      <td>KOLOM</td>
                      <td>KOLOM</td>
                      <td>KOLOM</td>
                      <td>KOLOM</td>
                      <td>KOLOM</td>
                      <td>KOLOM</td>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                   @for ($i = 1; $i <= 50; $i++)
                   <tr>
                    <td>{{ $i }}</td>
                    <td>KOLOM</td>
                    <td>KOLOM</td>
                    <td>KOLOM</td>
                    <td>KOLOM</td>
                    <td>KOLOM</td>
                    <td>KOLOM</td>
                  </tr>
                   @endfor
                  </tbody>
                  <tfoot>
                    <tr><td colspan="7"></td></tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div><!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.main-content -->
  
  <br><br><br><br><br>
@endsection

@push('scripts')
    <script>
      $(document).ready(function () {
        $("th").addClass("align-middle");
        $("td").addClass("align-middle");
      });
    </script>
@endpush