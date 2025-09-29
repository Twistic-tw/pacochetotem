function inicializa_listener_rating(){
    $('.hover-star').rating();


    // $(".hover-star").change(function() {
    //     $("#hover-test").css("color", "#ff6a00");
    //     alert("sddsd");
    // });

    // //Cuando valoras
    $(".hover-star").change(function(event){

    // //     $valor=$(this).val();
    // //     //$sumaparcial = parseInt($valor) + parseInt($(".rationew").val());
    // //     //$cantidad = parseInt($(".votos").val()) + 1;
    // //     //$suma = $sumaparcial / $cantidad;
    // //    // $(".ratio").val($suma);
    // //     var rat = $valor;
    // //     var item = $(".item").val();
    // //     var id_idioma_rating = jQuery("#idioma_actual").text();
    // //     var dataString ="rat="+rat+"&item="+item+"&id_idioma="+id_idioma_rating;
        
    // //     $(".ratio").fadeIn("slow"); 

    // //     // Actualización de la valoración en bd_local rating        
    // //     $.ajax({
    // //         type: "POST",
    // //         url: "update.php",
    // //         data: dataString,
    // //         success: function(data) {
    // //             var chart =  $('.chart').data('easyPieChart');

    // //             var a = jQuery.parseJSON(data);

    // //             // Se actualizan las estrellas con los nuevos valores tras el voto
    // //             for (i=1;i<6;i++){
    // //                 $('#valoracion_'+i).attr('data-percent', a[i]);
    // //                 $('#valoracion_'+i).children().text(a[i]);
    // //                 chart = $('#valoracion_'+i).data('easyPieChart');
    // //                 chart.update(a[i]);
    // //             }
               

    // //         }
    // //     });
    // //     // Se deshabilitan los rating tras el voto
        $(".hover-star").rating('disable');  
        // $(".hover-star").fadeOut();
        $(".agenda_estrellas").fadeOut();

        $(".rating_informacion").fadeIn();

    });

};


