@extends('layouts.app')
@section('page_title') Membres de {{$groupe->nom_groupe}} @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">person</i>
                        </div>
                        <h4 class="card-title">Liste des membres du groupe {{$groupe->nom_groupe}}</h4>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <div class="toolbar">
                            <!--  Here you can write extra buttons/actions for the toolbar              -->
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
                                                <th width="5%">N°</th>
                                                <th width="10%">Nom</th>
                                                <!--th width="10%">Zone</th-->
                                                <th width="10%">Sous-zone</th>
                                                <th width="10%">Groupe</th>
                                                <!--th>Date d'inscr.</th-->
                                                <th width="10%">Profession</th>
                                                <th width="10%">Spécialité</th>
                                                <th width="10%">Catégorie Soc.</th>
                                                <th width="10%">Niveau d'enga.</th>
                                                <th width="10%">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- 
                                            @foreach($users as $user)
                                                @if($user)
                                                <tr>
                                                <td class="">{{$loop->index + 1}}</td>
                                                <td class="">{{$user->nom}} {{$user->prenom}}</td>
                                                <td class="">
                                                    {{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone()->first()->zone()->first()->nom }}
                                                </td>
                                                <td class="">{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->nom_groupe }}</td>
                                                <td class="">{{  $user->categorie_sociale }}</td>
                                                <td class="">{{  $user->niveauEngagement()->first()->nom }}</td>
                                            </tr>
                                                @endif
                                            @endforeach
                                             --}}
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
            $fileName = "LISTE DES MEMBRES DU GROUPE {{$groupe->nom_groupe}}";
            // Setup - add a text input to each footer cell
            $('.dataTable thead th:not(:last)').each(function () {
                var title = $(this).text();
                $(this).append('<br/><input style="width:100%" type="text" placeholder="Rechercher par ' + title + '" />');
            });

            // DataTable
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
               ajax: {
                    url: "{!! route('groupes.users.data') !!}",
                    data: function (d) {
                        d.groupe_id = "{{$groupe->id}}";
                    }
                },
                columnDefs: [
                    { targets: -1, className: 'td-actions text-right' }
                ],
                columns: [
                     {
                        data: null,
                        name: 'index',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'nom', name: 'nom' },
                    /*{ data: 'zone', name: 'zone' },*/
                    { data: 'sous-zone', name: 'sous-zone' },
                    { data: 'groupe', name: 'groupe' },
                    { data: 'profession', name: 'profession' },
                    { data: 'specialite', name: 'specialite' },
                    { data: 'categorie_sociale', name: 'categorie_sociale' },
                    { data: "niveau_engagement", name: "niveau_engagement" },
                    { data: 'actions', name: 'actions' },
                ],
                paging: true,
                layout: {
                    topStart: {
                        buttons: [
                            {
                                text: '<i class="material-icons">file_download</i> Exporter Excel',
                                className: 'btn btn-success btn-round',
                                filename: $fileName,
                                action: function () {
                                    window.location.href = "{!! route('groupes.users.export', ['groupe_id' => $groupe->id]) !!}";
                                }
                            },
                            /*{
                                title: null,
                                extend: 'excel',
                                filename: $fileName,
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                }
                            },*/
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
                    [50, 100, 150, -1],
                    [50, 100, 150, "All"]
                ],
                "order": [[1, "asc"]],
                responsive: true,
                language: datatable_fr,
                initComplete: function () {
                    // Apply the search
                    this.api()
                            .columns()
                            .every(function () {
                                var that = this;

                                $('input', this.header()).on('keyup change clear', function () {
                                    if (that.search() !== this.value) {
                                        console.log('searching...', this.value);
                                        that.search(this.value.replace("/;/g", "&quot;|&quot;"), true, false).draw();
                                        //that.search(this.value).draw();
                                    }
                                });
                            });
                }
            });
        });
</script>

@endsection
