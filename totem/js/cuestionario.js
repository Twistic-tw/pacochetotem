/* Este array contiene para cada indice una clase de css que contiene el background de cada value*/
var inputsClassIconsFromValue = {
    "muy bien": "muyBueno",
    "bien": "Bueno",
    "neutral": "Neutral",
    "regular": "Regular",
    "mal": "Mal"
};
var color;
$("document").ready(function() {

    
    /* Asociamos un listenner a cada imagen para que cuando se haga click sobre ella se seleccione su input radio */
    $("body").on("click", ".input_svg_face", function(event) {

//eliminamos el checked de todos los radios con el mismo nombre del elemento sobre el que se hizo click
//y tambien le quitamos la clase active al emoticono        
//        var nameInput = $(this).children("input").attr("name");
//        $("input[name=" + nameInput + "]").each(function() {
//            
//            $(this).removeProp ("checked");
//            $(this).parents(".input_svg_face").removeClass("active");
//        });
//        
        $(this).parents(".same_typeface").find(".input_svg_face.active").each(function(){
            $(this).find("input").removeProp ("checked");
            $(this).removeClass("active");
            $(this).find("svg").find("path, circle").attr("fill","#ffffff");
            
        });
        
        //ponemos como checked el radio
        //añadimos la clase active al elemento sbre el que se hizo click
        
        $(this).find("input").prop("checked", "checked");
        $(this).addClass("active");
        
        $(this).find("svg").find("path, circle").attr("fill", color);
        
        //Le damos un id al div que contiene todo la pregunta del radiobutton
        //para asi controlar el efecto del radiobutton que este activo en cada momento
        var id_padre = 'padre_' + $(this).children("input").attr("name");
        $(this).parent().parent().addClass(id_padre);
        $('.' + id_padre + " .valor .emotico_click").removeClass("emotico_click");
        $(this).addClass("emotico_click").css({
        });
    });
    
    // listener para desplegar el input para
    $(".menuContent").on("click", "#otra_razon", function(event) {
        $("#otro").slideDown();
    });
    
    //listener para ocultar el imput
    $(".menuContent").on("click", ".ocultar", function(event) {
        $("#otro").slideUp();
    });
    
    //listener para cuando rebote el click de pasar de pestaña
    $("body").on("click", ".formularioPaginas_nav li", function() {
        var target = $(this).children("a").attr("href");
        var targetParentForm = $(target).parents("form.formularioPaginas");
        if ($(this).next("li").size() == 0) {
//////////  REVIZAR PARA ACOTAR AL IDIOMA /////
            $("input:submit", targetParentForm).val("Enviar").data("submit", 1);
        }
        else {
            $("input:submit", targetParentForm).val("Siguiente").data("submit", 0);
        }
    });
});
        
        objetos = {
        "muy bien" : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="57px" viewBox="0 0 57 57" enable-background="new 0 0 57 57" xml:space="preserve"> <g> <path fill="#FFFFFF" d="M28.305,56.849c-15.631,0-28.349-12.717-28.349-28.349c0-15.631,12.717-28.349,28.349-28.349 c15.632,0,28.349,12.718,28.349,28.349C56.654,44.132,43.938,56.849,28.305,56.849L28.305,56.849z M28.305,4.485 C15.063,4.485,4.29,15.259,4.29,28.5c0,13.242,10.773,24.016,24.015,24.016S52.32,41.742,52.32,28.5 C52.32,15.259,41.547,4.485,28.305,4.485L28.305,4.485z"/> <g> <circle fill="#FFFFFF" cx="39.14" cy="28.502" r = "3.882"/> <circle fill="#FFFFFF" cx="17.471" cy="28.502" r="3.882"/></g> <g> <path fill="#FFFFFF" d="M28.305,44.646c-4.214,0-8.206-1.622-11.243-4.569l-3.835-3.721h30.152l-3.83,3.721 C36.516, 43.024, 32.521, 44.646, 28.305, 44.646L28.305, 44.646z"/></g></g></svg>' ,
        "bien" : '<svg version="1.1" id="Capa_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="57px" viewBox="0 0 57 57" enable-background="new 0 0 57 57" xml:space="preserve"> <g> <path fill="#FFFFFF" d="M28.273,56.919c-15.632,0-28.349-12.717-28.349-28.349c0-15.631,12.717-28.349,28.349-28.349 c15.631,0,28.348,12.718,28.348,28.349C56.621,44.202,43.904,56.919,28.273,56.919L28.273,56.919z M28.273,4.556 c-13.242,0-24.015,10.773-24.015,24.015c0,13.242,10.773,24.016,24.015,24.016c13.242,0,24.014-10.773,24.014-24.016 C52.287,15.329,41.515,4.556,28.273,4.556L28.273,4.556z"/> <g> <circle fill="#FFFFFF" cx="39.106" cy="28.572" r="3.882"/> <circle fill="#FFFFFF" cx="17.439" cy="28.572" r="3.882"/> </g> <g> <path fill="#FFFFFF" d="M28.273,44.717c-4.215,0-8.209-1.622-11.243-4.569c-0.859-0.833-0.879-2.204-0.045-3.063 c0.833-0.857,2.205-0.879,3.063-0.045c2.222,2.156,5.142,3.345,8.225,3.345s6.002-1.188,8.224-3.345 c0.857-0.834,2.231-0.813,3.063,0.045c0.834,0.859,0.814,2.23-0.045,3.063C36.481,43.095,32.488,44.717,28.273,44.717 L28.273,44.717z"/></g></g></svg>',
        "neutral" : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="57px" viewBox="0 0 57 57" enable-background="new 0 0 57 57" xml:space="preserve"><g><path fill="#FFFFFF" d="M28.444,56.848c-15.632,0-28.349-12.717-28.349-28.348c0-15.631,12.717-28.348,28.349-28.348 S56.793,12.869,56.793,28.5C56.793,44.131,44.076,56.848,28.444,56.848L28.444,56.848z M28.444,4.485 C15.202,4.485,4.429,15.258,4.429,28.5c0,13.243,10.773,24.016,24.015,24.016c13.241,0,24.015-10.773,24.015-24.016 C52.459,15.258,41.686,4.485,28.444,4.485L28.444,4.485z"/><g><circle fill="#FFFFFF" cx="39.278" cy="28.501" r="3.882"/> <circle fill="#FFFFFF" cx="17.61" cy="28.501" r="3.882"/> </g> <path fill="#FFFFFF" d="M35.531,43.398H21.357c-1.196,0-2.167-0.971-2.167-2.168c0-1.195,0.97-2.166,2.167-2.166h14.174 c1.196,0,2.167,0.971,2.167,2.166C37.698,42.428,36.728,43.398,35.531,43.398L35.531,43.398z"/> </g></svg>',
        "regular" : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="57px" viewBox="0 0 57 57" enable-background="new 0 0 57 57" xml:space="preserve"><g> <path fill="#FFFFFF" d="M28.273,56.849c-15.632,0-28.349-12.717-28.349-28.349c0-15.631,12.717-28.348,28.349-28.348 c15.631,0,28.348,12.717,28.348,28.348C56.621,44.132,43.904,56.849,28.273,56.849L28.273,56.849z M28.273,4.485 C15.031,4.485,4.258,15.259,4.258,28.5c0,13.242,10.773,24.016,24.015,24.016c13.242,0,24.014-10.773,24.014-24.016 C52.287,15.259,41.515,4.485,28.273,4.485L28.273,4.485z"/> <g> <circle fill="#FFFFFF" cx="39.106" cy="28.502" r="3.882"/> <circle fill="#FFFFFF" cx="17.439" cy="28.502" r="3.882"/> </g> <g> <path fill="#FFFFFF" d="M38.006,44.646c-0.544,0-1.088-0.203-1.509-0.612c-2.222-2.156-5.142-3.345-8.224-3.345 s-6.002,1.188-8.225,3.345c-0.856,0.836-2.23,0.814-3.063-0.045c-0.834-0.858-0.814-2.23,0.045-3.063 c3.034-2.947,7.028-4.569,11.243-4.569s8.208,1.622,11.243,4.569c0.859,0.833,0.879,2.205,0.045,3.063 C39.137,44.427,38.572,44.646,38.006,44.646L38.006,44.646z"/></g></g></svg>',
        "mal" : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="57px" viewBox="0 0 57 57" enable-background="new 0 0 57 57" xml:space="preserve"><g> <g><path fill="#FFFFFF" d="M38.178,44.241c-0.545,0-1.088-0.203-1.51-0.612c-2.221-2.157-5.142-3.345-8.224-3.345 c-3.083,0-6.002,1.188-8.224,3.345c-0.858,0.835-2.229,0.814-3.064-0.045c-0.834-0.859-0.814-2.23,0.045-3.064 c3.034-2.946,7.028-4.568,11.243-4.568c4.215,0,8.208,1.622,11.243,4.568c0.859,0.834,0.879,2.205,0.045,3.064 C39.308,44.021,38.744,44.241,38.178,44.241L38.178,44.241z"/> </g> <path fill="#FFFFFF" d="M28.444,56.443c-15.632,0-28.349-12.717-28.349-28.349S12.813-0.254,28.444-0.254 s28.349,12.717,28.349,28.349S44.076,56.443,28.444,56.443L28.444,56.443z M28.444,4.08c-13.242,0-24.015,10.772-24.015,24.015 S15.202,52.11,28.444,52.11c13.241,0,24.015-10.773,24.015-24.016S41.686,4.08,28.444,4.08L28.444,4.08z"/><g><circle fill="#FFFFFF" cx="39.278" cy="28.097" r="3.882"/><circle fill="#FFFFFF" cx="17.61" cy="28.097" r="3.882"/></g><g><path fill="#FFFFFF" d="M34.49,23.188c-0.695,0-1.379-0.335-1.799-0.957c-0.668-0.991-0.406-2.339,0.587-3.007l7.12-4.798 c0.987-0.666,2.337-0.408,3.008,0.587c0.668,0.991,0.406,2.339-0.588,3.007l-7.119,4.798 C35.328,23.067,34.908,23.188,34.49,23.188L34.49,23.188z"/><path fill="#FFFFFF" d="M22.398,23.188c-0.417,0-0.838-0.12-1.209-0.37L14.07,18.02c-0.993-0.668-1.256-2.016-0.587-3.007 c0.671-0.995,2.019-1.253,3.007-0.587l7.12,4.798c0.993,0.668,1.255,2.016,0.587,3.007C23.778,22.853,23.094,23.188,22.398,23.188 L22.398,23.188z"/></g></g></svg>'
        };
        
function initializeCuestionario() {

    /*Establer estilo para la clase input_emoticono; que seran los estilos de los radiobutton con imagen*/
    $(".input_emoticono").each(function() {
        //var clase = inputsClassIconsFromValue[ $(this).attr('value') ];
        var objSvg = objetos[ $(this).attr("value") ];
        $(this).after("<div class='input_svg_face'>" + objSvg + "</div>");
        //$(this).after("<div class='input_radio_img " + clase + "'></div>");
        $(this).appendTo($(this).siblings(".input_svg_face"));
        $(this).hide();
    });
    // activamos las pestañas
    $("#cuestionario").tabs({
        active: 0
    });
}

//({
//        borderTopColor: color,
//        borderTopWidth: "5px",
//        borderTopStyle: "solid"
//    });

//    $(".scroll").slimScroll({//para el scroll personalizado del cuestionario
//        //height: '285px',    
//        railColor: 'black',
//        railOpacity: "0.7",
//        railVisibile: true,
//        alwaysVisible: true,
//        opacity: ".6",
//        color: "white",
//        scrollBy: '1px',
//        size: "10px",
//        disableFadeOut: true
//    });

    // $(".scroll").setSlimScroll();
    
    //activamos la funcionalidad definida arriba
/*    $("li.ui-tabs-active").children("a").click();*/
    // ACTIVAMOS LA FUNCION PARA CONTROLAR LA ACCION DE PASAR A LA SIGUIENTE PESTAÑA

    //asociamos un listener al boton de enviar/siguiente. Comprobamos el valor que solo se activa al pasar de pestañas
//    $(".formularioPaginas input:submit").click(function(event){
//        if ( $(this).data("submit") != 1) {
//            event.preventDefault();
//            next_poll();
//        }
//    });
    /*Esta funcion dividira las preguntas para pintarlas en dos div derecho e izquierdo*/
//    var cantidadDiv = $("#medio > div").length;
//});

function setColorForActiveTab() {
    $("#hotelWrapper li.active").removeClass("active").removeAttr("style");
    $("#hotelWrapper li.ui-tabs-active").addClass("active").css({
        background: color
    });
}

/** funcion para pasar de pestañas. Se llama al hacer click sobre una pestaña o sobre el boton de
 * "siguiente" */
function next_poll() {
    $("li.ui-tabs-active").next().children("a").click();
}
