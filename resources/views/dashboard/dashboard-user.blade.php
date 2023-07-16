@extends('layouts.app')
@section('page_title') Tableau de bord @endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- BO Membres -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats card-ht">
                        <div class="card-header card-header-green card-header-icon">
                            <div class="card-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <p class="card-category">Membres</p>
                            <h4 class="card-title f-w-400">1</h4>

                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
                <!-- END BO Membres -->

            </div>
            
            <div class="row">
                <!-- Présence -->
                <div class="col-xl-8 col-sm-6">
                    <div class="card mt-0 card-h-md">
                        <div class="card-header card-header-light">
                            <h4 class="card-title" style="color:#3c3c3b">Statistiques de présence</h4>
                        </div>
                        <div class="card-body table-responsive">

                            <table id="datatables"
                                   class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                   style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th width="20%">Activité</th>
                                    <th width="10%">Sessions</th>
                                    <th width="10%">Participation</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categorieActivites as $categorieActivite)
                                    <tr>
                                        <td class="">
                                            <span class="font-weight-normal">{{$categorieActivite->nom}}</span>
                                        </td>
                                        <td class="">
                                            <span class="font-weight-normal">
<?php $type = 1; ?>
                                                {{
                                                $categorieActivite->activites()
                                                ->where('categorie_activite_id', $categorieActivite->id)
                                                ->count()
                                                }}

                                                {{--
                                                $categorieActivite->activites()
                                                ->where('categorie_activite_id', $categorieActivite->id)
                                                ->where(['type_activite'=>\App\Constantes::ACTIVITE_REGIONALE])
                                                ->orWhere(['type_activite'=>\App\Constantes::ACTIVITE_ZONALE, 'zone_id'=>$user->zone()->first()->id])
                                                ->orWhere(['type_activite'=>\App\Constantes::ACTIVITE_SOUS_ZONALE, 'sous_zone_id'=>$user->groupeActif()->sousZone()->first()->id])
                                                ->orWhere(['type_activite'=>\App\Constantes::ACTIVITE_GROUPE, 'groupe_id'=>$user->groupeActif()->first()->id])
                                                ->count()
                                                --}}

                                            </span>
                                        </td>
                                         <td class="">
                                            <span class="font-weight-normal">
                                                {{
                                                $user->activites()
                                                    ->where('categorie_activite_id', $categorieActivite->id)
                                                    ->count()
                                                }}
                                            </span>
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

                <!-- OFFRES EMPLOI -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats ">
                        <div class="card-header card-header-primary card-header-icon">
                            <div class="card-icon">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <p class="card-category">Offres d'emplois</p>
                            <h4 class="card-title f-w-400">10</h4>
                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
                <!--End OFFRES EMPLOI -->
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