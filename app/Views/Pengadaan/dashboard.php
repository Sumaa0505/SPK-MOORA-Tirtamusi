<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Pengadaan</h2>
            <p class="text-muted mb-0">Memproses disposisi Direktur menjadi pembelian, dokumen, dan serah barang ke Gudang.</p>
        </div>
        <a href="<?= site_url('pengadaan/pembelian') ?>" class="btn btn-primary"><i class="bi bi-cart-check me-1"></i> Proses Pembelian</a>
    </div>

    <div class="row g-3 mb-4">
        <?php
        $cards = [
            ['label' => 'Disposisi Baru', 'value' => $total_disposisi ?? 0, 'icon' => 'bi-envelope-paper'],
            ['label' => 'Sedang Diproses', 'value' => $total_diproses ?? 0, 'icon' => 'bi-arrow-repeat'],
            ['label' => 'Menunggu Gudang', 'value' => $total_serah_gudang ?? 0, 'icon' => 'bi-box-arrow-in-down'],
            ['label' => 'Selesai', 'value' => $total_selesai ?? 0, 'icon' => 'bi-check2-circle'],
        ];
        ?>
        <?php foreach ($cards as $card): ?>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-semibold"><?= esc($card['label']) ?></div>
                            <div class="fs-3 fw-bold"><?= esc($card['value']) ?></div>
                        </div>
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                            <i class="bi <?= esc($card['icon']) ?> fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Usulan Aktif Pengadaan</h5>
            <div class="d-flex gap-2">
                <a href="<?= site_url('pengadaan/dokumen') ?>" class="btn btn-sm btn-outline-primary">Dokumen</a>
                <a href="<?= site_url('pengadaan/serah-barang') ?>" class="btn btn-sm btn-outline-success">Serah Barang</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Usulan</th>
                        <th>Unit</th>
                        <th>Pengusul</th>
                        <th>Total Estimasi</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($usulan)): $no=1; foreach ($usulan as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-bold"><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                            <td><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                            <td><?= esc($row['nama_pengusul'] ?? '-') ?></td>
                            <td>Rp <?= number_format((float)($row['total_anggaran'] ?? 0), 0, ',', '.') ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= esc(ucwords(str_replace('_', ' ', $row['status'] ?? '-'))) ?></span></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada usulan aktif untuk Pengadaan.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
