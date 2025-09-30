$( document ).ready(function() {

    window.onload = function () {
        if (typeof history.pushState === "function") {
            history.pushState("jibberish", null, null);
            window.onpopstate = function () {
                history.pushState('newjibberish', null, null);
                // Handle the back (or forward) buttons here
                // Will NOT handle refresh, use onbeforeunload for this.

                if($('.fondo-popup-zoom').length > 0){
                    $('.back-popup').click();
                }else{
                    $('.volver-index').click();
                }


                return;
            };
        }
        else {
            var ignoreHashChange = true;
            window.onhashchange = function () {
                if (!ignoreHashChange) {
                    ignoreHashChange = true;
                    window.location.hash = Math.random();
                    // Detect and redirect change here
                    // Works in older FF and IE9
                    // * it does mess with your hash symbol (anchor?) pound sign
                    // delimiter on the end of the URL
                    // alert(2222);
                }
                else {
                    // alert(3333);
                    ignoreHashChange = false;
                }
            };
        }
    }

    $("body").on("click", ".js_contenidos_masinfo_scroll", function(event){

        var ruta = $(this).attr('data-rutamovil');

        $(this).attr('href',ruta);
        var url = $(this).attr('href');
        //window.open(url,'_blank');
        console.log(url);

        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="'+url+'"></div>' +
            '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

    });

    $("body").on("click", ".js_contenidos_masinfo_scroll_app", function(event){

        event.preventDefault();

        var img = $(this).attr('data-imagen');
        var id_centro = $('#id_centro_global').val();
        var url = 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'+id_centro+'/imagenes/contenidos/'+img+'.jpg';


        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="'+url+'"></div>' +
            '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

    });

    // $("body").on("click", ".js_contenidos_masinfo_scroll_navidad", function(event){

    //     event.preventDefault();

    //     var img = $(this).attr('data-imagen');
    //     var id_centro = $('#id_centro_global').val();
    //     var url = 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'+id_centro+'/programa_navidad/'+img;


    //     $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

    //     //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

    //     var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="'+url+'"></div>' +
    //         '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
    //         '</div>';

    //     $('body').append(texto_html);

    // });

    $("body").on("click", ".js_contenidos_masinfo_scroll_navidad", function(event){

        event.preventDefault();

        var img_total = $(this).attr('data-imagen');
        var img_split = img_total.split(',');
        var id_centro = $('#id_centro_global').val();
        
        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup">';

        img_split.forEach(function(element) {
            var img = element;
            var ext = img.split('.');
            let extension = ext[ext.length - 1];

            var url = 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'+id_centro+'/programa_navidad/'+img;
            if(extension == 'pdf' || extension == 'PDF'){
                texto_html += '<iframe style="width: 100%; height: 100%" src="https://view.twisticdigital.com/dunas/visor_pdf/index.php?file='+url+'" frameborder="0"></iframe>';
            }else{
                texto_html += '<img class="img-popup" src="'+url+'">';
            }

        });
        texto_html += '</div>' +
        '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
        '</div>';

        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');
        $('body').append(texto_html);

    });

    $("body").on("click", ".js_contenidos_masinfo_scroll_navidad_pdf", function(event){

        event.preventDefault();

        var img = $(this).attr('data-imagen');
        var id_centro = $('#id_centro_global').val();
        var url = 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'+id_centro+'/programa_navidad/'+img;


        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><iframe style="width: 100%; height: 100%" src="https://view.twisticdigital.com/dunas/visor_pdf/index.php?file='+url+'" frameborder="0"></iframe></div>' +
            '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

    });

    $("body").on("click", ".zoom_imagen", function(event){

        var ruta = $(this).attr('src');

        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="'+ruta+'"></div>' +
            '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

    });

    $('body').on('click','a.volver-index,a.header-logo,a.sidebar-logo',function (e) {
        e.preventDefault();
        //location.reload();
        location.href ="./";
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

                if(id == 'rutas'){
                    var id_categoria = $(this).attr('id');
                    var datos = {'pagina':id,'id_ruta':id_categoria};
                }


                
                var url_pagina = $(this).attr('data-url');
                var titulo_pagina = $(this).attr('data-titulo');

                add_page_analytics(url_pagina,titulo_pagina);

                $.ajax({
                    url: 'index.php',
                    data: datos,
                    type:'GET',
                    beforeSend: function(xhr) {
                        //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
                    },
                    success: function(json){
                        //console.log(json);
                        $('#content-general').fadeOut(100,function () {
                            $('#content-general').html(json);
                            $('#content-general').fadeIn(300);
                        });

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
                // window.location.href = "index.php?id_hotel=" + id_hotel;
                window.location.href = "./";
                /*console.log(json);*/
            },
            error: function(err) {
                //alert(JSON.stringify(err));
            }
        });

        return false;

    });

    $("body").on('click','.js_boton_carta_vinos',function(e){

        var id_carta = $(this).attr('id');

        $('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');

        var nombre_carta = $(this).html();

        var url_pagina = 'gastronomia/carta';
        var titulo_pagina = 'Carta '+ nombre_carta;

        add_page_analytics(url_pagina,titulo_pagina);

        //user-scalable=no, initial-scale=1.0, maximum-scale=1.0
        // var contenido_carta = $('.js_carta_vinos').html();
        var contenido_carta = $(id_carta).html();

        var texto_html = '<div class="fondo-popup-zoom"><div class="content-popup">'+contenido_carta+'</div>' +
            '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div>' +
            '</div>';

        $('body').append(texto_html);

        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {

                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    var class_display  = "none";
                } else {

                    var class_display  = "block";
                }

                if(this.classList.contains('active')){
                    var active = true;
                }else{
                    var active = false;
                }

                $('button.accordion').removeClass('active');
                if(active){
                    this.classList.remove("active");
                }else{
                    this.classList.add("active");
                }

                $('.content-popup > .panel').css('display','none');
                panel.style.display = class_display;



            });
        }

    });

    // $("body").on("click", ".js_info_dinamica", function (event) {

    //     event.stopPropagation();
    //     var obj = $(this);
    //     var id_carta = $(this).attr("data-id");
    //     var title = $(this).attr("data-titulo");


    //     $.ajax({
    //         type: "POST",
    //         url: "./index.php?pagina=get_cartas_dinamicas&id_carta=" + id_carta,
    //         success: function (res) {

    //             var contenido_carta = JSON.parse(res);
    //             // console.log(datos)
    //             console.log('click carta vinos');

    //             //$('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');


    //             var texto_html = '<div style="display: none" class="fondo-popup-zoom"><div class="content-popup content-img-zoom">' + contenido_carta + '</div>' +
    //                 '<div class="back-popup"><img src="https://view.twisticdigital.com/lopesan/contenido/_general/feelapp/iconos/back.svg" alt=""></div>' +
    //                 '</div>';


    //             $('.fondo-popup-zoom').remove();
    //             $('body').append(texto_html);
    //             $('.fondo-popup-zoom').fadeIn(500);


    //             // obj.parents(".content").popup({
    //             //     title: title,
    //             //     titleClass: "eventoDetailTitle",
    //             //     contenido: datos,
    //             //     contenidoClass: "eventoDetailContent",
    //             //     contenedorClass: "overlayWrapper popup-element demo_popup",
    //             //     renderCallback: function () {
    //             //         $(obj).parents(".menuContentWrapper").click(function (event) {
    //             //             if ($(".demo_popup").size() > 0) {
    //             //                 event.stopPropagation();
    //             //                 $(this).removeClass("filter1");
    //             //                 $(this).find(".filter1").removeClass("filter1");
    //             //                 $(".demo_popup").fadeOut(function () {
    //             //                     $(".overlayBackground").remove();
    //             //                     $(this).remove();
    //             //
    //             //                 })
    //             //             }
    //             //         });
    //             //     }
    //             // });
    //         }
    //     });
    // });

    // $('body').on("click","a.js_info_dinamica,a.js_info_dinamica_v2", function (e) {
    //     e.preventDefault();
    //     return;
    // });

    // $("body").on("click", ".js_info_dinamica_v2", function (event) {

    //     event.stopPropagation();
    //     var obj = $(this);
    //     var id_carta = $(this).attr("data-id");
    //     var title = $(this).attr("data-titulo");


    //     $.ajax({
    //         type: "POST",
    //         url: "./index.php?pagina=get_cartas_dinamicas_fondo&id_carta=" + id_carta,
    //         success: function (res) {

    //             var contenido_carta = JSON.parse(res);
    //             // console.log(datos)
    //             console.log('click carta vinos');

    //             //$('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');


    //             var texto_html = '<div style="display: none" class="fondo-popup-zoom"><div class="content-popup content-img-zoom">' + contenido_carta + '</div>' +
    //                 '<div class="back-popup"><img src="https://view.twisticdigital.com/lopesan/contenido/_general/feelapp/iconos/back.svg" alt=""></div>' +
    //                 '</div>';


    //             $('.fondo-popup-zoom').remove();
    //             $('body').append(texto_html);
    //             $('.fondo-popup-zoom').fadeIn(500);


    //             // obj.parents(".content").popup({
    //             //     title: title,
    //             //     titleClass: "eventoDetailTitle",
    //             //     contenido: datos,
    //             //     contenidoClass: "eventoDetailContent",
    //             //     contenedorClass: "overlayWrapper popup-element demo_popup",
    //             //     renderCallback: function () {
    //             //         $(obj).parents(".menuContentWrapper").click(function (event) {
    //             //             if ($(".demo_popup").size() > 0) {
    //             //                 event.stopPropagation();
    //             //                 $(this).removeClass("filter1");
    //             //                 $(this).find(".filter1").removeClass("filter1");
    //             //                 $(".demo_popup").fadeOut(function () {
    //             //                     $(".overlayBackground").remove();
    //             //                     $(this).remove();
    //             //
    //             //                 })
    //             //             }
    //             //         });
    //             //     }
    //             // });

    //         }
    //     });
    // });


    // $("body").on("click", ".js_info_dinamica_v3", function (event) {

    //     event.stopPropagation();
    //     var obj = $(this);
    //     var id_carta = $(this).attr("data-id");
    //     var title = $(this).attr("data-titulo");

    //     $.ajax({
    //         type: "POST",
    //         url: "./index.php?pagina=get_cartas_dinamicas_fondo_new&id_carta=" + id_carta,
    //         success: function (res) {

    //             var contenido_carta = JSON.parse(res);

    //             contenido_carta = contenido_carta['contenido'];
    //             // console.log(datos)
    //             console.log('click carta vinos');

    //             //$('meta[name=viewport]').attr('content', 'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0');


    //             // var texto_html = '<div style="display: none" class="fondo-popup-zoom"><div class="content-popup content-img-zoom">' + contenido_carta + '</div>' +
    //             //     '<div class="back-popup"><img src="https://view.twisticdigital.com/lopesan/contenido/_general/feelapp/iconos/back.svg" alt=""></div>' +
    //             //     '</div>';


    //             var texto_html = contenido_carta;

    //             $('.fondo-popup-zoom').remove();
    //             $('body').append(texto_html);
    //             $('.fondo-popup-zoom').fadeIn(500);

    //         }
    //     });
    // });

    
    $("body").on("click", ".abrir_accordion", function () {

        var div_actual = $(this).next();

        if ($(this).next().is(':visible')) {
            var visible = 1;
        }else{
            var visible = 2;
        }

        // if ($(this).next().hasClass('panel')) {
        //     if ($(this).next().is(':visible')) {
        //         $(this).removeClass('active');
        //         $(this).next().addClass('active').hide('slow');
        //     } else {
        //         $(this).addClass('active');
        //         $(this).next().addClass('active').show('slow')
        //     }
        // }

        $('.abrir_accordion').removeClass('active');
        $('.panel').addClass('active').hide();

        if (div_actual.hasClass('panel')) {
            if (visible == 1) {
                $(this).removeClass('active');
                $(this).next().addClass('active').hide();
            } else {
                $(this).addClass('active');
                $(this).next().addClass('active').show()
            }
        }


    });

    // $('body').on('click','.accordion_solo',function(){

    //     var id = $(this).attr('id');

    //     if($(id).hasClass('active')){
    //         $(id).removeClass('active');
    //     }else{
    //         $(id).addClass('active');
    //     }

    // });


    $('body').on('click', '.add-button', function (e) {

        e.preventDefault();

        //if(isMobileSafari()){

        var navegador = obtenerIdNavegador();
        var sistema_operativo = getMobileOperatingSystem();

        //if((sistema_operativo != 'Android') && (navegador != 'Chrome')){

        var datos = {'pagina': 'add_screen', 'tipo_navegador': navegador, 'sistema_operativo': sistema_operativo};

        $.ajax({
            url: 'index.php',
            data: datos,
            type: 'GET',
            beforeSend: function (xhr) {
                //xhr.setRequestHeader("Authorization", "Basic "+btoa(username+':'+password));
            },
            success: function (json, xhr, settings) {
                //console.log(json);
                $('#content-general').html(json);
                $('#content-general').hide();
                $('#content-general').html();
                $('#content-general').fadeIn(1000);
                $('body').scrollTop(0);
                $('#sidebar-tap-close').click();

            },
            error: function (err) {
                //alert(JSON.stringify(err));
            }
        });

        //}

        //}

    });


    /*************** Acceso Directo ******************/

    let deferredPrompt;
    const addBtn = document.querySelector('.add-button');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        // Update UI to notify the user they can add to home screen
        addBtn.style.display = 'block';

        addBtn.addEventListener('click', (e) => {
            // hide our user interface that shows our A2HS button
            addBtn.style.display = 'none';
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                    addBtn.style.display = 'none';
                } else {
                    console.log('User dismissed the A2HS prompt');
                }
                deferredPrompt = null;
            });
        });
    });


});


function obtenerIdNavegador() {
    var
        aKeys = ["MSIE", "Firefox", "Safari", "Chrome", "Opera"],
        sUsrAg = navigator.userAgent, nIdx = aKeys.length - 1;

    for (nIdx; nIdx > -1 && sUsrAg.indexOf(aKeys[nIdx]) === -1; nIdx--) ;

    return aKeys[nIdx];
}

function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "WindowsPhone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return false;
}

function add_page_analytics(url_pagina,titulo_pagina){

    var id_analytics = $('input#id_analytics').val();
    var url_pagina_global = $('input#url_web_input').val();
    var nombre_hotel = $('input#url_web_input').val();

    if(url_pagina && titulo_pagina && id_analytics && url_pagina_global && nombre_hotel){

        var url_final = url_pagina_global + '/' + url_pagina;

        var titulo_web = document.title;

        titulo_pagina = titulo_web + ' - ' + titulo_pagina;

        console.log(titulo_pagina);
        console.log(url_final);
        //console.log(id_analytics);

        gtag('event', 'page_view', {
            'page_title': titulo_pagina,
            'page_location': url_final,
            'send_to': id_analytics
        });
        
    }else{
        console.log('faltan datos analytics');
    }

    return;

}


