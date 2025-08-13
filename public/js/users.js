let UsersData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 10;

// 1. Ambil data dari API
function loadUsersData() {
    fetch("/api/users", { headers: { Accept: "application/json" } })
        .then((res) => res.json())
        .then((json) => {
            console.log("Data dari API:", json);

            if (!json.success) {
                return Swal.fire("Error", json.message, "error");
            }

            // Urutkan data berdasarkan properti 'name' secara alfabet (case insensitive)
            const sortedData = json.data.sort((a, b) => {
                const nameA = a.name.toLowerCase();
                const nameB = b.name.toLowerCase();
                return nameA.localeCompare(nameB);
            });

            UsersData = sortedData;
            filteredData = UsersData; // awalnya filter = semua data
            currentPage = 1;
            renderTable();
        })
        .catch((err) => {
            console.error(err);
            Swal.fire("Error", "Gagal memuat data.", "error");
        });
}

// 2. Render tabel berdasarkan filteredData & paging
function renderTable() {
    const tbody = document.getElementById("users-tbody");
    tbody.innerHTML = "";

    // paging
    const start = (currentPage - 1) * itemsPerPage;
    const pageData = filteredData.slice(start, start + itemsPerPage);

    pageData.forEach((item) => {
        // Format created_at untuk menampilkan tanggal dan jam saja
        const createdAt = new Date(item.created_at);
        const formattedDate = createdAt.toLocaleString("id-ID", {
            weekday: "short", // opsional, menampilkan hari (Sen, Sel, dst)
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            hour12: false, // jika ingin format 24 jam
        });
        const tr = document.createElement("tr");
        tr.innerHTML = `
      <td class="text-center">${item.name}</td>
      <td class="text-center">${item.email}</td>
      <td class="text-center">${formattedDate}</td>
      <td class="text-center">
        <button class="btn btn-sm btn-secondary" onclick='openEditModal(${JSON.stringify(
            item
        )})'>Edit</button>
        <button class="btn btn-sm btn-danger" onclick="deleteUsers(${
            item.id
        })">Delete</button>
      </td>
    `;
        tbody.appendChild(tr);
    });

    renderPagination();
}

// 3. Render tombol pagination
function renderPagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
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
function searchUsers() {
    const q = document.getElementById("searchInput").value.trim().toLowerCase();
    filteredData = UsersData.filter(
        (item) =>
            item.name.toLowerCase().includes(q) ||
            item.email.toLowerCase().includes(q) ||
            item.created_at.toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTable();
}
// Hapus Data
function deleteUsers(id) {
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
            fetch(`/api/users/${id}`, {
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
                        loadUsersData();
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
    $('#usersForm')[0].reset();
    $('#idUsers').val('');
    $('#usersForm').attr('action', '/api/users');
    $('#usersForm').attr('method', 'POST');
    $('#UsersModalLabel').text('Tambah Pengguna');
    $('#saveBtn').text('Save');
    $('#password').prop('required', true);
    usersModal.show();
}


function openEditModal(item) {
    $("#idUsers").val(item.id);
    $("#name").val(item.name);
    $("#email").val(item.email);
    $("#password").val("");
    $("#password").prop("required", false);
    $("#modalForm").attr("action", `/api/users/${item.id}`);
    $("#modalForm").attr("method", "PUT");
    $("#UsersModalLabel").text("Edit users");
    $("#saveBtn").text("Update");
    usersModal.show();
}

// 8. Inisialisasi ketika dokumen siap
let usersModal;

document.addEventListener("DOMContentLoaded", () => {
    usersModal = new bootstrap.Modal(document.getElementById("UsersModal"));
    loadUsersData();
});



// Add dan Edit
document.getElementById("usersForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const id = document.getElementById("idUsers").value;
    const method = id ? "PUT" : "POST";
    const url = id ? `/api/users/${id}` : "/api/users";

    const data = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        role: document.getElementById("role").value,
    };

    if (!id) delete data.id;

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
                document.getElementById("usersForm").reset();
                document.getElementById("idUsers").value = "";

                const modalElement = document.getElementById("UsersModal");
                const modalInstance =
                    bootstrap.Modal.getInstance(modalElement) ||
                    new bootstrap.Modal(modalElement);

                // Fix overlay modal backdrop
                
                modalInstance.hide();
                // Hilangkan backdrop secara manual
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

                loadUsersData();
            } else {
                let errorText = "";
                if (res.data && typeof res.data === "object") {
                    for (const key in res.data) {
                        errorText += `${key}: ${res.data[key].join(", ")}\n`;
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
