<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Barang Pengadaan</h2>
            <p class="text-muted mb-0">
                Daftar barang hasil pengadaan. Status usulan baru menjadi selesai setelah Sub Unit mengonfirmasi semua barang diterima.
            </p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <?php
        $totalAmbil = 0;
        $totalAntar = 0;
        $totalSelesai = 0;

        foreach ($dataBarang as $row) {
            if (($row['jenis_distribusi'] ?? '') === 'diambil') {
                $totalAmbil++;
            }

            if (($row['jenis_distribusi'] ?? '') === 'diantar') {
                $totalAntar++;
            }

            if (($row['status_distribusi'] ?? '') === 'selesai') {
                $totalSelesai++;
            }
        }
        ?>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Harus Diambil</p>
                    <h3 class="fw-bold text-warning mb-0"><?= esc($totalAmbil) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Akan Diantarkan</p>
                    <h3 class="fw-bold text-primary mb-0"><?= esc($totalAntar) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Selesai Diterima</p>
                    <h3 class="fw-bold text-success mb-0"><?= esc($totalSelesai) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-1">Daftar Barang Pengadaan</h5>
            <p class="text-muted mb-3">Barang hasil pengadaan yang sudah diterima Gudang dan menunggu konfirmasi Sub Unit.</p>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Nomor Usulan</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Distribusi</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($dataBarang)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($dataBarang as $row) : ?>
                                <?php
                                $status = $row['status_distribusi'] ?? 'menunggu_pengambilan';

                                $badgeStatus = match ($status) {
                                    'menunggu_pengambilan' => 'warning',
                                    'diambil'              => 'info',
                                    'akan_diantar'         => 'primary',
                                    'diantar'              => 'success',
                                    'selesai'              => 'success',
                                    default                => 'secondary',
                                };

                                $jenis = $row['jenis_distribusi'] ?? 'diambil';

                                $badgeJenis = $jenis === 'diantar' ? 'primary' : 'warning';
                                ?>

                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="fw-semibold"><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['kode_alternatif'] ?? '-') ?></td>
                                    <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $badgeJenis ?>">
                                            <?= $jenis === 'diantar' ? 'Diantarkan' : 'Diambil' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($row['tanggal_jadwal'] ?? '-') ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $badgeStatus ?>">
                                            <?= esc(ucwords(str_replace('_', ' ', $status))) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (in_array($status, ['menunggu_pengambilan', 'akan_diantar', 'diambil', 'diantar'], true)) : ?>
                                            <form action="<?= site_url('sub-unit/barang-pengadaan/konfirmasi-terima/' . $row['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="catatan_pengusul" value="Barang telah diterima oleh sub unit">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi barang sudah diterima?')">
                                                    Konfirmasi Diterima
                                                </button>
                                            </form>
                                        <?php elseif ($status === 'selesai') : ?>
                                            <span class="text-success fw-semibold">Diterima</span>
                                        <?php else : ?>
                                            <span class="text-muted">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada barang pengadaan yang perlu diambil atau diantarkan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>