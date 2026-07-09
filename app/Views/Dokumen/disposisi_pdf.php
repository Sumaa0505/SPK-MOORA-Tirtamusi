<?php
$printMode = $printMode ?? false;
$dokumen = $dokumen ?? [];
$usulan = $usulan ?? [];
$detail = $detail ?? [];
$hasilMoora = $hasilMoora ?? [];
$approval = $approval ?? [];
$qr = $qr ?? [];
$qrPath = !empty($qr['qr_file_path']) ? FCPATH . '../' . $qr['qr_file_path'] : null;
$qrSvg = ($qrPath && is_file($qrPath)) ? file_get_contents($qrPath) : '';
$returnUrl = $returnUrl ?? site_url('dashboard');
$total = 0;
foreach ($detail as $row) {
    $total += (float) ($row['total_estimasi'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Dokumen Disposisi Pengadaan') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#e5e7eb; color:#111827; font-family: Arial, sans-serif; }
        .doc-wrap { max-width: 980px; margin: 24px auto; }
        .doc-paper { background:#fff; padding: 34px 42px; border-radius: 4px; box-shadow: 0 12px 30px rgba(15,23,42,.18); }
        .kop { border-bottom: 4px double #111827; padding-bottom: 14px; margin-bottom: 20px; display:flex; align-items:center; gap:16px; }
        .logo { width:74px; height:74px; object-fit:contain; }
        .kop h3 { margin:0; font-size:20px; font-weight:800; text-transform:uppercase; }
        .kop p { margin:2px 0 0; font-size:12px; }
        .doc-title { text-align:center; margin: 18px 0 22px; }
        .doc-title h4 { font-size:17px; font-weight:800; text-decoration: underline; margin-bottom: 4px; }
        .doc-title p { margin:0; font-size:13px; }
        table { font-size:12px; }
        .table-bordered th, .table-bordered td { border:1px solid #111827 !important; }
        .info-table th { width: 170px; vertical-align:top; }
        .section-title { font-size:13px; font-weight:800; text-transform:uppercase; margin:22px 0 8px; }
        .qr-box { border:1px solid #111827; padding:12px; display:flex; gap:14px; align-items:center; }
        .qr-visual svg { width:128px; height:128px; display:block; }
        .small-muted { font-size:11px; color:#4b5563; word-break:break-all; }
        .sign-area { display:flex; justify-content:flex-end; margin-top:28px; }
        .sign-box { width:260px; text-align:center; font-size:12px; }
        .sign-space { height:64px; }
        .toolbar { max-width:980px; margin:18px auto 0; display:flex; justify-content:space-between; gap:8px; }
        @media print {
            body { background:#fff; }
            .toolbar { display:none !important; }
            .doc-wrap { margin:0; max-width:100%; }
            .doc-paper { box-shadow:none; padding:0; }
        }
    </style>
</head>
<body>
<?php if (!$printMode): ?>
<div class="toolbar">
    <a href="<?= esc($returnUrl, 'attr') ?>" class="btn btn-outline-secondary">Kembali</a>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
        <?php if (!empty($qr['verification_url'])): ?>
            <a class="btn btn-outline-primary" target="_blank" href="<?= esc($qr['verification_url']) ?>">Buka Verifikasi</a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="doc-wrap">
    <div class="doc-paper">
        <div class="kop">
            <img class="logo" src="<?= base_url('assets/img/Logo Tirta Musi.png') ?>" alt="Logo">
            <div>
                <h3>Perumda Tirta Musi Palembang</h3>
                <p>Sistem Pendukung Keputusan Pengadaan Barang Metode MOORA</p>
                <p>Dokumen disposisi digital berbasis workflow persetujuan berjenjang</p>
            </div>
        </div>

        <div class="doc-title">
            <h4><?= esc($dokumen['judul_dokumen'] ?? 'Dokumen Disposisi Pengadaan') ?></h4>
            <p>Nomor: <?= esc($dokumen['nomor_dokumen'] ?? '-') ?></p>
        </div>

        <div class="section-title">A. Identitas Usulan</div>
        <table class="table table-sm table-bordered info-table">
            <tr><th>Nomor Usulan</th><td><?= esc($usulan['nomor_usulan'] ?? '-') ?></td></tr>
            <tr><th>Tanggal Usulan</th><td><?= esc($usulan['tanggal_usulan'] ?? '-') ?></td></tr>
            <tr><th>Unit Pengusul</th><td><?= esc($usulan['unit_pengusul'] ?? '-') ?></td></tr>
            <tr><th>Nama Pengusul</th><td><?= esc($usulan['nama_pengusul'] ?? '-') ?></td></tr>
            <tr><th>Jenis Usulan</th><td><?= esc($usulan['jenis_usulan'] ?? '-') ?></td></tr>
            <tr><th>Status Workflow</th><td><?= esc(ucwords(str_replace('_', ' ', $usulan['status'] ?? '-'))) ?></td></tr>
            <tr><th>Catatan Manajer</th><td><?= esc($usulan['catatan_manajer'] ?? '-') ?></td></tr>
            <tr><th>Catatan Direktur</th><td><?= esc($usulan['catatan_direksi'] ?? $usulan['catatan_validasi'] ?? '-') ?></td></tr>
        </table>

        <div class="section-title">B. Daftar Barang Usulan</div>
        <table class="table table-sm table-bordered">
            <thead><tr><th>No</th><th>Barang</th><th>Spesifikasi</th><th>Qty</th><th>Harga</th><th>Total</th></tr></thead>
            <tbody>
            <?php if (!empty($detail)): $no=1; foreach ($detail as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                    <td><?= esc($row['spesifikasi'] ?? '-') ?></td>
                    <td class="text-center"><?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                    <td class="text-end">Rp <?= number_format((float) ($row['estimasi_harga_satuan'] ?? 0), 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format((float) ($row['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center">Tidak ada detail barang.</td></tr>
            <?php endif; ?>
            <tr><th colspan="5" class="text-end">Total Estimasi</th><th class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></th></tr>
            </tbody>
        </table>

        <div class="section-title">C. Ringkasan Ranking MOORA</div>
        <table class="table table-sm table-bordered">
            <thead><tr><th>Ranking</th><th>Alternatif</th><th>Nilai Yi</th><th>Versi Hitung</th></tr></thead>
            <tbody>
            <?php if (!empty($hasilMoora)): foreach ($hasilMoora as $row): ?>
                <tr>
                    <td class="text-center"><?= esc($row['ranking'] ?? '-') ?></td>
                    <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                    <td class="text-end"><?= number_format((float) ($row['nilai_yi'] ?? 0), 8) ?></td>
                    <td class="text-center"><?= esc($row['versi_hitung'] ?? '-') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center">Hasil MOORA belum tersedia.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="section-title">D. Riwayat Approval Direktur</div>
        <table class="table table-sm table-bordered">
            <thead><tr><th>Tahap</th><th>Aksi</th><th>Pejabat</th><th>Waktu</th><th>Catatan</th></tr></thead>
            <tbody>
            <?php if (!empty($approval)): foreach ($approval as $row): ?>
                <tr>
                    <td><?= esc(ucwords(str_replace('_', ' ', $row['tahap_approval'] ?? '-'))) ?></td>
                    <td><?= esc(ucwords(str_replace('_', ' ', $row['aksi'] ?? '-'))) ?></td>
                    <td><?= esc($row['nama_approver'] ?? '-') ?></td>
                    <td><?= !empty($row['approved_at']) ? date('d-m-Y H:i', strtotime($row['approved_at'])) : '-' ?></td>
                    <td><?= esc($row['catatan'] ?? '-') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">Belum ada approval.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="section-title">E. Verifikasi Digital</div>
        <div class="qr-box">
            <div class="qr-visual"><?= $qrSvg ?></div>
            <div>
                <strong>Status Dokumen:</strong> <?= esc(ucwords(str_replace('_', ' ', $dokumen['status_dokumen'] ?? 'preview'))) ?><br>
                <strong>Hash:</strong><br><span class="small-muted"><?= esc($qr['qr_hash'] ?? $dokumen['hash_dokumen'] ?? '-') ?></span><br>
                <strong>URL Verifikasi:</strong><br><span class="small-muted"><?= esc($qr['verification_url'] ?? '-') ?></span>
            </div>
        </div>

        <div class="sign-area">
            <div class="sign-box">
                Palembang, <?= !empty($dokumen['approved_at']) ? date('d F Y', strtotime($dokumen['approved_at'])) : date('d F Y') ?><br>
                Direktur Umum
                <div class="sign-space"></div>
                <strong><?= esc($usulan['nama_validator'] ?? 'Direktur') ?></strong>
            </div>
        </div>
    </div>
</div>
</body>
</html>
