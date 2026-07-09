<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

/* =========================================
   DASHBOARD SUB UNIT — Content Only
   Tema sidebar: dark navy #040d1a
   Aksen: #0ea5e9 (biru) & #f5c542 (gold)
   Font: Plus Jakarta Sans
========================================= */

*, *::before, *::after { box-sizing: border-box; }

.db-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f0f4f9;
    min-height: 100vh;
}

/* ─── MINI NAV ─── */
.db-subnav {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 0 28px;
    display: flex;
    align-items: center;
    gap: 4px;
    height: 44px;
    overflow-x: auto;
}

.db-subnav-lbl {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-right: 8px;
    white-space: nowrap;
    flex-shrink: 0;
}

.db-subnav a {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    transition: all .18s;
    white-space: nowrap;
    border: 1px solid transparent;
}

.db-subnav a:hover {
    background: #f0f9ff;
    color: #0ea5e9;
    text-decoration: none;
}

.db-subnav a.active {
    background: linear-gradient(105deg,rgba(14,165,233,.12),rgba(37,99,235,.06));
    color: #0ea5e9;
    border-color: rgba(14,165,233,.22);
    font-weight: 800;
}

.db-subnav a .bi { font-size: 14px; }

/* ─── MAIN ─── */
.db-main {
    padding: 24px 28px 48px;
}

/* ─── PAGE HEADER ─── */
.db-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 14px;
}

.db-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 800;
    color: #0ea5e9;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-bottom: 6px;
    background: rgba(14,165,233,.08);
    padding: 4px 11px;
    border-radius: 20px;
    border: 1px solid rgba(14,165,233,.18);
}

.db-page-title {
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 6px;
    letter-spacing: -.4px;
    line-height: 1.2;
}

.db-page-desc {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

.db-btn-primary {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #ffffff;
    border: none;
    padding: 11px 22px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 16px rgba(14,165,233,.32);
    white-space: nowrap;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.db-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(14,165,233,.42);
    color: #fff;
    text-decoration: none;
}

.db-btn-primary .bi { font-size: 16px; }

/* ─── STAT CARDS ─── */
.db-stats-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.db-stat {
    background: #ffffff;
    border: 1px solid #e8eef5;
    border-radius: 16px;
    padding: 20px 22px;
    position: relative;
    overflow: hidden;
    transition: all .22s cubic-bezier(.4,0,.2,1);
}

.db-stat:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,.09);
    border-color: transparent;
}

.db-stat::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 16px 16px 0 0;
}

.s-total::before  { background: linear-gradient(90deg,#0ea5e9,#2563eb); }
.s-draft::before  { background: linear-gradient(90deg,#64748b,#94a3b8); }
.s-ajukan::before { background: linear-gradient(90deg,#f5c542,#f59e0b); }
.s-selesai::before{ background: linear-gradient(90deg,#22c55e,#16a34a); }

.db-stat-deco {
    position: absolute;
    right: 16px;
    bottom: 12px;
    font-size: 44px;
    opacity: .07;
    pointer-events: none;
    line-height: 1;
}

.s-total  .db-stat-deco { color:#0ea5e9; opacity:.1; }
.s-draft  .db-stat-deco { color:#64748b; }
.s-ajukan .db-stat-deco { color:#f5c542; }
.s-selesai .db-stat-deco{ color:#22c55e; }

.db-stat-ico {
    width: 40px;
    height: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    font-size: 18px;
}

.s-total  .db-stat-ico { background:rgba(14,165,233,.1);  color:#0ea5e9; }
.s-draft  .db-stat-ico { background:rgba(100,116,139,.1); color:#64748b; }
.s-ajukan .db-stat-ico { background:rgba(245,197,66,.12); color:#d97706; }
.s-selesai .db-stat-ico{ background:rgba(34,197,94,.1);   color:#16a34a; }

.db-stat-lbl {
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: .7px;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.db-stat-val {
    font-size: 38px;
    font-weight: 900;
    line-height: 1;
    letter-spacing: -1.5px;
    margin-bottom: 6px;
}

.s-total  .db-stat-val { color:#0ea5e9; }
.s-draft  .db-stat-val { color:#475569; }
.s-ajukan .db-stat-val { color:#d97706; }
.s-selesai .db-stat-val{ color:#16a34a; }

.db-stat-note {
    font-size: 11.5px;
    color: #cbd5e1;
    font-weight: 600;
}

/* ─── 2-COL ROW ─── */
.db-two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

/* ─── PROGRESS CARD ─── */
.db-prog-card {
    background: #ffffff;
    border: 1px solid #e8eef5;
    border-radius: 16px;
    padding: 20px 24px;
}

.db-card-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.db-card-title .bi { color: #0ea5e9; font-size: 16px; }

.db-prog-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 13px;
}

.db-prog-row:last-child { margin-bottom: 0; }

.db-prog-name {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    width: 88px;
    flex-shrink: 0;
}

.db-prog-track {
    flex: 1;
    height: 8px;
    background: #f1f5f9;
    border-radius: 20px;
    overflow: hidden;
}

.db-prog-fill {
    height: 100%;
    border-radius: 20px;
    transition: width .7s ease;
}

.pf-draft  { background: linear-gradient(90deg,#94a3b8,#64748b); }
.pf-ajukan { background: linear-gradient(90deg,#f5c542,#f59e0b); }
.pf-selesai{ background: linear-gradient(90deg,#22c55e,#16a34a); }

.db-prog-pct {
    font-size: 12px;
    font-weight: 800;
    color: #334155;
    width: 34px;
    text-align: right;
    flex-shrink: 0;
}

/* ─── TIMELINE CARD ─── */
.db-tl-card {
    background: #ffffff;
    border: 1px solid #e8eef5;
    border-radius: 16px;
    padding: 20px 24px;
}

.db-tl-card .db-card-title .bi { color: #f5c542; }

.db-timeline {
    display: flex;
    align-items: flex-start;
    position: relative;
}

.db-tl-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.db-tl-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 17px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: #e2e8f0;
    z-index: 0;
}

.db-tl-step.done:not(:last-child)::after {
    background: linear-gradient(90deg, #0ea5e9, rgba(14,165,233,.2));
}

.db-tl-dot {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #cbd5e1;
    z-index: 1;
    position: relative;
    transition: all .2s;
}

.db-tl-step.done .db-tl-dot {
    background: linear-gradient(135deg,#0ea5e9,#2563eb);
    border-color: #0ea5e9;
    color: #fff;
    box-shadow: 0 4px 12px rgba(14,165,233,.3);
}

.db-tl-step.current .db-tl-dot {
    background: #fffbeb;
    border-color: #f5c542;
    color: #d97706;
    box-shadow: 0 0 0 4px rgba(245,197,66,.18);
}

.db-tl-lbl {
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    margin-top: 7px;
    text-align: center;
    letter-spacing: .2px;
    line-height: 1.3;
}

.db-tl-step.done    .db-tl-lbl { color:#0ea5e9; }
.db-tl-step.current .db-tl-lbl { color:#d97706; font-weight:800; }

/* ─── QUICK ACTIONS ─── */
.db-quick-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.db-qcard {
    background: #ffffff;
    border: 1px solid #e8eef5;
    border-radius: 16px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    color: inherit;
}

.db-qcard:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(14,165,233,.14);
    border-color: rgba(14,165,233,.3);
    text-decoration: none;
    color: inherit;
}

.db-qcard-ico {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.qc-buat    .db-qcard-ico { background:linear-gradient(135deg,#0ea5e9,#2563eb); color:#fff; box-shadow:0 6px 18px rgba(14,165,233,.3); }
.qc-pantau  .db-qcard-ico { background:linear-gradient(135deg,#f5c542,#f59e0b); color:#fff; box-shadow:0 6px 18px rgba(245,197,66,.3); }

.db-qcard-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 3px;
    line-height: 1.2;
}

.db-qcard-desc {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
}

.db-qcard-arr {
    margin-left: auto;
    color: #cbd5e1;
    font-size: 18px;
    flex-shrink: 0;
    transition: color .18s;
}

.db-qcard:hover .db-qcard-arr { color: #0ea5e9; }

/* ─── TABLE CARD ─── */
.db-tbl-card {
    background: #ffffff;
    border: 1px solid #e8eef5;
    border-radius: 16px;
    overflow: hidden;
}

.db-tbl-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    background: linear-gradient(105deg,#fafbfd,#f8faff);
}

.db-tbl-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.db-tbl-header-ico {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: rgba(14,165,233,.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0ea5e9;
    font-size: 18px;
    flex-shrink: 0;
}

.db-tbl-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px;
    line-height: 1.2;
}

.db-tbl-desc {
    font-size: 11.5px;
    color: #94a3b8;
    font-weight: 600;
    margin: 0;
}

.db-btn-outline {
    background: transparent;
    border: 1.5px solid rgba(14,165,233,.35);
    color: #0ea5e9;
    padding: 7px 16px;
    border-radius: 9px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all .18s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    white-space: nowrap;
}

.db-btn-outline:hover {
    background: #0ea5e9;
    color: #fff;
    text-decoration: none;
    border-color: #0ea5e9;
}

/* Filter bar */
.db-filter-bar {
    padding: 11px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
    background: #fafbfd;
}

.db-filter-lbl {
    font-size: 10.5px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: .6px;
    text-transform: uppercase;
    margin-right: 4px;
    flex-shrink: 0;
}

.db-chip {
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    color: #64748b;
    background: #fff;
    transition: all .15s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.db-chip:hover, .db-chip.active {
    background: #0ea5e9;
    color: #fff;
    border-color: #0ea5e9;
}

/* Table */
.db-tbl-wrap { overflow-x: auto; }

.db-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.db-table thead tr {
    background: #f8fafc;
    border-bottom: 1px solid #e8eef5;
}

.db-table thead th {
    padding: 12px 18px;
    text-align: left;
    font-size: 10.5px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: .8px;
    text-transform: uppercase;
    white-space: nowrap;
}

.db-table thead th.tc { text-align: center; }

.db-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .12s;
}

.db-table tbody tr:last-child { border-bottom: none; }
.db-table tbody tr:hover { background: #f8faff; }

.db-table td {
    padding: 13px 18px;
    vertical-align: middle;
    color: #334155;
}

.db-table td.tc { text-align: center; }

.td-no {
    font-size: 12px;
    color: #cbd5e1;
    font-weight: 700;
    text-align: center;
    width: 44px;
}

.td-nomor {
    font-family: 'Courier New', monospace;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: .4px;
    display: block;
}

.td-nomor-sub {
    font-size: 10.5px;
    color: #94a3b8;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: block;
    margin-top: 2px;
}

.td-tgl {
    font-size: 12.5px;
    color: #64748b;
    font-weight: 600;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 5px;
}

.td-tgl .bi { opacity: .5; font-size: 12px; }

.td-unit {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}

/* Badge */
.spk-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid transparent;
}

.spk-badge .bi { font-size: 12px; }

.b-draft        { background:#f1f5f9; color:#475569;  border-color:#e2e8f0; }
.b-diajukan     { background:#eff6ff; color:#1d4ed8;  border-color:#bfdbfe; }
.b-diverifikasi { background:#f0fdf4; color:#15803d;  border-color:#bbf7d0; }
.b-ditolak      { background:#fef2f2; color:#dc2626;  border-color:#fecaca; }
.b-banding      { background:#fffbeb; color:#b45309;  border-color:#fde68a; }
.b-selesai      { background:#ecfdf5; color:#059669;  border-color:#a7f3d0; }

/* Tombol Detail */
.db-btn-detail {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: all .15s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.db-btn-detail:hover {
    background: linear-gradient(135deg,#0ea5e9,#2563eb);
    border-color: #0ea5e9;
    color: #fff;
    text-decoration: none;
}

.db-btn-detail .bi { font-size: 13px; }

/* Empty */
.db-empty {
    padding: 56px 24px;
    text-align: center;
}

.db-empty-ico { font-size: 48px; color: #e2e8f0; margin-bottom: 14px; display: block; }
.db-empty-ttl { font-size: 15px; font-weight: 800; color: #94a3b8; margin-bottom: 6px; }
.db-empty-dsc { font-size: 13px; color: #cbd5e1; font-weight: 500; margin-bottom: 18px; }

/* Table footer */
.db-tbl-footer {
    padding: 13px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafbfd;
    flex-wrap: wrap;
    gap: 8px;
}

.db-tbl-footer-info {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
}

.db-tbl-footer-info strong { color: #334155; font-weight: 800; }

/* ─── RESPONSIVE ─── */
@media (max-width: 1024px) {
    .db-stats-grid { grid-template-columns: repeat(2,1fr); }
    .db-two-col    { grid-template-columns: 1fr; }
    .db-quick-row  { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
    .db-main    { padding: 16px; }
    .db-subnav  { padding: 0 16px; }
    .db-stats-grid { grid-template-columns: repeat(2,1fr); gap: 10px; }
    .db-tbl-header { padding: 14px 16px; }
    .db-table td, .db-table th { padding: 11px 12px; }
    .db-page-title { font-size: 20px; }
}
</style>

<div class="db-wrap">

    <div class="db-subnav">
        <span class="db-subnav-lbl">Menu</span>
        <a href="<?= site_url('sub-unit/dashboard') ?>" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= site_url('sub-unit/usulan') ?>">
            <i class="bi bi-file-earmark-text"></i> Usulan Saya
        </a>
        <a href="<?= site_url('sub-unit/barang-pengadaan') ?>">
            <i class="bi bi-truck"></i> Barang Pengadaan
        </a>
        <a href="<?= site_url('sub-unit/usulan/create') ?>">
            <i class="bi bi-plus-circle"></i> Buat Usulan
        </a>
    </div>

    <div class="db-main">

        <div class="db-page-header">
            <div>
                <div class="db-eyebrow">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Ringkasan Pengadaan
                </div>
                <h1 class="db-page-title">Dashboard Sub Unit</h1>
                <p class="db-page-desc">Kelola, ajukan, dan pantau status usulan pengadaan unit Anda secara real-time.</p>
            </div>
            <a href="<?= site_url('sub-unit/usulan/create') ?>" class="db-btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Buat Usulan Baru
            </a>
        </div>

        <?php
            $total    = (int)($totalUsulan   ?? 0);
            $draft    = (int)($totalDraft    ?? 0);
            $diajukan = (int)($totalDiajukan ?? 0);
            $selesai  = (int)($totalSelesai  ?? 0);
            $pctDraft   = $total > 0 ? round($draft/$total*100)    : 0;
            $pctAjukan  = $total > 0 ? round($diajukan/$total*100) : 0;
            $pctSelesai = $total > 0 ? round($selesai/$total*100)  : 0;
        ?>

        <div class="db-stats-grid">
            <div class="db-stat s-total">
                <div class="db-stat-ico"><i class="bi bi-clipboard2-data"></i></div>
                <div class="db-stat-deco"><i class="bi bi-clipboard2-data"></i></div>
                <div class="db-stat-lbl">Total Usulan</div>
                <div class="db-stat-val"><?= $total ?></div>
                <div class="db-stat-note">Seluruh periode aktif</div>
            </div>
            <div class="db-stat s-draft">
                <div class="db-stat-ico"><i class="bi bi-pencil-square"></i></div>
                <div class="db-stat-deco"><i class="bi bi-pencil-square"></i></div>
                <div class="db-stat-lbl">Draft</div>
                <div class="db-stat-val"><?= $draft ?></div>
                <div class="db-stat-note">Belum diajukan</div>
            </div>
            <div class="db-stat s-ajukan">
                <div class="db-stat-ico"><i class="bi bi-send"></i></div>
                <div class="db-stat-deco"><i class="bi bi-send"></i></div>
                <div class="db-stat-lbl">Diajukan</div>
                <div class="db-stat-val"><?= $diajukan ?></div>
                <div class="db-stat-note">Menunggu persetujuan</div>
            </div>
            <div class="db-stat s-selesai">
                <div class="db-stat-ico"><i class="bi bi-patch-check"></i></div>
                <div class="db-stat-deco"><i class="bi bi-patch-check"></i></div>
                <div class="db-stat-lbl">Selesai</div>
                <div class="db-stat-val"><?= $selesai ?></div>
                <div class="db-stat-note">Telah diproses tuntas</div>
            </div>
        </div>

        <div class="db-two-col">

            <div class="db-prog-card">
                <div class="db-card-title">
                    <i class="bi bi-bar-chart-line"></i>
                    Proporsi Status Usulan
                </div>
                <div class="db-prog-row">
                    <div class="db-prog-name">Draft</div>
                    <div class="db-prog-track">
                        <div class="db-prog-fill pf-draft" style="width:<?= $pctDraft ?>%"></div>
                    </div>
                    <div class="db-prog-pct"><?= $pctDraft ?>%</div>
                </div>
                <div class="db-prog-row">
                    <div class="db-prog-name">Diajukan</div>
                    <div class="db-prog-track">
                        <div class="db-prog-fill pf-ajukan" style="width:<?= $pctAjukan ?>%"></div>
                    </div>
                    <div class="db-prog-pct"><?= $pctAjukan ?>%</div>
                </div>
                <div class="db-prog-row">
                    <div class="db-prog-name">Selesai</div>
                    <div class="db-prog-track">
                        <div class="db-prog-fill pf-selesai" style="width:<?= $pctSelesai ?>%"></div>
                    </div>
                    <div class="db-prog-pct"><?= $pctSelesai ?>%</div>
                </div>
            </div>

            <div class="db-tl-card">
                <div class="db-card-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Alur Proses Pengadaan
                </div>
                <div class="db-timeline">
                    <div class="db-tl-step done">
                        <div class="db-tl-dot"><i class="bi bi-pencil"></i></div>
                        <div class="db-tl-lbl">Draft</div>
                    </div>
                    <div class="db-tl-step done">
                        <div class="db-tl-dot"><i class="bi bi-send"></i></div>
                        <div class="db-tl-lbl">Diajukan</div>
                    </div>
                    <div class="db-tl-step current">
                        <div class="db-tl-dot"><i class="bi bi-search"></i></div>
                        <div class="db-tl-lbl">Verifikasi</div>
                    </div>
                    <div class="db-tl-step">
                        <div class="db-tl-dot"><i class="bi bi-bar-chart"></i></div>
                        <div class="db-tl-lbl">MOORA</div>
                    </div>
                    <div class="db-tl-step">
                        <div class="db-tl-dot"><i class="bi bi-shield-check"></i></div>
                        <div class="db-tl-lbl">Validasi</div>
                    </div>
                    <div class="db-tl-step">
                        <div class="db-tl-dot"><i class="bi bi-patch-check"></i></div>
                        <div class="db-tl-lbl">Selesai</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="db-quick-row">
            <a href="<?= site_url('sub-unit/usulan/create') ?>" class="db-qcard qc-buat">
                <div class="db-qcard-ico"><i class="bi bi-plus-circle-fill"></i></div>
                <div>
                    <div class="db-qcard-title">Buat Usulan Baru</div>
                    <div class="db-qcard-desc">Ajukan kebutuhan pengadaan barang unit Anda</div>
                </div>
                <div class="db-qcard-arr"><i class="bi bi-arrow-right"></i></div>
            </a>
            <a href="<?= site_url('sub-unit/usulan') ?>" class="db-qcard qc-pantau">
                <div class="db-qcard-ico"><i class="bi bi-clipboard-check-fill"></i></div>
                <div>
                    <div class="db-qcard-title">Pantau Semua Usulan</div>
                    <div class="db-qcard-desc">Lihat riwayat dan status seluruh pengajuan</div>
                </div>
                <div class="db-qcard-arr"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>

        <div class="db-tbl-card">
            <div class="db-tbl-header">
                <div class="db-tbl-header-left">
                    <div class="db-tbl-header-ico"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h5 class="db-tbl-title">Usulan Terbaru</h5>
                        <p class="db-tbl-desc">5 usulan pengadaan terbaru dari sub unit Anda</p>
                    </div>
                </div>
                <a href="<?= site_url('sub-unit/usulan') ?>" class="db-btn-outline">
                    <i class="bi bi-list-ul"></i> Lihat Semua
                </a>
            </div>

            <div class="db-filter-bar">
                <span class="db-filter-lbl">Filter:</span>
                <button class="db-chip active" onclick="filterTbl(this,'semua')">Semua</button>
                <button class="db-chip" onclick="filterTbl(this,'draft')">Draft</button>
                <button class="db-chip" onclick="filterTbl(this,'diajukan')">Diajukan</button>
                <button class="db-chip" onclick="filterTbl(this,'diverifikasi')">Diverifikasi</button>
                <button class="db-chip" onclick="filterTbl(this,'ditolak')">Ditolak</button>
                <button class="db-chip" onclick="filterTbl(this,'banding')">Banding Gudang</button>
            </div>

            <div class="db-tbl-wrap">
                <table class="db-table" id="tblUsulan">
                    <thead>
                        <tr>
                            <th class="tc">No</th>
                            <th>Nomor Usulan</th>
                            <th>Tanggal</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th class="tc">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usulanTerbaru)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($usulanTerbaru as $row) : ?>
                                <?php
                                $status = strtolower(trim($row['status'] ?? 'draft'));

                                $badgeCls = match(true) {
                                    str_contains($status,'diverifikasi') => 'b-diverifikasi',
                                    str_contains($status,'ditolak')      => 'b-ditolak',
                                    str_contains($status,'banding')      => 'b-banding',
                                    str_contains($status,'selesai')      => 'b-selesai',
                                    str_contains($status,'diajukan')     => 'b-diajukan',
                                    default                              => 'b-draft',
                                };

                                $badgeIco = match(true) {
                                    str_contains($status,'diverifikasi') => 'bi-check-circle',
                                    str_contains($status,'ditolak')      => 'bi-x-circle',
                                    str_contains($status,'banding')      => 'bi-arrow-repeat',
                                    str_contains($status,'selesai')      => 'bi-patch-check',
                                    str_contains($status,'diajukan')     => 'bi-send',
                                    default                              => 'bi-pencil',
                                };

                                $badgeLbl = ucwords(str_replace('_',' ', $row['status'] ?? 'draft'));

                                $fKey = match(true) {
                                    str_contains($status,'diverifikasi') => 'diverifikasi',
                                    str_contains($status,'ditolak')      => 'ditolak',
                                    str_contains($status,'banding')      => 'banding',
                                    str_contains($status,'selesai')      => 'selesai',
                                    str_contains($status,'diajukan')     => 'diajukan',
                                    default                              => 'draft',
                                };
                                ?>
                                <tr data-status="<?= esc($fKey) ?>">
                                    <td class="td-no"><?= $no++ ?></td>
                                    <td>
                                        <span class="td-nomor"><?= esc($row['nomor_usulan'] ?? '-') ?></span>
                                        <span class="td-nomor-sub">ID: #<?= esc($row['id'] ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span class="td-tgl">
                                            <i class="bi bi-calendar3"></i>
                                            <?= esc($row['tanggal_usulan'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="td-unit"><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                                    <td>
                                        <span class="spk-badge <?= $badgeCls ?>">
                                            <i class="bi <?= $badgeIco ?>"></i>
                                            <?= esc($badgeLbl) ?>
                                        </span>
                                    </td>
                                    <td class="tc">
                                        <a href="<?= site_url('sub-unit/usulan/detail/'.$row['id']) ?>"
                                           class="db-btn-detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">
                                    <div class="db-empty">
                                        <i class="bi bi-inbox db-empty-ico"></i>
                                        <div class="db-empty-ttl">Belum Ada Usulan</div>
                                        <p class="db-empty-dsc">Sub unit Anda belum memiliki usulan pengadaan.</p>
                                        <a href="<?= site_url('sub-unit/usulan/create') ?>"
                                           class="db-btn-primary"
                                           style="margin:0 auto;display:inline-flex;">
                                            <i class="bi bi-plus-circle-fill"></i> Buat Usulan Pertama
                                        </a>
                                    </div>
                                annual log
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="db-tbl-footer">
                <div class="db-tbl-footer-info">
                    Menampilkan <strong id="rowCount"><?= count($usulanTerbaru ?? []) ?></strong>
                    dari <strong><?= $total ?></strong> total usulan
                </div>
                <a href="<?= site_url('sub-unit/usulan') ?>" class="db-btn-outline"
                   style="font-size:12px;padding:5px 13px;">
                    <i class="bi bi-arrow-right-circle"></i> Kelola Semua Usulan
                </a>
            </div>
        </div>

    </div></div><script>
function filterTbl(btn, status) {
    document.querySelectorAll('.db-chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    const rows = document.querySelectorAll('#tblUsulan tbody tr[data-status]');
    let n = 0;
    rows.forEach(r => {
        const ok = status === 'semua' || r.dataset.status === status;
        r.style.display = ok ? '' : 'none';
        if (ok) n++;
    });
    document.getElementById('rowCount').textContent = n;
}
</script>

<?= $this->endSection() ?>