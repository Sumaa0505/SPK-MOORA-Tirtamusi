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

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Detail Usulan Pengadaan</h2>
            <p class="text-muted mb-0">Informasi lengkap tentang usulan barang dari Sub Unit.</p>
        </div>
        <a href="<?= site_url('sub-unit/usulan') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Informasi Usulan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Umum</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <strong>Nomor Usulan:</strong> <?= esc($usulan['nomor_usulan']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Tanggal Usulan:</strong> <?= esc($usulan['tanggal_usulan']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Unit Pengusul:</strong> <?= esc($usulan['unit_pengusul']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Jenis Usulan:</strong> <?= esc($usulan['jenis_usulan']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong> <?= esc($usulan['status']) ?>
                </div>
                <div class="col-12">
                    <strong>Catatan Pengusul:</strong>
                    <p><?= esc($usulan['catatan_pengusul'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Barang -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Detail Barang Usulan</h5>

            <?php if (!empty($detailList)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Spesifikasi</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Estimasi Satuan</th>
                                <th>Total Estimasi</th>
                                <th>Alasan Kebutuhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detailList as $i => $item): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($item['kode_alternatif']) ?></td>
                                    <td><?= esc($item['nama_alternatif']) ?></td>
                                    <td><?= esc($item['kategori_barang']) ?></td>
                                    <td><?= esc($item['jenis_barang']) ?></td>
                                    <td><?= esc($item['spesifikasi']) ?></td>
                                    <td><?= esc($item['satuan']) ?></td>
                                    <td class="text-center"><?= esc($item['jumlah']) ?></td>
                                    <td class="text-end"><?= number_format($item['estimasi_harga_satuan'], 2) ?></td>
                                    <td class="text-end"><?= number_format($item['total_estimasi'], 2) ?></td>
                                    <td><?= esc($item['alasan_kebutuhan']) ?></td>
                                    <td><?= esc($item['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-end fw-bold">
                    Total Estimasi: Rp <?= number_format(array_sum(array_column($detailList, 'total_estimasi')), 2) ?>
                </p>
            <?php else: ?>
                <p class="text-center text-muted">Belum ada barang yang diusulkan.</p>
            <?php endif; ?>

            <!-- Tombol Ajukan jika masih draft -->
            <?php if($usulan['status'] === 'draft'): ?>
                <form action="<?= site_url('sub-unit/usulan/ajukan/' . $usulan['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Ajukan Usulan ke Seksi Gudang</button>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>


    <!-- Hasil MOORA Final -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Hasil MOORA</h5>
                    <p class="text-muted mb-0">Sumber hasil memakai latest MOORA final. RKA tampil sebagai agregasi dokumen, Pesan Cepat tampil per item.</p>
                </div>
                <?php if (!empty($hasilMoora[0]['mode_hitung'])): ?>
                    <span class="badge bg-info"><?= esc($hasilMoora[0]['mode_hitung'] === 'rka_aggregate' ? 'RKA Agregat' : 'Pesan Cepat per Item') ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($hasilMoora)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="80">Ranking</th>
                                <th>Keputusan / Barang</th>
                                <th>Mode</th>
                                <th>Nilai Yi</th>
                                <th>Tanggal Hitung</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hasilMoora as $row): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($row['ranking'] ?? '-') ?></td>
                                    <td><?= esc($row['nama_alternatif'] ?? $row['jenis_keputusan'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['mode_hitung'] ?? '-') ?></td>
                                    <td class="text-end fw-semibold"><?= number_format((float) ($row['nilai_yi'] ?? 0), 6) ?></td>
                                    <td class="text-center"><?= esc($row['tanggal_hitung'] ?? '-') ?></td>
                                    <td><?= esc($row['catatan_hitung'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border mb-0">Hasil MOORA belum tersedia. Usulan masih menunggu proses Gudang.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tracking Pengadaan & Distribusi -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Tracking Pengadaan & Distribusi</h5>
            <?php if (!empty($distribusi)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Status Distribusi</th>
                                <th>Jadwal</th>
                                <th>Diterima Sub Unit</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($distribusi as $i => $row): ?>
                                <?php $statusDistribusi = $row['status_distribusi'] ?? 'menunggu_pengambilan'; ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                                    <td class="text-center"><span class="badge bg-<?= $statusDistribusi === 'selesai' ? 'success' : 'warning' ?>"><?= esc(ucwords(str_replace('_', ' ', $statusDistribusi))) ?></span></td>
                                    <td class="text-center"><?= esc($row['tanggal_jadwal'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['diterima_oleh_pengusul_at'] ?? '-') ?></td>
                                    <td><?= esc($row['catatan_gudang'] ?? $row['catatan_pengusul'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border mb-0">Distribusi belum dibuat. Data akan tampil setelah Pengadaan menyerahkan barang dan Gudang menerima barang.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Timeline Validasi -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Timeline Workflow</h5>
            <?php if (!empty($riwayat)): ?>
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Role</th>
                                <th>Aksi</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riwayat as $row): ?>
                                <tr>
                                    <td><?= esc($row['tanggal_aksi'] ?? $row['created_at'] ?? '-') ?></td>
                                    <td><?= esc(ucwords(str_replace('_', ' ', $row['role_user'] ?? '-'))) ?></td>
                                    <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_', ' ', $row['aksi'] ?? '-'))) ?></span></td>
                                    <td><?= esc($row['nama_user'] ?? '-') ?></td>
                                    <td><?= esc($row['catatan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border mb-0">Belum ada riwayat validasi.</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>