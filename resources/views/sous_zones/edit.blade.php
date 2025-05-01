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
                                <h4 class="card-title">Modifier le sous zone</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('sous_zones.update', $sous_zone) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('zone_id') has-danger @enderror">
                                            <label for="zone_id" class="bmd-label-floating0 @error('zone_id') text-danger @enderror">Zone</label>

                                            <select name="zone_id" id="zone_id" 
                                                class="selectpicker col-md-12 form-control" value="{{ old('zone_id') }}"
                                                data-actions-box="true" data-live-search="true"
                                                data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" 
                                                data-header="Choisir la zone">
                                                @foreach ($zones as $zone)
                                                    @if(isset($zone))
                                                        <option value="{{ $zone->id }}"  {{$zone->id == $sous_zone->zone_id ? 'selected'  : '' }}>{{ $zone->nom }}</option>
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
                                    <div class="col-md-6">
                                        <div class="form-group @error('quartier') has-danger @enderror">
                                            <label for="quartier" class="bmd-label-floating @error('quartier') text-danger @enderror">Quartier</label>
                                            <input type="text" name="quartier" id="quartier" value="{{ old('quartier') ?? $sous_zone->quartier }}" class="form-control @error('quartier') is-invalid @enderror">
                                            @error('quartier')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $sous_zone->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('has_country') has-danger @enderror">
                                            <label for="has_country" class="bmd-label-floating0 @error('has_country') text-danger @enderror">A des pays ?</label>
    
                                            <select name="has_country" id="has_country" value="{{ old('has_country') }}"
                                                class="selectpicker col-md-12 form-control"
                                                data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" 
                                                data-actions-box="true" data-live-search="true"
                                                data-header="Choisir la zone">
                                                    <option {{ $sous_zone->has_country == \App\Constantes::ETAT_INACTIF ? 'selected' : '' }}  }} value="{{ \App\Constantes::ETAT_INACTIF }}">Non</option>
                                                    <option {{ $sous_zone->has_country == \App\Constantes::ETAT_ACTIF ? 'selected' : ''  }} value="{{ \App\Constantes::ETAT_ACTIF }}">Oui</option>
                                            </select>
                                            @error('has_country')
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
