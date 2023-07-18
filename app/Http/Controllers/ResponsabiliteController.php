<?php

namespace App\Http\Controllers;

use App\Models\Responsabilite;
use Illuminate\Http\Request;

class ResponsabiliteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responsabilites = Responsabilite::get();
        
        return view('responsabilite.index',compact('responsabilites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('responsabilite.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
        ]);

        Responsabilite::create($request->all());
        
        return redirect()->route('responsabilite.index')
                ->with('success', 'Responsabilité créé avec succès.');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Responsabilite  $responsabilite
     * @return \Illuminate\Http\Response
     */
    public function show(Responsabilite $responsabilite)
    {
        return view('responsabilite.show',compact('responsabilite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Responsabilite  $responsabilite
     * @return \Illuminate\Http\Response
     */
    public function edit(Responsabilite $responsabilite)
    {
        return view('responsabilite.edit',compact('responsabilite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Responsabilite  $responsabilite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Responsabilite $responsabilite)
    {
        $request->validate([
            'nom' => 'required'
        ]);

        $responsabilite->update($request->all());

        return redirect()->route('responsabilite.index')
            ->with('success','Responsabilité mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Responsabilite  $responsabilite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            Responsabilite::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }

    }
    
}
