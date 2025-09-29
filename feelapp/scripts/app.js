$( document ).ready(function() {

    $("body").on("click", ".js_contenidos_masinfo_scroll", function(event){

        var ruta = $(this).attr('data-rutamovil');

        $(this).attr('href',ruta);
        var url = $(this).attr('href');
        //window.open(url,'_blank');
        console.log(url);


        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="'+url+'"></div>' +
            '<div class="back-popup"><img src="http://admin.twisticdigital.com/contenido_proyectos/dunas/contenido/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

    });

    $('body').on('click','.back-popup',function(){
        $('.fondo-popup-zoom').remove();
        $('meta[name=viewport]').attr('content', 'user-scalable=no, initial-scale=1.0, maximum-scale=1.0');
    });

    //$(".enlace-pagina").click(function(e){
    $( "body" ).on( "click", ".enlace-pagina", function(e){

            e.preventDefault();

            var id = $(this).attr('data-enlace');

            if(id != '#'){

                $('#sidebar-tap-close').click();

                var id_contenido = id.split('-');

                /*if(id == 'calendario'){
                    var datos = {'pagina':'calendario'};
                }else{
                    var datos = {'pagina':'contenidos','id_contenido': id_contenido[1]};
                }*/

                if(id.indexOf("categoria") > -1){
                    var datos = {'pagina':'contenidos','id_contenido': id_contenido[1]};
                }else{
                    var datos = {'pagina':id};
                }

                $.ajax({
                    url: 'index.php',
                    data: datos,
                    type:'GET',
                    beforeSend: function(xhr) {
                        //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
                    },
                    success: function(json){
                        //console.log(json);
                        $('#content-general').html(json);
                        $('body').scrollTop(0);
                    },
                    error: function(err) {
                        //alert(JSON.stringify(err));
                    }
                });
            }

            return false;

        });


    $( "body" ).on( "click", ".actividad-popup", function(e){
        e.preventDefault();

        var id_actividad = $(this).attr('id');
        console.log(id_actividad);

        return false;
    });

    $( "body" ).on( "click", ".dia-semana-actividades", function(e){
        e.preventDefault();

        var id_fecha_actividad = $(this).attr('id');

        $('.calendar-hours').css('display','none');
        $(id_fecha_actividad).css('display','block');

        var fecha_calendario = $(this).attr('data-fecha_texto');
        $('#fecha_actual_calendario').html(fecha_calendario);

        $('.dia-semana-actividades').removeClass('taken-day');
        $('.dia-semana-actividades').removeClass('clear-day');
        $('.dia-semana-actividades').addClass('clear-day');

        $(this).removeClass('clear-day');
        $(this).addClass('taken-day');


        return false;

    });

    $( "body" ).on( "click", ".content-destinos", function(e){
        e.preventDefault();

        var id_destino = $(this).attr('id');

        $('.content-paises').css('display','none');
        $(id_destino).css('display','block');
        $('.btn-atras-destinos').css('display','block');
        $('.content-destinos').css('display','none');
        $('.content-absolute').addClass('content-hoteles-paises');

        return false;

    });

    $( "body" ).on( "click", ".btn-atras-destinos", function(e){
        e.preventDefault();

        var id_destino = $(this).attr('id');

        $('.content-paises').css('display','none');
        $('.content-destinos').css('display','block');
        $('.btn-atras-destinos').css('display','none');
        $('.content-absolute').removeClass('content-hoteles-paises');


        return false;

    });


    $( "body" ).on( "click", ".content-idioma", function(e){

        e.preventDefault();

        var id = $(this).attr('id');
        var id_idioma = id.split('_');

        var datos = {'id_idioma': id_idioma[1]};

        var id_hotel = $(this).attr('data-hotel');

        $.ajax({
            url: 'index.php',
            data: datos,
            type:'POST',
            beforeSend: function(xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
            },
            success: function(json){
                window.location.href = "index.php?id_hotel=" + id_hotel;
                /*console.log(json);*/
            },
            error: function(err) {
                //alert(JSON.stringify(err));
            }
        });

        return false;

    });


});
