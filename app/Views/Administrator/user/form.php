<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($user);
?>

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold mb-1"><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h2>
        <p class="text-muted mb-0">Form pengelolaan data user sistem.</p>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="<?= $action ?>" method="post">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text"
                               name="nama_lengkap"
                               class="form-control"
                               value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               class="form-control"
                               value="<?= old('username', $user['username'] ?? '') ?>"
                               <?= $isEdit ? 'readonly' : 'required' ?>>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Password <?= $isEdit ? '(Kosongkan jika tidak diubah)' : '' ?>
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               <?= $isEdit ? '' : 'required' ?>>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>

                            <option value="">-- Pilih Role --</option>

                            <?php
                            $roles = [
                                'administrator' => 'Administrator',
                                'sub_unit'      => 'Sub Unit',
                                'gudang'        => 'Gudang',
                                'manajer_umum'  => 'Manajer Umum',
                                'direktur'      => 'Direktur',
                                'pengadaan'     => 'Bagian Pengadaan'
                            ];
                            ?>

                            <?php foreach ($roles as $key => $val) : ?>
                                <option value="<?= $key ?>"
                                    <?= old('role', $user['role'] ?? '') == $key ? 'selected' : '' ?>>
                                    <?= $val ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>

                    <a href="<?= site_url('administrator/user') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<?= $this->endSection() ?>