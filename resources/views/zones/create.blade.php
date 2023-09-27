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

                                    <div class="form-group @error('continent') has-danger @enderror">
                                        <label for="continent" class="bmd-label-floating0 @error('continent') text-danger @enderror">Continent</label>

                                        <select name="continent" id="continent" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-actions-box="true" data-live-search="true"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le continent">
                                            @foreach (config('data.continents') as $continent)
                                                @if(isset($continent))
                                                    <option value="{{ $continent }}">{{ $continent }}</option>
                                                @else
                                                    <option  selected disabled>Aucune zone trouvée</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('continent')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group @error('pays') has-danger @enderror">
                                        <label for="pays" class="bmd-label-floating0 @error('pays') text-danger @enderror">Pays</label>

                                        <select name="pays" id="pays" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-actions-box="true" data-live-search="true"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir le pays">
                                            @foreach (config('data.countries') as $pays)
                                                @if(isset($pays))
                                                    <option value="{{ $pays }}">{{ $pays }}</option>
                                                @else
                                                    <option  selected disabled>Aucun pays trouvée</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('pays')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group @error('ville') has-danger @enderror">
                                        <label for="ville" class="bmd-label-floating0 @error('ville') text-danger @enderror">Ville</label>

                                        <select name="ville" id="ville" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-actions-box="true" data-live-search="true"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la ville">
                                            @foreach (config('data.towns') as $ville)
                                                @if(isset($ville))
                                                    <option value="{{ $ville }}">{{ $ville }}</option>
                                                @else
                                                    <option  selected disabled>Aucune ville trouvée</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('ville')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror">
                                        @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group @error('nom') has-danger @enderror">
                                        <button id="btn-send" type="submit" class="btn btn-primary">Envoyer</button>
                                    </div>
                                </div><!-- col-md-8 -->

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
