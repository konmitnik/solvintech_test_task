<?php

use App\Http\Controllers\UserController;
use App\Models\Users;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request, Users $user) {
    if (!$request->session()->get('token') || !$user->isUserExistByToken($request->session()->get('token'))) {
        return Redirect::to('/login');
    }
    return view('index', ['token' => session()->get('token')]);
})->name('mainPage');

Route::get('/register', [UserController::class, 'registrationPage'])->name('registrationPage');
Route::get('/login', [UserController::class, 'loginPage'])->name('loginPage');
Route::post('/new-user', [UserController::class, 'newUser'])->name('newUser');
Route::post('/login-user', [UserController::class, 'login'])->name('login');
Route::post('/new-token', [UserController::class, 'generateNewToken'])->name('generateNewToken');
