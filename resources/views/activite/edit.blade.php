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

                                        <div class="form-group @error('categorie_activite_id') has-danger @enderror">
                                            <label for="categorie_activite_id" class="bmd-label-floating @error('categorie_activite_id') text-danger @enderror">Categorie</label>

                                            <select name="categorie_activite_id" id="categorie_activite_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir la catégorie">
                                                @foreach ($categories as $categorie_activite)
                                                    @if(isset($categorie_activite))
                                                        <option value="{{ $categorie_activite->id }}"  {{$categorie_activite == $activite->categorie_activite ? 'selected'  : '' }}>{{ $categorie_activite->nom }}</option>
                                                    @else
                                                        <option  selected disabled>Aucune categorie d'activite trouvée</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('categorie_activite')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('zone_id') has-danger @enderror">
                                            <label for="zone_id" class="bmd-label-floating @error('zone_id') text-danger @enderror">Zone</label>

                                            <select name="zone_id" id="zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir la zone">
                                                @foreach ($zones as $zone)
                                                    @if(isset($zone))
                                                        <option value="{{ $zone->id }}"  {{$zone == $activite->zone ? 'selected'  : '' }}>{{ $zone->nom }}</option>
                                                    @else
                                                        <option  selected disabled>Aucune zone trouvée</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('zone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('sous_zone_id') has-danger @enderror">
                                            <label for="sous_zone_id" class="bmd-label-floating @error('sous_zone_id') text-danger @enderror">Sous Zone</label>

                                            <select name="sous_zone_id" id="sous_zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir la sous zone">
                                                @foreach ($sous_zones as $sous_zone)
                                                    @if(isset($sous_zone))
                                                        <option value="{{ $sous_zone->id }}"  {{$sous_zone == $sous_zone->sous_zone ? 'selected'  : '' }}>{{ $sous_zone->nom }}</option>
                                                    @else
                                                        <option  selected disabled>Aucune sous zone trouvée</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('sous_zone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>


                                        <div class="form-group @error('groupe_id') has-danger @enderror">
                                            <label for="groupe_id" class="bmd-label-floating @error('groupe_id') text-danger @enderror">Groupe</label>

                                            <select name="groupe_id" id="groupe_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                                @foreach ($groupes as $groupe)
                                                    @if(isset($groupe))
                                                        <option value="{{ $groupe->id }}"  {{$groupe == $activite->groupe ? 'selected'  : '' }}>{{ $groupe->nom_groupe }}</option>
                                                    @else
                                                        <option  selected disabled>Aucun groupe trouvé</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('groupe')
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
                                            <select name="apostolat[]" id="apostolat" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                            data-style2="btn btn-primary btn-round" data-header="Choisir les apostolats" multiple>
                                                @foreach ($apostolats as $apostolat)
                                                @if(isset($apostolat))
                                                        <option value="{{ $apostolat->id }}" {{in_array($apostolat->id, $activite->id_apostolats()) ? 'selected': ' '}}>
                                                            {{$apostolat->nom}}
                                                        </option>
                                                    @else
                                                        <option  selected disabled>Aucun apostolat trouvé</option>
                                                    @endif
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
