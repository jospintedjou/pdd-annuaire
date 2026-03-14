@extends('layouts.app')
@section('page_title') Responsables sous-zone {{$sous_zone->nom}} @endsection
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single {
            height: 36px !important;
            border: none !important;
            border-bottom: 1px solid #d2d2d2 !important;
            border-radius: 0 !important;
            background-color: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            color: #3c3c3c !important;
            padding-left: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #aaa !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 34px !important; }
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom: 2px solid #0d6516 !important;
            outline: none !important;
        }
        .select2-dropdown { border: 1px solid #d2d2d2 !important; border-radius: 4px !important; box-shadow: 0 2px 8px rgba(0,0,0,.12) !important; }
        .select2-container--default .select2-search--dropdown .select2-search__field { border: 1px solid #d2d2d2 !important; border-radius: 3px !important; outline: none !important; }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: #0d6516 !important; box-shadow: none !important; }
        .select2-container--default .select2-results__option { color: #3c3c3c !important; }
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6516 !important;
            color: #fff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"] { background-color: #e8f5e9 !important; color: #0d6516 !important; }
        .resp-table { width: 100%; border-collapse: collapse; }
        .resp-table td { padding: 8px 10px; vertical-align: middle; }
        .resp-table tr { border-bottom: 1px solid #f0f0f0; }
        .resp-table tr:last-child { border-bottom: none; }
        .resp-table .col-label { width: 38%; font-size: .875rem; font-weight: 500; color: #3c3c3c; white-space: nowrap; }
        .resp-table .col-select { width: 62%; }
        .resp-panel { border: 1px solid #e8e8e8; border-radius: 6px; overflow: hidden; }
    </style>
@endsection

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
                                <h4 class="card-title">Responsables {{$sous_zone->nom}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{!! route('responsable_sous_zones.update', $sous_zone) !!}">
                                @csrf
                                @method('PUT')
                                <div class="container">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Zone</label>
                                            <input type="text" class="form-control" disabled value="{{ $sous_zone->zone()->first()->nom }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Sous-Zone</label>
                                            <input type="hidden" name="sous_zone_id" value="{{ $sous_zone->id }}">
                                            <input type="text" class="form-control" disabled value="{{ $sous_zone->nom }}">
                                        </div>
                                    </div>
                                    @php
                                        $respList  = $responsabilites->values();
                                        $half      = (int) ceil($respList->count() / 2);
                                        $leftHalf  = $respList->slice(0, $half);
                                        $rightHalf = $respList->slice($half);
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="resp-panel">
                                                <table class="resp-table">
                                                @foreach($leftHalf as $responsabilite)
                                                    @php $currentUser = $currentResponsables->get($responsabilite->id); @endphp
                                                    <tr>
                                                        <td class="col-label">{{ $responsabilite->nom }}</td>
                                                        <td class="col-select">
                                                            <select name="responsabilite_sous_zones[{{ $responsabilite->id }}]" class="responsable-select form-control">
                                                                <option value="">Aucun</option>
                                                                @if($currentUser)
                                                                    <option value="{{ $currentUser->id }}" selected>{{ $currentUser->nom }} {{ $currentUser->prenom }}</option>
                                                                @endif
                                                                @foreach($initialUsers as $u)
                                                                    @if(!$currentUser || $u->id !== $currentUser->id)
                                                                        <option value="{{ $u->id }}">{{ $u->nom }} {{ $u->prenom }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="resp-panel">
                                                <table class="resp-table">
                                                @foreach($rightHalf as $responsabilite)
                                                    @php $currentUser = $currentResponsables->get($responsabilite->id); @endphp
                                                    <tr>
                                                        <td class="col-label">{{ $responsabilite->nom }}</td>
                                                        <td class="col-select">
                                                            <select name="responsabilite_sous_zones[{{ $responsabilite->id }}]" class="responsable-select form-control">
                                                                <option value="">Aucun</option>
                                                                @if($currentUser)
                                                                    <option value="{{ $currentUser->id }}" selected>{{ $currentUser->nom }} {{ $currentUser->prenom }}</option>
                                                                @endif
                                                                @foreach($initialUsers as $u)
                                                                    @if(!$currentUser || $u->id !== $currentUser->id)
                                                                        <option value="{{ $u->id }}">{{ $u->nom }} {{ $u->prenom }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary pull-right">Modifier</button>
                                    <div class="clearfix"></div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(function () {
        var searchUrl = '{{ route('users.search') }}';
        $('.responsable-select').each(function () {
            var $select = $(this);
            $select.select2({
                placeholder: 'Rechercher un membre...',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: searchUrl,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return { q: params.term || '', page: params.page || 1 };
                    },
                    processResults: function (data) {
                        return {
                            results: [{ id: '', text: 'Aucun' }].concat(data.results),
                            pagination: data.pagination
                        };
                    },
                    cache: true
                }
            });
        });
    });
</script>
@endsection
