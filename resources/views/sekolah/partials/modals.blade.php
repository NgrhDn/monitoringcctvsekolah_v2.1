<!-- Modal Add/Edit -->
<div class="modal fade" id="cctvsekolahModal" tabindex="-1" aria-labelledby="cctvsekolahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cctvsekolahModalLabel">Tambah CCTV Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cctvForm" method="POST">
                    @csrf
                    <input type="hidden" id="idSekolah">
                    <div class="mb-3">
                        <label for="wilayah_id">Nama Wilayah</label>
                        <select class="form-control" id="wilayah_id" name="wilayah_id" required>
                            <option value="" disabled selected>Pilih Wilayah</option>
                            <option value="1">KOTA JOGJA</option>
                            <option value="2">KABUPATEN BANTUL</option>
                            <option value="3">KABUPATEN GUNUNG KIDUL</option>
                            <option value="4">KABUPATEN KULONPROGO</option>
                            <option value="5">KABUPATEN SLEMAN</option>
                        </select>
                    </div>
                    <div class="mb-3"> 
                        <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_titik" class="form-label">Titik Wilayah</label>
                        <input type="text" class="form-control" id="nama_titik" name="nama_titik" required>
                    </div>
                    <div class="mb-3">
                        <label for="link_stream" class="form-label">Link</label>
                        <input type="text" class="form-control" id="link_stream" name="link_stream" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>