<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminDocumentController;
use App\Http\Controllers\TeacherScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminUserController;

// ---------------------
// PUBLIC SITE ROUTES
// ---------------------
Route::get('/', [SiteController::class, 'home']);
Route::get('/news', [SiteController::class, 'news']);
Route::get('/news/{id}', [SiteController::class, 'newsShow']);
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

// ---------------------
// AUTH ROUTES
// ---------------------
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// ---------------------
// ADMIN AUTH
// ---------------------
Route::get('/admin', [AdminController::class, 'loginForm']);
Route::post('/admin/login', [AdminController::class, 'login']);

// ---------------------
// ADMIN ROUTES (with admin middleware)
// ---------------------
Route::middleware('admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/logout', [AdminController::class, 'logout']);

    // NEWS
    Route::prefix('news')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index']);
        Route::get('/create', [AdminNewsController::class, 'create']);
        Route::post('/store', [AdminNewsController::class, 'store']);
        Route::get('/edit/{id}', [AdminNewsController::class, 'edit']);
        Route::post('/update/{id}', [AdminNewsController::class, 'update']);
        Route::delete('/delete/{id}', [AdminNewsController::class, 'destroy']);
    });

    // TEACHERS
    Route::prefix('teachers')->group(function () {
        Route::get('/', [AdminTeacherController::class, 'index']);
        Route::get('/create', [AdminTeacherController::class, 'create']);
        Route::post('/store', [AdminTeacherController::class, 'store']);
        Route::get('/edit/{id}', [AdminTeacherController::class, 'edit']);
        Route::post('/update/{id}', [AdminTeacherController::class, 'update']);
        Route::delete('/delete/{id}', [AdminTeacherController::class, 'destroy']);
    });

    // SCHEDULE (ADMIN FULL ACCESS)
    Route::prefix('schedule')->group(function () {
        Route::get('/', [AdminScheduleController::class, 'index']);
        Route::get('/create', [AdminScheduleController::class, 'create']);
        Route::post('/store', [AdminScheduleController::class, 'store']);
        Route::get('/edit', [AdminScheduleController::class, 'edit']);
        Route::post('/update', [AdminScheduleController::class, 'update']);
        Route::post('/delete-day', [AdminScheduleController::class, 'destroyDay']);
    });

    // DOCUMENTS
    Route::prefix('documents')->group(function () {
        Route::get('/', [AdminDocumentController::class, 'index']);
        Route::get('/create', [AdminDocumentController::class, 'create']);
        Route::post('/store', [AdminDocumentController::class, 'store']);
        Route::get('/edit/{id}', [AdminDocumentController::class, 'edit']);
        Route::post('/update/{id}', [AdminDocumentController::class, 'update']);
        Route::delete('/delete/{id}', [AdminDocumentController::class, 'destroy']);
        Route::post('/update-order', [AdminDocumentController::class, 'updateOrder']);
    });

    // USERS (ADMIN)
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index']);
        Route::post('/update-class', [AdminUserController::class, 'updateClass']);
        Route::post('/update-role', [AdminUserController::class, 'updateRole']);
        Route::delete('/delete/{id}', [AdminUserController::class, 'destroy']);
    });
});

// ---------------------
// TEACHER ROUTES
// ---------------------
Route::middleware(['auth', 'teacher'])->prefix('teacher')->group(function () {

    Route::get('/classes', [TeacherController::class, 'index']);
    Route::post('/classes/update', [TeacherController::class, 'update']);

    // SCHEDULE (TEACHER LIMITED)
    Route::prefix('schedule')->group(function () {
        Route::get('/', [TeacherScheduleController::class, 'index']);
        Route::get('/create', [TeacherScheduleController::class, 'create']);
        Route::post('/store', [TeacherScheduleController::class, 'store']);
        Route::get('/edit', [TeacherScheduleController::class, 'edit']);
        Route::post('/update', [TeacherScheduleController::class, 'update']);
    });
});

// ---------------------
// PROFILE
// ---------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
});

