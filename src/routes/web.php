<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

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
            '/breakStart',
            [AttendanceController::class, 'breakStart']
        )->name('attendance.breakStart');
        Route::post(
            '/breakEnd',
            [AttendanceController::class, 'breakEnd']
        )->name('attendance.breakEnd');

        // TODO : 勤怠一覧画面
        // TODO : 勤怠詳細画面
    });

    // TODO : 申請一覧

});

// 管理者権限ユーザー用画面
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::prefix('/attendance')->group(function () {
            // TODO: 勤怠一覧画面
            Route::get(
                '/',
                [AttendanceController::class, 'index']
            )->name('admin.attendance.index');
            Route::post(
                '/clockIn',
                [AttendanceController::class, 'clockIn']
            )->name('test.clockIn');

            // TODO : 勤怠詳細画面
            // TODO : スタッフ別勤怠一覧画面
        });

        // TODO : スタッフ一覧画面

        // TODO : 申請系画面
        // TODO : 申請一覧画面
        // TODO : 修正申請承認画面
    });
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