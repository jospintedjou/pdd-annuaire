@extends('layouts.app')
@section('page_title') Groupe @endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Modifier le groupe</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('groupes.update', $groupe) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group @error('zone_id') has-danger @enderror">
                                            <label for="zone_id" class="bmd-label-floating0 @error('zone_id') text-danger @enderror">Zone</label>

                                            <select name="zone_id" id="select-zone" data-url="{{route('get_sous_zone')}}" 
                                                class="selectpicker col-md-12 form-control zone" 
                                                data-size="auto" data-style="select-with-transition"
                                                data-actions-box="true" data-live-search="true"
                                                data-style2="btn btn-primary btn-round" 
                                                data-header="Choisir la zone">
                                                @foreach ($zones as $zone)
                                                    @if(isset($zone))
                                                        <option value="{{ $zone->id }}"  {{$zone == $groupe->sousZone->zone ? 'selected'  : '' }}>{{ $zone->nom }}</option>
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
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('sous_zone_id') has-danger @enderror">
                                            <label for="sous_zone_id" class="bmd-label-floating0 @error('sous_zone_id') text-danger @enderror">Sous-zone
                                                </label>

                                                <select name="sous_zone_id" id="select-sous-zone" 
                                                    class="selectpicker col-md-12 form-control sous-zone" data-size="auto" 
                                                    data-style="select-with-transition"
                                                    data-actions-box="true" data-live-search="true"
                                                    data-url="{{route('get_pays')}}"
                                                    data-style2="btn btn-primary btn-round" 
                                                    data-header="Choisir la sous zone">
                                                    @foreach ($sous_zones as $sous_zone)
                                                        @if(isset($sous_zone))
                                                            <option value="{{ $sous_zone->id }}" data-has_country="{{$sous_zone->has_country}}"
                                                                 {{$sous_zone->id == $groupe?->sousZone?->id ? 'selected'  : '' }}>{{ $sous_zone->nom }}</option>
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
                                    </div>
                                    <div class="col-md-4 select-pays-container" style="@if($groupe->sousZone?->has_country == 0) display: none; @endif">
                                        <div class="form-group @error('pays') has-danger @enderror">
                                            <label for="pays" class="bmd-label-floating0 @error('pays') text-danger @enderror">Pays</label>
    
                                            <select name="pays_id" id="select-pays" 
                                                class="selectpicker col-md-12 form-control pays"
                                                data-actions-box="true" data-live-search="true"
                                                data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" 
                                                data-header="Choisir le pays">
                                                <option value="" disabled @if($groupe->sousZone?->has_country == 0) selected @endif>Faire un choix</option>
                                                @if($paysArr && $groupe->sousZone?->has_country)
                                                    @foreach ($paysArr as $pays)
                                                        <option value="{{ $pays->id }}" {{$pays->id == $groupe?->pays?->id ? 'selected'  : '' }}>{{ $pays->nom }}</option> 
                                                    @endforeach
                                                @else
                                                    <option selected disabled>Aucun pays trouvée</option>
                                                @endif
                                            </select>
                                            @error('pays')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('nom_groupe') has-danger @enderror">
                                            <label for="nom_groupe" class="bmd-label-floating @error('nom_groupe') text-danger @enderror">Nom</label>
                                            <input type="text" name="nom_groupe" id="nom_groupe" 
                                                value="{{ old('nom_groupe') ?? $groupe->nom_groupe }}" 
                                                class="form-control @error('nom_groupe') is-invalid @enderror">
                                            @error('nom_groupe')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('paroisse') has-danger @enderror">
                                            <label for="paroisse" class="bmd-label-floating @error('paroisse') text-danger @enderror">Paroisse</label>
                                            <input type="text" name="paroisse" id="paroisse" 
                                                value="{{ old('paroisse') ?? $groupe->paroisse }}" 
                                                class="form-control @error('paroisse') is-invalid @enderror">
                                            @error('paroisse')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('jour_reunion') has-danger @enderror">
                                            <label for="jour_reunion" class="bmd-label-floating0 @error('jour_reunion') text-danger @enderror">Jour de Reunion</label>
                                            <select name="jour_reunion" id="jour_reunion" value="{{ old('jour_reunion') ?? $groupe->jour_reunion }}" 
                                                class="selectpicker col-md-12 form-control" data-size="auto" 
                                                data-style="select-with-transition"
                                                data-live-search="true" data-actions-box="true"
                                                data-style2="btn btn-primary btn-round" 
                                                data-header="Choisir le jour de réunion">
                                                <option value="Lundi">Lundi</option>
                                                <option value="Mardi">Mardi</option>
                                                <option value="Mercredi">Mercredi</option>
                                                <option value="Jeudi">Jeudi</option>
                                                <option value="Vendredi">Vendredi</option>
                                                <option value="Samedi">Samedi</option>
                                                <option value="Dimanche">Dimanche</option>
                                            </select>
                                            @error('jour_reunion')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <br>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('heure_reunion') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('heure_reunion') text-danger @enderror">Heure de Reunion</label>
                                            <input type="time" name="heure_reunion" id="heure_reunion" value="{{ old('heure_reunion') ?? $groupe->heure_reunion }}" class="form-control @error('heure_reunion') is-invalid @enderror">
                                            @error('heure_reunion')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="btn-send" type="submit" 
                                                class="btn btn-primary"><i class="material-icons">send</i> Modifier
                                            </button>
                                        </div>
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
