@extends('layouts.app')
@section('page_title') Utilisateur @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @if ($message = Session::get('success'))
                {{-- ── Import result banner ── --}}
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="material-icons me-2" style="font-size:20px;vertical-align:middle">check_circle</i>
                    <span>{{ $message }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>

                @php
                    $duplicatedRows = Session::get('duplicatedRows', []);
                    $skippedRows    = Session::get('skippedRows', []);
                @endphp

                @if(count($duplicatedRows) > 0 || count($skippedRows) > 0)
                <div class="row g-3 mb-3">

                    {{-- ── Doublons card ── --}}
                    @if(count($duplicatedRows) > 0)
                    <div class="{{ count($skippedRows) > 0 ? 'col-md-6' : 'col-12' }}">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between py-2"
                                 style="background:#fff3cd;border-left:4px solid #ffc107;">
                                <span class="fw-semibold text-warning-emphasis">
                                    <i class="material-icons align-middle me-1" style="font-size:18px">content_copy</i>
                                    Déjà existants
                                </span>
                                <span class="badge rounded-pill" style="background:#ffc107;color:#333;">
                                    {{ count($duplicatedRows) }}
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div style="max-height:220px;overflow-y:auto;">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr><th style="width:40px">#</th><th>Nom</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($duplicatedRows as $i => $name)
                                            <tr>
                                                <td class="text-muted small">{{ $i + 1 }}</td>
                                                <td>{{ $name }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── Lignes ignorées card ── --}}
                    @if(count($skippedRows) > 0)
                    <div class="{{ count($duplicatedRows) > 0 ? 'col-md-6' : 'col-12' }}">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between py-2"
                                 style="background:#f8d7da;border-left:4px solid #dc3545;">
                                <span class="fw-semibold text-danger-emphasis">
                                    <i class="material-icons align-middle me-1" style="font-size:18px">block</i>
                                    Lignes ignorées
                                </span>
                                <span class="badge rounded-pill bg-danger">
                                    {{ count($skippedRows) }}
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div style="max-height:220px;overflow-y:auto;">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width:50px">Ligne</th>
                                                <th>Nom</th>
                                                <th>Raison</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($skippedRows as $skippedRow)
                                            <tr>
                                                <td class="text-muted small">{{ $skippedRow['row'] }}</td>
                                                <td>{{ $skippedRow['name'] }}</td>
                                                <td class="text-danger small">{{ $skippedRow['reason'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endif

            @elseif($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p>{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                @if($failures = Session::get('failures'))
                    @if($failures->isNotEmpty())
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header d-flex align-items-center justify-content-between py-2"
                                 style="background:#f8d7da;border-left:4px solid #dc3545;">
                                <span class="fw-semibold text-danger-emphasis">
                                    <i class="material-icons align-middle me-1" style="font-size:18px">error_outline</i>
                                    Erreurs de validation
                                </span>
                                <span class="badge rounded-pill bg-danger">{{ $failures->count() }}</span>
                            </div>
                            <div class="card-body p-0">
                                <div style="max-height:220px;overflow-y:auto;">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width:60px">Ligne</th>
                                                <th>Champ</th>
                                                <th>Erreur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($failures as $failure)
                                            <tr>
                                                <td class="text-muted small">{{ $failure->row() }}</td>
                                                <td class="small">{{ $failure->attribute() }}</td>
                                                <td class="text-danger small">{{ implode(', ', $failure->errors()) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
                    <!-- Tabs navs -->
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">

                    <ul class="nav flex-column0 nav-pills text-center"
                        id="h-pills-tab" role="tablist" aria-orientation="horizontal">
                        <li class="nav-item0">
                            <a class="nav-link active" href="#add-user" data-toggle="tab">
                                <i class="material-icons" style="margin-right: 10px;">person_add</i> Ajouter un membre
                            </a>
                        </li>
                        <li class="nav-item0">
                            <a class="nav-link" href="#add-list" data-toggle="tab">
                                <i class="material-icons" style="margin-right: 10px;">file_upload</i> Importer une liste
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content border rounded p-3 w-100">
                        <div class="tab-pane active" id="add-user">
                            <form method="post" action="{!! route('users.store') !!}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card pb-30">
                                            <div class="card-header card-header-primary card-header-text">
                                                <div class="card-text">
                                                    <h4 class="card-title">Ajouter un utilisateur</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div-- class="container">
                                                    <div class="row">
                                                        <div class="form-group col-md-4 @error('nom') has-danger @enderror">
                                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                                            <input type="text" name="nom" id="nom"
                                                                   value="{{ old('nom') }}"
                                                                   class="form-control @error('nom') is-invalid @enderror">
                                                            @error('nom')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('prenom') has-danger @enderror">
                                                            <label for="prenom" class="bmd-label-floating @error('prenom') text-danger @enderror">Prenom</label>
                                                            <input type="text" name="prenom" id="prenom"
                                                                   value="{{ old('prenom') }}"
                                                                   class="form-control @error('prenom') is-invalid @enderror">
                                                            @error('prenom')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('sexe') has-danger @enderror">
                                                            <label for="sexe" class="bmd-label-floating0 @error('sexe') text-danger @enderror">Sexe</label>
                                                            <select name="sexe" id="sexe" class="selectpicker w-100"
                                                                    data-size="auto" data-style="select-with-transition"
                                                                    data-actions-box="true" data-live-search="true"
                                                                    data-style2="btn btn-primary btn-round"
                                                                    data-header="Choisir le sexe">
                                                                <option value="{{\App\Constantes::SEXE_MASCULIN}}">
                                                                    Homme
                                                                </option>
                                                                <option value="{{\App\Constantes::SEXE_FEMININ}}">
                                                                    Femme
                                                                </option>
                                                            </select>
                                                            @error('sexe')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <!-- </div>
                                                        <div class="row"> -->
                                                        <div class="form-group col-md-4 @error('adresse') has-danger @enderror">
                                                            <label for="adresse" class="bmd-label-floating @error('adresse') text-danger @enderror">Adresse</label>
                                                            <input type="text" name="adresse" id="adresse"
                                                                   value="{{ old('adresse') }}"
                                                                   class="form-control @error('adresse') is-invalid @enderror">
                                                            @error('adresse')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('telephone1') has-danger @enderror">
                                                            <label for="telephone1" class="bmd-label-floating @error('telephone1') text-danger @enderror">Telephone
                                                                1</label>
                                                            <input type="text" name="telephone1" id="telephone1"
                                                                   value="{{ old('telephone1') }}"
                                                                   class="form-control @error('telephone1') is-invalid @enderror">
                                                            @error('telephone1')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('telephone2') has-danger @enderror">
                                                            <label for="telephone2" class="bmd-label-floating @error('telephone2') text-danger @enderror">Telephone
                                                                2</label>
                                                            <input type="text" name="telephone2" id="telephone2"
                                                                   value="{{ old('telephone2') }}"
                                                                   class="form-control @error('telephone2') is-invalid @enderror">
                                                            @error('telephone2')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <!-- </div>
                                                        <div class="row"> -->
                                                        <div class="form-group col-md-4 @error('email') has-danger @enderror">
                                                            <label for="email" class="bmd-label-floating @error('email') text-danger @enderror">Email</label>
                                                            <input type="text" name="email" id="email"
                                                                   value="{{ old('email') }}"
                                                                   class="form-control @error('email') is-invalid @enderror">
                                                            @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('quartier') has-danger @enderror">
                                                            <label for="quartier" class="bmd-label-floating @error('quartier') text-danger @enderror">Quartier</label>
                                                            <input type="text" name="quartier" id="quartier"
                                                                   value="{{ old('quartier') }}"
                                                                   class="form-control @error('quartier') is-invalid @enderror">
                                                            @error('quartier')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4 @error('profession') has-danger @enderror">
                                                            <label for="profession" class="bmd-label-floating @error('profession') text-danger @enderror">Profession</label>
                                                            <input type="text" name="profession" id="profession"
                                                                   value="{{ old('profession') }}"
                                                                   class="form-control @error('profession') is-invalid @enderror">
                                                            @error('profession')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>

                                                        <!-- </div>
                                                        <div class="row"> -->
                                                        <div class="form-group col-md-4 @error('categorie_sociale') has-danger @enderror">
                                                            <label for="categorie_sociale" class="bmd-label-floating0 @error('categorie_sociale') text-danger @enderror">Categorie sociale</label>
                                                            <select name="categorie_sociale" id="categorie_sociale"  
                                                                class="selectpicker"
                                                                data-width="100%"
                                                                data-size="auto"
                                                                data-style="select-with-transition"
                                                                data-actions-box="true" data-live-search="true"
                                                                data-style2="btn btn-primary btn-round"
                                                                data-header="Choisir une catégorie sociale">
                                                                @foreach (\App\Constantes::CATEGORIE_SOCIALES as $categorie_sociale)
                                                                    @if(isset($categorie_sociale))
                                                                        <option value="{{ $categorie_sociale }}">{{$categorie_sociale}}</option>
                                                                    @else
                                                                        <option selected disabled>Aucune categorie
                                                                            sociale trouvée
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @error('categorie_sociale')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-4 @error('apostolat_id') has-danger @enderror">
                                                            <label for="apostolat_id" class="bmd-label-floating0 @error('apostolat_id') text-danger @enderror">Apostolat</label>
                                                            <select name="apostolat_id[]" id="apostolat_id"
                                                                    class="selectpicker" 
                                            data-width="100%"data-size="auto"
                                                                    data-style="select-with-transition"
                                                                    data-actions-box="true" data-live-search="true"
                                                                    data-style2="btn btn-primary btn-round"
                                                                    data-header="Choisir un apostolat" multiple>
                                                                @foreach ($apostolats as $apostolat)
                                                                    @if(isset($apostolat))
                                                                        <option value="{{ $apostolat->id }}">{{$apostolat->nom}}</option>
                                                                    @else
                                                                        <option selected disabled>Aucun apostolat
                                                                            trouvé
                                                                        </option>
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
                                                            <label for="niveau_engagement_id" class="bmd-label-floating0 @error('niveau_engagement_id') text-danger @enderror">Niveau d'engagement</label>
                                                            <select name="niveau_engagement_id"
                                                                    id="niveau_engagement_id"
                                                                    class="selectpicker" 
                                            data-width="100%"data-size="auto"
                                                                    data-style="select-with-transition"
                                                                    data-actions-box="true" data-live-search="true"
                                                                    data-style2="btn btn-primary btn-round"
                                                                    data-header="Choisir un niveau d'engagement">
                                                                @foreach ($niveau_engagements as $niveau_engagement)
                                                                    @if(isset($niveau_engagement))
                                                                        <option value="{{ $niveau_engagement->id }}">{{$niveau_engagement->nom}}</option>
                                                                    @else
                                                                        <option selected disabled>Aucun niveau
                                                                            d'engagement trouvé
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @error('niveau_engagement_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <!-- </div>
                                                        <div class="row"> -->

                                                        <div class="form-group col-md-4 @error('groupe_id') has-danger @enderror">
                                                            <label for="groupe_id" class="bmd-label-floating0 @error('groupe_id') text-danger @enderror">Groupe</label>
                                                            <select name="groupe_id" id="groupe_id"
                                                                    class="selectpicker" 
                                            data-width="100%"data-size="auto"
                                                                    data-style="select-with-transition"
                                                                    data-actions-box="true" data-live-search="true"
                                                                    data-style2="btn btn-primary btn-round"
                                                                    data-header="Choisir un groupe">
                                                                @foreach ($groupes as $groupe)
                                                                    @if(isset($groupe))
                                                                        <option value="{{ $groupe->id }}">{{$groupe->nom_groupe}}</option>
                                                                    @else
                                                                        <option selected disabled>Aucun groupe trouvé
                                                                        </option>
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
                                                            <input type="password" name="password" id="password"
                                                                   value="{{ old('password') }}"
                                                                   class="form-control @error('password') is-invalid @enderror">
                                                            @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-4 @error('etat') has-danger @enderror">
                                                            <label for="etat" class="bmd-label-floating0 @error('etat') text-danger @enderror">Etat</label>
                                                            <select name="etat" id="etat" 
                                                                    class="selectpicker" 
                                            data-width="100%"data-size="auto"
                                                                    data-style="select-with-transition"
                                                                    data-actions-box="true" data-live-search="true"
                                                                    data-style2="btn btn-primary btn-round"
                                                                    data-header="Choisir un état">
                                                                <option value="{{ \App\Constantes::ETAT_ACTIF }}">
                                                                    Activé
                                                                </option>
                                                                <option value="{{ \App\Constantes::ETAT_INACTIF }}">
                                                                    Désactivé
                                                                </option>
                                                            </select>
                                                            @error('etat')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>

                                                        <!-- </div>
                                                        <div class="row"> -->
                                                        <div class="form-group col-md-4 @error('date_entree') has-danger @enderror">
                                                            <label for="date_entree" class="bmd-label-floating0 @error('date_entree') text-danger @enderror">Date d'entrée</label>
                                                            <input type="date" name="date_entree" id="date_entree"
                                                                   value="{{ old('date_entree') }}"
                                                                   class="form-control @error('date_entree') is-invalid @enderror">

                                                            @error('date_entree')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div><!-- row -->

                                                    <!-- >
                                <div class="row">
                                    <div class="form-group col-md-6 @error('responsabilite_groupe_id') has-danger @enderror">
                                        <label for="responsabilite_groupe_id" class="bmd-label-floating @error('responsabilite_groupe_id') text-danger @enderror">Responsable de Groupe ?</label>

                                        <select name="responsabilite_groupe_id" id="responsabilite_groupe_id" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                            <option value="">Non</option>
                                            @foreach ($groupes as $groupe)
                                                    @if(isset($groupe))
                                                            <option value="{{ $groupe->id }}">{{$groupe->nom_groupe}}</option>
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
                                                            <option value="{{ $responsabilite }}">{{$responsabilite}}</option>
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

                                                        </div--><!-- row -->

                                                    <!--div class="row">
                                    <div class="form-group col-md-6 @error('responsable_sous_zone_id') has-danger @enderror">
                                        <label for="responsable_sous_zone_id" class="bmd-label-floating @error('responsable_sous_zone_id') text-danger @enderror">Responsable de Sous-zone?</label>

                                        <select name="responsable_sous_zone_id" id="responsable_sous_zone_id" class="selectpicker col-md-7" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le sous-zone">
                                            <option value="">Non</option>
                                            @foreach ($sous_zones as $sous_zone)
                                                    @if(isset($sous_zone))
                                                            <option value="{{ $sous_zone->id }}">{{$sous_zone->nom}}</option>
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
                                        <label for="responsabilite_sous_zone" class="bmd-label-floating @error('responsabilite_sous_zone') text-danger @enderror">Responsabilité de dans la sous-zone</label>

                                        <select name="responsabilite_sous_zone" id="responsabilite_sous_zone" class="selectpicker col-md-6" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la responsabilité">
                                            <option value="">Aucune</option>
                                            @foreach (\App\Constantes::RESPONSABILITES_SOUS_ZONE as $responsabilite)
                                                    @if(isset($responsabilite))
                                                            <option value="{{ $responsabilite }}">{{$responsabilite}}</option>
                                                @else
                                                            <option  selected disabled>Aucune responsabilité trouvé</option>
                                                        @endif
                                                    @endforeach
                                                            </select>
                                                            @error('responsabilite_sous_zone')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                                            </div>
                                                        </div--><!-- row -->

                                                    <!--div class="row">
                                    <div class="form-group col-md-6 @error('responsable_zone_id') has-danger @enderror">
                                        <label for="responsable_zone_id" class="bmd-label-floating @error('responsable_zone_id') text-danger @enderror">Responsable Zone?</label>

                                        <select name="responsable_zone_id" id="responsable_zone_id" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la zone">
                                            <option value="">Non</option>
                                            @foreach ($zones as $zone)
                                                    @if(isset($zone))
                                                            <option value="{{ $zone->id }}">{{$zone->nom}}</option>
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
                                                            <option value="{{ $responsabilite }}">{{$responsabilite}}</option>
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
                                                        </div>

                                                    </div--><!-- container -->

                                                <div class="clearfix"></div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button id="btn-send" type="submit"
                                                                class="btn btn-primary"><i class="material-icons">send</i> Envoyer
                                                        </button>
                                                    </div>
                                                </div><!-- row -->

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="add-list">
                            <i class="sub-title text-muted">En-tête: Nom, Prenom, Sexe, Adresse, Telephone1, Telephone2, Email, Quartier, Profession, Categorie sociale, Apostolat, Niveau engagement, Groupe, Mot de passe, Etat, Date entrée.</i>
                            <hr>
                            <p>
                                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf
                                    <div class="row align-items-end g-3">
                                        <div class="col-md-5">
                                            {{-- Drop-zone / file picker --}}
                                            <div id="importDropZone"
                                                 class="@error('file') border-danger @else border-secondary @enderror"
                                                 style="border:2px dashed #aaa;border-radius:10px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#fafafa;">
                                                <input type="file" name="file" id="importFileInput"
                                                       accept=".xls,.xlsx,.csv" required
                                                       style="display:none;">
                                                <div id="importPlaceholder">
                                                    <i class="material-icons" style="font-size:40px;color:#aaa;">upload_file</i>
                                                    <p class="mb-0 mt-1 text-muted small">Glisser-déposer le fichier Excel ici<br>ou <span style="color:#1a73e8;text-decoration:underline;">parcourir</span></p>
                                                    <p class="mb-0 text-muted" style="font-size:11px;">Formats acceptés : .xls, .xlsx, .csv</p>
                                                </div>
                                                <div id="importFileInfo" style="display:none;">
                                                    <i class="material-icons" style="font-size:38px;color:#1e7e34;">check_circle</i>
                                                    <p class="mb-0 mt-1 fw-semibold" id="importFileName" style="word-break:break-all;color:#1e7e34;"></p>
                                                    <p class="mb-0 text-muted" id="importFileSize" style="font-size:11px;"></p>
                                                    <span style="font-size:11px;color:#888;text-decoration:underline;cursor:pointer;" id="importChangefile">Changer de fichier</span>
                                                </div>
                                            </div>
                                            @error('file')
                                            <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" id="importSubmitBtn" class="btn btn-primary w-100" disabled>
                                                <i class="material-icons align-middle" style="font-size:18px;">file_upload</i> Importer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/custom.js')}}"></script>
    <script type="text/javascript">
        $('button:submit').click(function (e) {
            e.preventDefault();
            $(this).closest('form').submit();
        });

        (function () {
            var zone       = document.getElementById('importDropZone');
            var input      = document.getElementById('importFileInput');
            var placeholder = document.getElementById('importPlaceholder');
            var fileInfo   = document.getElementById('importFileInfo');
            var fileName   = document.getElementById('importFileName');
            var fileSize   = document.getElementById('importFileSize');
            var changeBtn  = document.getElementById('importChangefile');
            var submitBtn  = document.getElementById('importSubmitBtn');

            if (!zone) return;

            function formatBytes(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            }

            function showFile(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatBytes(file.size);
                placeholder.style.display = 'none';
                fileInfo.style.display = 'block';
                zone.style.borderColor = '#1e7e34';
                zone.style.background  = '#f0fff4';
                submitBtn.disabled = false;
            }

            function resetZone() {
                placeholder.style.display = 'block';
                fileInfo.style.display = 'none';
                zone.style.borderColor = '#aaa';
                zone.style.background  = '#fafafa';
                submitBtn.disabled = true;
                input.value = '';
            }

            // Click on zone opens file picker
            zone.addEventListener('click', function (e) {
                if (e.target === changeBtn) return;
                input.click();
            });

            changeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                resetZone();
                input.click();
            });

            input.addEventListener('change', function () {
                if (this.files && this.files[0]) showFile(this.files[0]);
            });

            // Drag & drop
            zone.addEventListener('dragover', function (e) {
                e.preventDefault();
                zone.style.borderColor = '#1a73e8';
                zone.style.background  = '#e8f0fe';
            });
            zone.addEventListener('dragleave', function () {
                zone.style.borderColor = input.files && input.files[0] ? '#1e7e34' : '#aaa';
                zone.style.background  = input.files && input.files[0] ? '#f0fff4' : '#fafafa';
            });
            zone.addEventListener('drop', function (e) {
                e.preventDefault();
                var file = e.dataTransfer.files[0];
                if (!file) return;
                // Transfer dropped file to the real input via DataTransfer
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showFile(file);
            });

            // Prevent form submit if no file (belt + suspenders)
            document.getElementById('importForm').addEventListener('submit', function (e) {
                if (!input.files || !input.files[0]) {
                    e.preventDefault();
                    zone.style.borderColor = '#dc3545';
                }
            });
        })();
    </script>
@endsection




