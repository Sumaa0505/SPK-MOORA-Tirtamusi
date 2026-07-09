<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$kriteria = $kriteria ?? [];

$total = count($kriteria);
$aktif = 0;
$totalBobot = 0;

foreach ($kriteria as $k) {
    if (($k['is_active'] ?? 1) == 1) {
        $aktif++;
        $totalBobot += (float) $k['bobot'];
    }
}

$valid = abs($totalBobot - 1.0) < 0.0001;
?>

<style>

/* ================= HERO (SAMAKAN APPROVAL REGISTER) ================= */
.k-hero {
    background: linear-gradient(135deg, #0b2a55, #144a8a);
    color: #fff;
    padding: 22px;
    border-radius: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.k-hero h2 {
    margin: 0;
    font-weight: 800;
}

.k-hero p {
    margin: 0;
    font-size: 13px;
    opacity: .85;
}

.k-btn {
    background: #fbbf24;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 800;
    text-decoration: none;
    color: #000;
}

/* ================= CARD ================= */
.k-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,.08);
}

/* ================= TABLE ================= */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: #0f2f5f;
    color: #fff;
    padding: 10px;
    font-size: 13px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

/* ================= BADGE ================= */
.badge-ok {
    background: #10b981;
    color: #fff;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
}

.badge-no {
    background: #ef4444;
    color: #fff;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
}

/* ================= STAT ================= */
.stat-wrap {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.stat {
    flex: 1;
    min-width: 180px;
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.06);
}

</style>

<!-- HERO -->
<div class="k-hero">
    <div>
        <h2>Data Kriteria</h2>
        <p>Administrator Verification - MOORA System</p>
    </div>

    <a href="<?= site_url('administrator/dashboard') ?>" class="k-btn">
        ← Dashboard
    </a>
</div>

<!-- STAT -->
<div class="stat-wrap">

    <div class="stat">
        <b>Total Kriteria</b>
        <h2><?= $total ?></h2>
    </div>

    <div class="stat">
        <b>Kriteria Aktif</b>
        <h2><?= $aktif ?></h2>
    </div>

    <div class="stat">
        <b>Total Bobot</b>
        <h2><?= number_format($totalBobot, 2) ?></h2>
    </div>

    <div class="stat">
        <b>Status Bobot</b><br><br>
        <?= $valid
            ? '<span class="badge-ok">VALID</span>'
            : '<span class="badge-no">TIDAK VALID</span>'
        ?>
    </div>

</div>

<!-- TABLE -->
<div class="k-card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <b>Tabel Kriteria MOORA</b>

        <a href="<?= site_url('administrator/kriteria/create') ?>" class="btn btn-primary btn-sm">
            + Tambah
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Bobot</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($kriteria)) : ?>
            <?php foreach ($kriteria as $k): ?>
                <tr>

                    <td><?= esc($k['kode_kriteria']) ?></td>

                    <td><?= esc($k['nama_kriteria']) ?></td>

                    <td><?= esc($k['jenis']) ?></td>

                    <td><?= number_format($k['bobot'], 2) ?></td>

                    <td>
                        <?= ($k['is_active'] ?? 1) == 1
                            ? '<span class="badge-ok">Aktif</span>'
                            : '<span class="badge-no">Nonaktif</span>'
                        ?>
                    </td>

                    <!-- ================= ACTION FIX ================= -->
                    <td class="text-center">

                        <!-- EDIT -->
                        <a href="<?= site_url('administrator/kriteria/edit/' . $k['id']) ?>"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <!-- DELETE -->
                        <a href="<?= site_url('administrator/kriteria/delete/' . $k['id']) ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="bi bi-trash"></i>
                        </a>

                    </td>

                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Tidak ada data kriteria
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>