<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Engine MOORA Gudang</h2>
            <p class="mb-0 text-muted">Gudang memproses MOORA operasional: RKA dihitung agregat per dokumen, Pesan Cepat dihitung per item barang.</p>
        </div>
        <a href="<?= site_url('gudang/dashboard') ?>" class="btn btn-light btn-sm">← Dashboard Gudang</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('warning')): ?>
        <div class="alert alert-warning border-0 shadow-sm"><?= esc(session()->getFlashdata('warning')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="alert alert-info border-0 shadow-sm">
        <strong>V4 Final Engine:</strong> nilai C1-C5 dibuat otomatis dari data barang, stok, biaya, kondisi, movement type, jenis usulan, dan alasan kebutuhan. Admin tidak lagi menjadi pemroses MOORA operasional.
    </div>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Usulan</th>
                        <th>Unit / Pengusul</th>
                        <th>Jenis</th>
                        <th>Barang</th>
                        <th>Status</th>
                        <th>Terakhir Hitung</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($usulanList)): ?>
                        <?php $i = 1; foreach($usulanList as $usulan): ?>
                        <?php
                            $jenis = $usulan['jenis_usulan'] ?? 'RKA';
                            $modeLabel = strtolower($jenis) === 'pesan cepat' ? 'Per Item' : 'Agregasi RKA';
                            $barangNames = array_map(fn($b) => $b['nama_alternatif'] ?? '-', $usulan['detailBarang'] ?? []);
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <strong><?= esc($usulan['nomor_usulan'] ?? '-') ?></strong><br>
                                <small class="text-muted"><?= !empty($usulan['tanggal_usulan']) ? date('d/m/Y', strtotime($usulan['tanggal_usulan'])) : '-' ?></small>
                            </td>
                            <td>
                                <?= esc($usulan['unit_pengusul'] ?? '-') ?><br>
                                <small class="text-muted"><?= esc($usulan['nama_pengusul'] ?? '-') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= esc($jenis) ?></span><br>
                                <small class="text-muted"><?= esc($modeLabel) ?></small>
                            </td>
                            <td style="max-width:360px;">
                                <?= esc(implode(', ', array_slice($barangNames, 0, 4))) ?>
                                <?php if(count($barangNames) > 4): ?>
                                    <small class="text-muted"> +<?= count($barangNames) - 4 ?> barang</small>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-<?= ($usulan['status'] ?? '') === 'moora_selesai' ? 'success' : 'warning text-dark' ?>"><?= esc(ucwords(str_replace('_',' ', $usulan['status'] ?? '-'))) ?></span></td>
                            <td><?= !empty($usulan['terakhir_dihitung']) ? date('d/m/Y H:i', strtotime($usulan['terakhir_dihitung'])) : '<span class="text-muted">Belum</span>' ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('gudang/penilaian/detail/' . $usulan['id']) ?>" class="btn btn-sm btn-primary">Preview & Proses</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada usulan berstatus diverifikasi/moora_selesai untuk diproses Gudang.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
