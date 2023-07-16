<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\CategorieActivite;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user){
            abort(404);
        }
        $user = User::find($request->user);

        $groupe = $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first();
        $sousZone = $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone;
        $zone = $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone->zone;

        //Get all activities related to this user
        $queryCategorieActivites = CategorieActivite::get();
        $categorieActivites = [];
        $activites = Activite::get();

        foreach($queryCategorieActivites as $categorieActivite){

            switch($categorieActivite->type_activite){
                CASE \App\Constantes::ACTIVITE_REGIONALE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_ZONALE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$user->zone()->first()->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$user->zone()->first()->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$user->groupeActif()->sousZone()->first()->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$user->groupeActif()->sousZone()->first()->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_GROUPE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$user->groupeActif()->first()->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$user->groupeActif()->first()->id])
                        ->count();
                    break;
                default:
                    $nombreActivite = 0;
                    $nombreParticipation = 0;
                    break;
            }

            $categorieActivites[$categorieActivite->nom] = array("nombreActivite" => $nombreActivite,
                "nombreParticipation" => $nombreParticipation );
        }

        return view('dashboard.dashboard-user', compact('user', 'activites', 'categorieActivites',
            'groupe', 'sousZone', 'zone'));
    }
}
