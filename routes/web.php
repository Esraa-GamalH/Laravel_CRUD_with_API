<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postController;
use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


// Route::get('/posts', [postController::class, "index"])->name("posts.index");
// Route::get('/posts/create', [postController::class, "create"])->name("posts.create");
// Route::get('/posts/{id}', [postController::class, "show"])->name("posts.show")->where('id', '[0-9]+');
// Route::get('/posts/{id}/edit', [postController::class, "edit"])->name("posts.edit")->where('id', '[0-9]+');
// Route::get('/posts/{id}/destroy', [postController::class, "destroy"])->name("posts.destroy")->where('id', '[0-9]+');
// ### create new object
// # http request method --> post
// Route::post("/posts", [postController::class, 'store'])->name("posts.store");
// Route::post("/posts/{id}", [postController::class, 'update'])->name("posts.update")->where('id', '[0-9]+');

// generate route from resource controller

Route::resource('posts', postController::class);
Route::post('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');

Route::resource('authors', AuthorController::class);

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/******************Login with Github */

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name("github.login");

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();
    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'password' => $githubUser->token,
        'image' => $githubUser->getAvatar(),
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);
    Auth::login($user);

    return redirect('/home');
});

