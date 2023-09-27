@extends('layouts.app')
@section('page_title') Activit&eacute; @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">activity</i>
                        </div>
                        <h4 class="card-title">Liste des activit&eacute;s</h4>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <!--   Here you can write extra buttons/actions for the toolbar      -->
                        </div>
                        <div class="material-datatables">
                            <div id="datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="datatables"
                                               class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
                                               style="width: 100%;" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>Activité</th>
                                                <!--th>Categorie</th-->
                                                <th>Concern&eacute;s</th>
                                                <th>Date Debut</th>
                                                <th>Date Fin</th>
                                                <th>Lieu</th>
                                                <th>Heure debut</th>
                                                <th class="disabled-sorting text-right sorting">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($activites as $activite)
                                            <tr>
                                                <td class="">{{$activite->nom}}</td>
                                                <!--td class="">{{--$activite->categorieActivite->nom--}}</td-->
                                                <td class="">
                                                    @if ($activite->zone_id)
                                                    {{$activite->zone->nom}}
                                                    @elseif ($activite->sous_zone_id)
                                                    {{$activite->sousZone->nom}}
                                                    @else
                                                    Groupe de {{$activite->groupe->nom_groupe}}
                                                    @endif
                                                </td>
                                                <td class="">{{$activite->date_debut}}</td>
                                                <td class="">{{$activite->date_fin}}</td>
                                                <td class="">{{$activite->lieu}}</td>
                                                <td class="">{{$activite->heure_debut}}</td>
                                                <td class="td-actions text-right">
                                                    <form action="{{ route('activites.destroy',$activite->id) }}" method="Post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{route('activites.edit', ['activite' =>$activite->id])}}" type="button" rel="tooltip"
                                                           class="btn btn-success btn-round" data-original-title="" title="modifier">
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-danger btn-round text-white"
                                                                data-id="{{ $activite->id }}"
                                                                data-href="{{ route('activites.destroy',$activite->id) }}"
                                                                data-toggle="modal" data-target="#confirm-delete">
                                                            <i class="material-icons">close</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation de la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Etes-vous sûr de vouloir supprimer cet élément?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-ok">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            //console.log($('.dataTable').html());
            $('.dataTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [[ 0, "desc" ]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });
        });
    </script>
@endsection
