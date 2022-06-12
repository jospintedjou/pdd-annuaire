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
                                    <div class=" @error('nom') has-danger @enderror">
                                        <label for="zone_id" class="form-label @error('zone_id') text-danger @enderror">Zone</label>
                                        <br>
                                        <select name="zone_id" id="zone_id" value="{{ old('zone_id') }}" class="form-control @error('zone_id') is-invalid @enderror">
                                            <option value="" disabled selected>-- <i>Choisir dans la liste</i></option>
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
