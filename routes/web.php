<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\MyPostsController;
use App\Http\Controllers\PostsController;
use App\Models\Post;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $userInfo = auth()->user();
    // dd($userInfo);
    return view('dashboard', compact('userInfo'));
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    return 'Wellcome!';
});

Route::get('/test2', function() {
    return view('test.index');
});

Route::get('/test3', function() {
    // 비즈니스 로직 처리
    $name = '홍길동';
    $age = 20;
    // return view('test.show', ['name'=>$name, 'age'=>$age]);
    return view('test.show', compact('name', 'age'));
});

Route::get('/test4', [TestController::class, 'index']);

Route::get('/posts/create', [PostsController::class, 'create']);/*->middleware(['auth']);*/

Route::get('/myposts/mycreate', [MyPostsController::class, 'mycreate']);

Route::post('/myposts/mystore', [MyPostsController::class, 'mystore'])->name('posts.mystore');

Route::post('/posts/store', [PostsController::class, 'store'])->name('posts.store');/*->middleware(['auth']);*/

Route::get('/posts/index', [PostsController::class, 'index'])->name('posts.index');

Route::get('/myposts/myshow/{id}', [MyPostsController::class, 'myshow'])->name('posts.myshow');

Route::get('/posts/show/{id}', [PostsController::class, 'show'])->name('posts.show');

Route::get('/myposts/mypost', [MyPostsController::class, 'mylist'])->name('posts.mypost');

Route::get('/myposts/{post}', [MyPostsController::class, 'myedit'])->name('posts.myedit');

Route::get('/posts/{post}', [PostsController::class, 'edit'])->name('posts.edit');

Route::put('/posts/{id}', [PostsController::class, 'update'])->name('posts.update');

Route::put('/myposts/{id}', [MyPostsController::class, 'myupdate'])->name('posts.myupdate');

Route::delete('/myposts/{id}', [MyPostsController::class, 'mydestroy'])->name('posts.mydelete');

Route::delete('/posts/{id}', [PostsController::class, 'destroy'])->name('posts.delete');

Route::get('/chart/index', [ChartController::class, 'index']);

Route::get('/posts/likes/{id}', [PostsController::class, 'like'])->name('posts.like');


