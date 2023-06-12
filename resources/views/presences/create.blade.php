@extends('layouts.app')
@section('page_title') Fiche de présence {{$activite->nom}} @endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">person</i>
                        </div>
                        <h4 class="card-title">Fiche de présence {{$activite->nom}}</h4>
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
                                               data-url="{{route('presences.store')}}"
                                               data-activite_id="{{$activite->id}}"
                                               class="table table-no-bordered dataTable dtr-inline"
                                               style="width: 100%;" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <!--th>Zone</th-->
                                                <!--th>Sous-zone</th-->
                                                <th>Groupe</th>
                                                <th>Niveau d'engagement</th>
                                                <th>Heure</th>
                                                <th class="disabled-sorting text-right sorting">
                                                    Présent?
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($users as $user)
                                                <tr class="@if($user->activite()->where('activite_id', $activite->id)->exists()) presence-active @endif" data-user_id="{{$user->id}}">
                                                    <td class="">
                                                        {{$user->prenom}} {{$user->nom}}</td>
                                                    <!--td class="">{{-- $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->sousZone()->first()->nom --}}</td-->
                                                    <td class="">{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()->nom_groupe }}</td>
                                                    <td class="">{{  $user->niveauEngagement()->first()->nom }}</td>
                                                    <td class="">
                                                        <div class="form-check">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control timepicker heure_arrivee"
                                                                       step="3600" min="00:00" max="23:59" pattern="[0-2][0-9]:[0-5][0-9]"
                                                                       value="@if($user->activite()->where('activite_id', $activite->id)->exists())
                                                                               {{$user->activite()->where('activite_id', $activite->id)->first()->pivot->heure_arrivee}}
                                                                               @else
                                                                                {{\Carbon\Carbon::now()}}
                                                                               @endif"/>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="td-actions text-right">
                                                        <div class="form-check">
                                                            <div class="form-group">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input presence-input" type="checkbox" value="1"
                                                                           @if($user->activite()->where('activite_id', $activite->id)->exists()) checked @endif>
                                                                <span class="form-check-sign">
                                                                    <span class="check"></span>
                                                                </span>
                                                                </label>
                                                            </div>
                                                        </div>
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
                "order": [[4, "desc"]],
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
