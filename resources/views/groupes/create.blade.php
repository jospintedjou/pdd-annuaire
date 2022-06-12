@extends('layouts.app')
@section('page_title') Apostolat @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('groupes.store') !!}" id="groupe_form">
                @csrf
                <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Ajouter un groupe</h4>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group @error('zone') has-danger @enderror">
                                        <label for="zone" class="bmd-label-floating @error('zone') text-danger @enderror">Zone</label>

                                        <select name="zone_id" id="zone" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la catégorie">
                                            @foreach ($zones as $zone)
                                                @if(isset($zone))
                                                    <option value="{{ $zone->id }}">{{ $zone->nom }}</option>
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

                                    <div class="form-group @error('sous_zone') has-danger @enderror">
                                        <label for="sous_zone" class="bmd-label-floating @error('sous_zone') text-danger @enderror">Zone</label>

                                        <select name="sous_zone_id" id="sous_zone" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la catégorie">
                                            @foreach ($sous_zones as $sous_zone)
                                                @if(isset($sous_zone))
                                                    <option value="{{ $sous_zone->id }}">{{ $sous_zone->nom }}</option>
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

                                    <div class="form-group @error('nom_groupe') has-danger @enderror">
                                        <label for="nom_groupe" class="bmd-label-floating @error('nom_groupe') text-danger @enderror">Nom</label>
                                        <input type="text" name="nom_groupe" id="nom_groupe" value="{{ old('nom_groupe') }}" class="form-control @error('nom_groupe') is-invalid @enderror">
                                        @error('nom_groupe')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('paroisse') has-danger @enderror">
                                        <label for="paroisse" class="bmd-label-floating @error('paroisse') text-danger @enderror">Paroisse</label>
                                        <input type="text" name="paroisse" id="paroisse" value="{{ old('paroisse') }}" class="form-control @error('paroisse') is-invalid @enderror">
                                        @error('paroisse')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="@error('jour_reunion') has-danger @enderror">
                                        <label for="jour_reunion" class="bmd-label-floating @error('jour_reunion') text-danger @enderror">Jour de Reunion</label>
                                        <select name="jour_reunion" id="jour_reunion" value="{{ old('jour_reunion') }}"  class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                            data-style2="btn btn-primary btn-round" data-header="Choisir le jour">
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
                                        <input type="time" name="heure_reunion" id="heure_reunion" value="{{ old('heure_reunion') }}" class="form-control @error('heure_reunion') is-invalid @enderror">
                                        @error('heure_reunion')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <button id="btn-send" type="submit" class="btn btn-primary">Envoyer</button>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
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
