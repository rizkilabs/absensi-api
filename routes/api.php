<?php

use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkin', [AttendanceController::class, 'checkIn']);
    Route::post('/checkout', [AttendanceController::class, 'checkOut']);
    Route::get('/history', [AttendanceController::class, 'history']);
});

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/admin/attendances', [AttendanceController::class, 'adminDashboard']);
    Route::get('/admin/attendances/export', function (Illuminate\Http\Request $request) {
        return Excel::download(new AttendanceExport($request), 'absensi.xls');
    });
});