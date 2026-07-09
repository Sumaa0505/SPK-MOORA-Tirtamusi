<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Proses Pembelian</h2>
            <p class="text-muted mb-0">Mencatat vendor, nomor PO, dan status pembelian dari disposisi Direktur.</p>
        </div>
        <a href="<?= site_url('pengadaan/dashboard') ?>" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>

    <?php foreach (['success'=>'success','error'=>'danger','warning'=>'warning'] as $key=>$type): ?>
        <?php if (session()->getFlashdata($key)): ?><div class="alert alert-<?= $type ?>"><?= session()->getFlashdata($key) ?></div><?php endif; ?>
    <?php endforeach; ?>

    <div class="alert alert-info border-0 shadow-sm">
        <strong>Patch 10 Workflow Lock:</strong> status pengadaan tidak boleh ditutup manual sebagai selesai. Upload dokumen pengadaan dahulu, serahkan barang ke Gudang, lalu status selesai akan terjadi setelah Sub Unit mengonfirmasi penerimaan barang.
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Buat Data Pembelian</h5>
                    <form action="<?= site_url('pengadaan/pembelian/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Usulan Disposisi</label>
                            <select name="id_usulan" class="form-select" required>
                                <option value="">Pilih usulan</option>
                                <?php foreach ($usulan ?? [] as $row): ?>
                                    <option value="<?= esc($row['id']) ?>"><?= esc($row['nomor_usulan']) ?> - <?= esc($row['unit_pengusul']) ?> (Rp <?= number_format((float)$row['total_anggaran'],0,',','.') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Vendor</label>
                            <input type="text" name="vendor" class="form-control" placeholder="Nama vendor/rekanan">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor PO</label>
                                <input type="text" name="nomor_po" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal PO</label>
                                <input type="date" name="tanggal_po" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Tanggal Pengadaan</label>
                            <input type="date" name="tanggal_pengadaan" value="<?= date('Y-m-d') ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan proses pembelian"></textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit" onclick="return confirm('Proses usulan ini sebagai pembelian?')">Simpan Pembelian</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Riwayat Pembelian</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr><th>No</th><th>Nomor</th><th>Usulan</th><th>Vendor/PO</th><th>Total</th><th>Dokumen</th><th>Status</th><th>Update</th></tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($pembelian)): $no=1; foreach ($pembelian as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td class="fw-bold"><?= esc($row['nomor_pengadaan']) ?></td>
                                    <td><?= esc($row['nomor_usulan'] ?? '-') ?><br><small class="text-muted"><?= esc($row['unit_pengusul'] ?? '-') ?></small></td>
                                    <td><?= esc($row['vendor'] ?? '-') ?><br><small class="text-muted"><?= esc($row['nomor_po'] ?? 'PO belum diisi') ?></small></td>
                                    <td>Rp <?= number_format((float)($row['total_pengadaan'] ?? 0),0,',','.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= ((int)($row['jumlah_dokumen'] ?? 0) > 0) ? 'success' : 'warning text-dark' ?>">
                                            <?= (int)($row['jumlah_dokumen'] ?? 0) ?> file
                                        </span><br>
                                        <small class="text-muted">PO: <?= (int)($row['dok_po'] ?? 0) ?> · Invoice: <?= (int)($row['dok_invoice'] ?? 0) ?> · BAST: <?= (int)($row['dok_bast'] ?? 0) ?></small>
                                    </td>
                                    <td><span class="badge bg-info text-dark"><?= esc(ucwords(str_replace('_',' ', $row['status_pengadaan'] ?? '-'))) ?></span></td>
                                    <td>
                                        <form action="<?= site_url('pengadaan/pembelian/update-status/'.$row['id']) ?>" method="post" class="d-flex gap-1">
                                            <?= csrf_field() ?>
                                            <select name="status_pengadaan" class="form-select form-select-sm">
                                                <?php foreach (['menunggu','diproses','po_terbit','barang_datang','diserahkan_gudang','dibatalkan'] as $st): ?>
                                                    <option value="<?= $st ?>" <?= ($row['status_pengadaan'] ?? '')===$st?'selected':'' ?>><?= ucwords(str_replace('_',' ', $st)) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-sm btn-outline-primary">OK</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pembelian.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?= site_url('pengadaan/serah-barang') ?>" class="btn btn-success">Lanjut Serah Barang ke Gudang</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
