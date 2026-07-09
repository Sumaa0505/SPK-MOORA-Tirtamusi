<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$mode = $mode ?? 'create';
$usulan = $usulan ?? [];
$isEdit = $mode === 'edit' && !empty($usulan['id']);
$jenisValue = old('jenis_usulan', $usulan['jenis_usulan'] ?? '');
?>

<div class="container-fluid">

    <!-- Header & Navigasi -->
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><?= $isEdit ? 'Perbarui Usulan Pengadaan' : 'Buat Usulan Pengadaan' ?></h2>
            <p class="text-muted mb-0">Form pengajuan kebutuhan peralatan operasional dari sub unit.</p>
        </div>
        <a href="<?= site_url('sub-unit/usulan') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Flashdata Error & Success -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Form Usulan -->
    <form action="<?= $isEdit ? site_url('sub-unit/usulan/update/' . $usulan['id']) : site_url('sub-unit/usulan/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Informasi Usulan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Usulan</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Unit Pengusul</label>
                        <input type="text" name="unit_pengusul" class="form-control" 
                               value="<?= esc(old('unit_pengusul', $usulan['unit_pengusul'] ?? '')) ?>" 
                               placeholder="Contoh: Sub Unit Distribusi" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Usulan</label>
                        <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Usulan</label>
                        <select name="jenis_usulan" class="form-select" required onchange="toggleRKA(this.value)">
                            <option value="">-- Pilih Jenis Usulan --</option>
                            <option value="RKA" <?= $jenisValue === 'RKA' ? 'selected' : '' ?>>RKA</option>
                            <option value="Pesan Cepat" <?= $jenisValue === 'Pesan Cepat' ? 'selected' : '' ?>>Pesan Cepat</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan Pengusul</label>
                        <textarea name="catatan_pengusul" class="form-control" rows="3" placeholder="Tuliskan catatan tambahan jika ada"><?= esc(old('catatan_pengusul', $usulan['catatan_pengusul'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Detail Peralatan</h5>
                        <p class="text-muted mb-0">Pilih peralatan yang akan diajukan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBaris()" id="btnTambahItem">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Item
                    </button>
                </div>

                <!-- Upload RKA -->
                <div class="mb-3" id="rkaUpload" style="display:none;">
                    <div class="alert alert-info border-0 small mb-3">
                        <strong>RKA Final:</strong> Excel dipakai sebagai sumber data barang otomatis. Dokumen resmi PDF/Word bersifat lampiran audit untuk preview antar-role.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Excel RKA <span class="text-danger">*</span></label>
                            <input type="file" name="file_rka" class="form-control" accept=".xlsx,.xls" <?= $isEdit ? '' : '' ?>>
                            <small class="text-muted">Format: Kode | Nama Barang | Kategori | Spesifikasi | Jumlah | Satuan | Estimasi Satuan | Alasan.</small>
                            <?php $excelRka = $usulan['file_rka_excel_path'] ?? $usulan['file_rka_path'] ?? null; ?>
                            <?php if (!empty($excelRka)) : ?>
                                <div class="small mt-2">Excel tersimpan: <a href="<?= site_url('dokumen-rka/' . basename($excelRka)) ?>" target="_blank">Lihat Excel</a></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Dokumen Resmi RKA (opsional)</label>
                            <input type="file" name="file_rka_dokumen" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">PDF/Word/gambar sebagai bukti dokumen resmi RKA.</small>
                            <?php if (!empty($usulan['file_rka_dokumen_path'])) : ?>
                                <div class="small mt-2">Dokumen tersimpan: <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_dokumen_path'])) ?>" target="_blank">Lihat dokumen</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tabel Preview Barang -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="tabelItem">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Peralatan</th>
                                <th width="120">Jumlah</th>
                                <th>Alasan Kebutuhan</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="id_alternatif[]" class="form-select" required>
                                        <option value="">-- Pilih Peralatan --</option>
                                        <?php foreach($alternatifList as $alt): ?>
                                            <option value="<?= esc($alt['id']) ?>">
                                                <?= esc($alt['kode_alternatif'] ?? '-') ?> - <?= esc($alt['nama_alternatif'] ?? '-') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="jumlah[]" class="form-control text-center" min="1" value="1" required></td>
                                <td><input type="text" name="alasan_kebutuhan[]" class="form-control" placeholder="Contoh: Dibutuhkan untuk operasional lapangan" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="<?= site_url('sub-unit/usulan') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Usulan' ?></button>
                </div>

            </div>
        </div>

    </form>

</div>

<script>
function toggleRKA(value) {
    const rka = document.getElementById('rkaUpload');
    const btnTambah = document.getElementById('btnTambahItem');
    const tbody = document.querySelector('#tabelItem tbody');

    if(value === 'RKA'){
        rka.style.display = 'block';
        btnTambah.style.display = 'none';
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Upload file RKA untuk melihat preview barang</td></tr>';
    } else {
        rka.style.display = 'none';
        btnTambah.style.display = 'inline-block';
        // Reset tabel default untuk Pesan Cepat
        if(tbody.rows.length===1 && tbody.rows[0].querySelector('td').colSpan===4){
            tbody.innerHTML = `<tr>
                <td>
                    <select name="id_alternatif[]" class="form-select" required>
                        <option value="">-- Pilih Peralatan --</option>
                        <?php foreach($alternatifList as $alt): ?>
                            <option value="<?= esc($alt['id']) ?>">
                                <?= esc($alt['kode_alternatif'] ?? '-') ?> - <?= esc($alt['nama_alternatif'] ?? '-') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control text-center" min="1" value="1" required></td>
                <td><input type="text" name="alasan_kebutuhan[]" class="form-control" placeholder="Contoh: Dibutuhkan untuk operasional lapangan" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
        }
    }
}

function tambahBaris() {
    const tbody = document.querySelector('#tabelItem tbody');
    const row = tbody.querySelector('tr').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = input.type==='number'?1:'');
    row.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    tbody.appendChild(row);
}

function hapusBaris(button) {
    const tbody = document.querySelector('#tabelItem tbody');
    if(tbody.rows.length <= 1){
        alert('Minimal harus ada satu item peralatan.');
        return;
    }
    button.closest('tr').remove();
}
document.addEventListener('DOMContentLoaded', function () {
    toggleRKA("<?= esc($jenisValue) ?>");
});
</script>

<?= $this->endSection() ?>