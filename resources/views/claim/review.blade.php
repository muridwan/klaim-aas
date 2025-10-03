@extends('_layout.app')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .timeline-steps {
    display: flex;
    justify-content: center;
    flex-wrap: wrap
  }

  .timeline-steps .timeline-step {
    align-items: center;
    display: flex;
    flex-direction: column;
    position: relative;
    margin: 1rem
  }

  @media (min-width:768px) {
    .timeline-steps .timeline-step:not(:last-child):after {
      content: "";
      display: block;
      border-top: .25rem dotted #3b82f6;
      width: 3.46rem;
      position: absolute;
      left: 7.5rem;
      top: .3125rem
    }

    .timeline-steps .timeline-step:not(:first-child):before {
      content: "";
      display: block;
      border-top: .25rem dotted #3b82f6;
      width: 3.8125rem;
      position: absolute;
      right: 7.5rem;
      top: .3125rem
    }
  }

  .timeline-steps .timeline-content {
    width: 10rem;
    text-align: center
  }

  .timeline-steps .timeline-content .inner-circle {
    border-radius: 1.5rem;
    height: 1rem;
    width: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #3b82f6
  }

  .timeline-steps .timeline-content .inner-circle:before {
    content: "";
    background-color: #3b82f6;
    display: inline-block;
    height: 3rem;
    width: 3rem;
    min-width: 3rem;
    border-radius: 6.25rem;
    opacity: .5
  }
</style>
@endpush

@section('section')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid border-bottom pb-1">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="mt-1">{!! ucwords($title) . " <b>[" . $claim->code . "]</b>"  !!}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right mt-2 px-2 bg-white shadow-sm">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
          <li class="breadcrumb-item"><a href="{{ route('claims') }}">Klaim</a></li>
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
        <form action="{{ route('review_action') }}" method="POST" autocomplete="off">
          @csrf
          <input type="hidden" name="uuid" value="{{ $claim->uuid }}">
          <div class="card card-success card-outline card-tabs">
            <div class="card-body">

              {{-- Timeline --}}
              <div class="row mb-5 mt-2">
                <div class="col-xl-6 col-lg-8">
                  <h4 class="font-weight-bold">
                    <i class="fas fa-stopwatch"></i> <u>LINI MASA </u>
                  </h4>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                    <div class="timeline-step">
                      <div class="timeline-content" title="">
                        <div class="inner-circle"></div>
                        <p class="h6 mt-3 mb-1">{{ date('d-m-Y, H:i', strtotime($claim->created_at)) . " WIB" }}</p>
                        <p class="h6 text-dark mb-0 mb-lg-0">Pengajuan</p>
                      </div>
                    </div>
                    <div class="timeline-step">
                      <div class="timeline-content" title="">
                        <div class="inner-circle"></div>
                        @if ($claim->status == 1)
                        <p class="h6 mt-3 mb-1 text-dark"> <i>In Progress</i></p>
                        <p class="h6 text-success font-weight-bold mb-0 mb-lg-0">Peninjauan</p>
                        @else
                        <p class="h6 mt-3 mb-1"> {{date('d-m-Y, H:i', strtotime($claim->reviewed_at)) . " WIB"}}</p>
                        <p class="h6 text-dark mb-0 mb-lg-0">Peninjauan</p>
                        @endif
                      </div>
                    </div>
                    <div class="timeline-step">
                      <div class="timeline-content" title="">
                        <div class="inner-circle"></div>
                        @if ($claim->status == 2) {{-- Penijauan --}}
                        <p class="h6 mt-3 mb-1 text-dark"> <i>In Progress</i></p>
                        <p class="h6 text-success font-weight-bold mb-0 mb-lg-0">Persetujuan</p>
                        @elseif( $claim->status >= 3 ) {{-- Persetujian --}}
                        <p class="h6 mt-3 mb-1"> {{date('d-m-Y, H:i', strtotime($claim->reviewed_at)) . " WIB"}}</p>
                        <p class="h6 text-dark mb-0 mb-lg-0">Persetujuan</p>
                        @else {{-- Pengajuan --}}
                        <p class="h6 mt-3 mb-1"> <i>...</i></p>
                        <p class="h6 text-muted  mb-0 mb-lg-0">Persetujuan</p>
                        @endif
                      </div>
                    </div>
                    <div class="timeline-step">
                      <div class="timeline-content" title="">
                        <div class="inner-circle"></div>
                        @if ($claim->status == 3) {{-- Penijauan --}}
                        <p class="h6 mt-3 mb-1 text-dark"> <i>In Progress</i></p>
                        <p class="h6 text-success font-weight-bold mb-0 mb-lg-0">Pembayaran</p>
                        @elseif( $claim->status >= 4 ) {{-- Persetujian --}}
                        <p class="h6 mt-3 mb-1"> {{date('d-m-Y, H:i', strtotime($claim->paid_at)) . " WIB"}}</p>
                        <p class="h6 text-dark mb-0 mb-lg-0">Pembayaran</p>
                        @else {{-- Pengajuan --}}
                        <p class="h6 mt-3 mb-1"> <i>...</i></p>
                        <p class="h6 text-muted mb-0 mb-lg-0">Pembayaran</p>
                        @endif
                      </div>
                    </div>
                    <div class="timeline-step mb-0">
                      <div class="timeline-content" title="">
                        <div class="inner-circle"></div>
                        @if ($claim->status == 4) {{-- Penijauan --}}
                        <p class="h6 mt-3 mb-1 text-dark"> <i>In Progress</i></p>
                        <p class="h6 text-success font-weight-bold mb-0 mb-lg-0">Penyelesaian</p>
                        @elseif( $claim->status >= 5 ) {{-- Persetujian --}}
                        <p class="h6 mt-3 mb-1"> {{date('d-m-Y, H:i', strtotime($claim->settled_at)) . " WIB"}}</p>
                        <p class="h6 text-dark mb-0 mb-lg-0">Penyelesaian</p>
                        @else {{-- Pengajuan --}}
                        <p class="h6 mt-3 mb-1"> <i>...</i></p>
                        <p class="h6 text-muted mb-0 mb-lg-0">Penyelesaian</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- End of Timeline --}}

              <hr class="my-4">
              {{-- POLICY TAB --}}
              <div class="row mb-3 mt-2">
                <div class="col-xl-6 col-lg-8">
                  <h4 class="font-weight-bold">
                    <i class="far fa-file-alt"></i> <u>FORMULIR</u>
                  </h4>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 px-3">
                  <table class="table table-hover">
                    <tr>
                      <td style="width: 225px" class="bg-secondary font-weight-bold">TANGGAL PENGAJUAN</td>
                      <td class="border-right">
                        <b>{{ date('d F Y', strtotime($claim->created_at)) }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">NOMOR POLIS</td>
                      <td class="border-right">
                        <b>{{ $claim->policy }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">NOMOR SERTIFIKAT</td>
                      <td class="border-right">
                        <b>{{ $claim->certificate != '' ? $claim->certificate : '-' }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">NAMA PESERTA</td>
                      <td class="border-right">
                        <b> {{ $claim->name ?? '-' }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">PEKERJAAN</td>
                      <td class="border-right">
                        <b>{{ $claim->occupation->name ?? '-' }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">CABANG</td>
                      <td class="border-right">
                        <b>{{ '[' . $claim->office->code . '] ' . $claim->office->name }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">JENIS PEMBIAYAAN</td>
                      <td class="border-right">
                        <b>{{ responseToString($claim->response)->TOC}}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-0" colspan="2"></td>
                    </tr>
                  </table>
                </div>
                <div class="col-lg-6 px-3">
                  <table class="table table-hover">
                    <tr>
                      <td style="width: 225px" class="bg-secondary font-weight-bold">JANGKA WAKTU</td>
                      <td class="border-right">
                        <b>
                          {!! date('d F Y', strtotime($claim->start_date)) . '&nbsp;&nbsp; s.d. &nbsp;&nbsp;' .
                          date('d F Y', strtotime($claim->end_date)) !!}
                        </b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">TANGGAL KEJADIAN</td>
                      <td class="border-right">
                        <b> {{ old('incident_date') ?? date('d-F-Y', strtotime($claim->incident_date)) }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">PLAFON PEMBIAYAAN (Rp)</td>
                      <td class="border-right">
                        <b class="rupiah">{{ number_format($claim->tsi_amount, 0, ',', '.') }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">NILAI PENGAJUAN (Rp)</td>
                      <td class="border-right">
                        <b>{{ number_format($claim->claim_amount, 0, ',', '.') }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">PENYEBAB KLAIM / <br> <i>Cause Of Loss</i></td>
                      <td class="border-right">
                        <b> {{ $claim->cause->name ?? '' }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">KETERANGAN PENYEBAB KLAIM</td>
                      <td class="border-right">
                        <b> {{ $claim->description }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">LOKASI KLAIM / <br> <i>Location Of Loss</i></td>
                      <td class="border-right">
                        <b> {{ $claim->location->loc_desc ?? '' }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="bg-secondary font-weight-bold">KETERANGAN LOKASI KLAIM</td>
                      <td class="border-right">
                        <b> {{ $claim->loss_loc_desc }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td class="p-0" colspan="2"></td>
                    </tr>
                  </table>
                </div>
              </div>
              {{-- End of POLICY TAB --}}

              {{-- DOCUMENTs TAB --}}
              <hr class="my-4">
              <div class="row mb-3 mt-2">
                <div class="col-xl-6 col-lg-8">
                  <h4 class="font-weight-bold">
                    <i class="far fa-folder-open"></i> <u>DOKUMEN</u>
                  </h4>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                  <thead class="text-center bg-gradient-success">
                    <tr>
                      <th style="width: 50px" class="border-left py-2">NO</th>
                      <th style="width: 28%">NAMA</th>
                      <th>BERKAS</th>
                      <th style="width: 28%" class="border-right">KETERANGAN</th>
                      <th>SESUAI?</th>
                      <th style="width: 28%" class="border-right">CATATAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($claim->documents as $document)
                    <input type="hidden" name="documents[{{ $loop->iteration-1 }}]" value="{{ $document->uuid }}">
                    <input type="hidden" id="claim-{{$loop->iteration}}" value="{{ $claim->uuid }}">
                    <input type="hidden" id="document-{{$loop->iteration}}" value="{{ $document->uuid }}">

                    <tr>
                      <td class="text-center border">{{ $loop->iteration }}</td>
                      <td>
                        <span id="file-name-{{$loop->iteration}}">
                          {{ $document->cause_file->file->name ?? '' }}
                        </span>
                      </td>
                      <td class="text-center border-left">
                        @if ($document->document)
                        <a target="_blank" data-lable="{{ $document->cause_file->file->name }}" href="{{ asset('storage/uploads/'. $document->document) }}" class="badge bg-gradient-info px-3 py-1 modalFile" id="status-default-{{$loop->iteration}}">
                          <i class="fa fa-search"></i> 
                        </a>
                        <a href="{{ asset('storage/uploads/'. $document->document) }}" download type="button" class="badge bg-gradient-primary px-3 py-1 mt-2">
                          <i class="fa fa-download"></i> 
                        </a>
                        @endif
                        <span id="status-text-{{$loop->iteration}}"></span>
                      </td>
                      <td class="{{ empty($document->description) ? 'text-center' : '' }}">
                        {{{ $document->description ?? '-' }}}
                      </td>
                      <td class="text-center">
                        @if ($claim->sequence == 1 && (session('user_role')['role_id'] == 4 || session('user_role')['role_id'] == 5 || session('user_role')['role_id'] == 6 ))
                        <input data-row="{{ $loop->iteration }}" type="checkbox" value="1" id="file_decision-{{$loop->iteration}}" class="file_decision" @checked($document->is_accepted == 1)
                        name="file_decisions[{{$loop->iteration-1}}]">
                        @else
                          @if ($document->is_accepted)
                          <i class="text-success fa fa-check-circle"></i>
                          @else
                          <i class="text-danger fa fa-times-circle"></i>
                          @endif
                        @endif
                      </td>
                      <td>
                        @if ($claim->sequence == 1 && (session('user_role')['role_id'] == 4 || session('user_role')['role_id'] == 5 || session('user_role')['role_id'] == 6 ))
                        <textarea data-row="{{ $loop->iteration }}" name="remarks[{{ $loop->iteration-1 }}]" class="form-control file_remarks" id="file-remarks-{{$loop->iteration}}"
                          rows="2">{{{ $document->remarks ?? '' }}}</textarea>
                        @else
                        {{ $document->remarks ?? '-' }}
                        @endif
                      </td>
                    </tr>
                    @empty
                    @endforelse
                  </tbody>
                </table>
              </div>
              {{-- End of DOCUMENTs TAB --}}

              {{-- RECOMENDATIONs --}}
              <hr class="my-4">
              <div class="row mb-2 mt-2">
                <div class="col-xl-6 col-lg-8">
                  <h4 class="font-weight-bold">
                    <i class="far fa-folder-open"></i> <u>REKOMENDASI KLAIM</u>
                  </h4>
                </div>
              </div>

              @foreach ($claim->recommendations as $item)
              @if ($item->sequence <= $claim->sequence)
                <input type="hidden" name="recom_uuid" value="{{ $item->uuid }}">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mt-1">
                      <label for="recom_note{{ $item->sequence }}">
                        <i class="fas fa-caret-right"></i> CATATAN :
                        {{-- <i class="fas fa-caret-right"></i> CATATAN <u>{{ strtoupper($item->position->name) }}</u> : --}}
                      </label>
                      @if ( session('user_role')['role_id'] == 4 || session('user_role')['role_id'] == 5 || session('user_role')['role_id'] == 6 )
                        <textarea class="form-control" id="recom_note{{ $item->sequence }}" name="recom_note" rows="3" required
                        @readonly($item->sequence != $claim->sequence)>{{ $item->description ?? '' }}</textarea>
                      @else
                        <textarea disabled="true" class="form-control" id="recom_note{{ $item->sequence }}" name="recom_note" rows="3" required
                        @readonly($item->sequence != $claim->sequence)>{{ $item->description ?? '' }}</textarea>    
                      @endif
                      @if ( $item->created_at AND $item->created_by )
                      <small>
                        <span>
                          Oleh: {{ $item->creater->name ." (".strtoupper($item->position->name).")" ?? '-' }}
                          , Pada: {{ date('d-m-Y, H:i', strtotime( $item->created_at )) . " WIB" }}
                        </span>
                      </small>
                      @endif
                    </div>
                    @if ($item->is_decider == 0  && (session('user_role')['role_id'] == 4 || session('user_role')['role_id'] == 5 || session('user_role')['role_id'] == 6 ))
                    <div class="form-group form-check" id="all_done_block" style="display: {{ $isChecedkAll ? 'show' : 'none' }}">
                      <input type="checkbox" class="form-check-input" id="all_done" name="all_done" value="1" {{ $isChecedkAll ?  : '' }} @checked($item->suggestion == 1)>
                      <label class="form-check-label" for="all_done">
                        <b>Semua sudah sesuai, Kirim ke Atasan sekarang </b>
                      </label>
                    </div>
                    @endif
                  </div>
                </div>
                @endif
                @endforeach
                {{-- END of RECOMENDATIONs --}}
            </div>
            @if ( session('user_role')['role_id'] == 4 || session('user_role')['role_id'] == 5 || session('user_role')['role_id'] == 6 )
              <div class="card-footer text-right">
                <button type="submit" class="btn btn-sm bg-gradient-success px-3 rounded-pill">
                  <i class="fa fa-check"></i> SIMPAN
                </button>
              </div> 
            @elseif($item->sequence > $claim->sequence)
              <div class="card-footer text-right">
                <button type="submit" class="btn btn-sm bg-gradient-success px-3 rounded-pill">
                  <i class="fa fa-check"></i> KEMBALI KE PENGAJUAN
                </button>
              </div>   
            @endif            
          </div>
        </form>
      </div>
      <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Modal -->
<div class="modal fade" id="detailFile" tabindex="-1" aria-labelledby="detailFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title">
          <i class="fa fa-file mr-1"></i> <span id="detailFileLabel"></span>
        </h5>
      </div>
      <div class="modal-body">
        <embed id="detailFileEbd" src="#" width="100%" height="600px" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm rounded-pill px-3 btn-secondary" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Tutup
        </button>
        <a href="#" id="detailFileUrl" download type="button" class="btn btn-sm rounded-pill px-3 bg-gradient-success">
          <i class="fa fa-download"></i> Unduh
        </a>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->

@endsection

@push('scripts')

<script>
  $(document).ready(function () {
    $('.rupiah').each(function () { 
      let value = $(this).val();
      $(this).val(formatRupiah(value));
    });
    
    $("th").addClass("align-middle");
    $("td").addClass("align-middle");

    showModal();
  });

  // $('.file_decision').on('input', function() {
  //   console.log($(this).val());
  // });


  function showModal(){
    $(".modalFile").click(function (e) { 
      e.preventDefault();
      let file = $(this).attr('href');
      let lable = $(this).attr('data-lable');
      $("#detailFile").modal('show');
      $("#detailFileLabel").text(lable);
      $("#detailFileEbd").attr('src', file)
      $("#detailFileUrl").attr('href', file)
    });
  }

  // Event mengisi Keputusan
  $('.file_decision').on('change', function() {
    const isChecked = $(this).is(':checked');
    const row       = $(this).data('row');
    const decision   = isChecked ? 1 : 0;

    $.ajax({
      url: "{{ route('file_decision') }}",
      type: "POST",
      data: {
        claim:    $(`#claim-${row}`).val(),
        document: $(`#document-${row}`).val(),
        decision:  decision,
      },
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      success: function (result) {
        console.log(result);
        if( result.isChecedkAll == true ){
          $("#all_done_block").show();
          $("#all_done").attr("required", false);
        }else{
          $("#all_done").removeAttr("required");
          $("#all_done_block").hide();
        }
      },
      error: function () {
      }
    });
  });

  // Event mengisi Remarks
  $(".file_remarks").blur(function (e) { 
    let $this   = $(this);
    let value   = $(this).val();
    let row     = $this.data("row"); 
    let remarks = $(`#file-remarks-${row}`).val();
    
    if( remarks != '' ){
      $.ajax({
        url: "{{ route('file_remarks') }}",
        type: "POST",
        data: {
          claim:    $(`#claim-${row}`).val(),
          document: $(`#document-${row}`).val(),
          remarks:  remarks,
        },
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
        success: function (result) {
          // console.log(result);
        },
        error: function () {
        }
      });
    }
  });
  


</script>
@endpush