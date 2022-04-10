<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Groupe;
use App\Models\Activite;
use App\Models\SousZone;
use App\Models\Apostolat;
use Illuminate\Http\Request;
use App\Models\ApostolatConcerne;
use App\Models\CategorieActivite;
use Illuminate\Support\Facades\DB;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $activites = Activite::latest()->paginate(5);

        return view('activite.index', compact('activites'))
                ->with('i', (request()->input('page', 1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $zones = Zone::all();
        $sous_zones = SousZone::all();
        $groupes = Groupe::all();
        $apostolats = Apostolat::all();
        $categories = CategorieActivite::all();

        return view('activite.create')
                ->with('zones', $zones)
                ->with('sous_zones', $sous_zones)
                ->with('groupes', $groupes)
                ->with('categories', $categories)
                ->with('apostolats', $apostolats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'zone_id' => 'required_without_all:sous_zone_id,groupe_id|exists:zones,id',
            'sous_zone_id' => 'required_without_all:zone_id,groupe_id|exists:sous_zones,id',
            'groupe_id' => 'required_without_all:zone_id,sous_zone_id|exists:groupes,id',
            'categorie_activite_id' => 'required|exists:categorie_activites,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'heure_debut' => 'required|date_format:H:i',
            'lieu' => 'required|string',
            'apostolat' => 'required|array|min:1',
            'apostolat.*' => 'exists:apostolats,id'
        ]);

        if($data['zone_id']){
            $data['type'] = 'zonale';
        }else if($data['sous_zone_id']){
            $data['type'] = 'sous_zonale';
        }else{
            $data['type'] = 'groupe';
        }

        DB::beginTransaction();
        $activite = Activite::create($data);

        foreach ($data['apostolat'] as $apostolat) {
            ApostolatConcerne::create([
                'categorie_activite_id' => $activite->categorie_activite_id,
                'activite_id' => $activite->id,
                'apostolat_id' => $apostolat
            ]);
        }

        DB::commit();

        return redirect()->route('activites.index')
            ->with('message', 'Activité créé avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function show(Activite $activite)
    {
        //
        return view('activite.show', compact('activite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function edit(Activite $activite)
    {
        //
        $zones = Zone::all();
        $sous_zones = SousZone::all();
        $groupes = Groupe::all();
        $apostolats = Apostolat::all();
        $categories = CategorieActivite::all();

        return view('activite.edit', compact('activite'))
                ->with('zones', $zones)
                ->with('sous_zones', $sous_zones)
                ->with('groupes', $groupes)
                ->with('categories', $categories)
                ->with('apostolats', $apostolats);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activite $activite)
    {
        //
        $data = $request->validate([
            'zone_id' => 'required_without_all:sous_zone_id,groupe_id|exists:zones,id',
            'sous_zone_id' => 'required_without_all:zone_id,groupe_id|exists:sous_zones,id',
            'groupe_id' => 'required_without_all:zone_id,sous_zone_id|exists:groupes,id',
            'categorie_activite_id' => 'required|exists:categorie_activites,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'heure_debut' => 'required|date_format:H:i',
            'lieu' => 'required|string',
            'apostolat' => 'required|array|min:1',
            'apostolat.*' => 'exists:apostolats,id'
        ]);

        if($data['zone_id']){
            $data['type'] = 'zonale';
        }else if($data['sous_zone_id']){
            $data['type'] = 'sous_zonale';
        }else{
            $data['type'] = 'groupe';
        }

        DB::beginTransaction();
        $activite->update($data);

        ApostolatConcerne::where('activite_id', $activite->id)->delete();

        foreach ($data['apostolat'] as $apostolat) {
            ApostolatConcerne::create([
                'categorie_activite_id' => $activite->categorie_activite_id,
                'activite_id' => $activite->id,
                'apostolat_id' => $apostolat
            ]);
        }

        DB::commit();

        return redirect()->route('activites.index')
            ->with('message', 'Activité modifié avec succes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activite $activite)
    {
        //

        DB::beginTransaction();
        ApostolatConcerne::where('activite_id', $activite->id)->delete();

        $activite->delete();
        DB::commit();

        return redirect()->route('activites.index')
            ->with('message', 'Activité supprimée avec succes');
    }
}
