@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-4 text-uppercase">Data Users</h4>

    <div class="card">
        <div class="card-body table-responsive">
            <input type="text" id="searchInput" class="form-control mb-3 w-25" placeholder="Cari nama atau email...">

            <table class="table table-hover align-middle text-center" id="userTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody id="userBody">
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-sm btn-primary" onclick="prevPage()">Prev</button>
                <button class="btn btn-sm btn-primary" onclick="nextPage()">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    const rows = Array.from(document.querySelectorAll('#userBody tr'));
    const rowsPerPage = 10;
    let currentPage = 1;

    function displayRows() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? '' : 'none';
        });
    }

    function nextPage() {
        if ((currentPage * rowsPerPage) < rows.length) {
            currentPage++;
            displayRows();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayRows();
        }
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        currentPage = 1;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // tampilkan halaman awal
    displayRows();
</script>
@endsection
