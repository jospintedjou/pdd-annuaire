@extends('layouts.app')
@section('page_title') Responsables {{$zone->nom}}  @endsection
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
                                                <label for="zone_id" class="bmd-label-floating0 col-md-10 @error('zone_id') text-danger @enderror">Zone</label>

                                                <select name="zone_id" id="zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                        data-actions-box="true" data-live-search="true"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la zone">
                                                    <option value="{{$zone->id}}" selected>{{$zone->nom}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                    @foreach($responsabilites as $responsabilite)
                                        <div class="col-md-6">
                                            <div class="form-group @error('responsabilite_zones') has-danger @enderror">
                                                <label for="responsabilite_zones" class="bmd-label-floating0 col-md-8 @error('responsabilite_zones') text-danger @enderror">{{$responsabilite->nom}}</label>

                                                <select name="responsabilite_zones[{{$responsabilite->id}}]" id="" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                        data-actions-box="true" data-live-search="true"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir le responsable">
                                                    <option value="">Aucun</option>

                                                    @foreach ($users as $user)
                                                        @if(isset($user))
                                                            <option value="{{ $user->id }}"
                                                                    {{$zone->responsableZones()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'responsabilite_id' => $responsabilite->id, 'user_id' => $user->id])->exists() ? "selected" : ""}}>
                                                                {{$user->nom}} {{$user->prenom}}
                                                            </option>
                                                        @else
                                                            <option  selected disabled>Aucun membre trouvé</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('responsabilite_zones')
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
