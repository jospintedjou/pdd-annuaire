@extends('layouts.app')
@section('page_title') zone @endsection
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
                                <h4 class="card-title">Modifier une zone</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('zones.update', $zone->id) !!}">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-8">


                                        <div class="form-group @error('continent') has-danger @enderror">
                                            <label for="continent" class="bmd-label-floating0 @error('continent') text-danger @enderror">Continent</label>

                                            <select name="continent" id="continent" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-actions-box="true" data-live-search="true"
                                                    data-style2="btn btn-primary btn-round" data-header="Choisir le continent">
                                                @foreach (config('data.continents') as $continent)
                                                    @if(isset($continent))
                                                        <option value="{{ $continent }}" {{$continent == $zone->continent ? 'selected'  : '' }}>{{ $continent }}</option>
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
                                                        <option value="{{ $pays }}" {{$pays == $zone->pays ? 'selected'  : '' }}>{{ $pays }}</option>
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
                                            <label for="ville" class="bmd-label-floating0 @error('ville') text-danger @enderror">VIlle</label>

                                            <select name="ville" id="ville" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                    data-actions-box="true" data-live-search="true"
                                                    data-actions-box="true" data-live-search="true"data-style2="btn btn-primary btn-round" data-header="Choisir le type d'assujetti">
                                                @foreach (config('data.towns') as $ville)
                                                    @if(isset($ville))
                                                        <option value="{{ $ville }}"  value="{{ $continent }}" {{$continent == $zone->ville ? 'selected'  : '' }}>{{ $ville }}</option>
                                                    @else
                                                        <option selected disabled>Aucune ville trouvée</option>
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
                                            <input type="text" value="{{old('nom') ?? $zone->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                    </div>

                                <button type="submit" class="btn btn-primary">Modifier</button>
                                <div class="clearfix"></div>
                            </form>
                        </div><!-- col-md-8 -->
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
