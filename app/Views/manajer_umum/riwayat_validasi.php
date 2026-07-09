<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Validasi</h2>
            <p class="mb-0 text-muted">Menampilkan semua aksi yang dilakukan terhadap usulan pengadaan (Manajer Umum & role kosong).</p>
        </div>
        <div>
            <a href="<?= base_url('manajer-umum/riwayat-validasi/exportExcel') ?>" class="btn btn-success">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="mb-3 d-flex gap-2 flex-wrap">
        <input type="date" id="filter-start" class="form-control w-auto" placeholder="Mulai">
        <input type="date" id="filter-end" class="form-control w-auto" placeholder="Sampai">
        <input type="text" id="search-text" class="form-control w-auto" placeholder="Cari nomor atau asal usulan">
        <button id="btn-filter" class="btn btn-primary">Filter</button>
    </div>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table id="table-riwayat" class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nomor Usulan</th>
                        <th>Asal Usulan</th>
                        <th>Aksi</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($riwayat)): ?>
                        <?php $no=1; foreach($riwayat as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($r['nomor_usulan']) ?></td>
                                <td><?= esc($r['asal_usulan'] ?? $r['unit_pengusul']) ?></td>
                                <td><?= ucfirst(esc($r['aksi'])) ?></td>
                                <td><?= esc($r['catatan']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($r['tanggal_aksi'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada riwayat validasi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
document.getElementById('btn-filter').addEventListener('click', function() {
    const start = document.getElementById('filter-start').value;
    const end   = document.getElementById('filter-end').value;
    const search = document.getElementById('search-text').value.toLowerCase();
    const rows = document.querySelectorAll('#table-riwayat tbody tr');

    rows.forEach(row => {
        const nomor = row.cells[1].textContent.toLowerCase();
        const asal  = row.cells[2].textContent.toLowerCase();
        const tanggalCell = row.cells[5].textContent;

        let showDate = true;
        if(start && end && tanggalCell!==''){
            const parts = tanggalCell.split(' ')[0].split('-');
            const tgl = new Date(parts[2], parts[1]-1, parts[0]);
            const s = new Date(start); const e = new Date(end);
            showDate = tgl >= s && tgl <= e;
        }

        let showText = true;
        if(search){ showText = nomor.includes(search) || asal.includes(search); }

        row.style.display = (showDate && showText) ? '' : 'none';
    });
});
</script>

<?= $this->endSection() ?>