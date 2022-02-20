$(document).ready(function(){
    const vip1="VIP1", vip2='VIP2', vip3='VIP3', vip4='VIP4', vip5='VIP5', cbi="CBI", feesVIP1=18000, feesVIP2=30000, feesVIP3=0,
        feesVIP4=300000, feesVIP5=600000;
    var newServiceCenter = $('.new-service-center');
    var oldServiceCenter = $('.old-service-center');
    var hasServiceCenter = $('#has-service-center'); //checkbutton
    var userLevel = $('.user-level').attr('user-level');
    var inscriptionFee = $('#frais_inscription');
    var parrain = $('#box-parrain');
    $('#niveau').change(function () {
        /** inscription fees **/
        switch($(this).val()){
            case vip5: inscriptionFee.val(feesVIP5); break;
            case vip4: inscriptionFee.val(feesVIP4); break;
            case vip2: inscriptionFee.val(feesVIP2); break;
            case vip3: inscriptionFee.val(feesVIP3); break;
            case vip1: inscriptionFee.val(feesVIP1); break;
            default: break;
        }
        inscriptionFee.closest('.form-group').addClass('is-filled');
       $('.empty-message').hide();
       console.log('userLevel', userLevel);
       if($(this).val() === vip4 || $(this).val() === vip5) {
           if(userLevel === cbi){
               hasServiceCenter.fadeIn();
               showNewServiceCenterForm();
           }
       }
       else {
           hasServiceCenter.fadeOut();
           hideNewServiceCenterForm();
       }
       if($(this).val() === vip3) {
            parrain.hide();
       }else{
           parrain.show();
       }
    });
    hasServiceCenter.find('input:checkbox').on('click', function(){
       if($(this).is(':checked')){
           showNewServiceCenterForm();
       }else{
           hideNewServiceCenterForm();
       }
    });
    function showNewServiceCenterForm(){
        newServiceCenter.find('input').val('');
        newServiceCenter.fadeIn();
        oldServiceCenter.hide();
    }
    function hideNewServiceCenterForm(){
        newServiceCenter.hide();
        oldServiceCenter.fadeIn();
    }
});
