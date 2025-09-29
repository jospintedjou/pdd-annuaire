@extends('layouts.app')
@section('page_title') Fiche de présence {{$activite->nom}} @endsection

@section('css')
<style>
    .presence-active {
        background-color: #d4edda !important;
        border-color: #c3e6cb !important;
    }
    .presence-active td {
        background-color: #d4edda !important;
    }
    .processing {
        opacity: 0.7;
        position: relative;
    }
    .processing::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.5);
        z-index: 1;
    }
    #notification-area {
        position: relative;
        z-index: 1050;
    }
</style>
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">      <div class="card-header card-header-primary card-header-icon">
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
                            <button type="button" class="btn btn-danger btn-round text-white"
                                    data-href="{{ route('users.store',[]) }}"
                                    data-toggle="modal" data-target="#add-user">
                                <i class="material-icons">add</i>Ajouter un nouveau
                                <div class="ripple-container"></div>
                            </button>
                            <button type="button" id="reload-table-btn" class="btn btn-info btn-round text-white ml-2"
                                    title="Actualiser la liste">
                                <i class="material-icons">refresh</i>Actualiser
                                <div class="ripple-container"></div>
                            </button>
                        </div>
                        
                        <!-- Notification area -->
                        <div id="notification-area" style="display: none;" class="alert alert-dismissible fade show mt-2" role="alert">
                            <span id="notification-message"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
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
                                                <th>N°</th>
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
                                                <!-- Data will be loaded via AJAX -->
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
    <div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un membre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form method="post" action="{!! route('users.store') !!}">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card pb-30">
                                        <div class="card-header card-header-primary card-header-text">
                                            <div class="card-text">
                                                <h4 class="card-title">Ajouter un utilisateur</h4>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="container">
                                                <input type="hidden" name="activite_id" value="{{$activite->id}}">
                                                <input type="hidden" name="to" value="{{route('presences.create', ['activite_id' =>$activite->id])}}">
                                                <div class="row">
                                                    <div class="form-group col-md-5 @error('nom') has-danger @enderror">
                                                        <label for="nom" class="bmd-label-floating @error('nom') text-danger @enderror">Nom</label>
                                                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror">
                                                        @error('nom')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4 @error('prenom') has-danger @enderror">
                                                        <label for="prenom" class="bmd-label-floating @error('prenom') text-danger @enderror">Prenom</label>
                                                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" class="form-control @error('prenom') is-invalid @enderror">
                                                        @error('prenom')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-3  col-xs-3 @error('sexe') has-danger @enderror">
                                                        <label for="sexe" class="bmd-label-floating @error('sexe') text-danger @enderror">Sexe</label>
                                                        <select name="sexe" id="sexe" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir le sexe">
                                                            <option value="{{\App\Constantes::SEXE_MASCULIN}}">Homme</option>
                                                            <option value="{{\App\Constantes::SEXE_FEMININ}}">Femme</option>
                                                        </select>
                                                        @error('sexe')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div><!-- row -->

                                                <div class="row">
                                                    <div class="form-group col-md-5 @error('adresse') has-danger @enderror">
                                                        <label for="adresse" class="bmd-label-floating @error('adresse') text-danger @enderror">Adresse</label>
                                                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" class="form-control @error('adresse') is-invalid @enderror">
                                                        @error('adresse')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4 @error('telephone1') has-danger @enderror">
                                                        <label for="telephone1" class="bmd-label-floating @error('telephone1') text-danger @enderror">Telephone 1</label>
                                                        <input type="text" name="telephone1" id="telephone1" value="{{ old('telephone1') }}" class="form-control @error('telephone1') is-invalid @enderror">
                                                        @error('telephone1')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-3 @error('telephone2') has-danger @enderror">
                                                        <label for="telephone2" class="bmd-label-floating @error('telephone2') text-danger @enderror">Telephone 2</label>
                                                        <input type="text" name="telephone2" id="telephone2" value="{{ old('telephone2') }}" class="form-control @error('telephone2') is-invalid @enderror">
                                                        @error('telephone2')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div><!-- row -->

                                                <div class="row">
                                                    <div class="form-group col-md-5 @error('email') has-danger @enderror">
                                                        <label for="email" class="bmd-label-floating @error('email') text-danger @enderror">Email</label>
                                                        <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4 @error('quartier') has-danger @enderror">
                                                        <label for="quartier" class="bmd-label-floating @error('quartier') text-danger @enderror">Quartier</label>
                                                        <input type="text" name="quartier" id="quartier" value="{{ old('quartier') }}" class="form-control @error('quartier') is-invalid @enderror">
                                                        @error('quartier')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-3 @error('profession') has-danger @enderror">
                                                        <label for="profession" class="bmd-label-floating @error('profession') text-danger @enderror">Profession</label>
                                                        <input type="text" name="profession" id="profession" value="{{ old('profession') }}" class="form-control @error('profession') is-invalid @enderror">
                                                        @error('profession')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                </div><!-- row -->

                                                <div class="row">
                                                    <div class="form-group col-md-4 @error('categorie_sociale') has-danger @enderror">
                                                        <label for="categorie_sociale" class="bmd-label-floating col-12 @error('categorie_sociale') text-danger @enderror">Categorie sociale</label>
                                                        <select name="categorie_sociale" id="categorie_sociale" class="selectpicker col-12" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir">
                                                            @foreach (\App\Constantes::CATEGORIE_SOCIALES as $categorie_sociale)
                                                                @if(isset($categorie_sociale))
                                                                    <option value="{{ $categorie_sociale }}">{{$categorie_sociale}}</option>
                                                                @else
                                                                    <option  selected disabled>Aucune categorie sociale trouvée</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('categorie_sociale')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4 @error('apostolat_id') has-danger @enderror">
                                                        <label for="apostolat_id" class="bmd-label-floating col-12 @error('apostolat_id') text-danger @enderror">Apostolat</label>
                                                        <select name="apostolat_id[]" id="apostolat_id" class="selectpicker col-12" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir l'apostolat" multiple>
                                                            @foreach ($apostolats as $apostolat)
                                                                @if(isset($apostolat))
                                                                    <option value="{{ $apostolat->id }}">{{$apostolat->nom}}</option>
                                                                @else
                                                                    <option  selected disabled>Aucun apostolat trouvé</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('apostolat_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4 @error('niveau_engagement_id') has-danger @enderror">
                                                        <label for="niveau_engagement_id" class="bmd-label-floating col-12 @error('niveau_engagement_id') text-danger @enderror">Niveau d'engagement</label>
                                                        <select name="niveau_engagement_id" id="niveau_engagement_id" class="selectpicker col-12" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir">
                                                            @foreach ($niveau_engagements as $niveau_engagement)
                                                                @if(isset($niveau_engagement))
                                                                    <option value="{{ $niveau_engagement->id }}">{{$niveau_engagement->nom}}</option>
                                                                @else
                                                                    <option  selected disabled>Aucun niveau d'engagement trouvé</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('niveau_engagement_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div><!-- row -->

                                                <div class="row">

                                                    <div class="form-group col-md-4 @error('groupe_id') has-danger @enderror">
                                                        <label for="groupe_id" class="bmd-label-floating @error('groupe_id') text-danger @enderror">Groupe</label>

                                                        <select name="groupe_id" id="groupe_id" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                                            @foreach ($groupes as $groupe)
                                                                @if(isset($groupe))
                                                                    <option value="{{ $groupe->id }}">{{$groupe->nom_groupe}}</option>
                                                                @else
                                                                    <option  selected disabled>Aucun groupe trouvé</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('groupe_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4 @error('password') has-danger @enderror">
                                                        <label for="password" class="bmd-label-floating @error('password') text-danger @enderror">Mot de passe</label>
                                                        <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4 @error('etat') has-danger @enderror">
                                                        <label for="etat" class="bmd-label-floating @error('etat') text-danger @enderror">Etat</label>

                                                        <select name="etat" id="etat" class="selectpicker col-md-8" data-size="auto" data-style="select-with-transition"
                                                                data-style2="btn btn-primary btn-round" data-header="Choisir le groupe">
                                                            <option value="{{ \App\Constantes::ETAT_ACTIF }}">Activé</option>
                                                            <option value="{{ \App\Constantes::ETAT_INACTIF }}">Désactivé</option>
                                                        </select>
                                                        @error('etat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                </div><!-- row -->

                                                <div class="row">
                                                    <div class="form-group col-md-4 @error('date_entree') has-danger @enderror">
                                                        <label for="date_entree" class="bmd-label-floating @error('date_entree') text-danger @enderror">Date d'entrée</label>
                                                        <input type="date" name="date_entree" id="date_entree" value="{{ old('date_entree') }}" class="form-control @error('date_entree') is-invalid @enderror">

                                                        @error('date_entree')
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                        @enderror
                                                    </div>
                                                </div><!-- row -->

                                                <div class="clearfix"></div>
                                                <div class="row text-center">
                                                    <button id="btn-send" type="submit" class="btn btn-primary wd-100 col-md-3">Envoyer</button>
                                                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                </div><!-- row -->

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            // Get activite_id from the data attribute
            var activiteId = $('#datatables').data('activite_id');
            var storeUrl = $('#datatables').data('url');

            // Setup - add a text input to each footer cell (except first and last columns)
            $('.dataTable thead th').each(function (index) {
                if (index > 0 && index < 4) { // Skip index, heure and presence columns
                    var title = $(this).text();
                    $(this).append('<br/><input style="width:100%" type="text" placeholder="Rechercher par ' + title + '" />');
                }
            });

            // DataTable with server-side processing
            var table = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('presences.users.data') }}",
                    data: function (d) {
                        d.activite_id = activiteId;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nom', name: 'nom'},
                    {data: 'groupe', name: 'groupe'},
                    {data: 'niveau_engagement', name: 'niveau_engagement'},
                    {data: 'heure_arrivee', name: 'heure_arrivee', orderable: false, searchable: false},
                    {data: 'presence_checkbox', name: 'presence_checkbox', orderable: false, searchable: false}
                ],
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "order": [[1, "asc"]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Rechercher des membres",
                    processing: "Chargement...",
                    lengthMenu: "Afficher _MENU_ entrées",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                    infoEmpty: "Affichage de 0 à 0 sur 0 entrées",
                    infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
                    paginate: {
                        first: "Premier",
                        last: "Dernier",
                        next: "Suivant",
                        previous: "Précédent"
                    }
                },
                initComplete: function () {
                    // Apply the search for individual columns
                    this.api()
                        .columns([1, 2, 3]) // Only searchable columns (Nom, Groupe, Niveau engagement)
                        .every(function () {
                            var that = this;
                            $('input', this.header()).on('keyup change clear', function () {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                },
                drawCallback: function() {
                    // Apply green background to checked rows after each draw
                    $('#datatables tbody tr').each(function() {
                        var checkbox = $(this).find('.presence-checkbox');
                        if (checkbox.is(':checked')) {
                            $(this).addClass('presence-active');
                        } else {
                            $(this).removeClass('presence-active');
                        }
                    });
                }
            });

            // Handle presence checkbox changes
            $(document).on('change', '.presence-checkbox', function () {
                var checkbox = $(this);
                var userId = checkbox.data('user-id');
                var activiteId = checkbox.data('activite-id');
                var isChecked = checkbox.is(':checked');
                var $row = checkbox.closest('tr');
                var timeInput = $row.find('input[type="time"], .heure_arrivee, .timepicker');
                var heureArrivee = timeInput.val();

                // If checked but no time, set current time
                if (isChecked && !heureArrivee) {
                    var now = new Date();
                    heureArrivee = now.getHours().toString().padStart(2, '0') + ':' + 
                                  now.getMinutes().toString().padStart(2, '0');
                    timeInput.val(heureArrivee);
                }

                // Add/remove green background for checked rows
                if (isChecked) {
                    $row.addClass('presence-active');
                } else {
                    $row.removeClass('presence-active');
                }

                // Show processing state
                checkbox.prop('disabled', true);
                $row.addClass('processing');

                // Send AJAX request to update presence
                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: userId,
                        activite_id: activiteId,
                        presence: isChecked ? 1 : 0,
                        heure_arrivee: heureArrivee
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Show success notification without reloading table
                            showNotification('Présence mise à jour avec succès', 'success');
                            
                            // Optional: Update counter or badge if you have one
                            console.log('Presence updated successfully');
                        }
                        
                        // Remove processing state
                        checkbox.prop('disabled', false);
                        $row.removeClass('processing');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating presence:', error);
                        
                        // Revert checkbox state on error
                        checkbox.prop('checked', !isChecked);
                        if (isChecked) {
                            $row.removeClass('presence-active');
                        } else {
                            $row.addClass('presence-active');
                        }
                        
                        // Remove processing state
                        checkbox.prop('disabled', false);
                        $row.removeClass('processing');
                        
                        // Show error notification
                        var errorMessage = 'Erreur lors de la mise à jour de la présence';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    }
                });
            });

            // Handle time input changes
            $(document).on('change', 'input[type="time"], .heure_arrivee, .timepicker', function () {
                var timeInput = $(this);
                var userId = timeInput.data('user-id');
                var checkbox = timeInput.closest('tr').find('.presence-checkbox');
                
                // If time is changed and user is marked present, update the presence
                if (checkbox.is(':checked')) {
                    checkbox.trigger('change');
                }
            });

            // Manual reload button
            $('#reload-table-btn').on('click', function() {
                var btn = $(this);
                var originalText = btn.html();
                
                // Show loading state
                btn.html('<i class="material-icons">hourglass_empty</i> Chargement...');
                btn.prop('disabled', true);
                
                // Reload table
                table.ajax.reload(function() {
                    // Restore button state
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    showNotification('Liste actualisée', 'success');
                }, false); // false = don't reset pagination
            });

            // Notification function
            function showNotification(message, type) {
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var iconClass = type === 'success' ? 'check_circle' : 'error';
                
                $('#notification-area')
                    .removeClass('alert-success alert-danger')
                    .addClass(alertClass)
                    .find('#notification-message')
                    .html('<i class="material-icons" style="vertical-align: middle; margin-right: 5px;">' + iconClass + '</i>' + message);
                
                $('#notification-area').slideDown();
                
                // Auto-hide after 3 seconds
                setTimeout(function() {
                    $('#notification-area').slideUp();
                }, 3000);
            }
        });
    </script>
@endsection