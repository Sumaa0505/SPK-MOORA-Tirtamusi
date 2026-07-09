<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$status = $usulan['status'] ?? '-';
$isFinal = in_array($status, ['disposisi_pengadaan', 'diproses_pengadaan', 'selesai_pengadaan', 'menunggu_penerimaan', 'direalisasi', 'selesai', 'ditolak'], true)
    || in_array($usulan['status_validasi'] ?? '', ['disetujui', 'ditolak'], true);
$stageLabel = $stageLabel ?? 'Direktur';
$approvalStages = $approvalStages ?? [];
?>

<div class="container-fluid">

<?php if (!empty($usulan['file_rka_path'])) : ?>
    <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div><strong>Dokumen RKA:</strong> <?= esc(basename($usulan['file_rka_path'])) ?></div>
        <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_path'])) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat / Unduh RKA</a>
    </div>
<?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1">Detail Validasi <?= esc($stageLabel) ?></h2>
            <p class="text-muted mb-0">Validasi berjenjang Direktur Bidang → Direktur Utama → Direktur Umum.</p>
        </div>
        <a href="<?= site_url('direktur/validasi') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

    <div class="card border-0 shadow-sm tm-dir-card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Alur Approval Direktur</h5>
                    <p class="text-muted mb-0">Tahap aktif saat ini: <strong><?= esc($stageLabel) ?></strong>. Setiap klik setuju akan memindahkan usulan ke tahap berikutnya.</p>
                </div>
                <span class="badge bg-primary">3 Tahap Berjenjang</span>
            </div>

            <div class="row g-3">
                <?php foreach ($approvalStages as $idx => $stage) : ?>
                    <?php
                        $state = $stage['state'] ?? 'waiting';
                        $badge = 'bg-secondary';
                        $labelState = 'Menunggu';
                        if ($state === 'active') { $badge = 'bg-warning text-dark'; $labelState = 'Tahap Aktif'; }
                        if ($state === 'done') { $badge = 'bg-success'; $labelState = 'Selesai'; }
                        if ($state === 'rejected') { $badge = 'bg-danger'; $labelState = 'Ditolak'; }
                    ?>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 <?= $state === 'active' ? 'border-warning' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong><?= ($idx + 1) ?>. <?= esc($stage['label'] ?? '-') ?></strong>
                                <span class="badge <?= esc($badge) ?>"><?= esc($labelState) ?></span>
                            </div>
                            <div class="text-muted small mb-2"><?= esc($stage['description'] ?? '-') ?></div>
                            <div class="small">
                                <strong>Aksi:</strong> <?= esc(ucwords(str_replace('_', ' ', $stage['aksi'] ?? 'menunggu'))) ?><br>
                                <strong>Waktu:</strong> <?= !empty($stage['approved_at']) ? date('d-m-Y H:i', strtotime($stage['approved_at'])) : '-' ?>
                                <?php if (!empty($stage['catatan'])) : ?><br><strong>Catatan:</strong> <?= esc($stage['catatan']) ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm tm-dir-card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Usulan</h5>
                    <table class="table table-sm tm-detail-table mb-0">
                        <tr><th>Nomor Usulan</th><td><?= esc($usulan['nomor_usulan'] ?? '-') ?></td></tr>
                        <tr><th>Tanggal</th><td><?= esc($usulan['tanggal_usulan'] ?? '-') ?></td></tr>
                        <tr><th>Pengusul</th><td><?= esc($usulan['nama_pengusul'] ?? '-') ?></td></tr>
                        <tr><th>Unit</th><td><?= esc($usulan['unit_pengusul'] ?? '-') ?></td></tr>
                        <tr><th>Tahap Aktif</th><td><span class="badge bg-info text-dark"><?= esc($stageLabel) ?></span></td></tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_', ' ', $status))) ?></span></td>
                        </tr>
                        <tr>
                            <th>Status Validasi</th>
                            <td><span class="badge bg-primary"><?= esc(ucwords(str_replace('_', ' ', $usulan['status_validasi'] ?? 'menunggu'))) ?></span></td>
                        </tr>
                        <tr><th>Catatan Pengusul</th><td><?= esc($usulan['catatan_pengusul'] ?? '-') ?></td></tr>
                        <tr><th>Catatan Gudang</th><td><?= esc($usulan['catatan_verifikasi'] ?? '-') ?></td></tr>
                        <tr><th>Catatan Manajer</th><td><?= esc($usulan['catatan_manajer'] ?? '-') ?></td></tr>
                        <tr><th>Catatan Direktur</th><td><?= esc($usulan['catatan_direksi'] ?? $usulan['catatan_validasi'] ?? '-') ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm tm-dir-card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Riwayat Approval Direktur</h5>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($approval)) : foreach ($approval as $a) : ?>
                            <?php
                            $label = ucwords(str_replace('_', ' ', $a['tahap_approval'] ?? '-'));
                            $aksi = $a['aksi'] ?? 'menunggu';
                            $badge = $aksi === 'menunggu' ? 'bg-warning text-dark' : (($aksi === 'tolak') ? 'bg-danger' : 'bg-success');
                            ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><?= esc($label) ?></strong>
                                    <span class="badge <?= $badge ?>"><?= esc(ucwords($aksi)) ?></span>
                                </div>
                                <small class="text-muted"><?= !empty($a['approved_at']) ? date('d-m-Y H:i', strtotime($a['approved_at'])) : 'Menunggu aksi' ?></small>
                                <?php if (!empty($a['catatan'])) : ?><div class="small mt-1"><?= esc($a['catatan']) ?></div><?php endif; ?>
                            </div>
                        <?php endforeach; else : ?>
                            <div class="text-muted">Belum ada riwayat approval Direktur.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm tm-dir-card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Dokumen Disposisi Digital</h5>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="<?= site_url('dokumen-disposisi/preview/' . $usulan['id']) . '?return=' . rawurlencode('direktur/validasi/detail/' . $usulan['id']) ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Preview Dokumen
                        </a>
                        <form action="<?= site_url('dokumen-disposisi/generate/' . $usulan['id']) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Generate/Refresh
                            </button>
                        </form>
                    </div>
                    <?php if (!empty($dokumen)) : ?>
                        <table class="table table-sm mb-0">
                            <tr><th>Nomor</th><td><?= esc($dokumen['nomor_dokumen'] ?? '-') ?></td></tr>
                            <tr><th>Status</th><td><span class="badge bg-success"><?= esc(ucwords($dokumen['status_dokumen'] ?? '-')) ?></span></td></tr>
                            <tr><th>Disahkan</th><td><?= !empty($dokumen['approved_at']) ? date('d-m-Y H:i', strtotime($dokumen['approved_at'])) : '-' ?></td></tr>
                            <tr>
                                <th>File Resmi</th>
                                <td>
                                    <?php if (!empty($dokumen['file_path'])) : ?>
                                        <a href="<?= site_url('dokumen-disposisi/preview/' . $usulan['id']) . '?return=' . rawurlencode('direktur/validasi/detail/' . $usulan['id']) ?>" target="_blank" class="btn btn-sm btn-outline-success">Cetak / Simpan PDF</a>
                                    <?php else : ?>Preview belum difinalkan<?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Verifikasi QR</th>
                                <td>
                                    <?php if (!empty($qr['verification_url'])) : ?>
                                        <a href="<?= esc($qr['verification_url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Buka Verifikasi</a>
                                    <?php else : ?>-
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-light border mb-0">Dokumen disposisi dibuat otomatis setelah tahap Direktur Umum selesai.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm tm-dir-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Keputusan <?= esc($stageLabel) ?></h5>
                    <p class="text-muted small mb-3">
                        <?= esc($stageLabel) ?> hanya memproses tahap aktif. Setelah disetujui, sistem otomatis mengarahkan ke tahap Direktur berikutnya sampai disposisi final.
                    </p>
                    <?php if ($isFinal) : ?>
                        <div class="alert <?= ($usulan['status_validasi'] ?? '') === 'ditolak' ? 'alert-danger' : 'alert-success' ?> mb-0">
                            Usulan ini sudah berada pada status akhir tahap Direktur: <strong><?= esc(ucwords(str_replace('_', ' ', $status))) ?></strong>.
                        </div>
                    <?php else : ?>
                        <form action="<?= site_url('direktur/validasi/setujui/' . $usulan['id']) ?>" method="post" class="mb-3">
                            <?= csrf_field() ?>
                            <label class="form-label fw-semibold">Catatan Persetujuan / Disposisi</label>
                            <textarea name="catatan_validasi" class="form-control mb-3" rows="3" placeholder="Tuliskan catatan <?= esc($stageLabel) ?>."></textarea>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Proses persetujuan tahap <?= esc($stageLabel) ?>?')">
                                <i class="bi bi-check2-circle me-1"></i> Setujui Tahap <?= esc($stageLabel) ?>
                            </button>
                        </form>
                        <hr>
                        <form action="<?= site_url('direktur/validasi/tolak/' . $usulan['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <label class="form-label fw-semibold">Catatan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan_validasi" class="form-control mb-3" rows="3" placeholder="Tuliskan alasan penolakan." required></textarea>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak usulan ini pada tahap <?= esc($stageLabel) ?>?')">
                                <i class="bi bi-x-circle me-1"></i> Tolak Usulan
                            </button>
                        </form>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm tm-dir-card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Detail Barang Diusulkan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle tm-dir-table mb-0">
                            <thead><tr><th>No</th><th>Barang</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr></thead>
                            <tbody>
                            <?php if (!empty($detail)) : $no = 1; foreach ($detail as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nama_alternatif'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['spesifikasi'] ?? '-') ?></small></td>
                                    <td><?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                                    <td>Rp <?= number_format((float) ($row['estimasi_harga_satuan'] ?? 0), 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format((float) (($row['jumlah'] ?? 0) * ($row['estimasi_harga_satuan'] ?? 0)), 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; else : ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Detail barang belum tersedia.</td></tr>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm tm-dir-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Hasil Prioritas MOORA</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle tm-dir-table mb-0">
                            <thead><tr><th>Ranking</th><th>Barang</th><th>Nilai Yi</th><th>Estimasi</th><th>Alasan</th></tr></thead>
                            <tbody>
                            <?php if (!empty($hasilMoora)) : foreach ($hasilMoora as $row) : ?>
                                <tr>
                                    <td><span class="badge bg-primary">#<?= esc($row['ranking']) ?></span></td>
                                    <td><strong><?= esc($row['nama_alternatif'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['kode_alternatif'] ?? '-') ?></small></td>
                                    <td><?= number_format((float) ($row['nilai_yi'] ?? 0), 8, ',', '.') ?></td>
                                    <td>Rp <?= number_format((float) ($row['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                                    <td><?= esc($row['alasan_kebutuhan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; else : ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Hasil MOORA belum tersedia.</td></tr>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
