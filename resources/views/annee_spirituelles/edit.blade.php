@extends('layouts.app')
@section('page_title') Année spirituelle @endsection
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
                                <h4 class="card-title">Modifier l'année spirituelle</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('annee_spirituelles.update', [$annee_spirituelle->id]) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $annee_spirituelle->nom}}" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('date_debut') has-danger @enderror">
                                            <label for="date_debut" class="bmd-label-floating @error('date_debut') text-danger @enderror">Date début</label>
                                            <input type="date" value="{{old('date_debut') ?? $annee_spirituelle->date_debut}}" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror">
                                            @error('date_debut')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('date_fin') has-danger @enderror">
                                            <label for="date_fin" class="bmd-label-floating @error('date_fin') text-danger @enderror">Date fin</label>
                                            <input type="date" value="{{old('date_fin') ?? $annee_spirituelle->date_fin}}" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror">
                                            @error('date_fin')
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
