@extends('layouts.app')
@section('page_title') Utilisateur @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">person</i>
                        </div>
                        <h4 class="card-title">Liste des utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
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
                                                <th>Nom</th>
                                                <th>Zone</th>
                                                <th>Sous-zone</th>
                                                <th>Groupe</th>
                                                <th>Date d'inscr.</th>
                                                <th>Niveau d'engagement</th>
                                                <th class="disabled-sorting text-right sorting">
                                                    Actions
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                @if($user)
                                                <tr>
                                                <td class="">{{$user->prenom}} {{$user->nom}}</td>
                                                <td class="">
                                                    <?php //dd($user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()) ?>
                                                    {{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone()->first()->zone()->first()->nom }}
                                                </td>
                                                <td class="">{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone()->first()->nom }}</td>
                                                <td class="">{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->nom_groupe }}</td>
                                                <td class="">{{$user->created_at}}</td>
                                                <td class="">{{  $user->niveauEngagement()->first()->nom }}</td>
                                                <td class="td-actions text-right">
                                                    <form action="{{ route('users.destroy',$user->id) }}" method="Post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{route('users.edit', ['user' =>$user->id])}}" type="button" rel="tooltip"
                                                           class="btn btn-success btn-round" data-original-title="" title="modifier">
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-danger btn-round text-white" data-href="{{ route('users.destroy',$user->id) }}"
                                                                data-id="{{ $user->id }}"
                                                                data-toggle="modal" data-target="#confirm-delete">
                                                            <i class="material-icons">close</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </form>
                                                </td>
                                            </tr>
                                                @endif
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
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            // Setup - add a text input to each footer cell
            $('.dataTable thead th').each(function () {
                var title = $(this).text();
                $(this).append('<input type="text" placeholder="Search ' + title + '" />');
            });

            // DataTable
            var table = $('.dataTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [[ 4, "desc" ]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                },
                initComplete: function () {
                    // Apply the search
                    this.api()
                            .columns()
                            .every(function () {
                                var that = this;

                                $('input', this.header()).on('keyup change clear', function () {
                                    if (that.search() !== this.value) {
                                        that.search(this.value.replace("/;/g", "&quot;|&quot;"), true, false).draw();
                                        //that.search(this.value).draw();
                                    }
                                });
                            });
                }
            });

            // Apply the search
            /*
            * mytable.columns().eq(0).each(function (colIdx) {
                 $('input', mytable.column(colIdx).footer()).on('keyup change', function () {
                     mytable.column (colIdx)
                              .search (this.value.replace(/;/g, &quot;|&quot;), true, false)
                              .draw ();
                 } );
             } );
            * */
            /*table.columns().eq( 0 ).each( function ( colIdx ) {
                $( 'input', table.column( colIdx ).header() ).on( 'keyup change', function () {
                    table
                            .column( colIdx )
                            .search( this.value )
                            .draw();
                } );
            } );*/
        });

        /*$(document).ready(function () {
            //console.log($('#datatables').html());
            $('.dataTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [[ 4, "desc" ]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });

            //var table = $('#datatable').DataTable();

            // Edit record
            table.on('click', '.edit', function () {
                $tr = $(this).closest('tr');
                var data = table.row($tr).data();
                alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
            });

            // Delete a record
            table.on('click', '.remove', function (e) {
                $tr = $(this).closest('tr');
                table.row($tr).remove().draw();
                e.preventDefault();
            });

            //Like record
            table.on('click', '.like', function () {
                alert('You clicked on Like button');
            });
        });
        */

    </script>
@endsection
