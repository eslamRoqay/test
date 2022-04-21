<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\InboxController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
Auth::routes();

Route::get('cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'success';
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {


    Route::get('/', function () {
        return redirect()->route('admin');
    })->name('front.home');


    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin');

    Route::group(['middleware' => 'auth'], function () {

// admins Route
        Route::group(['prefix' => 'admins'], function () {
            $permission = 'admins';
            Route::get('/', [AdminController::class, 'index'])->name('admins')->middleware('permission:read-' . $permission);
            Route::get('create', [AdminController::class, 'create'])->name('admins.create')->middleware('permission:create-' . $permission);
            Route::post('store', [AdminController::class, 'store'])->name('admins.store')->middleware('permission:create-' . $permission);
            Route::get('edit/{id}', [AdminController::class, 'edit'])->name('admins.edit')->middleware('permission:update-' . $permission);
            Route::post('update/{id}', [AdminController::class, 'update'])->name('admins.update')->middleware('permission:update-' . $permission);
            Route::get('delete/{id}', [AdminController::class, 'delete'])->name('admins.delete')->middleware('permission:delete-' . $permission);
            Route::post('deletes', [AdminController::class, 'deletes'])->name('admins.deletes')->middleware('permission:delete-' . $permission);

        });
// roles Route
        Route::group(['prefix' => 'roles'], function () {
            $permission = 'roles';
            Route::get('/', [RoleController::class, 'index'])->name('roles')->middleware('permission:read-' . $permission);
            Route::get('create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-' . $permission);
            Route::post('store', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-' . $permission);
            Route::get('edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:update-' . $permission);
            Route::post('update/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:update-' . $permission);
            Route::post('deletes', [RoleController::class, 'deletes'])->name('roles.deletes')->middleware('permission:delete-' . $permission);
            Route::get('delete/{id}', [RoleController::class, 'delete'])->name('roles.delete')->middleware('permission:delete-' . $permission);
        });

// users Route
        Route::group(['prefix' => 'users'], function () {
            $permission = 'users';
            Route::get('/', [UserController::class, 'index'])->name('users')->middleware('permission:read-' . $permission);
            Route::get('show/{id}', [UserController::class, 'show'])->name('users.show')->middleware('permission:create-' . $permission);
            Route::post('change_status', [UserController::class, 'change_status'])->name('users.change_status');
            Route::get('create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-' . $permission);
            Route::post('store', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-' . $permission);
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:update-' . $permission);
            Route::post('update/{id}', [UserController::class, 'update'])->name('users.update')->middleware('permission:update-' . $permission);
            Route::post('deletes', [UserController::class, 'deletes'])->name('users.deletes')->middleware('permission:delete-' . $permission);
            Route::get('delete/{id}', [UserController::class, 'delete'])->name('users.delete')->middleware('permission:delete-' . $permission);

            //userAddress
            Route::get('address/{id}', [UserController::class, 'indexAddress'])->name('users.address')->middleware('permission:read-' . $permission);

        });
        //settings
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [SettingController::class, 'index'])->name('settings');
            Route::post('/update', [SettingController::class, 'update'])->name('settings.update');
        });
//inboxs
        Route::group(['prefix' => 'inboxes', 'middleware' => 'auth'], function () {
            Route::get('/', [InboxController::class, 'index'])->name('inboxes');
            Route::get('/{id}', [InboxController::class, 'show'])->name('inboxes.show');
        });

    });
});


