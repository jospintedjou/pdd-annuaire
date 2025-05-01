@extends('layouts.app')
@section('page_title') Responsables de pays @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">supervisor_account</i>
                        </div>
                        <h4 class="card-title">Liste des responsables des pays</h4>
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
                                                <th>N°</th>
                                                <th>Pays</th>
                                                <th>Zone</th>
                                                <th>Sous-zone</th>
                                                <th>Responsables</th>
                                                <th class="disabled-sorting text-right sorting">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($paysArr as $pays)
                                            <tr>
                                                <td class="">{{$loop->index + 1}}</td>
                                                <td class="">{{$pays->nom}}</td>
                                                <td class="">{{$pays->sousZone?->zone?->nom}}</td>
                                                <td class="">{{$pays->sousZone?->nom}}</td>
                                                <td class="">
                                                    @foreach($pays->responsablePays()->where('actif', \App\Constantes::ETAT_ACTIF)->cursor() as $responsablePays)
                                                        {{$responsablePays->nom}} {{$responsablePays->prenom}}
                                                        @php
                                                        $responsabilite = \App\Models\Responsabilite::find($responsablePays->pivot->responsabilite_id);
                                                        @endphp

                                                        ({{ !empty($responsabilite) ? $responsabilite->nom : "" }})
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="td-actions text-right">
                                                    <form action="{{-- route('responsable_pays.destroy',$pays->id) --}}" method="Post">
                                                        @csrf
                                                        @method('DELETE')
                                                        {{--dd(auth()->user()?->isResponsableZone())--}}
                                                        @if(auth()->user()->isAdmin() || (auth()->user()?->isResponsableZone() && auth()->user()?->isResponsableZone()?->id == $pays->zone_id))
                                                        <a href="{{route('responsable_pays.edit', ['pays' =>$pays->id])}}" type="button" rel="tooltip"
                                                           class="btn btn-success btn-round" data-original-title="" title="modifier">
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        @endif
                                                        <!-- Button trigger modal -->
                                                        <!--button type="button" class="btn btn-danger btn-round text-white"
                                                                data-id="{{-- $pays->id }}"
                                                                data-href="{{-- route('responsable_pays.destroy',$pays->id) --}}"
                                                                data-toggle="modal" data-target="#confirm-delete">
                                                            <i class="material-icons">close</i>
                                                            <div class="ripple-container"></div>
                                                        </button-->
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
            $fileName = 'LISTE DES RESPONSABLES DE PAYS';
            $('.dataTable').DataTable({
                layout: {
                    topStart: {
                        buttons: [
                            {
                                title: null,
                                extend: 'csv',
                                filename: $fileName,
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                }
                            },
                            {
                                title: null,
                                extend: 'excel',
                                filename: $fileName,
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                }
                            },
                            {
                                title: null,
                                extend: 'print',
                                filename: $fileName,
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                }
                            }
                        ]
                    }
                },
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [[ 0, "asc" ]],
                responsive: true,
                language: datatable_fr
            });
        });
    </script>
@endsection
