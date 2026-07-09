<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h5 class="fw-bold mb-1">Tambah Data Alat</h5>
    <p class="text-muted mb-4">Form khusus penambahan data alat operasional.</p>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form action="<?= site_url('administrator/master-data/alat/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Kode Alat</label>
                    <input type="text" name="kode_alternatif" class="form-control" placeholder="Contoh: A007" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alternatif" class="form-control" placeholder="Contoh: Mesin Pompa Air" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori Alat</label>
                    <input type="text" name="kategori_barang" class="form-control" placeholder="Contoh: Peralatan Mekanikal">
                </div>

                <div class="mb-3">
                    <label class="form-label">Spesifikasi</label>
                    <textarea name="spesifikasi" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="Contoh: unit">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estimasi Harga</label>
                    <input type="number" name="estimasi_harga" class="form-control" placeholder="Contoh: 45000000">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('administrator/master-data/alat') ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>