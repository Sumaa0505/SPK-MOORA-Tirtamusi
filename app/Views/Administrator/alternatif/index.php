<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$alternatif = $alternatif ?? [];
$total = count($alternatif);
?>

<style>

/* ================= HERO (KONSISTEN SYSTEM) ================= */
.alt-hero {
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

.alt-hero h2 {
    margin: 0;
    font-weight: 800;
}

.alt-hero p {
    margin: 0;
    font-size: 13px;
    opacity: .85;
}

.alt-btn {
    background: #fbbf24;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 800;
    text-decoration: none;
    color: #000;
}

/* ================= STAT ================= */
.alt-stat {
    background: #fff;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.06);
    margin-bottom: 15px;
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

/* ================= ACTION BUTTON FIX ================= */
.action-wrap {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 8px;
    background: #fbbf24;
    color: #000;
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
    white-space: nowrap;
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 8px;
    background: #ef4444;
    color: #fff;
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
    white-space: nowrap;
}

.btn-edit:hover,
.btn-delete:hover {
    opacity: .9;
}

</style>

<!-- HERO -->
<div class="alt-hero">

    <div>
        <h2>Data Alternatif</h2>
        <p>Gabungan data alat & material untuk proses MOORA</p>
    </div>

    <a href="<?= site_url('administrator/dashboard') ?>" class="alt-btn">
        ← Dashboard
    </a>

</div>

<!-- STAT -->
<div class="alt-stat">
    <b>Total Alternatif</b>
    <h2><?= $total ?></h2>
</div>

<!-- TABLE -->
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <b>Tabel Alternatif</b>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Alternatif</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($alternatif)) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($alternatif as $a) : ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($a['kode_alternatif']) ?></td>
                            <td><?= esc($a['nama_alternatif']) ?></td>
                            <td><?= esc($a['keterangan']) ?></td>

                            <!-- ================= ACTION FIX FULL ================= -->
                            <td>

                                <div class="action-wrap">

                                    <!-- EDIT -->
                                    <a href="<?= site_url('administrator/alternatif/edit/' . $a['id']) ?>"
                                       class="btn-edit">
                                        ✏ Edit
                                    </a>

                                    <!-- DELETE -->
                                    <a href="<?= site_url('administrator/alternatif/delete/' . $a['id']) ?>"
                                       class="btn-delete"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        🗑 Hapus
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>
                <?php else : ?>

                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data alternatif
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>
</div>

<?= $this->endSection() ?>