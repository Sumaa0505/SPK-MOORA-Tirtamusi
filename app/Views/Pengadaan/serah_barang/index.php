<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Serah Barang ke Gudang</h2>
            <p class="text-muted mb-0">Mencatat barang hasil pembelian yang diserahkan ke Seksi Gudang untuk diterima dan update stok.</p>
        </div>
        <a href="<?= site_url('pengadaan/dashboard') ?>" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>

    <?php foreach (['success'=>'success','error'=>'danger'] as $key=>$type): ?>
        <?php if (session()->getFlashdata($key)): ?><div class="alert alert-<?= $type ?>"><?= session()->getFlashdata($key) ?></div><?php endif; ?>
    <?php endforeach; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Form Serah Barang</h5>
                    <form action="<?= site_url('pengadaan/serah-barang/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Pengadaan</label>
                            <select name="id_pengadaan" class="form-select" required>
                                <option value="">Pilih pengadaan</option>
                                <?php foreach ($pengadaan ?? [] as $row): ?>
                                    <option value="<?= esc($row['id']) ?>"><?= esc($row['nomor_pengadaan']) ?> - <?= esc($row['nomor_usulan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang Usulan</label>
                            <select name="id_detail_usulan" class="form-select" required>
                                <option value="">Pilih barang</option>
                                <?php foreach ($detail ?? [] as $row): ?>
                                    <option value="<?= esc($row['id']) ?>"><?= esc($row['nomor_usulan']) ?> - <?= esc($row['nama_alternatif']) ?> (Sisa <?= esc($row['sisa_serah'] ?? $row['jumlah']) ?> dari <?= esc($row['jumlah']) ?> <?= esc($row['satuan']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Pastikan barang sesuai dengan nomor usulan pada nomor pengadaan.</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah</label>
                                <input type="number" name="jumlah_diserahkan" min="1" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="date" name="tanggal_serah" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan_pengadaan" rows="3" class="form-control"></textarea>
                        </div>
                        <button class="btn btn-success w-100" onclick="return confirm('Serahkan barang ini ke Gudang?')">Serahkan ke Gudang</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Riwayat Serah Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light"><tr><th>No</th><th>Usulan</th><th>Barang</th><th>Jumlah</th><th>Tanggal</th><th>Status</th></tr></thead>
                            <tbody>
                            <?php if (!empty($serah)): $no=1; foreach ($serah as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['nomor_pengadaan'] ?? '-') ?></small></td>
                                    <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                    <td><?= esc($row['jumlah_diserahkan']) ?> <?= esc($row['satuan'] ?? '') ?></td>
                                    <td><?= esc($row['tanggal_serah'] ?? '-') ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= esc(ucwords(str_replace('_',' ', $row['status_serah'] ?? '-'))) ?></span></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada barang yang diserahkan.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
