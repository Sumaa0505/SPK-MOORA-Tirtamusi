<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK MOORA Tirta Musi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy:       #0a1628;
            --navy-mid:   #102040;
            --blue-deep:  #1a4a8a;
            --blue-core:  #1e6fc8;
            --blue-light: #3b9fd8;
            --cyan:       #45c6e8;
            --gold:       #f0b429;
            --gold-light: #fdd574;
            --white:      #ffffff;
            --off-white:  #e8f4fd;
            --muted:      rgba(255,255,255,0.55);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--navy);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
        }

        /* ── Animated water background ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bg-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%,   #1e4d8c55 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 100% 100%, #0d3d7a44 0%, transparent 60%),
                radial-gradient(ellipse 70% 70% at 0%   80%,  #1a6bb822 0%, transparent 60%),
                linear-gradient(175deg, #0d1f3c 0%, #0a1628 50%, #071020 100%);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.18;
            animation: floatOrb linear infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: var(--blue-core);  top: -120px; left: -100px; animation-duration: 20s; }
        .orb-2 { width: 380px; height: 380px; background: var(--cyan);       bottom: -80px; right: -60px; animation-duration: 25s; animation-delay: -8s; }
        .orb-3 { width: 260px; height: 260px; background: var(--gold);       top: 40%;   right: 15%;  animation-duration: 18s; animation-delay: -4s; opacity: 0.10; }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -40px) scale(1.05); }
            66%       { transform: translate(-20px, 20px) scale(0.97); }
        }

        .waves-svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            opacity: 0.18;
        }

        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: var(--cyan);
            animation: rise linear infinite;
            opacity: 0;
        }
        @keyframes rise {
            0%   { transform: translateY(0) scale(1);   opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.2; }
            100% { transform: translateY(-100vh) scale(0.3); opacity: 0; }
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(30,111,200,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30,111,200,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Card — disesuaikan seperti register ── */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px; /* diperkecil dari 460px → 420px seperti register */
            animation: cardEntrance 0.9s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(40px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }

        .login-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(69,198,232,0.08) inset,
                0 32px 64px rgba(0,0,0,0.45),
                0 8px 24px rgba(30,111,200,0.12);
        }

        .card-accent {
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--cyan), var(--gold), var(--cyan), var(--blue-core));
            background-size: 300% 100%;
            animation: gradientSlide 4s linear infinite;
        }
        @keyframes gradientSlide {
            0%   { background-position: 0%   50%; }
            100% { background-position: 300% 50%; }
        }

        /* Padding dikurangi seperti register */
        .card-inner {
            padding: 28px 32px 32px;
        }

        /* ── Logo section — ukuran lebih kecil seperti register ── */
        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-ring {
            position: relative;
            width: 88px;
            height: 88px;
            margin-bottom: 14px;
        }
        .logo-ring::before,
        .logo-ring::after {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            animation: ringPulse 3s ease-in-out infinite;
        }
        .logo-ring::before {
            border: 2px solid rgba(69,198,232,0.35);
            animation-delay: 0s;
        }
        .logo-ring::after {
            inset: -11px;
            border: 1.5px solid rgba(30,111,200,0.20);
            animation-delay: 0.5s;
        }
        @keyframes ringPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(1.04); }
        }

        .logo-img-wrap {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            overflow: hidden;
            background: radial-gradient(circle, #1a3a6a, #0d1f3c);
            border: 2px solid rgba(255,255,255,0.12);
            box-shadow:
                0 0 24px rgba(30,111,200,0.35),
                0 0 48px rgba(30,111,200,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .logo-img-wrap img {
            width: 82%;
            height: 82%;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }

        .company-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.02em;
            text-align: center;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .company-sub {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.18em;
            text-align: center;
        }

        /* Step dots — seperti register */
        .step-dots {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin: 12px 0 4px;
        }
        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.20);
        }
        .step-dot.active {
            background: var(--cyan);
            width: 20px;
            border-radius: 4px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 16px 0 20px;
        }
        .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.10); }
        .divider-text {
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-weight: 600;
            white-space: nowrap;
        }

        /* ── Flash alerts ── */
        .alert {
            border: none;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 500;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-danger  { background: rgba(220,53,69,0.18);  color: #ff8a97;  border-left: 3px solid #dc3545; }
        .alert-success { background: rgba(25,200,120,0.16); color: #6dffbe;  border-left: 3px solid #19c878; }

        /* ── Form fields ── */
        .field-group { margin-bottom: 16px; }

        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            margin-bottom: 6px;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(69,198,232,0.55);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.25s;
        }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 10px;
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            padding: 11px 14px 11px 40px;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.26); }
        .field-input:focus {
            background: rgba(30,111,200,0.10);
            border-color: rgba(69,198,232,0.50);
            box-shadow: 0 0 0 3px rgba(69,198,232,0.10), 0 4px 12px rgba(30,111,200,0.12);
        }
        .field-wrap:focus-within .field-icon { color: var(--cyan); }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.30);
            cursor: pointer;
            font-size: 0.95rem;
            padding: 4px;
            transition: color 0.2s;
            line-height: 1;
        }
        .pw-toggle:hover { color: var(--cyan); }

        /* ── Login button ── */
        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--white);
            background: linear-gradient(135deg, var(--blue-core) 0%, var(--blue-light) 60%, var(--cyan) 100%);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(30,111,200,0.38);
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(30,111,200,0.50);
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        /* ── Footer note ── */
        .card-footer-note {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .card-footer-note p {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.28);
            letter-spacing: 0.03em;
            line-height: 1.6;
        }
        .card-footer-note .badge-spk {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(240,180,41,0.10);
            border: 1px solid rgba(240,180,41,0.22);
            color: var(--gold-light);
            font-size: 0.60rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 7px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 575.98px) {
            body {
                padding: 14px 12px;
                align-items: flex-start;
            }
            .login-wrapper { max-width: 100%; }
            .card-inner { padding: 22px 18px 26px; }
            .logo-ring, .logo-img-wrap { width: 76px; height: 76px; }
            .company-name { font-size: 0.95rem; }
            .company-sub { font-size: 0.62rem; letter-spacing: 0.12em; }
            .field-input { font-size: 16px; padding: 11px 14px 11px 38px; }
            .btn-login { padding: 12px; font-size: 0.875rem; }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            body { padding: 20px 18px; }
            .card-inner { padding: 26px 28px 30px; }
        }

        @media (min-width: 1200px) {
            .login-wrapper { max-width: 430px; }
        }
    </style>
</head>
<body>

<!-- Background scene -->
<div class="bg-scene">
    <div class="bg-gradient"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-lines"></div>
    <div class="particles" id="particles"></div>
    <svg class="waves-svg" viewBox="0 0 1800 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,160 C200,80 400,240 600,160 C800,80 1000,200 1200,140 C1400,80 1600,200 1800,160 L1800,320 L0,320 Z" fill="#1e6fc8"/>
        <path d="M0,200 C300,120 500,260 800,190 C1000,130 1200,240 1500,180 C1650,150 1750,200 1800,200 L1800,320 L0,320 Z" fill="#45c6e8" opacity="0.6"/>
        <path d="M0,240 C250,180 500,300 750,240 C1000,180 1250,300 1500,240 C1650,200 1750,260 1800,240 L1800,320 L0,320 Z" fill="#3b9fd8" opacity="0.35"/>
    </svg>
</div>

<!-- Login card -->
<div class="login-wrapper">
    <div class="login-card">
        <div class="card-accent"></div>

        <div class="card-inner">

            <!-- Logo & title -->
            <div class="logo-section">
                <div class="logo-ring">
                    <div class="logo-img-wrap">
                        <img src="<?= base_url('assets/img/Logo Tirta Musi.png') ?>" alt="Logo Tirta Musi">
                    </div>
                </div>
                <div class="company-name">Perumda Tirta Musi Palembang</div>
                <div class="company-sub">Sistem Pendukung Keputusan</div>

                <!-- Step dots seperti register -->
                <div class="step-dots">
                    <div class="step-dot active"></div>
                    <div class="step-dot"></div>
                    <div class="step-dot"></div>
                </div>
            </div>

            <!-- Divider -->
            <div class="divider">
                <div class="divider-line"></div>
                <div class="divider-text">Masuk ke Sistem</div>
                <div class="divider-line"></div>
            </div>

            <!-- Flash messages -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= base_url('login') ?>" method="post" autocomplete="off">
                <?= csrf_field() ?>

                <!-- USERNAME -->
                <div class="field-group">
                    <label class="field-label">Username</label>
                    <div class="field-wrap">
                        <i class="bi bi-person field-icon"></i>
                        <input type="text" name="username" class="field-input" placeholder="Masukkan username" required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" id="password" name="password" class="field-input" placeholder="Masukkan password" required>
                        <button type="button" class="pw-toggle" id="pwToggle">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>

                <div style="text-align:center; margin-top:10px;">
                    <p style="color: rgba(255,255,255,0.45); font-size:0.72rem;">
                        Belum punya akun?
                        <a href="<?= base_url('register') ?>" style="color:#45c6e8; font-weight:600;">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </form>

            <!-- Footer note -->
            <div class="card-footer-note">
                <div class="badge-spk">
                    <i class="bi bi-grid-3x3-gap-fill" style="font-size:0.6rem;"></i>
                    Metode MOORA
                </div>
                <p>Implementasi Metode MOORA dalam<br>Penentuan Prioritas Pengadaan Peralatan Operasional</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Password toggle
    const pwToggle = document.getElementById('pwToggle');
    const pwIcon   = document.getElementById('pwIcon');
    const pwInput  = document.getElementById('password');

    pwToggle.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        pwIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // Generate floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 22; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size  = Math.random() * 4 + 2;
        const left  = Math.random() * 100;
        const delay = Math.random() * 18;
        const dur   = Math.random() * 12 + 10;
        p.style.cssText = `width:${size}px;height:${size}px;left:${left}%;bottom:-${size}px;animation-duration:${dur}s;animation-delay:${delay}s;opacity:0;`;
        container.appendChild(p);
    }
</script>

</body>
</html>