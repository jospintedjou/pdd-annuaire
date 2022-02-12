<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APostolatController;
use App\Http\Controllers\CategorieActiviteController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\ResponsableGroupeController;
use App\Http\Controllers\ResponsableSousZoneController;
use App\Http\Controllers\ResponsableZoneController;
use App\Http\Controllers\SousZoneController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NiveauEngagementController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::resource('niveau_engagements', NiveauEngagementController::class);
    Route::resource('apostolats', ApostolatController::class);
    Route::resource('categorie_activites', CategorieActiviteController::class);
    Route::resource('activites', ActiviteController::class);
    Route::resource('groupes', GroupeController::class);
    Route::resource('logs', LogController::class);
    Route::resource('participations', ParticipationController::class);
    Route::resource('responsable_groupes', ResponsableGroupeController::class);
    Route::resource('responsable_sous_zones', ResponsableSousZoneController::class);
    Route::resource('responsable_zones', ResponsableZoneController::class);
    Route::resource('sous_zones', SousZoneController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('users', UserController::class);
});


