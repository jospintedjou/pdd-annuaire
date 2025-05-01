@extends('layouts.app')
@section('page_title') Pays @endsection
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
                                <h4 class="card-title">Modifier le pays</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('pays.update', $pays) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group @error('sous_zone_id') has-danger @enderror">
                                            <label for="sous_zone_id" class="bmd-label-floating0 @error('sous_zone_id') text-danger @enderror">Zone</label>
    
                                            <select name="sous_zone_id" id="sous_zone_id" value="{{ old('sous_zone_id') }}"
                                                class="selectpicker "
                                            data-width="100%"
                                                data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" 
                                                data-actions-box="true" data-live-search="true"
                                                data-header="Choisir la sous zone">
                                                <!-- <option value="" disabled selected>-- <i>Faire un choix</i> --</option> -->
                                                @foreach ($sousZones as $sousZone)
                                                    @if(isset($sousZone))
                                                        <option value="{{ $sousZone->id }}" {{$sousZone->id == $pays->sous_zone_id ? 'selected'  : '' }}>{{ $sousZone->nom }}</option>
                                                    @else
                                                        <option selected disabled>Aucune sous zone trouvée</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('sous_zone_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('continent') has-danger @enderror">
                                            <label for="continent" class="bmd-label-floating0 @error('continent') text-danger @enderror">Continent</label>
    
                                            <select name="continent" id="continent" value="{{ old('continent') }}"
                                                class="selectpicker "
                                            data-width="100%"
                                                data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round"
                                                data-actions-box="true" data-live-search="true"
                                                data-header="Choisir le continent">
                                                @foreach (\App\Constantes::CONTINENTS as $continent)
                                                    @if(isset($continent))
                                                        <option value="{{ $continent }}" {{$continent == $pays->continent ? 'selected'  : '' }}>{{ $continent }}</option>
                                                    @else
                                                        <option  selected disabled>Aucun continent trouvé</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('continent')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $pays->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
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
