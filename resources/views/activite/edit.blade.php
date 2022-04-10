@extends('layouts.app')
@section('page_title') Activit&eacute; @endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            @component('helpers.alert')
            @endcomponent
            <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Modifier une activit&eacute;</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('activites.update', $activite) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class=" @error('categorie_activite_id') has-danger @enderror">
                                            <label for="categorie_activite_id" class="form-label @error('categorie_activite_id') text-danger @enderror">Cat&eacute;gorie</label>
                                            <br>
                                            <select name="categorie_activite_id" id="categorie_activite_id" value="{{ old('categorie_activite_id') ?? $activite->categorie_activite_id }}" class="form-control @error('categorie_activite_id') is-invalid @enderror">
                                                <option value="" disabled >-- <i>Choisir dans la liste</i></option>
                                                @foreach ($categories as $categorie)
                                                    <option value="{{ $categorie->id }}">{{$categorie->nom}}</option>
                                                @endforeach
                                            </select>
                                            @error('categorie_activite_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class=" @error('zone_id') has-danger @enderror">
                                            <label for="zone_id" class="form-label @error('zone_id') text-danger @enderror">Zone</label>
                                            <br>
                                            <select name="zone_id" id="zone_id" value="{{ old('zone_id') ?? $activite->zone_id }}" class="form-control @error('zone_id') is-invalid @enderror">
                                                <option value="" disabled >-- <i>Choisir dans la liste</i></option>
                                                @foreach ($zones as $zone)
                                                    <option value="{{ $zone->id }}">{{$zone->nom}}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class=" @error('sous_zone_id') has-danger @enderror">
                                            <label for="sous_zone_id" class="form-label @error('sous_zone_id') text-danger @enderror">Sous Zone</label>
                                            <br>
                                            <select name="sous_zone_id" id="sous_zone_id" value="{{ old('sous_zone_id') ?? $activite->sous_zone_id }}" class="form-control @error('sous_zone_id') is-invalid @enderror">
                                                <option value="" disabled >-- <i>Choisir dans la liste</i></option>
                                                @foreach ($sous_zones as $sous_zone)
                                                    <option value="{{ $sous_zone->id }}">{{$sous_zone->nom}}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class=" @error('groupe_id') has-danger @enderror">
                                            <label for="groupe_id" class="form-label @error('groupe_id') text-danger @enderror">Groupe</label>
                                            <br>
                                            <select name="groupe_id" id="groupe_id" value="{{ old('groupe_id') ?? $activite->groupe_id }}" class="form-control @error('groupe_id') is-invalid @enderror">
                                                <option value="" disabled >-- <i>Choisir dans la liste</i></option>
                                                @foreach ($groupes as $groupe)
                                                    <option value="{{ $groupe->id }}">{{$groupe->nom_groupe}}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('date_debut') has-danger @enderror">
                                            <label for="date_debut" class="bmd-label-floating @error('date_debut') text-danger @enderror">Date de debut</label>
                                            <input type="date" name="date_debut" id="date_debut" value="{{ old('heure_debut') ?? $activite->date_debut }}" class="form-control @error('date_debut') is-invalid @enderror">
                                            @error('date_debut')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('date_fin') has-danger @enderror">
                                            <label for="date_fin" class="bmd-label-floating @error('date_fin') text-danger @enderror">Date de fin</label>
                                            <input type="date" name="date_fin" id="date_fin" value="{{ old('heure_fin') ?? $activite->date_fin }}" class="form-control @error('date_fin') is-invalid @enderror">
                                            @error('date_fin')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('heure_debut') has-danger @enderror">
                                            <label for="heure_debut" class="bmd-label-floating @error('heure_debut') text-danger @enderror">Heure de Debut</label>
                                            <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') ?? $activite->heure_debut }}" class="form-control @error('heure_debut') is-invalid @enderror">
                                            @error('heure_debut')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('lieu') has-danger @enderror">
                                            <label for="lieu" class="bmd-label-floating @error('lieu') text-danger @enderror">Lieu</label>
                                            <input type="text" name="lieu" id="lieu" value="{{ old('lieu') ?? $activite->lieu }}" class="form-control @error('lieu') is-invalid @enderror">
                                            @error('lieu')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('nom') has-danger @enderror">
                                            <label for="apostolat" class="form-label @error('apostolat') text-danger @enderror">Apostolats</label>
                                            <br>
                                            <select name="apostolat[]" id="apostolat" class="form-control @error('apostolat') is-invalid @enderror" multiple>
                                                @foreach ($apostolats as $apostolat)
                                                    <option value="{{ $apostolat->id }}">{{$apostolat->nom}}</option>
                                                @endforeach
                                            </select>
                                            @error('apostolat')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary pull-right">Modifier</button>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script type="text/javascript">
        $('button:submit').click(function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        });
    </script>
@endsection
