@extends('layouts.app')
@section('page_title') Apostolat @endsection
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
                                    <div class="col-md-8">
                                        <div class="form-group @error('nom_groupe') has-danger @enderror">
                                            <label for="nom_groupe" class="bmd-label-floating @error('nom_groupe') text-danger @enderror">Nom</label>
                                            <input type="text" name="nom_groupe" id="nom_groupe" value="{{ old('nom_groupe') ?? $groupe->nom_groupe }}" class="form-control @error('nom_groupe') is-invalid @enderror">
                                            @error('nom_groupe')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('paroisse') has-danger @enderror">
                                            <label for="paroisse" class="bmd-label-floating @error('paroisse') text-danger @enderror">Paroisse</label>
                                            <input type="text" name="paroisse" id="paroisse" value="{{ old('paroisse') ?? $groupe->paroisse }}" class="form-control @error('paroisse') is-invalid @enderror">
                                            @error('paroisse')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('jour_reunion') has-danger @enderror">
                                            <label for="jour_reunion" class="bmd-label-floating @error('jour_reunion') text-danger @enderror">Jour de Reunion</label>
                                            <select name="jour_reunion" id="jour_reunion" value="{{ old('jour_reunion') ?? $groupe->jour_reunion }}" class="form-control @error('jour_reunion') is-invalid @enderror">
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
                                        <div class="form-group @error('heure_reunion') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('heure_reunion') text-danger @enderror">Heure de Reunion</label>
                                            <input type="time" name="heure_reunion" id="heure_reunion" value="{{ old('heure_reunion') ?? $groupe->heure_reunion }}" class="form-control @error('heure_reunion') is-invalid @enderror">
                                            @error('heure_reunion')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class=" @error('nom') has-danger @enderror">
                                            <label for="zone_id" class="form-label @error('zone_id') text-danger @enderror">Zone</label>
                                            <br>
                                            <select name="zone_id" id="zone_id" value="{{ old('zone_id') ?? $groupe->sousZone->zone_id }}" class="form-control @error('zone_id') is-invalid @enderror">
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
                                        <div class=" @error('nom') has-danger @enderror">
                                            <label for="sous_zone_id" class="form-label @error('sous_zone_id') text-danger @enderror">Sous Zone</label>
                                            <br>
                                            <select name="sous_zone_id" id="sous_zone_id" value="{{ old('sous_zone_id') ?? $groupe->sous_zone_id }}" class="form-control @error('sous_zone_id') is-invalid @enderror">
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
