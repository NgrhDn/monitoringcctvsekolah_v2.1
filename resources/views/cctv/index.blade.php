@extends('layouts.user_type.guest')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>CCTV List</h6>
                    </div>
                    <div class="card-header pb-0">
                        <a href="{{ route('cctv.create') }}" class="btn btn-primary">Tambah CCTV</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Wilayah</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Titik Wilayah</th>
                                        <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor Kamera</th> -->
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cctv as $cctv)
                                        <tr>
                                            <td class="align-middle">
                                                <p class="text-sm font-weight-bold mb-0">{{ $cctv->namaWilayah }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="text-sm font-weight-bold mb-0">{{ $cctv->namaTitik }}</p>
                                            </td>
                                            <!-- <td class="align-middle text-center">
                                                    <p class="text-sm font-weight-bold mb-0">{{ $cctv->nomorKamera }}</p>
                                                </td> -->
                                            <td class="align-middle">
                                                <a href="{{ route('cctv.edit', $cctv->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ route('cctv.delete', $cctv->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this CCTV?')">Delete</button>
                                                </form>
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
