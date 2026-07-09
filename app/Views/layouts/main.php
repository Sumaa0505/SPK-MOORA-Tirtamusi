<?php
$__tmRole = session()->get('role') ?? null;
$__tmUserId = session()->get('id_user') ?? session()->get('id') ?? null;
$__tmUnreadNotif = 0;
if (!empty($__tmRole) || !empty($__tmUserId)) {
    try {
        $__tmUnreadNotif = (new \App\Models\NotificationModel())->countUnreadFor($__tmUserId ? (int) $__tmUserId : null, $__tmRole ? (string) $__tmRole : null);
    } catch (\Throwable $e) {
        $__tmUnreadNotif = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'SPK MOORA') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --font-main: "Segoe UI", Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            margin: 0;
            min-height: 100vh;
            font-size: 14px;
            transition: background .2s ease, color .2s ease;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 20px 26px 40px;
            transition: background .2s ease, color .2s ease;
        }

        .topbar {
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
        }

        .topbar-title {
            font-weight: 800;
            margin: 0;
            font-size: 15px;
        }

        .topbar-subtitle {
            font-size: 12px;
            margin: 3px 0 0;
        }

        .page-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .page-subtitle {
            margin-bottom: 0;
            font-size: 14px;
        }

        .card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
        }

        .card-body {
            padding: 18px;
        }

        .stat-card h3 {
            font-size: 26px;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .stat-card p {
            margin-bottom: 6px;
        }

        .btn-theme-toggle {
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .table {
            margin-bottom: 0;
            font-size: 14px;
        }

        .table th {
            font-weight: 800;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-weight: 800;
        }

        a.btn,
        button.btn {
            font-weight: 700;
        }

        /* ================= LIGHT MODE ================= */
        body.theme-light {
            background: #eef4fb;
            color: #0f172a;
        }

        body.theme-light .main-content {
            background: #eef4fb;
            color: #0f172a;
        }

        body.theme-light .topbar,
        body.theme-light .card {
            background: #ffffff;
            color: #0f172a;
        }

        body.theme-light .topbar-subtitle,
        body.theme-light .text-muted,
        body.theme-light .page-subtitle {
            color: #64748b !important;
        }

        body.theme-light .table {
            color: #0f172a;
            border-color: #d9e2ec;
        }

        body.theme-light .table th {
            background: #f8fafc;
            color: #0f172a;
            border-color: #d9e2ec;
        }

        body.theme-light .table td {
            background: #ffffff;
            color: #0f172a;
            border-color: #d9e2ec;
        }

        body.theme-light .btn-theme-toggle {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, .15);
        }

        body.theme-light .btn-theme-toggle:hover {
            background: #f1f5f9;
        }

        /* ================= DARK MODE ================= */
        body.theme-dark {
            background: #0f172a;
            color: #f8fafc;
        }

        body.theme-dark .main-content {
            background: #0f172a;
            color: #f8fafc;
        }

        body.theme-dark .topbar,
        body.theme-dark .card {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .28);
        }

        body.theme-dark h1,
        body.theme-dark h2,
        body.theme-dark h3,
        body.theme-dark h4,
        body.theme-dark h5,
        body.theme-dark h6,
        body.theme-dark p,
        body.theme-dark span,
        body.theme-dark label,
        body.theme-dark strong,
        body.theme-dark small {
            color: inherit;
        }

        body.theme-dark .text-muted,
        body.theme-dark .topbar-subtitle,
        body.theme-dark .page-subtitle {
            color: #cbd5e1 !important;
        }

        body.theme-dark .table {
            color: #f8fafc;
            border-color: rgba(255, 255, 255, .18);
        }

        body.theme-dark .table th {
            background: #334155 !important;
            color: #f8fafc !important;
            border-color: rgba(255, 255, 255, .18);
        }

        body.theme-dark .table td {
            background: #ffffff !important;
            color: #020617 !important;
            border-color: #d1d5db;
        }

        body.theme-dark .form-control,
        body.theme-dark .form-select,
        body.theme-dark textarea {
            background: #0f172a;
            color: #f8fafc;
            border-color: rgba(255, 255, 255, .18);
        }

        body.theme-dark .form-control::placeholder,
        body.theme-dark textarea::placeholder {
            color: #94a3b8;
        }

        body.theme-dark .btn-theme-toggle {
            background: #334155;
            color: #ffffff;
            border-color: rgba(255, 255, 255, .18);
        }

        body.theme-dark .btn-theme-toggle:hover {
            background: #475569;
        }

        body.theme-dark .alert {
            border: 1px solid rgba(255, 255, 255, .12);
        }

        /* ================= FIX SIDEBAR GAP ================= */
        .sidebar-admin {
            z-index: 1000;
        }


        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar-notification {
            position: relative;
            border-radius: 999px;
            padding: 8px 13px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            border: 1px solid rgba(15, 23, 42, .14);
            background: #ffffff;
            color: #0f172a;
        }

        .topbar-notification:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .notif-count-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: #dc2626;
            color: #ffffff;
            font-size: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
        }

        body.theme-dark .topbar-notification {
            background: #334155;
            color: #ffffff;
            border-color: rgba(255, 255, 255, .18);
        }

        body.theme-dark .topbar-notification:hover {
            background: #475569;
            color: #ffffff;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title {
                font-size: 24px;
            }
        }

 :root {
    --tm-bg: #f4f7fb;
    --tm-card: #ffffff;
    --tm-text: #0f172a;
    --tm-muted: #64748b;
    --tm-primary: #0d6efd;
    --tm-border: #e5e7eb;
}

body {
    background: var(--tm-bg) !important;
    color: var(--tm-text) !important;
}

    :root {
        --tm-bg: #0f172a;
        --tm-content-bg: #f4f7fb;
        --tm-card: #ffffff;
        --tm-text: #0f172a;
        --tm-muted: #64748b;
        --tm-border: #e5e7eb;
        --tm-primary: #0d6efd;
    }

    body {
        background: var(--tm-bg) !important;
        color: var(--tm-text) !important;
    }

    .main-content,
    .content,
    .container-fluid {
        color: var(--tm-text) !important;
    }

    .tm-page-header {
        background: linear-gradient(135deg, #15233b, #1e3a8a);
        color: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .18);
    }

    .tm-page-header h1,
    .tm-page-header h2,
    .tm-page-header h3,
    .tm-page-header p {
        color: #ffffff !important;
    }

    .tm-card,
    .card {
        background: #ffffff !important;
        color: #0f172a !important;
        border-radius: 16px !important;
    }

    .card-body,
    .card-body h1,
    .card-body h2,
    .card-body h3,
    .card-body h4,
    .card-body h5,
    .card-body h6,
    .card-body p,
    .card-body strong,
    .form-label {
        color: #0f172a !important;
    }

    .text-muted {
        color: #64748b !important;
    }

    .tm-kpi-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px;
        min-height: 110px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, .08);
        border: 1px solid rgba(226, 232, 240, .95);
    }

    .tm-kpi-card p {
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 700;
    }

    .tm-kpi-card h3 {
        margin-bottom: 2px;
        font-weight: 800;
        font-size: 28px;
    }

    .tm-kpi-card small {
        font-size: 12px;
    }

    .tm-kpi-blue {
        background: #e0f2fe;
        color: #075985;
    }

    .tm-kpi-green {
        background: #dcfce7;
        color: #166534;
    }

    .tm-kpi-yellow {
        background: #fef9c3;
        color: #92400e;
    }

    .tm-kpi-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .tm-kpi-blue p,
    .tm-kpi-blue h3,
    .tm-kpi-blue small,
    .tm-kpi-green p,
    .tm-kpi-green h3,
    .tm-kpi-green small,
    .tm-kpi-yellow p,
    .tm-kpi-yellow h3,
    .tm-kpi-yellow small,
    .tm-kpi-red p,
    .tm-kpi-red h3,
    .tm-kpi-red small {
        color: inherit !important;
    }

    .tm-table,
    .table {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #e5e7eb !important;
    }

    .tm-table thead th,
    .table thead th {
        background: #334155 !important;
        color: #ffffff !important;
        border-color: #334155 !important;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        white-space: nowrap;
    }

    .tm-table tbody td,
    .table tbody td {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #e5e7eb !important;
        font-size: 13px;
        vertical-align: middle;
    }

    .tm-table tbody tr:hover td,
    .table tbody tr:hover td {
        background: #f8fafc !important;
    }

    .tm-table-detail th {
        width: 120px;
        background: #f8fafc !important;
        color: #0f172a !important;
        font-weight: 700;
    }

    .tm-table-detail td {
        background: #ffffff !important;
        color: #0f172a !important;
    }

    .form-control,
    .form-select {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15) !important;
    }

    .tm-info-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 16px;
        min-height: 110px;
    }

    .tm-info-box p {
        margin-bottom: 6px;
        color: #64748b !important;
        font-weight: 700;
        font-size: 13px;
    }

    .tm-info-box h3 {
        color: #0f172a !important;
        font-weight: 800;
        margin-bottom: 2px;
    }

    .tm-info-box small {
        color: #64748b !important;
    }

    .alert {
        border-radius: 14px;
    }

    .badge {
        font-weight: 700;
    }

    .list-group-item {
        color: #0f172a !important;
    }

    .tm-dir-card {
        border-radius: 18px;
    }

    .tm-dir-table th {
        font-size: 13px;
        white-space: nowrap;
    }

    .tm-dir-table td {
        font-size: 13px;
    }

    .tm-dir-card {
        border-radius: 18px;
    }

    .tm-detail-table th {
        width: 170px;
        color: #64748b;
        font-size: 13px;
    }

    .tm-detail-table td {
        font-size: 13px;
    }

    .tm-dir-table th {
        font-size: 13px;
        white-space: nowrap;
    }

    .tm-dir-table td {
        font-size: 13px;
    }

        .tm-dir-card {
        border-radius: 18px;
    }

    .tm-dir-table th {
        font-size: 13px;
        white-space: nowrap;
    }

    .tm-dir-table td {
        font-size: 13px;
    }
   
    .dir-hero {
        background: linear-gradient(135deg, #0f3b73 0%, #0ea5e9 100%);
        color: #fff;
        border-radius: 22px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        box-shadow: 0 18px 40px rgba(14, 165, 233, .20);
    }

    .dir-hero h2 {
        color: #fff !important;
        font-weight: 900;
        margin: 8px 0 4px;
    }

    .dir-hero p {
        color: #eaf6ff !important;
        margin: 0;
    }

    .dir-badge {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 999px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        color: #fff;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .08em;
    }

    .dir-hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dir-stat {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 118px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, .08);
        border: 1px solid #e5e7eb;
    }

    .dir-stat p {
        color: #64748b !important;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .dir-stat h3 {
        color: #0f172a !important;
        font-weight: 900;
        margin-bottom: 2px;
    }

    .dir-stat small {
        color: #94a3b8 !important;
        font-size: 12px;
    }

    .dir-icon {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .dir-icon.blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .dir-icon.yellow {
        background: #fef3c7;
        color: #b45309;
    }

    .dir-icon.green {
        background: #dcfce7;
        color: #15803d;
    }

    .dir-icon.red {
        background: #fee2e2;
        color: #b91c1c;
    }

    .dir-card {
        border-radius: 18px !important;
    }

    .dir-money-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
    }

    .dir-money-box.approved {
        background: #ecfdf5;
        border-color: #bbf7d0;
    }

    .dir-money-box span {
        color: #64748b !important;
        font-weight: 800;
        font-size: 13px;
    }

    .dir-money-box h3 {
        color: #0f172a !important;
        font-weight: 900;
        margin: 8px 0 4px;
    }

    .dir-money-box p {
        color: #64748b !important;
        margin: 0;
        font-size: 12px;
    }

    .dir-note {
        background: #eff6ff;
        color: #1e40af;
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        gap: 10px;
        align-items: center;
        font-weight: 700;
    }

    .dir-note span,
    .dir-note i {
        color: #1e40af !important;
    }

    .dir-table th {
        text-align: center;
        font-size: 13px;
    }

    .dir-table td {
        font-size: 13px;
    }

    .rank-badge {
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        color: #fff;
        font-weight: 900;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .dir-hero {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .tm-log-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 560px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .tm-log-item {
        display: grid;
        grid-template-columns: 72px 1fr;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
    }

    .tm-log-time {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        white-space: nowrap;
    }

    .tm-log-content strong {
        display: block;
        font-size: 13px;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .tm-log-content small {
        display: block;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .tm-log-content p {
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
        margin: 0;
        word-break: break-word;
    }

    .tm-log-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 520px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .tm-log-item {
        display: grid;
        grid-template-columns: 72px 1fr;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
    }

    .tm-log-time {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }

    .tm-log-content strong {
        display: block;
        font-size: 13px;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .tm-log-content small {
        display: block;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .tm-log-content p {
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
        margin: 0;
    }

.tm-task-item {
        border-left: 4px solid #0d6efd;
        padding-left: 12px;
        margin-bottom: 14px;
    }

    .tm-task-item strong {
        color: #0f172a;
        font-size: 14px;
    }

    .tm-task-item p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }


</style>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script>
    function toggleAlternatifMenu(event) {
        event.preventDefault();

        const submenu = document.getElementById('alternatifSubmenu');
        const arrow = document.querySelector('.alternatif-arrow');

        submenu.classList.toggle('show');
        arrow.classList.toggle('rotate');
        
    }
    </script>
    </head>

<body class="theme-light">

    <?= $this->include('layouts/sidebar') ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h6 class="topbar-title"><?= esc($title ?? 'Dashboard') ?></h6>
                <p class="topbar-subtitle">Perumda Tirta Musi Palembang</p>
            </div>

            <div class="topbar-actions">
                <a href="<?= site_url('notifikasi') ?>" class="topbar-notification" title="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifikasi</span>
                    <?php if ((int) $__tmUnreadNotif > 0): ?>
                        <span class="notif-count-badge"><?= (int) $__tmUnreadNotif ?></span>
                    <?php endif; ?>
                </a>
                <button type="button" id="themeToggle" class="btn-theme-toggle">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    <span id="themeText">Mode Gelap</span>
                </button>
            </div>
        </div>

        <?= $this->renderSection('content') ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const toggle = document.getElementById('themeToggle');
            const icon = document.getElementById('themeIcon');
            const text = document.getElementById('themeText');

            const savedTheme = localStorage.getItem('spk_moora_theme') || 'light';

            function applyTheme(theme) {
                body.classList.remove('theme-light', 'theme-dark');

                if (theme === 'dark') {
                    body.classList.add('theme-dark');
                    icon.className = 'bi bi-brightness-high-fill';
                    text.textContent = 'Mode Cerah';
                } else {
                    body.classList.add('theme-light');
                    icon.className = 'bi bi-moon-stars-fill';
                    text.textContent = 'Mode Gelap';
                }

                localStorage.setItem('spk_moora_theme', theme);
            }

            applyTheme(savedTheme);

            toggle.addEventListener('click', function () {
                const isDark = body.classList.contains('theme-dark');
                applyTheme(isDark ? 'light' : 'dark');
            });
        });
    </script>

</body>
</html>