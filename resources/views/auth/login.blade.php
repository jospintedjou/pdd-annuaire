@extends('layouts.app-login')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">

                <form style="padding-top: 50px" class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-content"><!-- Form content -->
                        <div class="logo">
                            <a href="#" class="simple-text logo-normal">
                                <img src="{{asset('images/logo.png')}}" alt="">
                            </a>
                        </div>
                        @csrf
                        <span class="login100-form-title p-b-43" style="font-size: 20px">
                            Connectez-vous
                        </span>

                        <div class="wrap-input100 validate-input" data-validate = "Adresse email valide requise: exple@abc.xyz">
                            <input class="input100" type="text" name="email">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Email</span>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="wrap-input100 validate-input" data-validate="Mot de passe requis">
                            <input class="input100" id="password" type="password" name="password">
                            <span class="focus-input100"></span>
                            <span class="label-input100">Mot de passe</span>
                            <div class="input-group-append field-icon pointer">
                                <span data-toggle="#password" class="input-group-text toggle-password">
                                    <span class="fa fa-eye"></span>
                                </span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="flex-sb-m w-full p-t-3 p-b-32">
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="ckb1">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <div>
                                <a href="{{ route('password.update') }}" class="txt1">
                                    Mot de passe oublié?
                                </a>
                            </div>
                        </div>

                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn" style="background: #218310">
                                Se connecter
                            </button>
                        </div>
                    </div><!-- Form content -->
                </form>

                <div class="login100-more" style="background-image: url('{{asset('images/25emme_anniv_image.jpg')}}');">
                </div>
            </div>
        </div>
    </div>
@endsection
