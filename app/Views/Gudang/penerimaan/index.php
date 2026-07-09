<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <div>
            <h2 class="fw-bold mb-1">Penerimaan Barang</h2>
            <p class="mb-0">
                Pencatatan barang masuk ke gudang dan pembaruan stok secara otomatis.
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


    <div class="card tm-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-1">Penerimaan dari Bagian Pengadaan</h5>
            <p class="text-muted mb-3">Barang yang sudah diserahkan Pengadaan akan masuk daftar ini dan harus dikonfirmasi Gudang.</p>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Usulan</th>
                        <th>Nomor Pengadaan</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Serah</th>
                        <th style="width:260px;">Aksi Terima</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($serahPengadaan)) : $noSerah = 1; foreach ($serahPengadaan as $row) : ?>
                        <tr>
                            <td><?= $noSerah++ ?></td>
                            <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['unit_pengusul'] ?? '-') ?></small></td>
                            <td><?= esc($row['nomor_pengadaan'] ?? '-') ?></td>
                            <td><?= esc($row['kode_alternatif'] ?? '-') ?> - <?= esc($row['nama_alternatif'] ?? '-') ?></td>
                            <td><?= esc($row['jumlah_diserahkan'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                            <td><?= esc($row['tanggal_serah'] ?? '-') ?></td>
                            <td>
                                <form action="<?= site_url('gudang/penerimaan/terima-serah/' . $row['id']) ?>" method="post" class="d-flex gap-1">
                                    <?= csrf_field() ?>
                                    <input type="number" name="jumlah_diterima" min="1" value="<?= esc($row['jumlah_diserahkan'] ?? 1) ?>" class="form-control form-control-sm" style="max-width:90px;">
                                    <input type="hidden" name="catatan_gudang" value="Diterima Gudang">
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Terima barang ini dan update stok?')">Terima</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada barang dari Pengadaan yang menunggu diterima.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Form Penerimaan Barang</h5>
                    <p class="text-muted mb-4">
                        Pilih barang dan masukkan jumlah barang yang diterima.
                    </p>

                    <form action="<?= site_url('gudang/penerimaan/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach (($barang ?? []) as $row) : ?>
                                    <option value="<?= esc($row['id']) ?>">
                                        <?= esc($row['kode_alternatif'] ?? '-') ?> -
                                        <?= esc($row['nama_alternatif'] ?? '-') ?>
                                        | Stok: <?= esc($row['stok'] ?? 0) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Diterima</label>
                            <input type="number"
                                   name="jumlah"
                                   class="form-control"
                                   min="1"
                                   placeholder="Masukkan jumlah barang masuk"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Asal / Sumber Barang</label>
                            <input type="text"
                                name="sumber_barang"
                                class="form-control"
                                placeholder="Contoh: Pengadaan 2026 / Supplier / Pengembalian Unit"
                                value="<?= old('sumber_barang') ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Contoh: Barang masuk dari hasil realisasi pengadaan"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-box-arrow-in-down me-1"></i> Simpan Penerimaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Daftar Stok Barang</h5>
                    <p class="text-muted mb-3">
                        Referensi stok barang sebelum melakukan penerimaan.
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