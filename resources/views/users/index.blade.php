@extends('layouts.app')
@section('page_title') Utilisateur @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">people</i>
                        </div>
                        <h4 class="card-title">Liste des utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            {{-- ── Import result banner ── --}}
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                                <i class="material-icons me-2" style="font-size:20px;vertical-align:middle">check_circle</i>
                                <span>{{ $message }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                            </div>

                            @php
                                $duplicatedRows = Session::get('duplicatedRows', []);
                                $skippedRows    = Session::get('skippedRows', []);
                            @endphp

                            @if(count($duplicatedRows) > 0 || count($skippedRows) > 0)
                            <div class="row g-3 mb-3">

                                {{-- ── Doublons card ── --}}
                                @if(count($duplicatedRows) > 0)
                                <div class="{{ count($skippedRows) > 0 ? 'col-md-6' : 'col-12' }}">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header d-flex align-items-center justify-content-between py-2"
                                             style="background:#fff3cd;border-left:4px solid #ffc107;">
                                            <span class="fw-semibold text-warning-emphasis">
                                                <i class="material-icons align-middle me-1" style="font-size:18px">content_copy</i>
                                                Déjà existants
                                            </span>
                                            <span class="badge rounded-pill" style="background:#ffc107;color:#333;">
                                                {{ count($duplicatedRows) }}
                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            <div style="max-height:220px;overflow-y:auto;">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width:40px">#</th>
                                                            <th>Nom</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($duplicatedRows as $i => $name)
                                                        <tr>
                                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                                            <td>{{ $name }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- ── Lignes ignorées card ── --}}
                                @if(count($skippedRows) > 0)
                                <div class="{{ count($duplicatedRows) > 0 ? 'col-md-6' : 'col-12' }}">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header d-flex align-items-center justify-content-between py-2"
                                             style="background:#f8d7da;border-left:4px solid #dc3545;">
                                            <span class="fw-semibold text-danger-emphasis">
                                                <i class="material-icons align-middle me-1" style="font-size:18px">block</i>
                                                Lignes ignorées
                                            </span>
                                            <span class="badge rounded-pill bg-danger">
                                                {{ count($skippedRows) }}
                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            <div style="max-height:220px;overflow-y:auto;">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width:50px">Ligne</th>
                                                            <th>Nom</th>
                                                            <th>Raison</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($skippedRows as $row)
                                                        <tr>
                                                            <td class="text-muted small">{{ $row['row'] }}</td>
                                                            <td>{{ $row['name'] }}</td>
                                                            <td class="text-danger small">{{ $row['reason'] }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            @endif

                        @elseif($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p>{{ $message }}</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            @if($failures = Session::get('failures'))
                                @if($failures->isNotEmpty())
                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-header d-flex align-items-center justify-content-between py-2"
                                             style="background:#f8d7da;border-left:4px solid #dc3545;">
                                            <span class="fw-semibold text-danger-emphasis">
                                                <i class="material-icons align-middle me-1" style="font-size:18px">error_outline</i>
                                                Erreurs de validation
                                            </span>
                                            <span class="badge rounded-pill bg-danger">{{ $failures->count() }}</span>
                                        </div>
                                        <div class="card-body p-0">
                                            <div style="max-height:220px;overflow-y:auto;">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width:60px">Ligne</th>
                                                            <th>Champ</th>
                                                            <th>Erreur</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($failures as $failure)
                                                        <tr>
                                                            <td class="text-muted small">{{ $failure->row() }}</td>
                                                            <td class="small">{{ $failure->attribute() }}</td>
                                                            <td class="text-danger small">{{ implode(', ', $failure->errors()) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
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
                                                <!--th width="5%">N°</th-->
                                                <th width="20%">Nom</th>
                                                <th width="20%">Zone</th>
                                                <th width="15%">Groupe</th>
                                                <th width="20%">Catégorie Sociale</th>
                                                <th width="10%">Niveau d'engagement</th>
                                                <th width="10%" class="disabled-sorting text-right sorting">
                                                    Actions
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{--  foreach($users as $user)
                                                @if($user)
                                                    <tr>
                                                        <td class="">{{$loop->index + 1}}</td>
                                                        <td class="">{{$user->nom}} {{$user->prenom}}</td>
                                                        <td class="">
                                                            {{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()?->sousZone()?->first()?->zone()?->first()?->nom }}
                                                        </td>
                                                        <td class="">{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)?->first()?->nom_groupe }}</td>
                                                        <td class="">   </td>
                                                        <td class="">{{  $user->niveauEngagement()?->first()?->nom }}</td>
                                                        <td class="td-actions text-right">
                                                            <form action="{{ route('users.destroy',$user->id) }}"
                                                                  method="Post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="{{route('statistiques_membre', ['user' =>$user->id])}}"
                                                                   type="button" rel="tooltip"
                                                                   class="btn btn-primary btn-round"
                                                                   data-original-title="" title="statistiques">
                                                                    <i class="material-icons">bar_chart</i>

                                                                    <div class="ripple-container"></div>
                                                                </a>
                                                                <a href="{{route('users.edit', ['user' =>$user->id])}}"
                                                                   type="button" rel="tooltip"
                                                                   class="btn btn-success btn-round"
                                                                   data-original-title="" title="modifier">
                                                                    <i class="material-icons">edit</i>

                                                                    <div class="ripple-container"></div>
                                                                </a>
                                                                <!-- Button trigger modal -->
                                                                @if(auth()->user()->isAdmin())
                                                                    <button type="button"
                                                                            class="btn btn-danger btn-round text-white"
                                                                            data-href="{{ route('users.destroy',$user->id) }}"
                                                                            data-id="{{ $user->id }}"
                                                                            data-toggle="modal"
                                                                            data-target="#confirm-delete">
                                                                        <i class="material-icons">close</i>

                                                                        <div class="ripple-container"></div>
                                                                    </button>
                                                                @endif

                                                            </form>
                                                        </td>
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
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
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
            $fileName = 'TABLEAU DES STATISTIQUES GLOBALES DES MEMBRES';

            // Setup - add a text input to each footer cell
            $('.dataTable thead th:not(:last)').each(function () {
                var title = $(this).text();
                $(this).append('<br/><input style="width:100%" type="text" placeholder="Rechercher par ' + title + '" />');
            });

            // DataTable
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('users.data') !!}",
                columnDefs: [
                    { targets: -1, className: 'td-actions text-right' } //add class in last td (actions) for button style
                ],
                columns: [
                    //{ data: 'N°', name: 'N°' },
                    { data: 'nom', name: 'nom' },
                    { data: 'zone', name: 'zone' },
                    { data: 'groupe', name: 'groupe' },
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
                                    window.location.href = "{!! route('users.export') !!}";
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
