<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::resource('projects', ProjectController::class);
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::get('/login/manager', [LoginController::class, 'showManagerLoginForm']);
Route::get('/register/admin', [RegisterController::class, 'showAdminRegisterForm']);
Route::get('/register/manager', [RegisterController::class, 'showManagerRegisterForm']);
Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/manager', [LoginController::class, 'managerLogin']);
Route::post('/register/admin', [RegisterController::class, 'createAdmin']);
Route::post('/register/manager', [RegisterController::class, 'createManager']);
Route::group(['middleware' => 'auth:manager'], function () {
    Route::view('/manager', 'manager');
});
Route::group(['middleware' => 'auth:admin'], function () {
 Route::view('/admin', 'admin');
});
Route::get('logout', [LoginController::class, 'logout']);
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::resource('projects', 'ProjectController');

// Comments CRUD
Route::get('/projects/{project}/comments', [CommentController::class, 'view'])->name('comments.show');
Route::post('/projects/{project}/comments/addComment', [CommentController::class, 'store'])->name('comments.store');

Route::get('/projects/{project}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::post('/projects/{project}/comments/{comment}/updateComment', [CommentController::class, 'update'])->name('comments.update');

Route::get('/projects/{project}/comments/{comment}/deleteComment', [CommentController::class, 'destroy'])->name('comments.destroy');
