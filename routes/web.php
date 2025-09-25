<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
  return redirect()->route('offices');
});
Route::get('/form-upload',    [ExcelController::class, 'formExcel']);
Route::post('/upload-excel',  [ExcelController::class, 'uploadExcel'])->name('upload-excel');

// Otentikasi
Route::get('login',         [UserController::class, 'login'])->name('login');
Route::get('logout',        [UserController::class, 'logout'])->name('logout');
Route::post('login_action', [UserController::class, 'login_action'])->name('login_action');

Route::get('offices',       [MasterController::class, 'office'])->name('offices')->middleware(Authentication::class);
Route::get('outlets',       [MasterController::class, 'outlet'])->name('outlets')->middleware(Authentication::class);
Route::get('roles',         [MasterController::class, 'role'])->name('roles')->middleware(Authentication::class);

// Institutions
Route::get('/institution/{uuid}', [InstitutionController::class, 'detail'])->name('institution.detail')->middleware(Authentication::class);
Route::resource('institutions',   InstitutionController::class)->names([
  'index'     => 'institutions',
  'show'      => 'institution.show',
  'create'    => 'institution.create',
  'edit'      => 'institution.edit',
  'store'     => 'institution.store',
  'update'    => 'institution.update',
  'destroy'   => 'institution.destroy',
])->middleware(Authentication::class);

// USERs
Route::get('users',   [MasterController::class, 'user'])->name('users')->middleware(Authentication::class);

Route::get('/business/{uuid}', [BusinessController::class, 'detail'])->name('business.detail')->middleware(Authentication::class);
Route::resource('businesses',   BusinessController::class)->names([
  'index'     => 'businesses',
  // 'show'      => 'business.show',
  'create'    => 'business.create',
  // 'edit'      => 'business.edit',
  'store'     => 'business.store',
  'update'    => 'business.update',
  'destroy'   => 'business.destroy',
])->middleware(Authentication::class);

Route::get('/business/cause/create',         [CauseController::class, 'create'])->name('cause.create')->middleware(Authentication::class);
Route::get('/business/cause/{uuid}',         [CauseController::class, 'detail'])->name('cause.detail')->middleware(Authentication::class);
Route::get('/business/cause/limits/{uuid}',  [CauseController::class, 'limits'])->name('cause.limits')->middleware(Authentication::class);
Route::post('/business/cause/limits',        [CauseController::class, 'update_limit'])->name('cause.update_limit')->middleware(Authentication::class);
Route::resource('causes', CauseController::class)->names([
  'store'     => 'cause.store',
  'update'    => 'cause.update',
  'destroy'   => 'cause.destroy',
])->middleware(Authentication::class);


Route::resource('files', FileController::class)->names([
  'index'     => 'files',
  'edit'      => 'file.edit',
  'store'     => 'file.store',
  'destroy'   => 'file.destroy',
])->middleware(Authentication::class);


Route::get('/claim/{uuid}', [ClaimController::class, 'detail'])->name('claim.detail')->middleware(Authentication::class);
Route::resource('claims',   ClaimController::class)->names([
  'index'     => 'claims',
  // 'show'      => 'claim.show',
  'create'    => 'claim.create',
  // 'edit'      => 'claim.edit',
  'store'     => 'claim.store',
  'update'    => 'claim.update',
  'destroy'   => 'claim.destroy',
])->middleware(Authentication::class);

Route::post('/policy_validation',  [ClaimController::class, 'policy_validation'])->name('policy_validation')->middleware(Authentication::class);
Route::post('/review_action',      [ClaimController::class, 'review_action'])->name('review_action')->middleware(Authentication::class);


// UPLOAD - AJAX
Route::get('/upload-form',        [ClaimController::class, 'upload_form'])->name('upload');
Route::post('/upload',            [ClaimController::class, 'upload'])->name('upload.file');
Route::post('/upload/delete',     [ClaimController::class, 'deleteFile'])->name('upload.delete');
Route::post('/file_description',  [ClaimController::class, 'file_description'])->name('file_description');
Route::post('/file_decision',     [ClaimController::class, 'file_decision'])->name('file_decision');
Route::post('/file_remarks',      [ClaimController::class, 'file_remarks'])->name('file_remarks');
