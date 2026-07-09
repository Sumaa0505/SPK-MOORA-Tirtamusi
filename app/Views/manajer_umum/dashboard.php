<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Manajer Umum</h2>
            <p class="mb-0 text-muted">Ringkasan statistik dan visualisasi utama sistem SPK MOORA.</p>
        </div>
    </div>

    <!-- Kartu Statistik -->
    <div class="row g-4 mb-4">

        <!-- Total Usulan -->
        <div class="col-md-3">
            <div class="card tm-card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Total Usulan</h6>
                    <h3 class="fw-bold mb-0"><?= $total_usulan ?? 0 ?></h3>
                    <small class="text-muted">Semua usulan pengadaan</small>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-md-3">
            <div class="card tm-card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Total Pengguna</h6>
                    <h3 class="fw-bold mb-0"><?= $total_users ?? 0 ?></h3>
                    <small class="text-muted">Pengguna terdaftar</small>
                </div>
            </div>
        </div>

        <!-- Total Barang -->
        <div class="col-md-3">
            <div class="card tm-card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Total Barang</h6>
                    <h3 class="fw-bold mb-0"><?= $total_barang ?? 0 ?></h3>
                    <small class="text-muted">Semua item inventaris</small>
                </div>
            </div>
        </div>

        <!-- Total Hasil MOORA -->
        <div class="col-md-3">
            <div class="card tm-card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Hasil MOORA</h6>
                    <h3 class="fw-bold mb-0"><?= $total_moora ?? 0 ?></h3>
                    <small class="text-muted">Perhitungan prioritas</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Diagram Statistik -->
    <div class="row g-4">

        <!-- Line Chart: Pertumbuhan Usulan -->
        <div class="col-lg-6">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Pertumbuhan Usulan</h6>
                </div>
                <div class="card-body">
                    <canvas id="lineUsulan"></canvas>
                </div>
            </div>
        </div>

        <!-- Pie Chart: Distribusi Kategori Barang -->
        <div class="col-lg-6">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Distribusi Kategori Barang</h6>
                </div>
                <div class="card-body">
                    <canvas id="pieKategori"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Hasil MOORA per Usulan -->
        <div class="col-12">
            <div class="card tm-card border-0 shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Hasil MOORA per Usulan</h6>
                </div>
                <div class="card-body">
                    <canvas id="barMoora"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Data dinamis dari controller (pastikan controller mengirim JSON encoded array)
    const pertumbuhanUsulan = <?= json_encode($data_usulan_per_tanggal ?? []) ?>;
    const labelUsulan = pertumbuhanUsulan.map(u => u.tanggal);
    const totalUsulanPerTanggal = pertumbuhanUsulan.map(u => u.total);

    const kategoriBarang = <?= json_encode($data_kategori_barang ?? []) ?>;
    const labelsKategori = kategoriBarang.map(k => k.kategori);
    const totalKategori = kategoriBarang.map(k => k.total);

    const mooraData = <?= json_encode($data_moora_per_usulan ?? []) ?>;
    const labelMoora = mooraData.map(m => m.nomor_usulan);
    const nilaiMoora = mooraData.map(m => m.nilai_yi);

    // Line Chart
    new Chart(document.getElementById('lineUsulan'), {
        type: 'line',
        data: {
            labels: labelUsulan,
            datasets: [{
                label: 'Jumlah Usulan',
                data: totalUsulanPerTanggal,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieKategori'), {
        type: 'pie',
        data: {
            labels: labelsKategori,
            datasets: [{
                data: totalKategori,
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']
            }]
        },
        options: { responsive: true }
    });

    // Bar Chart
    new Chart(document.getElementById('barMoora'), {
        type: 'bar',
        data: {
            labels: labelMoora,
            datasets: [{
                label: 'Nilai MOORA',
                data: nilaiMoora,
                backgroundColor: 'rgba(75, 192, 192, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

});
</script>

<?= $this->endSection() ?>