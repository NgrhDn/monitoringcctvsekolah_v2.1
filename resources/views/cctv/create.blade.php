@extends('layouts.user_type.guest')

@section('content')

<body>
    <div class="container">
        <h2>Create CCTV Record</h2>
        <form action="{{ route('cctv.store') }}" method="POST"
            style="max-width: 600px; margin: 0; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
            @csrf
            <div class="form-group">
                <label for="namaWilayah">Nama Wilayah:</label>
                <input type="text" class="form-control" id="namaWilayah" name="namaWilayah" required
                    style="width: 100%;">
            </div>
            <div class="form-group">
                <label for="namaTitik">Nama Titik:</label>
                <input type="text" class="form-control" id="namaTitik" name="namaTitik" required style="width: 100%;">
            </div>
            <!-- <div class="form-group">
    <label for="nomorKamera">Nomor Kamera:</label>
    <input type="text" class="form-control" id="nomorKamera" name="nomorKamera" required style="width: 100%;">
</div> -->
            <div class="form-group">
                <label for="link">Link:</label>
                <input type="text" class="form-control" id="link" name="link" required style="width: 100%;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
        </form>
    </div>
</body>
@endsection
