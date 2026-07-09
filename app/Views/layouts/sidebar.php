<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');

.sidebar {
    width: 260px;
    min-height: 100vh;
    background: #040d1a;
    border-right: 1px solid rgba(255, 255, 255, .05);
    color: #ffffff;
    position: fixed;
    left: 0;
    top: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    font-family: 'Plus Jakarta Sans', sans-serif;
    overflow: hidden;
}

/* Ambient background glow */
.sidebar::before {
    content: '';
    position: absolute;
    top: -80px;
    left: -60px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(14, 165, 233, .18) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

.sidebar::after {
    content: '';
    position: absolute;
    bottom: 60px;
    right: -80px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(245, 197, 66, .10) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

/* Subtle grid lines texture */
.sidebar-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 24px 16px;
    background-image:
        linear-gradient(rgba(255,255,255,.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.015) 1px, transparent 1px);
    background-size: 28px 28px;
}

/* ── Brand ── */
.sidebar-brand {
    text-align: center;
    padding-bottom: 24px;
    margin-bottom: 20px;
    position: relative;
}

.sidebar-brand::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 10%;
    width: 80%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(245,197,66,.45), transparent);
}

.logo-circle {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    margin: 0 auto 14px;
    background: radial-gradient(circle at 35% 35%, #1a4f8a, #071b36);
    border: 2px solid rgba(245, 197, 66, .65);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 0 0 5px rgba(245, 197, 66, .08),
        0 0 28px rgba(245, 197, 66, .22),
        inset 0 1px 1px rgba(255,255,255,.12);
    position: relative;
    transition: box-shadow .4s ease;
}

.logo-circle:hover {
    box-shadow:
        0 0 0 5px rgba(245, 197, 66, .14),
        0 0 40px rgba(245, 197, 66, .36),
        inset 0 1px 1px rgba(255,255,255,.18);
}

.logo-circle img {
    width: 56px;
    height: 56px;
    object-fit: contain;
    filter: drop-shadow(0 0 6px rgba(255,255,255,.2));
}

.sidebar-brand h5 {
    font-size: 14.5px;
    font-weight: 900;
    margin: 0 0 4px;
    line-height: 1.35;
    letter-spacing: .2px;
    color: #f0f6ff;
}

.sidebar-brand small {
    display: inline-block;
    color: #f5c542;
    font-size: 9.5px;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    font-weight: 800;
    opacity: .85;
}

/* ── Section label ── */
.sidebar-section {
    color: rgba(148, 163, 184, .55);
    font-size: 9.5px;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 800;
    margin: 8px 4px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sidebar-section::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,.06);
}

/* ── Menu ── */
.sidebar-menu {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #94a3b8;
    text-decoration: none;
    padding: 11px 13px;
    border-radius: 13px;
    font-weight: 700;
    font-size: 13.5px;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    position: relative;
    border: 1px solid transparent;
    letter-spacing: .1px;
}

.sidebar-menu a i {
    width: 34px;
    height: 34px;
    min-width: 34px;
    background: rgba(255, 255, 255, .05);
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 15px;
    transition: all .22s ease;
    border: 1px solid rgba(255,255,255,.06);
}

.sidebar-menu a:hover {
    background: rgba(14, 165, 233, .09);
    color: #e2eeff;
    border-color: rgba(14, 165, 233, .15);
    transform: translateX(4px);
    padding-left: 15px;
}

.sidebar-menu a:hover i {
    background: rgba(14, 165, 233, .18);
    color: #7dd3fc;
    border-color: rgba(14, 165, 233, .22);
}

.sidebar-menu a.active {
    background: linear-gradient(105deg, rgba(14, 165, 233, .22) 0%, rgba(245, 197, 66, .08) 100%);
    color: #ffffff;
    border-color: rgba(14, 165, 233, .25);
    transform: translateX(4px);
    padding-left: 15px;
    box-shadow: 0 4px 16px rgba(14, 165, 233, .12);
}

.sidebar-menu a.active::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 22px;
    background: linear-gradient(180deg, #0ea5e9, #f5c542);
    border-radius: 0 3px 3px 0;
}

.sidebar-menu a.active i {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #ffffff;
    border-color: rgba(14, 165, 233, .4);
    box-shadow: 0 2px 10px rgba(14, 165, 233, .35);
}

/* ── Footer ── */
.sidebar-footer {
    margin-top: auto;
    padding-top: 16px;
    position: relative;
}

.sidebar-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 5%;
    width: 90%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
}

.user-box {
    display: flex;
    align-items: center;
    gap: 11px;
    background: rgba(255, 255, 255, .04);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 14px;
    padding: 11px 13px;
    margin-bottom: 10px;
    transition: background .2s;
}

.user-box:hover {
    background: rgba(255,255,255,.07);
}

.avatar {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 15px;
    color: #fff;
    box-shadow: 0 2px 10px rgba(14, 165, 233, .3);
    border: 1.5px solid rgba(255,255,255,.15);
}

.user-info strong {
    display: block;
    font-size: 13px;
    font-weight: 800;
    color: #e8f0fe;
    line-height: 1.3;
}

.user-info small {
    display: block;
    color: #64748b;
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: .3px;
    text-transform: capitalize;
}

.logout-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 9px;
    background: rgba(244, 63, 94, .08);
    color: #fb7185;
    border: 1px solid rgba(244, 63, 94, .22);
    text-decoration: none;
    border-radius: 13px;
    padding: 11px;
    font-weight: 800;
    font-size: 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .22s ease;
    letter-spacing: .1px;
    cursor: pointer;
}

.logout-btn i {
    font-size: 15px;
    transition: transform .22s ease;
}

.logout-btn:hover {
    background: rgba(244, 63, 94, .18);
    color: #ffffff;
    border-color: rgba(244, 63, 94, .45);
    box-shadow: 0 4px 16px rgba(244, 63, 94, .15);
}

.logout-btn:hover i {
    transform: translateX(-3px);
}

/* ── Main content offset ── */
.main-content {
    margin-left: 260px;
}

.sidebar-admin {
    width: 260px;
    min-height: 100vh;
    background: linear-gradient(180deg, #071a33 0%, #020817 100%);
    color: #ffffff;
    position: fixed;
    left: 0;
    top: 0;
    padding: 24px 16px;
    border-right: 1px solid rgba(255, 255, 255, .08);
    z-index: 1000;
}

.sidebar-brand {
    text-align: center;
    padding-bottom: 22px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, .08);
}

.logo-wrap {
    width: 76px;
    height: 76px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: rgba(255, 193, 7, .12);
    border: 2px solid rgba(255, 193, 7, .55);
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-wrap img {
    width: 58px;
    height: 58px;
    object-fit: contain;
}

.sidebar-brand h5 {
    font-size: 14px;
    font-weight: 800;
    margin: 0;
}

.sidebar-brand p {
    font-size: 10px;
    font-weight: 700;
    color: #facc15;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin: 4px 0 0;
}

.sidebar-menu-title {
    font-size: 10px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .12em;
    margin: 16px 0 8px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #94a3b8;
    text-decoration: none;
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 8px;
    transition: .2s ease;
}

.sidebar-link i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, .06);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.sidebar-link:hover {
    color: #ffffff;
    background: rgba(14, 165, 233, .12);
}

.sidebar-link.active {
    color: #ffffff;
    background: rgba(14, 165, 233, .18);
    border: 1px solid rgba(14, 165, 233, .45);
    box-shadow: 0 10px 30px rgba(14, 165, 233, .12);
}

.sidebar-link.active i {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #ffffff;
}

.sidebar-user {
    margin-top: 34px;
    padding: 12px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .08);
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
}

.sidebar-user h6 {
    margin: 0;
    font-size: 13px;
    font-weight: 800;
}

.sidebar-user small {
    color: #cbd5e1;
    font-size: 11px;
}

.sidebar-logout {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    padding: 12px 14px;
    border-radius: 14px;
    color: #fb7185;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    background: rgba(225, 29, 72, .10);
    border: 1px solid rgba(225, 29, 72, .25);
}

.sidebar-logout:hover {
    color: #ffffff;
    background: rgba(225, 29, 72, .25);
}

.main-content {
    margin-left: 260px;
    min-height: 100vh;
    background: #eef4fb;
}

@media (max-width: 991px) {
    .sidebar-admin {
        width: 100%;
        min-height: auto;
        position: relative;
    }

    .main-content {
        margin-left: 0;
    }
}
.sidebar-admin {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    padding: 22px 14px;
    overflow-y: auto;
    z-index: 1000;
    transition: background .25s ease, color .25s ease, border .25s ease;
}

.sidebar-brand {
    text-align: center;
    padding-bottom: 22px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(148, 163, 184, .22);
}

.sidebar-logo {
    width: 78px;
    height: 78px;
    margin: 0 auto 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-logo img {
    width: 60px;
    height: 60px;
    object-fit: contain;
}

.sidebar-brand h5 {
    font-size: 14px;
    font-weight: 900;
    margin: 0;
}

.sidebar-brand p {
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin: 4px 0 0;
}

.sidebar-menu-title {
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin: 18px 0 8px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 12px;
    margin-bottom: 8px;
    border-radius: 14px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition: .2s ease;
    border: 1px solid transparent;
}

.sidebar-link i {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.sidebar-bottom {
    margin-top: 32px;
}

.sidebar-user {
    padding: 12px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 11px;
    border: 1px solid transparent;
}

.sidebar-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    flex-shrink: 0;
}

.sidebar-user-info h6 {
    font-size: 12px;
    font-weight: 900;
    margin: 0;
    line-height: 1.2;
}

.sidebar-user-info small {
    font-size: 11px;
    font-weight: 600;
}

.sidebar-logout {
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 14px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 900;
    border: 1px solid transparent;
    transition: .2s ease;
}

/* Sidebar Light */
body.theme-light .sidebar-admin {
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
    color: #0f172a !important;
    border-right: 1px solid #d9e2ec !important;
}

body.theme-light .sidebar-logo {
    background: #fff7d6;
    border: 2px solid #eab308;
}

body.theme-light .sidebar-brand h5 {
    color: #0f172a;
}

body.theme-light .sidebar-brand p {
    color: #c99700;
}

body.theme-light .sidebar-menu-title {
    color: #64748b;
}

body.theme-light .sidebar-link {
    color: #475569 !important;
}

body.theme-light .sidebar-link i {
    background: #eaf1f8;
    color: #334155;
}

body.theme-light .sidebar-link:hover {
    background: #e0f2fe;
    color: #075985 !important;
}

body.theme-light .sidebar-link.active {
    background: linear-gradient(135deg, #0ea5e9, #2563eb) !important;
    color: #ffffff !important;
    box-shadow: 0 10px 28px rgba(37, 99, 235, .22);
}

body.theme-light .sidebar-link.active i {
    background: rgba(255, 255, 255, .22);
    color: #ffffff;
}

body.theme-light .sidebar-user {
    background: #f1f5f9;
    border-color: #d9e2ec;
}

body.theme-light .sidebar-user-info h6 {
    color: #0f172a;
}

body.theme-light .sidebar-user-info small {
    color: #64748b;
}

body.theme-light .sidebar-logout {
    background: #fff1f2;
    color: #be123c;
    border-color: rgba(225, 29, 72, .25);
}

/* Sidebar Dark */
body.theme-dark .sidebar-admin {
    background: linear-gradient(180deg, #071a33 0%, #020817 100%) !important;
    color: #ffffff !important;
    border-right: 1px solid rgba(255, 255, 255, .08) !important;
}

body.theme-dark .sidebar-logo {
    background: rgba(250, 204, 21, .12);
    border: 2px solid rgba(250, 204, 21, .55);
}

body.theme-dark .sidebar-brand h5 {
    color: #ffffff;
}

body.theme-dark .sidebar-brand p {
    color: #facc15;
}

body.theme-dark .sidebar-menu-title {
    color: #64748b;
}

body.theme-dark .sidebar-link {
    color: #94a3b8 !important;
}

body.theme-dark .sidebar-link i {
    background: rgba(255, 255, 255, .06);
    color: #94a3b8;
}

body.theme-dark .sidebar-link:hover {
    background: rgba(14, 165, 233, .12);
    color: #ffffff !important;
}

body.theme-dark .sidebar-link.active {
    background: rgba(14, 165, 233, .18) !important;
    color: #ffffff !important;
    border-color: rgba(14, 165, 233, .45);
}

body.theme-dark .sidebar-link.active i {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #ffffff;
}

body.theme-dark .sidebar-user {
    background: rgba(255, 255, 255, .06);
    border-color: rgba(255, 255, 255, .08);
}

body.theme-dark .sidebar-user-info h6 {
    color: #ffffff;
}

body.theme-dark .sidebar-user-info small {
    color: #cbd5e1;
}

body.theme-dark .sidebar-logout {
    background: rgba(225, 29, 72, .10);
    color: #fb7185;
    border-color: rgba(225, 29, 72, .25);
}

.sidebar-parent {
    position: relative;
}

.sidebar-parent .alternatif-arrow {
    margin-left: auto;
    width: auto !important;
    height: auto !important;
    min-width: auto !important;
    background: transparent !important;
    border: none !important;
    font-size: 11px;
    transition: transform .25s ease;
}

.sidebar-parent .alternatif-arrow.rotate {
    transform: rotate(180deg);
}

.submenu-collapse {
    display: none;
    margin: -2px 0 8px 46px;
    padding-left: 12px;
    border-left: 2px solid rgba(14, 165, 233, .35);
}

.submenu-collapse.show {
    display: block;
}

.sidebar-submenu-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    margin-bottom: 4px;
    border-radius: 10px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: .2s ease;
}

.sidebar-submenu-link i {
    width: 16px;
    font-size: 12px;
}

.sidebar-submenu-link:hover,
.sidebar-submenu-link.active {
    color: #ffffff;
    background: rgba(14, 165, 233, .18);
}
</style>

<?php
$role       = session()->get('role') ?? 'administrator';
$nama       = session()->get('nama_lengkap') ?? 'Administrator Sistem';
$currentUrl = uri_string();

function sidebarActive($path, $currentUrl)
{
    return str_contains($currentUrl, $path) ? 'active' : '';
}

function roleLabel($role)
{
    return ucwords(str_replace('_', ' ', $role));
}
?>

<aside class="sidebar-admin">

    <!-- BRAND -->
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <img src="<?= base_url('assets/img/Logo Tirta Musi.png') ?>" alt="Logo Tirta Musi">
        </div>
        <h5>Perumda Tirta Musi</h5>
        <p>Sistem Pendukung Keputusan</p>
    </div>

    <!-- ================= ADMIN ================= -->
    <?php if ($role === 'administrator') : ?>

        <div class="sidebar-menu-title">Menu Utama</div>

        <a href="<?= site_url('administrator/dashboard') ?>"
           class="sidebar-link <?= sidebarActive('administrator/dashboard', $currentUrl) ?>">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

       <div class="sidebar-menu-title">Master Data</div>

<a href="<?= site_url('administrator/kriteria') ?>"
   class="sidebar-link <?= sidebarActive('administrator/kriteria', $currentUrl) ?>">
    <i class="bi bi-sliders"></i>
    <span>Data Kriteria</span>
</a>

<?php
$isAlternatifOpen =
    uri_string() == 'administrator/alternatif' ||
    uri_string() == 'administrator/master-data/alat' ||
    uri_string() == 'administrator/master-data/material';
?>

<a href="#"
   class="sidebar-link sidebar-parent <?= $isAlternatifOpen ? 'active' : '' ?>"
   onclick="toggleAlternatifMenu(event)">
    <i class="bi bi-box-seam"></i>
    <span>Data Alternatif</span>
    <i class="bi bi-chevron-down alternatif-arrow <?= $isAlternatifOpen ? 'rotate' : '' ?>"></i>
</a>

<div id="alternatifSubmenu" class="submenu-collapse <?= $isAlternatifOpen ? 'show' : '' ?>">
    <a href="<?= site_url('administrator/master-data/alat') ?>"
       class="sidebar-submenu-link <?= uri_string() == 'administrator/master-data/alat' ? 'active' : '' ?>">
        <i class="bi bi-tools"></i>
        <span>Data Alat</span>
    </a>

    <a href="<?= site_url('administrator/master-data/material') ?>"
       class="sidebar-submenu-link <?= uri_string() == 'administrator/master-data/material' ? 'active' : '' ?>">
        <i class="bi bi-box-seam"></i>
        <span>Data Material</span>
    </a>
</div>
<a href="<?= site_url('administrator/master-data/perbaikan-alat') ?>"
   class="sidebar-link <?= sidebarActive('perbaikan-alat', $currentUrl) ?>">
    <i class="bi bi-wrench-adjustable"></i>
    <span>Perbaikan Alat</span>
</a>
        <div class="sidebar-menu-title">Monitoring</div>

        <a href="<?= site_url('administrator/monitoring') ?>"
           class="sidebar-link <?= sidebarActive('administrator/monitoring', $currentUrl) ?>">
            <i class="bi bi-clipboard-data"></i>
            <span>Monitoring Usulan</span>
        </a>

        <a href="<?= site_url('administrator/kalkulasi-moora') ?>"
           class="sidebar-link <?= sidebarActive('administrator/kalkulasi-moora', $currentUrl) ?: sidebarActive('administrator/moora', $currentUrl) ?>">
            <i class="bi bi-calculator-fill"></i>
            <span>Kalkulasi MOORA</span>
        </a>

        <a href="<?= site_url('administrator/training-moora') ?>"
           class="sidebar-link <?= sidebarActive('administrator/training-moora', $currentUrl) ?>">
            <i class="bi bi-activity"></i>
            <span>Training MOORA</span>
        </a>

        <div class="sidebar-menu-title">Sistem</div>

        <a href="<?= site_url('administrator/user') ?>"
           class="sidebar-link <?= sidebarActive('administrator/user', $currentUrl) ?>">
            <i class="bi bi-people"></i>
            <span>Manajemen User</span>
        </a>

        <a href="<?= site_url('administrator/registrasi') ?>"
           class="sidebar-link <?= sidebarActive('administrator/registrasi', $currentUrl) ?>">
            <i class="bi bi-person-fill-check"></i>
            <span>Approval Register</span>
        </a>

        <a href="<?= site_url('administrator/log') ?>"
           class="sidebar-link <?= sidebarActive('administrator/log', $currentUrl) ?>">
            <i class="bi bi-clock-history"></i>
            <span>Log Aktivitas</span>
        </a>

        <a href="<?= site_url('administrator/setting') ?>"
           class="sidebar-link <?= sidebarActive('administrator/setting', $currentUrl) ?>">
            <i class="bi bi-gear-fill"></i>
            <span>Setting</span>
        </a>

    <!-- ================= SUB UNIT ================= -->
    <?php elseif ($role === 'sub_unit') : ?>

        <a href="<?= site_url('sub-unit/dashboard') ?>" class="sidebar-link">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= site_url('sub-unit/usulan') ?>" class="sidebar-link">
            <i class="bi bi-file-earmark-text"></i>
            <span>Usulan Saya</span>
        </a>

        <a href="<?= site_url('sub-unit/barang-pengadaan') ?>" class="sidebar-link">
            <i class="bi bi-truck"></i>
            <span>Barang Pengadaan</span>
        </a>

        <a href="<?= site_url('sub-unit/usulan/create') ?>" class="sidebar-link">
            <i class="bi bi-plus-circle"></i>
            <span>Buat Usulan</span>
        </a>

    <!-- ================= GUDANG ================= -->
    <?php elseif ($role === 'gudang') : ?>

    <a href="<?= site_url('gudang/dashboard') ?>" class="sidebar-link">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    <a href="<?= site_url('gudang/usulan-masuk') ?>" class="sidebar-link">
        <i class="bi bi-inbox"></i>
        <span>Usulan Masuk</span>
    </a>

    <a href="<?= site_url('gudang/hasil-moora') ?>" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i>
        <span>Hasil MOORA</span>
    </a>

    <a href="<?= site_url('gudang/stok') ?>" class="sidebar-link">
        <i class="bi bi-box-seam"></i>
        <span>Stok Barang</span>
    </a>

    <a href="<?= site_url('gudang/penerimaan') ?>" class="sidebar-link">
        <i class="bi bi-box-arrow-in-down"></i>
        <span>Penerimaan Barang</span>
    </a>

    <a href="<?= site_url('gudang/pengambilan') ?>" class="sidebar-link">
        <i class="bi bi-box-arrow-up"></i>
        <span>Pengambilan Barang</span>
    </a>

    <a href="<?= site_url('gudang/riwayat') ?>" class="sidebar-link">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat Aktivitas</span>
    </a>

<!-- ================= MANAJER UMUM ================= -->
<?php elseif ($role === 'manajer_umum') : ?>

    <div class="sidebar-menu-title">Menu Manajer Umum</div>

    <a href="<?= site_url('manajer-umum/dashboard') ?>"
       class="sidebar-link <?= sidebarActive('manajer-umum/dashboard', $currentUrl) ?>">
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
    </a>

    <a href="<?= site_url('manajer-umum/usulan') ?>"
       class="sidebar-link <?= sidebarActive('manajer-umum/usulan', $currentUrl) ?>">
        <i class="bi bi-clipboard-check"></i>
        <span>Review Usulan</span>
    </a>

    <a href="<?= site_url('manajer-umum/hasil-moora') ?>"
       class="sidebar-link <?= sidebarActive('manajer-umum/hasil-moora', $currentUrl) ?>">
        <i class="bi bi-bar-chart-line"></i>
        <span>Hasil MOORA</span>
    </a>

    <a href="<?= site_url('manajer-umum/riwayat-validasi') ?>"
       class="sidebar-link <?= sidebarActive('manajer-umum/riwayat-validasi', $currentUrl) ?>">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat Validasi</span>
    </a>
    
    <!-- ================= DIREKTUR ================= -->
    <?php elseif ($role === 'direktur') : ?>

        <div class="sidebar-menu-title">Menu Direktur</div>

        <a href="<?= site_url('direktur/dashboard') ?>"
           class="sidebar-link <?= sidebarActive('direktur/dashboard', $currentUrl) ?>">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= site_url('direktur/hasil') ?>"
           class="sidebar-link <?= sidebarActive('direktur/hasil', $currentUrl) ?>">
            <i class="bi bi-bar-chart"></i>
            <span>Hasil MOORA</span>
        </a>

        <a href="<?= site_url('direktur/validasi') ?>"
           class="sidebar-link <?= sidebarActive('direktur/validasi', $currentUrl) ?>">
            <i class="bi bi-shield-check"></i>
            <span>Validasi Usulan</span>
        </a>

    <!-- ================= PENGADAAN ================= -->
    <?php elseif ($role === 'pengadaan') : ?>

        <div class="sidebar-menu-title">Menu Pengadaan</div>

        <a href="<?= site_url('pengadaan/dashboard') ?>"
           class="sidebar-link <?= sidebarActive('pengadaan/dashboard', $currentUrl) ?>">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= site_url('pengadaan/pembelian') ?>"
           class="sidebar-link <?= sidebarActive('pengadaan/pembelian', $currentUrl) ?>">
            <i class="bi bi-cart-check"></i>
            <span>Proses Pembelian</span>
        </a>

        <a href="<?= site_url('pengadaan/dokumen') ?>"
           class="sidebar-link <?= sidebarActive('pengadaan/dokumen', $currentUrl) ?>">
            <i class="bi bi-file-earmark-arrow-up"></i>
            <span>Dokumen Pengadaan</span>
        </a>

        <a href="<?= site_url('pengadaan/serah-barang') ?>"
           class="sidebar-link <?= sidebarActive('pengadaan/serah-barang', $currentUrl) ?>">
            <i class="bi bi-truck"></i>
            <span>Serah Barang</span>
        </a>

    <?php endif; ?>

    <!-- ================= USER INFO ================= -->
    <div class="sidebar-bottom">

        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?= esc(strtoupper(substr($nama, 0, 1))) ?>
            </div>

            <div class="sidebar-user-info">
                <h6><?= esc($nama) ?></h6>
                <small><?= esc(roleLabel($role)) ?></small>
            </div>
        </div>

        <a href="<?= site_url('logout') ?>"
           class="sidebar-logout"
           onclick="return confirm('Yakin ingin keluar dari sistem?')">
            <i class="bi bi-box-arrow-left"></i>
            <span>Keluar dari Sistem</span>
        </a>

    </div>

</aside>