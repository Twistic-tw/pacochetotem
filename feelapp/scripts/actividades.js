$(document).ready(function() {

    $('body').on('click','.calendar-hour',function(){

        var id_evento = $(this).attr('id');
        id_evento = id_evento.split('-')[1];

        var datos = {'id_evento':id_evento};

        $.ajax({
            url: 'index.php?pagina=calendario_detalle',
            data: datos,
            type:'POST',
            beforeSend: function(xhr) {
                $('.fondo_detalle_actividades').remove();
            },
            success: function(response){
                $('.fondo_detalle_actividades').remove();
                $('body').append(response);
            },
            error: function(err) {
                $('.fondo_detalle_actividades').remove();
            }
        });

    });

    $('body').on('click','.fondo_detalle_actividades,.cerrar_detalle_actividad',function(e){


        if (e.target == this) {

            $('.fondo_detalle_actividades').remove();

        }

        return;

    });

});