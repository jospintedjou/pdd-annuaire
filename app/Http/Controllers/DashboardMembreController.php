<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Activite;
use App\Models\CategorieActivite;
use App\Models\Groupe;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DashboardMembreController extends DashboardController
{
   
    public function index(Request $request)
    {
        $users = User::where('role', '!=', Constantes::ROLE_ADMIN)->get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-user-index', compact('users', 'nombreMembres'));
    }

    /* Dashboard of one user */
    public function dashboard(Request $request)
    {
        if(!$request->user){
            abort(404);
        }
        $user = User::find($request->user);

        $groupe = $user->groupeActif()->first();
        $sousZone = $user->groupeActif()->first()->sousZone;
        $zone = $user->groupeActif()->first()->sousZone->zone;

        //Get all activities related to this user
        $categorieActivitesArr = CategorieActivite::get();
        $categorieActivites = [];
        $activites = Activite::get();

        foreach($categorieActivitesArr as $categorieActivite){

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
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$zone->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$zone->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$sousZone->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$sousZone->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_GROUPE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$groupe->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$groupe->id])
                        ->count();
                    break;
                default:
                    $nombreActivite = 0;
                    $nombreParticipation = 0;
                    break;
            }

            /* If the activity is annual, we consider 01 attemp per year even if the were more than one attemps.
             E.g: we can have 03 optionnal Retreat but every member should attemp for one */
            if($categorieActivite->periodicite == Constantes::PERIODE_ANNUELLE){
                $nombreActivite = $nombreActivite > 0 ? 1 : 0;
            }

            $categorieActivites[$categorieActivite->nom] = array("nombreActivite" => $nombreActivite,
                "nombreParticipation" => $nombreParticipation );
            $categorieActivites[$categorieActivite->nom]["stats"] = $categorieActivites[$categorieActivite->nom]["nombreActivite"] > 0
                ? $categorieActivites[$categorieActivite->nom]["nombreParticipation"] * 100 / $categorieActivites[$categorieActivite->nom]["nombreActivite"] : 0;
        }

        $categorieActivitesDetails = $this->activityStats(array($user));

        return view('dashboard.dashboard-user', compact('categorieActivitesDetails', 'user', 'activites', 'categorieActivites'));
    }

    /* Return stats */
    protected function getPourcentageActivite($nombreActivite, $nombreParticipation, $nombreMembres){

        $res = $nombreActivite > 0 ? $nombreParticipation * 100 / ($nombreMembres * $nombreActivite) : 0;

        return $res;
    }
}
