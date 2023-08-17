@extends('layouts.app')
@section('page_title') Tableau de bord des <span class="text-primary">Groupes</span>  @endsection
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
                            <p class="card-category">Nombre Total de Membres</p>
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
                            <h4 class="card-title" style="color:#3c3c3b">LIste des groupes</h4>
                        </div>
                        <div class="card-body table-responsive">

                            <table id="datatables"
                                   class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                   style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th width="20%">Groupes</th>
                                    <th width="20%">Zone</th>
                                    <th width="10%">Membres</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($groupes as $groupe)
                                    <tr>
                                        <td class="">
                                            <span class="font-weight-normal">{{$groupe->nom_groupe}}</span>
                                        </td>
                                        <td class="">
                                            <span class="font-weight-normal">{{$groupe->sousZone->zone->nom}}</span>
                                        </td>
                                        <td class="">
                                            <span class="font-weight-normal">
                                              {{$groupe->users()->count()}}
                                            </span>
                                        </td>
                                        <td class="td-actions text-right">
                                            <a href="{{route('statistiques_groupe', ['groupe' =>$groupe->id])}}" type="button" rel="tooltip"
                                               class="btn btn-primary btn-round" data-original-title="" title="statistiques">
                                                <i class="material-icons">bar_chart</i>
                                                <div class="ripple-container"></div>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
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
