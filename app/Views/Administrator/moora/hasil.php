<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$usulan = $usulan ?? [];
$hasil = $hasil ?? [];
$kriteria = $kriteria ?? [];
$penilaianMap = $penilaianMap ?? [];
$riwayatHasil = $riwayatHasil ?? [];
$modeUtama = $hasil[0]['mode_hitung'] ?? null;
$isRkaAggregate = $modeUtama === 'rka_aggregate';
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Hasil Perhitungan MOORA</h2>
            <p class="mb-0 text-muted">Monitoring Admin. Hasil resmi diproses oleh Gudang; halaman ini hanya preview dan audit hasil.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('administrator/moora-audit') ?>" class="btn btn-outline-primary btn-sm">Audit Engine V6</a>
            <a href="<?= site_url('administrator/kalkulasi-moora') ?>" class="btn btn-light btn-sm">← Monitoring MOORA</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')) : ?>
        <div class="alert alert-warning border-0 shadow-sm"><?= esc(session()->getFlashdata('warning')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6"><small class="text-muted">Nomor Usulan</small><br><strong><?= esc($usulan['nomor_usulan'] ?? '-') ?></strong></div>
                <div class="col-lg-3 col-md-6"><small class="text-muted">Unit Pengusul</small><br><strong><?= esc($usulan['unit_pengusul'] ?? '-') ?></strong></div>
                <div class="col-lg-2 col-md-6"><small class="text-muted">Jenis</small><br><span class="badge bg-primary"><?= esc($usulan['jenis_usulan'] ?? '-') ?></span></div>
                <div class="col-lg-2 col-md-6"><small class="text-muted">Status</small><br><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_', ' ', $usulan['status'] ?? '-'))) ?></span></div>
                <div class="col-lg-2 col-md-6"><small class="text-muted">Mode Hasil</small><br><?php if ($isRkaAggregate) : ?><span class="badge bg-success">rka_aggregate</span><?php else : ?><span class="badge bg-info text-dark"><?= esc($modeUtama ?: '-') ?></span><?php endif; ?></div>
            </div>
        </div>
    </div>

    <?php if ($isRkaAggregate) : ?>
        <div class="alert alert-success border-0 shadow-sm"><strong>RKA Agregat:</strong> satu dokumen RKA menghasilkan satu nilai Yi. Barang menjadi rincian pembentuk nilai C1-C5.</div>
    <?php elseif (!empty($hasil)) : ?>
        <div class="alert alert-info border-0 shadow-sm"><strong>Pesan Cepat Item:</strong> satu barang menghasilkan satu nilai Yi dan ranking sendiri.</div>
    <?php endif; ?>

    <div class="alert alert-light border shadow-sm">
        <strong>Patch 9 Single Source:</strong> tabel utama di bawah membaca hasil aktif dari <code>v_latest_moora_context</code>. Histori audit tetap tersimpan di <code>hasil_moora</code> sebanyak <?= count($riwayatHasil) ?> baris.
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div><strong>Ranking Hasil MOORA</strong><br><small class="text-muted">Data hasil bersifat otomatis dan tidak boleh diedit manual.</small></div>
            <span class="badge bg-light text-dark">Total <?= count($hasil) ?> hasil</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Ranking</th>
                        <th>Objek Keputusan</th>
                        <th>Jumlah</th>
                        <th>Nilai Kriteria</th>
                        <th class="text-end">Benefit</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Nilai Yi</th>
                        <th>Total Estimasi</th>
                        <th>Tanggal Hitung</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hasil)) : ?>
                        <?php foreach ($hasil as $row) : ?>
                            <?php
                                $decoded = !empty($row['rincian_json']) ? (json_decode((string) $row['rincian_json'], true) ?: []) : [];
                                $kriteriaRincian = $decoded['kriteria'] ?? [];
                            ?>
                            <tr>
                                <td class="text-center"><span class="badge bg-warning text-dark">#<?= esc($row['ranking']) ?></span></td>
                                <td>
                                    <strong><?= esc($isRkaAggregate ? ('Agregasi Dokumen RKA - ' . ($usulan['unit_pengusul'] ?? '-')) : ($row['nama_alternatif'] ?? '-')) ?></strong><br>
                                    <small class="text-muted"><?= esc($isRkaAggregate ? ($usulan['nomor_usulan'] ?? '-') : (($row['kode_alternatif'] ?? '-') . ' · ' . ($row['kategori_barang'] ?? '-'))) ?></small>
                                </td>
                                <td><?= $isRkaAggregate ? '1 dokumen' : (number_format((float) ($row['jumlah'] ?? 0), 0, ',', '.') . ' ' . esc($row['satuan'] ?? '')) ?></td>
                                <td>
                                    <?php if (!empty($kriteriaRincian)) : ?>
                                        <?php foreach ($kriteriaRincian as $k) : ?>
                                            <span class="badge bg-light text-dark border me-1 mb-1" title="<?= esc($k['nama_kriteria'] ?? '') ?>">
                                                <?= esc($k['kode_kriteria'] ?? '-') ?>: <?= number_format((float)($k['nilai_awal'] ?? 0), 2, ',', '.') ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <?php foreach ($kriteria as $k) : ?>
                                            <?php $nilai = $penilaianMap[$row['id_detail_usulan']][$k['id']]['nilai'] ?? null; ?>
                                            <span class="badge bg-light text-dark border me-1 mb-1"><?= esc($k['kode_kriteria'] ?? '') ?>: <?= $nilai !== null ? number_format((float) $nilai, 2, ',', '.') : '-' ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= $row['nilai_benefit'] !== null ? number_format((float)$row['nilai_benefit'], 8, ',', '.') : '-' ?></td>
                                <td class="text-end"><?= $row['nilai_cost'] !== null ? number_format((float)$row['nilai_cost'], 8, ',', '.') : '-' ?></td>
                                <td class="text-end fw-bold"><?= number_format((float) ($row['nilai_yi'] ?? 0), 8, ',', '.') ?></td>
                                <td>Rp <?= number_format((float) ($row['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                                <td><?= !empty($row['tanggal_hitung']) ? date('d/m/Y H:i', strtotime($row['tanggal_hitung'])) : '-' ?><br><small class="text-muted">Versi <?= esc($row['versi_hitung'] ?? '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada hasil MOORA.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
