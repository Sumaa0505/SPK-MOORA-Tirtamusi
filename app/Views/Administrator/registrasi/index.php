<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
if (! function_exists('regAngka')) {
    function regAngka($value)
    {
        return number_format((int) ($value ?? 0), 0, ',', '.');
    }
}

if (! function_exists('regTanggal')) {
    function regTanggal($date)
    {
        if (empty($date)) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($date));
    }
}

if (! function_exists('regInitial')) {
    function regInitial($name)
    {
        $name = trim((string) $name);
        return $name !== '' ? strtoupper(substr($name, 0, 1)) : 'U';
    }
}

if (! function_exists('regRoleLabel')) {
    function regRoleLabel($role, $roles = [])
    {
        $role = (string) $role;
        return $roles[$role] ?? ucwords(str_replace('_', ' ', $role));
    }
}

if (! function_exists('regStatusBadge')) {
    function regStatusBadge($status)
    {
        $status = strtolower((string) $status);

        if ($status === 'pending') {
            return '<span class="reg-badge reg-badge-warn"><i class="bi bi-hourglass-split"></i> Pending</span>';
        }

        if ($status === 'approved') {
            return '<span class="reg-badge reg-badge-ok"><i class="bi bi-check-circle"></i> Disetujui</span>';
        }

        if ($status === 'rejected') {
            return '<span class="reg-badge reg-badge-danger"><i class="bi bi-x-circle"></i> Ditolak</span>';
        }

        return '<span class="reg-badge reg-badge-muted"><i class="bi bi-question-circle"></i> Tidak diketahui</span>';
    }
}

$registrations = $registrations ?? [];
$logs          = $logs ?? [];
$roles         = $roles ?? [];
$status        = $status ?? 'pending';
$keyword       = $keyword ?? '';
$role          = $role ?? '';
?>

<style>
#regWrap {
  --reg-bg: #f4f7fb;
  --reg-card: #ffffff;
  --reg-card-2: #f8fafc;
  --reg-text: #0f172a;
  --reg-muted: #64748b;
  --reg-line: #e2e8f0;
  --reg-blue: #2563eb;
  --reg-green: #10b981;
  --reg-amber: #f59e0b;
  --reg-red: #ef4444;
  --reg-purple: #7c3aed;
  --reg-navy: #0f1f3d;
  --reg-shadow: 0 8px 24px rgba(15, 23, 42, .08);
  background: var(--reg-bg) !important;
  color: var(--reg-text) !important;
  min-height: 100vh;
  padding: 1.25rem;
  font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif;
}

body.theme-dark #regWrap,
body.dark-mode #regWrap,
html[data-theme="dark"] #regWrap,
html[data-bs-theme="dark"] #regWrap,
#regWrap[data-theme="dark"] {
  --reg-bg: #0b1220;
  --reg-card: #111d30;
  --reg-card-2: #0f1a2b;
  --reg-text: #eaf2ff;
  --reg-muted: #a8b8d0;
  --reg-line: #263954;
  --reg-shadow: 0 8px 28px rgba(0, 0, 0, .35);
}

#regWrap .bi { font-family: "bootstrap-icons" !important; }
#regWrap * { box-sizing: border-box; }

.reg-header {
  background:
    radial-gradient(circle at 85% 18%, rgba(16, 185, 129, .20) 0, rgba(16, 185, 129, .20) 90px, transparent 91px),
    radial-gradient(circle at 98% 75%, rgba(255, 255, 255, .10) 0, rgba(255, 255, 255, .10) 70px, transparent 71px),
    linear-gradient(135deg, #0b1d3a 0%, #183b73 100%) !important;
  border-radius: 16px;
  padding: 1.25rem 1.5rem;
  margin-bottom: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  box-shadow: var(--reg-shadow);
  border: 1px solid rgba(255,255,255,.14);
}

.reg-header h2 {
  color: #fff !important;
  font-size: 1.25rem;
  font-weight: 900;
  margin: 0 0 .24rem;
  letter-spacing: -.2px;
}

.reg-header p {
  color: #dbeafe !important;
  font-size: .78rem;
  font-weight: 500;
  margin: 0;
}

.reg-header-actions {
  display: flex;
  gap: .5rem;
  align-items: center;
  flex-wrap: wrap;
}

.reg-chip {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  background: rgba(255, 255, 255, .14);
  border: 1px solid rgba(255, 255, 255, .28);
  color: #fff !important;
  border-radius: 999px;
  padding: .42rem .82rem;
  font-size: .72rem;
  font-weight: 850;
  white-space: nowrap;
}

.reg-back {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  background: #fbbf24;
  border: 1px solid rgba(255,255,255,.25);
  color: #071833 !important;
  text-decoration: none;
  border-radius: 999px;
  padding: .42rem .82rem;
  font-size: .72rem;
  font-weight: 900;
  white-space: nowrap;
}

.reg-stat-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: .75rem;
  margin-bottom: 1rem;
}

.reg-stat {
  background: var(--reg-card) !important;
  border-radius: 14px;
  padding: 1rem;
  border: 1px solid var(--reg-line);
  border-left: 4px solid var(--reg-blue);
  box-shadow: var(--reg-shadow);
  position: relative;
  overflow: hidden;
}

.reg-stat small {
  color: var(--reg-muted) !important;
  font-weight: 800;
  font-size: .7rem;
  display: block;
}

.reg-stat h3 {
  color: var(--reg-text) !important;
  font-size: 1.65rem;
  font-weight: 950;
  margin: .24rem 0 0;
  line-height: 1;
}

.reg-stat i {
  position: absolute;
  right: .85rem;
  bottom: .7rem;
  font-size: 1.9rem;
  opacity: .12;
}

.reg-card {
  background: var(--reg-card) !important;
  color: var(--reg-text) !important;
  border-radius: 14px;
  box-shadow: var(--reg-shadow);
  border: 1px solid var(--reg-line);
  overflow: hidden;
}

.reg-card-head {
  padding: 1rem 1.1rem;
  border-bottom: 1px solid var(--reg-line);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: .8rem;
  flex-wrap: wrap;
}

.reg-card-head h5 {
  color: var(--reg-text) !important;
  font-size: .95rem;
  font-weight: 900;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .4rem;
}

.reg-card-head p {
  color: var(--reg-muted) !important;
  font-size: .7rem;
  margin: .18rem 0 0;
}

.reg-card-body { padding: 1rem; }

.reg-filter {
  display: flex;
  gap: .5rem;
  align-items: center;
  flex-wrap: wrap;
}

#regWrap .form-control,
#regWrap .form-select,
#regWrap textarea {
  background: var(--reg-card-2) !important;
  color: var(--reg-text) !important;
  border: 1px solid var(--reg-line) !important;
  font-size: .8rem;
  min-height: 38px;
}

#regWrap .form-control::placeholder,
#regWrap textarea::placeholder {
  color: var(--reg-muted) !important;
}

#regWrap .form-label {
  color: var(--reg-text) !important;
  font-weight: 800;
  font-size: .76rem;
}

.reg-table {
  width: 100%;
  border-collapse: collapse;
  font-size: .8rem;
}

.reg-table th {
  color: var(--reg-muted) !important;
  background: var(--reg-card-2) !important;
  border-bottom: 2px solid var(--reg-line) !important;
  font-size: .67rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .06em;
  padding: .72rem .65rem;
  white-space: nowrap;
}

.reg-table td {
  color: var(--reg-text) !important;
  background: var(--reg-card) !important;
  border-bottom: 1px solid var(--reg-line) !important;
  padding: .78rem .65rem;
  vertical-align: middle;
}

.reg-table tbody tr:hover td {
  background: rgba(37, 99, 235, .06) !important;
}

.reg-user-cell {
  display: flex;
  align-items: center;
  gap: .65rem;
  min-width: 245px;
}

.reg-avatar {
  width: 40px;
  height: 40px;
  border-radius: 13px;
  background: rgba(37, 99, 235, .12);
  color: var(--reg-blue) !important;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  font-weight: 950;
  flex-shrink: 0;
}

.reg-user-main strong {
  display: block;
  color: var(--reg-text) !important;
  font-weight: 900;
  line-height: 1.25;
}

.reg-user-main span {
  display: block;
  color: var(--reg-muted) !important;
  font-size: .69rem;
  margin-top: .1rem;
  line-height: 1.35;
}

.reg-role-pill {
  display: inline-flex;
  align-items: center;
  gap: .28rem;
  padding: .26rem .58rem;
  border-radius: 999px;
  background: rgba(37, 99, 235, .10);
  color: var(--reg-blue) !important;
  font-weight: 900;
  font-size: .69rem;
  white-space: nowrap;
}

.reg-badge {
  display: inline-flex;
  align-items: center;
  gap: .32rem;
  padding: .25rem .58rem;
  border-radius: 999px;
  font-size: .68rem;
  font-weight: 900;
  white-space: nowrap;
}

.reg-badge-warn { background: #fef3c7; color: #92400e !important; }
.reg-badge-ok { background: #dcfce7; color: #15803d !important; }
.reg-badge-danger { background: #fee2e2; color: #b91c1c !important; }
.reg-badge-muted { background: #e2e8f0; color: #475569 !important; }

body.theme-dark #regWrap .reg-badge-warn,
#regWrap[data-theme="dark"] .reg-badge-warn { background: #2a1f00; color: #fcd34d !important; }
body.theme-dark #regWrap .reg-badge-ok,
#regWrap[data-theme="dark"] .reg-badge-ok { background: #052015; color: #4ade80 !important; }
body.theme-dark #regWrap .reg-badge-danger,
#regWrap[data-theme="dark"] .reg-badge-danger { background: #2a0812; color: #fda4af !important; }
body.theme-dark #regWrap .reg-badge-muted,
#regWrap[data-theme="dark"] .reg-badge-muted { background: #1e2d45; color: #cbd5e1 !important; }

.reg-actions {
  display: inline-flex;
  gap: .35rem;
  justify-content: center;
  align-items: center;
}

.reg-btn-icon {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: none;
  color: #fff !important;
  font-size: .9rem;
}

.reg-btn-detail { background: var(--reg-blue); }
.reg-btn-approve { background: var(--reg-green); }
.reg-btn-reject { background: var(--reg-red); }

.reg-log-item {
  display: flex;
  gap: .65rem;
  padding: .72rem 0;
  border-bottom: 1px solid var(--reg-line);
}

.reg-log-item:last-child { border-bottom: none; }

.reg-log-icon {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.reg-log-title {
  color: var(--reg-text) !important;
  font-weight: 900;
  font-size: .78rem;
  line-height: 1.35;
}

.reg-log-meta,
.reg-log-note {
  color: var(--reg-muted) !important;
  font-size: .68rem;
  margin-top: .12rem;
  line-height: 1.45;
}

.reg-empty {
  text-align: center;
  color: var(--reg-muted) !important;
  padding: 2.5rem 1rem;
}

.reg-empty i {
  font-size: 2.1rem;
  display: block;
  margin-bottom: .5rem;
  opacity: .55;
}

.reg-note-list {
  margin: 0;
  padding-left: 1.05rem;
  color: var(--reg-muted) !important;
  font-size: .77rem;
  line-height: 1.7;
}

.reg-note-list strong { color: var(--reg-text) !important; }

#regWrap .modal-content {
  background: var(--reg-card) !important;
  color: var(--reg-text) !important;
  border: 1px solid var(--reg-line) !important;
  border-radius: 16px;
  overflow: hidden;
}

#regWrap .modal-header,
#regWrap .modal-footer {
  border-color: var(--reg-line) !important;
}

#regWrap .modal-title,
#regWrap .modal-body,
#regWrap .modal-body p,
#regWrap .modal-body strong {
  color: var(--reg-text) !important;
}



/* FIX MODAL AKSI APPROVAL
   Modal tidak boleh berada di dalam tabel. Style ini dibuat global agar modal rapi,
   berada di tengah layar, dan tidak terpengaruh table-responsive/sidebar. */
.reg-approval-modal.modal { z-index: 2060 !important; }
.modal-backdrop { z-index: 2050 !important; }
.reg-approval-modal .modal-dialog {
  max-width: 560px;
  width: calc(100% - 2rem);
  margin-left: auto;
  margin-right: auto;
}
.reg-approval-modal .modal-content {
  background: var(--reg-card, #fff) !important;
  color: var(--reg-text, #0f172a) !important;
  border: 1px solid var(--reg-line, #e2e8f0) !important;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 22px 60px rgba(15, 23, 42, .28);
}
.reg-approval-modal .modal-header,
.reg-approval-modal .modal-footer { border-color: var(--reg-line, #e2e8f0) !important; }
.reg-approval-modal .modal-title,
.reg-approval-modal .modal-body,
.reg-approval-modal .modal-body p,
.reg-approval-modal .modal-body strong { color: var(--reg-text, #0f172a) !important; }
.reg-approval-modal .modal-body { padding: 1.1rem 1.25rem; }
.reg-approval-modal .form-control,
.reg-approval-modal .form-select,
.reg-approval-modal textarea {
  background: var(--reg-card-2, #f8fafc) !important;
  color: var(--reg-text, #0f172a) !important;
  border: 1px solid var(--reg-line, #e2e8f0) !important;
  font-size: .86rem;
  min-height: 42px;
}
.reg-approval-modal .form-label {
  color: var(--reg-text, #0f172a) !important;
  font-weight: 800;
  font-size: .78rem;
}
.reg-approval-modal .reg-modal-help {
  color: var(--reg-muted, #64748b) !important;
  font-size: .72rem;
  display: block;
  margin-top: .35rem;
}
.reg-approval-modal .detail-grid {
  display: grid;
  grid-template-columns: 145px minmax(0, 1fr);
  gap: .48rem .8rem;
  font-size: .82rem;
}
.reg-approval-modal .detail-grid span:nth-child(odd) {
  color: var(--reg-muted, #64748b) !important;
  font-weight: 800;
}
.reg-approval-modal .detail-grid span:nth-child(even) {
  color: var(--reg-text, #0f172a) !important;
  font-weight: 650;
  word-break: break-word;
}
html[data-theme="dark"] .reg-approval-modal,
html[data-bs-theme="dark"] .reg-approval-modal,
body.dark-mode .reg-approval-modal,
body.theme-dark .reg-approval-modal {
  --reg-card: #111d30;
  --reg-card-2: #0f1a2b;
  --reg-text: #eaf2ff;
  --reg-muted: #a8b8d0;
  --reg-line: #263954;
}

#regWrap .detail-grid {
  display: grid;
  grid-template-columns: 140px minmax(0, 1fr);
  gap: .45rem .75rem;
  font-size: .8rem;
}

#regWrap .detail-grid span:nth-child(odd) {
  color: var(--reg-muted) !important;
  font-weight: 800;
}

#regWrap .detail-grid span:nth-child(even) {
  color: var(--reg-text) !important;
  font-weight: 650;
}

#regWrap .pagination { margin-bottom: 0; }
#regWrap .page-link {
  background: var(--reg-card-2) !important;
  color: var(--reg-text) !important;
  border-color: var(--reg-line) !important;
}
#regWrap .page-item.active .page-link {
  background: var(--reg-blue) !important;
  border-color: var(--reg-blue) !important;
  color: #fff !important;
}

@media (max-width: 1200px) {
  .reg-stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 768px) {
  #regWrap { padding: .85rem; }
  .reg-header { padding: 1rem; }
  .reg-header h2 { font-size: 1.08rem; }
  .reg-header-actions { width: 100%; }
  .reg-chip, .reg-back { flex: 1; justify-content: center; }
  .reg-filter { width: 100%; }
  .reg-filter .form-control,
  .reg-filter .form-select,
  .reg-filter .btn { width: 100%; }
  .reg-card-head { align-items: flex-start; }
}

@media (max-width: 560px) {
  .reg-stat-grid { grid-template-columns: 1fr; }
  #regWrap .detail-grid { grid-template-columns: 1fr; }
}
</style>

<div class="reg-wrap" id="regWrap">
  <div class="reg-header">
    <div>
      <h2><i class="bi bi-person-fill-check me-2"></i>Approval Registrasi User</h2>
      <p>Validasi akun baru sebelum pengguna dapat mengakses sistem SPK MOORA PDAM Tirta Musi.</p>
    </div>

    <div class="reg-header-actions">
      <span class="reg-chip"><i class="bi bi-shield-lock-fill"></i> Administrator Verification</span>
      <a href="<?= site_url('administrator/dashboard') ?>" class="reg-back"><i class="bi bi-arrow-left"></i> Dashboard</a>
    </div>
  </div>

  <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success border-0 shadow-sm">
      <i class="bi bi-check-circle me-1"></i><?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger border-0 shadow-sm">
      <i class="bi bi-exclamation-triangle me-1"></i><?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <div class="reg-stat-grid">
    <div class="reg-stat" style="border-left-color: var(--reg-amber);">
      <small>Menunggu Approval</small>
      <h3><?= regAngka($totalPending ?? 0) ?></h3>
      <i class="bi bi-hourglass-split" style="color: var(--reg-amber);"></i>
    </div>

    <div class="reg-stat" style="border-left-color: var(--reg-green);">
      <small>Registrasi Disetujui</small>
      <h3><?= regAngka($totalApproved ?? 0) ?></h3>
      <i class="bi bi-person-check" style="color: var(--reg-green);"></i>
    </div>

    <div class="reg-stat" style="border-left-color: var(--reg-red);">
      <small>Registrasi Ditolak</small>
      <h3><?= regAngka($totalRejected ?? 0) ?></h3>
      <i class="bi bi-person-x" style="color: var(--reg-red);"></i>
    </div>

    <div class="reg-stat" style="border-left-color: var(--reg-blue);">
      <small>Total User Aktif</small>
      <h3><?= regAngka($totalUsers ?? 0) ?></h3>
      <i class="bi bi-people" style="color: var(--reg-blue);"></i>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-xl-8">
      <div class="reg-card">
        <div class="reg-card-head">
          <div>
            <h5><i class="bi bi-list-check" style="color: var(--reg-blue);"></i>Daftar Registrasi</h5>
            <p>Gunakan aksi approve/reject agar status user tercatat dan tidak mengubah data langsung.</p>
          </div>

          <form action="<?= site_url('administrator/registrasi') ?>" method="get" class="reg-filter">
            <input type="text" name="q" value="<?= esc($keyword) ?>" class="form-control" placeholder="Cari nama, username, email...">

            <select name="status" class="form-select">
              <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua Status</option>
              <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Disetujui</option>
              <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
            </select>

            <select name="role" class="form-select">
              <option value="" <?= $role === '' ? 'selected' : '' ?>>Semua Role</option>
              <?php foreach ($roles as $roleKey => $roleLabel) : ?>
                <option value="<?= esc($roleKey) ?>" <?= $role === $roleKey ? 'selected' : '' ?>><?= esc($roleLabel) ?></option>
              <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <a href="<?= site_url('administrator/registrasi') ?>" class="btn btn-outline-secondary">Reset</a>
          </form>
        </div>

        <div class="reg-card-body">
          <div class="table-responsive">
            <table class="reg-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Role Diajukan</th>
                  <th>Status</th>
                  <th>Tanggal Daftar</th>
                  <th style="width: 155px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (! empty($registrations)) : ?>
                  <?php foreach ($registrations as $row) : ?>
                    <?php
                    $rowId   = (int) ($row['id'] ?? 0);
                    $rowStat = strtolower((string) ($row['status'] ?? 'pending'));
                    $rowRole = (string) ($row['role'] ?? 'sub_unit');
                    ?>
                    <tr>
                      <td>
                        <div class="reg-user-cell">
                          <div class="reg-avatar"><?= esc(regInitial($row['nama_lengkap'] ?? '')) ?></div>
                          <div class="reg-user-main">
                            <strong><?= esc($row['nama_lengkap'] ?? '-') ?></strong>
                            <span>
                              @<?= esc($row['username'] ?? '-') ?>
                              <?php if (! empty($row['email'])) : ?>
                                · <?= esc($row['email']) ?>
                              <?php endif; ?>
                            </span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="reg-role-pill"><i class="bi bi-person-badge"></i><?= esc(regRoleLabel($rowRole, $roles)) ?></span>
                      </td>
                      <td><?= regStatusBadge($rowStat) ?></td>
                      <td style="color: var(--reg-muted) !important; white-space: nowrap;">
                        <?= esc(regTanggal($row['created_at'] ?? null)) ?>
                      </td>
                      <td class="text-center">
                        <div class="reg-actions">
                          <button type="button" class="reg-btn-icon reg-btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal<?= $rowId ?>" title="Detail">
                            <i class="bi bi-eye"></i>
                          </button>

                          <?php if ($rowStat === 'pending') : ?>
                            <button type="button" class="reg-btn-icon reg-btn-approve" data-bs-toggle="modal" data-bs-target="#approveModal<?= $rowId ?>" title="Setujui">
                              <i class="bi bi-check2-circle"></i>
                            </button>
                            <button type="button" class="reg-btn-icon reg-btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $rowId ?>" title="Tolak">
                              <i class="bi bi-x-circle"></i>
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>

                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="5">
                      <div class="reg-empty">
                        <i class="bi bi-inbox"></i>
                        Tidak ada data registrasi pada filter ini. Jika baru mendaftar tetapi tidak muncul, pastikan username belum pernah terdaftar dan buka filter <strong>Semua Status</strong>.
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if (! empty($pager)) : ?>
            <div class="mt-3">
              <?= $pager->links('registrasi', 'default_full') ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="reg-card">
        <div class="reg-card-head">
          <div>
            <h5><i class="bi bi-clock-history" style="color: var(--reg-purple);"></i>Riwayat Approval</h5>
            <p>Aktivitas approval terbaru oleh Administrator.</p>
          </div>
        </div>

        <div class="reg-card-body">
          <?php if (! empty($logs)) : ?>
            <?php foreach ($logs as $log) : ?>
              <?php
              $action = strtolower((string) ($log['action'] ?? 'reviewed'));
              $isApproved = $action === 'approved';
              $isRejected = $action === 'rejected';
              $iconClass = $isApproved ? 'bi-check-circle' : ($isRejected ? 'bi-x-circle' : 'bi-eye');
              $iconBg = $isApproved ? 'rgba(16,185,129,.13)' : ($isRejected ? 'rgba(239,68,68,.13)' : 'rgba(37,99,235,.13)');
              $iconColor = $isApproved ? 'var(--reg-green)' : ($isRejected ? 'var(--reg-red)' : 'var(--reg-blue)');
              $actionLabel = $isApproved ? 'Disetujui' : ($isRejected ? 'Ditolak' : 'Direview');
              ?>
              <div class="reg-log-item">
                <div class="reg-log-icon" style="background: <?= esc($iconBg) ?>; color: <?= esc($iconColor) ?>;">
                  <i class="bi <?= esc($iconClass) ?>"></i>
                </div>
                <div>
                  <div class="reg-log-title"><?= esc($actionLabel) ?> · <?= esc($log['username'] ?? '-') ?></div>
                  <div class="reg-log-meta">
                    Admin: <?= esc($log['admin_nama'] ?? 'Administrator') ?> · <?= esc(regTanggal($log['created_at'] ?? null)) ?>
                  </div>
                  <?php if (! empty($log['catatan'])) : ?>
                    <div class="reg-log-note"><?= esc($log['catatan']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="reg-empty" style="padding: 1.7rem 1rem;">
              <i class="bi bi-clock-history"></i>
              Belum ada riwayat approval.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="reg-card mt-3">
        <div class="reg-card-head">
          <div>
            <h5><i class="bi bi-info-circle" style="color: var(--reg-blue);"></i>Catatan Implementasi</h5>
            <p>Aturan agar alur register tetap aman dan konsisten.</p>
          </div>
        </div>
        <div class="reg-card-body">
          <ul class="reg-note-list">
            <li>User baru dari halaman register masuk ke status <strong>pending</strong>.</li>
            <li>Approve akan membuat akun aktif pada tabel <strong>users</strong>.</li>
            <li>Username yang sudah pending/approved tidak akan dibuat ulang agar tidak memicu error duplicate key.</li>
            <li>Reject tidak menghapus data, hanya mengubah status menjadi <strong>rejected</strong>.</li>
            <li>Semua aksi tercatat pada <strong>approval log</strong> dan <strong>log aktivitas</strong>.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>


<?php if (! empty($registrations)) : ?>
  <?php foreach ($registrations as $row) : ?>
    <?php
    $rowId   = (int) ($row['id'] ?? 0);
    $rowStat = strtolower((string) ($row['status'] ?? 'pending'));
    $rowRole = (string) ($row['role'] ?? 'sub_unit');
    ?>

    <!-- Modal Detail: diletakkan di luar table agar tidak rusak oleh table-responsive -->
    <div class="modal fade reg-approval-modal" id="detailModal<?= $rowId ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-person-vcard me-1"></i>Detail Registrasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="detail-grid">
              <span>Nama Lengkap</span><span><?= esc($row['nama_lengkap'] ?? '-') ?></span>
              <span>Username</span><span><?= esc($row['username'] ?? '-') ?></span>
              <span>Email</span><span><?= esc($row['email'] ?: '-') ?></span>
              <span>Role Diajukan</span><span><?= esc(regRoleLabel($rowRole, $roles)) ?></span>
              <span>Status</span><span><?= strip_tags(regStatusBadge($rowStat)) ?></span>
              <span>Tanggal Daftar</span><span><?= esc(regTanggal($row['created_at'] ?? null)) ?></span>
              <span>Update Terakhir</span><span><?= esc(regTanggal($row['updated_at'] ?? null)) ?></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <?php if ($rowStat === 'pending') : ?>
      <div class="modal fade reg-approval-modal" id="approveModal<?= $rowId ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form method="post" action="<?= site_url('administrator/registrasi/approve/' . $rowId) ?>" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header" style="background: var(--reg-green); color: #fff;">
              <h5 class="modal-title" style="color: #fff !important;"><i class="bi bi-check-circle me-1"></i>Setujui Registrasi</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="mb-3">Setujui akun <strong><?= esc($row['nama_lengkap'] ?? '-') ?></strong>?</p>

              <div class="mb-3">
                <label class="form-label">Role Akun Final</label>
                <select name="role" class="form-select" required>
                  <?php foreach ($roles as $roleKey => $roleLabel) : ?>
                    <option value="<?= esc($roleKey) ?>" <?= $rowRole === $roleKey ? 'selected' : '' ?>><?= esc($roleLabel) ?></option>
                  <?php endforeach; ?>
                </select>
                <small class="reg-modal-help">Admin boleh menyesuaikan role sebelum akun diaktifkan.</small>
              </div>

              <div class="mb-0">
                <label class="form-label">Catatan Approval</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Opsional, contoh: Data user sudah sesuai."></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Setujui & Aktifkan</button>
            </div>
          </form>
        </div>
      </div>

      <div class="modal fade reg-approval-modal" id="rejectModal<?= $rowId ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form method="post" action="<?= site_url('administrator/registrasi/reject/' . $rowId) ?>" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header" style="background: var(--reg-red); color: #fff;">
              <h5 class="modal-title" style="color: #fff !important;"><i class="bi bi-x-circle me-1"></i>Tolak Registrasi</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="mb-3">Tolak akun <strong><?= esc($row['nama_lengkap'] ?? '-') ?></strong>?</p>

              <div class="mb-0">
                <label class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                <textarea name="catatan" class="form-control" rows="4" required placeholder="Tuliskan alasan penolakan agar proses administratif jelas."></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Tolak Registrasi</button>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>

<script>
(function () {
  var wrap = document.getElementById('regWrap');
  if (!wrap) return;

  function syncTheme() {
    var dark = document.body.classList.contains('theme-dark')
      || document.body.classList.contains('dark-mode')
      || document.body.getAttribute('data-theme') === 'dark'
      || document.documentElement.classList.contains('dark')
      || document.documentElement.getAttribute('data-theme') === 'dark'
      || document.documentElement.getAttribute('data-bs-theme') === 'dark';

    wrap.setAttribute('data-theme', dark ? 'dark' : 'light');
  }

  syncTheme();

  var observer = new MutationObserver(syncTheme);
  observer.observe(document.body, { attributes: true, attributeFilter: ['class', 'data-theme'] });
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme', 'data-bs-theme'] });
})();
</script>

<?= $this->endSection() ?>
