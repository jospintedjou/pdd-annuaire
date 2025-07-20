@extends('layouts.app')
@section('page_title') Présence aux activités @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">checkdate</i>
                        </div>
                        <h4 class="card-title">Présence aux activités</h4>
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
                                                <th>Categorie</th>
                                                <th>Activité</th>
                                                <th>Concernés</th>
                                                <th>Date Debut</th>
                                                <th>Date Fin</th>
                                                <th>Heure debut</th>
                                                <th class="disabled-sorting text-right sorting">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
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
            $fileName = 'LISTE DES ACTIVITES';

            // Setup - add a text input to each footer cell
            $('.dataTable thead th:not(:last)').each(function () {
                var title = $(this).text();
                $(this).append('<br/><input style="width:100%" type="text" placeholder="Rechercher par ' + title + '" />');
            });

            // DataTable
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('presences.activites.data') }}",
                columnDefs: [
                    { targets: -1, className: 'td-actions text-right' } //add class in last td (actions) for button style
                ],
                columns: [
                    //{ data: 'N°', name: 'N°' },
                    { data: 'categorie', name: 'categorie' },
                    { data: 'nom', name: 'nom' },
                    { data: 'concernes', name: 'concernes' },
                    { data: 'date_debut', name: 'date_debut' },
                    { data: 'date_fin', name: 'date_fin' },
                    { data: 'heure_debut', name: 'heure_debut' },
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
                                    window.location.href = "{{ route('presences.activites.export') }}";
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
