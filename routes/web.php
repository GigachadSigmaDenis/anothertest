<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\AdminScheduleController;

Route::get('/', [SiteController::class, 'home']);
Route::get('/news', [SiteController::class, 'news']);
Route::get('/teachers', [SiteController::class, 'teachers']);
Route::get('/schedule', [SiteController::class, 'schedule']);
Route::get('/contacts', [SiteController::class, 'contacts']);
Route::prefix('about')->group(function () {
    Route::get('/', [SiteController::class, 'about']);
    Route::get('/general', [SiteController::class, 'general']);
    Route::get('/structure', [SiteController::class, 'structure']);
    Route::get('/documents', [SiteController::class, 'documents']);
    Route::get('/management', [SiteController::class, 'management']);
});
Route::get('/news/{id}', [SiteController::class, 'newsShow']);
Route::get('/admin', [AdminController::class, 'loginForm']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/news', [AdminNewsController::class, 'index']);
    Route::get('/admin/news/create', [AdminNewsController::class, 'create']);
    Route::post('/admin/news/store', [AdminNewsController::class, 'store']);
    Route::get('/admin/news/edit/{id}', [AdminNewsController::class, 'edit']);
    Route::post('/admin/news/update/{id}', [AdminNewsController::class, 'update']);
    Route::delete('/admin/news/delete/{id}', [AdminNewsController::class, 'destroy']);
    Route::get('/admin/teachers', [AdminTeacherController::class, 'index']);
    Route::get('/admin/teachers/create', [AdminTeacherController::class, 'create']);
    Route::post('/admin/teachers/store', [AdminTeacherController::class, 'store']);
    Route::get('/admin/teachers/edit/{id}', [AdminTeacherController::class, 'edit']);
    Route::post('/admin/teachers/update/{id}', [AdminTeacherController::class, 'update']);
    Route::delete('/admin/teachers/delete/{id}', [AdminTeacherController::class, 'destroy']);
    Route::get('/admin/schedule', [AdminScheduleController::class, 'index']);
    Route::get('/admin/schedule/create', [AdminScheduleController::class, 'create']);
    Route::post('/admin/schedule/store', [AdminScheduleController::class, 'store']);
    Route::get('/admin/schedule/{id}/edit', [AdminScheduleController::class, 'edit']);
    Route::post('/admin/schedule/{id}/update', [AdminScheduleController::class, 'update']);
    Route::delete('/admin/schedule/{id}', [AdminScheduleController::class, 'destroy']);
});