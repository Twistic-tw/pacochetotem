$(document).ready(function () {

    var cerrar_popup;

    // $('body').on('submit','#formulario_chickin',function(e){
    //
    //     e.preventDefault();
    //
    //     var datos_formularios = $(this).serialize();
    //     var action = $(this).attr("action");
    //
    //     $.ajax({
    //         url: action,
    //         type:'post',
    //         data: datos_formularios,
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             response = JSON.parse(response);
    //
    //             if(response['error_code'] > 0){
    //
    //                 var  error = response['error_texto'];
    //                 $('.error_formulario').remove();
    //                 var div_error = '<div class="error_formulario error_formulario_padding">' + error + '</div>';
    //                 $('.content_formularios').prepend(div_error);
    //
    //                 $('#numero_reserva').val('');
    //                 $('#primer_apellido').val('');
    //
    //                 // cerrar_popup = setTimeout(function () {
    //                 //     $('.error_formulario').remove();
    //                 // },3000);
    //
    //             }else{
    //
    //                 $('#content-general').html();
    //                 $('#content-general').hide();
    //                 $('#content-general').html(response['contenido']);
    //                 $('#content-general').fadeIn(1000);
    //
    //             }
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('submit','#formulario_chickin_datos',function(e){
    //
    //     e.preventDefault();
    //
    //     //var datos_formularios = $(this).serialize();
    //
    //     var datos_formularios  = new FormData(document.forms.namedItem("formulario_chickin_datos"));
    //     var action = $(this).attr("action");
    //
    //     $.ajax({
    //         url: action,
    //         type:'post',
    //         data: datos_formularios,
    //         processData: false,
    //         contentType: false,
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             $('#content-general').html();
    //             $('#content-general').hide();
    //             $('#content-general').html(response);
    //             $('#content-general').fadeIn(1000);
    //
    //             cerrar_popup = setTimeout(function () {
    //                 window.location.href = "";
    //             },4000);
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('click','.add_nuevo_usuario',function(){
    //
    //     var id_usuario = $('.content_usuario').length;
    //     id_usuario++;
    //
    //     var datos_formularios = {'id_nuevo_usuario':id_usuario};
    //
    //     $.ajax({
    //         url: 'index.php?pagina=checkin_usuario',
    //         data: datos_formularios,
    //         type:'post',
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             if(response != 'error'){
    //                 $('.content_usuarios_global').append(response);
    //             }
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('click','.delete_usuario_form',function(){
    //     $(this).parent().parent('.content_usuario').remove();
    // });

    $('body').on('click', '.fondo_popup,.close_popup', function (e) {

        if (e.target == this) {

            $('.fondo_popup').remove();

            return;

        }
    });

    // /*********************** Control de usuarios *****************************/
    //
    // $('body').on('click','#iniciar_sesion',function(){
    //     iniciar_session();
    //     return;
    // });
    //
    // $('body').on('submit','#formulario_login',function(e){
    //
    //     e.preventDefault();
    //
    //     //var datos_formularios = $(this).serialize();
    //
    //     var datos_formularios  = new FormData(document.forms.namedItem("formulario_login"));
    //     var action = $(this).attr("action");
    //
    //     $.ajax({
    //         url: action,
    //         type:'post',
    //         data: datos_formularios,
    //         processData: false,
    //         contentType: false,
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             response = JSON.parse(response);
    //
    //             if(response['error_code'] == 0){
    //
    //                 $('.content_popup').html(response['contenido']);
    //
    //                 $('#iniciar_sesion').addClass('displayNone');
    //                 $('#cuenta_usuario').removeClass('displayNone');
    //
    //                 $('.enlace_whatsapp').attr('href',response['texto_whatsapp']);
    //
    //                 cerrar_popup = setTimeout(function () {
    //                     $('.fondo_popup').remove();
    //                 },3000);
    //
    //             }else{
    //
    //                 var  error = response['error_texto'];
    //
    //                 $('.error_formulario').remove();
    //                 $('#formulario_login').prepend(error);
    //
    //             }
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('click','#cuenta_usuario',function(){
    //
    //     $.ajax({
    //         url: 'index.php?pagina=panel_usuario',
    //         type:'post',
    //         data: '',
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             response = JSON.parse(response);
    //
    //             if(response['error_code'] > 0){
    //
    //                 window.location.href = "";
    //
    //             }else{
    //
    //                 $('#sidebar-tap-close').click();
    //
    //                 $('#content-general').html();
    //                 $('#content-general').hide();
    //                 $('#content-general').html(response['contenido']);
    //                 $('#content-general').fadeIn(1000);
    //
    //             }
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('click','#checkout_express',function(e){
    //
    //     e.preventDefault();
    //
    //     var datos_formularios = {'checkout':1};
    //
    //     $.ajax({
    //         url: 'index.php?pagina=checkout',
    //         data: datos_formularios,
    //         type:'post',
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //
    //             if(response){
    //                 $('.content_panel_usuario').html(response);
    //                 cerrar_popup = setTimeout(function () {
    //                     window.location.href = "";
    //                 },3000);
    //
    //             }
    //
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // $('body').on('click','#cerrar_sesion',function(e){
    //
    //     e.preventDefault();
    //
    //     var datos_formularios = {'cerrar_sesion':1};
    //
    //     $.ajax({
    //         url: 'index.php?pagina=panel_usuario',
    //         data: datos_formularios,
    //         type:'post',
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             window.location.href = "";
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // /*********** Reserva Servicios ************/
    //
    // $('body').on('click','.servicio_reserva',function(e){
    //
    //     clearTimeout(cerrar_popup);
    //
    //     var id_reserva = $(this).attr('data-id_reserva');
    //
    //     var datos_formularios = {'id_reserva':id_reserva};
    //
    //     $.ajax({
    //         url: 'index.php?pagina=servicios_reserva',
    //         data: datos_formularios,
    //         type:'post',
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             response = JSON.parse(response);
    //
    //             if(response['error_code'] == 1){
    //
    //                 iniciar_session();
    //
    //             }else if(response['error_code'] == 0){
    //
    //                 $('.fondo_popup').remove();
    //                 $('body').prepend(response['contenido']);
    //
    //             }
    //
    //             return;
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // //
    //
    // $('body').on('submit','#formulario_reserva_servicio',function(e){
    //
    //     e.preventDefault();
    //
    //     //var datos_formularios = $(this).serialize();
    //
    //     var datos_formularios  = new FormData(document.forms.namedItem("formulario_reserva_servicio"));
    //     var action = $(this).attr("action");
    //
    //
    //     $.ajax({
    //         url: action,
    //         type:'post',
    //         data: datos_formularios,
    //         processData: false,
    //         contentType: false,
    //         beforeSend: function(xhr) {
    //             //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
    //         },
    //         success: function(response, xhr, settings){
    //
    //             $('.error_formulario_fixed').remove();
    //
    //             if(response){
    //
    //                 response = JSON.parse(response);
    //
    //                 if(response['error_code'] == 0){
    //
    //                     $('.content_popup').html(response['contenido']);
    //
    //                     cerrar_popup = setTimeout(function () {
    //                         $('.fondo_popup').remove();
    //                     },3000);
    //
    //                 }else if(response['error_code'] == 1){
    //
    //                     $('.imagen_popup_reserva').append(response['contenido']);
    //                     $('.content_popup_scroll').scrollTo(0);
    //
    //                     cerrar_popup = setTimeout(function () {
    //                         $('.error_formulario_absolute').remove();
    //                     },3000);//
    //
    //                 }else{
    //
    //                     $('.content_popup').html(response['contenido']);
    //
    //                     cerrar_popup = setTimeout(function () {
    //                         $('.fondo_popup').remove();
    //                     },3000);
    //
    //                 }
    //
    //             }else{
    //
    //                 //No hay respuesta
    //
    //             }
    //
    //
    //
    //         },
    //         error: function(err) {
    //             //alert(JSON.stringify(err));
    //         }
    //     });
    //
    // });
    //
    // /********** Sumar articulos **************/
    //
    // $('body').on('click','.restar_cantidad,.sumar_cantidad',function(){
    //
    //     var class_pulsada = $(this).attr('class');
    //     var total_actual = $(this).parent().children('.total_articulos').children('input').val();
    //
    //     if(class_pulsada == 'restar_cantidad'){
    //
    //         if(total_actual == 0){
    //             return;
    //         }else{
    //             total_actual = total_actual - 1;
    //         }
    //
    //     }else{
    //
    //         total_actual++;
    //
    //     }
    //
    //     $(this).parent().children('.total_articulos').children('input').val(total_actual);
    //
    //     var total_cesta = 0;
    //
    //     $(".cantidad_producto").each(function() {
    //
    //         var precio_articulo = $(this).attr('data-precio');
    //         var cantidad_articulo = $(this).children('.total_articulos').children('input').val();
    //         var total_dinero_producto = precio_articulo * cantidad_articulo;
    //
    //         total_cesta = total_cesta + total_dinero_producto;
    //
    //     });
    //
    //     $('#total_cesta_articulos').html(total_cesta);
    //     $('#total_cesta').val(total_cesta);
    //
    // });
    //
    // /*********************** Reserva Restaurantes **************************/

    $('body').on('click', '.btn_reserva_restaurante', function () {

        clearTimeout(cerrar_popup);

        var id_restaurante_click = null;
        id_restaurante_click = $(this).attr('data-id_restaurante');
        // panel_reserva_restaurante(id_restaurante);


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

                    //var id_restaurante = 1;
                    var id_restaurante = id_restaurante_click;
                    panel_reserva_restaurante(id_restaurante);

                }else{

                    iniciar_session();

                }

            },
            error: function(err) {
                $('.fondo_detalle_actividades').remove();
                $('.fondo_cargando').remove();
                $('.fondo_general_cargando').remove();
            }
        });

    });


    $('body').on('click', '.restar_cantidad_r,.sumar_cantidad_r', function () {

        var class_pulsada = $(this).attr('class');
        var total_actual = $(this).parent().children('.total_articulos').children('input').val();

        if (class_pulsada == 'restar_cantidad_r') {

            if (total_actual == 1) {
                return;
            } else {
                total_actual = total_actual - 1;
            }

        } else {

            total_actual++;

        }

        $(this).parent().children('.total_articulos').children('input').val(total_actual);

    });

    // $('body').on('click','.restaurante_reserva_horario',function(e){
    //     $('.restaurante_reserva_horario').removeClass('active');
    //     var id_horario = $(this).attr('data-id_horario');
    //     var horario_hora = $(this).attr('data-horario_hora');
    //     $('input#id_horario').val(id_horario);
    //     $('input#horario_hora').val(horario_hora);
    //     $(this).addClass('active');
    // });


    $('body').on('click', '.btn_reservar_turno', function (e) {

        //alert('dasdasd');

        if ($(this).hasClass('class_desactivado')) {
            return false;
        } else {

            var id_completo = $(this).attr('data-fecha_horario');
            var split_id = id_completo.split('_');
            var id_parcheado = split_id[0] + '_' + split_id[1];

            var seleccionado = $('#textos_reservas').attr('data-seleccionar');
            var cancelar = $('#textos_reservas').attr('data-cancelar');

            if ($(this).parent().parent('.content_turno_franja').hasClass('active')) {

                $('div[data-fecha_horario*="' + id_parcheado + '"]').parent().parent('.content_turno_franja').removeClass('active');
                $('input[data-fecha_horario*="' + id_parcheado + '"]').val(null);
                $(this).closest('.content_reserva_turnos_restaurantes').find('.btn_reservar_turno').not('.class_desactivado').removeClass('displayNone');
                // $(this).html('SELECCIONAR').css('background-color', '#115E67');
                $(this).html(seleccionado).css('background-color', '#115E67');

            } else {

                $('div[data-fecha_horario*="' + id_parcheado + '"]').parent().parent('.content_turno_franja').removeClass('active');
                $('input[data-fecha_horario*="' + id_parcheado + '"]').val(null);
                $(this).closest('.content_reserva_turnos_restaurantes').find('.btn_reservar_turno').css('background-color', '#115E67').html(seleccionado);
                $(this).parent().parent('.content_turno_franja').addClass('active');
                $('input[data-fecha_horario="' + id_completo + '"]').val('1');
                $(this).html(cancelar).css('background-color', 'red');


            }

        }

    });


    $('body').on('submit', '.formulario_reserva_restaurante', function (e) {

        e.preventDefault();

        var id_form = $(this).attr('id');

        var total_activos = $(this).find('.content_turno_franja.active');

        if(total_activos.length > 0){

            //var datos_formularios = $(this).serialize();

        var datos_formularios = new FormData(document.forms.namedItem(id_form));
        var action = $(this).attr("action");

        console.log(datos_formularios);

        $.ajax({
            url: action,
            type: 'post',
            data: datos_formularios,
            processData: false,
            contentType: false,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
            },
            success: function (response, xhr, settings) {

                response = JSON.parse(response);

                //no hay y sugerencia
                if (response['error_code'] == 9) {

                    $('.fondo_popup').html(response['contenido']);

                } else if (response['error_code'] == 0) {

                    $('.content_popup').html(response['contenido']);

                    // cerrar_popup = setTimeout(function () {
                    //     $('.fondo_popup').remove();
                    // }, 8000);

                } else {
                    $('.content_popup').html(response['contenido']);

                    cerrar_popup = setTimeout(function () {
                        $('.fondo_popup').remove();
                    }, 3000);
                }

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

        }else{

            var texto = $('#id_texto_seleccionar_turno').val();
            var html_error = '<div class="fondo-popup-formulario"><div class="content-popup-formulario"><div class="mensaje_error_checkin_reserva">'+texto+'</div></div></div>';
            $('.fondo-popup-formulario').remove();
            $('body').append(html_error);
        }

    });

    $('body').on('change', '#select_fecha_reserva_restaurantes', function () {

        var id_fecha = $(this).val();

        $('.content_dia_reserva_restaurante').fadeOut(250, function () {
            $('.restaurante_reserva_horario').removeClass('active');
            $('input.res_fecha_franja').val(null);
            $('#reserva_restaurante_' + id_fecha).fadeIn(250);
        });

    });

    $('body').on('click', '.cancelar_turno_reserva_restaurate', function (e) {

        e.preventDefault();

        var id = $(this).attr('id');
        var id = id.split('_')[1];

        var action = 'index.php?pagina=cancelar_restaurante_reserva';

        var datos = {'id_reserva_restaurante': id};

        $.ajax({
            url: action,
            type: 'post',
            data: datos,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
            },
            success: function (response, xhr, settings) {

                response = JSON.parse(response);

                // //no hay y sugerencia
                if (response['error_code'] == 0) {

                    var texto_cupo = response['texto'];
                    var id_reserva = response['id_reserva'];

                    $('#reserva-r_' + id_reserva).closest('.content_reserva_turnos_restaurantes').find('.btn_reservar_turno').not('.class_desactivado').removeClass('displayNone');

                    $('#reserva-r_' + id_reserva).closest('.content_turno_franja').find(".btn_reservar_turno").removeClass('class_desactivado displayNone');
                    $('#reserva-r_' + id_reserva).closest('.content_turno_franja').find(".mensaje_reserva_hora").removeClass('class_color_rojo');
                    $('#reserva-r_' + id_reserva).closest('.content_turno_franja').find(".mensaje_reserva_hora").html(texto_cupo);
                    $('#reserva-r_' + id_reserva).remove();

                } else {

                    $('.content_popup').html(response['contenido']);

                    cerrar_popup = setTimeout(function () {
                        $('.fondo_popup').remove();
                    }, 3000);

                }

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });

    $('body').on('click', '.cancelar_turno_reserva_restaurate_panel', function (e) {

        e.preventDefault();

        var id = $(this).attr('id');
        var id = id.split('_')[1];

        var action = 'index.php?pagina=cancelar_restaurante_reserva';

        var datos = {'id_reserva_restaurante': id};

        $.ajax({
            url: action,
            type: 'post',
            data: datos,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));


                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('body').append(fondo_carga);
                $('.fondo_popup').remove();

            },
            success: function (response, xhr, settings) {

                $('.fondo_general_cargando').remove();
                response = JSON.parse(response);

                // //no hay y sugerencia
                if (response['error_code'] == 0) {

                    var texto_cupo = response['texto'];
                    var id_reserva = response['id_reserva'];

                    $('#contet-r-rest_'+id_reserva).remove();

                    var total_reservas = $('.content_guest_reserva').length;

                    if(total_reservas < 1){
                        $('.content_guest_reservas').remove();
                    }

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                    cerrar_popup = setTimeout(function () {
                        $('.fondo_popup').remove();
                    }, 4000);

                    

                } else {

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                    cerrar_popup = setTimeout(function () {
                        $('.fondo_popup').remove();
                    }, 3000);

                }

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });


    $('body').on('click', '.editar_turno_reserva_restaurate_panel', function (e) {

        e.preventDefault();

        var id = $(this).attr('id');
        var id = id.split('_')[1];

        var action = 'index.php?pagina=editar_restaurante_reserva';

        var datos = {'id_reserva_restaurante': id, 'function': "popup_editar_reserva"};

        $.ajax({
            url: action,
            type: 'post',
            data: datos,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));

                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('body').append(fondo_carga);
                $('.fondo_popup').remove();

            },
            success: function (response, xhr, settings) {
                
                $('.fondo_general_cargando').remove();

                response = JSON.parse(response);

                // //no hay y sugerencia
                if (response['error_code'] == 0) {

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                    // cerrar_popup = setTimeout(function () {
                    //     $('.fondo_popup').remove();
                    // }, 3000);

                } else {

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                    cerrar_popup = setTimeout(function () {
                        $('.fondo_popup').remove();
                    }, 3000);

                }

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });

    $('body').on('click','.seleccionar_turno_reserva_restaurate',function(e){

        e.preventDefault();

        var id_reserva_restaurante = $(this).attr('id');
        var hora_inicio = $(this).attr('data-hora-inicio');
        var hora_fin = $(this).attr('data-hora-fin');
        var id_reserva = $(this).attr('data-id_reserva');
        var id_restaurante = $(this).attr('data-id_restaurante');
        var fecha = $(this).attr('data-fecha');

        var action = 'index.php?pagina=editar_restaurante_reserva';

        var datos = {'id_reserva_restaurante': id_reserva_restaurante,'function': "popup_editar_reserva_final",'hora_inicio': hora_inicio,'hora_fin': hora_fin,'id_reserva': id_reserva,'id_restaurante':id_restaurante,'fecha':fecha};

        $.ajax({
            url: action,
            type: 'post',
            data: datos,
            beforeSend: function (xhr) {

                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));

                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('body').append(fondo_carga);
                $('.fondo_popup').remove();

            },
            success: function (response, xhr, settings) {
                
                $('.fondo_general_cargando').remove();

                response = JSON.parse(response);

                if (response['error_code'] == 0) {

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                    var horas = response['horas'];
                    var id_reserva = response['id_reserva'];

                    $('#contet-r-rest_'+id_reserva).find('.horas_reserva_rest').html(horas);

                } else {

                    $('body').append('<div class="fondo_popup"><div class="content_popup">'+response['contenido']+'</div></div>');

                }

                cerrar_popup = setTimeout(function () {
                    $('.fondo_popup').remove();
                }, 5000);

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });


    $('body').on('click','.open-new-habitacion',function(e){

        var action = 'index.php?pagina=formulario_agregar_habitacion';
        var fecha_reserva_habitacion = $(this).attr('data-id_fecha');
        var id_restaurante_global = $('#id_restaurante_global').val();

        var total_comensales = 0;
        $('.reservas_r_'+fecha_reserva_habitacion).each(function() {
            total_comensales = parseInt(total_comensales) + parseInt($( this ).val().split('_')[1]);
        });

        var datos = {'fecha_reserva_habitacion':fecha_reserva_habitacion,'id_restaurante_global':id_restaurante_global,'numero_total_comensales':total_comensales};

        $.ajax({
            url: action,
            type: 'post',
            data: datos,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
            },
            success: function (response, xhr, settings) {

                response = JSON.parse(response);
                $('.fondo-popup-formulario').remove();

                $('body').append(response['mensaje']);
                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });

    $('body').on('click','.cancelar-habitacion,.fondo-popup-formulario,.mensaje_error_checkin_reserva',function(e){

        if (e.target == this) {

            $('.fondo-popup-formulario').remove();

            return;

        }

    });

    $('body').on('submit','#formulario-add-habitacion',function(e){

        e.preventDefault();

        var id_form = $(this).attr('id');

        var datos_formularios = new FormData(document.forms.namedItem(id_form));
        var action = $(this).attr("action");

        console.log(datos_formularios);

        $.ajax({
            url: action,
            type: 'post',
            data: datos_formularios,
            processData: false,
            contentType: false,
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));

                $('.fondo_general_cargando').remove();

                var fondo_carga = '<div class="fondo_general_cargando">' +
                    '<img class="gif_cargando" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/cargando.gif" alt="">' +
                    '</div>';

                $('body').append(fondo_carga);
                $('.fondo-popup-formulario').remove();

            },
            success: function (response, xhr, settings) {

                response = JSON.parse(response);

                if(response['error_code'] == 0){

                    var mensaje = response['mensaje'];
                    var comensales = response['comensales'];
                    var bebes = response['bebes'];
                    var id_reserva = response['id_reserva'];
                    var fecha = response['fecha'];
                    var input = response['input'];
                    var html_reserva = response['html_reserva'];
                    var html_bebes = response['html_bebes'];

                    // <input type="hidden" id="comensales_'.$post['fecha_dia_reserva'].'_'.$ReservationCode.'" name="nueva_habitacion_comensales[]" value="'.$ReservationCode.'_'.$num_comensales.'"></input>

                    $('input#comensales_'+fecha+'_'+id_reserva).remove();
                    $('#texto_comensales_'+fecha+'_'+id_reserva).remove();
                    $('.trona-'+id_reserva).remove();
                    $('.content_habitacion_'+fecha).find('.formulario-agregar-habitacion').append(input);
                    $('.content_habitacion_'+fecha).find('.formulario-agregar-habitacion').append(html_reserva);
                    $('#content-tronas-'+fecha).append(html_bebes);

                    $('.fondo-popup-formulario').remove();

                }else{
                    
                    var mensaje = '<div class="fondo-popup-formulario"><div class="content-popup-formulario"><div class="mensaje_error_checkin_reserva">'+response['mensaje']+'</div></div></div>';
                    $('body').append(mensaje);

                }

                $('.fondo_general_cargando').remove();
                

                // if (response['error_code'] == 9) {

                //     $('.fondo_popup').html(response['contenido']);

                // } else if (response['error_code'] == 0) {

                //     $('.content_popup').html(response['contenido']);

                //     cerrar_popup = setTimeout(function () {
                //         $('.fondo_popup').remove();
                //     }, 3000);

                // } else {
                //     $('.content_popup').html(response['contenido']);

                //     cerrar_popup = setTimeout(function () {
                //         $('.fondo_popup').remove();
                //     }, 3000);
                // }

                return;

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

    });

    $('body').on('click','.span-cancelar-habitacion',function(e){

        var id_texto = $(this).closest('.eliminar_habitacion_reserva_restaurante').attr('id');
        $(this).closest('.eliminar_habitacion_reserva_restaurante').remove();
        var id = id_texto.replace('texto_','');
        $('input#'+id).remove();

        var id_reserva = $(this).attr('id');
        
        $('.trona-'+id_reserva).remove();

    });

    $('body').on('click','.content-reserva-alergeno',function(e){

        var identificador = $(this).attr('data-id_alergeno');

        if($(this).hasClass('active')){
            $('.r_alergeno_'+identificador).removeClass('active');
            $('input.input_alergeno_'+identificador).val('0');
        }else{
            $('.r_alergeno_'+identificador).addClass('active');
            $('input.input_alergeno_'+identificador).val('1');
        }

        //{identificador}
    });


});


function iniciar_session() {

    $('.fondo_popup').remove();

    $.ajax({
        url: 'index.php?pagina=login',
        data: '',
        type: 'post',
        beforeSend: function (xhr) {
            //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
        },
        success: function (response, xhr, settings) {

            if (response) {
                $('.fondo_popup').remove();
                $('body').prepend(response);
            }

            return;

        },
        error: function (err) {
            //alert(JSON.stringify(err));
        }
    });

    return;

}


function panel_reserva_restaurante(id_restaurante, total_comensales = null, fecha_activa = null, horario_hora = null) {

    $('.fondo_popup').remove();

    var id_restaurante = id_restaurante;

    var datos_formularios = {};

    datos_formularios['id_restaurante'] = id_restaurante;

    if (total_comensales) {
        datos_formularios['total_comensales'] = total_comensales;
    }

    if (fecha_activa) {
        datos_formularios['fecha_activa'] = fecha_activa;
    }

    if (horario_hora) {
        datos_formularios['horario_hora'] = horario_hora;
    }

    //var datos_formularios = {'id_restaurante':id_restaurante};

    $.ajax({
        url: 'index.php?pagina=restaurantes_reserva',
        data: datos_formularios,
        type: 'post',
        beforeSend: function (xhr) {
            //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
        },
        success: function (response, xhr, settings) {

            response = JSON.parse(response);


            if (response['error_code'] == 1) {

                iniciar_session();

            } else if(response['error_code'] == 5){

                $('.fondo_popup').remove();
                $('body').prepend(response['contenido']);

            }else if (response['error_code'] == 0) {

                $('.fondo_popup').remove();
                $('body').prepend(response['contenido']);

                // console.log(response['datos_grafica']);
                // return;

                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart);


                function drawChart() {
                    // var dataArray = ['Cupo total', 'Cupo restante'];

                    $.each(response['datos_grafica'], function (fecha, array_values) {
                        // console.log(clave)
                        // console.log(valor)
                        // console.log(valor[clave])
                        $.each(array_values, function (turno, cupos) {

                            // var data = google.visualization.arrayToDataTable([
                            //     ['Cupos', 'Valores'],
                            //     ['Ocupado', cupos.cupo_reservado],
                            //     ['Libre', cupos.cupo_total]
                            // ]);

                            var cupo_libre = cupos.cupo_total - cupos.cupo_reservado;
                            if(cupo_libre < 0){
                                cupo_libre = 0;
                            }
            
                            var data = google.visualization.arrayToDataTable([
                                ['Cupos', 'Valores'],
                                ['Ocupado', cupos.cupo_reservado],
                                ['Libre', cupo_libre]
                            ]);


                            var options = {
                                width: '80%',
                                height: '300px',
                                is3D: true,
                                colors: ['#115E67', '#A4A4A4'],
                                legend: 'bottom',
                                backgroundColor: {fill: 'transparent'},
                                chartArea: {width: "80%"}, //left: 0, top: 10,

                            };

                            var chart = new google.visualization.PieChart(document.getElementById(turno + "_" + fecha));
                            chart.draw(data, options);

                        });
                    });

                }

                $(window).resize(function () {
                    drawChart();
                });

                $('body').on('change', '#select_fecha_reserva_restaurantes', function () {

                    google.load('visualization', '1', {
                        packages: ['timeline'],
                        callback: drawChart
                    });

                });

            }

            return;

        },
        error: function (err) {
            //alert(JSON.stringify(err));
        }
    });

}


