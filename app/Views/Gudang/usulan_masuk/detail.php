<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

<?php $excelRka = $usulan['file_rka_excel_path'] ?? $usulan['file_rka_path'] ?? null; ?>
<?php if (!empty($excelRka) || !empty($usulan['file_rka_dokumen_path'])) : ?>
    <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Dokumen RKA:</strong>
            <?php if (!empty($excelRka)) : ?>
                <a href="<?= site_url('dokumen-rka/' . basename($excelRka)) ?>" target="_blank" class="ms-2">Excel Import</a>
            <?php endif; ?>
            <?php if (!empty($usulan['file_rka_dokumen_path'])) : ?>
                <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_dokumen_path'])) ?>" target="_blank" class="ms-2">Dokumen Resmi</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Detail Usulan Masuk</h2>
            <p class="mb-0 text-muted">Verifikasi usulan sebelum masuk engine MOORA Gudang.</p>
        </div>
        <a href="<?= site_url('gudang/usulan-masuk') ?>" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card tm-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Usulan</h5>
            <table class="table table-borderless mb-0">
                <tr>
                    <th style="width:220px;">Nomor Usulan</th>
                    <td><?= esc($usulan['nomor_usulan'] ?? 'USL-' . ($usulan['id'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <th>Jenis Usulan</th>
                    <td>
                        <span class="badge bg-primary"><?= esc($usulan['jenis_usulan'] ?? '-') ?></span>
                        <span class="text-muted ms-2"><?= strtolower($usulan['jenis_usulan'] ?? '') === 'pesan cepat' ? 'MOORA per item barang' : 'MOORA agregasi dokumen RKA' ?></span>
                    </td>
                </tr>
                <tr>
                    <th>Pengusul</th>
                    <td><?= esc($usulan['unit_pengusul'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td><?= !empty($usulan['created_at']) ? date('d/m/Y H:i', strtotime($usulan['created_at'])) : '-' ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if(!empty($usulan['status']) && $usulan['status'] === 'banding_gudang'): ?>
                            <span class="badge bg-danger">Gudang Mengajukan Banding</span>
                        <?php else: ?>
                            <span class="badge bg-primary"><?= esc(ucwords(str_replace('_', ' ', $usulan['status'] ?? 'Diajukan'))) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td><?= esc($usulan['catatan_banding_gudang'] ?? $usulan['catatan_verifikasi'] ?? '-') ?></td>
                </tr>
            </table>

            <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">
                <?php if(($usulan['status'] ?? '') === 'diajukan'): ?>
                    <button type="button" class="btn btn-outline-danger" id="btnAjukanBanding">
                        <i class="bi bi-x-circle me-1"></i> Ajukan Banding
                    </button>

                    <form action="<?= site_url('gudang/usulan-masuk/verifikasi/' . ($usulan['id'] ?? 0)) ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Verifikasi usulan ini dan lanjutkan ke engine MOORA Gudang?')">
                            <i class="bi bi-check-circle me-1"></i> Verifikasi & Lanjut MOORA
                        </button>
                    </form>
                <?php elseif(($usulan['status'] ?? '') === 'diverifikasi'): ?>
                    <a href="<?= site_url('gudang/penilaian/detail/' . ($usulan['id'] ?? 0)) ?>" class="btn btn-success">
                        <i class="bi bi-cpu me-1"></i> Proses MOORA Gudang
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <form action="<?= site_url('gudang/usulan-masuk/banding/' . ($usulan['id'] ?? 0)) ?>" method="post" id="formBanding" class="mb-4" style="display:none;">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="keterangan_banding" class="form-label">Alasan Banding</label>
            <textarea name="keterangan_banding" id="keterangan_banding" class="form-control" rows="3" placeholder="Isi alasan banding..." required></textarea>
        </div>
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-check-circle me-1"></i> Kirim Banding
        </button>
    </form>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-2">Preview Barang yang Diusulkan</h5>
            <?php if(!empty($detailBarang)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kode</th>
                                <th>Kategori / Jenis</th>
                                <th>Stok / Min</th>
                                <th>Kondisi</th>
                                <th>Movement</th>
                                <th>Jumlah</th>
                                <th>Estimasi</th>
                                <th>Total</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($detailBarang as $d): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($d['nama_alternatif'] ?? '-') ?></td>
                                <td><?= esc($d['kode_alternatif'] ?? '-') ?></td>
                                <td><?= esc(($d['kategori_barang'] ?? '-') . ' / ' . ($d['jenis_barang'] ?? '-')) ?></td>
                                <td><?= esc(($d['stok'] ?? 0) . ' / ' . ($d['stok_minimum'] ?? 0)) ?></td>
                                <td><?= esc($d['kondisi_barang'] ?? '-') ?></td>
                                <td><?= esc($d['movement_type'] ?? '-') ?></td>
                                <td><?= esc($d['jumlah'] ?? 0) ?> <?= esc($d['satuan'] ?? '-') ?></td>
                                <td>Rp <?= number_format($d['estimasi_harga_satuan'] ?? 0, 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($d['total_estimasi'] ?? 0, 0, ',', '.') ?></td>
                                <td><?= esc($d['alasan_kebutuhan'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mb-0">Belum ada barang yang diusulkan.</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
const btnBanding = document.getElementById('btnAjukanBanding');
if (btnBanding) {
    btnBanding.addEventListener('click', function() {
        const form = document.getElementById('formBanding');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
