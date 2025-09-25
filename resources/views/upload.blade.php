<!DOCTYPE html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Otomatis dengan jQuery AJAX & Bootstrap 4</title>

    <!-- Bootstrap 4 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
      .progress-container {
        display: none;
      }
    </style>
  </head>

  <body class="container mt-5">
    <h2 class="text-center">Upload File JQuery & AJAX</h2>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama File</th>
          <th>Progress</th>
          <th>Status</th>
          <th>Batalkan Upload</th>
        </tr>
      </thead>
      <tbody id="fileTable">
        <tr>
          <td>1</td>
          <td><input type="file" class="file-input" data-row="1"></td>
          <td>
            <div class="progress-container" id="progress-container-1">
              <div class="progress">
                <div id="progress-bar-1" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                  role="progressbar" style="width: 0%;">0%
                </div>
              </div>
            </div>
          </td>
          <td>
            <div id="status-1"></div>
          </td>
          <td>
            <button type="button" class="btn btn-danger cancel-button" id="cancelButton-1" style="display: none;"
              data-row="1">Batalkan
            </button>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td><input type="file" class="file-input" data-row="2"></td>
          <td>
            <div class="progress-container" id="progress-container-2">
              <div class="progress">
                <div id="progress-bar-2" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                  role="progressbar" style="width: 0%;">0%</div>
              </div>
            </div>
          </td>
          <td>
            <div id="status-2"></div>
          </td>
          <td>
            <button type="button" class="btn btn-danger cancel-button" id="cancelButton-2" style="display: none;"
              data-row="2">Batalkan
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Bootstrap 4 & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      $(document).ready(function () {
        $("th").addClass("align-middle");
        $("td").addClass("align-middle");
        let xhrRequest = {};    // Menyimpan objek xhr untuk membatalkan upload berdasarkan row
        let uploadedFiles = {}; // Menyimpan file path yang telah diupload

        $(".file-input").change(function () {
          let row = $(this).data("row"); // Ambil nomor baris
          let file = this.files[0];
          if (!file) return;

          let formData = new FormData();
          formData.append("file", file);

          let progressBar = $(`#progress-bar-${row}`);
          let progressContainer = $(`#progress-container-${row}`);
          let statusText = $(`#status-${row}`);
          let cancelButton = $(`#cancelButton-${row}`);

          progressBar.width("0%").text("0%");
          progressContainer.show();
          statusText.html("");
          cancelButton.hide(); // Sembunyikan tombol batal selama upload

          // Melakukan upload
          xhrRequest[row] = $.ajax({
            url: "{{ route('upload.file') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            xhr: function () {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                progressBar.width(percentComplete + "%").text(percentComplete + "%");
              }
            }, false);
            return xhr;
            },
            success: function (response) {
            if (response.success) {
              uploadedFiles[row] = response.file_path; // Menyimpan path file yang diupload
              statusText.html(`<div class="alert alert-success">${response.message} <br><a href="${response.file_path}" target="_blank">Lihat File</a></div>`);
              progressBar.removeClass("bg-success").addClass("bg-primary");
              cancelButton.show(); // Tampilkan tombol batal setelah upload selesai
            }
            },
            error: function (xhr) {
            if (xhr.statusText !== 'abort') {
              let errorMessage = xhr.responseJSON?.message || "Upload gagal!";
              statusText.html(`<div class="alert alert-danger">${errorMessage}</div>`);
              progressBar.removeClass("bg-success").addClass("bg-danger");
            }
            }
          });
        });

        // Event klik tombol batal
        $(document).on("click", ".cancel-button", function () {
        let row = $(this).data("row"); // Ambil nomor baris
          let progressContainer = $(`#progress-container-${row}`);
          let statusText = $(`#status-${row}`);
          let cancelButton = $(`#cancelButton-${row}`);

          if (xhrRequest[row]) {
            xhrRequest[row].abort(); // Membatalkan upload
            // Kirim request untuk menghapus file yang sudah di-upload
            if (uploadedFiles[row]) {
              $.ajax({
                url: "{{ route('upload.delete') }}",
                type: "POST",
                data: { file_path: uploadedFiles[row] },
                headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                success: function () {
                  progressContainer.hide();
                  statusText.html("<div class='alert alert-warning'>Upload dibatalkan dan file dihapus.</div>");
                  cancelButton.hide();
                  $(`input[data-row="${row}"]`).val(""); // 🔹 Kosongkan input file
                },
                error: function () {
                  statusText.html("<div class='alert alert-danger'>Gagal menghapus file.</div>");
                }
              });
            }
          }
        });
      });
    </script>

  </body>

</html>