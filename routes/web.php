<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//USER ROUTES//
/* this route has been configured to have name property the name property is used in middleware functions to act like navigation guards */
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('auth');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('auth');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('auth');

//POST ROUTES //
/* middleware is used to add a layer of authenthication before a particular route is accessed */
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');/* make sure user has logged in before route is accessed */
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('auth');/* make sure user has logged in before route is accessed */
/* the route below is different because it has a dynamic value which is passed into the controller function as its first argument */
/* for this to wrok you have to make sure the variable is an id */
Route::get('/post/{post}', [PostController::class, 'showSinglePost'])->middleware('auth');
Route::delete('/post/{post}', [PostController::class, 'deletePost'])->middleware('auth');
Route::get('/post/{post}/edit', [PostController::class, 'editRoute'])->middleware('auth');
Route::put('/post/{post}', [PostController::class, 'editPost'])->middleware('auth');
Route::get('/search/{term}',[PostController::class, 'search'])->middleware('auth');


//PROFILE ROUTES //
/* the dynamic value here is gotten from the user that has signed in */
/* to achieve this we use the glabally available auth function to get the username which is then passed to the profilePost function */
/* this type of implicit model binding allows laravel to you to pass in the username as the parameter other than the id */
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('auth');
Route::get('/profile/{user:username}', [UserController::class, 'profilePost'])->middleware('auth');
/* this route parameter allows laravel to retrieve the corresponding 'User' model instance/object from 
   database based on the value of the username parameter */
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers'])->middleware('auth');
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing'])->middleware('auth');


//FOLLOW RELATED ROUTES//
Route::post('/create-follow/{user:username}',[FollowController::class, 'createFollow'])->middleware('auth');
Route::post('/remove-follow/{user:username}',[FollowController::class, 'removeFollow'])->middleware('auth');