@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-uppercase mb-0">Detail Titik Panorama - Wilayah: {{ $wilayah }}</h4>
        <a href="{{ route('rekapan.cctv.panorama') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Titik</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataPanorama as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->namaTitik }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="showPanoramaDetail('{{ $wilayah }}', '{{ $p->namaTitik }}', '{{ $p->link }}')">
                                    🔍 Detail
                                </button>
                            </td>
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

    <!-- Modal Detail -->
    <div class="modal fade" id="modalPanoramaDetail" tabindex="-1" aria-labelledby="panoramaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="panoramaModalLabel">Detail CCTV Panorama</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-4 p-3">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Wilayah</label>
                                <input type="text" class="form-control" id="modalWilayah" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Titik Wilayah</label>
                                <input type="text" class="form-control" id="modalTitik" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Link</label>
                                <input type="text" class="form-control" id="modalLink" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a id="openCCTVLink" href="#" class="btn btn-primary" target="_blank">🔗 Buka Link</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showPanoramaDetail(wilayah, titik, link) {
        document.getElementById('modalWilayah').value = wilayah;
        document.getElementById('modalTitik').value = titik;
        document.getElementById('modalLink').value = link;
        document.getElementById('openCCTVLink').href = link;

        const modal = new bootstrap.Modal(document.getElementById('modalPanoramaDetail'));
        modal.show();
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
</script>
@endpush
