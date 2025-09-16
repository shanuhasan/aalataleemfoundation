<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\frontend\BlogController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadImageController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'blogs'])->name('home.blogs');
Route::get('/blogs/{slug}', [BlogController::class, 'blogView'])->name('home.blogs.view');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('home.about');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('home.contact');

Route::get('/cache', function () {
    Artisan::call('optimize:clear');
    return redirect('/');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //pages
    Route::get('/pages', [PageController::class, 'index'])->name('page.index');
    Route::get('/page/create', [PageController::class, 'create'])->name('page.create');
    Route::post('/page/store', [PageController::class, 'store'])->name('page.store');
    Route::get('/page/{id}/edit', [PageController::class, 'edit'])->name('page.edit');
    Route::put('/page/{id}', [PageController::class, 'update'])->name('page.update');
    Route::delete('/page/{id}', [PageController::class, 'destroy'])->name('page.delete');

    //blogs
    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [AdminBlogController::class, 'create'])->name('blog.create');
    Route::post('/blog/store', [AdminBlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{guid}/edit', [AdminBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{guid}', [AdminBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{guid}', [AdminBlogController::class, 'destroy'])->name('blog.delete');

    //Team
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('/team/{guid}/edit', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('/team/{guid}', [TeamController::class, 'update'])->name('team.update');
    Route::delete('/team/{guid}', [TeamController::class, 'destroy'])->name('team.delete');

    Route::post('/upload-image', [UploadImageController::class, 'create'])->name('media.create');

    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }

        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    })->name('getSlug');
});

require __DIR__ . '/auth.php';
