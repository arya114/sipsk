<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirekturPengajuanController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StafPengajuanController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Rute beranda (hapus salah satu rute beranda jika tidak dibutuhkan)
Route::get('/', [DashboardController::class, 'index'])->name('index');

// Rute untuk dashboard umum
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute profil pengguna dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update'); // Gunakan POST dan @method('PUT') di form
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
    Route::post('/profile/upload-signature', [ProfileController::class, 'uploadSignature'])->name('profile.upload.signature');
});

// Rute untuk dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-user', [DashboardController::class, 'userDashboard'])->name('dashboard.user');
    // Dashboard untuk Admin
    Route::get('/dashboard-staf', [DashboardController::class, 'stafDashboard'])->middleware('role:admin')->name('dashboard.staf');

    // Dashboard untuk Superadmin
    Route::get('/dashboard-direktur', [DashboardController::class, 'direkturDashboard'])->middleware('role:superadmin')->name('dashboard.direktur');

    Route::get('/form-pengajuan', function () {
        return view('form-pengajuan');
    })->name('form.pengajuan');
    Route::post('/form-pengajuan', [PersetujuanController::class, 'store'])->name('persetujuan.store');
});

Route::get('/list-pengajuan', [PersetujuanController::class, 'index'])->name('list.pengajuan');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-staf', [DashboardController::class, 'stafDashboard'])->name('dashboard.staf');
    Route::get('/list-pengajuan-staf', [StafPengajuanController::class, 'index'])->name('list.pengajuan.staf');
    Route::post('/pengajuan/action/{id}', [StafPengajuanController::class, 'action'])->name('pengajuan.action');
    Route::get('/pengajuan/view/{id}', [StafPengajuanController::class, 'view'])->name('pengajuan.view');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard-direktur', [DashboardController::class, 'direkturDashboard'])->name('dashboard.direktur');
    Route::get('/list-pengajuan-direktur', [DirekturPengajuanController::class, 'index'])->name('list.pengajuan.direktur');
    Route::get('/pengajuan/view/{id}', [DirekturPengajuanController::class, 'view'])->name('pengajuan.view');
    Route::get('/pengajuan/confirm/{id}', [DirekturPengajuanController::class, 'confirmAction'])->name('pengajuan.confirm');
    Route::post('/pengajuan/action/{id}', [DirekturPengajuanController::class, 'action'])->name('pengajuan.action');
    Route::get('/pengajuan/approve/{id}', [DirekturPengajuanController::class, 'approveDirect'])->name('pengajuan.approve.direct');
});
Route::get('/send-test-email', function () {
    Mail::raw('Ini adalah email uji dari Laravel menggunakan Mailtrap!', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email from Laravel');
    });

    return 'Test email telah dikirim!';
});

require __DIR__ . '/auth.php';
