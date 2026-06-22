<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\LeaveRequestController;
use App\Http\Controllers\Guru\NotificationController;
use App\Http\Controllers\Orangtua\ParentPortalController;
use App\Http\Controllers\Orangtua\ParentLeaveRequestController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

// ─── Redirect root based on role ───────────────────────────────────────────────
Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    return match(auth()->user()->role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'guru'      => redirect()->route('guru.dashboard'),
        'orangtua'  => redirect()->route('orangtua.dashboard'),
        default     => redirect()->route('login'),
    };
});

// ─── Auth Routes (Breeze) ──────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── ADMIN Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Students CRUD
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/{siswa}/qr', [SiswaController::class, 'showQr'])->name('siswa.qr');
    Route::get('/siswa/{siswa}/qr/download', [SiswaController::class, 'downloadQr'])->name('siswa.qr.download');
    Route::get('/siswa/{siswa}/idcard', [SiswaController::class, 'printIdCard'])->name('siswa.idcard');

    // Classes CRUD
    Route::resource('kelas', KelasController::class);

    // Teachers
    Route::resource('guru', GuruController::class);

    // Parents
    Route::resource('orangtua', App\Http\Controllers\Admin\OrangTuaController::class);

    // Settings
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

    // Attendance management
    Route::get('/absensi', [AttendanceController::class, 'adminIndex'])->name('absensi.index');
    Route::get('/absensi/export', [AttendanceController::class, 'export'])->name('absensi.export');
    Route::post('/absensi/alpha', [AttendanceController::class, 'markAllAlpha'])->name('absensi.alpha');
    Route::post('/absensi/notify-wa', [NotificationController::class, 'sendBulkAlpha'])->name('absensi.notify');

    // Notification logs
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::delete('/notifikasi/clear', [NotificationController::class, 'clearLogs'])->name('notifikasi.clear');

    // Leave requests (admin can also manage)
    Route::get('/izin', [LeaveRequestController::class, 'index'])->name('izin.index');
    Route::patch('/izin/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('izin.approve');
    Route::patch('/izin/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('izin.reject');

    // Leaderboard
    Route::get('/leaderboard', [AdminController::class, 'leaderboard'])->name('leaderboard');
});

// ─── GURU Routes ──────────────────────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru,admin'])->group(function () {
    Route::get('/dashboard', [DashboardGuruController::class, 'index'])->name('dashboard');

    // QR Scanner
    Route::get('/scanner', [AttendanceController::class, 'scannerPage'])->name('scanner');
    Route::post('/scan', [AttendanceController::class, 'processQr'])->name('scan');

    // Attendance table
    Route::get('/absensi', [AttendanceController::class, 'guruIndex'])->name('absensi.index');
    Route::get('/absensi/export', [AttendanceController::class, 'export'])->name('absensi.export');

    // Leave requests
    Route::get('/izin', [LeaveRequestController::class, 'index'])->name('izin.index');
    Route::patch('/izin/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('izin.approve');
    Route::patch('/izin/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('izin.reject');
    Route::get('/izin/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('izin.show');

    // Leaderboard
    Route::get('/leaderboard', [DashboardGuruController::class, 'leaderboard'])->name('leaderboard');
});

// ─── ORANG TUA Routes ─────────────────────────────────────────────────────────
Route::prefix('orangtua')->name('orangtua.')->middleware(['auth', 'role:orangtua'])->group(function () {
    Route::get('/dashboard', [ParentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/absensi', [ParentPortalController::class, 'attendance'])->name('absensi');

    // Leave requests by parent
    Route::get('/izin', [ParentLeaveRequestController::class, 'index'])->name('izin.index');
    Route::get('/izin/buat', [ParentLeaveRequestController::class, 'create'])->name('izin.create');
    Route::post('/izin', [ParentLeaveRequestController::class, 'store'])->name('izin.store');
    Route::get('/izin/{leaveRequest}', [ParentLeaveRequestController::class, 'show'])->name('izin.show');
});
