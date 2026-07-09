<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Verifikasi Dokumen') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mx-auto" style="max-width: 760px;">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <?php if ($qr && (int) ($qr['is_valid'] ?? 0) === 1) : ?>
                    <div class="text-center mb-4">
                        <div class="display-5 text-success mb-2"><i class="bi bi-check-circle"></i></div>
                        <h3 class="fw-bold">Dokumen Tervalidasi</h3>
                        <p class="text-muted mb-0">Hash dokumen cocok dengan data disposisi sistem.</p>
                    </div>
                    <table class="table table-bordered align-middle">
                        <tr><th style="width: 220px;">Nomor Dokumen</th><td><?= esc($qr['nomor_dokumen'] ?? '-') ?></td></tr>
                        <tr><th>Judul</th><td><?= esc($qr['judul_dokumen'] ?? '-') ?></td></tr>
                        <tr><th>Nomor Usulan</th><td><?= esc($qr['nomor_usulan'] ?? '-') ?></td></tr>
                        <tr><th>Unit Pengusul</th><td><?= esc($qr['unit_pengusul'] ?? '-') ?></td></tr>
                        <tr><th>Status Dokumen</th><td><?= esc(ucwords(str_replace('_', ' ', $qr['status_dokumen'] ?? '-'))) ?></td></tr>
                        <tr><th>Disahkan Pada</th><td><?= esc($qr['approved_at'] ?? '-') ?></td></tr>
                        <tr><th>Hash</th><td><code class="small"><?= esc($qr['qr_hash'] ?? $hash) ?></code></td></tr>
                    </table>
                <?php else : ?>
                    <div class="text-center">
                        <h3 class="fw-bold text-danger">Dokumen Tidak Valid</h3>
                        <p class="text-muted">Hash tidak ditemukan atau dokumen sudah dinonaktifkan.</p>
                        <code><?= esc($hash) ?></code>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
