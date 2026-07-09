<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$hasilMoora = $hasilMoora ?? [];
$isRkaAggregate = !empty($hasilMoora[0]['mode_hitung']) && ($hasilMoora[0]['mode_hitung'] ?? '') === 'rka_aggregate';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Detail Usulan: <?= esc($usulan['nomor_usulan'] ?? '-') ?></h2>
            <p class="text-muted mb-0">Detail barang dan hasil aktif MOORA dari single source latest.</p>
        </div>
        <span class="badge bg-<?= ($usulan['status'] ?? '') === 'moora_selesai' ? 'success' : 'primary' ?>">
            <?= esc(ucwords(str_replace('_', ' ', $usulan['status'] ?? '-'))) ?>
        </span>
    </div>

    <?php if (!empty($usulan['file_rka_path']) || !empty($usulan['file_rka_excel_path']) || !empty($usulan['file_rka_dokumen_path'])): ?>
        <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Dokumen RKA:</strong>
                <?php if (!empty($usulan['file_rka_excel_path']) || !empty($usulan['file_rka_path'])): ?>
                    <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_excel_path'] ?? $usulan['file_rka_path'])) ?>" target="_blank" class="ms-2">Excel Import</a>
                <?php endif; ?>
                <?php if (!empty($usulan['file_rka_dokumen_path'])): ?>
                    <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_dokumen_path'])) ?>" target="_blank" class="ms-2">Dokumen Resmi</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <p><strong>Tanggal Usulan:</strong> <?= esc($usulan['tanggal_usulan'] ?? '-') ?></p>
        <p><strong>Unit Pengusul:</strong> <?= esc($usulan['unit_pengusul'] ?? '-') ?></p>
        <p><strong>Jenis:</strong> <?= esc($usulan['jenis_usulan'] ?? '-') ?></p>
        <?php if (!empty($usulan['catatan_banding_gudang'])): ?>
            <p><strong>Catatan Banding:</strong> <?= esc($usulan['catatan_banding_gudang']) ?></p>
        <?php endif ?>
    </div>

    <?php if (in_array($usulan['status'] ?? '', ['diverifikasi', 'moora_selesai'], true)): ?>
        <form action="<?= site_url('gudang/detail-usulan/proses-moora/'.$usulan['id']) ?>" method="post" class="mb-3">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Proses ulang MOORA otomatis untuk usulan ini? Histori lama tidak dihapus dan view latest akan memilih versi terbaru.')">
                Proses MOORA Otomatis
            </button>
            <a href="<?= site_url('gudang/penilaian/detail/' . $usulan['id']) ?>" class="btn btn-outline-secondary">Preview Engine</a>
        </form>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (!empty($hasilMoora)): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-bold mb-2">Hasil MOORA Aktif</h5>
                <p class="text-muted mb-3">
                    <?= $isRkaAggregate ? 'RKA tampil sebagai satu hasil agregasi dokumen.' : 'Pesan Cepat tampil sebagai ranking per item barang.' ?>
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Ranking</th>
                                <th>Keputusan / Barang</th>
                                <th>Mode</th>
                                <th>Nilai Yi</th>
                                <th>Versi</th>
                                <th>Tanggal Hitung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hasilMoora as $row): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($row['ranking'] ?? '-') ?></td>
                                    <td><?= esc($row['nama_alternatif'] ?? $row['jenis_keputusan'] ?? '-') ?></td>
                                    <td class="text-center"><span class="badge bg-info text-dark"><?= esc($row['mode_hitung'] ?? '-') ?></span></td>
                                    <td class="text-end fw-semibold"><?= number_format((float) ($row['nilai_yi'] ?? 0), 6) ?></td>
                                    <td class="text-center"><?= esc($row['versi_hitung'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['tanggal_hitung'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Detail Barang</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Estimasi Harga Satuan</th>
                            <th>Total Estimasi</th>
                            <th>Status Detail</th>
                            <th>Nilai MOORA</th>
                            <th>Ranking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($details)): ?>
                            <?php $i = 1; foreach ($details as $detail): ?>
                                <?php $hasil = $detail['hasil_moora'] ?? null; ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= esc($detail['nama_alternatif'] ?? '-') ?></td>
                                    <td><?= esc($detail['jumlah'] ?? 0) ?> <?= esc($detail['satuan'] ?? '') ?></td>
                                    <td>Rp <?= number_format((float) ($detail['estimasi_harga_satuan'] ?? 0), 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format((float) ($detail['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                                    <td><?= esc(ucwords(str_replace('_', ' ', $detail['status'] ?? '-'))) ?></td>
                                    <td><?= $hasil ? number_format((float) $hasil['nilai_yi'], 6) : ($isRkaAggregate ? '<span class="text-muted">Agregat RKA</span>' : '-') ?></td>
                                    <td><?= $hasil ? esc($hasil['ranking']) : ($isRkaAggregate ? '<span class="text-muted">Agregat</span>' : '-') ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada detail barang.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
