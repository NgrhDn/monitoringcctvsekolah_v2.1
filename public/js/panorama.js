// public/js/sekolah.js
let panoramaData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 5;

// 1. Ambil data dari API
function loadpanoramaData() {
    fetch("/api/cctvpanorama", { headers: { Accept: "application/json" } })
        .then((res) => res.json())
        .then((json) => {
            if (!json.success) {
                return Swal.fire("Error", json.message, "error");
            }
            panoramaData = json.data;
            filteredData = panoramaData; // awalnya filter = semua data
            currentPage = 1;
            renderTable();
        })
        .catch((err) => {
            console.error(err);
            Swal.fire("Error", "Gagal memuat data.", "error");
        });
}

function grouppanoramaData(data) {
    const grouped = {};

    data.forEach((item) => {
        const key = `${item.namaWilayah}`;
        if (!grouped[key]) {
            grouped[key] = {
                namaWilayah: item.namaWilayah,
                titik: [],
            };
        }
        grouped[key].titik.push(item);
    });

    // Sorting titik di tiap grup berdasarkan namaTitik
    Object.values(grouped).forEach((group) => {
        group.titik.sort((a, b) => {
            return a.namaTitik
                .toLowerCase()
                .localeCompare(b.namaTitik.toLowerCase());
        });
    });

    // Ubah grouped jadi array lalu sorting berdasarkan namaWilayah
    const result = Object.values(grouped);
    result.sort((a, b) => {
        return a.namaWilayah
            .toLowerCase()
            .localeCompare(b.namaWilayah.toLowerCase());
    });

    return result;
}

// 2. Render tabel berdasarkan filteredData & paging
function renderTable() {
    const tbody = document.getElementById("sekolah-tbody");
    tbody.innerHTML = "";

    const groupedData = grouppanoramaData(filteredData);

    // paging (berdasarkan group, bukan item per titik)
    const start = (currentPage - 1) * itemsPerPage;
    const pageData = groupedData.slice(start, start + itemsPerPage);

    pageData.forEach((group) => {
        const rowspan = group.titik.length;

        const wilayahNama =
            group.namaWilayah === "KABUPATEN GK"
                ? "KAB GUNUNG KIDUL"
                : group.namaWilayah === "KABUPATEN KP"
                ? "KAB KULONPROGO"
                : group.namaWilayah === "KABUPATEN BANTUL"
                ? "KAB BANTUL"
                : group.namaWilayah === "KABUPATEN SLEMAN"
                ? "KAB SLEMAN"
                : group.namaWilayah;

        group.titik.forEach((item, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
        ${
            index === 0
                ? `<td class="text-center" rowspan="${rowspan}">${wilayahNama}</td>`
                : ""
        }
        <td class="text-center">${item.namaTitik}</td>
        <td class="text-center">
          <button class="btn btn-sm btn-secondary" onclick='openEditModal(${JSON.stringify(
              item
          )})'>Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteSekolah(${
              item.id
          })">Delete</button>
        </td>
      `;
            tbody.appendChild(tr);
        });
    });

    renderPagination(groupedData.length);
}

// 3. Render tombol pagination
function renderPagination(totalItems = null) {
    const totalPages = Math.ceil(
        (totalItems ?? filteredData.length) / itemsPerPage
    );
    document.querySelector('button[onclick="prevPage()"]').disabled =
        currentPage === 1;
    document.querySelector('button[onclick="nextPage()"]').disabled =
        currentPage === totalPages;
}

// 4. Prev / Next
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}
function nextPage() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
}

// 5. Search lokal
function searchCctvPanorama() {
    const q = document.getElementById("searchInput").value.trim().toLowerCase();
    filteredData = panoramaData.filter(
        (item) =>
            item.namaWilayah.toLowerCase().includes(q) ||
            item.namaTitik.toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTable();
}
// Hapus Data
function deleteSekolah(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/cctvpanorama/${id}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((r) => r.json())
                .then((res) => {
                    if (res.success) {
                        Swal.fire("Dihapus", res.message, "success");
                        loadpanoramaData();
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                })
                .catch((err) => {
                    console.error(err);
                    Swal.fire("Error", "Gagal menghapus.", "error");
                });
        }
    });
}

// 7. Open modal edit/ add (contoh)
function openAddModal() {
    $("#panoramaForm")[0].reset();
    $("#panoramaForm").attr("action", "/api/cctvpanorama");
    $("#panoramaForm").attr("method", "POST");
    $("#panoramaModalLabel").text("Tambah CCTV Panorama");
    $("#saveBtn").text("Save");
    new bootstrap.Modal($("#panoramaModal")).show();
}
function openEditModal(item) {
    $("#idSekolah").val(item.id);
    $("#namaWilayah").val(item.namaWilayah);
    $("#namaTitik").val(item.namaTitik);
    $("#link").val(item.link);

    $("#panoramaForm").attr("action", `/api/cctvpanorama/${item.id}`);
    $("#panoramaForm").attr("method", "PUT");
    $("#panoramaModalLabel").text("Edit CCTV Panorama");
    $("#saveBtn").text("Update");
    new bootstrap.Modal($("#panoramaModal")).show();
}

// 8. Inisialisasi ketika dokumen siap
document.addEventListener("DOMContentLoaded", loadpanoramaData);

// Add dan Edit
document
    .getElementById("panoramaForm")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        const id = document.getElementById("idSekolah").value;
        const method = id ? "PUT" : "POST";
        const url = id ? `/api/cctvpanorama/${id}` : "/api/cctvpanorama";

        const data = {
            namaWilayah: document.getElementById("namaWilayah").value,
            namaTitik: document.getElementById("namaTitik").value,
            link: document.getElementById("link").value,
        };

        fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then((res) => res.json())
            .then((res) => {
                if (res.success) {
                    Swal.fire("Berhasil", res.message, "success");
                    document.getElementById("panoramaForm").reset();
                    document.getElementById("idSekolah").value = "";

                    const modalElement =
                        document.getElementById("panoramaModal");
                    const modalInstance =
                        bootstrap.Modal.getInstance(modalElement) ||
                        new bootstrap.Modal(modalElement);

                    // Fix overlay modal backdrop
                    document
                        .querySelectorAll(".modal-backdrop")
                        .forEach((el) => el.remove());
                    document.body.classList.remove("modal-open");
                    document.body.style.overflow = "";
                    document.body.style.paddingRight = "";
                    modalInstance.hide();

                    loadpanoramaData();
                } else {
                    let errorText = "";
                    if (res.data && typeof res.data === "object") {
                        for (const key in res.data) {
                            errorText += `${key}: ${res.data[key].join(
                                ", "
                            )}\n`;
                        }
                    } else {
                        errorText = res.message;
                    }
                    Swal.fire("Gagal", errorText, "error");
                }
            })
            .catch((err) => {
                console.error(err);
                Swal.fire(
                    "Error",
                    "Terjadi kesalahan saat menyimpan data.",
                    "error"
                );
            });
    });
