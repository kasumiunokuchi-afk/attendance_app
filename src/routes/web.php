<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StampCorrectionController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )
        ->name('login');

    Route::get(
        '/admin/login',
        [AuthController::class, 'showLogin']
    )
        ->name('admin.login');
});

Route::middleware('auth')->group(function () {
    Route::prefix('/attendance')->group(function () {
        // 勤怠登録画面
        Route::get(
            '/',
            [AttendanceController::class, 'index']
        )->name('attendance.index');
        Route::post(
            '/clockIn',
            [AttendanceController::class, 'clockIn']
        )->name('attendance.clockIn');
        Route::post(
            '/clockOut',
            [AttendanceController::class, 'clockOut']
        )->name('attendance.clockOut');
        Route::post(
            '/restStart',
            [AttendanceController::class, 'restStart']
        )->name('attendance.restStart');
        Route::post(
            '/restEnd',
            [AttendanceController::class, 'restEnd']
        )->name('attendance.restEnd');

        // 勤怠一覧画面
        Route::get(
            '/list',
            [AttendanceController::class, 'list']
        )->name('attendance.list');

        // 勤怠詳細画面
        Route::get(
            '/detail/{id}',
            [AttendanceController::class, 'detail']
        )->name('attendance.detail');
        Route::POST(
            '/update',
            [StampCorrectionController::class, 'update']
        )->name('attendance.update');

    });

    // 申請一覧
    Route::get(
        '/stamp_correction_request/list',
        [StampCorrectionController::class, 'list']
    )->name('stamp_correction_requests.index');
});

// 管理者権限ユーザー用画面
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::prefix('/attendance')->group(function () {
            // 勤怠一覧画面
            Route::get(
                '/list',
                [AttendanceController::class, 'adminList']
            )->name('admin.attendance.list');

            // 勤怠詳細画面
            Route::get(
                '/{id}',
                [AttendanceController::class, 'detail']
            )->name('admin.attendance');
            Route::PUT(
                '/update',
                [AttendanceController::class, 'update']
            )->name('admin.attendance.update');

            // スタッフ別勤怠一覧画面
            Route::get(
                '/staff/{id}',
                [AttendanceController::class, 'adminStaffList']
            )->name('admin.attendance.staff');
        });

        // スタッフ一覧画面
        Route::get(
            '/staff/list',
            [StaffController::class, 'list']
        )->name('admin.staff.list');
    });

    // 修正申請承認画面
    Route::get(
        '/stamp_correction_request/approve/{id}',
        [StampCorrectionController::class, 'detail']
    )->name('stamp_correction_requests.detail');

    Route::post(
        '/stamp_correction_request/approve/{id}',
        [StampCorrectionController::class, 'approve']
    )->name('stamp_correction_requests.approve');
});

// ユーザー登録画面
Route::post(
    '/register',
    [AuthController::class, 'store']
)
    ->middleware('guest')
    ->name('register');

// ログアウト処理
Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');