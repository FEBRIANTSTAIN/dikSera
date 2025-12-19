<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminPerawatController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PerawatDrhController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\ManajemenAkunController;
use App\Http\Controllers\PenanggungJawabUjianController;
use App\Http\Controllers\UserFormController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\PengajuanSertifikatController;
use App\Http\Controllers\AdminPengajuanWawancaraController;
use App\Http\Controllers\AdminLisensiController;
use App\Http\Controllers\PewawancaraController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register/perawat', [AuthController::class, 'showPerawatRegisterForm'])->name('register.perawat');
Route::post('/register/perawat', [AuthController::class, 'registerPerawat'])->name('register.perawat.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/admin', [DashboardController::class, 'adminIndex'])->name('dashboard.admin');
    Route::get('/dashboard/pewawancara', [PewawancaraController::class, 'index'])->name('dashboard.pewawancara');

    // === GROUP ADMIN ===
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/perawat', [AdminPerawatController::class, 'index'])->name('perawat.index');
        Route::get('/perawat/{id}', [AdminPerawatController::class, 'show'])->name('perawat.show');
        Route::get('/perawat/{id}/edit', [AdminPerawatController::class, 'edit'])->name('perawat.edit');
        Route::put('/perawat/{id}', [AdminPerawatController::class, 'update'])->name('perawat.update');
        Route::delete('/perawat/{id}', [AdminPerawatController::class, 'destroy'])->name('perawat.destroy');
        Route::get('/perawat/{id}/sertifikat', [AdminPerawatController::class, 'sertifikat'])->name('perawat.sertifikat');

        // LISENSI
        Route::get('/admin/lisensi', [AdminLisensiController::class, 'lisensiIndex'])->name('lisensi.index');
        Route::get('/admin/lisensi/create', [AdminLisensiController::class, 'lisensiCreate'])->name('lisensi.create');
        Route::post('/admin/lisensi', [AdminLisensiController::class, 'lisensiStore'])->name('lisensi.store');
        Route::get('/admin/lisensi/{id}/edit', [AdminLisensiController::class, 'lisensiEdit'])->name('lisensi.edit');
        Route::put('/admin/lisensi/{id}', [AdminLisensiController::class, 'lisensiUpdate'])->name('lisensi.update');
        Route::delete('/admin/lisensi/{id}', [AdminLisensiController::class, 'lisensiDestroy'])->name('lisensi.destroy');

        // PENGAJUAN SERTIFIKAT
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/pengajuan/{id}/approve-score', [AdminPengajuanController::class, 'approveExamScore'])->name('pengajuan.approve_score');
        Route::post('/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::get('/pengajuan/{id}/complete', [AdminPengajuanController::class, 'completeProcess'])->name('pengajuan.complete');
        Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/bulk-approve', [AdminPengajuanController::class, 'bulkApprove'])->name('pengajuan.bulk_approve');
        Route::post('/pengajuan/bulk-approve-score', [AdminPengajuanController::class, 'bulkApproveScore'])->name('pengajuan.bulk_approve_score');
        Route::post('/pengajuan/bulk-approve-interview', [AdminPengajuanController::class, 'bulkApproveInterview'])->name('pengajuan.bulk_approve_interview');

        // PENGAJUAN WAWANCARA (ADMIN ONLY APPROVE/REJECT)
        Route::prefix('pengajuan-wawancara')->name('pengajuan_wawancara.')->group(function () {
            Route::get('/{id}/approve', [AdminPengajuanWawancaraController::class, 'approveJadwal'])->name('approve');
            Route::post('/{id}/reject', [AdminPengajuanWawancaraController::class, 'rejectJadwal'])->name('reject');
        });

        Route::post('/perawat/verifikasi-kelayakan', [AdminPerawatController::class, 'verifikasiKelayakan'])->name('perawat.verifikasi.kelayakan');
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('/telegram/generate', [AdminProfileController::class, 'generateCode'])->name('telegram.generate');
        Route::post('/telegram/unlink', [AdminProfileController::class, 'unlink'])->name('telegram.unlink');
        Route::post('/telegram/test', [AdminProfileController::class, 'testMessage'])->name('telegram.test');

        // MANAJEMEN AKUN
        Route::get('/manajemen-akun', [ManajemenAkunController::class, 'index'])->name('manajemen_akun.index');
        Route::put('/manajemen-akun/{id}/update', [ManajemenAkunController::class, 'updateStatus'])->name('manajemen_akun.update');
        Route::delete('/manajemen-akun/{id}', [ManajemenAkunController::class, 'destroy'])->name('manajemen_akun.destroy');

        // FORM & BANK SOAL
        Route::get('/forms', [FormController::class, 'index'])->name('form.index');
        Route::get('/forms/create', [FormController::class, 'create'])->name('form.create');
        Route::post('/forms', [FormController::class, 'store'])->name('form.store');
        Route::patch('form/{form}/update-status', [FormController::class, 'updateStatus'])->name('form.update-status');
        Route::resource('penanggung-jawab', PenanggungJawabUjianController::class);
        Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('form.edit');
        Route::put('/forms/{form}', [FormController::class, 'update'])->name('form.update');
        Route::delete('/forms/{form}', [FormController::class, 'destroy'])->name('form.destroy');

        Route::get('bank-soal', [BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::get('bank-soal/create', [BankSoalController::class, 'create'])->name('bank-soal.create');
        Route::post('bank-soal/store', [BankSoalController::class, 'store'])->name('bank-soal.store');
        Route::get('bank-soal/{id}/edit', [BankSoalController::class, 'edit'])->name('bank-soal.edit');
        Route::post('bank-soal/{id}/update', [BankSoalController::class, 'update'])->name('bank-soal.update');
        Route::post('bank-soal/{id}/delete', [BankSoalController::class, 'destroy'])->name('bank-soal.delete');
        Route::post('/bank-soal/import-json', [BankSoalController::class, 'importJson'])->name('bank-soal.import-json');

        Route::get('/forms/{form}/hasil', [FormController::class, 'hasil'])->name('form.hasil');
        Route::delete('/hasil-ujian/{result}', [FormController::class, 'resetHasil'])->name('form.reset-hasil');
        Route::post('/forms/{form}/generate-soal', [FormController::class, 'generateSoal'])->name('form.generate-soal');
        Route::get('/forms/{form}/kelola-soal', [FormController::class, 'kelolaSoal'])->name('form.kelola-soal');
        Route::post('/forms/{form}/kelola-soal', [FormController::class, 'simpanSoal'])->name('form.simpan-soal');
    });

    // === GROUP PERAWAT ===
    Route::prefix('perawat')->name('perawat.')->middleware('role:perawat')->group(function () {
        Route::get('/drh', [PerawatDrhController::class, 'index'])->name('drh');
        Route::get('/drh/identitas', [PerawatDrhController::class, 'editIdentitas'])->name('identitas.edit');
        Route::post('/drh/identitas', [PerawatDrhController::class, 'updateIdentitas'])->name('identitas.update');
        Route::get('/drh/data-lengkap', [PerawatDrhController::class, 'showDataLengkap'])->name('data.lengkap');

        // CRUD MODUL DRH (Pendidikan, Pelatihan, dll - disingkat agar fit)
        Route::resource('pendidikan', PerawatDrhController::class)->except(['show']);
        Route::resource('pelatihan', PerawatDrhController::class)->except(['show']);
        Route::resource('pekerjaan', PerawatDrhController::class)->except(['show']);
        Route::resource('keluarga', PerawatDrhController::class)->except(['show']);
        Route::resource('organisasi', PerawatDrhController::class)->except(['show']);
        Route::resource('tanda-jasa', PerawatDrhController::class)->names('tandajasa')->except(['show']);

        // DOKUMEN
        Route::get('/dokumen/lisensi', [PerawatDrhController::class, 'lisensiIndex'])->name('lisensi.index');
        Route::resource('/dokumen/str', PerawatDrhController::class)->names('str')->except(['show']);
        Route::resource('/dokumen/sip', PerawatDrhController::class)->names('sip')->except(['show']);
        Route::resource('/dokumen/tambahan', PerawatDrhController::class)->names('tambahan')->except(['show']);

        // TELEGRAM
        Route::get('/telegram/link', [TelegramController::class, 'linkTelegram'])->name('telegram.link');
        Route::post('/telegram/generate-code', [TelegramController::class, 'generateCode'])->name('telegram.generate-code');
        Route::post('/telegram/unlink', [TelegramController::class, 'unlinkTelegram'])->name('telegram.unlink');

        // UJIAN
        Route::get('/ujian-aktif', [UserFormController::class, 'index'])->name('ujian.index');
        Route::get('/ujian-aktif/{form:slug}', [UserFormController::class, 'show'])->name('ujian.show');
        Route::get('/ujian/{form:slug}/kerjakan', [UserFormController::class, 'kerjakan'])->name('ujian.kerjakan');
        Route::post('/ujian/{form:slug}/submit', [UserFormController::class, 'submit'])->name('ujian.submit');
        Route::get('/ujian/{form:slug}/selesai', [UserFormController::class, 'selesai'])->name('ujian.selesai');

        // PENGAJUAN
        Route::get('/pengajuan/{id}/sertifikat', [PengajuanSertifikatController::class, 'printSertifikat'])->name('pengajuan.sertifikat');
        Route::get('/pengajuan', [PengajuanSertifikatController::class, 'index'])->name('pengajuan.index');
        Route::post('/pengajuan/store', [PengajuanSertifikatController::class, 'store'])->name('pengajuan.store');
        Route::post('/pengajuan/{id}/pilih-metode', [PengajuanSertifikatController::class, 'pilihMetode'])->name('pengajuan.pilih_metode');
        Route::post('/pengajuan/{id}/store-wawancara', [PengajuanSertifikatController::class, 'storeWawancara'])->name('pengajuan.store_wawancara');
        Route::get('/dokumen/lisensi/{id}/generate', [PerawatDrhController::class, 'generateLisensi'])->name('lisensi.generate');
    });

    // === GROUP PEWAWANCARA ===
    Route::prefix('pewawancara')->name('pewawancara.')->middleware('role:pewawancara')->group(function () {
        Route::get('/dashboard', [PewawancaraController::class, 'index'])->name('dashboard');
        Route::get('/riwayat', [PewawancaraController::class, 'riwayat'])->name('riwayat');
        Route::get('/penilaian/{id}', [PewawancaraController::class, 'showPenilaian'])->name('penilaian');
        Route::post('/penilaian/{id}', [PewawancaraController::class, 'storePenilaian'])->name('store_nilai');
    });

    Route::post('/webhook', [TelegramController::class, 'webhook'])->name('webhook');
});