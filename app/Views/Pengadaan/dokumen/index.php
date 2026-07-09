<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dokumen Pengadaan</h2>
            <p class="text-muted mb-0">Upload PO, invoice, BAST, surat jalan, dan bukti administrasi pengadaan.</p>
        </div>
        <a href="<?= site_url('pengadaan/dashboard') ?>" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>

    <?php foreach (['success'=>'success','error'=>'danger'] as $key=>$type): ?>
        <?php if (session()->getFlashdata($key)): ?><div class="alert alert-<?= $type ?>"><?= session()->getFlashdata($key) ?></div><?php endif; ?>
    <?php endforeach; ?>

    <div class="alert alert-info border-0 shadow-sm"><strong>Patch 10 Dokumen Lock:</strong> dokumen PO/Invoice/BAST/Surat Jalan menjadi bukti proses sebelum barang diserahkan ke Gudang.</div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Upload Dokumen</h5>
                    <form action="<?= site_url('pengadaan/dokumen/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Pengadaan</label>
                            <select name="id_pengadaan" class="form-select" required>
                                <option value="">Pilih pengadaan</option>
                                <?php foreach ($pengadaan ?? [] as $row): ?>
                                    <option value="<?= esc($row['id']) ?>"><?= esc($row['nomor_pengadaan']) ?> - <?= esc($row['nomor_usulan'] ?? '-') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Dokumen</label>
                            <select name="jenis_dokumen" class="form-select">
                                <?php foreach (['po'=>'PO','invoice'=>'Invoice','bast'=>'BAST','surat_jalan'=>'Surat Jalan','bukti_pembayaran'=>'Bukti Pembayaran','lainnya'=>'Lainnya'] as $k=>$v): ?>
                                    <option value="<?= $k ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Dokumen</label>
                            <input type="text" name="nomor_dokumen" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">File</label>
                            <input type="file" name="file_dokumen" class="form-control" required>
                            <small class="text-muted">PDF, gambar, Excel, atau Word.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan" rows="3" class="form-control"></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Upload Dokumen</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Daftar Dokumen</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light"><tr><th>No</th><th>Usulan</th><th>Jenis</th><th>Nomor</th><th>File</th><th>Upload</th></tr></thead>
                            <tbody>
                            <?php if (!empty($dokumen)): $no=1; foreach ($dokumen as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['nomor_pengadaan'] ?? '-') ?></small></td>
                                    <td><?= esc(ucwords(str_replace('_',' ', $row['jenis_dokumen'] ?? '-'))) ?></td>
                                    <td><?= esc($row['nomor_dokumen'] ?? '-') ?></td>
                                    <td>
                                        <?php $fileName = basename((string)($row['file_path'] ?? '')); ?>
                                        <?php if ($fileName): ?>
                                            <a href="<?= site_url('pengadaan/dokumen/file/'.$fileName) ?>" target="_blank" class="fw-semibold"><?= esc($row['nama_file'] ?? $fileName) ?></a>
                                        <?php else: ?>
                                            <?= esc($row['nama_file'] ?? '-') ?>
                                        <?php endif; ?>
                                        <br><small class="text-muted"><?= esc($row['file_path'] ?? '-') ?></small>
                                    </td>
                                    <td><?= esc($row['uploaded_at'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada dokumen pengadaan.</td></tr>
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
