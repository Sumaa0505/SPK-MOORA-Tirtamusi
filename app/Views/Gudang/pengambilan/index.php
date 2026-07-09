<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pengambilan Barang</h2>
            <p class="mb-0">
                Pencatatan barang keluar dari gudang dan pembaruan stok secara otomatis.
            </p>
        </div>

        <a href="<?= site_url('gudang/stok') ?>" class="btn btn-light">
            <i class="bi bi-box-seam me-1"></i> Data Stok
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Form Pengambilan Barang</h5>
                    <p class="text-muted mb-4">
                        Pilih barang dan masukkan jumlah barang yang keluar dari gudang.
                    </p>

                    <form action="<?= site_url('gudang/pengambilan/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach (($barang ?? []) as $row) : ?>
                                    <?php $stok = (int) ($row['stok'] ?? 0); ?>
                                    <option value="<?= esc($row['id']) ?>" <?= $stok <= 0 ? 'disabled' : '' ?>>
                                        <?= esc($row['kode_alternatif'] ?? '-') ?> -
                                        <?= esc($row['nama_alternatif'] ?? '-') ?>
                                        | Stok: <?= esc($stok) ?> <?= esc($row['satuan'] ?? '') ?>
                                        <?= $stok <= 0 ? ' - HABIS' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Diambil</label>
                            <input type="number"
                                   name="jumlah"
                                   class="form-control"
                                   min="1"
                                   placeholder="Masukkan jumlah barang keluar"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Unit Pengambil</label>
                            <input type="text"
                                   name="unit_pengambil"
                                   class="form-control"
                                   placeholder="Contoh: Sub Unit Produksi / Distribusi / Teknik">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Contoh: Digunakan untuk perbaikan jaringan distribusi"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-box-arrow-up me-1"></i> Simpan Pengambilan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Ketersediaan Barang</h5>
                    <p class="text-muted mb-3">
                        Referensi stok sebelum melakukan pengambilan barang.
                    </p>

                    <div class="table-responsive">
                        <table class="table tm-table align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                    <th>Minimum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($barang)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($barang as $row) : ?>
                                        <?php
                                        $stok = (int) ($row['stok'] ?? 0);
                                        $min  = (int) ($row['stok_minimum'] ?? 0);

                                        if ($stok <= 0) {
                                            $badge = 'danger';
                                            $status = 'Habis';
                                        } elseif ($min > 0 && $stok <= $min) {
                                            $badge = 'warning text-dark';
                                            $status = 'Minimum';
                                        } else {
                                            $badge = 'success';
                                            $status = 'Aman';
                                        }
                                        ?>

                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-center fw-semibold"><?= esc($row['kode_alternatif'] ?? '-') ?></td>
                                            <td class="fw-semibold"><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                            <td class="text-center"><?= esc($row['satuan'] ?? '-') ?></td>
                                            <td class="text-center fw-bold"><?= esc($stok) ?></td>
                                            <td class="text-center"><?= esc($min) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $badge ?>">
                                                    <?= esc($status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada data barang.
                                        </td>
                                    </tr>
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