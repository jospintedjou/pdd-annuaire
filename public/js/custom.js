$(document).ready(function(){
    /** Start show/hide password **/
    var clicked = 0;
    $(".toggle-password").click(function (e) {
        e.preventDefault();

        $(this).toggleClass("toggle-password");
        if (clicked == 0) {
            $(this).find('.material-icons').html('visibility_off');
            $(this).closest('.wrap-input100').find('.fa').attr('class', 'fa fa-eye-slash');
            clicked = 1;
        } else {
            $(this).find('.material-icons').html('visibility');
            $(this).closest('.wrap-input100').find('.fa').attr('class', 'fa fa-eye');
            clicked = 0;
        }

        var input = $($(this).data("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    /** End show/hide password **/

    /**
     * Start show/hide zone,sous-zone & region containers in activite form
     * **/

    const typeRegion = 'Régionale', typeZone = 'Zonale', typeSousZone = 'Sous-zonale',typeGroupe = 'Groupe';
    var zoneContainer = $('.zone-container');
    var sousZoneContainer = $('.sous-zone-container');
    var groupeContainer = $('.groupe-container');
    $('#type_activite').change(function () {
        switch($(this).val()){
            case typeRegion: zoneContainer.hide('fade');
                             sousZoneContainer.hide('fade');
                             groupeContainer.hide('fade');
                             break;
            case typeZone: zoneContainer.show('fade');
                           sousZoneContainer.hide('fade');
                           groupeContainer.hide('fade');
                           break;
            case typeSousZone: sousZoneContainer.show('fade');
                               zoneContainer.hide('fade');
                               groupeContainer.hide('fade');
                               break;
            case typeGroupe: groupeContainer.show('fade');
                             zoneContainer.hide('fade');
                             sousZoneContainer.hide('fade');
                             break;
            default: zoneContainer.hide('fade');
                     sousZoneContainer.hide('fade');
                     groupeContainer.hide('fade');
                     break;
        }
    });
    /**
     * End show/hide zone,sous-zone & region containers in activite form
    * **/

    /**
     * Start check presence in a specific actiivy
    * **/
    $('.presence-checkbox').click(function () {

        var val = $(this).val(),
            tr = $(this).closest('tr'),
            table = $(this).closest('table'),
            url = table.data('url'),
            user_id = tr.data('user_id'),
            activite_id = table.data('activite_id'),
            heure_arrivee = table.find('.heure_arrivee').val(),
            presence = $(this).is(':checked') ? 1 : 0,
            formData = {
                activite_id: activite_id,
                user_id: user_id,
                heure_arrivee: heure_arrivee,
                presence: presence
            };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log('data', data);
                if(presence){
                    tr.addClass('presence-active');
                }else {
                    tr.removeClass('presence-active');
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    /**
     * End check presence in a specific actiivy
    * **/

    /**--------------------------------------------
    *  Start check presence in a specific actiivy  *
    *--------------------------------------------**/
    $('.evaluation-checkbox').click(function () {

        var val = $(this).val(),
            tr = $(this).closest('tr'),
            table = $(this).closest('table'),
            url = table.data('url'),
            user_id = tr.data('user_id'),
            rubrique_id = table.data('rubrique_id'),
            evaluation = $(this).is(':checked') ? 1 : 0,
            formData = {
                rubrique_id: rubrique_id,
                user_id: user_id,
                evaluation: evaluation
            };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log('data', data);
                if(evaluation){
                    tr.addClass('presence-active');
                }else {
                    tr.removeClass('presence-active');
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    /**--------------------------------------------
     *  End check presence in a specific actiivy  *
     *--------------------------------------------**/

    /**-------------------------------------------------
    *  Start dependant dropdown for sous-zone & group  *
    *-------------------------------------------------**/
    $('.zone').on('change', function () {

        var zone_id = $(this).val(),
            url = $(this).closest('select').data('url');

        formData = {
            zone_id: zone_id
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                var sousZone = $('#sous_zone');
                sousZone.html(data.data);
                sousZone.selectpicker('refresh');
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    /**-------------------------------------------------
     *  End dependant dropdown for sous-zone & group  *
     *-------------------------------------------------**/

});
