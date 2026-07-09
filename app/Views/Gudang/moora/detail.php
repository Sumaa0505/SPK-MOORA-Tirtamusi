<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$usulan = $usulan ?? [];
$hasil = $hasil ?? [];
$detailBarang = $detailBarang ?? [];
$detailMap = $detailMap ?? [];
$modeUtama = $hasil[0]['mode_hitung'] ?? null;
$isRkaAggregate = $modeUtama === 'rka_aggregate';
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Detail Hasil MOORA</h2>
            <p class="mb-0 text-muted">Preview final engine V6 untuk membedakan hasil RKA agregat dan Pesan Cepat per item.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('gudang/hasil-moora') ?>" class="btn btn-light btn-sm">← Hasil MOORA</a>
            <a href="<?= site_url('gudang/penilaian/detail/' . (int) ($usulan['id'] ?? 0)) ?>" class="btn btn-primary btn-sm">Proses Ulang</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3 col-md-6">
                    <small class="text-muted">Nomor Usulan</small><br>
                    <strong><?= esc($usulan['nomor_usulan'] ?? '-') ?></strong>
                </div>
                <div class="col-lg-3 col-md-6">
                    <small class="text-muted">Unit Pengusul</small><br>
                    <strong><?= esc($usulan['unit_pengusul'] ?? '-') ?></strong>
                </div>
                <div class="col-lg-2 col-md-6">
                    <small class="text-muted">Jenis</small><br>
                    <span class="badge bg-primary"><?= esc($usulan['jenis_usulan'] ?? '-') ?></span>
                </div>
                <div class="col-lg-2 col-md-6">
                    <small class="text-muted">Mode Hitung</small><br>
                    <?php if ($isRkaAggregate) : ?>
                        <span class="badge bg-success">rka_aggregate</span>
                    <?php else : ?>
                        <span class="badge bg-info text-dark"><?= esc($modeUtama ?: '-') ?></span>
                    <?php endif; ?>
                </div>
                <div class="col-lg-2 col-md-6">
                    <small class="text-muted">Versi</small><br>
                    <strong><?= esc($versi ?? '-') ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isRkaAggregate) : ?>
        <div class="alert alert-success border-0 shadow-sm">
            <strong>RKA Agregat:</strong> seluruh barang di bawah dokumen RKA ini membentuk satu nilai Yi. Barang tetap tampil sebagai rincian pembentuk nilai, bukan ranking terpisah.
        </div>
    <?php elseif (!empty($hasil)) : ?>
        <div class="alert alert-info border-0 shadow-sm">
            <strong>Pesan Cepat Item:</strong> setiap barang menghasilkan nilai Yi dan ranking sendiri dalam usulan ini.
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Hasil Perhitungan</strong></div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ranking</th>
                        <th>Objek Keputusan</th>
                        <th class="text-end">Benefit</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Nilai Yi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hasil)) : ?>
                        <?php foreach ($hasil as $row) : ?>
                            <tr>
                                <td><span class="badge bg-warning text-dark">#<?= (int) ($row['ranking'] ?? 0) ?></span></td>
                                <td>
                                    <strong><?= esc($isRkaAggregate ? ('Agregasi Dokumen RKA - ' . ($usulan['unit_pengusul'] ?? '-')) : ($row['nama_alternatif'] ?? '-')) ?></strong><br>
                                    <small class="text-muted"><?= esc($isRkaAggregate ? ($usulan['nomor_usulan'] ?? '-') : ($row['kode_alternatif'] ?? '-')) ?></small>
                                </td>
                                <td class="text-end"><?= $row['nilai_benefit'] !== null ? number_format((float) $row['nilai_benefit'], 8, ',', '.') : '-' ?></td>
                                <td class="text-end"><?= $row['nilai_cost'] !== null ? number_format((float) $row['nilai_cost'], 8, ',', '.') : '-' ?></td>
                                <td class="text-end fw-bold"><?= number_format((float) ($row['nilai_yi'] ?? 0), 8, ',', '.') ?></td>
                                <td><?= esc($row['catatan_hitung'] ?? '-') ?></td>
                            </tr>
                            <?php if (!empty($row['rincian_decoded']['kriteria'])) : ?>
                                <tr>
                                    <td></td>
                                    <td colspan="5">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Kriteria</th>
                                                        <th>Jenis</th>
                                                        <th class="text-end">Bobot</th>
                                                        <th class="text-end">Nilai Awal</th>
                                                        <th class="text-end">Normalisasi</th>
                                                        <th class="text-end">Terbobot</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($row['rincian_decoded']['kriteria'] as $k) : ?>
                                                        <tr>
                                                            <td><strong><?= esc($k['kode_kriteria'] ?? '-') ?></strong> - <?= esc($k['nama_kriteria'] ?? '-') ?></td>
                                                            <td><?= esc($k['jenis'] ?? '-') ?></td>
                                                            <td class="text-end"><?= number_format((float) ($k['bobot'] ?? 0), 2, ',', '.') ?></td>
                                                            <td class="text-end"><?= number_format((float) ($k['nilai_awal'] ?? 0), 4, ',', '.') ?></td>
                                                            <td class="text-end"><?= number_format((float) ($k['normalisasi'] ?? 0), 8, ',', '.') ?></td>
                                                            <td class="text-end"><?= number_format((float) ($k['terbobot'] ?? 0), 8, ',', '.') ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada hasil MOORA untuk usulan ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Rincian Barang Pembentuk Keputusan</strong><br>
                <small class="text-muted"><?= $isRkaAggregate ? 'Untuk RKA, daftar ini adalah sumber agregasi nilai C1-C5.' : 'Untuk Pesan Cepat, daftar ini adalah item yang diranking.' ?></small>
            </div>
            <span class="badge bg-light text-dark"><?= count($detailBarang) ?> barang</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th class="text-end">Estimasi</th>
                        <th>Stok/Kondisi</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($detailBarang)) : ?>
                        <?php foreach ($detailBarang as $i => $d) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= esc($d['nama_alternatif'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($d['kode_alternatif'] ?? '-') ?></small></td>
                                <td><?= number_format((float) ($d['jumlah'] ?? 0), 0, ',', '.') ?> <?= esc($d['satuan'] ?? '') ?></td>
                                <td class="text-end">Rp <?= number_format((float) (($d['total_estimasi'] ?? 0) ?: ((float) ($d['estimasi_harga_satuan'] ?? 0) * (int) ($d['jumlah'] ?? 1))), 0, ',', '.') ?></td>
                                <td>
                                    Stok: <?= (int) ($d['stok'] ?? 0) ?> / Min: <?= (int) ($d['stok_minimum'] ?? 0) ?><br>
                                    <small class="text-muted"><?= esc($d['kondisi_barang'] ?? '-') ?> · <?= esc($d['movement_type'] ?? '-') ?></small>
                                </td>
                                <td><?= esc($d['alasan_kebutuhan'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Detail barang tidak tersedia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
