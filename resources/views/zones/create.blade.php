@extends('layouts.app')
@section('page_title') zone @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('zones.store') !!}">
                @csrf
                <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Ajouter une zone</h4>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror">
                                        @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="@error('continent') has-danger @enderror">
                                        <label for="continent" class="bmd-label-floating @error('continent') text-danger @enderror">Continent</label>
                                        <select name="continent" id="continent" value="{{ old('continent') }}" class="form-control @error('continent') is-invalid @enderror">
                                            <option value="" disabled selected>-- <i>Choisir dans la liste</i></option>
                                            @foreach (config('data.continents') as $continent)
                                                <option value="{{ $continent }}">{{ $continent }}</option>
                                            @endforeach
                                        </select>
                                        @error('continent')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                    </div>
                                    <div class="@error('pays') has-danger @enderror">
                                        <label for="pays" class="bmd-label-floating @error('nom') text-danger @enderror">Pays</label>
                                        <select name="pays" id="pays" value="{{ old('pays') }}" class="form-control @error('pays') is-invalid @enderror">
                                            <option value="" disabled selected>-- <i>Choisir dans la liste</i></option>
                                            @foreach (config('data.countries') as $pays)
                                                <option value="{{ $pays }}">{{ $pays }}</option>
                                            @endforeach
                                        </select>
                                        @error('pays')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                    </div>
                                    <div class="@error('ville') has-danger @enderror">
                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Ville</label>
                                        <select name="ville" id="ville" value="{{ old('ville') }}" class="form-control @error('ville') is-invalid @enderror">
                                            <option value="" disabled selected>-- <i>Choisir dans la liste</i></option>
                                            @foreach (config('data.towns') as $ville)
                                                <option value="{{ $ville }}">{{ $ville }}</option>
                                            @endforeach
                                        </select>
                                        @error('ville')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
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
