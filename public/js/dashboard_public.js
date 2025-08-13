        // Global functions that need to be accessible from onclick attributes
        
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('active');
            
            const chevron = document.getElementById(dropdownId.replace('Dropdown', 'Chevron'));
            if (chevron) {
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-right');
            }
        }

        function toggleSubDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('active');
            
            const chevron = document.getElementById(dropdownId.replace('Dropdown', 'Chevron'));
            if (chevron) {
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-right');
            }
        }

        function toggleSchool(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('active');
            
            const chevron = document.getElementById(dropdownId + 'Chevron');
            if (chevron) {
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-right');
            }
        }

        // Fungsi untuk memilih CCTV dari sidebar
        function selectCCTV(namaSekolah, namaTitik) {
            const targetId = `${slugify(namaSekolah)}-${slugify(namaTitik)}`;
            const selectedCCTV = document.getElementById(targetId);
            const iframe = card.querySelector('iframe');
            const loader = card.querySelector('.iframe-loader');

            if (iframe && !iframe.src) {
                loader.style.display = 'block';
                iframe.src = iframe.dataset.src;

                iframe.onload = () => {
                    loader.style.display = 'none';
                    iframe.style.display = 'block';
                };
            }
            
            if (selectedCCTV) {
                // Sembunyikan semua CCTV terlebih dahulu
                document.querySelectorAll('.cctv-card').forEach(card => {
                    card.style.display = 'none';
                });
                
                // Uncheck semua sidebar checkboxes
                document.querySelectorAll('.cctv-point-checkbox').forEach(cb => {
                    cb.checked = false;
                });
                
                // Tampilkan CCTV yang dipilih
                selectedCCTV.style.display = 'block';

                selectedCCTV.dataset.isActive = '1'; // Tandai sebagai aktif
                
                // Check sidebar checkbox untuk CCTV yang dipilih
                const sidebarCheckbox = document.getElementById(`checkbox-${targetId}`);
                if (sidebarCheckbox) {
                    sidebarCheckbox.checked = true;
                }
                
                // Centang toggle switch
                const checkbox = selectedCCTV.querySelector('.toggle-switch input');
                if (checkbox) {
                    checkbox.checked = true;
                }
                
                // Lazy load iframe
                const iframe = selectedCCTV.querySelector('iframe');
                if (iframe && !iframe.src) {
                    iframe.src = iframe.dataset.src;
                }
                
                // Update jumlah CCTV aktif
                updateActiveCCTVCount();
            }
        }

        // Fungsi untuk menampilkan semua CCTV di sekolah tertentu
        function toggleAllSchoolCCTV(namaSekolah) {
            const schoolSlug = slugify(namaSekolah);
            const cards = document.querySelectorAll(`.cctv-card[data-sekolah="${schoolSlug}"]`);

            const anyVisible = Array.from(cards).some(card => card.style.display === 'block');
            const targetShow = !anyVisible;

            cards.forEach(card => {
                card.style.display = targetShow ? 'block' : 'none';
                card.dataset.isActive = targetShow ? '1' : '0';

                // Toggle di dalam card
                const cardCheckbox = card.querySelector('.toggle-switch input[type="checkbox"]');
                if (cardCheckbox) {
                    cardCheckbox.checked = targetShow;
                }
                

                // Checkbox di sidebar
                const sidebarCheckbox = document.getElementById(`checkbox-${card.id}`);
                if (sidebarCheckbox) {
                    sidebarCheckbox.checked = targetShow;
                }

                // Lazy load iframe kalau tampil
                if (targetShow) {
                    const iframe = card.querySelector('iframe');
                    if (iframe && !iframe.src) {
                        iframe.src = iframe.dataset.src;
                    }
                }
            });

            // Update ikon mata
            const eyeIcon = document.getElementById(`eye-${schoolSlug}`);
            if (eyeIcon) {
                eyeIcon.classList.remove('fa-eye', 'fa-eye-slash');
                eyeIcon.classList.add(targetShow ? 'fa-eye' : 'fa-eye-slash');
            }
            updateSchoolEyeIcon(sekolahSlug);
            updateHideAllButtonState(); // Tambahan untuk global eye


            updateActiveCCTVCount();
        }

        // Fungsi untuk toggle CCTV individual
        function toggleCCTV(checkbox, cardId) {
            const card = document.getElementById(cardId);
            if (!card) return;

            // Set display
            card.style.display = checkbox.checked ? 'block' : 'none';

            // Update data-is-active
            card.dataset.isActive = checkbox.checked ? '1' : '0';

            // Sinkronkan sidebar checkbox
            const sidebarCheckbox = document.getElementById(`checkbox-${cardId}`);
            if (sidebarCheckbox) {
                sidebarCheckbox.checked = checkbox.checked;
            }

            // Sinkronkan toggle di card
            const cardCheckbox = card.querySelector('.toggle-switch input[type="checkbox"]');
            if (cardCheckbox) {
                cardCheckbox.checked = checkbox.checked;
            }

            updateActiveCCTVCount();
        }

        // Fungsi untuk toggle CCTV dari sidebar checkbox
        function toggleCCTVFromSidebar(checkbox) {
            const cardId = checkbox.dataset.cardId;
            const card = document.getElementById(cardId);
            
            if (card) {
                if (checkbox.checked) {
                    card.style.display = 'block';
                    const cardCheckbox = card.querySelector('.toggle-switch input');
                    if (cardCheckbox) {
                        cardCheckbox.checked = true;
                    }
                    const iframe = card.querySelector('iframe');
                    if (iframe && !iframe.src) {
                        iframe.src = iframe.dataset.src;
                    }
                } else {
                    card.style.display = 'none';
                    const cardCheckbox = card.querySelector('.toggle-switch input');
                    if (cardCheckbox) {
                        cardCheckbox.checked = false;
                    }
                }

                const sekolahSlug = card.dataset.sekolah;
                updateSchoolEyeIcon(sekolahSlug);

                // 🔹 Update global "Sembunyikan Semua"
                updateHideAllButtonState();

                updateActiveCCTVCount();
            }
        }



        // Update fungsi toggleCCTV untuk sinkronisasi dengan sidebar checkbox
        // Fungsi toggleCCTV yang sudah diperbarui
        function toggleCCTV(checkbox, cardId) {
            const card = document.getElementById(cardId);
            if (!card) return;

            card.style.display = checkbox.checked ? 'block' : 'none';

            const sidebarCheckbox = document.getElementById(`checkbox-${cardId}`);
            if (sidebarCheckbox) {
                sidebarCheckbox.checked = checkbox.checked;
            }

            const cardCheckbox = card.querySelector('.toggle-switch input[type="checkbox"]');
            if (cardCheckbox) {
                cardCheckbox.checked = checkbox.checked;
            }

            const sekolahSlug = card.dataset.sekolah;
            updateSchoolEyeIcon(sekolahSlug);

            // 🔹 Update global "Sembunyikan Semua"
            updateHideAllButtonState();

            updateActiveCCTVCount();
        }



        // Fungsi untuk update jumlah CCTV aktif
        function updateActiveCCTVCount() {
            const activeVisibleCctvs = document.querySelectorAll(
                '.cctv-card[data-is-active="1"][style*="display: block"]'
            );
            document.getElementById('activeCCTVCountCard').textContent = activeVisibleCctvs.length;

            if (typeof updateHideAllButton === 'function') {
                updateHideAllButton();
            }
        }


        // Fungsi helper untuk slugify
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // Fungsi untuk dashboard admin
        function showDashboard() {
            alert('Dashboard Admin - Fitur ini akan menampilkan statistik lengkap, laporan keamanan, dan pengaturan sistem CCTV.');
            closeSidebar();
        }

        // Fungsi untuk about
        function showAbout() {
            alert('Tentang Sistem:\n\nDashboard CCTV Sekolah DIY\nVersi 1.0\n\nSistem monitoring keamanan sekolah yang terintegrasi untuk memantau kondisi keamanan di seluruh sekolah di Daerah Istimewa Yogyakarta.\n\nDikembangkan oleh Dinas Pendidikan DIY');
            closeSidebar();
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Update tombol hide/show berdasarkan CCTV yang terlihat
        function updateHideAllButton() {
            const visibleCards = document.querySelectorAll('.cctv-card[style*="display: block"]');
            const hideAllBtn = document.getElementById('sidebarHideAllBtn');
            const hideAllText = document.getElementById('sidebarHideAllText');
            const hideAllIcon = hideAllBtn.querySelector('i');
            
            if (visibleCards.length > 0) {
                hideAllText.textContent = 'Sembunyikan';
                hideAllIcon.className = 'fas fa-eye-slash me-1';
                hideAllBtn.disabled = false;
                hideAllBtn.style.opacity = '1';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi sidebar
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.checked = false;
        });

        document.querySelectorAll('.cctv-point-checkbox').forEach(sidebarCheckbox => {
            sidebarCheckbox.checked = false;
        });

        document.querySelectorAll('.cctv-card').forEach(card => {
            card.style.display = 'none';
        });

        updateActiveCCTVCount();

            function openSidebar() {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('currentTime').textContent = timeString;
            }

            // Panggil sekali untuk isi awal
            updateTime();

            // Perbarui setiap detik
            setInterval(updateTime, 1000);

            

            sidebarToggle.addEventListener('click', openSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Fungsi yang tidak perlu diakses dari onclick dapat tetap di sini

            // Fungsi untuk hide/show semua CCTV
            document.getElementById('sidebarHideAllBtn').addEventListener('click', function() {
                const visibleCards = document.querySelectorAll('.cctv-card[style*="display: block"]');
                
                if (visibleCards.length > 0) {
                    // Jika ada CCTV yang terlihat, sembunyikan semuanya
                    visibleCards.forEach(card => {
                        card.style.display = 'none';
                        const checkbox = card.querySelector('.toggle-switch input');
                        if (checkbox) {
                            checkbox.checked = false;
                        }
                        
                        // Uncheck sidebar checkbox yang sesuai
                        const cardId = card.id;
                        const sidebarCheckbox = document.getElementById(`checkbox-${cardId}`);
                        if (sidebarCheckbox) {
                            sidebarCheckbox.checked = false;
                        }
                    });
                }
                
                updateActiveCCTVCount();
            });

            // Fungsi untuk pencarian di sidebar
            document.getElementById('sidebarSearchInput').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const dropdownItems = document.querySelectorAll('.dropdown-item');
                
                if (searchTerm.length === 0) {
                    dropdownItems.forEach(item => item.style.display = 'block');
                    return;
                }
                
                dropdownItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        });