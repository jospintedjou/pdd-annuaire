@extends('layouts.app')
@section('page_title') Categorie Activit&eacute; @endsection
@section('content')
    <div class="content user-level">
        <div class="container-fluid">
            @component('helpers.alert')
                .
            @endcomponent
            <form method="post" action="{!! route('categorie_activites.store') !!}" id="groupe_form">
                @csrf
                <div class="row">
                <div class="col-md-12">
                    <div class="card pb-30">
                        <div class="card-header card-header-primary card-header-text">
                            <div class="card-text">
                                <h4 class="card-title">Ajouter une Categorie</h4>
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
                                    <div class="@error('periodicite') has-danger @enderror">
                                        <label for="periodicite" class="bmd-label-floating @error('periodicite') text-danger @enderror">Periodicit&eacute;</label>
                                        <select name="periodicite" id="periodicite" value="{{ old('periodicite') }}" class="form-control @error('periodicite') is-invalid @enderror">
                                            <option value="" disabled selected>-- <i>Choisir dans la liste</i></option>
                                            @foreach (config('data.periods') as $period)
                                                <option value="{{ $period }}">{{ $period }}</option>
                                            @endforeach
                                        </select>
                                        @error('periodicite')
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
