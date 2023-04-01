@extends('layouts.app')
@section('page_title') Categorie Activit&eacute; @endsection
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
                                <h4 class="card-title">Modifier la categorie</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('categorie_activites.update', $categorieActivite) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" name="nom" id="nom" value="{{ old('nom') ?? $categorieActivite->nom }}" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="@error('periodicite') has-danger @enderror">
                                            <label for="periodicite" class="bmd-label-floating @error('periodicite') text-danger @enderror">Periodicit&eacute;</label>
                                            <select name="periodicite" id="periodicite" value="{{ old('periodicite') ?? $categorieActivite->periodicite }}" class="selectpicker col-md-10" data-size="auto" data-style="select-with-transition"
                                                data-style2="btn btn-primary btn-round" data-header="Choisir la catégorie">
                                                <option value="" disabled>-- <i>Choisir dans la liste</i></option>
                                                @foreach (config('data.periods') as $period)
                                                    <option value="{{ $period }}" {{$period == $categorieActivite->periodicite ? 'selected' : ''}}>{{ $period }}</option>
                                                @endforeach
                                            </select>
                                            @error('periodicite')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <br>
                                        </div>
                                        <button type="submit" class="btn btn-primary pull-right">Modifier</button>
                                        <div class="clearfix"></div>
                                    </div>
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
