@extends('layouts.app')
@section('page_title') Responsables sous-zone {{$sous_zone->nom}} @endsection
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
                                <h4 class="card-title">Responsables {{$sous_zone->nom}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('responsable_sous_zones.update', $sous_zone) !!}">
                                @csrf
                                @method('PUT')
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @error('zone_id') has-danger @enderror">
                                                <label for="zone_id" class="bmd-label-floating0 col-md-10 @error('zone_id') text-danger @enderror">Zone</label>

                                                <select name="zone_id" id="zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-actions-box="true" data-live-search="true"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir la zone" disabled>
                                                     <option value="{{$sous_zone->zone()->first()->id}}" selected>{{$sous_zone->zone()->first()->nom}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group @error('sous_zone_id') has-danger @enderror">
                                                <label for="zone_id" class="bmd-label-floating0 col-md-10 @error('sous_zone_id') text-danger @enderror">Sous-Zone</label>

                                                <select name="sous_zone_id" id="sous_zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-actions-box="true" data-live-search="true"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la sous-zone">

                                                       <option value="{{$sous_zone->id}}" selected>{{$sous_zone->nom}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                    @foreach($responsabilites as $responsabilite)
                                        <div class="col-md-6">
                                            <div class="form-group @error('responsabilite_sous_zones') has-danger @enderror">
                                                <label for="responsabilite_sous_zones" class="bmd-label-floating0 col-md-8 @error('responsabilite_sous_zones') text-danger @enderror">{{$responsabilite->nom}}</label>

                                                <select name="responsabilite_sous_zones[{{$responsabilite->id}}]" id="" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                        data-actions-box="true" data-live-search="true"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir le responsable">
                                                    <option value="">Aucun</option>

                                                    @foreach ($users as $user)
                                                        @if(isset($user))
                                                            <option value="{{ $user->id }}"
                                                                    {{$sous_zone->responsableSousZones()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'responsabilite_id' => $responsabilite->id, 'user_id' => $user->id])->exists() ? "selected" : ""}}>
                                                                {{$user->nom}} {{$user->prenom}}
                                                            </option>
                                                        @else
                                                            <option  selected disabled>Aucun membre trouvé</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('responsabilite_sous_zones')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div><!-- col-md-6 -->
                                    @endforeach
                                    </div><!-- row -->
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
