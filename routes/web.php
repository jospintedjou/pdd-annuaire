<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\SousZoneController;
use App\Http\Controllers\ApostolatController;
use App\Http\Controllers\AnneeSpirituelleController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\ResponsableZoneController;
use App\Http\Controllers\NiveauEngagementController;
use App\Http\Controllers\CategorieActiviteController;
use App\Http\Controllers\ResponsableGroupeController;
use App\Http\Controllers\ResponsableSousZoneController;
use App\Http\Controllers\DashboardZoneController;
use App\Http\Controllers\DashboardGroupeController;
use App\Http\Controllers\DashboardMembreController;
use App\Http\Controllers\ResponsabiliteController;

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
//Clear Config cache:
Route::get('/clear', [App\Http\Controllers\CacheController::class, 'clear'])->name('clear');
Route::get('/clear', [App\Http\Controllers\CacheController::class, 'cache'])->name('cache');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::resource('niveau_engagements', NiveauEngagementController::class);
    Route::resource('apostolats', ApostolatController::class);
    Route::resource('responsabilite', ResponsabiliteController::class);
    Route::resource('annee_spirituelles', AnneeSpirituelleController::class);
    Route::resource('categorie_activites', CategorieActiviteController::class);
    Route::resource('activites', ActiviteController::class);
    Route::resource('groupes', GroupeController::class);
    Route::resource('logs', LogController::class);
    Route::resource('participations', ParticipationController::class);

    //Présence
    Route::get('/presences', [ActiviteController::class, 'presence'])->name('presences.index');
    Route::get('/presences-create', [ActiviteController::class, 'createPresence'])->name('presences.create');
    Route::post('/presences', [ActiviteController::class, 'storePresence'])->name('presences.store');

    //responsable groupe
    Route::get('/responsable_groupes', [ResponsableGroupeController::class, 'index'])
        ->name('responsable_groupes.index');
    Route::get('/responsable_groupes/create', [ResponsableGroupeController::class, 'create'])
        ->name('responsable_groupes.create');
    Route::get('/responsable_groupes/{groupe}', [ResponsableGroupeController::class, 'edit'])
        ->name('responsable_groupes.edit');
    Route::put('/responsable_groupes/{groupe}', [ResponsableGroupeController::class, 'update'])
        ->name('responsable_groupes.update');

    //responsable sous-zone
    Route::get('/responsable_sous_zones', [ResponsableSousZoneController::class, 'index'])
        ->name('responsable_sous_zones.index');
    Route::get('/responsable_sous_zones/create', [ResponsableSousZoneController::class, 'create'])
        ->name('responsable_sous_zones.create');
    Route::get('/responsable_sous_zones/{sous_zone}', [ResponsableSousZoneController::class, 'edit'])
        ->name('responsable_sous_zones.edit');
    Route::put('/responsable_sous_zones/{sous_zone}', [ResponsableSousZoneController::class, 'update'])
        ->name('responsable_sous_zones.update');

    //responsable zone
    Route::get('/responsable_zones', [ResponsableZoneController::class, 'index'])
        ->name('responsable_zones.index');
    Route::get('/responsable_zones/create', [ResponsableZoneController::class, 'create'])
        ->name('responsable_zones.create');
    Route::get('/responsable_zones/{zone}', [ResponsableZoneController::class, 'edit'])
        ->name('responsable_zones.edit');
    Route::put('/responsable_zones/{zone}', [ResponsableZoneController::class, 'update'])
        ->name('responsable_zones.update');

    //Stats
    Route::get('/statistiques_membre.index', [DashboardMembreController::class, 'index'])
        ->name('statistiques_membre.index');
    Route::get('/statistiques_membre', [DashboardMembreController::class, 'dashboard'])
         ->name('statistiques_membre');

    Route::get('/statistiques_groupe.index', [DashboardGroupeController::class, 'index'])
        ->name('statistiques_groupe.index');
     Route::get('/statistiques_groupe', [DashboardGroupeController::class, 'dashboard'])
         ->name('statistiques_groupe');

    Route::get('/statistiques_zone.index', [DashboardZoneController::class, 'index'])
        ->name('statistiques_zone.index');
    Route::get('/statistiques_zone', [DashboardZoneController::class, 'dashboard'])
         ->name('statistiques_zone');

    //Route::resource('responsable_groupes', ResponsableGroupeController::class);
   // Route::resource('responsable_sous_zones', ResponsableSousZoneController::class);
   // Route::resource('responsable_zones', ResponsableZoneController::class);

    Route::resource('sous_zones', SousZoneController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('users', UserController::class);

});


