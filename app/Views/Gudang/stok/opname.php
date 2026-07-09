<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <div>
            <h2 class="fw-bold mb-1">Stock Opname Gudang</h2>
            <p class="mb-0">Penyesuaian stok fisik barang gudang dengan data stok pada sistem.</p>
        </div>

        <a href="<?= site_url('gudang/stok') ?>" class="btn btn-light">
            <i class="bi bi-box-seam me-1"></i> Data Stok
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Daftar Penyesuaian Stok</h5>

            <div class="table-responsive">
                <table class="table tm-table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Minimum</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($barang)): ?>
                            <?php $no=1; foreach($barang as $row): ?>
                            <?php
                                $stok = (int)($row['stok'] ?? 0);
                                $min  = (int)($row['stok_minimum'] ?? 0);

                                if ($stok <= 0) { $badge='danger'; $status='Habis'; }
                                elseif($stok <= $min) { $badge='warning text-dark'; $status='Minimum'; }
                                else { $badge='success'; $status='Aman'; }
                            ?>
                            <tr>
                                <form action="<?= site_url('gudang/stok/opname/update/'.$row['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center fw-semibold"><?= esc($row['kode_alternatif'] ?? '-') ?></td>
                                    <td class="fw-semibold"><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['satuan'] ?? '-') ?></td>
                                    <td class="text-center fw-bold"><?= esc($stok) ?></td>
                                    <td><input type="number" name="stok" value="<?= esc($stok) ?>" min="0" class="form-control form-control-sm text-center" required></td>
                                    <td><input type="number" name="stok_minimum" value="<?= esc($min) ?>" min="0" class="form-control form-control-sm text-center" required></td>
                                    <td class="text-center"><span class="badge bg-<?= $badge ?>"><?= esc($status) ?></span></td>
                                    <td class="text-center"><button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Simpan</button></td>
                                </form>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data barang untuk stock opname.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>