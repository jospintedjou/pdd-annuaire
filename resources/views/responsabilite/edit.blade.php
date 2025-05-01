@extends('layouts.app')
@section('page_title') Responsabilit&eacute; @endsection
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
                                <h4 class="card-title">Modifier la Responsabilité</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('responsabilite.update', [$responsabilite->id]) !!}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('nom') has-danger @enderror">
                                            <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                            <input type="text" value="{{old('nom') ?? $responsabilite->nom}}" name="nom" id="nom" 
                                                class="form-control @error('nom') is-invalid @enderror">
                                            @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <button id="btn-send" type="submit" class="btn btn-primary"><i class="material-icons">send</i> Modifier</button>
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
