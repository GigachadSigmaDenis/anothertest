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
use App\Http\Controllers\StudentDiaryController;
use App\Http\Controllers\AdminDiaryController;
use App\Http\Controllers\ZamDirController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AdminAnnouncementController;

// ---------------------
// PUBLIC SITE ROUTES
// ---------------------
Route::get('/', [SiteController::class, 'home']);
Route::get('/news', [SiteController::class, 'news']);
Route::get('/news/{id}', [SiteController::class, 'newsShow']);
Route::get('/teachers', [SiteController::class, 'teachers']);
Route::get('/schedule', [SiteController::class, 'schedule']);
Route::get('/contacts', [SiteController::class, 'contacts']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/announcements/{id}', [AnnouncementController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::post('/announcements/read/{id}', [AnnouncementController::class, 'markRead']);
});

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
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
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
        Route::post('/store', [AdminNewsController::class, 'store']);
        Route::post('/update/{id}', [AdminNewsController::class, 'update']);
        Route::delete('/delete/{id}', [AdminNewsController::class, 'destroy']);
    });

    // TEACHERS
    Route::prefix('teachers')->group(function () {
        Route::get('/', [AdminTeacherController::class, 'index']);
        Route::post('/store', [AdminTeacherController::class, 'store']);
        Route::post('/update/{id}', [AdminTeacherController::class, 'update']);
        Route::delete('/delete/{id}', [AdminTeacherController::class, 'destroy']);
    });
    Route::get('/admin/schedule/check-template', [AdminScheduleController::class, 'checkTemplate'])->name('admin.schedule.check-template');


    // DOCUMENTS
    Route::prefix('documents')->group(function () {
        Route::get('/', [AdminDocumentController::class, 'index']);
        Route::post('/store', [AdminDocumentController::class, 'store']);
        Route::post('/update/{id}', [AdminDocumentController::class, 'update']);
        Route::delete('/delete/{id}', [AdminDocumentController::class, 'destroy']);
        Route::post('/update-order', [AdminDocumentController::class, 'updateOrder']);
        Route::post('/check-duplicate', [AdminDocumentController::class, 'checkDuplicate'])->name('admin.documents.check-duplicate');
    });

    // USERS (ADMIN)
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index']);
        Route::post('/update-class', [AdminUserController::class, 'updateClass']);
        Route::post('/update-role', [AdminUserController::class, 'updateRole']);
        Route::delete('/delete/{id}', [AdminUserController::class, 'destroy']);
    });

    // DIARY (ADMIN)
    Route::prefix('diary')->group(function () {
        Route::get('/', [AdminDiaryController::class, 'index']);
        Route::post('/store', [AdminDiaryController::class, 'store']);
        Route::delete('/delete/{id}', [AdminDiaryController::class, 'destroy']);
    });

    // Announcements (admin)
    Route::prefix('announcements')->group(function () {
    Route::get('/', [AdminAnnouncementController::class, 'index']);
    Route::post('/store', [AdminAnnouncementController::class, 'store']);
    Route::post('/update/{id}', [AdminAnnouncementController::class, 'update']);
    Route::delete('/delete/{id}', [AdminAnnouncementController::class, 'destroy']);
});
});

// ---------------------
// TEACHER ROUTES
// ---------------------
Route::middleware(['auth', 'teacher'])->prefix('teacher')->group(function () {
    Route::prefix('diary')->group(function () {
        Route::get('/', [TeacherController::class, 'diary']);
        Route::post('/store', [TeacherController::class, 'storeDiary']);
        Route::delete('/delete/{id}', [TeacherController::class, 'deleteDiary']);
    });

    Route::get('/grades', [TeacherController::class, 'grades']);
});
// Зам. директора - расписание
Route::prefix('zam/schedule')->group(function () {
    Route::get('/', [TeacherScheduleController::class, 'index']);
    Route::post('/store', [TeacherScheduleController::class, 'store']);
    Route::post('/delete-day', [TeacherScheduleController::class, 'destroyDay']);
    Route::post('/apply-template', [TeacherScheduleController::class, 'applyTemplate']);
    Route::post('/save-template', [TeacherScheduleController::class, 'saveTemplate']);
    Route::get('/check-template', [TeacherScheduleController::class, 'checkTemplate'])->name('zam.schedule.check-template');
});

// Админ - расписание
Route::prefix('admin/schedule')->group(function () {
    Route::get('/', [AdminScheduleController::class, 'index']);
    Route::post('/store', [AdminScheduleController::class, 'store']);
    Route::post('/delete-day', [AdminScheduleController::class, 'destroyDay']);
    Route::post('/apply-template', [AdminScheduleController::class, 'applyTemplate']);
    Route::post('/save-template', [AdminScheduleController::class, 'saveTemplate']);
});
// ---------------------
// ZAM DIRECTOR ROUTES
// ---------------------
Route::middleware(['auth', 'zam_dir'])->prefix('zam')->group(function () {
    Route::get('/classes', [ZamDirController::class, 'classes']);
    Route::post('/classes/update', [ZamDirController::class, 'updateClass']);

    Route::prefix('diary')->group(function () {
        Route::get('/', [ZamDirController::class, 'diary']);
        Route::post('/store', [ZamDirController::class, 'storeDiary']);
        Route::delete('/delete/{id}', [ZamDirController::class, 'deleteDiary']);
    });

    Route::get('/grades', [ZamDirController::class, 'grades']);

    Route::prefix('schedule')->group(function () {
        Route::get('/', [TeacherScheduleController::class, 'index']);
        Route::post('/store', [TeacherScheduleController::class, 'store']);
        Route::post('/delete-day', [TeacherScheduleController::class, 'destroyDay']);
    });

    Route::prefix('announcements')->group(function () {
        Route::get('/', [ZamDirController::class, 'announcements']);
        Route::post('/store', [ZamDirController::class, 'storeAnnouncement']);
        Route::post('/update/{id}', [ZamDirController::class, 'updateAnnouncement']);
        Route::delete('/delete/{id}', [ZamDirController::class, 'deleteAnnouncement']);
    });
});

// ---------------------
// PROFILE
// ---------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/diary', [StudentDiaryController::class, 'index']);
});