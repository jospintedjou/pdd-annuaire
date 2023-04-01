@extends('layouts.app')
@section('page_title') Responsables zone {{$zone->nom}} @endsection
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
                                <h4 class="card-title">Responsables {{$zone->nom}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('responsable_zones.update', $zone) !!}">
                                @csrf
                                @method('PUT')
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group @error('zone_id') has-danger @enderror">
                                                <label for="zone_id" class="bmd-label-floating @error('zone_id') text-danger @enderror">Zone</label>

                                                <select name="zone_id" id="zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la zone">

                                                       <option value="{{$zone->id}}" selected>{{$zone->nom}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach(\App\Constantes::RESPONSABILITES_ZONE as $responsabilite_zone)
                                        <div class="row">
                                            <div class="form-group col-md-6 @error('responsabilite_zones') has-danger @enderror">
                                                <label for="responsabilite_zones" class="bmd-label-floating @error('responsabilite_zones') text-danger @enderror">Responsabilité dans la zone</label>
                                                <select name="responsabilite_zones[{{ $loop->index }}]" id="" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                                    <option value="">Aucune</option>

                                                        <option value="{{ $responsabilite_zone }}"
                                                            {{$zone->responsableZones()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'nom_responsabilite' => $responsabilite_zone])->exists() ? "selected" : ""}}>
                                                            {{$responsabilite_zone}}
                                                        </option>
                                                </select>
                                                @error('responsabilite_zones')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6 @error('responsable_zone_ids') has-danger @enderror">
                                                <label for="responsable_groupe_ids" class="bmd-label-floating @error('responsable_zone_ids') text-danger @enderror">Responsable de zone ?</label>
                                                <select name="responsable_zone_ids[{{ $loop->index }}]" id="" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir le responsable">
                                                    <option value="">Aucun</option>

                                                    @foreach ($users as $user)
                                                        @if(isset($user))
                                                            <option value="{{ $user->id }}"
                                                                    {{$zone->responsableZones()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'nom_responsabilite' => $responsabilite_zone, 'user_id' => $user->id])->exists() ? "selected" : ""}}>
                                                                {{$user->nom}} {{$user->prenom}}
                                                            </option>
                                                        @else
                                                            <option  selected disabled>Aucun membre trouvé</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('responsable_zone_ids')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div><!-- row -->
                                    @endforeach
                                    <button type="submit" class="btn btn-primary pull-right">Modifier</button>
                                    <div class="clearfix"></div>
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
