@extends('layouts.app')
@section('page_title') Utilisateur @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('users.update', [$user]) !!}">
                @csrf
                @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Modifier un utilisateur</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="form-group col-md-5 @error('nom') has-danger @enderror">
                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                        <input type="text" name="nom" id="nom" value="{{ $user->nom }}" class="form-control @error('nom') is-invalid @enderror">
                                        @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 @error('prenom') has-danger @enderror">
                                        <label for="prenom" class="bmd-label-floating @error('prenom') text-danger @enderror">Prenom</label>
                                        <input type="text" name="prenom" id="prenom" value="{{ $user->prenom }}" class="form-control @error('prenom') is-invalid @enderror">
                                        @error('prenom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 @error('sexe') has-danger @enderror">
                                        <label for="sexe" class="bmd-label-floating @error('sexe') text-danger @enderror">Sexe</label>
                                        <select name="sexe" id="sexe" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le sexe">
                                            <option value="{{\App\Constantes::SEXE_MASCULIN}}" {{$user->sexe == \App\Constantes::SEXE_MASCULIN ? "selected" : "" }}>Homme</option>
                                            <option value="{{\App\Constantes::SEXE_FEMININ}}" {{$user->sexe == \App\Constantes::SEXE_FEMININ ? "selected" : "" }}>Femme</option>
                                        </select>
                                        @error('sexe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-5 @error('adresse') has-danger @enderror">
                                        <label for="adresse" class="bmd-label-floating @error('adresse') text-danger @enderror">Adresse</label>
                                        <input type="text" name="adresse" id="adresse" value="{{ $user->adresse }}" class="form-control @error('adresse') is-invalid @enderror">
                                        @error('adresse')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 @error('telephone1') has-danger @enderror">
                                        <label for="telephone1" class="bmd-label-floating @error('telephone1') text-danger @enderror">Telephone 1</label>
                                        <input type="text" name="telephone1" id="telephone1" value="{{ $user->telephone1 }}" class="form-control @error('telephone1') is-invalid @enderror">
                                        @error('telephone1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 @error('telephone2') has-danger @enderror">
                                        <label for="telephone2" class="bmd-label-floating @error('telephone2') text-danger @enderror">Telephone 2</label>
                                        <input type="text" name="telephone2" id="telephone2" value="{{ $user->telephone2 }}" class="form-control @error('telephone2') is-invalid @enderror">
                                        @error('telephone2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-5 @error('email') has-danger @enderror">
                                        <label for="email" class="bmd-label-floating @error('email') text-danger @enderror">Email</label>
                                        <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 @error('quartier') has-danger @enderror">
                                        <label for="quartier" class="bmd-label-floating @error('quartier') text-danger @enderror">Quartier</label>
                                        <input type="text" name="quartier" id="quartier" value="{{ $user->quartier }}" class="form-control @error('quartier') is-invalid @enderror">
                                        @error('quartier')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 @error('profession') has-danger @enderror">
                                        <label for="profession" class="bmd-label-floating @error('profession') text-danger @enderror">Profession</label>
                                        <input type="text" name="profession" id="profession" value="{{ $user->profession }}" class="form-control @error('profession') is-invalid @enderror">
                                        @error('profession')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-4 @error('categorie_sociale') has-danger @enderror">
                                        <label for="categorie_sociale" class="bmd-label-floating col-12 @error('categorie_sociale') text-danger @enderror">Categorie sociale</label>
                                        <select name="categorie_sociale" id="categorie_sociale" class="selectpicker  col-12" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir">
                                            @foreach (\App\Constantes::CATEGORIE_SOCIALES as $categorie_sociale)
                                                @if(isset($categorie_sociale))
                                                    <option value="{{ $categorie_sociale }}" {{ $user->categorie_sociale == $categorie_sociale ? "selected" : ""}}>{{$categorie_sociale}}</option>
                                                @else
                                                    <option  selected disabled>Aucune categorie sociale trouvée</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('categorie_sociale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <?php //dd($user->apostolats()->pluck('apostolat_id')->toArray()); ?>
                                    <div class="form-group col-md-4 @error('apostolat_id') has-danger @enderror">
                                        <label for="apostolat_id" class="bmd-label-floating  col-12 @error('apostolat_id') text-danger @enderror">Apostolat</label>
                                        <select name="apostolat_id[]" id="apostolat_id" class="selectpicker  col-12" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir l'apostolat" multiple>
                                            @foreach ($apostolats as $apostolat)
                                                @if(isset($apostolat))
                                                    <option value="{{ $apostolat->id }}" {{  in_array($apostolat->id, $user->apostolats()->pluck('apostolat_id')->toArray()) ? "selected" : ""}}>{{$apostolat->nom}}</option>
                                                @else
                                                    <option selected disabled>Aucun apostolat trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('apostolat_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 @error('niveau_engagement_id') has-danger @enderror">
                                        <label for="niveau_engagement_id" class="bmd-label-floating  col-12 @error('niveau_engagement_id') text-danger @enderror">Niveau d'engagement</label>
                                        <select name="niveau_engagement_id" id="niveau_engagement_id" class="selectpicker col-12" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir">
                                            @foreach ($niveau_engagements as $niveau_engagement)
                                                @if(isset($niveau_engagement))
                                                    <option value="{{ $niveau_engagement->id }}" {{ $user->niveauEngagement()->first()->id == $niveau_engagement->id ? "selected" : ""}}>{{$niveau_engagement->nom}}</option>
                                                @else
                                                    <option  selected disabled>Aucun niveau d'engagement trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('niveau_engagement_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">

                                    <div class="form-group col-md-4 @error('groupe_id') has-danger @enderror">
                                        <label for="groupe_id" class="bmd-label-floating @error('groupe_id') text-danger @enderror">Groupe</label>

                                        <select name="groupe_id" id="groupe_id" class="selectpicker col-12" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                            @foreach ($groupes as $groupe)
                                                @if(isset($groupe))
                                                    <option value="{{ $groupe->id }}" {{ $user->groupes()->first()->id == $groupe->id ? "selected" : ""}}>{{$groupe->nom_groupe}}</option>
                                                @else
                                                    <option  selected disabled>Aucun groupe trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('groupe_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 @error('password') has-danger @enderror">
                                        <label for="password" class="bmd-label-floating @error('password') text-danger @enderror">Mot de passe</label>
                                        <input type="password" name="password" id="password" value="" class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 @error('etat') has-danger @enderror">
                                        <label for="etat" class="bmd-label-floating @error('etat') text-danger @enderror">Etat</label>

                                        <select name="etat" id="etat" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                            <option value="{{ \App\Constantes::ETAT_ACTIF }}" {{ $user->etat == \App\Constantes::ETAT_ACTIF ? "selected" : ""}}>Activé</option>
                                            <option value="{{ \App\Constantes::ETAT_INACTIF }}" {{ $user->etat == \App\Constantes::ETAT_INACTIF ? "selected" : ""}}>Désactivé</option>
                                        </select>
                                        @error('etat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-4 @error('date_entree') has-danger @enderror">
                                        <label for="date_entree" class="bmd-label-floating @error('date_entree') text-danger @enderror">Date d'entrée</label>
                                        <input type="date" name="date_entree" id="date_entree" value="{{ $user->date_entree }}" class="form-control @error('date_entree') is-invalid @enderror">

                                        @error('date_entree')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">

                                    <div class="form-group col-md-6 @error('responsabilite_groupe_id') has-danger @enderror">
                                        <label for="responsabilite_groupe_id" class="bmd-label-floating @error('responsabilite_groupe_id') text-danger @enderror">Responsable de Groupe ?</label>

                                        <select name="responsabilite_groupe_id" id="responsabilite_groupe_id" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                            <option value="">Non</option>
                                            @foreach ($groupes as $groupe)
                                                @if(isset($groupe))
                                                    <option value="{{ $groupe->id }}"
                                                         {{$user->responsableGroupes()->where(['actif'=>\App\Constantes::ETAT_ACTIF, 'groupe_id'=>$groupe->id])->exists() ? "selected" : ""}}>
                                                        {{$groupe->nom_groupe}}
                                                    </option>
                                                @else
                                                    <option  selected disabled>Aucun groupe trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('responsabilite_groupe_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 @error('responsabilite_groupe') has-danger @enderror">
                                        <label for="responsabilite" class="bmd-label-floating @error('responsabilite_groupe') text-danger @enderror">Responsabilité dans le groupe</label>
                                        <select name="responsabilite_groupe" id="responsabilite_groupe" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                            <option value="">Aucune</option>
                                            @foreach (\App\Constantes::RESPONSABILITES_GROUPE as $responsabilite)
                                                @if(isset($responsabilite))
                                                    <?php $userResponsabiliteGroupe = $user->responsableGroupes()->where(['actif'=>\App\Constantes::ETAT_ACTIF])->first() ?>
                                                    <option value="{{ $responsabilite }}"
                                                         {{!empty($userResponsabiliteGroupe) && $userResponsabiliteGroupe->pivot->nom_responsabilite == $responsabilite ? "selected" : ""}}>
                                                        {{$responsabilite}}</option>
                                                @else
                                                    <option  selected disabled>Aucune responsabilité trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('groupe_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-6 @error('responsable_sous_zone_id') has-danger @enderror">
                                        <label for="responsable_sous_zone_id" class="bmd-label-floating @error('responsable_sous_zone_id') text-danger @enderror">Responsable de Sous-zone?</label>
                                        <select name="responsable_sous_zone_id" id="responsable_sous_zone_id" class="selectpicker col-md-7" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le sous-zone">
                                            <option value="">Non</option>
                                            @foreach ($sous_zones as $sous_zone)
                                                @if(isset($sous_zone))
                                                    <option value="{{ $sous_zone->id }}"
                                                            {{$user->responsableSousZones()->where(['actif'=>\App\Constantes::ETAT_ACTIF, 'sous_zone_id'=>$sous_zone->id])->exists() ? "selected" : ""}}>
                                                        {{$sous_zone->nom}}
                                                    </option>
                                                @else
                                                    <option selected disabled>Aucune sous zone trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('responsable_sous_zone_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 @error('responsabilite_sous_zone') has-danger @enderror">
                                        <label for="responsabilite_sous_zone" class="bmd-label-floating @error('responsabilite_sous_zone') text-danger @enderror">Responsabilité dans la sous-zone</label>
                                        <?php $userResponsabiliteSousZone = $user->responsableSousZones()->where(['actif'=>\App\Constantes::ETAT_ACTIF])->first(); ?>
                                        <select name="responsabilite_sous_zone" id="responsabilite_sous_zone" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                            <option value="">Aucune</option>
                                            @foreach (\App\Constantes::RESPONSABILITES_SOUS_ZONE as $responsabilite)
                                                @if(isset($responsabilite))
                                                    <?php $userResponsabiliteSousZone = $user->responsableSousZones()->where(['actif'=>\App\Constantes::ETAT_ACTIF])->first(); ?>
                                                    <option value="{{ $responsabilite }}"
                                                            {{!empty($userResponsabiliteSousZone) && $userResponsabiliteSousZone->pivot->nom_responsabilite == $responsabilite ? "selected" : ""}}>
                                                        {{$responsabilite}}</option>
                                                @else
                                                    <option selected disabled>Aucune responsabilité trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('responsabilite_sous_zone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                                <div class="row">
                                    <div class="form-group col-md-6 @error('responsable_zone_id') has-danger @enderror">
                                        <label for="responsable_zone_id" class="bmd-label-floating @error('responsable_zone_id') text-danger @enderror">Responsable Zone?</label>

                                        <select name="responsable_zone_id" id="responsable_zone_id" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la zone">
                                            <option value="">Non</option>
                                            @foreach ($zones as $zone)
                                                @if(isset($zone))
                                                    <option value="{{ $zone->id }}"
                                                        {{$user->responsableZones()->where(['actif'=>\App\Constantes::ETAT_ACTIF, 'zone_id'=>$zone->id])->exists() ? "selected" : ""}}>
                                                    {{$zone->nom}}</option>
                                                @else
                                                    <option selected disabled>Aucune sous zone trouvé</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('responsable_zone_id')
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 @error('responsabilite_zone') has-danger @enderror">
                                        <label for="responsabilite_zone" class="bmd-label-floating @error('responsabilite_zone') text-danger @enderror">Responsabilité dans la zone</label>

                                        <select name="responsabilite_zone" id="responsabilite_zone" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                            <option value="">Aucune</option>

                                            @foreach (\App\Constantes::RESPONSABILITES_ZONE as $responsabilite)
                                                @if(isset($responsabilite))
                                                    <option value="{{ $responsabilite }}"
                                                            <?php $userResponsabiliteZone = $user->responsableZones()->where(['actif'=>\App\Constantes::ETAT_ACTIF])->first(); ?>
                                                    {{!empty($userResponsabiliteZone) && $userResponsabiliteZone->pivot->nom_responsabilite == $responsabilite ? "selected" : ""}}>
                                                        {{$responsabilite}}</option>
                                                @else
                                                    <option  selected disabled>Aucune responsabilité trouvée</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('responsabilite_zone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div><!-- row -->

                            </div><!-- container -->

                            <div class="clearfix"></div>
                            <div class="row text-center">
                                <button id="btn-send" type="submit" class="btn btn-primary wd-100 col-md-3">Envoyer</button>
                            </div><!-- row -->

                        </div>
                    </div>

                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/custom.js')}}"></script>
    <script type="text/javascript">
        $('button:submit').click(function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        });
    </script>
@endsection




