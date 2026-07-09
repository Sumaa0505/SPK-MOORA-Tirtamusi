<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $ranking = $ranking ?? []; ?>

<div class="container-fluid">
    <div class="tm-page-header mb-4">
        <h2 class="fw-bold mb-1">Hasil MOORA</h2>
        <p class="mb-0 text-muted">Patch 11: ranking aktif dihitung global antar dokumen RKA dan antar item Pesan Cepat yang sudah moora_selesai.</p>
    </div>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Usulan</th>
                        <th>Mode</th>
                        <th>Objek Keputusan</th>
                        <th class="text-end">Benefit</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Nilai Yi</th>
                        <th>Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ranking) && is_array($ranking)) : ?>
                        <?php $no = 1; foreach ($ranking as $r) : ?>
                            <?php $isRka = ($r['mode_hitung'] ?? '') === 'rka_aggregate'; ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= esc($r['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($r['unit_pengusul'] ?? '-') ?></small></td>
                                <td>
                                    <?php if ($isRka) : ?>
                                        <span class="badge bg-success">RKA Agregat</span>
                                    <?php else : ?>
                                        <span class="badge bg-primary">Item Based</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($isRka ? ('Dokumen RKA - ' . ($r['unit_pengusul'] ?? '-')) : ($r['nama_alternatif'] ?? $r['nama_barang'] ?? '-')) ?></td>
                                <td class="text-end"><?= $r['nilai_benefit'] !== null ? number_format((float)$r['nilai_benefit'], 6, ',', '.') : '-' ?></td>
                                <td class="text-end"><?= $r['nilai_cost'] !== null ? number_format((float)$r['nilai_cost'], 6, ',', '.') : '-' ?></td>
                                <td class="text-end fw-bold"><?= isset($r['nilai_yi']) ? number_format((float)$r['nilai_yi'], 6, ',', '.') : '-' ?></td>
                                <td><span class="badge bg-warning text-dark">#<?= esc($r['ranking'] ?? '-') ?></span><?php if(isset($r['ranking_lokal'])): ?><br><small class="text-muted">lokal #<?= esc($r['ranking_lokal']) ?></small><?php endif; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="8" class="text-center text-muted">Belum ada data MOORA</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
