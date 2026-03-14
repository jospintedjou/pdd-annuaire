@extends('layouts.app')
@section('page_title') Tableau de bord de <span class="text-primary">{{$groupe->nom_groupe}}</span>  @endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Nombre de membres -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Nombre de Membres</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}}</h4>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <!-- Accompagnements Spirituels -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Accompagnements Spirituels</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}} (100%)</h4>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <!-- Centre de retraite -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <p class="card-category">Centre de retraite</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}} (100%)</h4>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <!-- Summary table -->
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card mt-0 card-h-md">
                        <div class="card-header card-header-light">
                            <h4 class="card-title" style="color:#3c3c3b">Statistiques de présence — {{$groupe->nom_groupe}}</h4>
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
                        <h4 class="card-title" style="color:#3c3c3b">Statistiques détaillées — {{$groupe->nom_groupe}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper d-flex">
                                <!-- Left pill navigation: one pill per category -->
                                <ul class="nav flex-column nav-pills text-center"
                                    id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                        <li class="nav-item0">
                                            <a class="nav-link @if($loop->first) active @endif"
                                               href="#cat-{{trim(str_replace(' ', '-', $nom))}}"
                                               data-toggle="tab">{{ $nom }}</a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 w-100">
                                    @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                        @php $tabId = trim(str_replace(' ', '-', $nom)); @endphp
                                        <div class="tab-pane @if($loop->first) active @endif" id="cat-{{ $tabId }}">

                                            <!-- Sub-tabs: En général / Par membre -->
                                            <ul class="nav nav-pills mb-3" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="pill"
                                                       href="#general-{{ $tabId }}" role="tab">En général</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="pill"
                                                       href="#membre-{{ $tabId }}" role="tab">Par membre</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content mt-3">
                                                <!-- Tab 1: En général -->
                                                <div class="tab-pane active" id="general-{{ $tabId }}" role="tabpanel">
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
                                                                <td><span class="font-weight-normal">{{ $activite['stats'] }}%</span></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Tab 2: Par membre -->
                                                <div class="tab-pane" id="membre-{{ $tabId }}" role="tabpanel">
                                                    @if(isset($categorieActivitesMembres[$nom]) && count($categorieActivitesMembres[$nom]))
                                                        @php
                                                            $memberStats   = $categorieActivitesMembres[$nom];
                                                            $activityNames = array_keys($memberStats[0]['activities'] ?? []);
                                                        @endphp
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                                                   style="width:100%;" cellspacing="0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Membre</th>
                                                                        <th>Ratio</th>
                                                                        <th>%</th>
                                                                        @foreach($activityNames as $actName)
                                                                            <th title="{{ $actName }}">{{ Str::limit($actName, 20) ?: 'N/A' }}</th>
                                                                        @endforeach
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($memberStats as $member)
                                                                    <tr>
                                                                        <td><span class="font-weight-normal">{{ $member['nom'] }}</span></td>
                                                                        <td><span class="font-weight-normal">{{ $member['ratio'] }}</span></td>
                                                                        <td><span class="font-weight-normal">{{ $member['stats'] }}%</span></td>
                                                                        @foreach($activityNames as $actName)
                                                                            <td class="text-center">
                                                                                @if($member['activities'][$actName] ?? false)
                                                                                    <i class="bi bi-check-circle-fill" style="color:#0d6516;" title="Présent"></i>
                                                                                @else
                                                                                    <i class="bi bi-x-circle-fill" style="color:#c0392b;" title="Absent"></i>
                                                                                @endif
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="text-muted">Aucune donnée disponible pour cette catégorie.</p>
                                                    @endif
                                                </div>
                                            </div>

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
            $fileName = 'TABLEAU DES STATISTIQUES DE GROUPE';
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
