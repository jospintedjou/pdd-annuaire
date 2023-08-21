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

class DashboardController extends Controller
{
    /* Zone Dashboard details */
    protected function activityStats($users)
    {

        $nombreMembres = sizeof($users);
        //Get all activities related to this user
        $categorieActivitesArr = CategorieActivite::get();
        $categorieActivites = [];
        $nombreActivite = 0;

        $activites = [];

        foreach($categorieActivitesArr as $categorieActivite){

            if($categorieActivite->activites->count()){
                foreach($categorieActivite->activites as $activite){

                    $categorieActivites[$categorieActivite->nom][$activite->nom]["nombreParticipation"] = 0;

                    foreach($users as $user){
                        $groupe = $user->groupeActif() ? $user->groupeActif() : null;

                        if($groupe) {
                            $nombreActivite = 1;
                            $nombreParticipation = $user->activites()
                                ->where('activite_id', $activite->id)
                                ->count();

                            $categorieActivites[$categorieActivite->nom][$activite->nom]["nombreParticipation"] =
                                isset($categorieActivites[$categorieActivite->nom][$activite->nom]["nombreParticipation"])
                                    ? $categorieActivites[$categorieActivite->nom][$activite->nom]["nombreParticipation"] + $nombreParticipation
                                    : $nombreParticipation;
                        }
                    }

                    $categorieActivites[$categorieActivite->nom][$activite->nom]["nombreActivite"] = $nombreActivite;
                    $categorieActivites[$categorieActivite->nom][$activite->nom]["stats"] = $nombreMembres > 0
                        ? round($categorieActivites[$categorieActivite->nom][$activite->nom]["nombreParticipation"] * 100 / $nombreMembres, 2) : 0;
                }
            }else{
                $categorieActivites[$categorieActivite->nom][""]["nombreParticipation"] = 0;
                $categorieActivites[$categorieActivite->nom][""]["nombreActivite"] = 0;
                $categorieActivites[$categorieActivite->nom][""]["stats"] = 0;
            }

        }

        return $categorieActivites;
    }

}
