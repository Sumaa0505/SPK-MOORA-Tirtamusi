<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$jenisData = $jenis ?? '-';

$kategoriList = [];
$satuanList   = [];

if (!empty($dataBarang)) {
    foreach ($dataBarang as $item) {
        if (!empty($item['kategori_barang'])) {
            $kategoriList[] = $item['kategori_barang'];
        }

        if (!empty($item['satuan'])) {
            $satuanList[] = $item['satuan'];
        }
    }
}

$kategoriList = array_unique($kategoriList);
$satuanList   = array_unique($satuanList);

sort($kategoriList);
sort($satuanList);
?>

<div class="container-fluid">

    <!-- HEADER HALAMAN -->
    <div class="page-header mb-4">
        <div>
            <h5 class="fw-bold mb-1">
                <?= esc($title ?? 'Master Data') ?>
            </h5>
            <small class="text-muted">
                Perumda Tirta Musi Palembang
            </small>
        </div>
    </div>

    <!-- INFORMASI DAN TOMBOL TAMBAH -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="mb-0 text-muted">
                Pengelolaan data <?= esc($jenisData) ?> operasional Perumda Tirta Musi Palembang.
            </p>
        </div>

        <div>
            <?php if ($jenisData == 'alat') : ?>
                <a href="<?= site_url('administrator/master-data/alat/create') ?>" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Alat
                </a>
            <?php elseif ($jenisData == 'material') : ?>
                <a href="<?= site_url('administrator/master-data/material/create') ?>" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Material
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- CARD FILTER DAN PENCARIAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-funnel me-1"></i> Filter dan Pencarian Data
                    </h6>
                    <small class="text-muted">
                        Gunakan fitur ini untuk mencari data berdasarkan kode, nama, kategori, satuan, dan estimasi harga.
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    Total Data: <span id="totalData"><?= count($dataBarang ?? []) ?></span>
                </span>
            </div>

            <div class="row g-3">

                <!-- PENCARIAN -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               id="searchInput"
                               class="form-control"
                               placeholder="Cari kode, nama, kategori, keterangan...">
                    </div>
                </div>

                <!-- FILTER KATEGORI -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Kategori</label>
                    <select id="filterKategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategoriList as $kategori) : ?>
                            <option value="<?= esc(strtolower($kategori)) ?>">
                                <?= esc($kategori) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FILTER SATUAN -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Filter Satuan</label>
                    <select id="filterSatuan" class="form-select">
                        <option value="">Semua Satuan</option>
                        <?php foreach ($satuanList as $satuan) : ?>
                            <option value="<?= esc(strtolower($satuan)) ?>">
                                <?= esc($satuan) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FILTER HARGA -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Filter Harga</label>
                    <select id="filterHarga" class="form-select">
                        <option value="">Semua Harga</option>
                        <option value="rendah">≤ Rp 1.000.000</option>
                        <option value="sedang">Rp 1.000.001 - Rp 10.000.000</option>
                        <option value="tinggi">> Rp 10.000.000</option>
                    </select>
                </div>

                <!-- RESET -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" id="resetFilter" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD TABEL -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="tableMasterData">
                    <thead class="text-center">
                        <tr>
                            <th width="50">No</th>
                            <th width="90">Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th width="90">Satuan</th>
                            <th width="150">Estimasi Harga</th>
                            <th>Keterangan</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($dataBarang)) : ?>
                            <?php $no = 1; foreach ($dataBarang as $row) : ?>
                                <?php
                                $kode       = $row['kode_alternatif'] ?? '-';
                                $nama       = $row['nama_alternatif'] ?? '-';
                                $kategori   = $row['kategori_barang'] ?? '-';
                                $satuan     = $row['satuan'] ?? '-';
                                $harga      = (float) ($row['estimasi_harga'] ?? 0);
                                $keterangan = $row['keterangan'] ?? '-';

                                $searchData = strtolower(
                                    $kode . ' ' .
                                    $nama . ' ' .
                                    $kategori . ' ' .
                                    $satuan . ' ' .
                                    $harga . ' ' .
                                    $keterangan
                                );
                                ?>

                                <tr class="data-row"
                                    data-search="<?= esc($searchData) ?>"
                                    data-kategori="<?= esc(strtolower($kategori)) ?>"
                                    data-satuan="<?= esc(strtolower($satuan)) ?>"
                                    data-harga="<?= esc((string) $harga) ?>">

                                    <td class="text-center nomor-urut">
                                        <?= $no++ ?>
                                    </td>

                                    <td class="text-center fw-semibold">
                                        <?= esc($kode) ?>
                                    </td>

                                    <td>
                                        <?= esc($nama) ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= esc($kategori) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?= esc($satuan) ?>
                                    </td>

                                    <td class="text-end">
                                        Rp <?= number_format($harga, 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?= esc($keterangan) ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= site_url('administrator/alternatif/edit/' . $row['id']) ?>"
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <a href="<?= site_url('administrator/alternatif/delete/' . $row['id']) ?>"
                                               onclick="return confirm('Yakin ingin menghapus data ini?')"
                                               class="btn btn-danger btn-sm">
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr id="emptyRow" style="display: none;">
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-search me-1"></i>
                                    Data tidak ditemukan berdasarkan filter atau pencarian.
                                </td>
                            </tr>

                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada data <?= esc($jenisData) ?>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('searchInput');
    const filterKategori = document.getElementById('filterKategori');
    const filterSatuan   = document.getElementById('filterSatuan');
    const filterHarga    = document.getElementById('filterHarga');
    const resetFilter    = document.getElementById('resetFilter');
    const totalData      = document.getElementById('totalData');
    const rows           = document.querySelectorAll('.data-row');
    const emptyRow       = document.getElementById('emptyRow');

    function filterTable() {
        const searchValue    = searchInput.value.toLowerCase().trim();
        const kategoriValue  = filterKategori.value.toLowerCase().trim();
        const satuanValue    = filterSatuan.value.toLowerCase().trim();
        const hargaValue     = filterHarga.value;
        let visibleCount     = 0;

        rows.forEach(function (row) {
            const rowSearch   = row.getAttribute('data-search') || '';
            const rowKategori = row.getAttribute('data-kategori') || '';
            const rowSatuan   = row.getAttribute('data-satuan') || '';
            const rowHarga    = parseFloat(row.getAttribute('data-harga') || 0);

            let matchSearch   = rowSearch.includes(searchValue);
            let matchKategori = kategoriValue === '' || rowKategori === kategoriValue;
            let matchSatuan   = satuanValue === '' || rowSatuan === satuanValue;
            let matchHarga    = true;

            if (hargaValue === 'rendah') {
                matchHarga = rowHarga <= 1000000;
            } else if (hargaValue === 'sedang') {
                matchHarga = rowHarga > 1000000 && rowHarga <= 10000000;
            } else if (hargaValue === 'tinggi') {
                matchHarga = rowHarga > 10000000;
            }

            if (matchSearch && matchKategori && matchSatuan && matchHarga) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        updateNomorUrut();

        if (totalData) {
            totalData.textContent = visibleCount;
        }

        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    function updateNomorUrut() {
        let nomor = 1;

        rows.forEach(function (row) {
            if (row.style.display !== 'none') {
                const nomorCell = row.querySelector('.nomor-urut');
                if (nomorCell) {
                    nomorCell.textContent = nomor++;
                }
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
    }

    if (filterKategori) {
        filterKategori.addEventListener('change', filterTable);
    }

    if (filterSatuan) {
        filterSatuan.addEventListener('change', filterTable);
    }

    if (filterHarga) {
        filterHarga.addEventListener('change', filterTable);
    }

    if (resetFilter) {
        resetFilter.addEventListener('click', function () {
            searchInput.value = '';
            filterKategori.value = '';
            filterSatuan.value = '';
            filterHarga.value = '';
            filterTable();
        });
    }
});
</script>

<?= $this->endSection() ?>