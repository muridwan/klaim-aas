<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>

<body>
  <form action="{{ route('upload-excel') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Pilih File Excel:</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Upload</button>
  </form>
</body>

</html>