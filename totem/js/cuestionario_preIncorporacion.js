/* Este array contiene para cada indice una clase de css que contiene el background de cada value*/
var inputsClassIconsFromValue = {
	"muy bien" : "muyBueno",
	"bien" : "Bueno",
    "neutral" : "Neutral",
	"regular" : "Regular",	
	"mal" : "Mal"
};
var color;

$("document").ready(function() {
});


function initializeCuestionario(){
    
    // activamos las pestañas
    $("#cuestionario").tabs({
        active: 0
    });

    // listener para desplegar el input para
    $(".menuContent").on("click", "#otra_razon", function(event) {
        $("#otro").slideDown();
    });

    //listener para ocultar el imput
    $(".menuContent").on("click", ".ocultar", function(event) {
        $("#otro").slideUp();
    });

    //obtenemos el color del elemento del menu atual
    color = $("#elmt2").attr("color");

    //establecemos el color para la pestaña activa
    setColorForActiveTab();
    $("#hotelWrapper ul").on("click", "li", function() {
        setColorForActiveTab();
    });

    // ACTIVAMOS LA FUNCION PARA CONTROLAR LA ACCION DE PASAR A LA SIGUIENTE PESTAÑA

    //asociamos un listener al boton de enviar/siguiente. Comprobamos el valor que solo se activa al pasar de pestañas
    $(".formularioPaginas input:submit").click(function(event) {
        if ($(this).data("submit") != 1) {
            event.preventDefault();
            next_poll();
        }
    });

    //listener para cuando rebote el click de pasar de pestaña
    $(".formularioPaginas_nav").on("click", "li", function() {
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

    //activamos la funcionalidad definida arriba
    $("li.ui-tabs-active").children("a").click();
}

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

 