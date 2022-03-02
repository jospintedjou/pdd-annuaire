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
                                <h4 class="card-title">Modifier l'zone</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('zones.update', $zone->id) !!}">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $zone->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('ville') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Ville</label>
                                            <input type="text" name="ville" id="ville" value="{{ old('ville') ?? $zone->ville }}" class="form-control @error('nom') is-invalid @enderror">
                                            @error('ville')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('pays') has-danger @enderror">
                                            <label for="pays" class="bmd-label-floating @error('nom') text-danger @enderror">Pays</label>
                                            <input type="text" name="pays" id="pays" value="{{ old('pays') ?? $zone->pays }}" class="form-control @error('pays') is-invalid @enderror">
                                            @error('pays')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('continent') has-danger @enderror">
                                            <label for="continent" class="bmd-label-floating @error('continent') text-danger @enderror">Continent</label>
                                            <input type="text" name="continent" id="continent" value="{{ old('continent') ?? $zone->continent }}" class="form-control @error('continent') is-invalid @enderror">
                                            @error('continent')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary pull-right">Modifier</button>
                                <div class="clearfix"></div>
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
