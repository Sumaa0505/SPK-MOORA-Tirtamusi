<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SPK MOORA PDAM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
            padding: 2rem 1rem;
        }

        /* Wave background */
        .wave-bg {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 220px;
            opacity: 0.1;
            pointer-events: none;
            z-index: 0;
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(56, 189, 248, 0.3);
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.5; }
            100% { transform: translateY(-100px) scale(1.5); opacity: 0; }
        }

        /* Main card */
        .register-container {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem 2rem 1.75rem;
            position: relative;
            z-index: 2;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(56, 189, 248, 0.05) inset;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .logo-wrap {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid rgba(56, 189, 248, 0.45);
            background: linear-gradient(135deg, rgba(14, 78, 122, 0.8), rgba(15, 23, 42, 0.9));
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.2);
            overflow: hidden;
            transition: box-shadow 0.3s;
        }

        .logo-ring:hover {
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.4);
        }

        .logo-ring img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 50%;
        }

        .reg-title {
            font-size: 18px;
            font-weight: 600;
            color: #f0f9ff;
            margin: 0.6rem 0 2px;
            text-align: center;
        }

        .reg-subtitle {
            font-size: 10px;
            color: #7dd3fc;
            letter-spacing: 0.1em;
            text-align: center;
            margin-bottom: 0;
        }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin: 1.25rem 0 1.5rem;
        }

        .step-dot {
            height: 7px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.18);
            transition: all 0.35s ease;
            cursor: default;
        }

        .step-dot.active {
            width: 28px;
            background: #38bdf8;
        }

        .step-dot.done {
            width: 7px;
            background: rgba(52, 211, 153, 0.75);
        }

        .step-dot.inactive {
            width: 7px;
        }

        /* Divider */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }

        .section-divider .line {
            flex: 1;
            height: 0.5px;
            background: rgba(255, 255, 255, 0.15);
        }

        .section-divider .label {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 0.1em;
        }

        /* Alert */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: rgba(248, 113, 113, 0.13);
            border: 0.5px solid rgba(248, 113, 113, 0.4);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.12);
            border: 0.5px solid rgba(52, 211, 153, 0.4);
            color: #6ee7b7;
        }

        .alert-box i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Form groups */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 0.9rem;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 5px;
            letter-spacing: 0.03em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px 10px 36px;
            background: rgba(255, 255, 255, 0.07);
            border: 0.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #f0f9ff;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.28);
        }

        .form-input:focus {
            border-color: rgba(56, 189, 248, 0.65);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12);
        }

        .form-input.is-error {
            border-color: rgba(248, 113, 113, 0.6);
        }

        .form-input.is-success {
            border-color: rgba(52, 211, 153, 0.55);
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.38);
            font-size: 16px;
            cursor: pointer;
            padding: 2px;
            transition: color 0.2s;
            line-height: 1;
        }

        .pw-toggle:hover {
            color: rgba(56, 189, 248, 0.8);
        }

        .pw-input {
            padding-right: 38px !important;
        }

        /* Password strength */
        .pw-strength-bar-wrap {
            height: 3px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .pw-strength-bar {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: width 0.35s ease, background-color 0.35s ease;
        }

        .pw-strength-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.38);
            margin-top: 3px;
            transition: color 0.35s;
        }

        /* Error messages */
        .err-msg {
            font-size: 10px;
            color: #f87171;
            margin-top: 3px;
            display: none;
        }

        .err-msg.visible {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        /* Role cards */
        .role-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 5px;
        }

        .role-card {
            padding: 12px 8px 10px;
            border-radius: 10px;
            border: 0.5px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.05);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .role-card:hover {
            background: rgba(56, 189, 248, 0.1);
            border-color: rgba(56, 189, 248, 0.4);
        }

        .role-card.selected {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.7);
            box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.2) inset;
        }

        .role-card i {
            font-size: 22px;
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.4);
            transition: color 0.2s;
        }

        .role-card.selected i {
            color: #38bdf8;
        }

        .role-card span {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.55);
            transition: color 0.2s;
            line-height: 1.3;
        }

        .role-card.selected span {
            color: #7dd3fc;
        }

        /* Summary */
        .summary-card {
            background: rgba(255, 255, 255, 0.05);
            border: 0.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 0.5px solid rgba(255, 255, 255, 0.07);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.42);
        }

        .summary-value {
            font-size: 12px;
            color: #e0f2fe;
            font-weight: 500;
            text-align: right;
            max-width: 60%;
            word-break: break-all;
        }

        /* Checkbox */
        .checkbox-wrap {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            margin-top: 1px;
            accent-color: #38bdf8;
            cursor: pointer;
        }

        .checkbox-wrap span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.58);
            line-height: 1.5;
        }

        /* Step panels */
        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        /* Navigation buttons */
        .step-nav {
            display: flex;
            gap: 10px;
            margin-top: 0.6rem;
        }

        .btn-back {
            flex: 1;
            padding: 11px 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 0.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-next {
            flex: 2;
            padding: 11px 10px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
            overflow: hidden;
        }

        .btn-next:hover { opacity: 0.88; }
        .btn-next:active { transform: scale(0.98); }
        .btn-next:disabled { opacity: 0.48; cursor: not-allowed; }

        .btn-full {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: opacity 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-full:hover { opacity: 0.88; }
        .btn-full:active { transform: scale(0.98); }
        .btn-full:disabled { opacity: 0.48; cursor: not-allowed; }

        /* Ripple */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.22);
            transform: scale(0);
            animation: rippleAnim 0.55s linear;
            pointer-events: none;
        }

        @keyframes rippleAnim {
            to { transform: scale(5); opacity: 0; }
        }

        /* Loading dots */
        .loading-dots {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .loading-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
            display: inline-block;
            animation: dotBounce 1.2s infinite ease-in-out;
        }

        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes dotBounce {
            0%, 80%, 100% { transform: scale(0); }
            40%           { transform: scale(1); }
        }

        /* Bottom link */
        .login-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        .login-link a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Footer badge */
        .footer-badge {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 0.5px solid rgba(255, 255, 255, 0.08);
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border: 0.5px solid rgba(56, 189, 248, 0.3);
            border-radius: 20px;
            font-size: 9px;
            color: rgba(56, 189, 248, 0.7);
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .footer-badge p {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.28);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem 1.25rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .role-cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>

<!-- Floating particles -->
<div class="particles" id="particles"></div>

<!-- Wave SVG background -->
<svg class="wave-bg" viewBox="0 0 1440 220" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0,60 C360,140 1080,0 1440,90 L1440,220 L0,220 Z" fill="#38bdf8"/>
    <path d="M0,110 C480,40 960,170 1440,70 L1440,220 L0,220 Z" fill="#0ea5e9" opacity="0.55"/>
</svg>

<!-- Register card -->
<div class="register-container">

    <!-- Logo & Header -->
    <div class="logo-wrap">
        <div class="logo-ring">
            <img src="<?= base_url('assets/img/Logo Tirta Musi.png') ?>" alt="Logo Tirta Musi">
        </div>
        <div class="reg-title">Perumda Tirta Musi Palembang</div>
        <div class="reg-subtitle">SISTEM PENDUKUNG KEPUTUSAN</div>
    </div>

    <!-- Step dots -->
    <div class="step-indicator" id="step-indicator">
        <div class="step-dot active" id="dot-0"></div>
        <div class="step-dot inactive" id="dot-1"></div>
        <div class="step-dot inactive" id="dot-2"></div>
    </div>

    <!-- Alert area -->
    <div id="alert-area"></div>

    <!-- PHP Flash error -->
    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert-box alert-error">
        <i class="ti ti-alert-circle"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <!-- ======================== -->
    <!-- STEP 1: Identitas        -->
    <!-- ======================== -->
    <div class="step-panel active" id="step-0">

        <div class="section-divider">
            <div class="line"></div>
            <div class="label">IDENTITAS PENGGUNA</div>
            <div class="line"></div>
        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <div class="input-wrap">
                <i class="ti ti-user input-icon"></i>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                       class="form-input"
                       placeholder="Masukkan nama lengkap"
                       autocomplete="name">
            </div>
            <div class="err-msg" id="err-nama">Nama lengkap wajib diisi</div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="ti ti-at input-icon"></i>
                    <input type="text" id="username" name="username"
                           class="form-input"
                           placeholder="Username unik"
                           autocomplete="username">
                </div>
                <div class="err-msg" id="err-username">Username wajib diisi</div>
            </div>

            <div class="form-group">
                <label>Email <span style="color:rgba(255,255,255,0.3);font-size:10px">(opsional)</span></label>
                <div class="input-wrap">
                    <i class="ti ti-mail input-icon"></i>
                    <input type="email" id="email" name="email"
                           class="form-input"
                           placeholder="Alamat email"
                           autocomplete="email">
                </div>
                <div class="err-msg" id="err-email">Format email tidak valid</div>
            </div>
        </div>

        <div class="step-nav">
            <button class="btn-next" style="flex:1" onclick="goNext(0)">
                Selanjutnya <i class="ti ti-arrow-right"></i>
            </button>
        </div>

    </div>

    <!-- ======================== -->
    <!-- STEP 2: Password & Role  -->
    <!-- ======================== -->
    <div class="step-panel" id="step-1">

        <div class="section-divider">
            <div class="line"></div>
            <div class="label">KEAMANAN & UNIT KERJA</div>
            <div class="line"></div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <i class="ti ti-lock input-icon"></i>
                <input type="password" id="password" name="password"
                       class="form-input pw-input"
                       placeholder="Buat password yang kuat"
                       autocomplete="new-password">
                <button type="button" class="pw-toggle" onclick="togglePw('password', 'icon-pw1')" aria-label="Tampilkan password">
                    <i class="ti ti-eye" id="icon-pw1"></i>
                </button>
            </div>
            <div class="pw-strength-bar-wrap">
                <div class="pw-strength-bar" id="pw-bar"></div>
            </div>
            <div class="pw-strength-label" id="pw-label">Masukkan password</div>
            <div class="err-msg" id="err-password">Password minimal 6 karakter</div>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <div class="input-wrap">
                <i class="ti ti-lock-check input-icon"></i>
                <input type="password" id="password_confirm"
                       class="form-input pw-input"
                       placeholder="Ulangi password"
                       autocomplete="new-password">
                <button type="button" class="pw-toggle" onclick="togglePw('password_confirm', 'icon-pw2')" aria-label="Tampilkan konfirmasi password">
                    <i class="ti ti-eye" id="icon-pw2"></i>
                </button>
            </div>
            <div class="err-msg" id="err-confirm">Password tidak cocok</div>
        </div>

        <div class="form-group">
            <label>Unit / Role Pengajuan</label>
            <div class="role-cards-grid">
                <div class="role-card selected" data-value="sub_unit" onclick="selectRole(this)">
                    <i class="ti ti-building-community"></i>
                    <span>Sub Unit</span>
                </div>
                <div class="role-card" data-value="gudang" onclick="selectRole(this)">
                    <i class="ti ti-package"></i>
                    <span>Gudang</span>
                </div>
                <div class="role-card" data-value="manajer_umum" onclick="selectRole(this)">
                    <i class="ti ti-user-star"></i>
                    <span>Manajer Umum</span>
                </div>
                <div class="role-card" data-value="direktur" onclick="selectRole(this)">
                    <i class="ti ti-shield-check"></i>
                    <span>Direktur</span>
                </div>
                <div class="role-card" data-value="pengadaan" onclick="selectRole(this)">
                    <i class="ti ti-truck-delivery"></i>
                    <span>Pengadaan</span>
                </div>
            </div>
            <input type="hidden" id="role" name="role" value="sub_unit">
            <div class="err-msg" id="err-role">Pilih unit / role terlebih dahulu</div>
        </div>

        <div class="step-nav">
            <button class="btn-back" onclick="goPrev(1)">
                <i class="ti ti-arrow-left"></i> Kembali
            </button>
            <button class="btn-next" onclick="goNext(1)">
                Selanjutnya <i class="ti ti-arrow-right"></i>
            </button>
        </div>

    </div>

    <!-- ======================== -->
    <!-- STEP 3: Konfirmasi       -->
    <!-- ======================== -->
    <div class="step-panel" id="step-2">

        <div class="section-divider">
            <div class="line"></div>
            <div class="label">KONFIRMASI DATA</div>
            <div class="line"></div>
        </div>

        <div class="summary-card" id="summary-data"></div>

        <div class="form-group" style="margin-bottom:0.6rem">
            <label class="checkbox-wrap" for="agree">
                <input type="checkbox" id="agree">
                <span>Saya menyatakan bahwa data yang diisikan adalah benar dan dapat dipertanggungjawabkan</span>
            </label>
            <div class="err-msg" id="err-agree">Anda harus mencentang pernyataan ini</div>
        </div>

        <!-- FORM SUBMIT ke CodeIgniter -->
        <form id="register-form" method="post" action="<?= base_url('register/store') ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <input type="hidden" name="nama_lengkap" id="f-nama">
            <input type="hidden" name="username"     id="f-username">
            <input type="hidden" name="email"        id="f-email">
            <input type="hidden" name="password"     id="f-password">
            <input type="hidden" name="role"         id="f-role">
        </form>

        <div class="step-nav">
            <button class="btn-back" onclick="goPrev(2)">
                <i class="ti ti-arrow-left"></i> Ubah Data
            </button>
            <button class="btn-next" id="submit-btn" onclick="submitRegister()">
                <i class="ti ti-check"></i> Daftar Sekarang
            </button>
        </div>

    </div>

    <!-- Login link -->
    <div class="login-link">
        Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk di sini</a>
    </div>

    <!-- Footer badge -->
    <div class="footer-badge">
        <div class="badge-pill">
            <i class="ti ti-cpu" style="font-size:11px"></i>
            METODE MOORA
        </div>
        <p>Implementasi Metode MOORA dalam Penentuan Prioritas Pengadaan Peralatan Operasional</p>
    </div>

</div><!-- end .register-container -->

<script>
(function() {
    /* ─── Particles ─── */
    const container = document.getElementById('particles');
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.cssText = [
            'left:' + Math.random() * 100 + '%',
            'width:' + (Math.random() * 4 + 2) + 'px',
            'height:' + (Math.random() * 4 + 2) + 'px',
            'animation-duration:' + (Math.random() * 12 + 8) + 's',
            'animation-delay:' + (Math.random() * 8) + 's',
            'opacity:' + (Math.random() * 0.5 + 0.1)
        ].join(';');
        container.appendChild(p);
    }

    /* ─── State ─── */
    let currentStep = 0;

    /* ─── Dot update ─── */
    function updateDots(step) {
        for (let i = 0; i < 3; i++) {
            const d = document.getElementById('dot-' + i);
            d.className = 'step-dot ' + (i < step ? 'done' : i === step ? 'active' : 'inactive');
        }
    }

    /* ─── Show step ─── */
    function showStep(step) {
        document.querySelectorAll('.step-panel').forEach(function(p, i) {
            p.classList.toggle('active', i === step);
        });
        updateDots(step);
        currentStep = step;
    }

    /* ─── Field helpers ─── */
    function val(id) {
        return document.getElementById(id).value.trim();
    }

    function setError(errId, inputId, show) {
        const errEl = document.getElementById(errId);
        if (errEl) errEl.classList.toggle('visible', show);
        if (inputId) {
            const inp = document.getElementById(inputId);
            if (!inp) return;
            inp.classList.toggle('is-error', show);
            if (!show && inp.value.trim()) inp.classList.add('is-success');
            else if (show) inp.classList.remove('is-success');
        }
    }

    /* ─── Validate step 0 ─── */
    function validateStep0() {
        let ok = true;
        const nama = val('nama_lengkap');
        const user = val('username');
        const email = val('email');
        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        setError('err-nama', 'nama_lengkap', !nama);
        if (!nama) ok = false;

        setError('err-username', 'username', !user);
        if (!user) ok = false;

        const emailBad = email !== '' && !emailRx.test(email);
        setError('err-email', 'email', emailBad);
        if (emailBad) ok = false;

        return ok;
    }

    /* ─── Validate step 1 ─── */
    function validateStep1() {
        let ok = true;
        const pw  = document.getElementById('password').value;
        const cf  = document.getElementById('password_confirm').value;
        const role = val('role');

        setError('err-password', 'password', pw.length < 6);
        if (pw.length < 6) ok = false;

        setError('err-confirm', 'password_confirm', pw !== cf || cf === '');
        if (pw !== cf || cf === '') ok = false;

        setError('err-role', null, !role);
        if (!role) ok = false;

        return ok;
    }

    /* ─── Navigation ─── */
    window.goNext = function(from) {
        if (from === 0 && !validateStep0()) return;
        if (from === 1 && !validateStep1()) return;
        if (from === 1) buildSummary();
        showStep(from + 1);
    };

    window.goPrev = function(from) {
        showStep(from - 1);
    };

    /* ─── Build summary ─── */
    function buildSummary() {
        const roleMap = {
            sub_unit      : 'Sub Unit',
            gudang        : 'Gudang',
            manajer_umum  : 'Manajer Umum',
            direktur      : 'Direktur',
            pengadaan     : 'Bagian Pengadaan'
        };
        const rows = [
            { label: 'Nama Lengkap', value: val('nama_lengkap') },
            { label: 'Username',     value: val('username') },
            { label: 'Email',        value: val('email') || '-' },
            { label: 'Password',     value: '\u25cf'.repeat(document.getElementById('password').value.length) },
            { label: 'Unit / Role',  value: roleMap[val('role')] || '-' }
        ];
        document.getElementById('summary-data').innerHTML = rows.map(function(r) {
            return '<div class="summary-row">'
                 + '<span class="summary-label">' + r.label + '</span>'
                 + '<span class="summary-value">' + r.value + '</span>'
                 + '</div>';
        }).join('');
    }

    /* ─── Role selection ─── */
    window.selectRole = function(el) {
        document.querySelectorAll('.role-card').forEach(function(c) { c.classList.remove('selected'); });
        el.classList.add('selected');
        document.getElementById('role').value = el.dataset.value;
        setError('err-role', null, false);
    };

    /* ─── Toggle password ─── */
    window.togglePw = function(inputId, iconId) {
        const inp = document.getElementById(inputId);
        const ico = document.getElementById(iconId);
        if (inp.type === 'password') {
            inp.type = 'text';
            ico.className = 'ti ti-eye-off';
        } else {
            inp.type = 'password';
            ico.className = 'ti ti-eye';
        }
    };

    /* ─── Password strength ─── */
    document.getElementById('password').addEventListener('input', function() {
        const v = this.value;
        const bar = document.getElementById('pw-bar');
        const lbl = document.getElementById('pw-label');
        let score = 0;
        if (v.length >= 6)  score++;
        if (v.length >= 10) score++;
        if (/[A-Z]/.test(v))       score++;
        if (/[0-9]/.test(v))       score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;

        const levels = [
            { pct: '0%',   color: 'transparent',  label: 'Masukkan password' },
            { pct: '20%',  color: '#f87171',       label: 'Sangat lemah' },
            { pct: '40%',  color: '#fb923c',       label: 'Lemah' },
            { pct: '62%',  color: '#facc15',       label: 'Sedang' },
            { pct: '82%',  color: '#34d399',       label: 'Kuat' },
            { pct: '100%', color: '#06b6d4',       label: 'Sangat kuat' }
        ];
        const lv = levels[Math.min(score, 5)];
        bar.style.width = lv.pct;
        bar.style.backgroundColor = lv.color;
        lbl.textContent = lv.label;
        lbl.style.color = lv.color === 'transparent' ? 'rgba(255,255,255,0.38)' : lv.color;

        if (v.length >= 6) setError('err-password', 'password', false);
    });

    document.getElementById('password_confirm').addEventListener('input', function() {
        const pw = document.getElementById('password').value;
        if (this.value && pw === this.value) setError('err-confirm', 'password_confirm', false);
    });

    /* ─── Live validation on typing ─── */
    ['nama_lengkap', 'username'].forEach(function(id) {
        document.getElementById(id).addEventListener('input', function() {
            if (this.value.trim()) setError('err-' + (id === 'nama_lengkap' ? 'nama' : id), id, false);
        });
    });

    document.getElementById('email').addEventListener('input', function() {
        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!this.value || emailRx.test(this.value)) setError('err-email', 'email', false);
    });

    /* ─── Show alert ─── */
    function showAlert(msg, type) {
        const area = document.getElementById('alert-area');
        const icon = type === 'success' ? 'circle-check' : 'alert-circle';
        area.innerHTML = '<div class="alert-box alert-' + type + '">'
                       + '<i class="ti ti-' + icon + '"></i><span>' + msg + '</span></div>';
        if (type === 'success') {
            setTimeout(function() { area.innerHTML = ''; }, 5000);
        }
    }

    /* ─── Ripple effect ─── */
    function addRipple(btn, e) {
        const rect = btn.getBoundingClientRect();
        const r = document.createElement('span');
        r.className = 'ripple';
        const size = Math.max(rect.width, rect.height);
        r.style.width  = r.style.height = size + 'px';
        r.style.left   = (e.clientX - rect.left  - size / 2) + 'px';
        r.style.top    = (e.clientY - rect.top   - size / 2) + 'px';
        btn.appendChild(r);
        setTimeout(function() { r.remove(); }, 600);
    }

    document.querySelectorAll('.btn-next, .btn-full').forEach(function(btn) {
        btn.addEventListener('click', function(e) { addRipple(btn, e); });
    });

    /* ─── Submit ─── */
    window.submitRegister = function() {
        const agree = document.getElementById('agree');
        if (!agree.checked) {
            setError('err-agree', null, true);
            document.getElementById('err-agree').classList.add('visible');
            return;
        }
        document.getElementById('err-agree').classList.remove('visible');

        /* Populate hidden form fields */
        document.getElementById('f-nama').value     = val('nama_lengkap');
        document.getElementById('f-username').value  = val('username');
        document.getElementById('f-email').value     = val('email');
        document.getElementById('f-password').value  = document.getElementById('password').value;
        document.getElementById('f-role').value      = val('role');

        /* Loading state */
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<div class="loading-dots"><span></span><span></span><span></span></div>';

        /* Submit form to CodeIgniter */
        document.getElementById('register-form').submit();
    };

})();
</script>

</body>
</html>