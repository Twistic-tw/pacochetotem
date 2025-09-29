
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {

    //formato de selector de fechas
    $(".fecha").datepicker();
    var cancel_datepicker = 0; //Funcion que controla la cancelacion de los click del datepicker
    $("#checkinCalendario").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,

        onSelect: function (date) {
        
        $('#fecha_checkin').val(date);
        }
       
    }).show();     //mostrar el calendario en la agenda

/*    //para el scroll personalizado de la agenda
    $(".scroll").each(function() {
        //$(this).setSlimScroll();
    });


    //// FUNCION PARA VALIDACION del formulario de login
    function validateForm(  ){
        return false;
        $(".modal_time").css("display", "block");
        
        
    }
    
    //FUNCION DESPUES DE HACER EL POST
    function formResponseHandler( responseText, statusText, xhr, form ){
       if ( statusText == "success" ){
            $(".modal_time").css("display", "none");
            $("#respuesta").html( responseText );
       }
    }

    //ARRAY DE OPCIONES PARA EL POST AJAX
    var options = {
        target: null,
        beforeSubmit: validateForm,
        success: formResponseHandler
    };
    
    $("#form_checkin").ajaxForm(options);*/

});
