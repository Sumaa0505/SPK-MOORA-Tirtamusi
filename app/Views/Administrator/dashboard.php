<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
if (! function_exists('angkaAdmin')) {
    function angkaAdmin($value)
    {
        return number_format((int) ($value ?? 0), 0, ',', '.');
    }
}

$jumlahKriteriaSafe    = (int) ($jumlahKriteria ?? 0);
$jumlahAlternatifSafe  = (int) ($jumlahAlternatif ?? 0);
$jumlahUsulanSafe      = (int) ($jumlahUsulan ?? 0);
$jumlahHasilSafe       = (int) ($jumlahHasil ?? 0);

$jumlahUserAktifSafe   = (int) ($jumlahUserAktif ?? 0);
$jumlahUserPendingSafe = (int) ($jumlahUserPending ?? 0);
$jumlahUserApproveSafe = (int) ($jumlahUserApprove ?? 0);
$jumlahApprovalLogSafe = (int) ($jumlahApprovalLog ?? 0);

$usulanDiprosesSafe    = (int) ($usulanDiproses ?? 0);
$usulanDisetujuiSafe   = (int) ($usulanDisetujui ?? 0);
$usulanDitolakSafe     = (int) ($usulanDitolak ?? 0);

$persenDiproses  = $jumlahUsulanSafe > 0 ? round(($usulanDiprosesSafe / $jumlahUsulanSafe) * 100) : 0;
$persenDisetujui = $jumlahUsulanSafe > 0 ? round(($usulanDisetujuiSafe / $jumlahUsulanSafe) * 100) : 0;
$persenDitolak   = $jumlahUsulanSafe > 0 ? round(($usulanDitolakSafe / $jumlahUsulanSafe) * 100) : 0;
?>

<style>
/* =========================================================
   DASHBOARD ADMINISTRATOR — ADAPTIVE THEME FINAL
========================================================= */

:root {
  --adm-font: "Inter", "Segoe UI", Roboto, Arial, sans-serif;

  --adm-navy: #0F1F3D;
  --adm-navy-2: #1A2F52;
  --adm-navy-3: #243B6B;

  --adm-teal: #00C9AE;
  --adm-teal-dark: #009E87;
  --adm-blue: #3B82F6;
  --adm-green: #10B981;
  --adm-amber: #F59E0B;
  --adm-amber-dark: #D97706;
  --adm-rose: #F43F5E;
  --adm-purple: #8B5CF6;

  --adm-radius: 12px;
  --adm-radius-lg: 16px;
}

/* ================= LIGHT MODE DEFAULT ================= */

#admWrap {
  --adm-bg: #F4F7FB;
  --adm-surface: #FFFFFF;
  --adm-surface-2: #F8FAFC;
  --adm-text: #0F172A;
  --adm-muted: #475569;
  --adm-line: #E2E8F0;

  --adm-teal-bg: #E6FBF8;
  --adm-blue-bg: #EFF6FF;
  --adm-green-bg: #ECFDF5;
  --adm-amber-bg: #FFFBEB;
  --adm-rose-bg: #FFF1F2;
  --adm-purple-bg: #F5F3FF;

  --adm-shadow: 0 1px 3px rgba(15, 23, 42, .08), 0 8px 22px rgba(15, 23, 42, .06);
  --adm-shadow-hover: 0 8px 26px rgba(15, 23, 42, .14);

  --hero-title: #FFFFFF;
  --hero-subtitle: #E5F0FF;
  --hero-muted: #BFD3F0;
  --hero-bg-1: #0B1D3A;
  --hero-bg-2: #183B73;
}

/* ================= DARK MODE ================= */

body.dark-mode #admWrap,
html.dark #admWrap,
html[data-theme="dark"] #admWrap,
html[data-bs-theme="dark"] #admWrap,
body[data-theme="dark"] #admWrap,
#admWrap[data-theme="dark"] {
  --adm-bg: #0B1220;
  --adm-surface: #111D30;
  --adm-surface-2: #0F1A2B;
  --adm-text: #EAF2FF;
  --adm-muted: #A8B8D0;
  --adm-line: #263954;

  --adm-teal-bg: #04221D;
  --adm-blue-bg: #10233F;
  --adm-green-bg: #062416;
  --adm-amber-bg: #2B1B03;
  --adm-rose-bg: #2A0812;
  --adm-purple-bg: #1B1235;

  --adm-shadow: 0 1px 4px rgba(0, 0, 0, .35), 0 8px 28px rgba(0, 0, 0, .30);
  --adm-shadow-hover: 0 10px 32px rgba(0, 0, 0, .48);

  --hero-title: #FFFFFF;
  --hero-subtitle: #EAF2FF;
  --hero-muted: #C8D8EF;
  --hero-bg-1: #07172F;
  --hero-bg-2: #14376F;
}

/* ================= BASE ================= */

#admWrap,
#admWrap *,
#admWrap *::before,
#admWrap *::after {
  box-sizing: border-box;
}

#admWrap {
  font-family: var(--adm-font);
}

#admWrap .bi {
  font-family: "bootstrap-icons" !important;
}

.adm-wrap {
  padding: 1.25rem;
  background: var(--adm-bg) !important;
  color: var(--adm-text) !important;
  min-height: 100vh;
  transition: background .25s ease, color .25s ease;
}

/* ================= HEADER HERO ================= */

.adm-header {
  background:
    radial-gradient(circle at 78% 20%, rgba(0, 201, 174, .18) 0, rgba(0, 201, 174, .18) 90px, transparent 91px),
    radial-gradient(circle at 96% 65%, rgba(255, 255, 255, .12) 0, rgba(255, 255, 255, .12) 65px, transparent 66px),
    linear-gradient(135deg, var(--hero-bg-1) 0%, var(--hero-bg-2) 100%) !important;
  border-radius: var(--adm-radius-lg);
  padding: 1.25rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: .85rem;
  margin-bottom: 1.45rem;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, .14);
  box-shadow: 0 12px 28px rgba(8, 20, 45, .22);
}

.adm-header h2 {
  color: var(--hero-title) !important;
  font-size: 1.22rem;
  font-weight: 800;
  margin: 0 0 .22rem;
  letter-spacing: -.35px;
  opacity: 1 !important;
  text-shadow: 0 1px 2px rgba(0, 0, 0, .38);
}

.adm-header h2 i {
  color: var(--hero-title) !important;
}

.adm-header p {
  color: var(--hero-subtitle) !important;
  font-size: .74rem;
  font-weight: 500;
  margin: 0;
  opacity: 1 !important;
}

.hdr-right {
  display: flex;
  align-items: center;
  gap: .6rem;
  flex-wrap: wrap;
  position: relative;
  z-index: 2;
}

.adm-pill-online {
  display: inline-flex;
  align-items: center;
  gap: .38rem;
  background: rgba(0, 201, 174, .20) !important;
  border: 1px solid rgba(0, 201, 174, .55) !important;
  color: #7FFFEF !important;
  font-size: .68rem;
  font-weight: 800;
  padding: .3rem .7rem;
  border-radius: 20px;
  margin-bottom: .5rem;
  position: relative;
  z-index: 2;
}

.adm-pill-online span {
  width: 7px;
  height: 7px;
  background: #25F4D0 !important;
  border-radius: 50%;
  display: inline-block;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .25; }
}

.adm-badge {
  background: #FBBF24 !important;
  color: #071833 !important;
  border: 1px solid rgba(255, 255, 255, .28) !important;
  box-shadow: 0 6px 14px rgba(251, 191, 36, .20);
  font-size: .68rem;
  font-weight: 900;
  padding: .38rem .88rem;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: .35rem;
  white-space: nowrap;
}

.adm-badge i {
  color: #071833 !important;
}

.theme-toggle-btn {
  background: rgba(255, 255, 255, .18) !important;
  border: 1px solid rgba(255, 255, 255, .35) !important;
  color: #FFFFFF !important;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: .95rem;
  transition: background .2s ease, transform .15s ease;
  flex-shrink: 0;
}

.theme-toggle-btn i {
  color: #FFFFFF !important;
}

.theme-toggle-btn:hover {
  background: rgba(255, 255, 255, .28) !important;
  transform: translateY(-1px);
}

/* ================= SECTION LABEL ================= */

.sec-label {
  font-size: .65rem;
  font-weight: 900;
  letter-spacing: .09em;
  text-transform: uppercase;
  color: var(--adm-muted) !important;
  margin: 1.25rem 0 .6rem;
}

/* ================= METRIC CARDS ================= */

.mc-grid {
  display: grid;
  gap: .75rem;
}

.mc-4 {
  grid-template-columns: repeat(4, minmax(0, 1fr));
}

.mc-3 {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

@media (max-width: 1100px) {
  .mc-4 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 700px) {
  .mc-4,
  .mc-3 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 480px) {
  .mc-4,
  .mc-3 {
    grid-template-columns: 1fr;
  }
}

.mc {
  background: var(--adm-surface) !important;
  color: var(--adm-text) !important;
  border-radius: var(--adm-radius);
  box-shadow: var(--adm-shadow);
  padding: .92rem 1rem;
  border-left: 3px solid transparent;
  display: flex;
  align-items: flex-start;
  gap: .78rem;
  transition: transform .15s ease, box-shadow .15s ease, background .25s ease;
}

.mc:hover {
  transform: translateY(-2px);
  box-shadow: var(--adm-shadow-hover);
}

.mc-icon {
  width: 38px;
  height: 38px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .98rem;
  flex-shrink: 0;
}

.mc-body {
  min-width: 0;
}

.mc-label {
  font-size: .69rem;
  color: var(--adm-muted) !important;
  margin: 0 0 .14rem;
  font-weight: 650;
  white-space: nowrap;
}

.mc-val {
  font-size: 1.55rem;
  font-weight: 900;
  line-height: 1;
  margin: 0 0 .14rem;
}

.mc-sub {
  font-size: .65rem;
  color: var(--adm-muted) !important;
  margin: 0;
}

.mc-trend {
  display: inline-flex;
  align-items: center;
  gap: .2rem;
  font-size: .62rem;
  font-weight: 800;
  padding: .13rem .42rem;
  border-radius: 10px;
  margin-top: .28rem;
}

.trend-up {
  background: #DCFCE7;
  color: #15803D;
}

.trend-dn {
  background: #FEE2E2;
  color: #B91C1C;
}

body.dark-mode #admWrap .trend-up,
#admWrap[data-theme="dark"] .trend-up {
  background: #052015;
  color: #4ADE80;
}

body.dark-mode #admWrap .trend-dn,
#admWrap[data-theme="dark"] .trend-dn {
  background: #2A0812;
  color: #FDA4AF;
}

.mc-teal {
  border-color: var(--adm-teal);
}

.mc-teal .mc-icon {
  background: var(--adm-teal-bg);
  color: var(--adm-teal-dark);
}

.mc-teal .mc-val {
  color: var(--adm-teal-dark);
}

.mc-blue {
  border-color: var(--adm-blue);
}

.mc-blue .mc-icon {
  background: var(--adm-blue-bg);
  color: var(--adm-blue);
}

.mc-blue .mc-val {
  color: var(--adm-blue);
}

.mc-amber {
  border-color: var(--adm-amber);
}

.mc-amber .mc-icon {
  background: var(--adm-amber-bg);
  color: var(--adm-amber-dark);
}

.mc-amber .mc-val {
  color: var(--adm-amber-dark);
}

.mc-green {
  border-color: var(--adm-green);
}

.mc-green .mc-icon {
  background: var(--adm-green-bg);
  color: var(--adm-green);
}

.mc-green .mc-val {
  color: var(--adm-green);
}

.mc-rose {
  border-color: var(--adm-rose);
}

.mc-rose .mc-icon {
  background: var(--adm-rose-bg);
  color: var(--adm-rose);
}

.mc-rose .mc-val {
  color: var(--adm-rose);
}

.mc-purple {
  border-color: var(--adm-purple);
}

.mc-purple .mc-icon {
  background: var(--adm-purple-bg);
  color: var(--adm-purple);
}

.mc-purple .mc-val {
  color: var(--adm-purple);
}

.mc-navy {
  border-color: var(--adm-navy);
}

.mc-navy .mc-icon {
  background: var(--adm-blue-bg);
  color: var(--adm-blue);
}

.mc-navy .mc-val {
  color: var(--adm-text) !important;
}

.mini-bar-wrap {
  background: var(--adm-line);
  border-radius: 20px;
  height: 5px;
  margin-top: .45rem;
  overflow: hidden;
}

.mini-bar-fill {
  height: 100%;
  border-radius: 20px;
  transition: width 1.2s ease;
}

/* ================= QUICK ACTIONS ================= */

.qa-bar {
  background: var(--adm-surface) !important;
  color: var(--adm-text) !important;
  border-radius: var(--adm-radius);
  box-shadow: var(--adm-shadow);
  padding: 1rem 1.25rem;
  margin-top: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: .75rem;
  transition: background .25s ease;
}

.qa-bar-left h6 {
  font-size: .86rem;
  font-weight: 850;
  color: var(--adm-text) !important;
  margin: 0 0 .13rem;
}

.qa-bar-left p {
  font-size: .7rem;
  color: var(--adm-muted) !important;
  margin: 0;
}

.qa-btns {
  display: flex;
  gap: .45rem;
  flex-wrap: wrap;
}

.qa-btn {
  display: inline-flex;
  align-items: center;
  gap: .32rem;
  padding: .42rem .86rem;
  border-radius: 7px;
  font-size: .72rem;
  font-weight: 750;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: filter .15s ease, transform .12s ease;
}

.qa-btn:hover {
  filter: brightness(1.08);
  transform: translateY(-1px);
  text-decoration: none;
}

.qa-btn-teal {
  background: var(--adm-teal);
  color: #06201C !important;
}

.qa-btn-green {
  background: var(--adm-green);
  color: #FFFFFF !important;
}

.qa-btn-blue {
  background: var(--adm-blue);
  color: #FFFFFF !important;
}

.qa-btn-amber {
  background: #FBBF24;
  color: #071833 !important;
}

.qa-btn-navy {
  background: var(--adm-navy);
  color: #FFFFFF !important;
}

.qa-btn-rose {
  background: var(--adm-rose);
  color: #FFFFFF !important;
}

/* ================= BOTTOM LAYOUT ================= */

.bottom-row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 280px;
  gap: .75rem;
  margin-top: .75rem;
}

@media (max-width: 900px) {
  .bottom-row {
    grid-template-columns: 1fr;
  }
}

.main-col {
  display: flex;
  flex-direction: column;
  gap: .75rem;
}

/* ================= CARD PANEL ================= */

.card-panel {
  background: var(--adm-surface) !important;
  color: var(--adm-text) !important;
  border-radius: var(--adm-radius);
  box-shadow: var(--adm-shadow);
  transition: background .25s ease, color .25s ease;
}

.card-panel-head {
  padding: .86rem 1.1rem;
  border-bottom: 1px solid var(--adm-line) !important;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .75rem;
}

.card-panel-head h6 {
  font-size: .83rem;
  font-weight: 850;
  color: var(--adm-text) !important;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .42rem;
}

.card-panel-body {
  padding: .72rem 1rem;
}

/* ================= RANKING TABLE ================= */

.rank-table {
  width: 100%;
  border-collapse: collapse;
  font-size: .76rem;
}

.rank-table th {
  color: var(--adm-muted) !important;
  font-weight: 800;
  font-size: .63rem;
  text-transform: uppercase;
  letter-spacing: .06em;
  padding: .42rem .55rem;
  border-bottom: 2px solid var(--adm-line) !important;
  white-space: nowrap;
  text-align: left;
}

.rank-table td {
  padding: .5rem .55rem;
  border-bottom: 1px solid var(--adm-line) !important;
  color: var(--adm-text) !important;
  vertical-align: middle;
}

.rank-table tr:last-child td {
  border-bottom: none !important;
}

.rank-table tbody tr {
  transition: background .12s ease;
}

.rank-table tbody tr:hover {
  background: rgba(59, 130, 246, .08) !important;
}

.rank-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  font-size: .63rem;
  font-weight: 900;
}

.rank-1 {
  background: #FEF3C7;
  color: #92400E;
}

.rank-2 {
  background: #E2E8F0;
  color: #475569;
}

.rank-3 {
  background: #FEE2E2;
  color: #991B1B;
}

.rank-n {
  background: #EDE9FE;
  color: #5B21B6;
}

body.dark-mode #admWrap .rank-1,
#admWrap[data-theme="dark"] .rank-1 {
  background: #2A1F00;
  color: #FCD34D;
}

body.dark-mode #admWrap .rank-2,
#admWrap[data-theme="dark"] .rank-2 {
  background: #1E2D45;
  color: #CBD5E1;
}

body.dark-mode #admWrap .rank-3,
#admWrap[data-theme="dark"] .rank-3 {
  background: #2A0812;
  color: #FDA4AF;
}

body.dark-mode #admWrap .rank-n,
#admWrap[data-theme="dark"] .rank-n {
  background: #1E1040;
  color: #C4B5FD;
}

.yi-val {
  font-family: "Courier New", monospace;
  font-size: .72rem;
  font-weight: 900;
  color: var(--adm-teal-dark);
}

body.dark-mode #admWrap .yi-val,
#admWrap[data-theme="dark"] .yi-val {
  color: var(--adm-teal);
}

/* ================= PROGRESS ================= */

.prog-row {
  display: flex;
  align-items: center;
  gap: .5rem;
  margin-bottom: .48rem;
  font-size: .71rem;
}

.prog-row:last-child {
  margin-bottom: 0;
}

.prog-label {
  width: 82px;
  color: var(--adm-muted) !important;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.prog-bar-wrap {
  flex: 1;
  background: var(--adm-line);
  border-radius: 20px;
  height: 7px;
  overflow: hidden;
}

.prog-bar {
  height: 100%;
  border-radius: 20px;
  transition: width 1s ease;
}

.prog-val {
  width: 32px;
  text-align: right;
  font-weight: 850;
  color: var(--adm-text) !important;
}

/* ================= ACTIVITY LOG ================= */

.activity-item {
  display: flex;
  gap: .6rem;
  padding: .47rem 0;
  border-bottom: 1px solid var(--adm-line) !important;
  font-size: .73rem;
}

.activity-item:last-child {
  border-bottom: none !important;
}

.act-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
  margin-top: .3rem;
}

.act-text {
  color: var(--adm-text) !important;
  line-height: 1.4;
  font-weight: 550;
}

.act-time {
  color: var(--adm-muted) !important;
  font-size: .64rem;
  margin-top: .1rem;
}

/* ================= SIDE PANEL ================= */

.side-stack {
  display: flex;
  flex-direction: column;
  gap: .75rem;
}

.kpi-pair {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: .55rem;
  margin-bottom: .6rem;
}

.kpi-box {
  background: var(--adm-surface-2) !important;
  border-radius: 9px;
  padding: .55rem .7rem;
  text-align: center;
  border: 1px solid var(--adm-line);
}

.kpi-box .kv {
  font-size: 1.12rem;
  font-weight: 900;
  line-height: 1;
}

.kpi-box .kl {
  font-size: .61rem;
  color: var(--adm-muted) !important;
  margin-top: .22rem;
}

.status-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: .75rem;
  padding: .45rem 0;
  border-bottom: 1px solid var(--adm-line) !important;
  font-size: .76rem;
}

.status-row:last-child {
  border-bottom: none !important;
}

.status-row span {
  color: var(--adm-muted) !important;
}

.status-row strong {
  font-weight: 850;
  color: var(--adm-text) !important;
}

.badge-on {
  background: #DCFCE7 !important;
  color: #15803D !important;
  font-size: .62rem;
  padding: .15rem .5rem;
  border-radius: 10px;
  font-weight: 850;
}

.badge-role {
  background: #EDE9FE !important;
  color: #5B21B6 !important;
  font-size: .62rem;
  padding: .15rem .5rem;
  border-radius: 10px;
  font-weight: 850;
}

body.dark-mode #admWrap .badge-on,
#admWrap[data-theme="dark"] .badge-on {
  background: #052015 !important;
  color: #4ADE80 !important;
}

body.dark-mode #admWrap .badge-role,
#admWrap[data-theme="dark"] .badge-role {
  background: #1E1040 !important;
  color: #C4B5FD !important;
}

/* ================= FLOW ================= */

.flow {
  display: flex;
  align-items: center;
  gap: 0;
  flex-wrap: wrap;
  margin-top: .4rem;
}

.flow-node {
  border: 1px solid var(--adm-line);
  border-radius: 7px;
  padding: .23rem .52rem;
  font-size: .64rem;
  font-weight: 850;
  white-space: nowrap;
}

.flow-arr {
  color: var(--adm-muted) !important;
  font-size: .72rem;
  padding: 0 .24rem;
}

/* ================= MINI CALENDAR ================= */

.mini-cal-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
  text-align: center;
}

.cal-day-lbl {
  font-size: .59rem;
  color: var(--adm-muted) !important;
  padding: .14rem;
  font-weight: 850;
}

.cal-day {
  font-size: .61rem;
  padding: .18rem;
  border-radius: 4px;
  color: var(--adm-text) !important;
}

.cal-today {
  background: var(--adm-teal) !important;
  color: #FFFFFF !important;
  font-weight: 900;
}

.cal-event {
  border: 1px solid var(--adm-blue);
  color: var(--adm-blue) !important;
}

.cal-legend {
  display: flex;
  gap: .75rem;
  margin-top: .55rem;
}

.cal-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  display: inline-block;
}

/* ================= ANIMATION ================= */

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(8px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.mc {
  animation: fadeUp .35s ease both;
}

.card-panel {
  animation: fadeUp .4s ease both;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 768px) {
  .adm-wrap {
    padding: .9rem;
  }

  .adm-header {
    padding: 1.1rem 1rem;
  }

  .adm-header h2 {
    font-size: 1.05rem !important;
  }

  .adm-header p {
    font-size: .72rem !important;
    line-height: 1.45;
  }

  .hdr-right {
    width: 100%;
    justify-content: space-between;
  }

  .qa-bar {
    padding: .9rem;
  }

  .card-panel-head {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>

<div class="adm-wrap" id="admWrap">

  <!-- HEADER -->
  <div class="adm-header">
    <div>
      <div class="adm-pill-online">
        <span></span> Sistem Online
      </div>

      <h2>
        <i class="bi bi-speedometer2 me-2"></i>Dashboard Administrator
      </h2>

      <p>
        SPK MOORA PDAM — Monitoring pengadaan barang, MOORA, dan manajemen pengguna.
      </p>
    </div>

    <div class="hdr-right">
      <div class="adm-badge">
        <i class="bi bi-award-fill"></i> SISTEM MOORA PDAM v2.0
      </div>

      <button
        type="button"
        class="theme-toggle-btn"
        id="themeToggleBtn"
        onclick="toggleDashboardTheme()"
        title="Ganti mode tampilan"
      >
        <i class="bi bi-moon-fill" id="themeIcon"></i>
      </button>
    </div>
  </div>

  <!-- STATISTIK MOORA -->
  <p class="sec-label">
    <i class="bi bi-bar-chart-fill me-1"></i>Statistik MOORA
  </p>

  <div class="mc-grid mc-4">

    <div class="mc mc-teal">
      <div class="mc-icon">
        <i class="bi bi-list-check"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Total Kriteria</p>
        <p class="mc-val"><?= angkaAdmin($jumlahKriteriaSafe) ?></p>
        <p class="mc-sub">Kriteria aktif sistem</p>
        <span class="mc-trend trend-up">↑ 2 baru bulan ini</span>
      </div>
    </div>

    <div class="mc mc-blue">
      <div class="mc-icon">
        <i class="bi bi-boxes"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Total Alternatif</p>
        <p class="mc-val"><?= angkaAdmin($jumlahAlternatifSafe) ?></p>
        <p class="mc-sub">Barang terdaftar</p>
        <span class="mc-trend trend-up">↑ +12 dari kemarin</span>
      </div>
    </div>

    <div class="mc mc-amber">
      <div class="mc-icon">
        <i class="bi bi-file-earmark-text"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Total Usulan</p>
        <p class="mc-val"><?= angkaAdmin($jumlahUsulanSafe) ?></p>
        <p class="mc-sub">Semua periode</p>
        <span class="mc-trend trend-up">↑ +5 minggu ini</span>
      </div>
    </div>

    <div class="mc mc-green">
      <div class="mc-icon">
        <i class="bi bi-graph-up-arrow"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Hasil MOORA</p>
        <p class="mc-val"><?= angkaAdmin($jumlahHasilSafe) ?></p>
        <p class="mc-sub">Kalkulasi selesai</p>
        <span class="mc-trend trend-up">↑ Terbaru hari ini</span>
      </div>
    </div>

  </div>

  <!-- MANAJEMEN PENGGUNA -->
  <p class="sec-label">
    <i class="bi bi-people-fill me-1"></i>Manajemen Pengguna
  </p>

  <div class="mc-grid mc-4">

    <div class="mc mc-green">
      <div class="mc-icon">
        <i class="bi bi-person-check"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">User Aktif</p>
        <p class="mc-val"><?= angkaAdmin($jumlahUserAktifSafe) ?></p>
        <p class="mc-sub">Dapat login sekarang</p>
        <span class="mc-trend trend-up">↑ Semua verified</span>
      </div>
    </div>

    <div class="mc mc-amber">
      <div class="mc-icon">
        <i class="bi bi-person-exclamation"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">User Pending</p>
        <p class="mc-val"><?= angkaAdmin($jumlahUserPendingSafe) ?></p>
        <p class="mc-sub">Menunggu verifikasi</p>

        <?php if ($jumlahUserPendingSafe > 0): ?>
          <span class="mc-trend trend-dn">⚠ Perlu perhatian</span>
        <?php else: ?>
          <span class="mc-trend trend-up">✓ Tidak ada antrian</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="mc mc-blue">
      <div class="mc-icon">
        <i class="bi bi-person-fill-check"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">User Disetujui</p>
        <p class="mc-val"><?= angkaAdmin($jumlahUserApproveSafe) ?></p>
        <p class="mc-sub">Total pernah approve</p>
        <span class="mc-trend trend-up">↑ +3 bulan ini</span>
      </div>
    </div>

    <div class="mc mc-navy">
      <div class="mc-icon">
        <i class="bi bi-journal-check"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Approval Log</p>
        <p class="mc-val"><?= angkaAdmin($jumlahApprovalLogSafe) ?></p>
        <p class="mc-sub">Riwayat tindakan</p>
        <span class="mc-trend trend-up">↑ Updated realtime</span>
      </div>
    </div>

  </div>

  <!-- STATUS USULAN -->
  <p class="sec-label">
    <i class="bi bi-clipboard2-data me-1"></i>Status Usulan
  </p>

  <div class="mc-grid mc-3">

    <div class="mc mc-blue">
      <div class="mc-icon">
        <i class="bi bi-arrow-repeat"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Diproses</p>
        <p class="mc-val"><?= angkaAdmin($usulanDiprosesSafe) ?></p>
        <p class="mc-sub">Sedang dikerjakan</p>

        <div class="mini-bar-wrap">
          <div
            class="mini-bar-fill"
            style="width: <?= $persenDiproses ?>%; background: var(--adm-blue);"
          ></div>
        </div>
      </div>
    </div>

    <div class="mc mc-green">
      <div class="mc-icon">
        <i class="bi bi-check2-circle"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Disetujui</p>
        <p class="mc-val"><?= angkaAdmin($usulanDisetujuiSafe) ?></p>
        <p class="mc-sub">Usulan diterima</p>

        <div class="mini-bar-wrap">
          <div
            class="mini-bar-fill"
            style="width: <?= $persenDisetujui ?>%; background: var(--adm-green);"
          ></div>
        </div>
      </div>
    </div>

    <div class="mc mc-rose">
      <div class="mc-icon">
        <i class="bi bi-x-circle"></i>
      </div>

      <div class="mc-body">
        <p class="mc-label">Ditolak</p>
        <p class="mc-val"><?= angkaAdmin($usulanDitolakSafe) ?></p>
        <p class="mc-sub">Tidak diproses</p>

        <div class="mini-bar-wrap">
          <div
            class="mini-bar-fill"
            style="width: <?= $persenDitolak ?>%; background: var(--adm-rose);"
          ></div>
        </div>
      </div>
    </div>

  </div>

  <!-- AKSI CEPAT -->
  <div class="qa-bar">
    <div class="qa-bar-left">
      <h6>
        <i class="bi bi-lightning-fill me-1" style="color: var(--adm-amber);"></i>Aksi Cepat
      </h6>
      <p>Navigasi langsung ke modul utama sistem.</p>
    </div>

    <div class="qa-btns">
      <a href="<?= site_url('administrator/kriteria') ?>" class="qa-btn qa-btn-teal">
        <i class="bi bi-list-check"></i> Kriteria
      </a>

      <a href="<?= site_url('administrator/alternatif') ?>" class="qa-btn qa-btn-green">
        <i class="bi bi-boxes"></i> Alternatif
      </a>

      <a href="<?= site_url('administrator/monitoring') ?>" class="qa-btn qa-btn-blue">
        <i class="bi bi-activity"></i> Monitoring
      </a>

      <a href="<?= site_url('administrator/registrasi') ?>" class="qa-btn qa-btn-amber">
        <i class="bi bi-person-fill-add"></i> Approval User
      </a>

      <a href="<?= site_url('administrator/moora') ?>" class="qa-btn qa-btn-rose">
        <i class="bi bi-graph-up-arrow"></i> Kalkulasi MOORA
      </a>

      <a href="<?= site_url('administrator/setting') ?>" class="qa-btn qa-btn-navy">
        <i class="bi bi-gear-fill"></i> Pengaturan
      </a>
    </div>
  </div>

  <!-- BOTTOM ROW -->
  <div class="bottom-row">

    <!-- KOLOM KIRI -->
    <div class="main-col">

      <!-- RANKING MOORA -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-trophy-fill" style="color: var(--adm-amber);"></i>
            Ranking MOORA Terbaru
          </h6>

          <span style="font-size: .68rem; color: var(--adm-muted);">
            Nilai Yi ↑ = prioritas tertinggi
          </span>
        </div>

        <div class="card-panel-body">
          <div class="table-responsive">
            <table class="rank-table">
              <thead>
                <tr>
                  <th style="width: 44px; text-align: center;">Rank</th>
                  <th>Kode</th>
                  <th>Nama Alternatif</th>
                  <th style="text-align: right;">Nilai Yi</th>
                  <th>Tanggal</th>
                </tr>
              </thead>

              <tbody>
                <?php if (! empty($rankingTerbaru)): ?>
                  <?php foreach ($rankingTerbaru as $row): ?>
                    <?php
                      $ranking = (int) ($row['ranking'] ?? 0);

                      if ($ranking === 1) {
                          $rankClass = 'rank-1';
                      } elseif ($ranking === 2) {
                          $rankClass = 'rank-2';
                      } elseif ($ranking === 3) {
                          $rankClass = 'rank-3';
                      } else {
                          $rankClass = 'rank-n';
                      }
                    ?>

                    <tr>
                      <td style="text-align: center;">
                        <span class="rank-badge <?= esc($rankClass) ?>">
                          <?= esc($row['ranking'] ?? '-') ?>
                        </span>
                      </td>

                      <td style="font-weight: 750; font-size: .68rem; color: var(--adm-muted) !important;">
                        <?= esc($row['kode_alternatif'] ?? '-') ?>
                      </td>

                      <td>
                        <?= esc($row['nama_alternatif'] ?? '-') ?>
                      </td>

                      <td style="text-align: right;">
                        <span class="yi-val">
                          <?= number_format((float) ($row['nilai_yi'] ?? 0), 6) ?>
                        </span>
                      </td>

                      <td style="color: var(--adm-muted) !important; font-size: .68rem;">
                        <?= esc($row['tanggal_hitung'] ?? '—') ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" style="text-align: center; color: var(--adm-muted) !important; padding: 2rem; font-size: .8rem;">
                      <i class="bi bi-inbox d-block mb-1" style="font-size: 1.5rem;"></i>
                      Belum ada data MOORA. Jalankan kalkulasi terlebih dahulu.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- BAR CHART TOP 5 -->
      <?php if (! empty($rankingTerbaru)): ?>
        <div class="card-panel">
          <div class="card-panel-head">
            <h6>
              <i class="bi bi-bar-chart-fill" style="color: var(--adm-blue);"></i>
              Distribusi Nilai Yi (Top 5)
            </h6>

            <span style="font-size: .68rem; color: var(--adm-muted);">
              Visualisasi komparatif
            </span>
          </div>

          <div class="card-panel-body">
            <?php
              $top5  = array_slice($rankingTerbaru, 0, 5);
              $maxYi = ! empty($top5) ? (float) ($top5[0]['nilai_yi'] ?? 1) : 1;

              if ($maxYi <= 0) {
                  $maxYi = 1;
              }

              $barColors = [
                  'var(--adm-teal)',
                  'var(--adm-blue)',
                  'var(--adm-purple)',
                  'var(--adm-amber)',
                  'var(--adm-green)',
              ];
            ?>

            <?php foreach ($top5 as $index => $barRow): ?>
              <?php
                $nilaiYi = (float) ($barRow['nilai_yi'] ?? 0);
                $percent = $maxYi > 0 ? round(($nilaiYi / $maxYi) * 100) : 0;
                $color   = $barColors[$index] ?? 'var(--adm-blue)';
              ?>

              <div class="prog-row">
                <span class="prog-label">
                  <?= esc($barRow['kode_alternatif'] ?? '-') ?>
                </span>

                <div class="prog-bar-wrap">
                  <div
                    class="prog-bar"
                    style="width: <?= $percent ?>%; background: <?= $color ?>;"
                  ></div>
                </div>

                <span class="prog-val">
                  <?= $percent ?>%
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- AKTIVITAS TERBARU -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-clock-history" style="color: var(--adm-purple);"></i>
            Aktivitas Terbaru
          </h6>

          <span style="font-size: .68rem; color: var(--adm-muted);">
            Log sistem hari ini
          </span>
        </div>

        <div class="card-panel-body">
          <?php
            $activities = $aktivitasTerbaru ?? [
                [
                    'dot'  => 'var(--adm-green)',
                    'text' => 'Kalkulasi MOORA selesai untuk periode bulan ini',
                    'time' => '2 menit lalu',
                ],
                [
                    'dot'  => 'var(--adm-amber)',
                    'text' => 'User baru menunggu verifikasi administrator',
                    'time' => '15 menit lalu',
                ],
                [
                    'dot'  => 'var(--adm-blue)',
                    'text' => 'Usulan baru diajukan oleh Sub Unit Distribusi',
                    'time' => '42 menit lalu',
                ],
                [
                    'dot'  => 'var(--adm-teal)',
                    'text' => 'Kriteria Harga Satuan diperbarui oleh admin',
                    'time' => '1 jam lalu',
                ],
                [
                    'dot'  => 'var(--adm-rose)',
                    'text' => 'Usulan ditolak — stok gudang mencukupi',
                    'time' => '3 jam lalu',
                ],
            ];
          ?>

          <?php foreach ($activities as $activity): ?>
            <div class="activity-item">
              <div
                class="act-dot"
                style="background: <?= esc($activity['dot'] ?? 'var(--adm-blue)') ?>;"
              ></div>

              <div>
                <div class="act-text">
                  <?= esc($activity['text'] ?? '-') ?>
                </div>

                <div class="act-time">
                  <?= esc($activity['time'] ?? '-') ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- SIDEBAR KANAN -->
    <div class="side-stack">

      <!-- STATUS SISTEM -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-shield-check" style="color: var(--adm-teal);"></i>
            Status Sistem
          </h6>
        </div>

        <div class="card-panel-body">
          <div class="kpi-pair">
            <div class="kpi-box">
              <div class="kv" style="color: var(--adm-green);">
                99.8%
              </div>
              <div class="kl">Uptime</div>
            </div>

            <div class="kpi-box">
              <div class="kv" style="color: var(--adm-blue);">
                12ms
              </div>
              <div class="kl">Latensi DB</div>
            </div>
          </div>

          <div class="status-row">
            <span>Sesi Login</span>
            <span class="badge-on">● Aktif</span>
          </div>

          <div class="status-row">
            <span>Role</span>
            <span class="badge-role">Administrator</span>
          </div>

          <div class="status-row">
            <span>Mesin MOORA</span>
            <span class="badge-on">● Running</span>
          </div>

          <div class="status-row">
            <span>Database</span>
            <span class="badge-on">● Connected</span>
          </div>

          <div class="status-row">
            <span>Cache Server</span>
            <span class="badge-on">● Warm</span>
          </div>

          <div class="status-row" style="border-bottom: none !important; padding-bottom: 0;">
            <span>Versi Sistem</span>
            <strong>v2.0</strong>
          </div>
        </div>
      </div>

      <!-- UTILISASI KRITERIA -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-fire" style="color: var(--adm-rose);"></i>
            Utilisasi Kriteria
          </h6>
        </div>

        <div class="card-panel-body">
          <?php
            $kriteriaUtil = $kriteriaUtilisasi ?? [
                [
                    'label' => 'Urgensi',
                    'pct'   => 92,
                    'color' => 'var(--adm-teal)',
                ],
                [
                    'label' => 'Biaya',
                    'pct'   => 85,
                    'color' => 'var(--adm-blue)',
                ],
                [
                    'label' => 'Kerusakan',
                    'pct'   => 78,
                    'color' => 'var(--adm-purple)',
                ],
                [
                    'label' => 'Frekuensi',
                    'pct'   => 65,
                    'color' => 'var(--adm-amber)',
                ],
                [
                    'label' => 'Dampak',
                    'pct'   => 54,
                    'color' => 'var(--adm-green)',
                ],
            ];
          ?>

          <?php foreach ($kriteriaUtil as $kriteria): ?>
            <div class="prog-row">
              <span class="prog-label">
                <?= esc($kriteria['label'] ?? '-') ?>
              </span>

              <div class="prog-bar-wrap">
                <div
                  class="prog-bar"
                  style="width: <?= (int) ($kriteria['pct'] ?? 0) ?>%; background: <?= esc($kriteria['color'] ?? 'var(--adm-blue)') ?>;"
                ></div>
              </div>

              <span class="prog-val">
                <?= (int) ($kriteria['pct'] ?? 0) ?>%
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ALUR SISTEM -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-diagram-3" style="color: var(--adm-blue);"></i>
            Alur Sistem
          </h6>
        </div>

        <div class="card-panel-body">
          <p style="font-size: .68rem; color: var(--adm-muted); margin: 0 0 .55rem;">
            Proses pengadaan barang:
          </p>

          <div class="flow">
            <div
              class="flow-node"
              style="background: var(--adm-purple-bg); border-color: #C7D2FE; color: var(--adm-purple);"
            >
              Sub Unit
            </div>

            <span class="flow-arr">→</span>

            <div
              class="flow-node"
              style="background: var(--adm-green-bg); border-color: #A7F3D0; color: var(--adm-green);"
            >
              Gudang
            </div>

            <span class="flow-arr">→</span>

            <div
              class="flow-node"
              style="background: var(--adm-teal-bg); border-color: #99F6E4; color: var(--adm-teal-dark);"
            >
              MOORA
            </div>

            <span class="flow-arr">→</span>

            <div
              class="flow-node"
              style="background: var(--adm-amber-bg); border-color: #FCD34D; color: var(--adm-amber-dark);"
            >
              Direktur
            </div>
          </div>

          <div style="margin-top: .9rem; font-size: .66rem; color: var(--adm-muted);">
            <div style="display: flex; align-items: center; gap: .35rem; margin-bottom: .3rem;">
              <span style="width: 7px; height: 7px; background: var(--adm-green); border-radius: 50%; display: inline-block;"></span>
              Persetujuan rata-rata:
              <strong style="color: var(--adm-text);">2,4 hari</strong>
            </div>

            <div style="display: flex; align-items: center; gap: .35rem;">
              <span style="width: 7px; height: 7px; background: var(--adm-blue); border-radius: 50%; display: inline-block;"></span>
              SLA target:
              <strong style="color: var(--adm-text);">3 hari kerja</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- KALENDER AKTIVITAS -->
      <div class="card-panel">
        <div class="card-panel-head">
          <h6>
            <i class="bi bi-calendar3" style="color: var(--adm-green);"></i>
            Kalender Aktivitas
          </h6>
        </div>

        <div class="card-panel-body">
          <?php
            $today       = (int) date('j');
            $daysInMonth = (int) date('t');
            $firstDay    = (int) date('N', strtotime(date('Y-m-01'))) % 7;
            $eventDays   = $calendarEvents ?? [5, 12, 14, 20, 25, 28];
            $dayLabels   = ['M', 'S', 'S', 'R', 'K', 'J', 'S'];
          ?>

          <div class="mini-cal-grid">
            <?php foreach ($dayLabels as $dayLabel): ?>
              <div class="cal-day-lbl">
                <?= esc($dayLabel) ?>
              </div>
            <?php endforeach; ?>

            <?php for ($blank = 0; $blank < $firstDay; $blank++): ?>
              <div></div>
            <?php endfor; ?>

            <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
              <?php
                $isToday = $day === $today;
                $isEvent = in_array($day, $eventDays);

                if ($isToday) {
                    $calendarClass = 'cal-today';
                } elseif ($isEvent) {
                    $calendarClass = 'cal-event';
                } else {
                    $calendarClass = '';
                }
              ?>

              <div class="cal-day <?= esc($calendarClass) ?>">
                <?= $day ?>
              </div>
            <?php endfor; ?>
          </div>

          <div class="cal-legend">
            <div style="display: flex; align-items: center; gap: .25rem; font-size: .62rem; color: var(--adm-muted);">
              <span class="cal-dot" style="background: var(--adm-teal);"></span>
              Hari ini
            </div>

            <div style="display: flex; align-items: center; gap: .25rem; font-size: .62rem; color: var(--adm-muted);">
              <span class="cal-dot" style="border: 1px solid var(--adm-blue);"></span>
              Ada aktivitas
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

<script>
(function () {
  var STORAGE_KEY = 'pdam_theme';
  var wrap = document.getElementById('admWrap');
  var icon = document.getElementById('themeIcon');
  var isApplying = false;

  if (!wrap) {
    return;
  }

  function detectGlobalDark() {
    return document.body.classList.contains('dark-mode')
      || document.body.getAttribute('data-theme') === 'dark'
      || document.documentElement.classList.contains('dark')
      || document.documentElement.getAttribute('data-theme') === 'dark'
      || document.documentElement.getAttribute('data-bs-theme') === 'dark';
  }

  function updateIcon(dark) {
    if (!icon) {
      return;
    }

    if (dark) {
      icon.classList.remove('bi-moon-fill');
      icon.classList.add('bi-sun-fill');
    } else {
      icon.classList.remove('bi-sun-fill');
      icon.classList.add('bi-moon-fill');
    }
  }

  function applyTheme(dark, save) {
    isApplying = true;

    if (dark) {
      document.body.classList.add('dark-mode');
      document.body.setAttribute('data-theme', 'dark');
      document.documentElement.setAttribute('data-theme', 'dark');
      document.documentElement.setAttribute('data-bs-theme', 'dark');
      wrap.setAttribute('data-theme', 'dark');
    } else {
      document.body.classList.remove('dark-mode');
      document.body.setAttribute('data-theme', 'light');
      document.documentElement.setAttribute('data-theme', 'light');
      document.documentElement.setAttribute('data-bs-theme', 'light');
      wrap.setAttribute('data-theme', 'light');
    }

    updateIcon(dark);

    if (save) {
      localStorage.setItem(STORAGE_KEY, dark ? 'dark' : 'light');
    }

    window.setTimeout(function () {
      isApplying = false;
    }, 0);
  }

  var savedTheme = localStorage.getItem(STORAGE_KEY);

  if (savedTheme === 'dark') {
    applyTheme(true, false);
  } else if (savedTheme === 'light') {
    applyTheme(false, false);
  } else {
    applyTheme(detectGlobalDark(), false);
  }

  window.toggleDashboardTheme = function () {
    var nowDark = wrap.getAttribute('data-theme') === 'dark';
    applyTheme(!nowDark, true);
  };

  var observer = new MutationObserver(function () {
    if (isApplying) {
      return;
    }

    applyTheme(detectGlobalDark(), false);
  });

  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class', 'data-theme', 'data-bs-theme']
  });

  observer.observe(document.body, {
    attributes: true,
    attributeFilter: ['class', 'data-theme']
  });

  if (window.matchMedia) {
    var media = window.matchMedia('(prefers-color-scheme: dark)');

    if (media && media.addEventListener) {
      media.addEventListener('change', function (event) {
        if (!localStorage.getItem(STORAGE_KEY)) {
          applyTheme(event.matches, false);
        }
      });
    }
  }
})();
</script>

<?= $this->endSection() ?>