<?php

use App\Http\Controllers\welcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarMenuController;
use App\Http\Controllers\IndukMenuController;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\RegisterController;

Route::pattern('id', '[0-9]+');
// Route::get('/', [welcomeController::class, 'index']);
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

// Route::group(['prefix' => 'user'], function () {
//     Route::get('/', [UserController::class, 'index']);
//     Route::post('/list', [UserController::class, 'list']);
//     Route::get('/create', [UserController::class, 'create']);
//     Route::post('/', [UserController::class, 'store']);
//     Route::get('/{id}', [UserController::class, 'show']);
//     Route::get('/{id}/edit', [UserController::class, 'edit']);
//     Route::put('/{id}', [UserController::class, 'update']);
//     Route::delete('/{id}', [UserController::class, 'destroy']);
// });

Route::middleware('auth')->group(function () {
    Route::get('/', [welcomeController::class, 'index']);
    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [usercontroller::class, 'index']); //menampilkan halaman awal user
        Route::post('/list', [usercontroller::class, 'list']); //menampilkan data user dalam bentuk json untuk data tables
        Route::get('/create', [usercontroller::class, 'create']); //menampilkan halaman form tambah user
        Route::post('/', [usercontroller::class, 'store']); //menyimpan data user baru
        Route::get('/create_ajax', [usercontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [usercontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [usercontroller::class, 'show']); //menampilkan detail user
        Route::get('/{id}/edit', [usercontroller::class, 'edit']); //menampilkan halaman form edit
        Route::put('/{id}', [usercontroller::class, 'update']); //meyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [usercontroller::class, 'edit_ajax']); //menampilkan halaman form edit ajax
        Route::put('/{id}/update_ajax', [usercontroller::class, 'update_ajax']); // update ajax
        Route::get('/{id}/delete_ajax', [usercontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [usercontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [userController::class, 'show_ajax']);
        Route::delete('/{id}', [usercontroller::class, 'destroy']); //menghapus data user
        Route::get('/import', [userController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [userController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [UserController::class, 'export_excel']); // ajax exsport excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']); // export pdf


    });
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/edit', [UserController::class, 'profile']);
        Route::post('/update_profile', [UserController::class, 'update_profile']);
        Route::put('/update', [UserController::class, 'updateinfo']);
        Route::put('/update_password', [UserController::class, 'update_password']);
        Route::post('/delete_avatar', [UserController::class, 'deleteAvatar']);
    });

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [levelController::class, 'index']);         // menampilkan halaman awal level
        Route::post('/list', [LevelController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create', [LevelController::class, 'create']);  // menampilkan halaman form tambah level
        Route::post('/', [LevelController::class, 'store']);        // menyimpan data level baru
        Route::get('/create_ajax', [Levelcontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::get('/{id}/edit_ajax', [Levelcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [Levelcontroller::class, 'update_ajax']);
        Route::post('/ajax', [Levelcontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [LevelController::class, 'show']);      // menampilkan detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
        Route::put('/{id}', [LevelController::class, 'update']);    // menyimpan perubahan data level
        Route::get('/{id}/delete_ajax', [Levelcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [Levelcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
        Route::get('/import', [LevelController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // ajax exsport excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // export pdf
    });
    Route::group(['prefix' => 'menu', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [MenuController::class, 'index']); // Menampilkan halaman pengaturan menu
        Route::post('/list', [MenuController::class, 'list']); // Menampilkan data menu dalam bentuk JSON
        Route::get('/create', [MenuController::class, 'create']); // Menampilkan halaman form tambah menu
        Route::post('/', [MenuController::class, 'store']); // Menyimpan menu baru
        Route::get('/{menu_id}/edit', [MenuController::class, 'edit']); // Menampilkan form edit menu
        Route::put('/{menu_id}', [MenuController::class, 'update']); // Memperbarui menu
        Route::delete('/{menu_id}', [MenuController::class, 'destroy']); // Menghapus menu
        Route::post('/upload-image', [MenuController::class, 'uploadImage']);
    });
    // Route::group(['prefix' => 'menu', 'middleware' => 'authorize:ADM'], function () {
    //     Route::get('/', [MenuController::class, 'index'])->name('menu.index');
    //     Route::post('/list', [MenuController::class, 'list'])->name('menu.list');
    //     Route::get('/create', [MenuController::class, 'create'])->name('menu.create');
    //     Route::post('/', [MenuController::class, 'store'])->name('menu.store'); // Tambahkan name disini
    //     Route::get('/{menu_id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    //     Route::put('/{menu_id}', [MenuController::class, 'update'])->name('menu.update');
    //     Route::delete('/{menu_id}', [MenuController::class, 'destroy'])->name('menu.destroy');
    //     Route::post('/upload-image', [MenuController::class, 'uploadImage'])->name('upload.image');
    // });
    Route::group(['prefix' => 'indukMenu', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [IndukMenuController::class, 'index']);
        Route::post('/list', [IndukMenuController::class, 'list']);
        Route::get('/create_ajax', [IndukMenuController::class, 'create_ajax']);
        Route::post('/ajax', [IndukMenuController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [IndukMenuController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [IndukMenuController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [IndukMenuController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [IndukMenuController::class, 'delete_ajax']);
    });

    Route::group(['prefix' => 'daftarMenu', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [DaftarMenuController::class, 'index']);
        Route::get('/{id}/subMenu', [DaftarMenuController::class, 'subMenu']);
        Route::get('/content/{pageId}', [DaftarMenuController::class, 'content']);
    });
});
