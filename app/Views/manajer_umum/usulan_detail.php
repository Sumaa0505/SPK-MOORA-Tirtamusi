<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$status = $usulan['status'] ?? '-';
$statusLabel = ucwords(str_replace('_', ' ', (string) $status));
$canAction = in_array($status, ['moora_selesai', 'direkomendasikan'], true);
?>

<div class="container-fluid">

<?php if (!empty($usulan['file_rka_path'])) : ?>
    <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div><strong>Dokumen RKA:</strong> <?= esc(basename($usulan['file_rka_path'])) ?></div>
        <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_path'])) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat / Unduh RKA</a>
    </div>
<?php endif; ?>

    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Detail Review Usulan</h2>
            <p class="mb-0 text-muted">
                <?= esc($usulan['nomor_usulan'] ?? '-') ?> • <?= esc($usulan['unit_pengusul'] ?? '-') ?> •
                <?= isset($usulan['tanggal_usulan']) ? date('d-m-Y', strtotime($usulan['tanggal_usulan'])) : '-' ?>
            </p>
        </div>
        <a href="<?= site_url('manajer-umum/usulan') ?>" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Status Proses</div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2"><?= esc($statusLabel) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Jenis Usulan</div>
                    <div class="fw-bold"><?= esc($usulan['jenis_usulan'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pengusul</div>
                    <div class="fw-bold"><?= esc($usulan['nama_pengusul'] ?? $usulan['unit_pengusul'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Status Validasi</div>
                    <div class="fw-bold"><?= esc(ucwords(str_replace('_', ' ', $usulan['status_validasi'] ?? '-'))) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Detail Barang Usulan</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Estimasi Satuan</th>
                        <th class="text-center">Stok</th>
                        <th>Kondisi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($detail) && is_array($detail)) : $no = 1; foreach ($detail as $d) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= esc($d['nama_alternatif'] ?? '-') ?></strong><br>
                                <small class="text-muted"><?= esc($d['spesifikasi'] ?? '-') ?></small>
                            </td>
                            <td><?= esc($d['kategori_barang'] ?? '-') ?></td>
                            <td class="text-center"><?= esc($d['jumlah'] ?? 0) ?> <?= esc($d['satuan'] ?? '') ?></td>
                            <td class="text-end">Rp <?= number_format((float) ($d['estimasi_harga_satuan'] ?? 0), 0, ',', '.') ?></td>
                            <td class="text-center fw-bold"><?= esc($d['stok_barang'] ?? 0) ?></td>
                            <td><?= esc(ucfirst((string) ($d['kondisi_barang'] ?? '-'))) ?></td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada detail barang.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Hasil MOORA</h5>
                    <p class="text-muted mb-0">Ranking ini hanya rekomendasi sistem, keputusan tetap melalui validasi berjenjang.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">Ranking</th>
                        <th>Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Total Estimasi</th>
                        <th class="text-end">Nilai Yi</th>
                        <th>Tanggal Hitung</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($hasilMoora)) : foreach ($hasilMoora as $h) : ?>
                        <tr>
                            <td class="text-center"><span class="badge bg-success">#<?= esc($h['ranking'] ?? '-') ?></span></td>
                            <td><?= esc($h['kode_alternatif'] ?? '-') ?> - <?= esc($h['nama_alternatif'] ?? '-') ?></td>
                            <td class="text-center"><?= esc($h['jumlah'] ?? 0) ?> <?= esc($h['satuan'] ?? '') ?></td>
                            <td class="text-end">Rp <?= number_format((float) ($h['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                            <td class="text-end fw-bold"><?= number_format((float) ($h['nilai_yi'] ?? 0), 8, ',', '.') ?></td>
                            <td><?= isset($h['tanggal_hitung']) ? date('d-m-Y H:i', strtotime($h['tanggal_hitung'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Hasil MOORA belum tersedia.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Keputusan Manajer Umum</h5>

            <?php if ($canAction) : ?>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <form action="<?= site_url('manajer-umum/usulan/rekomendasi/' . ($usulan['id'] ?? 0)) ?>" method="post" onsubmit="return confirm('Rekomendasikan usulan ini ke Direktur Bidang?')">
                            <?= csrf_field() ?>
                            <label class="form-label fw-semibold">Catatan Rekomendasi</label>
                            <textarea name="catatan_manajer" class="form-control mb-3" rows="4" placeholder="Contoh: Direkomendasikan karena ranking MOORA dan urgensi operasional sesuai."><?= esc($usulan['catatan_manajer'] ?? '') ?></textarea>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-circle me-1"></i> Rekomendasikan ke Direktur
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form action="<?= site_url('manajer-umum/usulan/kembalikan/' . ($usulan['id'] ?? 0)) ?>" method="post" onsubmit="return confirm('Kembalikan usulan ini untuk revisi?')">
                            <?= csrf_field() ?>
                            <label class="form-label fw-semibold">Catatan Pengembalian <span class="text-danger">*</span></label>
                            <textarea name="catatan_manajer" class="form-control mb-3" rows="4" required placeholder="Tuliskan alasan pengembalian agar pengusul dapat memperbaiki usulan."></textarea>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan untuk Revisi
                            </button>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-info mb-0">
                    Aksi Manajer tidak tersedia untuk status <strong><?= esc($statusLabel) ?></strong>. Usulan mungkin sudah dikirim ke Direktur atau telah selesai diproses.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
