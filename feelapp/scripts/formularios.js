$(document).ready(function() {


    $('body').on('click','.enlace-datos-reserva ',function (e){

        e.preventDefault();

        var identificador_usuario = localStorage.getItem('identificador_usuario');

        if(identificador_usuario != undefined && identificador_usuario != null && identificador_usuario != '' && identificador_usuario != 'undefined' && identificador_usuario != 'null'){
            var datos_post = {'identificador_usuario':identificador_usuario};
        }else {
            var datos_post = null;
        }

        $('#sidebar-tap-close').click();

        $.ajax({
            url: 'index.php?pagina=panel_usuario_formulario',
            type:'post',
            data: datos_post,
            timeout: 10000,
            beforeSend: function(xhr) {

                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('body').prepend(fondo_carga);
            },
            success: function(response, xhr, settings){

                $('.fondo_general_cargando').remove();
                $('.fondo_error').remove();

                response = JSON.parse(response);

                var error_code = response['error_code'];
                var contenido = response['mensaje'];

                if(error_code == 0){

                    localStorage.setItem('identificador_usuario', response['id_identificador_usuario']);

                    $('#content-general').fadeOut(100,function () {
                        $('#content-general').html(contenido);
                        $('#content-general').fadeIn(300);
                    });

                    $('body').scrollTop(0);

                }else{

                    alert(contenido);
                    localStorage.removeItem('identificador_usuario');
                    location.reload();

                }

            },
            error: function(err) {
                $('.fondo_detalle_actividades').remove();
                $('.fondo_cargando').remove();
                $('.fondo_general_cargando').remove();
            }
        });

    });


    $('body').on('submit','#formulario_panel_usuario',function(e){

        e.preventDefault();

        var datos_formularios  = new FormData(document.forms.namedItem("formulario_panel_usuario"));
        var action = $(this).attr("action");


        $.ajax({
            url: action,
            type:'post',
            data: datos_formularios,
            processData: false,
            contentType: false,
            timeout: 10000,
            beforeSend: function(xhr) {

                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('.fondo_popup').remove();
                $('body').prepend(fondo_carga);

            },
            success: function(response, xhr, settings){

                //formulario_panel_usuario

                $('.fondo_general_cargando').remove();
                $('.fondo_error').remove();

                response = JSON.parse(response);

                var error_code = response['error_code'];
                var contenido = response['mensaje'];

                if(error_code == 0){

                    localStorage.setItem('identificador_usuario', response['id_identificador_usuario']);

                    $('#content-general').fadeOut(100,function () {
                        $('#content-general').html(contenido);
                        $('#content-general').fadeIn(300);
                    });

                    $('body').scrollTop(0);

                }else if(error_code == 11){

                    localStorage.setItem('identificador_usuario', response['id_identificador_usuario']);

                    var fondo = '<div class="fondo_popup"><div class="content_popup">'+response['mensaje']+'</div></div>';
                    $('body').prepend(fondo);

                }else if(error_code == 12){

                    var fondo = '<div class="fondo_popup"><div class="content_popup">'+response['mensaje']+'</div></div>';
                    $('body').prepend(fondo);

                }else{

                    alert(contenido);

                }

            },
            error: function(err) {
                $('.fondo_detalle_actividades').remove();
                $('.fondo_cargando').remove();
                $('.fondo_general_cargando').remove();
            }
        });

    });


    $('body').on('click','#cerrar_sesion',function (e){

        e.preventDefault();

        $.ajax({
            url: 'index.php?pagina=cerrar',
            type:'post',
            timeout: 10000,
            beforeSend: function(xhr) {

            },
            success: function(response, xhr, settings){

                localStorage.removeItem('identificador_usuario');
                location.reload();

            },
            error: function(err) {
                return;
            }
        });

    });



});


