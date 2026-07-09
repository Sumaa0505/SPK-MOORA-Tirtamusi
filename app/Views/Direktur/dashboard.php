<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="dir-hero mb-4">
        <div>
            <span class="dir-badge">PANEL DIREKTUR</span>
            <h2>Dashboard Pengambilan Keputusan</h2>
            <p>
                Monitoring usulan pengadaan, validasi keputusan, dan prioritas berdasarkan hasil perhitungan MOORA.
            </p>
        </div>

        <div class="dir-hero-actions">
            <a href="<?= site_url('direktur/validasi') ?>" class="btn btn-light fw-bold">
                Validasi Usulan
            </a>
            <a href="<?= site_url('direktur/hasil') ?>" class="btn btn-outline-light fw-bold">
                Hasil MOORA
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dir-stat">
                <div class="dir-icon blue"><i class="bi bi-files"></i></div>
                <div>
                    <p>Total Usulan</p>
                    <h3><?= esc($totalUsulan ?? 0) ?></h3>
                    <small>Seluruh usulan pengadaan</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dir-stat">
                <div class="dir-icon yellow"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <p>Menunggu Validasi</p>
                    <h3><?= esc($menungguValidasi ?? 0) ?></h3>
                    <small>Perlu keputusan direktur</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dir-stat">
                <div class="dir-icon green"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <p>Disetujui</p>
                    <h3><?= esc($disetujui ?? 0) ?></h3>
                    <small>Usulan layak direalisasikan</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dir-stat">
                <div class="dir-icon red"><i class="bi bi-x-circle"></i></div>
                <div>
                    <p>Ditolak</p>
                    <h3><?= esc($ditolak ?? 0) ?></h3>
                    <small>Usulan tidak disetujui</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm dir-card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Peta Fungsi 3 Approval Direktur</h5>
                    <p class="text-muted mb-0">
                        Pada sistem ini satu role login <strong>Direktur</strong> menjalankan simulasi approval berjenjang sesuai status workflow: Direktur Bidang → Direktur Utama → Direktur Umum.
                    </p>
                </div>
                <a href="<?= site_url('direktur/validasi') ?>" class="btn btn-outline-primary btn-sm">Buka Validasi</a>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="fw-bold">1. Direktur Bidang</div>
                        <div class="text-muted small mb-2">Menilai kebutuhan teknis/operasional dari hasil MOORA.</div>
                        <span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_bidang'] ?? 0)) ?> menunggu</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="fw-bold">2. Direktur Utama</div>
                        <div class="text-muted small mb-2">Menilai prioritas strategis dan kelayakan keputusan perusahaan.</div>
                        <span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_utama'] ?? 0)) ?> menunggu</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="fw-bold">3. Direktur Umum</div>
                        <div class="text-muted small mb-2">Memberi disposisi final agar usulan masuk ke Bagian Pengadaan.</div>
                        <span class="badge bg-warning text-dark"><?= (int) (($stageCounts['direktur_umum'] ?? 0)) ?> menunggu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm dir-card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Ringkasan Anggaran Pengadaan</h5>
                    <p class="text-muted mb-4">Estimasi nilai pengadaan berdasarkan seluruh detail usulan.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="dir-money-box">
                                <span>Total Estimasi</span>
                                <h3>Rp <?= number_format((float) ($totalAnggaran ?? 0), 0, ',', '.') ?></h3>
                                <p>Akumulasi semua item pengadaan</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="dir-money-box approved">
                                <span>Anggaran Disetujui</span>
                                <h3>Rp <?= number_format((float) ($anggaranDisetujui ?? 0), 0, ',', '.') ?></h3>
                                <p>Usulan yang sudah divalidasi direktur</p>
                            </div>
                        </div>
                    </div>

                    <div class="dir-note mt-3">
                        <i class="bi bi-info-circle"></i>
                        <span>
                            Prioritas tertinggi ditentukan dari nilai Yi terbesar berdasarkan metode MOORA.
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm dir-card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Komposisi Validasi</h5>
                    <p class="text-muted mb-3">Status keputusan direktur terhadap usulan.</p>

                    <canvas id="chartValidasi" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm dir-card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Top Prioritas Pengadaan MOORA</h5>
                    <p class="text-muted mb-0">Daftar item dengan ranking tertinggi untuk bahan keputusan direktur.</p>
                </div>
                <a href="<?= site_url('direktur/hasil') ?>" class="btn btn-primary btn-sm">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 dir-table">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Barang</th>
                            <th>Usulan</th>
                            <th>Unit</th>
                            <th>Jumlah</th>
                            <th>Nilai Yi</th>
                            <th>Estimasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topPrioritas)) : ?>
                            <?php foreach ($topPrioritas as $row) : ?>
                                <tr>
                                    <td>
                                        <span class="rank-badge">#<?= esc($row['ranking']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($row['nama_alternatif'] ?? '-') ?></strong><br>
                                        <small class="text-muted"><?= esc($row['kode_alternatif'] ?? '-') ?></small>
                                    </td>
                                    <td><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                                    <td><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                                    <td><?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                                    <td>
                                        <strong><?= number_format((float) ($row['nilai_yi'] ?? 0), 6, ',', '.') ?></strong>
                                    </td>
                                    <td>Rp <?= number_format((float) ($row['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                            $status = $row['status_validasi'] ?? $row['status'] ?? 'menunggu';
                                            $badge = 'bg-secondary';

                                            if ($status === 'disetujui') {
                                                $badge = 'bg-success';
                                            } elseif ($status === 'ditolak') {
                                                $badge = 'bg-danger';
                                            } elseif ($status === 'menunggu' || $status === 'diajukan') {
                                                $badge = 'bg-warning text-dark';
                                            }
                                        ?>
                                        <span class="badge <?= $badge ?>">
                                            <?= esc(ucwords(str_replace('_', ' ', $status))) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada hasil perhitungan MOORA.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxValidasi = document.getElementById('chartValidasi');

if (ctxValidasi) {
    new Chart(ctxValidasi, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: [
                    <?= (int) ($menungguValidasi ?? 0) ?>,
                    <?= (int) ($disetujui ?? 0) ?>,
                    <?= (int) ($ditolak ?? 0) ?>
                ],
                backgroundColor: ['#f59e0b', '#22c55e', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>

<?= $this->endSection() ?>