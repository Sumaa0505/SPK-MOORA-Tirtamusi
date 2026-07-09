<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$totalData = count($perbaikan ?? []);
$totalDiajukan = 0;
$totalDiproses = 0;
$totalSelesai = 0;

if (!empty($perbaikan)) {
    foreach ($perbaikan as $row) {
        if (($row['status_perbaikan'] ?? '') === 'diajukan') {
            $totalDiajukan++;
        } elseif (($row['status_perbaikan'] ?? '') === 'diproses') {
            $totalDiproses++;
        } elseif (($row['status_perbaikan'] ?? '') === 'selesai') {
            $totalSelesai++;
        }
    }
}
?>

<div class="container-fluid">

    <div class="page-header mb-4">
        <div>
            <h5 class="fw-bold mb-1"><?= esc($title ?? 'Perbaikan Alat Operasional') ?></h5>
            <small class="text-muted">Pendataan alat yang sedang diperbaiki pada setiap unit Perumda Tirta Musi Palembang</small>
        </div>

        <a href="<?= site_url('administrator/master-data/perbaikan-alat/create') ?>" class="btn btn-primary px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Perbaikan
        </a>
    </div>

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

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Data</small>
                    <h4 class="fw-bold mb-0"><?= $totalData ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Diajukan</small>
                    <h4 class="fw-bold mb-0 text-secondary"><?= $totalDiajukan ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Diproses</small>
                    <h4 class="fw-bold mb-0 text-warning"><?= $totalDiproses ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Selesai</small>
                    <h4 class="fw-bold mb-0 text-success"><?= $totalSelesai ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-funnel me-1"></i> Filter dan Pencarian
                    </h6>
                    <small class="text-muted">
                        Cari berdasarkan kode alat, nama alat, unit, lokasi, kerusakan, status, atau prioritas.
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    Data tampil: <span id="totalDataTampil"><?= $totalData ?></span>
                </span>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari data perbaikan...">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="diajukan">Diajukan</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Prioritas</label>
                    <select id="filterPrioritas" class="form-select">
                        <option value="">Semua Prioritas</option>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="darurat">Darurat</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="resetFilter" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Alat</th>
                            <th>Unit Pemakai</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Kerusakan</th>
                            <th>Biaya</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th width="185">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($perbaikan)) : ?>
                            <?php $no = 1; foreach ($perbaikan as $row) : ?>
                                <?php
                                $status = $row['status_perbaikan'] ?? 'diajukan';
                                $prioritas = $row['prioritas'] ?? 'sedang';

                                $statusClass = [
                                    'diajukan' => 'bg-secondary',
                                    'diproses' => 'bg-warning text-dark',
                                    'selesai' => 'bg-success',
                                ][$status] ?? 'bg-secondary';

                                $prioritasClass = [
                                    'rendah' => 'bg-info text-dark',
                                    'sedang' => 'bg-primary',
                                    'tinggi' => 'bg-warning text-dark',
                                    'darurat' => 'bg-danger',
                                ][$prioritas] ?? 'bg-primary';

                                $searchData = strtolower(
                                    ($row['kode_alternatif'] ?? '') . ' ' .
                                    ($row['nama_alternatif'] ?? '') . ' ' .
                                    ($row['unit_pemakai'] ?? '') . ' ' .
                                    ($row['lokasi_unit'] ?? '') . ' ' .
                                    ($row['penanggung_jawab'] ?? '') . ' ' .
                                    ($row['kerusakan'] ?? '') . ' ' .
                                    ($row['status_perbaikan'] ?? '') . ' ' .
                                    ($row['prioritas'] ?? '')
                                );
                                ?>

                                <tr class="data-row"
                                    data-search="<?= esc($searchData) ?>"
                                    data-status="<?= esc(strtolower($status)) ?>"
                                    data-prioritas="<?= esc(strtolower($prioritas)) ?>">

                                    <td class="text-center nomor-urut"><?= $no++ ?></td>

                                    <td>
                                        <div class="fw-bold"><?= esc($row['nama_alternatif'] ?? '-') ?></div>
                                        <small class="text-muted"><?= esc($row['kode_alternatif'] ?? '-') ?> | <?= esc($row['kategori_barang'] ?? '-') ?></small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold"><?= esc($row['unit_pemakai'] ?? '-') ?></div>
                                        <small class="text-muted">PJ: <?= esc($row['penanggung_jawab'] ?? '-') ?></small>
                                    </td>

                                    <td><?= esc($row['lokasi_unit'] ?? '-') ?></td>

                                    <td>
                                        <div>Mulai: <?= !empty($row['tanggal_perbaikan']) ? date('d/m/Y', strtotime($row['tanggal_perbaikan'])) : '-' ?></div>
                                        <small class="text-muted">
                                            Target: <?= !empty($row['tanggal_target']) ? date('d/m/Y', strtotime($row['tanggal_target'])) : '-' ?>
                                        </small>
                                    </td>

                                    <td><?= esc($row['kerusakan'] ?? '-') ?></td>

                                    <td class="text-end">
                                        Rp <?= number_format((float) ($row['biaya_perbaikan'] ?? 0), 0, ',', '.') ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge <?= $prioritasClass ?>">
                                            <?= ucfirst($prioritas) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge <?= $statusClass ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <a href="<?= site_url('administrator/master-data/perbaikan-alat/edit/' . $row['id']) ?>"
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <?php if ($status !== 'selesai') : ?>
                                                <a href="<?= site_url('administrator/master-data/perbaikan-alat/selesai/' . $row['id']) ?>"
                                                   onclick="return confirm('Tandai perbaikan ini sebagai selesai?')"
                                                   class="btn btn-success btn-sm">
                                                    Selesai
                                                </a>
                                            <?php endif; ?>

                                            <a href="<?= site_url('administrator/master-data/perbaikan-alat/delete/' . $row['id']) ?>"
                                               onclick="return confirm('Yakin ingin menghapus data perbaikan ini?')"
                                               class="btn btn-danger btn-sm">
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr id="emptyRow" style="display: none;">
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-search me-1"></i>
                                    Data tidak ditemukan.
                                </td>
                            </tr>

                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Belum ada data perbaikan alat.
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
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const filterPrioritas = document.getElementById('filterPrioritas');
    const resetFilter = document.getElementById('resetFilter');
    const totalDataTampil = document.getElementById('totalDataTampil');
    const rows = document.querySelectorAll('.data-row');
    const emptyRow = document.getElementById('emptyRow');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase().trim();
        const statusValue = filterStatus.value.toLowerCase().trim();
        const prioritasValue = filterPrioritas.value.toLowerCase().trim();

        let visibleCount = 0;

        rows.forEach(function (row) {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowStatus = row.getAttribute('data-status') || '';
            const rowPrioritas = row.getAttribute('data-prioritas') || '';

            const matchSearch = rowSearch.includes(searchValue);
            const matchStatus = statusValue === '' || rowStatus === statusValue;
            const matchPrioritas = prioritasValue === '' || rowPrioritas === prioritasValue;

            if (matchSearch && matchStatus && matchPrioritas) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        updateNomorUrut();

        if (totalDataTampil) {
            totalDataTampil.textContent = visibleCount;
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

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
    if (filterPrioritas) filterPrioritas.addEventListener('change', filterTable);

    if (resetFilter) {
        resetFilter.addEventListener('click', function () {
            searchInput.value = '';
            filterStatus.value = '';
            filterPrioritas.value = '';
            filterTable();
        });
    }
});
</script>

<?= $this->endSection() ?>