@extends('layouts.app')
@section('page_title') Responsables {{$groupe->nom_groupe}} @endsection
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
                                <h4 class="card-title">Responsables {{$groupe->nom_groupe}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('responsable_groupes.update', $groupe) !!}">
                                @csrf
                                @method('PUT')
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @error('zone_id') has-danger @enderror">
                                                <label for="zone_id" class="bmd-label-floating @error('zone_id') text-danger @enderror">Zone</label>

                                                <select name="zone_id" id="zone_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la zone" disabled>

                                                       <option value="{{$groupe->sousZone()->first()->zone()->first()->id}}" selected>{{$groupe->sousZone()->first()->zone()->first()->nom}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group @error('groupe_id') has-danger @enderror">
                                                <label for="groupe_id" class="bmd-label-floating @error('groupe_id') text-danger @enderror">Groupe</label>

                                                <select name="groupe_id" id="groupe_id" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                                    <option value="{{$groupe->id}}" selected>{{$groupe->nom_groupe}}</option>
                                                </select>
                                                @error('groupe_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @foreach(\App\Constantes::RESPONSABILITES_GROUPE as $responsabiliteGroupe)
                                        <div class="row">
                                            <div class="form-group col-md-6 @error('responsabilite_groupes') has-danger @enderror">
                                                <label for="responsabilite_groupes" class="bmd-label-floating @error('responsabilite_groupes') text-danger @enderror">Responsabilité dans le groupe</label>
                                                <select name="responsabilite_groupes[{{ $loop->index }}]" id="" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                                    <option value="">Aucune</option>

                                                        <option value="{{ $responsabiliteGroupe }}"
                                                            {{$groupe->responsableGroupes()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'nom_responsabilite' => $responsabiliteGroupe])->exists() ? "selected" : ""}}>
                                                            {{$responsabiliteGroupe}}
                                                        </option>
                                                </select>
                                                @error('responsabilite_groupes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6 @error('responsable_groupe_id') has-danger @enderror">
                                                <label for="responsable_groupe_ids" class="bmd-label-floating @error('responsable_groupe_id') text-danger @enderror">Responsable de Groupe ?</label>
                                                <?php //dd($users); ?>
                                                <select name="responsable_groupe_ids[{{ $loop->index }}]" id="" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                        data-style2="btn btn-primary btn-round" data-header="Choisir le responsable">
                                                    <option value="">Aucun</option>

                                                    @foreach ($users as $user)
                                                        @if(isset($user))
                                                            <option value="{{ $user->id }}"
                                                                    {{$groupe->responsableGroupes()->where(['actif' => \App\Constantes::ETAT_ACTIF, 'nom_responsabilite' => $responsabiliteGroupe, 'user_id' => $user->id])->exists() ? "selected" : ""}}>
                                                                {{$user->nom}} {{$user->prenom}}
                                                            </option>
                                                        @else
                                                            <option  selected disabled>Aucun membre trouvé</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('responsable_groupe_id')
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
