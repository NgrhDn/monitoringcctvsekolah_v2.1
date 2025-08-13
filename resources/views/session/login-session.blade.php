@extends('layouts.user_type.guest')

@section('content')
<main class="main-content mt-0">
    <section class="min-vh-100 d-flex align-items-center justify-content-center" style="background-color: #007bff;">
        <div class="container d-flex justify-content-center">
            <div class="login-box d-flex flex-row bg-white shadow-lg" 
                style="width: 900px; border-radius: 40px; overflow: hidden;">

                <!-- Form Section -->
                <div class="form-section p-5 flex-fill d-flex flex-column justify-content-center" style="width: 50%;">
                    <h4 class="text-dark fw-bold mb-4 text-center">SISTEM MONITORING CCTV SEKOLAH</h4>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                style="width: 100%; border-radius: 30px;"
                                placeholder="Masukkan Email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                style="width: 100%; border-radius: 30px;"
                                placeholder="Masukkan Password" required>
                        </div>

                        <div class="mb-3">
                            <button type="submit" 
                                style="background-color: #007bff; 
                                    color: white; 
                                    width: 100%; 
                                    border: none; 
                                    border-radius: 50px; 
                                    padding: 10px 0; 
                                    font-weight: bold;">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Image Section -->
                <div class="image-section d-none d-md-block flex-fill" style="
                    width: 50%;
                    background: url('{{ asset('images/logocctv.jpg') }}') no-repeat center center;
                    background-size: contain;
                    background-color: white;
                    min-height: 400px;">
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
