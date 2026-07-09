<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================================
// AUTH
// =====================================================
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::prosesLogin');
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);
$routes->get('register', 'AuthController::register');
$routes->post('register/store', 'AuthController::storeRegister');
$routes->get('verifikasi-dokumen/(:segment)', 'Dokumen\\VerifikasiController::index/$1');
$routes->get('dokumen-rka/(:any)', 'Dokumen\\RkaController::show/$1', ['filter' => 'auth']);


// =====================================================
// DASHBOARD GLOBAL
// =====================================================
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// =====================================================
// NOTIFIKASI GLOBAL SEMUA ROLE
// =====================================================
$routes->group('notifikasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'NotifikasiController::index');
    $routes->post('baca/(:num)', 'NotifikasiController::baca/$1');
    $routes->post('baca-semua', 'NotifikasiController::bacaSemua');
});

// =====================================================
// DOKUMEN DISPOSISI DIGITAL
// =====================================================
$routes->group('dokumen-disposisi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dokumen\DisposisiController::index');
    $routes->get('preview', 'Dokumen\DisposisiController::index');
    $routes->get('preview/(:num)', 'Dokumen\DisposisiController::preview/$1');
    $routes->get('download/(:num)', 'Dokumen\DisposisiController::download/$1');
    $routes->post('generate/(:num)', 'Dokumen\DisposisiController::generate/$1');
});


// =====================================================
// ADMINISTRATOR
// =====================================================
$routes->group('administrator', ['filter' => ['auth', 'role:administrator']], function ($routes) {

    // -------------------------
    // DASHBOARD ADMIN
    // -------------------------
    $routes->get('dashboard', 'Administrator\DashboardController::index');

    // -------------------------
    // KRIITERIA
    // -------------------------
    $routes->get('kriteria', 'Administrator\KriteriaController::index');
    $routes->get('kriteria/create', 'Administrator\KriteriaController::create');
    $routes->post('kriteria/store', 'Administrator\KriteriaController::store');
    $routes->get('kriteria/edit/(:num)', 'Administrator\KriteriaController::edit/$1');
    $routes->post('kriteria/update/(:num)', 'Administrator\KriteriaController::update/$1');
    $routes->get('kriteria/delete/(:num)', 'Administrator\KriteriaController::delete/$1');

    // -------------------------
    // KALKULASI MOORA ADMIN
    // -------------------------
    $routes->get('kalkulasi-moora', 'Administrator\MooraController::index');
    $routes->post('kalkulasi-moora/trigger/(:num)', 'Administrator\MooraController::trigger/$1');
    $routes->post('kalkulasi-moora/batch', 'Administrator\MooraController::batch');
    $routes->post('kalkulasi-moora/recalculate-v5', 'Administrator\MooraController::recalculateV5'); // alias lama
    $routes->post('kalkulasi-moora/recalculate-v6', 'Administrator\MooraController::recalculateV6');
    $routes->post('kalkulasi-moora/global-rka', 'Administrator\MooraController::globalRkaRecalculate');
    $routes->get('kalkulasi-moora/hasil/(:num)', 'Administrator\MooraController::hasil/$1');
    $routes->get('moora-audit', 'Administrator\MooraAuditController::index');
    $routes->post('moora-audit/consolidate', 'Administrator\MooraAuditController::consolidate');
    $routes->get('training-moora', 'Administrator\TrainingMooraController::index');

    // Alias lama agar link existing tidak langsung 404
    $routes->get('moora', 'Administrator\MooraController::index');
    $routes->post('moora/trigger/(:num)', 'Administrator\MooraController::trigger/$1');
    $routes->post('moora/batch', 'Administrator\MooraController::batch');
    $routes->post('moora/recalculate-v5', 'Administrator\MooraController::recalculateV5'); // alias lama
    $routes->post('moora/recalculate-v6', 'Administrator\MooraController::recalculateV6');
    $routes->post('moora/global-rka', 'Administrator\MooraController::globalRkaRecalculate');
    $routes->get('moora/hasil/(:num)', 'Administrator\MooraController::hasil/$1');


    // -------------------------
    // MASTER DATA BARANG
    // -------------------------
    $routes->get('master-data/alat', 'Administrator\MasterDataBarangController::alat');
    $routes->get('master-data/alat/create', 'Administrator\MasterDataBarangController::createAlat');
    $routes->post('master-data/alat/store', 'Administrator\MasterDataBarangController::storeAlat');

    $routes->get('master-data/material', 'Administrator\MasterDataBarangController::material');
    $routes->get('master-data/material/create', 'Administrator\MasterDataBarangController::createMaterial');
    $routes->post('master-data/material/store', 'Administrator\MasterDataBarangController::storeMaterial');

    $routes->get('master-data/aset', 'Administrator\MasterDataBarangController::aset');

    // -------------------------
    // ALTERNATIF
    // -------------------------
    $routes->get('alternatif', 'Administrator\AlternatifController::index');
    $routes->get('alternatif/edit/(:num)', 'Administrator\AlternatifController::edit/$1');
    $routes->post('alternatif/update/(:num)', 'Administrator\AlternatifController::update/$1');
    $routes->get('alternatif/delete/(:num)', 'Administrator\AlternatifController::delete/$1');

    // -------------------------
    // MONITORING
    // -------------------------
    $routes->get('monitoring', 'Administrator\MonitoringController::index');
    $routes->get('monitoring/detail/(:num)', 'Administrator\MonitoringController::detail/$1');

    // -------------------------
    // USER
    // -------------------------
    $routes->get('user', 'Administrator\UserController::index');
    $routes->get('user/create', 'Administrator\UserController::create');
    $routes->post('user/store', 'Administrator\UserController::store');
    $routes->get('user/edit/(:num)', 'Administrator\UserController::edit/$1');
    $routes->post('user/update/(:num)', 'Administrator\UserController::update/$1');
    $routes->get('user/toggle/(:num)', 'Administrator\UserController::toggle/$1');

    // -------------------------
    // REGISTRASI USER
    // -------------------------
    $routes->get('registrasi', 'Administrator\RegistrasiController::index');
    $routes->post('registrasi/approve/(:num)', 'Administrator\RegistrasiController::approve/$1');
    $routes->post('registrasi/reject/(:num)', 'Administrator\RegistrasiController::reject/$1');

    // -------------------------
    // SETTING SISTEM
    // -------------------------
    $routes->get('setting', 'Administrator\SettingController::index');
    $routes->post('setting/update-sistem', 'Administrator\SettingController::updateSistem');
    $routes->post('setting/update-bobot', 'Administrator\SettingController::updateBobot');
    $routes->post('setting/reset-default', 'Administrator\SettingController::resetDefault');

    // -------------------------
    // PERBAIKAN ALAT
    // -------------------------
    $routes->get('master-data/perbaikan-alat', 'Administrator\PerbaikanAlatController::index');
    $routes->get('master-data/perbaikan-alat/create', 'Administrator\PerbaikanAlatController::create');
    $routes->post('master-data/perbaikan-alat/store', 'Administrator\PerbaikanAlatController::store');
    $routes->get('master-data/perbaikan-alat/edit/(:num)', 'Administrator\PerbaikanAlatController::edit/$1');
    $routes->post('master-data/perbaikan-alat/update/(:num)', 'Administrator\PerbaikanAlatController::update/$1');
    $routes->get('master-data/perbaikan-alat/selesai/(:num)', 'Administrator\PerbaikanAlatController::selesai/$1');
    $routes->get('master-data/perbaikan-alat/delete/(:num)', 'Administrator\PerbaikanAlatController::delete/$1');

    // -------------------------
    // LOG
    // -------------------------
    $routes->get('log', 'Administrator\LogController::index');
});


// =====================================================
// SUB UNIT
// =====================================================
$routes->group('sub-unit', ['filter' => ['auth', 'role:sub_unit']], function ($routes) {

    $routes->get('dashboard', 'SubUnit\DashboardController::index');

    $routes->get('usulan', 'SubUnit\UsulanController::index');
    $routes->get('usulan/create', 'SubUnit\UsulanController::create');
    $routes->post('usulan/store', 'SubUnit\UsulanController::store');
    $routes->get('usulan/detail/(:num)', 'SubUnit\UsulanController::detail/$1');
    $routes->get('usulan/edit/(:num)', 'SubUnit\UsulanController::edit/$1');
    $routes->post('usulan/update/(:num)', 'SubUnit\UsulanController::update/$1');
    $routes->post('usulan/ajukan/(:num)', 'SubUnit\UsulanController::ajukan/$1');

    $routes->get('barang-pengadaan', 'SubUnit\BarangPengadaanController::index');
    $routes->post('barang-pengadaan/konfirmasi-terima/(:num)', 'SubUnit\BarangPengadaanController::konfirmasiTerima/$1');
    $routes->post('barang-pengadaan/konfirmasi/(:num)', 'SubUnit\BarangPengadaanController::konfirmasiTerima/$1');
});


// =====================================================
// GUDANG (V4 FINAL ENGINE MOORA OPERASIONAL)
// =====================================================
$routes->group('gudang', ['filter' => ['auth', 'role:gudang']], function ($routes) {

    $routes->get('dashboard', 'Gudang\DashboardController::index');

    $routes->get('usulan-masuk', 'Gudang\UsulanMasukController::index');
    $routes->get('usulan-masuk/detail/(:num)', 'Gudang\UsulanMasukController::detail/$1');
    $routes->post('usulan-masuk/verifikasi/(:num)', 'Gudang\UsulanMasukController::verifikasi/$1');
    $routes->post('usulan-masuk/banding/(:num)', 'Gudang\UsulanMasukController::banding/$1');

    $routes->get('detail-usulan/(:num)', 'Gudang\DetailUsulanController::detail/$1');
    $routes->post('detail-usulan/proses-moora/(:num)', 'Gudang\DetailUsulanController::prosesMoora/$1');

    $routes->get('penilaian', 'Gudang\PenilaianController::index');
    $routes->get('penilaian/detail/(:num)', 'Gudang\PenilaianController::detail/$1');
    $routes->post('penilaian/submit/(:num)', 'Gudang\PenilaianController::submit/$1');

    $routes->get('hasil-moora', 'Gudang\HasilMooraController::index');
    $routes->get('hasil-moora/detail/(:num)', 'Gudang\HasilMooraController::detail/$1');
    $routes->post('hasil-moora/proses/(:num)', 'Gudang\HasilMooraController::proses/$1');

    // V4 Final Engine aliases
    $routes->get('moora', 'Gudang\MooraController::index');
    $routes->post('moora/proses/(:num)', 'Gudang\MooraController::proses/$1');
    $routes->post('proses-moora/(:num)', 'Gudang\MooraController::proses/$1');

    $routes->get('stok', 'Gudang\StokController::index');
    $routes->get('stok/detail/(:num)', 'Gudang\StokController::detail/$1');
    $routes->post('stok/detail/update/(:num)', 'Gudang\StokController::updateDetail/$1');
    $routes->get('stok/opname', 'Gudang\StokController::opname');
    $routes->post('stok/opname/update/(:num)', 'Gudang\StokController::updateMinimum/$1');

    $routes->get('penerimaan', 'Gudang\PenerimaanController::index');
    $routes->post('penerimaan/store', 'Gudang\PenerimaanController::store');
    $routes->post('penerimaan/terima-serah/(:num)', 'Gudang\PenerimaanController::terimaSerah/$1');

    $routes->get('pengambilan', 'Gudang\PengambilanController::index');
    $routes->post('pengambilan/store', 'Gudang\PengambilanController::store');

    $routes->get('riwayat', 'Administrator\LogController::index');
});


// =====================================================
// MANAJER UMUM
// =====================================================
$routes->group('manajer-umum', ['filter' => ['auth', 'role:manajer_umum']], function ($routes) {

    $routes->get('dashboard', 'ManajerUmum\DashboardController::index');

    $routes->get('riwayat-validasi', 'ManajerUmum\RiwayatValidasiController::index');
    $routes->get('riwayat-validasi/exportExcel', 'ManajerUmum\RiwayatValidasiController::exportExcel');

    $routes->get('usulan', 'ManajerUmum\UsulanController::index');
    $routes->get('usulan/detail/(:num)', 'ManajerUmum\UsulanController::detail/$1');
    $routes->post('usulan/rekomendasi/(:num)', 'ManajerUmum\UsulanController::rekomendasi/$1');
    $routes->post('usulan/kembalikan/(:num)', 'ManajerUmum\UsulanController::kembalikan/$1');

    $routes->get('hasil-moora', 'ManajerUmum\HasilMooraController::index');
});


// =====================================================
// DIREKTUR
// =====================================================
$routes->group('direktur', ['filter' => ['auth', 'role:direktur']], function ($routes) {

    $routes->get('dashboard', 'Direktur\DashboardController::index');

    $routes->get('hasil', 'Direktur\HasilController::index');
    $routes->get('hasil/detail/(:num)', 'Direktur\HasilController::detail/$1');

    $routes->get('validasi', 'Direktur\ValidasiController::index');
    $routes->get('validasi/detail/(:num)', 'Direktur\ValidasiController::detail/$1');
    $routes->post('validasi/setujui/(:num)', 'Direktur\ValidasiController::setujui/$1');
    $routes->post('validasi/tolak/(:num)', 'Direktur\ValidasiController::tolak/$1');
});


// =====================================================
// PENGADAAN
// =====================================================
$routes->group('pengadaan', ['filter' => ['auth', 'role:pengadaan']], function ($routes) {

    $routes->get('dashboard', 'Pengadaan\DashboardController::index');

    $routes->get('pembelian', 'Pengadaan\PembelianController::index');
    $routes->post('pembelian/store', 'Pengadaan\PembelianController::store');
    $routes->post('pembelian/update-status/(:num)', 'Pengadaan\PembelianController::updateStatus/$1');

    $routes->get('dokumen', 'Pengadaan\DokumenController::index');
    $routes->post('dokumen/upload', 'Pengadaan\DokumenController::upload');
    $routes->get('dokumen/file/(:segment)', 'Pengadaan\DokumenController::file/$1');

    $routes->get('serah-barang', 'Pengadaan\SerahBarangController::index');
    $routes->post('serah-barang/store', 'Pengadaan\SerahBarangController::store');
});


// =====================================================
// 404 OVERRIDE
// =====================================================
$routes->set404Override(function () {
    return view('errors/html/error_404');
});