@extends('layouts.user_type.auth')

@section('content')
    <main class="main-content">
        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        {{-- Header --}}
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Data Users</h6>
                            <div class="d-flex">
                                <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                                    placeholder="Search..." onkeyup="searchUsers()" style="height: 2.1rem; padding: 5px;">
                                <a href="javascript:;" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#UsersModal" onclick="openAddModal()">Add</a>
                            </div>
                        </div>

                        <!-- Table & Pagination -->
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                {{-- Tables --}}
                                @include('users.partials.table')
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-primary btn-sm ms-3 py-2 px-3" onclick="prevPage()">Prev</button>
                                <button class="btn btn-primary btn-sm me-3 py-2 px-3" onclick="nextPage()">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memanggil modal Add dan Edit dari partials -->
            @include('users.partials.modals')

        </div>
    </main>
@endsection

@push('scriptsku')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- js-cookie jika butuh token -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
    <script>
        const csrfToken = "{{ csrf_token() }}";
        const token = Cookies.get('token') || ''; // kalau pakai auth header
    </script>
    <!-- Custom Script -->
    <script src="{{ asset('js/users.js') }}"></script>
@endpush