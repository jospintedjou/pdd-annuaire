<?php

namespace App\Http\Controllers;

use App\Constantes;
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
        abort(404);
        return view('dashboard.dashboard', compact('user', 'activites'));
    }

    /* General Dashboard */
    public function dashboard(Request $request)
    {
        if(!$request->zone){
            //abort(404);
        }
        $request->zone = 1;
        $zone = Zone::find($request->zone);

        $users = $zone->getMembres();

        $nombreMembres = $users->count();
        //Get all activities related to this user
        $queryCategorieActivites = CategorieActivite::get();
        $categorieActivites = [];

        $activites = Activite::get();

        foreach($queryCategorieActivites as $categorieActivite){

            foreach($users as $user){
                $groupe = $user->groupeActif() ? $user->groupeActif() : null;
                $sousZone = $user->groupeActif() ? $user->groupeActif()->sousZone : null;
                $zone = $user->groupeActif() ? $user->groupeActif()->sousZone->zone : null;
                if($groupe) {
                    switch ($categorieActivite->type_activite) {
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
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $user->zone()->first()->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $user->zone()->first()->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $user->groupeActif()->sousZone()->first()->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $user->groupeActif()->sousZone()->first()->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_GROUPE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $user->groupeActif()->first()->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $user->groupeActif()->first()->id])
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

                    $categorieActivites[$categorieActivite->nom]["nombreParticipation"] = isset($categorieActivites[$categorieActivite->nom]["nombreParticipation"]) ? $categorieActivites[$categorieActivite->nom]["nombreParticipation"] + $nombreParticipation : $nombreParticipation;
                }
            }

            $categorieActivites[$categorieActivite->nom]["nombreActivite"] = $nombreActivite;
            $categorieActivites[$categorieActivite->nom]["stats"] = $categorieActivites[$categorieActivite->nom]["nombreActivite"] > 0
                ? $categorieActivites[$categorieActivite->nom]["nombreParticipation"] * 100 / ($nombreMembres * $categorieActivites[$categorieActivite->nom]["nombreActivite"]) : 0;
        }

        return view('dashboard.dashboard', compact('user', 'activites', 'categorieActivites',
            'groupe', 'sousZone', 'zone', 'nombreMembres'));
    }

    /* User Dashboard */
    public function userDashboard(Request $request)
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
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$user->zone()->first()->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$user->groupeActif()->sousZone()->first()->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$user->groupeActif()->sousZone()->first()->id])
                        ->count();
                    break;
                CASE \App\Constantes::ACTIVITE_GROUPE:
                    $nombreActivite = $categorieActivite->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$user->groupeActif()->first()->id])
                        ->count();
                    $nombreParticipation = $user->activites()
                        ->where('categorie_activite_id', $categorieActivite->id)
                        ->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE])
                        //->where(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$user->groupeActif()->first()->id])
                        ->count();
                    break;
                default:
                    $nombreActivite = 0;
                    $nombreParticipation = 0;
                    dd($categorieActivite->type_activite);
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

        return view('dashboard.dashboard-user', compact('user', 'activites', 'categorieActivites',
            'groupe', 'sousZone', 'zone'));
    }
}
