@extends('layouts.app')
@section('page_title') Profile @endsection
@section('content')
    <div class="content user-level" user-level="{{auth()->user()->niveau}}">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <div class="row">
                <div class="col-md-8">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Modifier mon profil</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('profile_store', ['id'=>$user->id]) !!}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $user->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
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
                                            <input type="text" value="{{old('prenom') ?? $user->prenom}}" name="prenom" id="prenom"  class="form-control @error('prenom') is-invalid @enderror">
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
                                            <input type="text" value="{{old('ville') ?? $user->ville}}" name="ville" id="ville" class="form-control @error('ville') is-invalid @enderror">
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
                                            <input type="text" value="{{old('pays') ?? $user->pays}}" name="pays" id="pays"
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
                                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') ?? $user->adresse }}" class="form-control @error('adresse') 'text-danger' @enderror">
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
                                        <div class="form-group @error('telephone') has-danger @enderror">
                                            <label for="telephone" class="bmd-label-floating @error('telephone') text-danger @enderror">Téléphone</label>
                                            <input type="text" id="telephone" name="telephone" value="{{ old('telephone') ?? $user->adresse }}" class="form-control @error('telephone') is-invalid @enderror">
                                            @error('telephone')
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
                                                <option value="{{App\Constantes::HOMME}}" @if(empty(old('sexe')) || old('sexe')==App\Constantes::HOMME || $user->sexe ==  App\Constantes::HOMME) selected @endif>Homme</option>
                                                <option value="{{App\Constantes::FEMME}}" @if(old('sexe')==App\Constantes::FEMME || $user->sexe ==  App\Constantes::FEMME) selected @endif>Femme</option>
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
                                            <input type="text" value="{{ old('date_naissance') ?? $user->date_naissance}}" name="date_naissance" id="date_naissance" class="form-control datepicker @error('date_naissance') text-danger @enderror">
                                            @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group @error('email') has-danger @enderror">
                                            <label for="email" class="bmd-label-floating @error('email') text-danger @enderror">Email</label>
                                            <input type="text" name="email" id="email" value="{{ old('email') ?? $user->email }}" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{--
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('password') has-danger @enderror">
                                            <label for="password" class="bmd-label-floating @error('password') text-danger @enderror">Nouveau  Mot de passe (code secret)</label>
                                            <input type="password" value="" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('confirm_password') has-danger @enderror">
                                            <label for="password" class="bmd-label-floating @error('confirm_password') text-danger @enderror">Retaper le  Mot de passe (code secret)</label>
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror">
                                            @error('confirm_password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                --}}
                                <button type="submit" class="btn btn-primary pull-right">Modifier mon Profil</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-avatar">
                            <a href="#pablo">
                                <img class="img" src="{{asset('images/avatar.jpeg')}}" />
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="card-category text-gray">{{$user->niveau}}</h6>
                            <h4 class="card-title">{{ucfirst($user->prenom)}} {{ucfirst($user->nom)}}</h4>
                            <p class="card-description">

                            </p>
                            <a href="#pablo" class="btn btn-primary btn-round">Voir</a>
                        </div>
                    </div>
                </div>
            </div>
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
        $(':password').val('');
    </script>
@endsection
