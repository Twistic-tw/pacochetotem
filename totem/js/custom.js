/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var delayTransitionsBottomSlider = 10000;        //indica cada cuantos miliseg se alterna de imagen abajo
var delayAnimationBottomSlider = 250;          //indica la velocidad con que se desplazan las imagenes
var delayScreenSaverActivationDelay = 210000;          //indica cada cuantos miliseg salta el salvapantalla 3.5minutos
var delayScreenSaverDeactivationDelay = 60000;   //iundica cuanto tarda en desactivarse el salvapantalla una vez activado
var screenSaverTimeOutId;
var screenSaverAnimationInDelay = 500;         //tiempo que tarda la animacion en entrar
var screenSaverAnimationOutDelay = 200;        //tiempo que tarda la animación en salir
var mainMenuElementSlideSpeed = 300;
var overlayLoadingDelay = 1500;                 //ms hasta que entra el loading gif
var AgendaDatepickerRefreshInterval = 1000 * 60 * 15;       //15minutos
var centroLatitud, centroLongitud;
var timeBeforeClosingOpenTabs = 3*60*1000;        //3 minutos.
var reloadIntervalTime = 15*60*1000;             //15minutos


var lockForLoadDestino = false;

//** variable para almacenar los dias de la semana segun el idioma **/
var diasSemanasIdiomas = {
    es: {0: "Domingo", 1: "Lunes", 2: "Martes", 3: "Miercoles", 4: "Jueves", 5: "Viernes", 6: "Sabado"},
    en: {0: "Sunday", 1: "Monday", 2: "Tuesday", 3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"},
    de: {0: "Sonntag", 1: "Montag", 2: "Dienstag", 3: "Mittwoch", 4: "Donnerstag", 5: "Freitag", 6: "Samstag"},
    pl: {0: "Niedziela", 1: "Poniedziałek ", 2: "Wtorek", 3: "Środa", 4: "Czwartek ", 5: "Piątek", 6: "Sobota"},
    fr: {0: "Dimanche", 1: "Lundi", 2: "Mardi", 3: "Mercredi", 4: "Jeudi", 5: "Vendredi", 6: "Samedi"},
    ru: {0: "", 1: "", 2: "", 3: "", 4: "", 5: "", 6: ""}
};

var mesesIdiomas = {
    es: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    en: ['January','February','March','April','May','June','July','August','September','October','November','December'],
    de: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
    pl: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
    fr:  ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    ru: {0: "", 1: "", 2: "", 3: "", 4: "", 5: "", 6: ""}
};

var diasSemanasIdiomasCorto = {
    es: {0: "Do", 1: "Lu", 2: "Ma", 3: "Mi", 4: "Ju", 5: "Vi", 6: "Sa"},
    en: {0: "Su", 1: "Mo", 2: "Tu", 3: "Wed", 4: "Th", 5: "Fr", 6: "Sa"},
    de: {0: "So", 1: "Mo", 2: "Di", 3: "Mi", 4: "Do", 5: "Fr", 6: "Sa"},
    pl: {0: "Niedziela", 1: "Poniedziałek ", 2: "Wtorek", 3: "Środa", 4: "Czwartek ", 5: "Piątek", 6: "Sobota"},
    fr: {0: "Di", 1: "Lu", 2: "Ma", 3: "Me", 4: "Je", 5: "Ve", 6: "Sa"},
    ru: {0: "", 1: "", 2: "", 3: "", 4: "", 5: "", 6: ""}
};

var idioma;
var idiomasCodigos = {
    1 : "es",
    2 : "en",
    3 : "de",
    4 : "fr",
    5 : "pl",
    6 : "ru"
}



var mensaje_postales_final = {es:"Muchas gracias por utilizar este servicio", en:"Thanks for use this service", de:"Vielen Dank für den Einsatz dieser Service", fr:"Merci beaucoup d'utiliser ce service"};
var mensaje_postales_final_error = {es:"No se ha podido enviar el mensaje", en:"Error, try to send after.", de:"Fehler, versuchen Sie, nach senden.", fr:"Le message n'a pas pu être envoyé"};


$(window).ready(function() {

    $.ajaxSetup({
        timeout: 30000
    });

    delayScreenSaverActivationDelay = $("#screenSaver").attr('data-screensaver_time');

    delayScreenSaverDeactivationDelay = $("#screenSaver").attr('data-screensaver_time_duration');

    $("#time").click(function(event){
        event.stopPropagation();
        screenSaver('activate')
    })

    $("#date").click(function(event){
	console.log("test");
        event.stopPropagation();
        screenSaver('activate')
    })


    /**********************************************************************************************
     **************                     ESPECIFICACIONES GENERALES               ******************/
    centroLatitud = $("#centroLatitud").text();
    centroLongitud = $("#centroLongitud").text();
    
    idioma = idiomasCodigos[ $("#idiomaActivo .idiomaActivo").text() ];

    var id_centro = $("#id_centro").text();


//////////////////////////////////////////Para el thumb de la agenda
    $("body").on("click", ".js_show_destacado_thumb", function(){
      var id = $(this).attr("id");
      var  id_nuevo = id.substring(1);

        //Ocultamos todos los elementos
        $('.js_show_destacado').removeClass('js_muestra_destacado');
        $('.js_show_destacado').addClass('js_oculta_destacado ');

        //Mostramos el selccionado
       $('#'+id_nuevo).removeClass('js_oculta_destacado ');
       $('#'+id_nuevo).addClass('js_muestra_destacado');


      console.log (id_nuevo);


    });

//    $("body").on("click", ".js_click_mapa", function(){
//        alert('aaqaa');
//        console.log('aaaaa');
//
//    });
//
//
//    $("body").on("click", "#js_click_mapa_hotel", function(){
//
//
//        console.log ('mapa');
//
//
//    });


///////////////////////////////////////////////////////////////
    
    //establecemos la actualizacion de la fecha
    setInterval(function(){
        var d = new Date();
        var m = d.getMinutes();
        var h = d.getHours();
        if ( m < 10){
            m = "0" + m;
        }
        if ( h < 10 ){
            h = "0" + h;
        }
        $("#time").html(h + "." + m);
    },30*1000);
    
    var permitir_arrastre=0;
    //if the browser is IE4+
    document.onselectstart = new Function("return false");


    //if the browser is NS6
    if (window.sidebar) {
        document.onmousedown = disabletext;
        //document.onclick = reEnable;
    }
    document.oncontextmenu = function(){return false}



    ////////////////         LISTENER IMPORTANTES Y PRINCIPALES
    $("a.no_link").click(function(event){
        event.preventDefault();    
    });
    
    $("body").on("click", ".reservas,.reservarEspecial", function(){
        
        if ( $(".loadingBg").size()>0){
            return true;
        }
        $("body").append("<div id='overlayLoadingContent' class='loadingBg'><img style='background: none repeat scroll 0 0 #232323;height: auto;margin-left: 144px;margin-top: 206px;padding: 10px;width: auto;' src='../../../contenido_proyectos/pacoche/_general/imagenes/reservas_proximamente.png'></div>");
        
        setTimeout( function() {
            $("#overlayLoadingContent").fadeOut("300", function(){
                $(this).remove();
            });
        }, 4500);
        
        $("#overlayLoadingContent").click(function(){
            $(this).remove();
        })
    });


    $("body").on("click", ".foto_banner_vertical", function(event){

        event.preventDefault();


        var foto = ($(this).attr('href'));



        if ( $(".loadingBg").size()>0){
            return true;
        }
        $("body").append(" + <div class='loadingBg flex_center'>" +
            "<div class='flex_baseline' style='height: 1500px;overflow-y: scroll;'>" +
            "<img style='width:990px; height:auto; margin-top:200px;' class='zoom-in'  src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/imagenes/contenidos/"+foto+"_"+idioma+".jpg'>" +
            "</div>" +
            "</div>");




        setTimeout( function() {
            $("#overlayLoadingContent2").fadeOut("300", function(){
                $(this).remove();
            });
        }, 300000);


        $(".loadingBg").click(function(){
            $(this).remove();
        })


    });;


    $("body").on("click", ".foto_banner_vertical_idioma", function(event){

        event.preventDefault();


        var foto = ($(this).attr('href'));



        if ( $(".loadingBg").size()>0){
            return true;
        }
        $("body").append(" + <div class='loadingBg'>" +
            "<div id='' style='height:1285px; width:900px; margin-left: 85px; margin-top: 280px; overflow-y: scroll;'>" +
            "<img style='900px;height:auto'  src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/imagenes/contenidos/"+foto+"_"+idioma+".jpg'>" +
            "</div>" +
            "</div>");


        setTimeout( function() {
            $("#overlayLoadingContent2").fadeOut("300", function(){
                $(this).remove();
            });
        }, 300000);


        $(".loadingBg").click(function(){
            $(this).remove();
        })


    });;

    ////Click en los iconos de Eurocopa

    $("body").on("click", ".load_ajax_eurocopa", function(event) {

        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];
            var href = $(this).data("accion");

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);

                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }

                target = $("#eurocopa-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);

                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo
                        inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares


                    });
                });

                lockForLoadDestino = false;



            }).fail(function(){

                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });
    ////////////////////////////////////////////////////////////////////////////////////////

    
    $("body").on("click", ".alert", function(){
        
        if ( $(".loadingBg").size()>0){
            return true;
        }
        $("body").append("<div id='overlayLoadingContent' style='background: rgba(0, 0, 0, 0.74);' class='loadingBg'><div class='overlayContent2'><div class='close2'></div><div class='overlyaTitle'>" + $(this).find(".alert_title").html() + "</div><div class='overlayMsg'>" + $(this).find(".alert_content").html() + "</div></div></div>");
        
        /*
        setTimeout( function() {
            $("#overlayLoadingContent").fadeOut("300", function(){
                $(this).remove();
            });
        }, 4500);
        */
        
        $("#overlayLoadingContent").click(function(){
            $(this).remove();
        })
    });

    $("body").on("click", ".css_guaguas_tarjeta_informacion_qr_masinfo", function(event){
       
        event.stopPropagation();
        var obj = $(this);
        var linea= $(this).attr("data-linea");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/guaguas/"+linea+".png'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });


    
    $("body").on("click", ".to_flip", function() {
        $(this).flip_municipios();
        
        $(this).parent().find(".to_flip").toggleClass("displayNone");
    });
    
    //para cerrar elementos de agenda cuando haces click fuera
    // $("body").click(function(event){
    //     if ( $(".popup-element:visible").size()>0 ) {
    //         event.stopPropagation();
    //         $(".popup-element:visible").hide().children(".close").click();
    //     }
    // });
    ////////////////         PARA EL SCREENSAVER        
    
    screenSaverTimeOutId = setTimeout("screenSaver('activate')", delayScreenSaverActivationDelay);

    $(document).bind("mousemove click", function() {
        screenSaver("");
        setTimeoutForClosing();
        setTimeoutForRefresh();
    });

    // overlay para el video
//    $("video").each(function(){
//        $(this).before("<div class='overlayVideoElement'></div>");
//        $(this).prev("div.overlayVideoElement").css({
//            width: $(this).width()+"px",
//            height: $(this).height()+"px",
//            top: $(this).offset().top+"px",
//            left: $(this).offset().left+"px"
//        });
//    });
    $("body").on("click", ".js_contenidos_masinfo", function(event){
       
        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".png'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });


    $("body").on("click", ".js_contenidos_masinfo_idioma", function(event){
       
        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+"_"+idioma+".png'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });


    $("body").on("click", ".js_contenidos_masinfo_scroll", function(event){

        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".png'>",
            contenidoClass: "eventoDetailContent2",
            contenedorClass: "overlayWrapper2 popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

//
    $("body").on("click", ".js_click_mapa", function(event){

        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        imagen = 'mapa';


        $(this).parents(".content").popup({
            title: '',
            titleClass: "",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".jpg'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element mapa_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });




    $("body").on("click", ".js_contenidos_masinfo_jpg", function(event){
        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");
        var timestamp = Date.now()

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".jpg?"+timestamp+"'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup css_contenidos_masinfo_jpg",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });



    $("body").on("click", ".js_contenidos_masinfo_agenda", function(event){

        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+"_"+idioma+".png'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup popup_agenda",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

    $("body").on("click", ".js_contenidos_masinfo_agenda_jpg", function(event){

        event.stopPropagation();
        var obj = $(this);
        var imagen= $(this).attr("data-imagen");
        var title = $(this).attr("data-titulo");

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".jpg'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup popup_agenda",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

    // cargamos mediante ajax los detalles relacionados con la agenda
    setTimeout(function() {

            //Inicializo el carrusel de los dias
            owl_agenda_pinero();

            //Hago click en el primer dia, que es el dia actual
            $("#js_agenda_dia0").click();

    }, 100);


    ////   PARA ESTABLECER EL COLOR DEL ELEMENTO DEL MENU EN FUNCIÓN DE LA CLASE PADRE
    ////   y tambien establecer por ejemmplo el borde y el color de loe elementos contenidos
    $("div.mainMenuElement").each(function() {
        var color = $(this).attr("color");
        $("svg path:first", $(this)).attr("fill", color);


        var target = $(this).find("a").attr("href");

        $(target).setColorsFromMenuSection(color)

    });
    
    

    /**********************************************************************************************
     **************                  FUNCIONES DE LLAMADAS A PLUGINS             ******************/
    
    initializeKeyboardListeners();
    

    /****************       ACTUALMENTE NO ESTA EN USO, REVIZARLO      *********/
    //le aplicamos formato de pestaña a la clase de agenda
    $(".agendaTabs").tabs({
        activate: function() {
            //para alternar las clases de las pestañas de activo a inactivo
            $(".actividad-active, .actividad-inactive").each(function() {
                if ($(this).hasClass("actividad-active") && $(this).hasClass("actividad-inactive")) {

                } else {
                    $(this).toggleClass("actividad-active").toggleClass("actividad-inactive");
                }
            });
        }
    });

    //formato de selector de fechas
    $(".fecha").datepicker();
	
	var today = new Date();
    var hoy = today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
	
	
    var cancel_datepicker = 0; //Funcion que controla la cancelacion de los click del datepicker
    $("#agendaCalendario").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        monthNames : mesesIdiomas[idioma],
        dayNamesMin: diasSemanasIdiomasCorto[idioma],
        beforeShowDay: function(date) {
            /* aqui validmos si el dia aparece en la agenda de del hotel o de la isla                  * 
             * si aparece en la agenda el hotel le añado una clase, si aparece en la agenda del hotel  * 
             * le añado otra. Luego al cambiar de "pestaña" se hace un toggleClass entre todas y listo */

            var activeId = $("#agendaActividades li.ui-tabs-active a").attr("href");
            var inactiveId = $("#agendaActividades li.ui-tabs-active").siblings("li").children("a").attr("href");

            var datosHotel = "";
            var datosIsla = "";
            datosHotel = $(".fechasActividades", activeId).text();
            datosIsla = $(".fechasActividades", inactiveId).text();

            var obj = new Array(2);

            obj[0] = false;
            obj[1] = "";

            var fechasActivas_tabActive = false;
            var fechasActivas_tabInactive = false;

            if (datosHotel != "") {
                try{
                    fechasActivas_tabActive = JSON.parse(datosHotel);
                }
                catch(e) {
                    //que hago?
                    return;
                }
            }
            if (datosIsla != "") {
                try{
                    fechasActivas_tabInactive = JSON.parse(datosIsla);
                }
                catch(e) {
                    //que hago?
                    return;
                }
            }

            var t = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
            //var t = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + (date.getDate())).slice(-2);

            if ((fechasActivas_tabActive && fechasActivas_tabActive[t]) || (t== hoy)) {
                obj[0] = true;
                obj[1] += "actividad-active ";
            }

            // if (fechasActivas_tabInactive && fechasActivas_tabInactive[t]) {
            //     obj[0] = true;
            //     obj[1] += "actividad-inactive ";
            // }

            return obj;
        },
        dateFormat: "dd-mm-yy",
        onSelect: function(sDate) {      //sdate es la fecha en formato string

            //cerramos cualquier posible elemento abierto
            $(".close, .back").click();


            if (!cancel_datepicker) { //sino esta cancelado
                cancel_datepicker = 1; //lo cancelo
                var datepickerParent = $("#agendaCalendario");

                datepickerParent.before("<div class='overlayDatepicker'></div>");
                datepickerParent.siblings("div.overlayDatepicker").css({
                    position: "absolute",
                    left: datepickerParent.css("left"),
                    top: datepickerParent.css("top"),
                    paddingTop: datepickerParent.css("paddingTop"),
                    paddingLeft: datepickerParent.css("paddingLeft"),
                    width: datepickerParent.width() + "px",
                    height: datepickerParent.height() + "px",
                    opacity: "0.001",
                    zIndex: "10"
                });

                //aqui hacemos una peticion para obtener las actividades de ese dia y las cargamos en la lista. 
                //En caso de que se haga click sobre un elemento con la clase "active" se llama para hote, si 
                //no tiene dicha clae se llama para isla y se cambia de pestaña
                $("#tDiv").remove();
                $("body").append("<div id='tDiv' style='display: none;'></div>");

                $("#tDiv").load("index.php?ajax=agendaRequest&request=actividades&fecha=" + sDate, function(response, statusText, request) {

					verificar_actualizacion(response);
                    if (request.status == 200) {
                        var datosObtenidos;
    
                        try{
                            datosObtenidos = JSON.parse(response);
                        }
                        catch(e) {
                            //que hago?
                            return;
                        }

                        var child = $("ul.actividadesList li:first");
                        var target;
                        var id;
                        var j;
                        var i;

                        /** obtengo un listado (ya viene en el formato html adecuado) de las actividades
                         * y ahora recorro las listas eliminando los hijos y añadiendo nuevos elementos
                         * La respuesta del servidor viene con la estructura:
                         * array['actividadesHote'] => htmlActividadesHotel
                         * array['actividadesIsla'] => htmlActividadesIsla */

                        $(".actividadesList").fadeOut("fast", function() {
                            $("#actividadesHotel ul.actividadesList").children().remove();
                            $("#actividadesHotel ul.actividadesList").append(datosObtenidos.actividadesHotel);

                            $("#actividadesIsla ul.actividadesList").children().remove();
                            $("#actividadesIsla ul.actividadesList").append(datosObtenidos.actividadesIsla);

                            datepickerParent.siblings("div.overlayDatepicker").remove();
                            cancel_datepicker = 0; //desbloqueo cuando cargo el dia



                            $(this).fadeIn("fast", function() {
                                $(this).setSlimScroll();
                            });
                        });


                        var activeId = $("li.ui-tabs-active a").attr("href").replace("#", "");

                        if (datosObtenidos[activeId] == null || datosObtenidos[activeId].length == 0 || datosObtenidos[activeId].length == null) {
                            $("li.ui-tabs-active").siblings("li").children("a").click();
                        }

                        //PENDIENTE: llamar a la funcion para establecer el evento activo en el tiempo
                    }

                });
            }
            else
                return false;
        }
    }).show();     //mostrar el calendario en la agenda

    //para el scroll personalizado de la agenda
    $(".scroll").each(function() {
        //$(this).setSlimScroll();
    });



    /**********************************************************************************************
     **************                     INICIALIZACION DE LISTENERS              ******************/



    //////////////Calendario en formato owl carrusel

    $("body").on("click", ".js_click_dia", function(event){

        //saco el valor de la fecha actual
        var sDate = $(this).data('fecha');

        //Quito los activos
        $(".js_click_dia").removeClass('css_dia_activo');
        $(this).addClass('css_dia_activo');

        $("#tDiv").remove();
        $("body").append("<div id='tDiv' style='display: none;'></div>");

        $("#tDiv").load("index.php?ajax=agendaRequest&request=actividades&fecha=" + sDate, function(response, statusText, request) {

        verificar_actualizacion(response);
        if (request.status == 200) {
            var datosObtenidos;

            try{
                datosObtenidos = JSON.parse(response);
            }
            catch(e) {
                //que hago?
                return;
            }

            var child = $("ul.actividadesList li:first");
            var target;
            var id;
            var j;
            var i;

            /** obtengo un listado (ya viene en el formato html adecuado) de las actividades
             * y ahora recorro las listas eliminando los hijos y añadiendo nuevos elementos
             * La respuesta del servidor viene con la estructura:
             * array['actividadesHote'] => htmlActividadesHotel
             * array['actividadesIsla'] => htmlActividadesIsla */

            $(".actividadesList").fadeOut("fast", function() {
                $("#actividadesHotel ul.actividadesList").children().remove();
                $("#actividadesHotel ul.actividadesList").append(datosObtenidos.actividadesHotel);

                $("#actividadesIsla ul.actividadesList").children().remove();
                $("#actividadesIsla ul.actividadesList").append(datosObtenidos.actividadesIsla);


                $(this).fadeIn("fast", function() {
                    $(this).setSlimScroll();
                });
            });


            var activeId = $("li.ui-tabs-active a").attr("href").replace("#", "");

            if (datosObtenidos[activeId] == null || datosObtenidos[activeId].length == 0 || datosObtenidos[activeId].length == null) {
                $("li.ui-tabs-active").siblings("li").children("a").click();
            }

            //PENDIENTE: llamar a la funcion para establecer el evento activo en el tiempo
        }

    });

    });



    
    ////    PARA CERRAR AL CLIKEAR FUERA DEL MENU DESPLEGADO
    $("body").mousedown(function(event) {
        var target = $(event.target);

        $(".clickActive").each(function() {
            if ( $(target).hasClass("clickActive") ||
                    $(target).children(".clickActive").size() > 0 ||
                    $(target).parents(".clickActive").size() > 0 ||
                    $(target).hasClass("clickActiveRelated")) {
                event.stopPropagation();
                return false;
            }
            else {
                if ($(this).hasClass("Effect1")) {
                    $(this).animateEffect1();
                }
                else if ($(this).hasClass("Effect2")) {
                    $(this).animateEffect2();
                }
                else {
                    if ($(this).hasClass("ui-keyboard")) {
                        $(this).fadeOut("normal");
                    }
                    else {
                        $(this).hide();
                    }
                    $(this).removeClass("clickActive");
                }
            }
        });
    });

    $("body").on("click", ".elemento_como_llegar", function(event){
        event.stopPropagation();
        var obj = $(this);
        var title = $(this).find(".text_wrapper").text();

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: $(this).find(".imagen_como_llegar_centrado").html(),
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

    $("body").on("click", ".agenda_personalizada", function(event){

        $('.close').click();
    });


    $("body").on("click", ".elemento_reservar", function(event){
        event.stopPropagation();
        var obj = $(this);
        var title = "Reservar"

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_1/imagenes/reserva.png'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

////////////////////////////////////////////////////////////////////////////////////////////////////////
///Para hacer click en la carta de restaurante principal magnolia RIU
    $("body").on("click", ".js_restaurante_carta_krystal", function(event){
       
        event.stopPropagation();
        var obj = $(this);
        var title = "KRYSTAL"

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/imagenes/restaurante/carta_kristal_"+idioma+".PNG'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

////////////////////////////////////////////////////////////////////////////////////////////////////////
///Para hacer click en la carta de restaurante ocean RIU
    $("body").on("click", ".js_restaurante_carta_ocean", function(event){
       
        event.stopPropagation();
        var obj = $(this);
        var title = "OCEAN"

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/imagenes/restaurante/carta_ocean_"+idioma+".PNG'>",
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });


////////////////////////////////////////////////////////////////////////////////////////////////////////


    $("body").on("click", ".demo_wrapper_estilo_1 .elemento", function(event){
        event.stopPropagation();
        var obj = $(this);
        var title = $(this).find(".elemento_nombre").text();

        $(this).parents(".content").popup({
            title: title,
            titleClass: "eventoDetailTitle",
            contenido: $(this).find(".display_info").html(),
            contenidoClass: "eventoDetailContent",
            contenedorClass: "overlayWrapper popup-element demo_popup",
            renderCallback: function(){
                $(obj).parents(".menuContentWrapper").click(function(event){
                    if ( $(".demo_popup").size()>0) {
                        event.stopPropagation();
                        $(this).removeClass("filter1");
                        $(this).find(".filter1").removeClass("filter1");
                        $(".demo_popup").fadeOut(function(){
                            $( ".overlayBackground").remove();
                            $(this).remove();

                        })
                    }
                });
            }
        });
    });

    ////   PARA CUANDO se le da a cerrar a algun popup
    $("body").on("click", ".cerrar_popup", function(event) {
        if ( $(this).hasClass("remove_popup") ) {
            $(this).parent(".closeWrapper").fadeOut(function(){
                $(this).remove();
            });
        }
        else {
            $(this).parent(".closeWrapper").fadeOut(function(){
                if ( $(this).hasClass("wrapper-zonas-bg") ) {
                    $(this).find(".contenido-listado").html("");
                } 
            });
        }
    });

    ////   PARA CUANDO se le da a cerrar algun objeto
    $("body").on("click", ".close, .back", function(event) {
        $('.video_principal_totem').get(0).play();


       //Fix para el cierre de la imagen de arriba cuando vuelves del tiempo (por ejemplo en el mapa)
       if (!($(".back").hasClass("menu_tiempo_volver_texto"))) {
        $(".overlayBannerHeader").fadeOut("200", function(){
            $(this).remove();
        });
       }

/*        $(".overlayBannerHeader").fadeOut("200", function(){
            $(this).remove();
        });*/

            if ( $(".double_size").size()>0){

                $(".double_size").parents(".menuContent").animate({
                    height: "556px"
                }, 240, function(){
                    $("#pageFooterWrapper").css("zIndex", "0");
                });
            }


        ///Pantalla full
        if ( $(".full_screen").size()>0){

            $("#pageFooterWrapper").css("zIndex", "0");
            $('#meteoWrapper').css('display','block');
            $('.espera_carga_meteo').css('display','none');
             
            if ( $("#pageBottomWrapper").hasClass("bottom") && $(".full_screen").size()==1) {
                $("#pageBottomWrapper").animate({
                    bottom: "-51px"
                },200, function(){
                    $("#pageBottomWrapper.bottom").removeClass("bottom");   
                });
            }
        }

        //Para el double size desde un full screen a programacion tipo espectaculos
        if ( $('#programacionWrapper').css('display') == 'block' ){

            setTimeout(function(){
                $("#pageFooterWrapper").css("zIndex", "-2");
                $('.menuContent').css('height','996px');

            },400);

        }

        
        if ( $(this).hasClass("closeWrapper") ){
            $(this).fadeOut("200", function(){
                $(this).remove();
            })
        }
        else {
            $(this).parents(".closeWrapper").fadeOut("200", function() {
                $(this).remove();
            });
        }
        
        //mostramos cualquier elemento de menu con la clase de visibilityHidden, pero solo la inmediatamente anterior al elemento que se cierra.
        $(this).parents(".closeWrapper").prev(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250", function(){
        });
//        $(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250", function(){
//            
//        });
//      
        if ($(this).hasClass("closeIframe")){
            $(this).fadeOut("200",function(){
                $(this).remove();
            });
            $("#conoce_granCanaria").fadeOut("200",function(){
                $(this).remove();
                $(".mainMenuElement:first a").mousedown();
            })
        }
        
        if ( $(this).hasClass("animateBottom") ){
            $("#pageBottomWrapper").animate({
                bottom: "-51px"
            },400);
        }
    });
    

    ////   para establecer un listener cuando se haga click en alguno de los elementos de la agenda
//    $(".actividadesList").on("click", "li", function(event) {
//        //evaluamos que el target del click sea el li y no el span
//        $(".close").click();
//
//        if (!$(event.target).hasClass("icon-svg")) {
//            //significa que NO ha sido una delegation
//            event.stopPropagation();
//            var title = $("<div/>", {
//                html: $(this).find(".actividadTime").html() + " - " + $(this).find(".actividadTimeFin").html()
//            });
//
//            var obj = $(this);
//
//            $(this).parents(".actividadesList").popup({
//                title: title,
//                titleClass: "eventoDetailTitle",
//                contenido: $(this).find(".actividadDetails").html(),
//                contenidoClass: "eventoDetailContent",
//                contenedorClass: "overlayWrapper popup-element agenda_personalizada",
//                renderCallback: function(){
//                    $(".agenda_personalizada .eventoDetailContent").append( $(obj).find(".actividadInfo").html() );
//
//                }
//            });
//
//            $(this).parents(".actividades").append("<div class='eventDetailsWrapper closeWrapper'><div class='eventDetails'><div class='close'></div></div>");
//            var child = "<div><div class='header'><span>" + $(this).find(".actividadTime").html() + " - " + $(this).find(".actividadTimeFin").html() + "</span><span>" + $(this).find(".actividadDesc").html() + "</span></div><div>" + $(this).find(".actividadDetails").html() + "</div>";
//            $(this).parents(".actividades").find(".eventDetails").append(child);
//        }
//    });

    ////   para establecer un listener cuando se haga click en el boton de la categoria del evento de la agenda
    $(".actividadesList").on("click", ".icon-svg", function(event) {
        $(this).showSimilarEvents();
    });


    var lockForAnimation = false;
    ////   ASOCIAMOS UN LISTENER PARA CUANDO SE HACE CLICK EN ALGUN ELEMENTO DEL MENU PRINCIPAl
    $("div.mainMenuElement").mousedown(function(event) {

        //cerramos cualquier posible elemento abierto
        $(".close, .back").click();


        $(".ui-keyboard.clickActive").fadeOut("fast", function(){
            $(this).removeClass("clickActive"); 
        });
        
        
        if ($(this).hasClass("validLink")) {
            // si tiene esta clase significa que es un link a otra página por lo que dejo que fluja el link
            return true;
        }

        if (lockForAnimation) {
            return false;
        }
        
        var target = $("a", this).attr("href");      //si no tiene elementos de la pagina como target

       // En el caso de entrar en hotel o informacion simple size

        if ( ( target == "#hotelWrapper" ) || ( target == "#informacionWrapper" ) ) {
            //console.log('hotel wrapper');

            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

        }

        /* Eurocopa */
        if ( target == "#eurocopaWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','515px');

            $("#eurocopa_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }
        /* Fin de la eurocopa */

        if ( target == "#conoceWrapper" ){

            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("a[href='getConoce_Municipios']").click();
            return false;
        }


        if ( target == "#destinoWrapper" ){

            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#destino_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#lugaresWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#lugar_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#sostenibilidadWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#sostenibilidad_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#sultanWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#sultan_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#hotelesWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#hoteles_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#spaWrapper" ){
            $("#pageFooterWrapper").css("zIndex", "0");
            $('.menuContent').css('height','556px');

            $("#spa_link_id > a" ).click();
            $('.video_principal_totem').get(0).pause();
            return false;
        }

        if ( target == "#programacionWrapper" ){
            console.log("espectaculos");

            $("#pageFooterWrapper").css("zIndex", "-2");

            $(".double_size").parents(".menuContent").animate({
                height: "996px"
            }, 440);

            $(".double_size").animate({
                    marginLeft: "0px"
                }, 450,
                function() {
                    var heightOriginal = 955;
                    $(".double_size").find(".content").animate({
                            height: heightOriginal+"px"
                        }, 250,
                        function() {
                            if (  $(".double_size").hasClass("tabs_") ||  $(".double_size").hasClass("tabs") ) {
                                $( $("li.ui-tabs-active a").attr("href") ).fadeIn();
                                $(".double_size").children("ul").fadeIn();
                            }
                            else {
                                $(".double_size").children().fadeIn();
                            }
                            $(".double_size").siblings(".back").fadeIn();
                            if (typeof callback == "function") {
                                callback();
                            }
                        });
                });

            owl_programacion();

            console.log('entro aqui');

            setTimeout(function(){
                $("#pageFooterWrapper").css("zIndex", "-2");
            },500);

        }
        
        lockForAnimation = true;
        
        if ($(target).size() == 0) {                //devuelvo false
            lockForAnimation = false;
            return false;
        }

        event.preventDefault();

        var menuActive = $("div.mainMenuElement.active");

        
        /*
        var id = $(this).attr("id");
        
        if ( id=="elmt5" ){
            $("body").append("<iframe id='conoce_granCanaria' class='closeWrapper' src='http://192.168.251.3/municipios_prueba'></iframe>");
            
            setTimeout(function(){
                $("body").append("<div class='back closeIframe'>Cerrar</div>");
            },1000);
        */
        
        if ( $(menuActive).size() > 0) {
            var target = $(this);
            toogleVisibilityMenuContent(menuActive, function() {
                $(menuActive).removeClass("active");

                $(target).addClass("active");
                setMenuIconShadowColor();
                
                toogleVisibilityMenuContent($(target), function() {
                    lockForAnimation = false;
                });
                
            });
        }
        else {
            setMenuIconShadowColor();

            toogleVisibilityMenuContent($(target), function() {
                lockForAnimation = false;
                $(this).addClass("active");
            });
        }
    });
    
//    $("div.mainMenuElement").bind("click dbclick", function(){
//        return false;
//    });


    ////   LISTENER A LAS CLASES LINK ASOCIANDOLO CON SU TARGET
    $(".link").click(function(event) {
//        var target;
//        if ($(this).children(".target").size() > 0) {
//            target = $(this).children(".target");
//        }
//        else if ($(this).siblings(".target").size() > 0) {
//            target = $(this).siblings(".target");
//        }
//
//        if (!$(target).hasClass("clickActive")) {       //solo se llama al efecto de animación si NO tiene la clase de activo
//            $(target).addClass("Effect1").animateEffect1();
//        }
    });


    $("body").on("click", "#meteoWrapper, .load_municipio", function(event){

        if ( $(this).hasClass("card_ruta") ) {
            return false;
        }

        $('.espera_carga_meteo').css('display','block');
            
        setTimeout(function() {
            $('.espera_carga_meteo').css('display', 'none');
        }, 4000);

        var obj = $(this);
        
        var accion;
        if ( $(this).hasClass("load_municipio") ) {
            accion = $(this).attr("href"); 
        }
        else {
            setOverlayContentLoadingGif();
            accion = "getMeteoInfo";
        }

        var url = "http://192.168.251.5/_desarrollo/proyecto_twistic/contenido_proyectos/twistic/totem/index.php?ajax="+accion;
        var url = "index.php?ajax="+accion;


        if ( $(this).is("a") ) {
            event.preventDefault();
            initialice_loading_municipio($(this));
            url = $(this).prop("href");
        }

        $.ajax({
            url: url,
            timeout: 20000,
            type: "GET"
//            data: { file: file.name }
        }).done(function(data){
            removeOverlayContentLoadingGif();
            try{
                datos = JSON.parse(data);
                $('#meteoWrapper').css('display','none');
            }
            catch(e) {
                 return;
            }

           // $(".background_tiempo").siblings(".back").click();
            $("#tiempo_full.full_screen").remove();

            $(".contenedor_tiempo").remove();
            $(".contenedor_slider").remove();
            $(".borde_separador_abajo_2").remove();

            $("body").append(datos.meteoHTML);
           
            $("#owl-municipios").owlCarousel2({
                navigation : true,
                items : 3, //10 items above 1000px browser width
                itemsDesktop : [1000,3], //5 items between 1000px and 901px
                itemsDesktopSmall : [900,3], // betweem 900px and 601px
                itemsTablet: [600,2], //2 items between 600 and 0
                itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
            });

            $("#owl-tiempo").owlCarousel({
                  navigation : true,
                  slideSpeed : 300,
                  paginationSpeed : 400,
                  singleItem : true
            });

            $( "#accordion" ).accordion({
                collapsible: false,
                active: false
            });

//             initializeFlipListener();

            // if ($(".owl-page:first").hasClass("active")) alert("si");
            
            $(datos.meteoHTML).hide().fadeIn(200);
            
            if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
                $("#pageBottomWrapper").animate({
                    "bottom":"-533px"
                },200,function(){
                    $(this).addClass("bottom");
                });
            }

            $( "#cambiar_zona" ).click(function() {
//                $(".texto_slider").css('display','none');
//                $(".imagen_slider").css('display','block');
                $('#cambiar_zona').css('display','none');
                $('#cambiar_zona_2').css('display','block');
            });

            $( "#cambiar_zona_2" ).click(function() {
//                $(".imagen_slider").css('display','none');
//                $(".texto_slider").css('display','table-cell');
                $('#cambiar_zona_2').css('display','none');
                $('#cambiar_zona').css('display','block');
            });


                $( ".contenedor_tiempo" ).keypress(function() {
                      alert( "Handler for .keyup() called." );
                });

            
            $("#pageFooterWrapper").css("zIndex", "-2");
            
        }).fail(function(){
            //No hay conexion (se fue justo con la pantalla abierta al cambio de locaclidad)
           
            
            if ( obj.hasClass("load_municipio") ) {
                //interfaz full width abierta
                stop_loading_municipio();
                // $(".acordeon_tiempo").css('visibility','hidden');
                $('.sin_conexion').css('visibility','visible');
                
            }
            else {
                removeOverlayContentLoadingGif( );
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout(function() {
                $("#overlayLoadingContent").fadeOut("200", function() {
                    $(this).find(".back").click();
                });
                }, 2000);       
            }


             

        });
    });

    ////   PARA MOSTRAR EL IDIOMA ACTIVO
    /* $("li.idiomaActivo").each(function() {
     $("#idiomaActivo").html($(this).html());
     });*/

    ////   LISTENER PARA DESPLEGAR LA LISTA DE IDIOMAS
    $("div#idiomaActivo").click(function(event) {
        if (!$("ul", $(this).parent()).hasClass("clickActive")) {    //solo se llama al efecto de animación si NO tiene la clase de activo
            $("ul", $(this).parent()).addClass("Effect2").animateEffect2();
        }
    });

    $("body").on("click", ".load_ajax_destino", function(event) {
        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();

/*            if ( $(this).is("area") || $(this).is("a") ) {
                var href_base = $(this).attr("href").split("&")[0];    
                var href = $(this).attr("href");
            }
            else{*/
                var href_base = $(this).data("accion").split("&")[0];    
                var href = $(this).data("accion");   
           // }

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);


            /*console.log("Vamos a cargar la url: " + urlAjaxRequest);*/


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);
                
                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();
                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }
                
                target = $("#destinos-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);
                    
                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        initializeDestinos(); // Para la vista que tiene el mapa del mundo
                        //inicializa_isotope_galeria(); //Para la galeria de las imageenes 
                    });
                });
                
                lockForLoadDestino = false;


            }).fail(function(){
                
                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");
                
                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });


/****************************************************Click en back de lugares*/
/**DEstruccion de isotope en el cambio de pantallas entre lugares y destinos*/

//Click en el back de lugares
$("body").on("click", ".js_back_lugares_destroy", function(event) {
        //Destruccion masiva de los isotope

        //Oculto el div para evitar el parpadeo de la inicalizacion del isoptope
        $('.isotope_contenedor_destinos').addClass('displayNone');
        $('.contendor_filtro_pais_destinos_hotel').addClass('displayNone');

        

            //Si existe un isotope lo destruyo para evitar encadenar cargas de isotope
            if ($(".isotope_contenedor_destinos").hasClass('isotope')) {
                
                $('.isotope_contenedor_destinos').isotope('destroy');
            }  

            //Fuerzo el click para llamar nuevamente a isotope
            $('.load_ajax_destino .active').click();
                          
});

//click en al cambiar de sitios de interes a que hacer (nose si hace falta, por si acaso)
$("body").on("click", ".js_destroy_isotope", function(event) {

        //Destruccion masiva de los isotope 
        setTimeout(function() {
            if ($(".isotope_contenedor_destinos").hasClass('isotope')) {
                
                $('.isotope_contenedor_destinos').isotope('destroy');
            }  
        }, 200);    
});


    $("body").on("click", ".load_ajax_lugar", function(event) {
        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];    
            var href = $(this).data("accion");   

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);
                
                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }
                
                target = $("#lugar-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);
                    
                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo 
                        inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares
                        

                    });
                });
                
                lockForLoadDestino = false;



            }).fail(function(){
                
                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");
                
                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });


////Click en los iconos de sostenibilidad

    $("body").on("click", ".load_ajax_sostenibilidad", function(event) {

        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];    
            var href = $(this).data("accion");   

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);
                
                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }
                
                target = $("#sostenibilidad-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);
                    
                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo 
                        inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares
                        

                    });
                });
                
                lockForLoadDestino = false;



            }).fail(function(){
                
                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");
                
                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });



    $("body").on("click", ".load_ajax_sultan", function(event) {

        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];
            var href = $(this).data("accion");

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);

                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }

                target = $("#sultan-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);

                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                    //    inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo
                    //    inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares


                    });
                });

                lockForLoadDestino = false;



            }).fail(function(){

                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });


    $("body").on("click", ".load_ajax_hoteles", function(event) {

        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];
            var href = $(this).data("accion");

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);

                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }

                target = $("#hoteles-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);

                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        //    inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo
                        //    inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares


                    });
                });

                lockForLoadDestino = false;



            }).fail(function(){

                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });



    $("body").on("click", ".load_ajax_spa", function(event) {

        event.preventDefault();

        if ( !lockForLoadDestino ) {
            lockForLoadDestino = true;

            setOverlayContentLoadingGif();


            var href_base = $(this).data("accion").split("&")[0];
            var href = $(this).data("accion");

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            console.log(urlAjaxRequest);


            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);

                try{
                    datost = JSON.parse(data);
                }
                catch(e) {
                    lockForLoadDestino = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }

                target = $("#spa-wrapper");
                console.log(target);


                removeOverlayContentLoadingGif();

                //comprobamos si el target YA es visible, si no es asi lo ponemos visible.
                target.fadeOut(function(){
                    $(this).children().remove();
                    $(this).append(datost.datos);

                    $(this).fadeIn(function()
                    {
                        //Primer elemento en grande
                        //$('.tarjeta_destinos_hotel:first').addClass('vista_detalle_hoteles_destino');

                        inicializa_isotope(); //Para la ventana que tiene la lista de hoteles
                        //initializeDestinos(); // Para la vista que tiene el mapa del mundo
                        inicializa_isotope_galeria(); //Para la inicializacion de la galeria de imagenes de Lugares


                    });
                });

                lockForLoadDestino = false;



            }).fail(function(){

                lockForLoadDestino = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });




    ////   LISTENER PARA CARGAR DINAMICAMENTE LOS CONTENIDOS DE LAS SECCIONES INTERIORES
    $("body").on("click", ".loadAjax", function(event) {
        event.preventDefault();

        //comparte cerrojo con el de click en menu
        if ( !lockForAnimation ) {
            lockForAnimation = true;
            
            setOverlayContentLoadingGif();

            //realizamos la peticion para cargar los datos
            var href_base = $(this).attr("href").split("&")[0];
            var href = $(this).attr("href");

            var urlAjaxRequest = "index.php?ajax=" + href;
            var object = $(this);

            $.get(urlAjaxRequest, function(data) {
                verificar_actualizacion(data);
				
                try{

                    datost = JSON.parse(data);


                }
                catch(e) {
                    lockForAnimation = false;
                    removeOverlayContentLoadingGif();

                    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                    setTimeout(function() {
                        $("#overlayLoadingContent").fadeOut("200", function() {
                            $(this).find(".back").click();
                        });
                    }, 2000);
                    return;
                }
 				
				
                lockForAnimation = false;
                //le añado la clase para ocultar el contenid y dejar visible la trama
                if ( object.parents(".menuContent").size()>0 && object.parents(".menuContent").children().is(":visible"))
                {
                    object.parents(".menuContent").children().fadeOut("200", function(){
                        $(this).addClass("menuContentHidden");
                        removeOverlayContentLoadingGif();
                    });
                }
                else {
                    removeOverlayContentLoadingGif();
                }
                

                window['handler_' + href_base](data, object);
                
                if (href == "getHotel_contenidoDinamico&contenidoId=36") {
                    rotar_fotos_galeria();
                }
            }).fail(function(){
                lockForAnimation = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");
                
                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 2000);
            });
        }
    });

    $("body").on("click", ".load-path", function(){

        data = $(this).data("path");

        //ya es un objeto.... magia.
        
        console.log(data);
        try{
            data = JSON.parse(data);
        }
        catch(e) {
           //que hago?
        }
        
        if ( typeof(data) != "object") return;
        
        //click_path(data, 0);
        click_path2(data, 0);
        
    });


    ////  LISTENER PARA CUANDO SE CARGE INFORMACION DETALLADA DE UN COMERCIO SUPERDESTACADO
    $("body").on("click", ".loadComercio", function(event){
        event.preventDefault();
        
        //comparte cerrojo con el de click en menu
        if ( !lockForAnimation ) {
            lockForAnimation = true;
            
            setOverlayContentLoadingGif( );
            
            var url = "index.php?ajax=" + $(this).attr("href");
            
            $.get(url, function(data) {
				verificar_actualizacion(data);
                lockForAnimation = false;
                removeOverlayContentLoadingGif();
                
                var datos;

                try{
                    datos = JSON.parse(data);
                }
                catch(e) {
                    //que hago?
                    return;
                }
                
                if ( !datos.error ) {
                    $("body").append("<div id='comercioWrapper'></div>");
                    
                    console.log(datos);
                    $("#comercioWrapper").hide().append(datos.comercio);
                    console.log( $("#comercioWrapper").find("#como_llegar_wrapper"));
                    $(".elto:first").click();
                    
                    
                    $("#pageBottomWrapper").data( "pos_original", $("#pageBottomWrapper").css("top") ).animate({
                        bottom: "-533px"
                    }, 400, function(){
                        $("#comercioWrapper").fadeIn( function(){
                            
                            $(this).find("a[href=#como_llegar_wrapper]").click(function() {
                                var coordLatitud, coordLongitud;

                                coordLatitud = parseFloat($("#comercioWrapper").find("#comercioLatitud").text());
                                coordLongitud = parseFloat($("#comercioWrapper").find("#comercioLongitud").text());
                                
                                setTimeout(function(){    
                                    $("#map_wrapper_llegar").generateMapaForComercio(coordLatitud, coordLongitud);
                                },600);
                            });
                        });
                    });
                }
            }).fail(function(){
                lockForAnimation = false;
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");
                
                setTimeout( function(){
                    $("#overlayLoadingContent").fadeOut("200", function(){
                        $(this).find(".back").click();
                    });
                }, 10000);
            });;
        }
    });


    /***********************************************************************************************
     **************                       ESPECIFICACIONES DE SECCIONES           ******************/

    ////   PARA ESTABLECER EL COLOR DE LA SOMBRA DEL ELEMNTO DE MENU ACTIVO
    setMenuIconShadowColor();


    ////   PARA MOSTRAR DESPLEGADOS LOS EVENTOS DEL MENU QUE ESTEN ACTIVOS
    toogleVisibilityMenuContent($("div.mainMenuElement.active"));

    ////   PARA ACTIVAR EL SLIDER INFERIOR
    slider_activate();


    ////   LISTENERS PARA ESTABLECER EL FORMATO DEL TEXTO DE POSTALES.
    $("body").on("click", ".fuente", function(){
        $(".fuente.active").removeClass("active");
        $(this).removeClass("fuente");
        $("#textoPostal").removeClass("fuente1 fuente2 fuente3 fuente4").addClass( $(this).attr("class") );
        $(this).addClass("fuente active");
    });    
    
    $("body").on("click", ".orientacion", function(){
        $(".orientacion.active").removeClass("active");
        $(this).removeClass("orientacion");
        $("#textoPostal").removeClass("textAlign-justify textAlign-left textAlign-right textAlign-center").addClass( $(this).attr("class") );
        $(this).addClass("orientacion active");
    });    
    
    $("body").on("click", ".size", function(){
        var fontSizeActual;
        
        if ( $("#textoPostal").data("fontSize") ){
            fontSizeActual = $("#textoPostal").data("fontSize");
        }
        else {
            fontSizeActual = 3;
        }
        
        if ( $(this).hasClass("mas") ) {
            $("#textoPostal").removeClass("fontSize" + fontSizeActual);
            fontSizeActual = fontSizeActual+1 > 6 ? 6 : fontSizeActual + 1;
        }
        else {
            $("#textoPostal").removeClass("fontSize" + fontSizeActual);
            fontSizeActual = fontSizeActual-1 < 1 ? 1 : fontSizeActual - 1;
        }
        
        $("#textoPostal").data("fontSize", fontSizeActual);
        $("#textoPostal").addClass("fontSize" + fontSizeActual);
    });    
    
    $('body').on("click",".btn-superior-ae",function(e){

        var id = $(this).attr("id");
        $(".btn-superior-ae,.content-frame-new").removeClass('active');
        
        $(this).addClass("active");
        $(id).addClass("active");

    });
    
    $("body").on("click", ".elto", function(event) {
        
        //cerramos cualquier posible elemento abierto
        //$(".close, .back").click();

        if ( $(this).hasClass("validLink")) {
            // si tiene esta clase significa que es un link a otra página por lo que dejo que fluja el link
            return true;
        }
        if (lockForAnimation) {
            return false;
        }
        

        var target = $(this).attr("href");      //si no tiene elementos de la pagina como target
        if ($(target).size() == 0) {                //devuelvo false
            return false;
        }

        lockForAnimation = true;
        
        event.preventDefault();

        var menuActive = $(".elto.active");

        if ( $(menuActive).size() > 0) {
            var obj = $(this);
            var target = $(menuActive).attr("href");
            
            $(target).fadeOut(function() {
                $(menuActive).removeClass("active");

                $(obj).addClass("active");
                $( $(obj).attr("href") ).fadeIn();
                lockForAnimation = false;
            });
        }
        else {
            $(this).addClass("active");
            $( $(this).attr("href") ).fadeIn();
            
            lockForAnimation = false;
            
        }
        
        return false;
    });
    
    //// boton de señor/señora
    $(".input_radio_male, .input_radio_female").click(function(event) {
        if ( event.target != this ){
            return true;
        }
        
        $(".input_radio_male.active, .input_radio_female.active").removeClass("active");
        $(this).addClass("active");
        
        $(this).find("input").click();
    });
    
    $("#navMenu a, #langSwitch a").click(function(){
        setOverlayContentLoadingGif();
    });
    

    //para navegabilidad en slider.
    $("#slideleft").click(function(){
        
        slider_activate();
        var p = $(".sliderElement:first");
        var l = $(".sliderElement:last");

        p.before( l );
        l.css({
            marginLeft: "-" + $(l).width() + "px"
        }).animate({
            marginLeft: "+" + 0 + "px"
        }, delayAnimationBottomSlider);

    });

    $("#slideRight").click(function(){    
        slider_activate();
        $(".sliderElement:first").animate({
            marginLeft: "-1080" + "px"
        }, delayAnimationBottomSlider, function() {
            $(this).appendTo($(this).parent()).css("marginLeft", "0");
        });
    });

    ///////////////////////////////      Precheking mediante ajaxForm
    $("form#form_checkin").ajaxForm({
            target: null,
            beforeSubmit: function(){

            },
            success: function(responseText, statusText, xhr, form){

                //recibo la respuesta del servidor.
                
                try{
                    responseDatos = JSON.parse(responseText);

                }
                catch(e) {
                    console.log("error al parsear el json: " + responseText);
                    return;
                }

                $(form).parents(".menuContent").children().fadeOut("200", function(){
                
                    $(this).addClass("menuContentHidden");
                    lockForAnimation = false;
                    removeOverlayContentLoadingGif();
                    
                    $(form).parents(".menuContent").append(responseDatos.datos);
                    initializeKeyboardListeners();// lentitud

                    $("#checkin2").click(function(e){
                        if (e.target.id == 'paises_chekin2') {            
                            $("#paises_chekin2").fadeOut("slow");
                        }
                    });

                    // $(".datepicker2").datepicker2({
                    //  viewMode: 'years',
                    //  format: 'dd/mm/yyyy'
                    // });

                    // $('.datepicker2').on('changeDate', function (ev) {
                    //         //close when viewMode='0' (days)
                    //         if(ev.viewMode === 'days'){
                    //           $('.datepicker2').datepicker2('hide');
                    //         }
                    //     })

                    $('.datepicker2').click(function(){

                        var datepicker_id = $(this).attr('id');

                        var offset = $(this).offset();

                         $('#'+datepicker_id).datepicker2('show');

                        //Posiociones 0,0 del primer elemento
                        var pos_top = offset.top - '94';
                        var pos_left = offset.left - '310' ;
                        //alert(pos_top);

                        $(".datepicker2.dropdown-menu").css('top', pos_top);
                        $(".datepicker2.dropdown-menu").css('left', pos_left); 

                         
                    });


                    var huesped_actual;
                    $(".nacionalidad").click(function(event){
                        event.stopPropagation();

                        //Es la id de la tarjeta que has echo click
                        huesped_actual = $(this).attr("id");
                        
                        //Posicion de la tarjeta
                        var offset = $(this).offset();

                        //Posiociones 0,0 del primer elemento
                        var pos_top = offset.top - '998.29';
                        var pos_left = offset.left - '73';

                        //Añado la posicion de la caja actual para posicionar sobre ellos los paises
                        $("#paises_chekin2").css('position','absolute');
                        $("#paises_chekin2").css('top', pos_top);
                        $("#paises_chekin2").css('left', pos_left);


                        //Actualizo cual es la id de la actual bandera, para ponerle la bandera del selector la clase active
                        $(".bandera_checkin").removeClass("bandera_active");
                        var nombre_pais_completo = $(this).find('#input_nacionalidad_'+huesped_actual).val();
                        $('#'+nombre_pais_completo).addClass('bandera_active');

                        //Muestro el selector de paises con el pais de la tarjeta seleccionada
                        $("#paises_chekin2").fadeIn();


                    });

                    //Cuando haces click en la caja de seleccion de la bandera
                    $(".bandera_checkin").click(function(){

                        //Url donde estan todas la banderas
                        var path_img = "../../../contenido_proyectos/pacoche/_general/iconos/iconos-bandera-flat/"; 

                        //Actualizo la bandera sseleccioanda en la caja
                        $(".bandera_checkin").removeClass("bandera_active");
                        $(this).addClass("bandera_active");

                        // Busco la bandera seleccionada (imagen + nombre completo de un input hidden)
                        var bandera_seleccionada = $(this).find('.bandera_checkin_imagen').attr("src");
                        var texto_bandera_seleccionada = $(this).find('.bandera_checkin_texto_completo').val();

                        //Actualizo la bandera del input de la tarjeta
                        $("#bandera_input_checkin_"+huesped_actual).attr("src", bandera_seleccionada );
                        $("#input_nacionalidad_"+huesped_actual).val(texto_bandera_seleccionada);

                        //Oculto la caja de seleccion de los paises
                        setTimeout(function(){
                                $("#paises_chekin2").fadeOut("slow");
                            }, 200);
                    
                    });

                     //Para ocultar cualquier ventana que este abierto picando en content
                     
                     $(".content").click(function(){
                            if ($('#paises_chekin2').is (':visible')) $("#paises_chekin2").fadeOut("slow");                       
                     });


                    var newChild = $(form).parents(".menuContent").children(":last");

                    newChild.hide();

                    newChild.setColorToSubsectionElements(form);
                    
                    newChild.setPositionsForSection(function() {

                        //en caso de que quisieramos mostrar un banner seria por aqui.....
                    });

                });
            },
            error: function(){
                //recibo la respuesta del servidor.
                removeOverlayContentLoadingGif();
                $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

                setTimeout(function() {
                    $("#overlayLoadingContent").fadeOut("200", function() {
                        $(this).find(".back").click();
                    });
                }, 2000);
            }

        });


});
////////////////////////////////////////////////////////////////////////////////       fin del documento.ready()   


var overlayTimeoutId;
function setOverlayContentLoadingGif( ) {

    clearTimeout(overlayTimeoutId);
    overlayTimeoutId = setTimeout(function(){
        if ( $(".loadingBg").size()>0){
            return true;
        }
        $("#pageContentWrapper .blockWrapper:first").addClass("filter1");
        
        //$("body").append("<div id='overlayLoadingContent' class='loadingBg'><div id='loadingGifWrapper'></div></div>");

        $("body").append("<div id='overlayLoadingContent' class='loadingBg'><div class='nueva_carga'> <img src='../../../contenido_proyectos/pacoche/_general/imagenes/animacion_wait_white.gif'> </div></div>");


        //The following code starts the animation
        //new imageLoader(cImageSrc, 'startAnimation()');

        //$("body").append("<div class='nueva_carga'> <img src='../../../contenido_proyectos/pacoche/_general/imagenes/animacion_totem02.gif'> </div>");

    }, overlayLoadingDelay);    

    //console.log(overlayTimeoutId);
}

function removeOverlayContentLoadingGif(remove){
    var fadeOut = remove || false;
    $(".filter1").removeClass("filter1");
    
    clearTimeout(overlayTimeoutId);
    if ( fadeOut ) {
        $("#overlayLoadingContent").fadeOut(function(){
            stopLoadingAnimation();
            $(this).remove();
        });
    }
    else {
        stopLoadingAnimation();
        $("#overlayLoadingContent").remove();
    }
}

/*Funciones para evitar la seleccion de doble click*/
//***************************************************
function disabletext(e) {
//    e.preventDefault();
    return false;
}

function reEnable() {
    return true;
}


/**
 * 
 * @param {function} clickListener listner para cuando se activa el teclado XDD
 * @returns {undefined}
 */
function initializeKeyboardListeners(clickListener){

    if($('#input_teclado').val() != 'no'){
        var callFunction = false;
        if ( typeof clickListener == "function" ) {
            callFunction = clickListener;
        }
        
        $("input[type=text], textarea").not(".ui-widget-content").keyboard({
            //singleton: $("#keyboard-container"), //pendiente de ver como funciona
            //repeatRate: 0, //esto hace que no se repita el click
            autoAccept: true,
            openOn: null,
            stayOpen: null,
            alwaysOpen: true,
            userPreview: false,
            enterNavigation: false,
            usePreview: false,
            layout: "custom",
            change: clickListener,
            customLayout: {
                'default': ['º 1 2 3 4 5 6 7 8 9 0 \' ¡ {b}',
                    '{tab} q w e r t y u i o p [ ] ç',
                    '{shift} a s d f g h j k l ñ ´ {enter}',
                    '{sp2} z x c v b n m , . - = {sp2}',
                    '$ € _ {space} @ .com'],
                'shift': ['ª ! @ # $ % & / * ( ) ? ¿ {b}',
                    '{tab} Q W E R T Y U I O P { } Ç',
                    '{shift} A S D F G H J K L Ñ ¨ {enter}',
                    '{sp2} Z X C V B N M ; : * / {sp2}',
                    '$ € _ {space} @ .com ']
            }
        }).click(function(event) {
            
            if (callFunction) {
                callFunction();
            }
            
            var keyboard = $(this).keyboard().getkeyboard();
            var speed = 200;
            var target = null;
    
            $(".hasKeyboardActive").removeClass("hasKeyboardActive");
            $(this).addClass("hasKeyboardActive");
    
            if ( $(".ui-keyboard").hasClass("clickActive") ) {
                speed = 5;
                target = $(".ui-keyboard.clickActive");
            }
    
            if (keyboard.el != target) {
                $(keyboard.$keyboard).css({
                    top: "1433px",
                    left: "55px",
                    fontSize: "1.90em",
                    paddingTop: "0em",
                    paddingBottom: "0em",
                    borderRadius: '22px',
                    
    
                }).finish().fadeIn(speed, function() {
                    $(this).addClass("clickActive");
                    $("#pageFooterWrapper overlay").show();
                });
            } 
            else {
                $("#pageFooterWrapper overlay").hide();
            }
    
        });
    
    
        $(".ui-keyboard").hide().draggable();
        $('.ui-keyboard').prepend("<div class='arrastre_izquierda'><img src='../../../contenido_proyectos/pacoche/_general/iconos/mano.png'/></div>");
        $('.ui-keyboard').prepend("<div class='arrastre_derecha'><img src='../../../contenido_proyectos/pacoche/_general/iconos/mano.png'/></div>");
    }

}

//FUNCION PARA EL SLIDER INFERIOR
var sliderTimeoutId;
function slider_activate() {

    if ($("#footerSlider").size() > 0) {
        clearTimeout(sliderTimeoutId);


        if ( $(".sliderElement").size()<=1) {
            $("#slideleft").hide();
            $("#slideRight").hide();

            return;
        }
        sliderTimeoutId = setTimeout(function() {
            $(".sliderElement:first").animate({
                marginLeft: "-" + $(this).width() + "px"
            }, delayAnimationBottomSlider, function() {
                $(this).appendTo($(this).parent()).css("marginLeft", "0");

            });
            slider_activate();
        }, delayTransitionsBottomSlider)
    }
}


/** funcion para desplegar el menuContentWrapper (en caso de que estuviese abierto se cierra). 
 * El caso de mostrar el contenido... se puede hacer pasandole algun parametro a esta función, 
 * o quizas un callback para llamarlo cuando ya se haya desplegado el wrapper.... 
 * 
 * @param {jquery} obj es el objeto sobre el que se hizo click. Necesario para ajustar la flecha superior
 * @param {function} callback una funcion callback a la que llamar al acabar todo. Opcional */
function toogleVisibilityMenuContent(obj, callback) {

    
    
    var target = $(obj).children("[href]").attr("href");
    
//    if ( target == "#conoceWrapper")
//    {
//        if (typeof callback == "function") {
//            callback();
//        }
//    }
    
    if ($(target).length == 0) {
        return false;
    }
    if ( $(target).is(":visible") ) {
        $(target).finish().slideUp(mainMenuElementSlideSpeed - 200, function() {
            if (typeof callback == "function") {
                callback();
            }
        });
    }
    else {
        setActiveMenuContentArrowPosition(obj);
        $(target).finish().delay(100).slideDown(mainMenuElementSlideSpeed - 100, function() {
            if (typeof callback == "function") {
                callback();
            }
        });
    }
}



/** funcion para estbalecer el color de la sombra para los elementos del menu que esten activos */
function setMenuIconShadowColor() {
    var color;

    $("div.mainMenuElement").each(function() {
        if ($(this).hasClass("active")) {
            color = $(this).attr("color");
           //$(this).css("background", "radial-gradient(closest-side at 46% 97% , " + color + " 5%, " + color + " 27%, #232323) no-repeat scroll 0 0 transparent");
            // $(this).css("background", "radial-gradient(closest-side at 44% 98.5% , " + color + " 5%, " + color + " 27%, transparent) no-repeat scroll 0 0 transparent");
        } else {
            $(this).removeAttr("style");
            //$(this).removeClass("mainMenuElement").addClass("mainMenuElement");
        }
    })
}

/** funcion para establecer la posición de la flecha superior del menu deplegable en función
 * del parametro pasado por parametro, que vendría a ser el elemento sobre el que se hizo click.
 * a partir de dicho elemento se calcula la posicion a la que debe ir situado 
 * @param {jquery} obj el padre de objeto sobre el que se hizo click (de clase .mainMenuElement) */
function setActiveMenuContentArrowPosition(obj) {

    // margenIzq + anchoObjeto/2 + anchoFlecha/2  =  punta de la flecha al medio del objeto
    var posLeft = Math.floor($(obj).offset().left + ($(obj).width() / 2) - 24);   //lo del 24 es por el tamaño de la flecha en si mism


    var cssValue = "" + posLeft + "px 0px";
    var target = $(obj).attr("href");

    $("div.menuContentHeader", target).css("backgroundPosition", cssValue);
}

/** funcion encargada del screen saver */
function screenSaver(action) {

//Controla si existe el screensaver, sin esta comprobacion peta, el screensaver es parametrizable por BD
if($('#screenSaver').length ){   


    if (action == "activate") {
        video_cabecera_pause(); //Video cabecera se para para hacerlo más fluido
        //debo activar el screenSaver
        $(".ui-keyboard").hide();

        $("#screenSaver video").get(0).play();
        $("#screenSaver").addClass("active").stop().animate({
            marginLeft: "0%"
        }, screenSaverAnimationInDelay);


        
        //activo el timeOut para desactivar el screensaver
        setTimeout("screenSaver()", delayScreenSaverDeactivationDelay);

        //desactivo el screenSaverTimeout
        clearTimeout(screenSaverTimeOutId);
        
        $(".back, .close").click();
    }
    else if ($("#screenSaver").hasClass("active")) {
        //debo cerrar el screenSaver
        $("#screenSaver video").get(0).pause();

        $("#screenSaver").removeClass("active").stop().animate({
            marginLeft: "-100%"
        }, screenSaverAnimationOutDelay, function() {
            video_cabecera_play(); //se activa de nuevo
            $("#screenSaver video").get(0).currentTime = 0;
        });

        //vuelvo a activarlo
        screenSaverTimeOutId = setTimeout("screenSaver('activate')", delayScreenSaverActivationDelay);
    }
    else {
        //debo resetear el timer del screensaver 
        clearTimeout(screenSaverTimeOutId);
        screenSaverTimeOutId = setTimeout("screenSaver('activate')", delayScreenSaverActivationDelay);
    }
}

}

//Estas funciones paran e inician el video que esta en la cabecera
function video_cabecera_play() {
    $(".hotelBanner").find("video").each(function() {
        $(this).get(0).play();
    });
}
function video_cabecera_pause() {
    $(".hotelBanner").find("video").each(function() {
        $(this).get(0).pause();
    });
}
//Con esta funcion hacemos play and pause dependiendo de su estado anterior
/*function video_cabecera_play_pause() {
 $(".hotelBanner").find("video").each(function() {
 if ($(this).get(0).paused) 
 $(this).get(0).play(); 
 else 
 $(this).get(0).pause(); 
 });
 }*/
 
/*Funcion que controla si existe una actualzacion en curso y hace un reload
   para que el index.php reciba $_GET vacio.	
*/
 function verificar_actualizacion(data){
	if( data=="actualizacion" ){
		location.reload();
	}
 }
 
/** funcion para obtener los datos meteorologicos y generar luego la grafica asi como todo
 * el panel que muestra dicha información
 * @param {string} municipio indica el municipio (el codigo para aemet) para el 
 *                que deseamos obtener los datos. Si no se le pasa argumento devuelve los por defecto
 */
function generarGraficaMeteorologica(municipio, callback) {
    if ( $("#meteoInfo_char").size() > 0) {             //generamos la grafica de temperaturas
        $.get("index.php?ajax=getMeteoInfo&municipio=" + municipio, function(data, status, jqXHR) {
			verificar_actualizacion(data);
            if (data == "") {
                if (typeof callback == "function") {
                    callback();
                }
            }
            var datosMeteo;

            try{
                datosMeteo = JSON.parse(data);
            }
            catch(e) {
                //que hago?
                //muestro una pantallita con información de error.
                if ( $("#t55").size()==0) {
                    $("#meteoInfo_char").after("<div id=t55 class='overlayMsgError closeWrapper back'><span class='back'>Sin conexión</span></div>");
                }
                if (typeof callback == "function") {
                    callback();
                }
                return;
            }

            if (typeof datosMeteo != "object") {
                //situacion de error.
                if ( $("#t55").size()==0) {
                    $("#meteoInfo_char").after("<div id=t55 class='overlayMsgError closeWrapper back'><span class='back'>Sin conexión</span></div>");
                }
                if (typeof callback == "function") {
                    callback();
                }
                return;
            }
            
            $("#t55 .back").click();
            //obtenemos las temperaturas para crear la tabla y dibujamos la grafica de temperaturas
//            var temperaturas = [parseInt(datosMeteo.hoy.temp_06), parseInt(datosMeteo.hoy.temp_12),
//                                parseInt(datosMeteo.hoy.temp_18), parseInt(datosMeteo.hoy.temp_24),
//                                parseInt(datosMeteo.tomorrow.temp_06), parseInt(datosMeteo.tomorrow.temp_12),
//                                parseInt(datosMeteo.tomorrow.temp_18), parseInt(datosMeteo.tomorrow.temp_24)];
            var temperaturas = [parseInt(datosMeteo.hoy.temp_06), parseInt(datosMeteo.hoy.temp_12),
                                parseInt(datosMeteo.hoy.temp_18), parseInt(datosMeteo.hoy.temp_24)];
            var fechas = new Array("6h", "12h", "18h", "0h", "6h", "12h", "18h", "0h");

            $("#meteoInfo_char").highcharts({
                chart: {
                    //type: 'line',
                    type: 'areaspline'
                },
                title: {
//                    text: datosMeteo.titulo,
                    text: "",
                    x: -145, //center
                    y: 3,
                    floating: true,
                    color: "#676767",
                    enabled: false
                },
                xAxis: {
                    categories: fechas
                },
                yAxis: {
                    plotLines: [{
                            value: 0,
                            width: 1
                                    //color: '#808080'
                        }],
                    title: {
                            enabled: false,
//                            text: "Temperaturas ºC"
                        },
                    lineWidth: 1,
                    gridLineWidth: 0,
                    labels: {
                        format: '{value} ºC'
                    }
                },
                legend: {
                    enabled: false,
                    y: 10,
                    borderWidth: 0
                },
                tooltip: {
                    valueSuffix: ' ºC',
                    enabled: false
                },
                series: [{
                        name: 'temperatura',
                        data: temperaturas,
                        color: '#ffb400'
                    }],
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillColor: {
                            linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                            stops: [
                                [0, '#ffb400'],
                                [1, Highcharts.Color('#ffb400').setOpacity(0).get('rgba')] //Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')
                            ]
                        },
                        lineWidth: 0,
                        marker: {
                            enabled: false,
                            lineColor: '#ffb600',
                            fillColor: '#ffb600',
                            valueSuffix: ' ºC'
                        },
                        shadow: false,
                        states: {
                            hover: {
                                enadbled: false
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        threshold: null
                    }
                }
            });

            var index = 6;
            var i, temp;
            //sección superior del tiempo
            $("div.meteoInfo_resumen").each(function() {

                i = index;
                if (index == 6) {
                    i = "06";
                }
                //cuadrado superior con los iconos para las temperaturas de 6h, 12h y 18h
                //icono
                $(".resumen_iconNubosidad img", $(this)).attr("src", "../../../contenido_proyectos/pacoche/_general/tiempo/" + datosMeteo.hoy.iconos[index]);

                //viento
                $(".viento", $(this)).html(datosMeteo.hoy["viento_vel_" + i + "_" + (index + 6) ]);

                //humedad
                $(".humedad", $(this)).html(datosMeteo.hoy["hum_" + (index + 6)]);

                //temperatura
                temp = Math.floor((parseInt(datosMeteo.hoy["temp_" + i]) + parseInt(datosMeteo.hoy["temp_" + (index + 6) ])) / 2);
                temp = datosMeteo.hoy["temp_" + (index + 6) ];
                $(".temperatura", $(this)).html(temp);

                index += 6;
            });

            index = 12;
            var day = "tomorrow";
            //seccion inferior para estimaciones
            $("div.meteoInfo_estimacion").each(function() {
                if (index > 18) {
                    index = 12;
                    day = "pasado";
                }
                //icono
                $(".meteoInfo_estimacionImgWrapper img", $(this)).attr("src", "../../../contenido_proyectos/pacoche/_general/tiempo/" + datosMeteo[day].iconos[index]);
                //hora
                $(".time", $(this)).html(index + ":00 H");
                index += 6;
            });

            var fecha;
            var day = "tomorrow";
            $("div.meteoInfo_estimacionTemp").each(function() {

                fecha = new Date(datosMeteo[day].fecha.substring(0, 10));
                //fecha
                $("span.fechaTime", $(this)).html(diasSemanasIdiomas[idioma][ fecha.getDay() ]);

                //temperaturas Max y Min
                $(".temps", $(this)).html(datosMeteo[day].temp_min + ", " + datosMeteo[day].temp_max);

                day = "pasado";
            });

            //seccion para el nombre de la localidad
            $("#meteoInfo_municipio").html(datosMeteo.localidad);

            if (typeof callback == "function") {
                callback();
            }
        })
        .fail(function(){
    
            if ( $("#t55").size()==0) {
                $("#meteoInfo_char").after("<div id=t55 class='overlayMsgError closeWrapper back'><span class='back'>Sin conexión</span></div>");
            }
            
            if (typeof callback == "function") {
                callback();
            }
        });
    }
}//fin grafica meteorologica

function failHandler(){
    lockForAnimation = false;
    removeOverlayContentLoadingGif();
    $("body").append("<div id='overlayLoadingContent' class='loadingBg closeWrapper back'><span class='back'>Sin conexión</span></div>");

    setTimeout( function(){
        $("#overlayLoadingContent").fadeOut("200", function(){
            $(this).find(".back").click();
        });
    }, 2000);
}
/** obtenemos de entrada el listado de los dias que hay actividades */
function getListadoEventosAgenda(repeat, callback) {
    
    $.get("index.php?ajax=agendaRequest&request=dias_eventos", function(data) {

        if (data.length > 0) {

			
            //hay actividades, obtengo el JSON correspondiente
            var actividades;

            try{
                actividades = JSON.parse(data);
            }
            catch(e) {
                //que hago?
                if (typeof callback == "function"){
                    callback();
                }
                return;
            }
            
            $("#actividadesHotel .fechasActividades").text(JSON.stringify(actividades.hotel));
            $("#actividadesIsla .fechasActividades").text(JSON.stringify(actividades.isla));

            $("#agendaCalendario").datepicker("refresh");

            if (typeof callback == "function") {
                callback();
            }
        }

    });

    if (repeat) {
        setTimeout(function() {
            getListadoEventosAgenda(true);
        }, AgendaDatepickerRefreshInterval);
    }
}


/*Funcion para disparar los eventos de 3 dias (RIU)*/
/** obtenemos de entrada el listado de los dias que hay actividades */
function getListadoEventosAgenda3(repeat, callback) {
    $.get("index.php?ajax=agendaRequest&request=actividades3", function(data) {
  
        if (data.length > 0) {
            
            //hay actividades, obtengo el JSON correspondiente
            var actividades;

            try{
                actividades = JSON.parse(data);
            }
            catch(e) {
                //que hago?
                if (typeof callback == "function"){
                    callback();
                }
                return;
            }
            
            $("#actividadesHotel .fechasActividades").text(JSON.stringify(actividades.hotel));
            $("#actividadesIsla .fechasActividades").text(JSON.stringify(actividades.isla));

            $("#agendaCalendario").datepicker("refresh");

            if (typeof callback == "function") {
                callback();
            }
        }
    });

    if (repeat) {
        setTimeout(function() {
            getListadoEventosAgenda3(true);
        }, AgendaDatepickerRefreshInterval);
    }
}





////////////////////////////////////////////////      Funciones jquery



// funcion para ocultar los elementos superiores sobrantes sobre el display de tabla_1 o tabla_2
$.fn.hideTopElements = function() {
    $(this).find(".tabla_1, .tabla_2").each(function(){
        var nTds = $(this).find(".contenido_elemento").size();
        
        console.log(nTds);
        
        if (nTds < 3) {
            $(this).find(".contenido_header .symbol:gt("+(nTds-1)+")").hide();
        }
    });
};


//se debe llamar sobre el wrapper que contiene los comercios
$.fn.setAlignForComercios = function() {
    
    var numeroDestacados = $(this).find(".comercio_destacado").size();
    if ( (numeroDestacados % 2) != 0 ) {
        //es impar.
        $(this).find(".comercio_normal:lt(3)").css({
            float:"right",
            clear: "right"
        });
    }
}

$.fn.setOverlayWrapper70 = function() {
    $(this).each(function() {
        $(this).css({
            height: "1292px",
            width: "100%",
            top: "79px",
            position: "absolute",
            zIndex: "100"
        });
    });
    return $(this);
}


/** funcion para aplicar el slimScroll con las opciones deseadas */
$.fn.setSlimScroll = function() {
    $(this).removeClass("scroll");
    $(this).parents(".scroll").removeClass("scroll");
    
    
    $(this).css({
        overflowY: "auto",
        overflowX: "hidden"        
    });
    
    /*
    $(this).slimScroll({
        //height: '100%',    
        railColor: 'black',
        railOpacity: "0.7",
        railVisibile: true,
        alwaysVisible: true,
        opacity: ".6",
        color: "white",
        scrollBy: '20px',
        size: "18px",
        disableFadeOut: true,
        touchScrollStep: "10"
    });
    */
}


var mostrar_act = {
    es: {0: "Mostrar todos"},
    en: {0: "Show all"},
    de: {0: "Zeige alles"}
};

/** funcion para mostrar los eventos similares al que se ha echo click */
$.fn.showSimilarEvents = function() {

    if ($(this).hasClass("active") || $(this).parents("li").siblings().find("span.active").size() > 0) {
        return;
    }

    var classTarget = $(this).attr("class").replace("icon-svg ", "");
    $(".actividadesList").find(".icon-svg:not(." + classTarget + ")").parents("li").slideUp(150);
    $(".actividadesList").find(".icon-svg." + classTarget).addClass("active");

    $(".actividadesList").append("<li class='showActividades center'>"+mostrar_act[idioma][0]+"</li>");
    $("li.showActividades").click(function() {
        $(this).siblings(":visible").find("span.active").removeClass("active");
        $(this).siblings(":hidden").slideDown(150);
        $(this).remove();

        //para mostrar tambien los iconos del otro lado
        $("li.showActividades").click();
    });
}

/** funccion para estableer los colores de los atributos de cada seccion y subseccion 
 * de contenido */
$.fn.setColorsFromMenuSection = function(color) {
    $(this).find(".buttonsRight, .buttonsLeft, .buttonsCenter, .buttons").css({
        color: color
    });


    if ($(this).hasClass("noTabsWrapper")) {
        $(this).find(".content").css({
            borderColor: color
        });
    }
    else {
        $(this).find(".noTabsWrapper .content, .content.draw_border").css({
            borderColor: color
        });
    }

    $(this).find("table.std_table th").css({
        color: "white",
        backgroundColor: color
    });

    $(this).find(".setBgColor").css({
        backgroundColor: color
    })

//    if ( $(this).hasClass("menuContentWrapper") )
//    {
        //es un elemento del menu, busco para rellenar los colores
        $(this).find(".block").each(function()
        {
            $(this).find("svg defs linearGradient stop:first").css("stop-color", color);
            var colorRGB = hexToRgb(color);
            //aumentamos 20 puntos en el color del tono verde.
            if (color == "#cd1604"){            //es el rojo
                colorRGB.g = colorRGB.g + 60;
                colorRGB.b = colorRGB.b + 60;
            }
            else if (color == "#8d02ef") {       //es el violeta
                colorRGB.g = colorRGB.g+60;
            }
            else {
                colorRGB.g = colorRGB.g+40;
            }
            $(this).find("svg defs linearGradient stop:last").css("stop-color", "rgb(" + colorRGB.r + "," + colorRGB.g + "," + colorRGB.b + ")" );
        });
//    }
//    else 
//    {
//        $(this).find("svg path").attr("fill", color);
//    }
}

//fuyncion para convertir
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

var timeoutCloseOpenTabsId;
function setTimeoutForClosing() {
    clearTimeout(timeoutCloseOpenTabsId);
    
    timeoutCloseOpenTabsId = setTimeout(function() {
        $(".close, .back").click();

        if ( $(".close, .back").size()>1 ) {
            setTimeoutForClosing();
        }
    }, timeBeforeClosingOpenTabs); 
}
var timeoutRefreshId;
function setTimeoutForRefresh() {
    clearTimeout(timeoutRefreshId);
    
    timeoutRefreshId = setTimeout(function(){
        if (true) {
            location.reload();
        }
    },reloadIntervalTime);
}

/** funcion para establecer los atributos de posicion y demas para las seciones contenidas
 * dentor de las seciones principales */
$.fn.setPositionsForSection = function(callback) {

    
    var parent = $(this).parents(".menuContent");
    if ( $(this).find(".tabs_").size() > 0) {

        $(this).find("ul.ui-tabs-nav").css({
            position: "absolute",
            right: "55px",
            top: "-2px"
        }).addClass("tabs_std1");

        $(this).show().css({
            position: "absolute",
            width: parent.width() + "px",
            marginLeft: "110%",
            top: "9px",
            zIndex: "1",
            //backgroundColor: parent.css("backgroundColor"),
            backgroundImage: "none"
        });
        $(this).find(".content").css({
            position: "static",
            marginTop: "35px",
//            backgroundColor: parent.css("backgroundColor"),
            backgroundImage: "none",
            borderLeft: "none",
            borderRight: "none"
        });
        $(this).find(".ui-widget-content").css("color", "white");
    }
    else {
        //estilo para NO pestañas

        $(this).find(".content").css({
            position: "static",
            marginTop: "35px"
        });
        $(this).show().css({
            position: "absolute",
            width: parent.width() + "px",
            marginLeft: "110%",
            top: "9px",
            zIndex: "1",
//            backgroundColor: parent.css("backgroundColor")
        });
    }
    
    var heightOriginal = $(this).find(".content").height();
    $(this).find(".content").height(0).css("overflow", "hidden"); //.children().hide();
    $(this).children(".back").hide();
    
    if ( $(this).hasClass("double_size") ) {
        
        $("#pageFooterWrapper").css("zIndex", "-2");
        $(this).parents(".menuContent").animate({
            height: "1038px"
        }, 440);
        
        $(this).animate({
            marginLeft: "0px"
        }, 450, 
        function() {
            var heightOriginal = 955;
            $(this).find(".content").animate({
                height: heightOriginal+"px"
            }, 250, 
            function() {
                if ( $(this).hasClass("tabs_") || $(this).hasClass("tabs") ) {
                    $( $("li.ui-tabs-active a").attr("href") ).fadeIn();
                    $(this).children("ul").fadeIn();
                }
                else {
                    $(this).children().fadeIn();
                }

                $(this).siblings(".back").fadeIn();
                if (typeof callback == "function") {
                    callback();
                }
            });
        });
    }
    else {
        $(this).animate({
            marginLeft: "0px"
        }, 450, 
        function() {
            $(this).find(".content").animate({
                height: heightOriginal+"px"
            }, 250, 
            function() {
                if ( $(this).hasClass("tabs_") || $(this).hasClass("tabs") ) {
                    $( $("li.ui-tabs-active a").attr("href") ).fadeIn();
                    $(this).children("ul").fadeIn();
                }
                else {
                    $(this).children().fadeIn();
                }

                $(this).siblings(".back").fadeIn();
                if (typeof callback == "function") {
                    callback();
                }
            });
        });
    }
    
    if ( $(this).hasClass("no_disponible") ){
        $(this).find(".content").addClass("filter1");
        $(this).append("<div class='img_overlay'><img src='../../../contenido_proyectos/pacoche/_general/imagenes/out_"+idioma+".png'></div>");
    }
}

/** funcion para establecer el color dentro de los elementos a partir de un objeto origin (el padre o algo asi)*/
$.fn.setColorToSubsectionElements = function(originObject) {
    var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");
    $(this).setColorsFromMenuSection(color);
}

/**
 *  mapa para comercios
 * @param {type} latitud
 * @param {type} longitud
 * @returns {undefined}
 */
$.fn.generateMapaForComercio = function(latitud, longitud){
    
    var center = new google.maps.LatLng(centroLatitud, centroLongitud);
    var comercio = new google.maps.LatLng(latitud, longitud);
    
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();
    
    //***************
    //variables para imagenes
    var image = '../../../contenido_proyectos/pacoche/_general/iconos/hotel.png';
    var image_here = '../../../contenido_proyectos/pacoche/_general/iconos/aqui.png';
    
    var EscalaGris = [
        {
            featureType: "poi",
            elementType: "labels",
            stylers: [
                {visibility:"off"}
            ]
        }
    ];
    var myOptions = {
        zoomControl: true,
        zoom: 14,
        center: center,
        disableDefaultUI: true,
        styles: EscalaGris,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    
//    console.log(center);
    if ($("#map_wrapper_llegar").size()>0) {
        map5 = new google.maps.Map( document.getElementById("map_wrapper_llegar"), myOptions);
    }
    else {
        return;
    }
    
    new google.maps.Marker({
        position: comercio,
        map: map5,
        icon: image
    });
    
//    
    new google.maps.Marker({
        position: center,
        map: map5,
        icon: image_here,
        title: 'Su posición',
        zIndex: 99
    });
    
    
    //    /***************Cierre del bloque***************************/
    //Direccion para el calculo de ruta
    directionsDisplay.setMap(map5);    
    
    var request = {
        origin: center,
        destination: comercio,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK)
        {
            directionsDisplay.setDirections(result);
            directionsDisplay.setOptions( { suppressMarkers: true } );
            //directionsDisplay.suppressMarkers = true;
        }
    });
        
//        
//        
//    function calcRoute(destino_latitud, destino_longitud, cliente_latitud, cliente_longitud, tipo) {
//    //genero los puntos para calcular la ruta
//        var start = new google.maps.LatLng(cliente_latitud, cliente_longitud);
//        var end = new google.maps.LatLng(destino_latitud, destino_longitud);
//        var request = {
//            origin: start,
//            destination: end,
//            travelMode: google.maps.TravelMode.DRIVING
//        };
//        if(tipo==="guardias"){
//            directionsService.route(request, function(result, status) {
//                if (status == google.maps.DirectionsStatus.OK)
//                {
//                    directionsDisplay.setDirections(result);
//                    directionsDisplay.suppressMarkers = true;
//                }
//            });
//        }
//        if(tipo==="farmacias"){
//            directionsService2.route(request, function(result, status) {
//                if (status == google.maps.DirectionsStatus.OK) {
//                    directionsDisplay2.setDirections(result);
//                    directionsDisplay2.suppressMarkers = true;
//                }
//            });
//        }
//    }
}

/** funcion para generar un popup. Adminite un objeto de opciones como parametro.
 * 
 * @param {type} obj opciones admitidas: <br>
 * title: Titulo de arriba (html)<br>
 * titleClass : clase para el bloque del titulo<br>
 * contenido: contenido a mostrar (html)<br>
 * contenidoClass: clase para el bloque de contenido <br>
 * parentChildrenClass: clase para los objetos sobre los cuales se sobrepondra este popup<br>
 * contenedorClass: clase para el contenedor.<br>
 * contenedorCloseClass: claseWrapper para gestionar el cierre. Por defecto closeWrapper<br>
 * closeClass: clae para gestionar elcierre on click. Por defecto "close"<br>
 * closeCallback: funcion a llamar cuando se cierre.
 * @returns {undefined}
 */
$.fn.popup = function(obj){
    var base = {
        title : "Prueba",
        titleClass : "",
        contenido : "Mensaje de prueba",
        contenidoClass: "",
        parentChildrensClass: "filter1",
        contenedorClass: "overlayWrapper popup-element",
        contenedorCloseClass : "closeWrapper",
        overlayClass: "overlayBackground",
        closeClass: "close",
        closeCallback: false,
        renderCallback: false
    }
    
    var options = $.extend(base, obj);
    var d = new Date();
    var id = d.getMilliseconds();
    var target = $(this);

    //creamos el objeto en si.
    var obj = $("<div/>", {
        id: id,
        class: options.contenedorClass + " " + options.contenedorCloseClass
    });
    
    //creamos el close.
    var close = $("<div/>", {
        class: options.closeClass
    });
    
    //creamos el titulo
    var title = $("<div/>", {
        class: options.titleClass,
        html: options.title    
    });
    
    //creamos el contenido
    var contenido = $("<div/>", {
        class: options.contenidoClass,
        html: options.contenido
    });
    
    var overlay = $("<div/>", {
        class: options.overlayClass
    });
    
    $(this).children().addClass(options.parentChildrensClass);    
    
    var position = $(this).css("position");
    if ( position != "absolute" || position != "relative") {
        //$(this).css("position", "relative");
    }
    
    $(this).append(overlay);
    
    $("body").append(obj);
    $(obj).css({
        width: $(this).width()+"px",
        left: $(this).offset().left,
        top: $(this).offset().top       //REctocado para los poupus de Agenda
        /*top: $(this).offset().top+40*/
    }).append(close).append(title).append(contenido);
    
    $(obj).data("obj-related", $(this));
    
    $(close).click(function(event){
        event.stopPropagation();
        event.preventDefault();

//        $(parent).find(".filter1").removeClass("filter1");
//
//        $(".demo_popup").fadeOut(function(){
//            $( ".overlayBackground, .overlayBannerHeader").remove();
//            $(this).remove();
//        });


        var parent = $(this).parent().data("obj-related");
        parent.children( "." + options.parentChildrensClass ).removeClass( options.parentChildrensClass );
        parent.find( "." + options.overlayClass).removeClass( options.overlayClass );
        //parent.css("position", position);

        $(obj).fadeOut(function(){
            $(this).remove();
            $( ".overlayBackground").remove();

            if ( typeof options.closeCallback == "function"){
                options.closeCalback();
            }
        });

    });

    if ( options.renderCallback && typeof options.renderCallback == "function") {
        options.renderCallback();
    }
    
}

/*************************************************************************************
 *  FUnciones de handler
 *************************************************************************************/


function owl_programacion(){
    /********* Programacion - Animacion - Agenda ***/
    var owlpro = $("#owl-animacion");

    owlpro.owlCarousel({
            items : 3, //10 items above 1000px browser width
            itemsDesktop : [1000,3], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,3], // betweem 900px and 601px
            itemsTablet: [600,3], //2 items between 600 and 0
            itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,

    });
}

function owl_agenda_pinero(){
    /********* Programacion - Animacion - Agenda ***/

    var owl2 = $("#owl-agenda_pinero");

    owl2.owlCarousel({
        items : 7, //10 items above 1000px browser width
        itemsDesktop : [1000,7], //5 items between 1000px and 901px
        itemsDesktopSmall : [900,7], // betweem 900px and 601px
        itemsTablet: [600,7], //2 items between 600 and 0
        navigation : true, // Show next and prev buttons
        slideSpeed : 300,
        loop:true,
        paginationSpeed : 400
    });

}


function handler_getinfo_piscinas(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        console.log(responseDatos);

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".actividades > div").css({
            height: "417px",
//            height: "96%",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}



/*************************************************************************************                  INFORMACION ********/

/** funcion handler de la peticion para informacion de farmacias de guardai (informacion/Farmacias)
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getInfo_farmacias(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    
    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)
        
        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }
        
        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();
        

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


function handler_getInfo_cajeros(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();


        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

function handler_getInfo_gasolineras(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();


        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


/** funcion handler de la peticion para informacion general (informacion/Telefonos de interes) 
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getInfo_general(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }
        
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");

        });
    }
}


function handler_getInfo_misas(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");

        });
    }
}


function handler_getInfo_consulados(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");

        });
    }
}


function handler_getInfo_ttoo(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");

        });
    }
}


function handler_getInfo_carnaval(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");

        });
    }
}


/** funcion handler de la peticion para informacion de coches (informacion/Alquiler de coches 
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getInfo_rentCar(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            return;
        }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.find(".scroll").css({
//            height: "385px"
            height: "96%"
        }).setSlimScroll();
        
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


/** funcion handler de la peticion para informacion sobre transportes publicos (informacion/transportes) 
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getInfo_transportePublico(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            return;
        }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }
        
        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


function handler_getInfo_medico(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

function handler_getInfo_vuelos(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            console.log(e);
            return;
        }

    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        console.log(responseDatos);

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }
        
        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".actividades > div").css({
            height: "417px",
//            height: "96%",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });
        
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

function handler_getInfo_aeropuertos_new(data, originObject) {

    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior) {
            $("body").append("<div class='overlayBannerHeader hidden'><img src='" + responseDatos.banner_superior + "' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function () {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

function handler_getInfo_mareas(data, originObject) {
    var responseDatos;

    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        console.log(responseDatos);

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".actividades > div").css({
            height: "417px",
//            height: "96%",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}




/******************************************************************************************       CONOCE      ***/


/** @type MICROSITE2
 * 
 * @param {type} data dato a procesar
 * @param {type} originObject objeto sobre el que se hizo click
 * @returns {unresolved}
 */
function handler_getConoce_Municipios(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");
        
        
//        newChild.hide();
//        newChild.setColorToSubsectionElements(originObject)
//        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        initializeConoceGC();
        
        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");
                                
                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
/*
        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".actividades > div").css({
            height: "417px",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
*/

    }
}


/**
 * 
 * @param {type} data dato a procesar
 * @param {type} originObject objeto sobre el que se hizo click
 * @returns {unresolved}
 */
function handler_getConoce_listadoMunicipio(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");


        initializeConoceGC();

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".actividades > div").css({
            height: "417px",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });

        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

/**
 *  funcion para cuando se hace click pra ver los details de una ruta determinada.
 * @param {type} data
 * @param {type} originObject
 * @returns {unresolved}
 */
function handler_getConoce_rutaDetail(data, originObject){
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            console.log(e);
            return;
        }

    if (responseDatos.error) {

    }
    else {

        return false;

            //Codigo para la inicializacion de la galeria incrustrada dentro de las rutas.
            $('.iosslider_ruta').iosSlider('destroy');
            $('.iosslider_ruta').iosSlider({   
                desktopClickDrag: true,
                snapToChildren: true,
                navNextSelector: $('.iosslider_ruta .next'),
                navPrevSelector: $('.iosslider_ruta .prev'),
                autoSlide: true,
                autoSlideTimer: 2000,
                infiniteSlider: true,
                autoSlideToggleSelector: $('.iosslider_ruta .gallery_play')
            });

            $('.iosslider_ruta .gallery_play').click(function(event){
                event.stopPropagation();

                $(this).toggleClass("play");

                if($(this).hasClass("play")){
                    $('.iosslider_ruta').iosSlider('autoSlidePlay');
                }
                else{
                    $('.iosslider_ruta').iosSlider('autoSlidePause');
                }
            });
            


            
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");


        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        newChild.find(".scroll").css({
            height: "420px",
            overflow: "auto"
        }).each(function() {
            $(this).setSlimScroll();
        });
        
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


function handler_getDestino_listadoZonas(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");
        

        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");
                                
                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}


/*Para el lugar donde se ecnuentra el totem*/
function handler_getLugar(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");
        

        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");
                                
                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}



///////////////////////////////////////////////////////////////Para la seccion de sostenibilidad
/*Para el lugar donde se ecnuentra el totem*/
function handler_getSostenibilidad(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");
        

        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");
                                
                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}


function handler_getSultan(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");


        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");

                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}

function handler_getHoteles(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");


        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");

                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}


function handler_getSpa(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");


        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");

                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}


///////////////////////////////////////////////////////////////Para la seccion de Eurocopa
/*Para el lugar donde se ecnuentra el totem*/
function handler_getEurocopa(data, originObject){
    var responseDatos;

    try {
        responseDatos = JSON.parse(data);
    }
    catch (e) {
        //que hago?
        console.log(e);
        return;
    }

    if (responseDatos.error) {

    }
    else {

        $("body").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");


        //Aqui va el codigo de inicialziacion pertinente
        initializeDestinos();


        if ( !$("#pageBannerWrapper").hasClass("bottom") ) {
            $("#pageBottomWrapper").animate({
                "bottom":"-533px"
            }, 200, function(){
                $(this).addClass("bottom");

                setTimeout(function(){
                    $(originObject).parents(".menuContent").find(".menuContentHidden").removeClass("menuContentHidden").fadeIn("250");
                },500);
            });
        }
    }
}

///////////////////////////////////////////////////////////////////////////


/******************************************************************************************       HOTEL      ***/

/** funcion handler de la peticion para informacion sobre hotel/queHacer
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getHotel_queHacer(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            return;
        }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        newChild.setPositionsForSection(function() {

        });
    }
}


/** funcion handler de la peticion para informacion sobre hotel/postales
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getHotel_postales(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            return;
        }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject);
        
        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();

        
        newChild.setPositionsForSection(function() {
            initializeKeyboardListeners(function(){
                $("#texto textarea").updatePostalTexto(); 
            });
            $("#fondo .postal_component:first, #foto .postal_component:first").click();
        });
        
        //initializo listeners
        $(".postal_component").click(function(){
            $(this).siblings(".postal_component_active").removeClass("postal_component_active");
            
            $(this).addClass("postal_component_active");
            
            initializePostalComponents();
        });
        
        var postalesChild = responseDatos.postalesDesingWrapperHTML;
        
        //muestro la pantalla de las fotos ya en tiempo real.
        $("body").append(postalesChild);
        $("#postalesDesignWrapper").show("350", function(){
            
        });
        
        //listener para "formatear" el texto.
        $("#texto textarea")[0].addEventListener("input" , function(){
            $(this).updatePostalTexto();            
        });
        
        
        //// HANDLER DEL SUBMIT de postales.
        $("form#enviarPostal").submit(function(){
            
        });
        
        var lockForCanvas = false;
        $("form#enviarPostal").ajaxForm({
            target: null,
            beforeSubmit: function(){

                if (lockForCanvas) {
                    setOverlayContentLoadingGif();
                    return true;
                }

                //debo generar la imagen.
                html2canvas( $("#postal")[0], {
                    onrendered: function(canvas){
                        $("form#enviarPostal").find("input#canvasDeLaPostal").val(canvas.toDataURL("image/jpeg",0.8));
                        lockForCanvas = true;
                        $("form#enviarPostal").submit();
                    }
                });
                
                return false;
            },
            success: function(){
                //recibo la respuesta del servidor.
                lockForCanvas = false;
                removeOverlayContentLoadingGif();
                $("form#enviarPostal").after("<h5 class='mensaje_respuesta text textAlign-center'>"+mensaje_postales_final[idioma]+"</h5>");
                $("#email_postal").val("");
                $("textarea").val("");
                $("#textoPostal p").empty();

                //$(".mensaje_respuesta").fadeIn();
                setTimeout (function(){
                    $(".mensaje_respuesta").fadeOut('slow');
                    removeOverlayContentLoadingGif();
                },7000);
            },
            error: function(){
            //recibo la respuesta del servidor.
                lockForCanvas = false;
                removeOverlayContentLoadingGif();
                $("form#enviarPostal").after("<h5 class='mensaje_respuesta text textAlign-center'>"+mensaje_postales_final[idioma]+"</h5>");
                $("#email_postal").val("");
                $("textarea").val("");
                $("#textoPostal p").empty();

                //$(".mensaje_respuesta").fadeIn();
                setTimeout (function(){
                    $(".mensaje_respuesta").fadeOut('slow');
                    removeOverlayContentLoadingGif();
                },7000);

            }

        });

        
    }
}

$.fn.updatePostalTexto = function() {
    var texto = "<p>" + $(this).val().replace(/\n/g, "</p><p>") + "</p>";
    $("#postal").find("#textoPostal").html(texto);
}





function initializePostalComponents(){
    //obtenosmo el fondo, foto y texto activos.
    var srcFondo = $("#fondo .postal_component_active img").attr("src");
    var srcFoto = $("#foto .postal_component_active img").attr("src");
    

    //los inserto en la postal
    $("#postal .elemento").fadeOut(function(){
        $("#postal").find("#fotoWrapper img").attr("src", srcFoto);
        $("#postal").find("#fondoWrapper img").attr("src", srcFondo);
        
        $(this).fadeIn();
    });
}


/** funcion handler de la peticion para informacion sobre hotel/cuestionario
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getHotel_cuestionario(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }
    
    

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        // initializeCuestionario(); 
        $('.hover-star').rating();

        jQuery(document).ready(function() {
            setTimeout(function(){ 

                $('.boton_derecha_cuestionario').fadeIn();

            }, 1500)
        })

        initializeKeyboardListeners();
        newChild.setPositionsForSection(function() {
//            initializeCuestionario();
    
            $("textarea:first").parents("form").scrollTop(0);
            newChild.find("form").ajaxForm({
                target: null,
                beforeSubmit: function(){
                    // return false;
                },
                success: function(responseText, statusText, xhr, form){
                    $(form).find("input[type=submit]").fadeOut("200", function(){
                    });
                    $(".mensaje_gracias_cuestionario").fadeIn();
                    setTimeout( function(){
                        $(".mensaje_gracias_cuestionario").fadeOut();
                        $(form).parents(".formularioPaginasWrapper:visible").find(".back, .close").click();
                    }, 
                    "3500");
                }
            });
        });

    }
}

/** funcion handler para las galerias de fotos. 
 * 
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {type} originObject objeto que origino la peticion
 */
function handler_getHotel_galeria(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();
        }
        
        var color = $(".mainMenuElement a[href=#" + $(originObject).parents(".menuContentWrapper").attr("id") + "]").parents(".mainMenuElement").attr("color");

        newChild.find(".scroll").css({
//            height: "385px",
            height: "94%",
            overflow: "auto"
        }).setSlimScroll();
        
        newChild.find(".tabs_").tabs();
        newChild.find("ul").click(function() {
            $(".ui-state-default a", $(this)).css({
                color: "black"
            });
            $(".ui-state-default", $(this)).css({
                backgroundColor: "white"
            });
            $(".ui-state-active", $(this)).css({
                backgroundColor: color
            });
            $(".ui-tabs-active a", $(this)).css({
                color: "white"
            });
        }).click();
        
        newChild.setPositionsForSection(function() {
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}


/** funcion handler de la peticion para hotel, cuando se hace click en alguna sección de las 
 * generadas dinamicamente.
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getHotel_contenidoDinamico(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        newChild.hideTopElements();
        
        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader hidden'><img src='"+responseDatos.banner_superior+"' /></div>");
            $('.video_principal_totem').get(0).pause();

            if (newChild.find(".double_size").size()>0){
                $(".overlayBannerHeader").fadeIn("150");
            }
        }
        
        
        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function() {
            setHeightForTables();
        
            $(".overlayBannerHeader").fadeIn("150");
        });
    }
}

/** funcion handler de la peticion para informacion sobre hotel/sugerencias
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getHotel_sugerencias(data, originObject) {
    var responseDatos;

        try{
            responseDatos = JSON.parse(data);
        }
        catch(e) {
            //que hago?
            return;
        }

    if (responseDatos.error) {

    }
    else {

        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        if (responseDatos.banner_superior){
            $("body").append("<div class='overlayBannerHeader'><img src='"+responseDatos.banner_superior+"' /></div>");
        }

        //Inicializamos el teclado
        initializeKeyboardListeners();
        
        newChild.setPositionsForSection(function() {

            $("textarea:first").parents("form").scrollTop(0);
            newChild.find("form").ajaxForm({
                target: null,
                beforeSubmit: function(){
                    //return false;
                },
                success: function(responseText, statusText, xhr, form){
                    $(form).find("input[type=submit]").fadeOut("200", function(){
                        $(this).after("<div class='desc'>"+responseText+"</div>").fadeInd();
                        $(this).remove();
                    });


                   $(".boton_derecha_sugerencias").fadeOut();
                   $(".gracias_sugerencias").fadeIn();
                    setTimeout( function(){
                        $(".texarea_sugerencias").val("");
                        $(".gracias_sugerencias").fadeOut();
                        $(form).parents(".formularioPaginasWrapper:visible").find(".back, .close").click();
                    }, 
                    "3500");
                }
            });

        });
    }
}


/******************************************************************************************       ACTIVIDADES      ***/

/** funcion handler de la peticion para ACTIVIDAD, cuando se hace click en alguna sección de las 
 * generadas dinamicamente.
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getActividad_contenidoDinamico(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)
        newChild.setAlignForComercios();
        
        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        
        newChild.setPositionsForSection(function() {
            initializeFlipListener();
        });
    }
}


/******************************************************************************************      COMPRAS      ***/

/** funcion handler de la peticion para Compras, cuando se hace click en alguna sección de las 
 * generadas dinamicamente.
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getCompras_contenidoDinamico(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)

        newChild.setAlignForComercios();
        
        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function() {
            
            initializeFlipListener();
        });
    }
}


/******************************************************************************************       COMER_OCIO      ***/

/** funcion handler de la peticion para COMER y OCIO, cuando se hace click en alguna sección de las 
 * generadas dinamicamente.
 * @param {json} data datos en formato json que devuelve el servidor
 * @param {jqueryObject} originObject objeto que origino la peticion al servidor (donde se hizo click)*/
function handler_getComerOcio_contenidoDinamico(data, originObject) {
    var responseDatos;
    
    try{
        responseDatos = JSON.parse(data);
    }
    catch(e) {
        //que hago?
        return;
    }

    if (responseDatos.error) {

    }
    else {
        $(originObject).parents(".menuContent").append(responseDatos.datos);
        var newChild = $(originObject).parents(".menuContent").children(":last");

        newChild.hide();

        newChild.setColorToSubsectionElements(originObject)
        newChild.setAlignForComercios();
        
        newChild.find(".scroll").css({
//            height: "385px",
            height: "96%",
            overflow: "auto"
        }).setSlimScroll();
        newChild.setPositionsForSection(function() {
            initializeFlipListener();
        });
    }
}


function initializeFlipListener(){
    var height = $(".flip").outerHeight();

    $(".flip").unbind().click(function(){
        var obj = $(this);
        var i=0;
        
        $(this).stop().animate({
            height: '' + height + 'px'
        },{
            duration: 250,
//            specialEasing: {
//                height: "easeOutCirc",
//                marginTop: "easeOutCirc"
//            },
            step: function( now, fx ) {
                i += 5;
                if ( i <= 90 ){
                    $(this).css("transform", "rotateX(" + i + "deg)");
                }
            },
            complete: function() {
                $(this).children().toggleClass("displayNone");
                $(obj).stop().animate({
                    height: '' + height + 'px'
                }, {
                    duration: 250,
                    step: function( now, fx ) {
                        i -= 5;
                        if (i >= 0) {
                            $(this).css("transform", "rotateX(" + i + "deg)");
                        }
                    },
//                    specialEasing: {
//                        height: "easeInCirc",
//                        marginTop: "easeInCirc"
//                    },
                    complete: function(){
                        if ( !$(obj).find("div:first").is(":visible") ){
                            setTimeout(function(){
                                if ( !$(obj).find("div:first").is(":visible") ) {
                                    $(obj).click();
                                }
                            },10000);
                        }
                    }
                });
            }
        });
    });    
}

function setHeightForTables(){
     
    $(".tabla_2").each(function() {
        for (var i = 0; i < $(".tabla_2 .contenido_elemento").size(); i += 3) {

            var h1, h2, h3, hmax;

            h1 = $(this).find(".contenido_elemento:eq(" + i + ")").height();
            h2 = $(this).find(".contenido_elemento:eq(" + i + ")").next().height();
            h3 = $(this).find(".contenido_elemento:eq(" + i + ")").next().next().height();

            hmax = h1 > h2 ? h1 : h2;
            hmax = hmax > h3 ? hmax : h3;

            $(this).find(".contenido_elemento:eq(" + i + ")").height(hmax);
            $(this).find(".contenido_elemento:eq(" + (i + 1) + ")").height(hmax);
            $(this).find(".contenido_elemento:eq(" + (i + 2) + ")").height(hmax);
        }
    });
    
    $(".tabla_1").each(function(){
    for (var i=0; i< $(this).find(".contenido_elemento").size(); i+=3) {

        var h1, h2, h3, hmax;

        h1 = $(this).find(".contenido_elemento:eq("+i+")").height();
        h2 = $(this).find(".contenido_elemento:eq("+i+")").next().height();
        h3 = $(this).find(".contenido_elemento:eq("+i+")").next().next().height();

        hmax = h1 > h2 ? h1 : h2;
        hmax = hmax > h3 ? hmax : h3;

        $(this).find(".contenido_elemento:eq("+i+")").height(hmax);
        $(this).find(".contenido_elemento:eq("+ (i+1) +")").height(hmax);
        $(this).find(".contenido_elemento:eq("+ (i+2) +")").height(hmax); 
    }
    });
    
    $(".tabla_3").each(function(){
        
        for (var i=0; i< $(this).find(".contenido_elemento").size(); i+=2) {

            var h1, h2, hmax;

            h1 = $(this).find(".contenido_elemento:eq("+i+")").height();
            h2 = $(this).find(".contenido_elemento:eq("+i+")").next().height();

            hmax = h1 > h2 ? h1 : h2;

            $(this).find(".contenido_elemento:eq("+i+")").height(hmax);
            $(this).find(".contenido_elemento:eq("+ (i+1) +")").height(hmax);
        }
    });
    $(".tabla_4").each(function(){        
        for (var i=0; i< $(this).find(".contenido_elemento").size(); i+=2) {

            var h1, h2, hmax;

            h1 = $(this).find(".contenido_elemento:eq("+i+")").height();
            h2 = $(this).find(".contenido_elemento:eq("+i+")").next().height();

            hmax = h1 > h2 ? h1 : h2;

            $(this).find(".contenido_elemento:eq("+i+")").height(hmax);
            $(this).find(".contenido_elemento:eq("+ (i+1) +")").height(hmax);
        }
    })
    
}



/**
 * @returns {undefined}
 */
$.fn.flip_municipios = function (){
    
    //hacemos el flip para mostrar nombres
    $(this).parent("div").find(".flip_municipio").each(function(){
        
        var obj = $(this);
        var width = $(this).outerWidth();
        var i=0;
        
        $(this).stop().animate({
            width: '' + width + 'px'
        }, {
            duration: 250,
//            specialEasing: {
//                height: "easeOutCirc",
//                marginTop: "easeOutCirc"
//            },
            step: function(now, fx) {
                i += 5;
                if (i <= 90) {
                    $(this).css("transform", "rotateY(" + i + "deg)");
                }
            },
            complete: function() {
                
                $(this).find(".flip_element").toggleClass("displayNone");
                
                if ( !$(obj).find(".flip_element:visible").hasClass("texto_slider") )
                {
                    //es la isla
                    //$(obj).addClass("remove_bg");
                    //Este cambio es para que aparezca primero mapas de gran canaria sin el background
                    //Para revertir el cambio ademas hay que descomentar el background de .municipio_element
                    $(obj).removeClass("add_bg");
                }
                else
                {
                    //$(obj).removeClass("remove_bg");
                    $(obj).addClass("add_bg");
                }
                
                $(obj).stop().animate({
                    width: '' + width + 'px'
                }, {
                    duration: 250,
                    step: function(now, fx) {
                        i -= 5;
                        if (i >= 0) {
                            $(this).css("transform", "rotateY(" + i + "deg)");
                        }
                    },
//                    specialEasing: {
//                        height: "easeInCirc",
//                        marginTop: "easeInCirc"
//                    },
                    complete: function() {
                        
                    }
                });
            }
        });
    });
    
}


function initialice_loading_municipio(municipio){
        municipio.parent().css('color','white');
        municipio.find('.texto_slider').css('color','white');
        municipio.find('.carga_municipio_2').css('display','block');
        municipio.find('.carga_municipio').css('display','block');
        $('.bloqueo_carga').css('display','block');
       // municipio.siblings().css('display','block');
        //$('.espera_datos_municipio').css('display','block');
    };

function stop_loading_municipio(){
    $('.espera_datos_municipio').css('display','none');
    $('.texto_slider').css('color','black');
    $('.carga_municipio_2').css('display','none');
    $('.carga_municipio').css('display','none');
    $('.bloqueo_carga').css('display','none');
};



function click_path(obj, indice) {

    href_target = obj[indice];
    target = $("a[href='" + href_target + "']");

        if ( target.size()>0) {

            if ( indice == 0 ) {
                //es el elemento del menu, no hay que hacer callback.
                target.mousedown();
                click_path(obj, indice+1);
            }
            else {
                //console.log(href_target);
                console.log("a[href='" + href_target + "']");
                setTimeout(function(){
                    $("a[href='" + href_target + "']").click();    

                    $( document ).ajaxComplete(function( event, xhr, settings ) {
                        click_path(obj, indice+1);
                    });

                }, 500);
            }
        }
        else {
            $( document ).ajaxComplete(function(){});
        }

    }

function click_path2(obj, indice) {

    // console.log('click_path2');

    // console.log(obj);

    href_target = obj[indice];
    target = $("a[href='" + href_target + "']");

    if ( target.size()>0) {

        if ( indice == 0 ) {
            //es el elemento del menu, no hay que hacer callback.
            target.mousedown();
            // return;
            click_path2(obj, indice+1);
        }
        else {
            //console.log(href_target);
            console.log("a[href='" + href_target + "']");
            setTimeout(function(){

                if ( (typeof(href_target) != "undefined" ) &&  (href_target.substr(0, 1) == '.') ){

                    console.log(obj.length);

                    //En este caso es un click a una carta, parseamos la variable separada por |
                    array_cadena = href_target.split("|");

                    //click_banner(array_cadena[0],array_cadena[1],array_cadena[2]);
                    click_banner(array_cadena[1],array_cadena[2]);

                    //obj = [];
                    for ( i =0 ; i!=obj.length;i++ ){ obj[i]='';}

                    return;

                }else{

                    $("a[href='" + href_target + "']").click();

                }

                $( document ).ajaxComplete(function( event, xhr, settings ) {
                    click_path2(obj, indice+1);
                    obj[0]='';
                    return;
                });

            }, 500);
        }
    }
    else {
        $( document ).ajaxComplete(function(){});
        obj='';
        return;

    }

}


function click_banner (imagen,titulo){
    var id_centro = $("#id_centro").text();
    //console.log('banner');

    $('.js_click_banner').parents(".content").popup({
        title: titulo,
        titleClass: "eventoDetailTitle",
        contenido: "<img src='../../../contenido_proyectos/pacoche/centro_"+id_centro+"/mas_info/"+imagen+".png'>",
        contenidoClass: "eventoDetailContent",
        contenedorClass: "overlayWrapper popup-element demo_popup ",
        renderCallback: function(){
            $('.js_click_banner').parents(".menuContentWrapper").click(function(event){
                if ( $(".demo_popup").size()>0) {
                    event.stopPropagation();
                    $(this).removeClass("filter1");
                    $(this).find(".filter1").removeClass("filter1");
                    $(".demo_popup").fadeOut(function(){
                        $( ".overlayBackground").remove();
                        $(this).remove();

                    })
                }
            });
        }

    });

    return;
}






    
