/*
 *
 *   Registration JS By Venkatesh
 *   version 1.0
 *
 */


$(document).ready(function () {
    $('#data_3 .input-group.date').datepicker({
        startView: 2,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
    $("#individual_reg_from :input").prop("disabled", true);
    $("#company_reg_from :input").prop("disabled", true);

    $('#company').on('click', function () {
       $(this).addClass('btn-warning');
       $(this).removeClass('btn-default');
       $('#individual').removeClass('btn-warning');
       $('#individual').addClass('btn-default');
       $('#reg_head').html('Company Registration');
       $("#company_reg_from :input").prop("disabled", false);
       $("#individual_reg_from :input").prop("disabled", true);
       $('#individual_div').css('display','none');
       $('#company_div').css('display','block');
    });
    $('#individual').on('click', function () {
        $(this).addClass('btn-warning');
        $(this).removeClass('btn-default');
        $('#company').removeClass('btn-warning');
        $('#company').addClass('btn-default');
        $('#reg_head').html('Individual Registration');
        $("#company_reg_from :input").prop("disabled", true);
        $("#individual_reg_from :input").prop("disabled", false);
        $('#company_div').css('display','none');
        $('#individual_div').css('display','block');
    });
});