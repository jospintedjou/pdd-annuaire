<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Activite;
use App\Models\CategorieActivite;
use App\Models\Groupe;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   /* public function index(Request $request)
    {
        abort(404);
        return view('dashboard.dashboard', compact('user', 'activites'));
    }

    public function GroupeDashboardIndex(Request $request)
    {
        $groupes = Groupe::get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-groupe-index', compact('groupes', 'nombreMembres'));
    }

    public function UserDashboardIndex(Request $request)
    {
        $users = User::where('role', '!=', Constantes::ROLE_ADMIN)->get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-user-index', compact('users', 'nombreMembres'));
    }

    public function ZoneDashboardIndex(Request $request)
    {
        $zones = Zone::get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-zone-index', compact('zones', 'nombreMembres'));
    }
*/
    /* Dashboard of one zone*/
    public function ZoneDashboard(Request $request)
    {
        if(!$request->zone){
            abort(404);
        }
        //$request->zone = 1;
        $zone = Zone::find($request->zone);

        $users = $zone->getMembres();

        $nombreMembres = $users->count();
        //Get all activities related to this user
        $queryCategorieActivites = CategorieActivite::get();
        $categorieActivites = [];
        $nombreActivite = 0;

        $activites = Activite::get();

        foreach($queryCategorieActivites as $categorieActivite){

            $categorieActivites[$categorieActivite->nom]["nombreParticipation"] = 0;

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
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $zone->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $zone->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $sousZone->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $sousZone->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_GROUPE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $groupe->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $groupe->id])
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

        return view('dashboard.dashboard-zone', compact('activites', 'categorieActivites', 'zone', 'nombreMembres'));
    }

    /* Dashboard of one Groupe*/
    public function GroupeDashboard(Request $request)
    {
        if(!$request->groupe){
            abort(404);
        }
        //$request->groupe = 1;
        $groupe = Groupe::find($request->groupe);

        $users = $groupe->users()->where('actif', Constantes::ETAT_ACTIF)->get();

        $nombreMembres = $users->count();
        //Get all activities related to this user
        $queryCategorieActivites = CategorieActivite::get();
        $categorieActivites = [];
        $nombreActivite = 0;

        $activites = Activite::get();

        foreach($queryCategorieActivites as $categorieActivite){

            $categorieActivites[$categorieActivite->nom]["nombreParticipation"] = 0;

            foreach($users as $user){
                $groupe = $user->groupeActif()->first() ? $user->groupeActif()->first() : null;
                $sousZone = $user->groupeActif()->first() ? $user->groupeActif()->first()->sousZone : null;
                $zone = $user->groupeActif()->first() ? $user->groupeActif()->first()->sousZone->zone : null;

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
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $zone->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_ZONALE, 'zone_id' => $zone->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_SOUS_ZONALE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $sousZone->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id' => $sousZone->id])
                                ->count();
                            break;
                        CASE \App\Constantes::ACTIVITE_GROUPE:
                            $nombreActivite = $categorieActivite->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $groupe->id])
                                ->count();
                            $nombreParticipation = $user->activites()
                                ->where('categorie_activite_id', $categorieActivite->id)
                                ->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE])
                                //->where(['type_activite' => \App\Constantes::ACTIVITE_GROUPE, 'groupe_id' => $groupe->id])
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

        return view('dashboard.dashboard-groupe', compact('users', 'activites', 'categorieActivites', 'groupe', 'nombreMembres'));
    }

    /* Dashboard of one user */
    public function userDashboard(Request $request)
    {
        if(!$request->user){
            abort(404);
        }
        $user = User::find($request->user);

        $groupe = $user->groupeActif()->first();
        $sousZone = $user->groupeActif()->first()->sousZone;
        $zone = $user->groupeActif()->first()->sousZone->zone;

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

        return view('dashboard.dashboard-user', compact('user', 'activites', 'categorieActivites'));
    }

    /* Return stats */
    protected function getPourcentageActivite($nombreActivite, $nombreParticipation, $nombreMembres){

        $res = $nombreActivite > 0 ? $nombreParticipation * 100 / ($nombreMembres * $nombreActivite) : 0;

        return $res;
    }
}
