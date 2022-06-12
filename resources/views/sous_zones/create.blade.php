@extends('layouts.app')
@section('page_title') Sous Zone @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('sous_zones.store') !!}">
                @csrf
                <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Ajouter une sous zone</h4>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">

                                    <div class="form-group @error('zone') has-danger @enderror">
                                        <label for="zone" class="bmd-label-floating @error('zone') text-danger @enderror">Zone</label>

                                        <select name="zone_id" id="zone" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la zone">
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

                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <label for="quartier" class="bmd-label-floating @error('quartier') text-danger @enderror">Quartier</label>
                                        <input type="text" name="quartier" id="quartier" value="{{ old('quartier') }}" class="form-control @error('quartier') is-invalid @enderror">
                                        @error('quartier')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror">
                                        @error('nom')
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
