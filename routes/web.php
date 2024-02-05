<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Api\AssetLinksController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
return view('welcome');
});*/

Route::prefix('/admin')->group(base_path('routes/admin_route.php'));
Route::get('/', [BlogController::class, 'index'])->name('admin.blogs.view');

Route::get('/admin/blogs', [BlogController::class, 'getBlogView'])->name('admin.blogs.list.view');

// Route::get('/delete-user', [UserController::class, 'deleteUserContent']);

// Route::get('/user', [UserController::class, 'user']);
// Route::get('/users-list', [UserController::class, 'usersList'])->name('usersList');

// Route::get('/app-news', [NewsController::class, 'appNews'])->name('appNews');
// Route::get('/logs', [UserController::class, 'logs'])->name('logs');

Route::get('/.well-known/assetlinks.json', [AssetLinksController::class, 'getAssetLinks']);

Route::get('/create-symlink', function () {
    try {
        Artisan::call('storage:link');
        return 'Symlink created successfully.';
    } catch (\Exception $e) {
        return 'Error creating symlink: ' . $e->getMessage();
    }
});
