<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MeetingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/exams', [ExamController::class, 'showExamFiles'])->name('exams');
    Route::post('/upload-file', [ExamController::class, 'ExamUploadController'])->name('ExamUploadController');

    Route::get('/reports', [ReportController::class, 'showReportFiles'])->name('reports');
    Route::get('/create-report', [ReportController::class, 'showCreateReportForm'])->name('create.report');
    Route::post('/create-report', [ReportController::class, 'createReport'])->name('store.report');
    Route::get('/dynamic-form', [ReportController::class, 'showDynamicForm'])->name('dynamic.form');
    Route::post('/dynamic-form', [ReportController::class, 'processDynamicForm'])->name('dynamic.form.submit');
    Route::get('/user/reports/{id}', [ReportController::class, 'showUserReportForm'])->name('view.user.report.form');

    Route::delete('/remove-file/{id}', [FileController::class, 'removeFile'])->name('remove.file');
    Route::get('/rename-file/{id}', [FileController::class, 'showRenameForm'])->name('rename.file');
    Route::post('/rename-file/{id}', [FileController::class, 'renameFile'])->name('rename.file.submit');
    Route::get('/download-file/{id}', [FileController::class, 'downloadFile'])->name('download.file');

    Route::get('/grading', [GradingController::class, 'showFolders'])->name('grading');
    Route::get('/grading/create', [GradingController::class, 'showCreateFolder'])->name('grading.create');
    Route::post('/grading/store', [GradingController::class, 'processGradingForm'])->name('process.grading.form');
    Route::delete('/grading/folders/{id}', [GradingController::class, 'destroyFolder'])->name('grading.destroyFolder');
    Route::get('/add-user-to-folder/{folder_id}', [GradingController::class, 'showAddUserToFolderPage'])->name('add_user_to_folder_page');
    Route::post('/grading/folders/{folder_id}/add-user', [GradingController::class, 'addUserToFolder'])->name('add_user_to_folder');
    Route::get('/grading/folders/{id}', [GradingController::class, 'showFolder'])->name('grading.showFolderDetails');
    Route::get('/fill-report/{id}', [GradingController::class, 'fillOutReport'])->name('fill_report');
    Route::get('/edit-report/{folderId}/{fileId}', [GradingController::class, 'editReport'])->name('edit_report');
    Route::post('/process-edit-form', [GradingController::class, 'processEditForm'])->name('process_edit_form');
    Route::post('/process-dynamic-form', [GradingController::class, 'processDynamicForm'])->name('process_dynamic_form');
    Route::get('/grading/folders/{id}/create-final-report', [GradingController::class, 'createFinalReport'])->name('create-final-report');
    Route::post('/grading/folders/{id}/create-final-report', [GradingController::class, 'processFinalForm'])->name('process-final-form');
    
    Route::get('/edit-final-form/{folderId}/{fileId}', [GradingController::class, 'editFinalform'])->name('edit-final-form');
    Route::post('/process-edit-final-form', [GradingController::class, 'processEditFinalForm'])->name('process_edit_final_form');
});

require __DIR__.'/auth.php';
