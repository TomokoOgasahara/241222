<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IconController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompsDatabaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form', function () {
    return view('insert');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/user_tou', function () {
    return view('user_touroku');
});

Route::get('/user_tou_kaku', function () {
    return view('user_touroku_kakunin');
});

Route::get('/user_pass', function () {
    return view('user_password');
});

Route::get('/user_pass_kaku', function () {
    return view('user_password_kakunin');
});

Route::get('/log', function () {
    return view('login');
});

Route::get('/top', function () {
    return view('top');
});

Route::get('/comps_database', function () {
    return view('comps_database');
});

Route::get('/dashboard', [IconController::class, 'index']);
Route::get('/icon/create',[IconController::class, 'create']);
Route::get('/icon/{icon_id}', [IconController::class, 'show']);

// 以下の部分を追加してください
Route::post('/icon/store',[IconController::class, 'store']);
Route::post('/icon/{icon_id}/update',[IconController::class, 'update']);
Route::post('/icon/{icon_id}/destroy',[IconController::class, 'destroy']);

// 登録用のルート設定の書き方の基本
Route::post('/icon/store', [IconController::class, 'store']);

Route::get('insert', [IconController::class, 'index']); // データ一覧表示
Route::post('insert', [IconController::class, 'store']); // データ登録
Route::post('update/{id}', [IconController::class, 'update'])->name('update');
Route::post('destroy/{id}',[IconController::class, 'destroy'])->name('destroy');;

Route::get('/user_touroku', [UserController::class, 'index']); // データ一覧表示
Route::post('/user_touroku/store', [UserController::class, 'store']);

Route::post('/user_password/store', [UserPasswordController::class, 'store']); // パスワード登録

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // ログインフォーム
Route::post('/login', [AuthController::class, 'login']); // ログイン処理
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // ログアウト

Route::get('/comps_database', [CompsDatabaseController::class, 'index']);
Route::post('/comps_database', [CompsDatabaseController::class, 'show']);
Route::get('/comps_database', [CompsDatabaseController::class, 'showCompanies']);