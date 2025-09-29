//Variables que almacenan diferentes tipos de espacio
var guardias = ""; //variable que guardara el json con guardias
var farmacias="";
var cliente = "";

var directionsDisplay = new google.maps.DirectionsRenderer();
var directionsDisplay2 = new google.maps.DirectionsRenderer();
var directionsService = new google.maps.DirectionsService();
var directionsService2 = new google.maps.DirectionsService();

var tiempo_transicion = 10000 //milisegundos
var total_guardias = 0;


$(document).ready(function() {

    // Obtener la informacion que necesita el js que el php ha introducido en el DOM
    var hotel_longitud = $('#farmacias').attr('data-hotel-longitud');
    var hotel_latitud = $('#farmacias').attr('data-hotel-latitud');
    var centro = $('#farmacias').attr('data-centro');
    var centro_cod_prov = $('#farmacias').attr('data-centro-cod-prov');
    
    // Json de contiene todas las farmacias que se pasa del php al DOM para no tener que llamar por ajax otra vez
    var JSON_posicion_farmacia = $('#JSON_posicion_farmacia').text();

    initialize(hotel_longitud,hotel_latitud,JSON_posicion_farmacia);


    // Forzar el click en el primer elemento a los 300 ms
    setTimeout(function(){ $('.farmacia_listado').first().click();}, 300);


    // Cuando se clickea en una farmacia
    $('.farmacia_listado').click(function(){

        // Se quita la farmacia seleccionada y se pone la clickada
        $('.farmacia_listado').removeClass('farmacia_listado_actual');
        $(this).addClass('farmacia_listado_actual');

        // Se obtiene la longitud y latitud de la farmacia seleccionada que se ha pasado previamente desde el php
        var farmacia_longitud = $(this).attr('data-farmacia-longitud');
        var farmacia_latitud = $(this).attr('data-farmacia-latitud');


        // Se coge la misma direccion que la que aparece en la barra izquierda
        var direccion_farmacia = $(this).children('.direccion-izq').text();
        $('.info_direccion').text(direccion_farmacia); 


        // Se recoge la id de la farmacia
        var id = $(this).attr('data-id');

        // Añadir qR
        var qr = $('.qr').children('img');
        qr.attr("src", "../../../contenido_proyectos/pacoche/centro_"+centro+"/farmacias_qr/"+id+".png");


        // Añadir imagen
        var foto_farmacia = $('.foto').children('img');
        foto_farmacia.attr("src", "../../../contenido_proyectos/pacoche/centro_"+centro+"/imagenes/farmacias/"+centro_cod_prov+"/"+id+".png");


        // Se calcula la ruta
        calcRoute(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud);

        // Calcular distancia a coche
        calcDistanceDrive(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud, id);

        // Calcular distancia a pie
        calcDistanceWalk(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud, id);


    });

    

});

function initialize(hotel_longitud,hotel_latitud,JSON_posicion_farmacia) { 

    // Se carga el mapa de google y se centra la posicion

    var center = new google.maps.LatLng(hotel_latitud, hotel_longitud);
    
    //***************
    //variables para imagenes
    var image = new google.maps.MarkerImage('../../../contenido_proyectos/pacoche/_general/iconos/farmacias.png', new google.maps.Size(32, 36), new google.maps.Point(0, 0));
    var image_here = new google.maps.MarkerImage('../../../contenido_proyectos/pacoche/_general/iconos/aqui.png', new google.maps.Size(32, 36), new google.maps.Point(0, 0));
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
        zoom: 14,
        center: center,
        disableDefaultUI: true,
        styles: EscalaGris,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControl: true,
    }


    // Se crea el mapa en la id con las opciones
    map = new google.maps.Map(document.getElementById("map_canvas_total"), myOptions);
    var rendererOptions = { map: map };
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    
    
    // Se convierte el json en array
    var array_posicion_farmacia = JSON.parse(JSON_posicion_farmacia);

    // Se pinta cada una de las farmacias en el mapa
    $.each(array_posicion_farmacia, function(index, value) {

         var farmacia_position = new google.maps.LatLng(value.farmacia_latitud, value.farmacia_longitud);
        
        new google.maps.Marker({
            position: farmacia_position,
            map: map,
            icon: image
        });
    });
    
    // Bloque que genera farmacia del cliente 
    var hotel_position = new google.maps.LatLng(hotel_latitud, hotel_longitud);

    new google.maps.Marker({
        position: hotel_position,
        map: map,
        icon: image_here,
        title: 'Su posición',
        zIndex: 99
    });
    

}

//Funcion que calcula las rutas desde el cliente hasta el destino
function calcRoute(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud) {
//genero los puntos para calcular la ruta
    var start = new google.maps.LatLng(hotel_latitud, hotel_longitud);

    var end = new google.maps.LatLng(farmacia_latitud, farmacia_longitud);
    var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService = new google.maps.DirectionsService();
    directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK)
            {
                directionsDisplay.setDirections(result);
                directionsDisplay.setOptions( { suppressMarkers: true } );
            }

    });
}


//Funcion que calcula las rutas desde el cliente hasta el destino
function calcDistanceDrive(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud, id) {
//genero los puntos para calcular la ruta

    var start = new google.maps.LatLng(hotel_latitud, hotel_longitud);

    var end = new google.maps.LatLng(farmacia_latitud, farmacia_longitud);


    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
      {
        origins: [start],
        destinations: [end],
        travelMode: google.maps.TravelMode.DRIVING,
        avoidHighways: false,
        avoidTolls: false
      }, callback);

    function callback(response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
//                alert('Error was: ' + status);
            }
            else 
            {
                    $('#car_total').text(response.rows[0].elements[0].distance.text);
                    $('#car_duration').text(response.rows[0].elements[0].duration.text);
            }
    }

}


//Funcion que calcula las rutas desde el cliente hasta el destino
function calcDistanceWalk(farmacia_latitud, farmacia_longitud, hotel_latitud, hotel_longitud, id) {
//genero los puntos para calcular la ruta

    var start = new google.maps.LatLng(hotel_latitud, hotel_longitud);

    var end = new google.maps.LatLng(farmacia_latitud, farmacia_longitud);


    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
      {
        origins: [start],
        destinations: [end],
        travelMode: google.maps.TravelMode.WALKING,
        avoidHighways: false,
        avoidTolls: false
      }, callback);

    function callback(response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                
            }
            else 
            {       
                    $('#walk_total').text(response.rows[0].elements[0].distance.text);
                    $('#walk_duration').text(response.rows[0].elements[0].duration.text);
            }
    }

}


