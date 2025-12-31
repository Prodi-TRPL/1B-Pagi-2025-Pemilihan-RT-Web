<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikPilih - Pemilihan RT Digital Terpercaya</title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
    <link rel="stylesheet" href="assets/css/landingpage/style.css">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="nav">
                <img src="/assets/img/logo.png" class="logo">
                <div class="nav-links-container">
                    <div class="nav-links" id="navLinks">
                        <a href="#beranda">Beranda</a>
                        <a href="#tentang">Tentang</a>
                        <a href="#cara">Cara</a>
                        <button class="login-btn" onclick="aksessebagai()">Login</button>
                    </div>
                </div>
                <button class="mobile-menu-btn" onclick="toggleMenu()">☰</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Pemilihan RT yang <span class="highlight">Aman, Cepat & Transparan</span></h1>
                <p>Platform E-Voting terpercaya untuk memilih ketua RT di lingkungan Anda. memberikan suara kini lebih
                    mudah, aman dan dapat dilakukan dimana saja.</p>
                <button class="cta-btn" onclick="aksessebagai()">Mulai Voting Sekarang</button>
            </div>
            <div class="hero-image">
                <img src="assets/img/goid33 1.png" alt="Pemilihan RT">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features scroll-reveal" id="tentang">
        <div class="section-header">
            <span class="section-badge">Fitur Unggulan</span>
            <h2>Kenapa memilih <span class="highlight">KlikPilih</span>?</h2>
            <p>Platform kami dirancang dengan teknologi terkini agar proses pemilihan dapat aman, cepat dan terpercaya
            </p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Aman & Terenkripsi</h3>
                <p>Data warga sudah terjamin aman dengan token hanya milik pribadi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Mudah digunakan</h3>
                <p>Rancangan antarmuka yang ramah semua kalangan usia</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✓</div>
                <h3>Transparan</h3>
                <p>Semua suara tercatat dengan baik dan dapat diverifikasi untuk transparansi penuh</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Efisien</h3>
                <p>Hemat waktu dengan proses voting digital tanpa antrian panjang</p>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps scroll-reveal" id="cara">
        <div class="section-header">
            <span class="section-badge">Panduan Mudah</span>
            <h2>Cara <span class="highlight">Menggunakan</span></h2>
            <p>Proses voting sederhana yang dapat dilakukan hanya dengan 4 langkah</p>
        </div>
        <div class="steps-grid">
            <div class="step-card" style="border-color:hover #000000ff;">
                <div class="step-number">1</div>
                <h3>Pilih Akses</h3>
                <p>Pilih opsi akses sebagai Warga</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Login dengan Token Pribadi</h3>
                <p>Masukkan Nama dan Token yang sudah disebar oleh panitia setiap RT</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Memilih calon kandidat</h3>
                <p>Memilih calon kandidat RT sesuai preferensi masing masing</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Melihat Hasil</h3>
                <p>Setelah proses perhitungan suara telah selesai, warga dapat melihat dan mengunduh hasil akhir suara
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section scroll-reveal">
        <div class="cta-content">
            <h2>Siap memberikan <span class="highlight">Suara Anda?</span></h2>
            <p>Partisipasi Anda sangat berarti untuk membangun lingkungan yang lebih baik. Mulai voting sekarang bersama
                kami di website KlikPilih!</p>
            <button class="cta-btn" onclick="aksessebagai()">Mulai Voting</button>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-brand">
                <h3>KlikPilih</h3>
                <p>Pemilihan Digital Terpercaya</p>
                <p>Platform E-Voting terpercaya untuk memilih ketua RT di lingkungan Anda.</p>
            </div>
            <div class="footer-links">
                <h4>Fitur</h4>
                <ul>
                    <li><a href="#">Akses Sebagai</a></li>
                    <li><a href="#">Login Aman</a></li>
                    <li><a href="#">Voting</a></li>
                    <li><a href="#">Lihat Hasil Akhir</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Cara Voting</a></li>
                    <li><a href="#">Kontak Support</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Privasi dan Keamanan</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 KlikPilih. Hasil Kerjaan PBL Kelompok 3.</p>
        </div>
    </footer>

    <script>
        function aksessebagai() {
            window.location.href = "aksessebagai";
        }
        // Mobile Menu Toggle
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Scroll Reveal Animation
        function revealOnScroll() {
            const reveals = document.querySelectorAll('.scroll-reveal');

            reveals.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('active');
                }
            });
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', () => {
            revealOnScroll();
        });

        // Initial reveal check
        window.addEventListener('load', revealOnScroll);

        // Button hover effects
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
            });

            button.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
        // Tunggu hingga seluruh konten HTML dimuat
        function reorganizeNav() {
            const nav = document.querySelector('.nav');
            const navLinksContainer = document.querySelector('.nav-links-container');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');

            if (window.innerWidth <= 768) {
                // Mobile: pindahkan nav-links-container setelah mobile-menu-btn
                nav.appendChild(mobileMenuBtn);
                nav.parentElement.insertBefore(navLinksContainer, nav.nextSibling);
            } else {
                // Desktop: kembalikan ke posisi semula
                nav.appendChild(navLinksContainer);
                nav.appendChild(mobileMenuBtn);
            }
        }

        // Jalankan saat load dan resize
        window.addEventListener('load', reorganizeNav);
        window.addEventListener('resize', reorganizeNav);
    </script>
</body>

</html>

