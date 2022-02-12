@extends('layouts.app')
@section('page_title') Utilisateur @endsection
@section('content')
    <div class="content user-level" user-level="{{auth()->user()->niveau}}">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('users.store') !!}">
                @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Ajouter un utilisateur</h4>
                            </div>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 @error('niveau') has-danger @enderror">
                                        <label for="niveau" class="bmd-label-floating @error('niveau') text-danger @enderror">Niveau d'entrée</label>
                                        <select name="niveau" id="niveau" class="selectpicker col-md-10" data-size="auto" data-style="btn btn-primary btn-round" title="Choisir un niveau">
                                            @if(empty(old('niveau')))
                                                <option selected disabled>Choisir un niveau</option>
                                            @endif
                                            @foreach($niveaux as $niveau)
                                                <option value="{{$niveau}}" @if(old('niveau') == $niveau) selected @endif>{{$niveau}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="row" id="has-service-center"
                                 style="display: {{(!empty(old('niveau')) && old('niveau') == \App\Constantes::NIVEAU_VIP4 && auth()->user()->isCBI())
                                    || old('niveau') == \App\Constantes::NIVEAU_VIP5 ? 'block' : 'none'}}">
                                <div class="form-group col-md-12 togglebutton">
                                    <label class="bmd-label" style="color: #999">
                                        A un service center
                                        <input type="checkbox" id="has-service-center" name="has-service-center" {{!empty(old('has-service-center')) && old('has-service-center')== 'on' || empty(old('has-service-center')) ? 'checked=""' : ''}}>
                                        <span class="toggle"></span>
                                    </label>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('prenom') has-danger @enderror">
                                            <label for="prenom" class="bmd-label-floating @error('prenom') text-danger @enderror">Prénom</label>
                                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" class="form-control @error('prenom') is-invalid @enderror">
                                            @error('prenom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group @error('ville') has-danger @enderror">
                                            <label for="ville" class="bmd-label-floating @error('ville') text-danger @enderror">Ville</label>
                                            <input type="text" name="ville" id="ville" value="{{ old('ville') }}" class="form-control @error('ville') is-invalid @enderror">
                                            @error('ville')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('pays') has-danger @enderror">
                                            <label for="pays" class="bmd-label-floating @error('pays') text-danger @enderror">Pays</label>
                                            <input type="text" name="pays" id="pays" value="{{ old('pays') ?? 'cameroun'}}"
                                                   class="form-control @error('pays') is-invalid @enderror">
                                            @error('pays')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('adresse') has-danger @enderror">
                                            <label for="adresse" class="bmd-label-floating @error('adresse') text-danger @enderror">Adresse</label>
                                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" class="form-control @error('adresse') 'text-danger' @enderror">
                                            @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group @error('telephone1') has-danger @enderror">
                                            <label for="telephone1" class="bmd-label-floating @error('telephone1') text-danger @enderror">Téléphone</label>
                                            <input type="text" id="telephone1" name="telephone1" value="{{ old('telephone1') }}" class="form-control @error('telephone1') is-invalid @enderror">
                                            @error('telephone1')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('sexe') has-danger @enderror">
                                            <select  name="sexe" id="sexe" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir le sexe">
                                            <option value="{{App\Constantes::HOMME}}" @if(empty(old('sexe')) || old('sexe')==App\Constantes::HOMME) selected @endif>Homme</option>
                                            <option value="{{App\Constantes::FEMME}}" @if(old('sexe')==App\Constantes::FEMME) selected @endif>Femme</option>
                                            </select>
                                            @error('sexe')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group @error('date_naissance') has-danger @enderror">
                                            <label for="date_naissance" class="bmd-label-floating @error('date_naissance') text-danger @enderror">Date de naissance</label>
                                            <input type="text" value="{{ old('date_naissance') ?? '01/05/1990'}}" name="date_naissance" id="date_naissance" class="form-control datepicker @error('date_naissance') text-danger @enderror">
                                            @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('email') has-danger @enderror">
                                            <label for="email" class="bmd-label-floating @error('email') text-danger @enderror">Email</label>
                                            <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('password') has-danger @enderror">
                                            <label for="password" class="bmd-label-floating @error('password') text-danger @enderror">Mot de passe)</label>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                        </div>
                    </div>
                    <button id="btn-send" type="submit" class="btn btn-primary wd-100">Envoyer</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/user.js')}}"></script>
    <script type="text/javascript">
        $('button:submit').click(function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        });
    </script>
@endsection




