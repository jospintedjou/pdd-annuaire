@extends('layouts.app')
@section('page_title') Tableau de bord de <span class="text-primary">{{$user->nom}} {{$user->prenom}}</span>  @endsection
@section('content')
    <div class="content" data-fullname="{{$user->nom}} {{$user->prenom}}">
        <div class="container-fluid">
            <div class="row">
                <!-- Infos Membre -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Infos Membre</p>
                        </div>
                        <div class="card-footer">
                            <table class="table table-striped table-no-bordered table-hover dtr-inline"
                                   style="width:100%;" cellspacing="0">
                                <tr><td>Nom</td><td>{{$user->nom}} {{$user->prenom}}</td></tr>
                                <tr><td>Groupe</td><td>{{$groupe->nom_groupe}}</td></tr>
                                <tr><td>Zone</td><td>{{$zone->nom ?? '—'}}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Accompagnement Spirituel -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Accompagnement Spirituel</p>
                        </div>
                        <div class="card-footer">
                            <table class="table table-striped table-no-bordered table-hover dtr-inline"
                                   style="width:100%;" cellspacing="0">
                                <tbody><tr><td>Effectué?</td><td>Oui</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Centre de retraite -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Centre de retraite</p>
                        </div>
                        <div class="card-footer">
                            <table class="table table-striped table-no-bordered table-hover dtr-inline"
                                   style="width:100%;" cellspacing="0">
                                <tbody><tr><td>Centre de retraite payé?</td><td>Oui</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary table -->
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card mt-0 card-h-md">
                        <div class="card-header card-header-light">
                            <h4 class="card-title" style="color:#3c3c3b">Statistiques de présence — {{$user->nom}} {{$user->prenom}}</h4>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="datatables"
                                   class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                   style="width:100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="25%">Activité</th>
                                        <th width="15%">Participation</th>
                                        <th width="15%">Total de séances</th>
                                        <th width="15%">Ratio</th>
                                        <th width="15%">Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($categorieActivites))
                                    @foreach($categorieActivites as $nom => $cat)
                                        <tr>
                                            <td><span class="font-weight-normal">{{ $nom }}</span></td>
                                            <td><span class="font-weight-normal">{{ $cat['nombreParticipation'] }}</span></td>
                                            <td><span class="font-weight-normal">{{ $cat['nombreActivite'] }}</span></td>
                                            <td><span class="font-weight-normal">{{ $cat['ratio'] }}</span></td>
                                            <td><span class="font-weight-normal">{{ $cat['stats'] }}%</span></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mr-3 mb-3">
                            <a href="{!! route('users.index') !!}" class="btn btn-primary btn-sm">Voir tous les membres</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed stats -->
            <div class="row">
                <div class="card mt-0 card-h-md" style="width:100%">
                    <div class="card-header card-header-light">
                        <h4 class="card-title" style="color:#3c3c3b">Statistiques détaillées — {{$user->nom}} {{$user->prenom}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper d-flex">
                                <ul class="nav flex-column nav-pills text-center"
                                    id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                        <li class="nav-item0">
                                            <a class="nav-link @if($loop->first) active @endif"
                                               href="#cat-{{ trim(str_replace(' ', '-', $nom)) }}"
                                               data-toggle="tab">{{ $nom }}</a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 w-100">
                                    @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                        @php $tabId = trim(str_replace(' ', '-', $nom)); @endphp
                                        <div class="tab-pane @if($loop->first) active @endif" id="cat-{{ $tabId }}">
                                            <table class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                                   style="width:100%;" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th width="30%">Activité</th>
                                                        <th width="15%">Participation</th>
                                                        <th width="15%">Ratio</th>
                                                        <th width="15%">Pourcentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($categorieActivite as $nomActivite => $activite)
                                                    <tr>
                                                        <td><span class="font-weight-normal">{{ $nomActivite }}</span></td>
                                                        <td><span class="font-weight-normal">{{ $activite['nombreParticipation'] }}</span></td>
                                                        <td><span class="font-weight-normal">{{ $activite['ratio'] }}</span></td>
                                                        <td>
                                                            <span class="font-weight-normal">
                                                                @if($activite['nombreParticipation'])
                                                                    <i class="bi bi-check-circle-fill" style="color:#0d6516;" title="Présent"></i>
                                                                @else
                                                                    <i class="bi bi-x-circle-fill" style="color:#c0392b;" title="Absent"></i>
                                                                @endif
                                                                {{ $activite['stats'] }}%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            var $name = $("[data-fullname]").length ? $("[data-fullname]").attr("data-fullname") : "";
            var $fileName = 'TABLEAU DES STATISTIQUES DE ' + $name;
            $('.dataTable').DataTable({
                layout: {
                    topStart: {
                        buttons: [
                            { title: null, extend: 'csv',   filename: $fileName },
                            { title: null, extend: 'excel', filename: $fileName },
                            { title: null, extend: 'print', filename: $fileName }
                        ]
                    }
                },
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "order": [],
                responsive: true,
                language: datatable_fr
            });
        });
    </script>
@endsection