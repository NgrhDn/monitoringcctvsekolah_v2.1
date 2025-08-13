<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/sekolah.css') }}" rel="stylesheet">

@php
    // Hapus semua query Eloquent di sini. Data disuplai dari controller:
    // $groupedCctvs, $jumlahCCTV, $jumlahSekolah, $jumlahWilayah, $jumlahCCTVaktif, $namaWilayahLengkap
@endphp

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <button class="btn btn-outline-primary me-3" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="navbar-brand">
                <div>
                    <i class="fas fa-video text-primary me-2"></i>
                    CCTV SEKOLAH
                </div>
                <div style="font-size: 0.7rem; color: #6c757d; font-weight: normal;">
                    Memantau Kondisi Keamanan Sekolah DIY
                </div>
            </div>
            <div class="ms-auto">
                <span class="badge bg-success me-2">
                    <i class="fas fa-circle me-1"></i>Online
                </span>
                <span class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    <span id="currentTime"></span>
                </span>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-4">
            <h4 class="m-0 text-white">
                <i class="fas fa-video me-2"></i>CCTV Control
            </h4>
            <button class="btn btn-link text-white p-0" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <div class="px-3 pb-3 pt-3">
                <div class="row g-2">
                    <div class="col-8">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control form-control-sm" id="sidebarSearchInput" placeholder="Cari..." style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; font-size: 0.8rem; padding: 8px 15px 8px 35px;">
                        </div>
                    </div>
                    <div class="col-4">
                        <button id="sidebarHideAllBtn"
                                class="btn btn-sm hide-all-btn w-100"
                                style="font-size: 0.7rem; padding: 8px 5px;"
                                disabled>
                            <i id="sidebarHideAllIcon" class="fas fa-eye-slash me-1"></i>
                            <span id="sidebarHideAllText">Sembunyikan</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sidebar-nav-wrapper d-flex flex-column h-100 px-2">
                <div class="flex-grow-1 overflow-auto" id="sidebar-scroll-area">
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <div class="sidebar-link d-flex justify-content-between align-items-center"
                                onclick="toggleDropdown('cctvDropdown')">
                                <span><i class="fas fa-video me-2"></i>CCTV Monitoring</span>
                                <i class="fas fa-chevron-right" id="cctvChevron"></i>
                            </div>
                            <div class="dropdown-content" id="cctvDropdown">
                                @foreach ($groupedCctvs as $wilayah => $sekolahGroup)
                                    <div class="dropdown-item" onclick="toggleSubDropdown('{{ Str::slug($wilayah) }}Dropdown')">
                                        <i class="fas fa-map-marker-alt me-2"></i>{{ $namaWilayahLengkap[$wilayah] ?? $wilayah }}
                                        <i class="fas fa-chevron-right float-end" id="{{ Str::slug($wilayah) }}Chevron"></i>
                                    </div>

                                    <div class="dropdown-content sub-dropdown ps-3" id="{{ Str::slug($wilayah) }}Dropdown">
                                        @foreach ($sekolahGroup as $namaSekolah => $cctvs)
                                            @php $sekolahSlug = Str::slug($namaSekolah); @endphp
                                            <div class="dropdown-item d-flex justify-content-between align-items-center"
                                                onclick="toggleSchool('{{ $sekolahSlug }}')">
                                                <span><i class="fas fa-school me-2"></i>{{ $namaSekolah }}</span>
                                                <div>
                                                    <i id="eye-{{ $sekolahSlug }}" class="fas fa-eye-slash me-2"
                                                        onclick="event.stopPropagation(); toggleAllSchoolCCTV('{{ $namaSekolah }}')"
                                                        title="Tampilkan/sembunyikan CCTV {{ $namaSekolah }}"
                                                        style="cursor: pointer; opacity: 0.7;"></i>
                                                    <i class="fas fa-chevron-right" id="{{ $sekolahSlug }}Chevron"></i>
                                                </div>
                                            </div>

                                            <div class="dropdown-content sub-dropdown ps-4" id="{{ $sekolahSlug }}">
                                                @foreach ($cctvs as $cctv)
                                                    <div class="dropdown-item d-flex justify-content-between align-items-center" style="padding: 8px 12px;">
                                                        <div class="d-flex align-items-center flex-grow-1"
                                                            onclick="selectCCTV('{{ $cctv->sekolah->nama_sekolah }}', '{{ $cctv->nama_titik }}')"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-camera me-2"></i>
                                                            <span>{{ $cctv->nama_titik }}</span>
                                                        </div>
                                                        <div class="form-check form-switch me-0" style="min-width: 40px;">
                                                            <input class="form-check-input cctv-point-checkbox"
                                                                type="checkbox"
                                                                id="checkbox-{{ Str::slug($cctv->sekolah->nama_sekolah . '-' . $cctv->nama_titik) }}"
                                                                data-card-id="{{ Str::slug($cctv->sekolah->nama_sekolah . '-' . $cctv->nama_titik) }}"
                                                                onchange="toggleCCTVFromSidebar(this)"
                                                                style="margin: 0; cursor: pointer;"
                                                                onclick="event.stopPropagation();"
                                                                {{ $cctv->active ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="mt-3 pt-3 pb-3 border-top border-white border-opacity-10">
                    <ul class="sidebar-nav">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="sidebar-item">
                                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                                        <span><i class="fas fa-tachometer-alt me-3"></i>Dashboard Admin</span>
                                    </a>
                                </li>
                            @endif
                        @endauth
                        <li class="sidebar-item">
                            <a  class="sidebar-link text-danger" onclick="confirmLogout()">
                                <span><i class="fas fa-sign-out-alt me-3"></i> Logout</span>
                            </a>
                        </li>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <script>
                        function confirmLogout() {
                            Swal.fire({
                                title: 'Apakah anda ingin logout?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('logoutForm').submit();
                                }
                            });
                        }
                        </script>

                    </ul>
                </div>
            </div>
        </div>

        <div class="sidebar-footer px-3 py-3 border-top border-white border-opacity-10">
            <div class="text-center mb-3">
                <small class="text-white-50">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Login: <span id="loginTime">{{ session('login_time', 'N/A') }}</span>
                </small>
            </div>

            <div class="user-info d-flex align-items-center">
                <div class="user-avatar me-3">
                    <i class="fas fa-user-shield text-white fs-4"></i>
                </div>
                <div class="user-details">
                    <div class="user-name text-white fw-bold">{{ Auth::user()->name }}</div>
                    <div class="user-role text-white-50">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('profil.pengguna') }}" class="btn btn-sm btn-outline-light w-100" style="font-size: 0.8rem;">
                    <i class="fas fa-user-cog me-1"></i>Profil Pengguna
                </a>
            </div>
        </div>
    </div>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" style="font-size: 24pt;" id="cctvCount">{{ $jumlahCCTV }}</h3>
                                    <p class="mb-0 small">Total CCTV</p>
                                </div>
                                <div class="text-white-50">
                                    <i class="fas fa-video fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #27ae60, #229954);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" style="font-size: 24pt;" id="schoolCount">{{ $jumlahSekolah }}</h3>
                                    <p class="mb-0 small">Total Sekolah</p>
                                </div>
                                <div class="text-white-50">
                                    <i class="fas fa-school fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1" id="regionCount">{{ $jumlahWilayah }}</h3>
                                    <p class="mb-0 small">Total Wilayah</p>
                                </div>
                                <div class="text-white-50">
                                    <i class="fas fa-map-marker-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1" id="activeCCTVCountCard">{{ $jumlahCCTVaktif }}</h3>
                                    <p class="mb-0 small">CCTV Aktif</p>
                                </div>
                                <div class="text-white-50">
                                    <i class="fas fa-eye fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="control-panel">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-video text-primary me-2"></i>
                            Live CCTV Monitoring
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="badge bg-success fs-6 mb-2">
                            <i class="fas fa-shield-alt me-1"></i>
                            Sistem Normal
                        </div>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-circle text-success me-1"></i>
                            Monitoring real-time dari 5 kabupaten/kota
                        </p>
                    </div>
                </div>
            </div>

            <div class="cctv-grid" id="cctvGrid">
                <!-- Render on-demand: grid dikosongkan -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/dashboard_public.js') }}"></script>

    <script>
        // Index dari controller
        window.cctvIndex = @json($cctvIndex ?? []);
        window.cctvById = {};
        Object.values(window.cctvIndex).forEach(list => (list || []).forEach(it => window.cctvById[it.cardId] = it));

        function slugify(txt) {
            return (txt || '').toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');
        }
        function shortNameOf(name) {
            const words = (name || '').trim().split(/\s+/);
            return words.length > 3 ? words.map(w => (w[0]||'').toUpperCase()).join('') : (name || '');
        }

        function renderCard(item) {
            const grid = document.getElementById('cctvGrid');
            if (!grid || document.getElementById(item.cardId)) return;
            const wrap = document.createElement('div');
            wrap.className = 'cctv-card';
            wrap.id = item.cardId;
            wrap.dataset.sekolah = item.sekolahSlug;
            wrap.dataset.wilayah = item.wilayah;
            wrap.dataset.titik = item.titik;
            wrap.dataset.isActive = item.active ? '1' : '0';
            wrap.style.display = 'none';
            wrap.innerHTML = `
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold">${item.sekolah}</h6>
                            <label class="toggle-switch">
                                <input type="checkbox" ${item.active ? 'checked' : ''} onchange="toggleCCTV(this, '${item.cardId}')">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p class="text-center mb-2" style="font-size: 0.8rem;">${shortNameOf(item.titik)}</p>
                        <div class="iframe-container">
                            <div class="iframe-loader">Memuat...</div>
                            <iframe loading="lazy" data-src="${item.link}" frameborder="0" allowfullscreen title="CCTV Live Stream"></iframe>
                        </div>
                        <div class="status-indicator mt-2">
                            <div class="status-dot"></div>
                            <span>${item.active ? 'Online' : 'Offline'}</span>
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(wrap);
        }
        function ensureCardRenderedById(cardId) {
            if (document.getElementById(cardId)) return true;
            const item = window.cctvById[cardId];
            if (!item) return false;
            renderCard(item);
            return !!document.getElementById(cardId);
        }
        function showCard(cardEl) {
            if (!cardEl) return;
            const iframe = cardEl.querySelector('iframe');
            if (iframe && !iframe.src) iframe.src = iframe.getAttribute('data-src') || '';
            cardEl.style.display = '';
        }
        function hideCard(cardEl) {
            if (!cardEl) return;
            cardEl.style.display = 'none';
        }

        function visibleCardsCount() {
            return Array.from(document.querySelectorAll('#cctvGrid .cctv-card'))
                .filter(n => n.style.display !== 'none').length;
        }

        function updateHideAllButtonState() {
            const btn = document.getElementById('sidebarHideAllBtn');
            const icon = document.getElementById('sidebarHideAllIcon');
            const hasVisible = visibleCardsCount() > 0;

            btn.disabled = !hasVisible;
            icon.classList.remove('fa-eye', 'fa-eye-slash');
            // ada yang tampil => tombol “Sembunyikan” aktif (ikon eye-slash), tidak ada => ikon eye
            icon.classList.add(hasVisible ? 'fa-eye-slash' : 'fa-eye');
        }

        // Ikon per-sekolah berdasarkan visibilitas kartu di grid
        function updateSchoolEyeIcon(sekolahSlug) {
            const eye = document.getElementById('eye-' + sekolahSlug);
            if (!eye) return;
            const anyVisible = Array.from(document.querySelectorAll(`#cctvGrid .cctv-card[data-sekolah="${sekolahSlug}"]`))
                .some(card => card.style.display !== 'none');

            eye.classList.remove('fa-eye', 'fa-eye-slash');
            eye.classList.add(anyVisible ? 'fa-eye' : 'fa-eye-slash');
        }

        // Tampilkan jumlah CCTV aktif (kartu yang terlihat) secara live
        function updateActiveCCTVCount() {
            const el = document.getElementById('activeCCTVCountCard');
            if (!el) return;
            el.textContent = visibleCardsCount();
        }

        function hideAllVisibleCCTV() {
            const cards = document.querySelectorAll('.cctv-card');

            cards.forEach(card => {
                if (card.style.display !== 'none') {
                    card.style.display = 'none';
                    card.dataset.isActive = '0';

                    const cardCheckbox = card.querySelector('.toggle-switch input[type="checkbox"]');
                    if (cardCheckbox) cardCheckbox.checked = false;

                    const sidebarCheckbox = document.getElementById(`checkbox-${card.id}`);
                    if (sidebarCheckbox) sidebarCheckbox.checked = false;
                }
            });

            // Sinkronkan semua ikon mata per-sekolah setelah disembunyikan
            document.querySelectorAll('[id^="eye-"]').forEach(icon => {
                const slug = icon.id.replace('eye-', '');
                updateSchoolEyeIcon(slug);
            });

            updateHideAllButtonState();
            updateActiveCCTVCount();
        }

        // Toggle dari checkbox di sidebar per titik CCTV
        function toggleCCTVFromSidebar(checkbox) {
            const cardId = checkbox.dataset.cardId;
            let card = document.getElementById(cardId);

            // Jika belum dirender dan ingin ditampilkan -> render dulu
            if (!card && checkbox.checked) {
                ensureCardRenderedById(cardId);
                card = document.getElementById(cardId);
            }
            if (!card) return;

            if (checkbox.checked) {
                card.style.display = 'block';
                card.dataset.isActive = '1';
                const cardCheckbox = card.querySelector('.toggle-switch input');
                if (cardCheckbox) cardCheckbox.checked = true;
                const iframe = card.querySelector('iframe');
                if (iframe && !iframe.src) iframe.src = iframe.dataset.src;
            } else {
                card.style.display = 'none';
                card.dataset.isActive = '0';
                const cardCheckbox = card.querySelector('.toggle-switch input');
                if (cardCheckbox) cardCheckbox.checked = false;
            }

            const sekolahSlug = card.dataset.sekolah;
            updateSchoolEyeIcon(sekolahSlug);
            updateHideAllButtonState();
            updateActiveCCTVCount();
        }

        // Toggle dari switch pada kartu di grid
        function toggleCCTV(el, cardId) {
            const card = document.getElementById(cardId);
            if (!card) return;

            const sidebarCheckbox = document.getElementById('checkbox-' + cardId);

            if (el.checked) {
                showCard(card);
                card.dataset.isActive = '1';
                if (sidebarCheckbox) sidebarCheckbox.checked = true;

                const iframe = card.querySelector('iframe');
                if (iframe && !iframe.src) iframe.src = iframe.dataset.src;
            } else {
                hideCard(card);
                card.dataset.isActive = '0';
                if (sidebarCheckbox) sidebarCheckbox.checked = false;
            }

            updateSchoolEyeIcon(card.dataset.sekolah);
            updateHideAllButtonState();
            updateActiveCCTVCount();
        }

        window.toggleAllSchoolCCTV = function(namaSekolah) {
            const sekolahSlug = slugify(namaSekolah);
            const cbs = (document.getElementById(sekolahSlug) || document).querySelectorAll('.cctv-point-checkbox');

            const anyVisible = Array.from(document.querySelectorAll(`#cctvGrid .cctv-card[data-sekolah="${sekolahSlug}"]`))
                .some(card => card.style.display !== 'none');
            const targetShow = !anyVisible;

            cbs.forEach(cb => {
                cb.checked = targetShow;
                const id = cb.dataset.cardId;
                if (targetShow) {
                    if (ensureCardRenderedById(id)) {
                        const cardEl = document.getElementById(id);
                        showCard(cardEl);
                        const cardCheckbox = cardEl.querySelector('.toggle-switch input');
                        if (cardCheckbox) cardCheckbox.checked = true;
                    }
                } else {
                    const cardEl = document.getElementById(id);
                    hideCard(cardEl);
                    if (cardEl) {
                        const cardCheckbox = cardEl.querySelector('.toggle-switch input');
                        if (cardCheckbox) cardCheckbox.checked = false;
                    }
                }
            });

            updateSchoolEyeIcon(sekolahSlug);
            updateHideAllButtonState();
            updateActiveCCTVCount();
        };


        window.selectCCTV = function(namaSekolah, namaTitik) {
            const sekolahSlug = slugify(namaSekolah);
            const list = window.cctvIndex[sekolahSlug] || [];
            const found = list.find(x => (x.titik || '').toLowerCase() === (namaTitik || '').toLowerCase());
            if (!found) return;

            if (ensureCardRenderedById(found.cardId)) {
                const el = document.getElementById(found.cardId);
                showCard(el);
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const cb = document.getElementById('checkbox-' + found.cardId);
                if (cb && !cb.checked) cb.checked = true;
            }
            updateSchoolEyeIcon(sekolahSlug);
            updateHideAllButtonState();
        };

        document.getElementById('sidebarHideAllBtn').addEventListener('click', function() {
            if (this.disabled) return;
            hideAllVisibleCCTV();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Sinkron awal ikon mata per-sekolah
            document.querySelectorAll('[id^="eye-"]').forEach(icon => {
                const slug = icon.id.replace('eye-', '');
                updateSchoolEyeIcon(slug);
            });
            updateHideAllButtonState();
            updateActiveCCTVCount(); // tampilkan jumlah aktif live saat awal
        });
    </script>
</body>