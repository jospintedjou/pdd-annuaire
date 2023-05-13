$(document).ready(function(){

    console.log('hello');
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
    $('.presence-input').change(function () {
        alert('presence');

        var tr = $(this).closest(tr),
            tr1 = $(this).closest(tr);
        tr.addClass('presence-active');

    });
    /**
     * End check presence in a specific actiivy
    * **/

});
