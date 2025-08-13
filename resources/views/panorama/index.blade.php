@extends('layouts.user_type.guest')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body::-webkit-scrollbar {
            display: none;
            /* Sembunyikan scrollbar di WebKit browsers (Chrome, Safari) */
        }

        .floating-button {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1000;
            /* Warna tombol */
            padding: 12px 20px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .container-fluid {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: none;
        }


        .floating-button:hover {
            background-color: rgba(77, 255, 0, 0.63);
            /* Warna saat hover */
            transform: scale(1.05);
            color: white;
        }

        button {
            height: 35px;
            /* Samakan tinggi semua tombol */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .background-image {
            position: absolute;
            /* Menghilangkan elemen dari flow dokumen */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            z-index: -1;
            /* Pastikan gambar berada di belakang konten lain */
        }

        .background-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 110%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
            transform: rotate(180deg);
        }
    </style>
</head>

<body>
    @section('content')
        <div class="pb-0 d-flex justify-content-end">
            <button class="floating-button btn" style="background-color: rgba(255, 0, 183, 0.63); color: white;"
                data-bs-toggle="modal" data-bs-target="#addEditPanoramaModal" onclick="resetForm()">
                Tambah CCTV Panorama
            </button>
        </div>

        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="text-center">CCTV Panorama</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Wilayah</th>
                                                <th class="text-center">Titik Wilayah</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($panorama as $item)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $item->namaWilayah }}</td>
                                                    <td class="text-center align-middle">{{ $item->namaTitik }}</td>
                                                    <td class="text-center align-middle">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <!-- Form Edit -->
                                                            <button class="btn btn-sm btn-primary"
                                                                onclick="editPanorama({{ $item->id }}, '{{ $item->namaWilayah }}', '{{ $item->namaTitik }}', '{{ $item->link }}')"
                                                                data-bs-toggle="modal" data-bs-target="#addEditPanoramaModal">
                                                                Edit
                                                            </button>

                                                            <!-- Form Delete -->
                                                            <form action="{{ route('panorama.delete', $item->id) }}"
                                                                method="POST" id="deleteForm{{ $item->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    onclick="confirmDelete({{ $item->id }})">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endsection

    <div class="background-image" style="background-image: url('{{ asset('images/pattern.jpg') }}');"></div>
    <!-- Modal for Add/Edit Panorama -->
    <div class="modal fade" id="addEditPanoramaModal" tabindex="-1" aria-labelledby="addEditPanoramaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditPanoramaModalLabel">Tambah CCTV Panorama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEditPanoramaForm" action="{{ route('panorama.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="namaWilayah" class="form-label">Nama Wilayah</label>
                            <input type="text" class="form-control" id="namaWilayah" name="namaWilayah" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaTitik" class="form-label">Nama Titik</label>
                            <input type="text" class="form-control" id="namaTitik" name="namaTitik" required>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" id="link" name="link" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika konfirmasi di klik, submit form delete
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }


        // Cek jika ada session success atau error
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            });
        @endif

        @if (session('validation_error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            }).then(() => {
                var myModal = new bootstrap.Modal(document.getElementById('addEditPanoramaModal'));
                myModal.show();
            });
        @endif
    </script>

    <script>
            // Reset Form saat menambah data baru
            function resetForm() {
                document.getElementById('addEditPanoramaForm').reset();
                document.getElementById('addEditPanoramaForm').action = "{{ route('panorama.store') }}";
                document.getElementById('addEditPanoramaModalLabel').textContent = "Tambah CCTV Panorama";
            }

        // Mengisi form untuk Edit
        function editPanorama(id, namaWilayah, namaTitik, link) {
            document.getElementById('namaWilayah').value = namaWilayah;
            document.getElementById('namaTitik').value = namaTitik;
            document.getElementById('link').value = link;
            document.getElementById('addEditPanoramaForm').action = '/panorama/' + id;
            document.getElementById('addEditPanoramaModalLabel').textContent = "Edit CCTV Panorama";
        }
    </script>
</body>