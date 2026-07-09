<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$rankingMoora = $rankingMoora ?? [];
$auditSummary = $auditSummary ?? [];
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Hasil MOORA Gudang</h2>
            <p class="mb-0 text-muted">V6: RKA tampil sebagai <strong>agregasi dokumen</strong>, Pesan Cepat tampil sebagai <strong>ranking per item barang</strong>.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('gudang/penilaian') ?>" class="btn btn-primary btn-sm">Engine MOORA</a>
            <a href="<?= site_url('gudang/dashboard') ?>" class="btn btn-light btn-sm">← Dashboard Gudang</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <small class="text-muted">RKA Sudah Agregat</small>
                <h3 class="fw-bold mb-0"><?= (int) ($auditSummary['rka_aggregate'] ?? 0) ?></h3>
                <span class="badge bg-success">rka_aggregate</span>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <small class="text-muted">RKA Perlu Recalculate</small>
                <h3 class="fw-bold mb-0"><?= (int) ($auditSummary['rka_masih_item'] ?? 0) ?></h3>
                <span class="badge bg-warning text-dark">item lama</span>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <small class="text-muted">Pesan Cepat Valid</small>
                <h3 class="fw-bold mb-0"><?= (int) ($auditSummary['pesan_cepat_item'] ?? 0) ?></h3>
                <span class="badge bg-primary">item_based</span>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <small class="text-muted">Belum Ada Hasil</small>
                <h3 class="fw-bold mb-0"><?= (int) ($auditSummary['belum_ada_hasil'] ?? 0) ?></h3>
                <span class="badge bg-secondary">menunggu proses</span>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Hasil Terbaru per Usulan</strong><br>
                <small class="text-muted">Data diambil dari versi hitung terakhir pada tabel hasil_moora.</small>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor / Unit</th>
                        <th>Jenis Keputusan</th>
                        <th>Objek Hasil</th>
                        <th class="text-end">Benefit</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Nilai Yi</th>
                        <th>Status</th>
                        <th>Versi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rankingMoora)) : ?>
                        <?php foreach ($rankingMoora as $i => $row) : ?>
                            <?php
                                $mode = $row['mode_hitung'] ?? 'item_based';
                                $isRka = $mode === 'rka_aggregate';
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br>
                                    <small class="text-muted"><?= esc($row['unit_pengusul'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <?php if ($isRka) : ?>
                                        <span class="badge bg-success">RKA Agregat</span><br>
                                        <small class="text-muted">1 dokumen = 1 nilai Yi</small>
                                    <?php else : ?>
                                        <span class="badge bg-primary">Pesan Cepat Item</span><br>
                                        <small class="text-muted">1 barang = 1 nilai Yi</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= esc($isRka ? ('Dokumen RKA - ' . ($row['unit_pengusul'] ?? '-')) : ($row['nama_alternatif'] ?? '-')) ?></strong><br>
                                    <small class="text-muted"><?= esc($isRka ? ($row['nomor_usulan'] ?? '-') : ($row['kode_alternatif'] ?? '-')) ?></small>
                                </td>
                                <td class="text-end"><?= $row['nilai_benefit'] !== null ? number_format((float) $row['nilai_benefit'], 6, ',', '.') : '-' ?></td>
                                <td class="text-end"><?= $row['nilai_cost'] !== null ? number_format((float) $row['nilai_cost'], 6, ',', '.') : '-' ?></td>
                                <td class="text-end fw-bold"><?= number_format((float) ($row['nilai_yi'] ?? 0), 6, ',', '.') ?></td>
                                <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_',' ', $row['status'] ?? '-'))) ?></span></td>
                                <td>
                                    <?= !empty($row['tanggal_hitung']) ? date('d/m/Y H:i', strtotime($row['tanggal_hitung'])) : '-' ?><br>
                                    <small class="text-muted"><?= esc($row['versi_hitung'] ?? '-') ?></small>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= site_url('gudang/hasil-moora/detail/' . (int) ($row['id_usulan'] ?? 0)) ?>">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="10" class="text-center text-muted py-4">Belum ada hasil MOORA final.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
