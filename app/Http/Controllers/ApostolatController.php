<?php

namespace App\Http\Controllers;

use App\Models\Apostolat;
use Illuminate\Http\Request;

class ApostolatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apostolats = Apostolat::get();

        return view('apostolats.index',compact('apostolats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apostolats.create');
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

        Apostolat::create($request->all());

        return redirect()->route('apostolats.index')
            ->with('success','Apostolat créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function show(Apostolat $apostolat)
    {
        return view('apostolats.show',compact('apostolat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function edit(Apostolat $apostolat)
    {
        return view('apostolats.edit',compact('apostolat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apostolat $apostolat)
    {
        $request->validate([
            'nom' => 'required'
        ]);

        $apostolat->update($request->all());

        return redirect()->route('apostolats.index')
            ->with('success','Apostolat mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            Apostolat::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }

    }
}
