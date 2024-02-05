<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanSubscriptionController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

//Admin Route
Route::any('/', function () {return redirect()->route('admin.login.view');});
Route::controller(AuthController::class)->group(function () {

    Route::get('/login', 'index')->name('admin.login.view');
    Route::post('/login', 'login')->name('admin.login.submit');
});

Route::group(['middleware' => 'admin_auth'], function () {

    Route::controller(DashboardController::class)->group(
        function () {

            Route::get('/dashboard', 'index')->name('admin.dashboard');

            Route::get('/logout', 'logout')->name('admin.logout');
        }
    );

    //plan management
    Route::controller(PlanSubscriptionController::class)->prefix('/plan-subscriptions')->group(
        function () {

            Route::get('/create-plan', 'index')->name('admin.plans.add');
            Route::post('/create-plan', 'create')->name('admin.plans.create');
            Route::get('/lists', 'getPlanView')->name('admin.plans.list.view');
            Route::get('/get-plan-lists', 'getPlanLists')->name('admin.plans.list');
            Route::post('/delete-plan', 'deletePlan')->name('admin.delete.plan');
            Route::get('/plan/detail/{plan_id}', 'planDetail')->name('admin.plan.detail');
            Route::get('/edit/{plan_id}', 'planEditView')->name('admin.plan.edit');
            Route::post('/plan-update', 'updatePlan')->name('admin.plan.update');

        }
    );

    // Categories Management
    Route::controller(CategoriesController::class)->prefix('/categories')->group(function () {

        Route::get('/create-category', 'index')->name('admin.categories.add');
        Route::post('/create-category', 'create')->name('admin.categories.create');
        Route::get('/lists', 'getCategoriesView')->name('admin.categories.list.view');
        Route::get('/get-category-lists', 'getCategoriesList')->name('admin.categories.list');
        Route::post('/delete-category', 'deleteCategory')->name('admin.delete.category');
        Route::get('/category/detail/{category_id}', 'categoryDetail')->name('admin.category.detail');
        Route::get('/edit/{category_id}', 'categoryEditView')->name('admin.category.edit');
        Route::post('/category-update', 'updateCategory')->name('admin.category.update');

    });

    //Blogs management
    Route::controller(BlogController::class)->group(function () {

        Route::prefix('blogscontroller')->group(function () {
            Route::get('/create', 'index')->name('admin.blogs.add');
            Route::post('/create', 'create')->name('admin.blogs.create');

            Route::get('/get-blog-lists', 'getBlogLists')->name('admin.blogs.get-list');
            Route::get('/edit/{blog}', 'getEdit')->name('admin.blogs.edit');
            Route::post('/update/{blog}', 'postUpdate')->name('admin.blogs.update');
            Route::post('/delete-blog', 'deleteBlog')->name('admin.delete.blog');
            Route::get('/detail/{blog}', 'getShow')->name('admin.blogs.detail');
        });
    });

});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');

    return "Cache cleared successfully";
});
