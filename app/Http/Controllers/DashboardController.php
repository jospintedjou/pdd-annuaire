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
        $categorieActivites = CategorieActivite::get();
        $activites = Activite::get();

        foreach($categorieActivites as $categorieActivite){
            $categorieActivites[] = 0;
        }

        return view('dashboard.dashboard-user', compact('user', 'activites', 'categorieActivites',
            'groupe', 'sousZone', 'zone'));
    }
}
