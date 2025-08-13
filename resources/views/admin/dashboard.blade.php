@extends('layouts.user_type.auth')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>Dashboard CCTV</title>
    <link rel="stylesheet" href="{{ asset('assets/css/weather-card.css') }}">
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>

</head>

<header class="header">
    <h4 style="text-align: left; margin-left: 20px;">Selamat Datang, Admin</h4>
    <p style="text-align: left; margin-left: 20px; margin-bottom: -15px;">Semoga kebaikan selalu menyertaimu</p>
</header>

<div class="container">
    <div class="dashboard-container">
        <!-- Weather -->
        <div class="weather-card">
            <img class="image-card" src="{{ asset('images/people.svg') }}" alt="">
            <div class="weather-info d-flex" id="weatherInfo" style="margin-top: 10px;">
                <div class="weather-details">
                    <h4 class="location font-weight-normal" id="location">Loading...</h4>
                    <h5 class="country font-weight-normal" id="country">Indonesia</h5>
                </div>
                <div class="temperature-info d-flex justify-content-end" style="margin-left: 125px;">
                    <h2 class="mb-0 font-weight-normal" id="temperature">
                        <i id="weatherIcon" class="mdi" style="font-size: 30px;"></i>
                        <span id="tempValue"></span>
                    </h2>
                </div>
            </div>
        </div>

        <!-- Card section -->
        <div class="card-container">
            <div class="card-row">
                <div class="card" style="background-color:#7da0fa; cursor: pointer;">
                    <a href="{{ route('rekapan.users') }}" style="text-decoration: none;">
                        <div class="card-content">
                            <div class="left-column">
                                <h3 style="color: white;">Users</h3>
                                <p style="color: white; font-size: 24pt;" class="fs-30 mb-2">{{ $userCount }}</p>
                            </div>
                            <div class="right-column">
                                <i class="fas fa-users icon-card"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card" style="background-color: rgba(71,71,161,255); cursor: pointer;">
                    <a href="{{ route('rekapan.cctv.panorama') }}" style="text-decoration: none;">
                        <div class="card-content">
                            <div class="left-column">
                                <h6 style="color: white; font-size: 13pt;">CCTV Panorama</h6>
                                <p style="color: white; font-size: 24pt;" class="fs-30 mb-2">{{ $panoramaCount }}</p>
                            </div>
                            <div class="right-column">
                                <i class="fas fa-earth-americas icon-card"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card-row">
                <div class="card" style="background-color:#7978e9; cursor: pointer;">
                    <a href="{{ route('rekapan.cctv.sekolah') }}" style="text-decoration: none;">
                        <div class="card-content">
                            <div class="left-column">
                                <h5 style="color: white; font-size: 13pt;">CCTV Sekolah</h5>
                                <p style="color: white; font-size: 24pt;" class="fs-30 mb-2">{{ $sekolahCount }}</p>
                            </div>
                            <div class="right-column">
                                <i class="fas fa-book-open icon-card"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card" style="background-color: #f3797e; cursor: pointer;">
                    <a href="{{ route('rekapan.detailsekolah') }}" style="text-decoration: none;">
                        <div class="card-content">
                            <div class="left-column">
                                <h3 style="color: white;">Sekolah</h3>
                                <p style="color: white; font-size: 24pt;" class="fs-30 mb-2">
                                    {{ $jumlahCCTVPerSekolah->count() }}
                                </p>
                            </div>
                            <div class="right-column">
                                <i class="fas fa-book-open icon-card"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik -->
<div style="padding: 20px 15px; max-width: 1140px; margin: 0 auto;">
    <div class="statistik-section" style="margin-top: -70px; margin-bottom: 40px;">
        <h4>Statistik CCTV</h4>
        <div style="margin-top: 20px;">
            <h5>Grafik Jumlah Sekolah dan CCTV per Wilayah</h5>
            <canvas id="wilayahChart" style="max-width: 100%; height: 400px;"></canvas>
        </div>
        <div style="margin-top: 40px;">
            <h5>Grafik Jumlah CCTV per Sekolah</h5>
            <canvas id="sekolahChart" style="max-width: 100%; height: 300px;"></canvas>
        </div>
    </div>
</div>

<script>
    const sekolahPerWilayah = @json($jumlahSekolahPerWilayah);
    const cctvPerWilayah = @json($jumlahCCTVPerWilayah);
    const cctvPerSekolah = @json($jumlahCCTVPerSekolah);

    const wilayahLabels = [...new Set([
        ...sekolahPerWilayah.map(d => d.namaWilayah),
        ...cctvPerWilayah.map(d => d.namaWilayah)
    ])];

    const sekolahData = wilayahLabels.map(w => {
        const match = sekolahPerWilayah.find(d => d.namaWilayah === w);
        return match ? match.total_sekolah : 0;
    });

    const cctvWilayahData = wilayahLabels.map(w => {
        const match = cctvPerWilayah.find(d => d.namaWilayah === w);
        return match ? match.total_cctv : 0;
    });

    new Chart(document.getElementById('wilayahChart'), {
        type: 'bar',
        data: {
            labels: wilayahLabels,
            datasets: [{
                label: 'Jumlah Sekolah',
                data: sekolahData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }, {
                label: 'Jumlah CCTV',
                data: cctvWilayahData,
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Statistik Sekolah & CCTV per Wilayah'
                }
            }
        }
    });

    const sekolahByWilayah = {};
    cctvPerSekolah.forEach(item => {
        if (!sekolahByWilayah[item.namaWilayah]) {
            sekolahByWilayah[item.namaWilayah] = [];
        }
        sekolahByWilayah[item.namaWilayah].push(item);
    });

    const sekolahLabels = [];
    const cctvSekolahData = [];
    const backgroundColors = [];

    Object.entries(sekolahByWilayah).forEach(([wilayah, sekolahList], wilayahIndex) => {
        sekolahList.forEach((item, sekolahIndex) => {
            sekolahLabels.push(item.namaSekolah);
            cctvSekolahData.push(item.total_cctv);
            const hue = (wilayahIndex * 60) % 360;
            const lightness = 50 + (sekolahIndex * 10) % 30;
            backgroundColors.push(`hsl(${hue}, 70%, ${lightness}%)`);
        });
    });

    new Chart(document.getElementById('sekolahChart'), {
        type: 'pie',
        data: {
            labels: sekolahLabels,
            datasets: [{
                label: 'Jumlah CCTV',
                data: cctvSekolahData,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                title: {
                    display: true,
                    text: 'Jumlah CCTV per Sekolah'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed} CCTV`;
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiKey = '66db882578a46f03c27c30a952240556';
        const lat = -7.797068;
        const lon = 110.370529;

        const iconMap = {
            '01d': 'mdi-weather-sunny',
            '01n': 'mdi-weather-night',
            '02d': 'mdi-weather-partlycloudy',
            '02n': 'mdi-weather-night',
            '03d': 'mdi-weather-cloudy',
            '03n': 'mdi-weather-cloudy',
            '04d': 'mdi-weather-cloudy',
            '04n': 'mdi-weather-cloudy',
            '09d': 'mdi-weather-pouring',
            '09n': 'mdi-weather-pouring',
            '10d': 'mdi-weather-rainy',
            '10n': 'mdi-weather-rainy',
            '11d': 'mdi-weather-lightning',
            '11n': 'mdi-weather-lightning',
            '13d': 'mdi-weather-snowy',
            '13n': 'mdi-weather-snowy',
            '50d': 'mdi-weather-fog',
            '50n': 'mdi-weather-fog',
        };

        fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const temperature = Math.round(data.main.temp);
                const location = data.name;
                const iconCode = data.weather[0].icon;
                const mdiClass = iconMap[iconCode] || 'mdi-weather-cloudy';

                document.getElementById('weatherIcon').className = 'mdi mr-2 ' + mdiClass;
                document.getElementById('tempValue').innerHTML = `${temperature}<sup>°C</sup>`;
                document.getElementById('location').textContent = location;
                document.getElementById('country').textContent = 'Indonesia';
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
    });
</script>

@endsection
