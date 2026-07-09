<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
if (! function_exists('dirTahapAktifLabel')) {
    function dirTahapAktifLabel(array $row): string
    {
        $status = $row['status'] ?? '';
        $stage = $row['approval_stage'] ?? '';
        if ($status === 'menunggu_direktur_utama') { return 'Direktur Utama'; }
        if ($status === 'menunggu_direktur_umum') { return 'Direktur Umum'; }
        if ($status === 'disposisi_pengadaan') { return 'Selesai / Disposisi'; }
        if ($status === 'ditolak') { return 'Ditolak'; }
        if ($stage === 'direktur_utama') { return 'Direktur Utama'; }
        if ($stage === 'direktur_umum') { return 'Direktur Umum'; }
        return 'Direktur Bidang';
    }
}
?>

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1">Validasi Usulan Pengadaan</h2>
            <p class="text-muted mb-0">
                Direktur melakukan pemeriksaan akhir terhadap usulan dan hasil prioritas MOORA.
            </p>
        </div>
        <a href="<?= site_url('direktur/dashboard') ?>" class="btn btn-outline-secondary">
            Kembali Dashboard
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

    <div class="alert alert-info border-0 shadow-sm">
        <strong>Alur 3 Approval Direktur:</strong> Direktur Bidang menyetujui aspek teknis, Direktur Utama menyetujui prioritas strategis, lalu Direktur Umum memberi disposisi final ke Pengadaan.
        Satu akun role <strong>Direktur</strong> dipakai untuk simulasi sidang, sedangkan tahap aktif dikontrol oleh status workflow.
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="fw-bold">Direktur Bidang</div><small class="text-muted">Review teknis operasional</small><div class="mt-2"><span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_bidang'] ?? 0)) ?> menunggu</span></div></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="fw-bold">Direktur Utama</div><small class="text-muted">Review prioritas strategis</small><div class="mt-2"><span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_utama'] ?? 0)) ?> menunggu</span></div></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="fw-bold">Direktur Umum</div><small class="text-muted">Disposisi final ke Pengadaan</small><div class="mt-2"><span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_umum'] ?? 0)) ?> menunggu</span></div></div></div></div>
    </div>

    <div class="card border-0 shadow-sm tm-dir-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tm-dir-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Usulan</th>
                            <th>Tanggal</th>
                            <th>Pengusul</th>
                            <th>Unit</th>
                            <th>Item</th>
                            <th>Total Estimasi</th>
                            <th>Tahap Aktif</th>
                            <th>Status</th>
                            <th>Validasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usulanList)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($usulanList as $row) : ?>
                                <?php
                                    $validasi = $row['status_validasi'] ?? 'menunggu';
                                    $badge = 'bg-warning text-dark';

                                    if ($validasi === 'disetujui') {
                                        $badge = 'bg-success';
                                    } elseif ($validasi === 'ditolak') {
                                        $badge = 'bg-danger';
                                    }
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong>
                                    </td>
                                    <td><?= esc($row['tanggal_usulan'] ?? '-') ?></td>
                                    <td><?= esc($row['nama_lengkap'] ?? '-') ?></td>
                                    <td><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                                    <td><?= esc($row['total_item'] ?? 0) ?> item</td>
                                    <td>Rp <?= number_format((float) ($row['total_anggaran'] ?? 0), 0, ',', '.') ?></td>
                                    <td><span class="badge bg-info text-dark"><?= esc(dirTahapAktifLabel($row)) ?></span></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= esc(ucwords(str_replace('_', ' ', $row['status'] ?? '-'))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badge ?>">
                                            <?= esc(ucwords(str_replace('_', ' ', $validasi))) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('direktur/validasi/detail/' . $row['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            Detail Validasi
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    Belum ada usulan pengadaan.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>