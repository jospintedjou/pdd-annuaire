@extends('layouts.app')
@section('page_title') Tableau de bord de <span class="text-primary">{{$zone->nom}}</span>  @endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Start Infos Membres -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <p class="card-category">Nombre de Membres</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}}</h4>

                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <!-- END Infos Membres -->

                <!-- Start Infos Membres -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <p class="card-category">Accompagnements Spirituels</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}} (100%)</h4>

                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <!-- END Infos Membres -->

                <!-- Start Infos Membres -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <p class="card-category">Centre de retraite</p>
                            <h4 class="card-title f-w-400">{{$nombreMembres}} (100%)</h4>
                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <!-- END Infos Membres -->

            </div>
            
            <div class="row">
                <!-- Présence -->
                <div class="col-xl-12 col-sm-6">
                    <div class="card mt-0 card-h-md">
                        <div class="card-header card-header-light">
                            <h4 class="card-title" style="color:#3c3c3b">Statistiques de présence {{$zone->nom}}</h4>
                        </div>
                        <div class="card-body table-responsive">

                            <table id="datatables"
                                   class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                   style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th width="20%">Activité</th>
                                    <th width="10%">Participation</th>
                                    <th width="10%">Total de séances</th>
                                    <th width="10%">Pourcentage</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(sizeof($categorieActivites))
                                    @foreach($categorieActivites as $nom => $categorieActivite)
                                        <tr>
                                            <td class="">
                                                <span class="font-weight-normal">{{$nom}}</span>
                                            </td>
                                            <td class="">
                                                <span class="font-weight-normal">
                                                    {{ $categorieActivite['nombreParticipation'] }}
                                                </span>
                                            </td>
                                            <td class="">
                                                <span class="font-weight-normal">

                                                    {{ $categorieActivite['nombreActivite'] }}

                                                </span>
                                            </td>
                                             <td class="">
                                                <span class="font-weight-normal">

                                                    {{ $categorieActivite['stats'] }}%

                                                </span>
                                            </td>

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
                <!-- END Présence -->
            </div>

            <!-- Start Stats detais -->
            <div class="row">
                <div class="card mt-0 card-h-md">
                    <div class="card-header card-header-light">
                        <h4 class="card-title" style="color:#3c3c3b">Statistiques détaillées {{$zone->nom}}</h4>
                    </div>
                    <div class="card-body">
                        <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper d-flex">
                                <ul  class="nav flex-column nav-pills text-center"
                                     id="v-pills-tab"
                                     role="tablist"
                                     aria-orientation="vertical">

                                    @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                    <li class="nav-item0">
                                        <a class="nav-link @if($loop->first) active @endif" href="#{{trim($nom)}}" data-toggle="tab">{{$nom}}</a>
                                    </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content border rounded p-3 w-100">
                                    @if(sizeof($categorieActivitesDetails))
                                        @foreach($categorieActivitesDetails as $nom => $categorieActivite)
                                            <div class="tab-pane @if($loop->first) active @endif" id="{{trim($nom)}}">
                                                <p>
                                                <table
                                                       class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                                       style="width: 100%;" width="100%" cellspacing="0">
                                                    <thead>
                                                    <tr>
                                                        <th width="20%">Activité</th>
                                                        <th width="10%">Participation</th>
                                                        <th width="10%">Total de séances</th>
                                                        <th width="10%">Pourcentage</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($categorieActivite as $nom => $activite)
                                                        <tr>
                                                            <td class="">
                                                                <span class="font-weight-normal">{{$nom}}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="font-weight-normal">
                                                                    {{ $activite['nombreParticipation'] }}
                                                                </span>
                                                            </td>
                                                            <td class="">
                                                                <span class="font-weight-normal">
                                                                    {{ $activite['nombreActivite'] }}
                                                                </span>
                                                            </td>
                                                            <td class="">
                                                                <span class="font-weight-normal">
                                                                    {{ $activite['stats'] }}%
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Stats detais -->
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.dataTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [],
                responsive: true,
                language: datatable_fr
            });
        });
    </script>
@endsection
