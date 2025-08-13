@extends('layouts.user_type.guest')

@section('content')


    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Edit CCTV Sekolah</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('sekolah.update', $sekolah->id) }}" method="POST" role="form text-left">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namaWilayah" class="form-control-label">{{ __('Nama Wilayah') }}</label>
                                <div class="@error('namaWilayah')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('region_name', $sekolah->namaWilayah) }}"
                                        type="text" placeholder="Nama Wilayah" id="namaWilayah" name="namaWilayah">
                                    @error('namaWilayah')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namaSekolah" class="form-control-label">{{ __('Nama Sekolah') }}</label>
                                <div class="@error('namaSekolah')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('namaSekolah', $sekolah->namaSekolah) }}"
                                        type="text" placeholder="Nama Sekolah" id="namaSekolah" name="namaSekolah">
                                    @error('namaSekolah')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namaTitik" class="form-control-label">{{ __('Nama Titik') }}</label>
                                <div class="@error('namaTitik')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('namaTitik', $sekolah->namaTitik) }}"
                                        type="text" placeholder="Nama Titik" id="namaTitik" name="namaTitik">
                                    @error('namaTitik')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link" class="form-control-label">{{ __('Link') }}</label>
                                <div class="@error('link')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('cctv_link', $sekolah->link) }}" type="text"
                                        placeholder="Link" id="link" name="link">
                                    @error('link')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection