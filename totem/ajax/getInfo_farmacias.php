<?php


// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Comprueba si hay internet
if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}

$id_centro = $_SESSION['id_centro'];
$id_lugar =  $_SESSION['id_lugar'];


if ($id_centro == '22' || $id_centro == '266' || $id_centro == '267')
{
    $tpl_info_farmacia = new TemplatePower("plantillas/seccion_farmacias_palma.html", T_BYFILE);
    $tpl_info_farmacia->prepare();

    registrarLog("informacion", "farmacias");

    $tpl_info_farmacia->assign("farmacias_title", LANG_INFO_FARMACIAS_TITLE );
    $tpl_info_farmacia->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

    // Obtengo las coordenadas del hotel (funcion en lib_totem)
    $coordenadas_hotel = coordenadas_hotel($id_centro);

    $tpl_info_farmacia->assign(hotel_latitud, $coordenadas_hotel['0']['latitud']);
    $tpl_info_farmacia->assign(hotel_longitud, $coordenadas_hotel['0']['longitud']);

        
    // Creamos un array con la fecha y hora actual de al peticion
    $hoy = getdate();


    //Creamos una conexion SOAP para la API de Farmacias
    $cliente = new SoapClient( 
        'https://ecofib.cofib.es/API/WSFarmacies.asmx?WSDL', 
        array( 
            // Stuff for development. 
            'trace' => 1, 
            'exceptions' => true, 
            'cache_wsdl' => WSDL_CACHE_NONE, 
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 

            // Auth credentials for the SOAP request. 
            'login' => 'Twistic', 
            'password' => 'as23kdj4'
        ) 
    );

    // Array de peticion tipo obertes (api farmacias)

    $array =  array (
        'usuari' =>'Twistic',
        'dia' => $hoy['mday'],
        'mes' => $hoy['mon'],
        'any' => $hoy['year'],
        'hora' => $hoy['hours'], 
        'minuts' =>$hoy['minutes'],
        'latitud' =>$coordenadas_hotel['0']['latitud'],
        'longitud' => $coordenadas_hotel['0']['longitud']
    );

    // Obtener las faramacias que estan abiertas en este momento desde la api
    $response = $cliente->Obertes($array);



    // Cambio la clarse stdObject por Array (lib_totem)
    $farmacias = objectToArray($response);

    // Si Resultats 0 o null, error
    if ($farmacias == null ||  $farmacias['ObertesResult']['Resultats'] == 0) exit;


    //Dame las 6 primeras farmacias que esta ahora abiertas
    $j = 5;
    if  ($id_centro == '266')  $j = 4;

    for ($i=0; $i < $j ; $i++) {

        $tpl_info_farmacia->newBlock("farmacia_listado_total");
        
        $tpl_info_farmacia->assign(n_farmacia, $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Telefon']);
        $tpl_info_farmacia->assign(farmacia_direccion, $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Localitzacio']);
        $tpl_info_farmacia->assign(farmacia_titular, $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Nom']);
        $tpl_info_farmacia->assign(qr, $id_centro . "-" . $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Telefon'] . '.png');
        $tpl_info_farmacia->assign(farmacia_latitud, $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Latitud']);
        $tpl_info_farmacia->assign(farmacia_longitud, $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Longitud']);

        // Creacion del array para el paso a javascript
        $posicion_farmacias[$i] = array ( 
            'id' =>  $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Telefon'],
            'farmacia_latitud' => $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Latitud'],
            'farmacia_longitud' => $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Longitud']
            );  


        //***********************************
        //Apartado QR code Generacion de la foto Qr Code
        $qr = "http://maps.google.es/maps?saddr=" . $coordenadas_hotel['0']['latitud'] . "," . $coordenadas_hotel['0']['longitud'] . "&daddr=" . $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Latitud'] . "," . $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Longitud'];
        QRcode::png($qr, '../../../contenido_proyectos/pacoche/centro_' . $id_centro . '/farmacias_qr/' . $farmacias['ObertesResult']['Farmacies']['Farmacia'][$i]['Telefon'] . '.png', 'L', 5); //generacion de k QR
       

        //***********************************
       

    }

    $posicion_farmacias = json_encode($posicion_farmacias);

     //print_r($posicion_farmacias);


    // Se asigna el json con todas las farmacias al DOM para js
     $tpl_info_farmacia->assignGlobal(JSON_posicion_farmacia, $posicion_farmacias);


    $tpl_info_farmacia->assignGlobal("qr_mensaje", LANG_GLOBAL_QRMSG);
    $tpl_info_farmacia->assignGlobal("centro_id", $id_centro);


    // Asignamos a "PELO" la carpeta donde se encuentran las imágenes de las farmacias (debido a los requerimientos impuestos)
    $tpl_info_farmacia->assignGlobal("centro_cod_prov", '07');


    $tpl_info_farmacia->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);

    $id_centro = $_SESSION['id_centro'];
    $imagen = "farmacias.jpg";

    $resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;
    $resultado['datos'] = $tpl_info_farmacia->getOutputContent();

    echo json_encode($resultado);



}


// Si es el destino Gran Canaria se utiliza otro para coger las imagenes de las farmacias
// Además si es 2999 (Fitur) no se utiliza gran canaria
elseif ($id_lugar != '47' and $id_centro != 2999){

    $tpl_info_farmacia = new TemplatePower("plantillas/seccion_farmacias.html", T_BYFILE);
    $tpl_info_farmacia->prepare();

    $id_centro = $_SESSION['id_centro'];
    $id_lugar =  $_SESSION['id_lugar'];

    registrarLog("informacion", "farmacias");

    $tpl_info_farmacia->assign("farmacias_title", LANG_INFO_FARMACIAS_TITLE );
    $tpl_info_farmacia->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

    $tpl_info_farmacia->assign("lang_farmacias_guardia_text", LANG_INFO_FARMACIAS_GUARDIA);
    $tpl_info_farmacia->assign("lang_farmacias_text", LANG_INFO_FARMACIAS_TITLE);


    // Obtengo las coordenadas del hotel (funcion en lib_totem)
    $coordenadas_hotel = coordenadas_hotel($id_centro);



    $tpl_info_farmacia->assign('hotel_latitud', $coordenadas_hotel['0']['latitud']);
    $tpl_info_farmacia->assign('hotel_longitud', $coordenadas_hotel['0']['longitud']);

        
    // Creamos un array con la fecha y hora actual de al peticion


    $farmacias['cercanas'] = get_farmacias_cercanas();
    $farmacias['cercanas']['resultados'] = count( $farmacias['cercanas'] );

    /*
    $farmacias["cercanas"]["resultados"] = 2;

    $farmacias["cercanas"][0]["id"] = "1";
    $farmacias["cercanas"][0]["telefono"] = "928111111";
    $farmacias["cercanas"][0]["direccion"] = "Calle alonso fernandez";
    $farmacias["cercanas"][0]["nombre"] = "Farmacia 1 cercana";
    $farmacias["cercanas"][0]["latitud"] =  "27.7577122";
    $farmacias["cercanas"][0]["longitud"] = "-15.5530104";

    $farmacias["cercanas"][1]["id"] = "3";
    $farmacias["cercanas"][1]["telefono"] = "928222222";
    $farmacias["cercanas"][1]["direccion"] = "Calle mendoza gil";
    $farmacias["cercanas"][1]["nombre"] = "Farmacia 3 cercana";
    $farmacias["cercanas"][1]["latitud"] =  "27.767122";
    $farmacias["cercanas"][1]["longitud"] = "-15.5630104";
    */


    $farmacias["guardias"] = get_farmacias_guardia();
    $farmacias["guardias"]["resultados"] = count( $farmacias["guardias"] );


    /*
    $farmacias["guardias"][0]["id"] = "1";
    $farmacias["guardias"][0]["telefono"] = "928111111";
    $farmacias["guardias"][0]["direccion"] = "Calle alonso fernandez g";
    $farmacias["guardias"][0]["nombre"] = "Farmacia 1 guardia";
    $farmacias["guardias"][0]["latitud"] =  "27.8577122";
    $farmacias["guardias"][0]["longitud"] = "-15.6530104";

    $farmacias["guardias"][1]["id"] = "2";
    $farmacias["guardias"][1]["telefono"] = "928222222";
    $farmacias["guardias"][1]["direccion"] = "Calle bermudez gutierrez g";
    $farmacias["guardias"][1]["nombre"] = "Farmacia 2 guardia";
    $farmacias["guardias"][1]["latitud"] =  "27.7456503";
    $farmacias["guardias"][1]["longitud"] = "-15.5704081";

    $farmacias["guardias"][2]["id"] = "2";
    $farmacias["guardias"][2]["telefono"] = "928222222";
    $farmacias["guardias"][2]["direccion"] = "Calle bermudez gutierrez g";
    $farmacias["guardias"][2]["nombre"] = "Farmacia 2 guardia";
    $farmacias["guardias"][2]["latitud"] =  "27.7456503";
    $farmacias["guardias"][2]["longitud"] = "-15.5704081";*/

    // echo "cercanas".$farmacias['cercanas']['resultados'];
    // echo "guardias".$farmacias['guardias']['resultados'];
    // exit;

    // Si Resultados 0 o null, error
    if ($farmacias == null) exit; 
    if ($farmacias['cercanas']['resultados'] == 0) $tpl_info_farmacia->assign("cercana_display", displayNone);
    if ($farmacias['guardias']['resultados'] == 0) $tpl_info_farmacia->assign("guardia_display", displayNone);

    // Se obtienen las farmacias 
    $total_cercanas = $farmacias['cercanas']['resultados'];
    $total_guardias = $farmacias['guardias']['resultados'];


    //Dame las primeras farmacias cercanas
    for ($i=0; $i < $total_cercanas ; $i++) { 



        // Asignar farmacias cercanas 
        $tpl_info_farmacia->newBlock("farmacia_listado_total_cercanas");
        
        $tpl_info_farmacia->assign(n_farmacia, $farmacias["cercanas"][$i]["id"]);
        $tpl_info_farmacia->assign(farmacia_direccion, $farmacias["cercanas"][$i]["direccion"]);
        $tpl_info_farmacia->assign(farmacia_titular, $farmacias["cercanas"][$i]["nombre"]);
        $tpl_info_farmacia->assign(qr, $id_centro . "-" . $farmacias["cercanas"][$i]["id"] . '.png');
        $tpl_info_farmacia->assign(farmacia_latitud, $farmacias["cercanas"][$i]["latitud"]);
        $tpl_info_farmacia->assign(farmacia_longitud, $farmacias["cercanas"][$i]["longitud"]);
        $tpl_info_farmacia->assign(farmacia_telefono, $farmacias["cercanas"][$i]["telefono"]);


        // Creacion del array para el paso a javascript de farmacias cercanas
        $posicion_farmacias_cercanas[$i] = array ( 
            'id' =>  $farmacias["cercanas"][$i]["id"],
            'farmacia_latitud' => $farmacias["cercanas"][$i]["latitud"],
            'farmacia_longitud' => $farmacias["cercanas"][$i]["longitud"]
            );  


        //***********************************
        //Apartado QR code Generacion de la foto Qr Code
        $qr = "http://maps.google.es/maps?saddr=" . $coordenadas_hotel['0']['latitud'] . "," . $coordenadas_hotel['0']['longitud'] . "&daddr=" . $farmacias["cercanas"][$i]["latitud"] . "," . $farmacias["cercanas"][$i]["longitud"];
        QRcode::png($qr, '../../../contenido_proyectos/pacoche/centro_' . $id_centro . '/farmacias_qr/' . $farmacias["cercanas"][$i]["id"] . '.png', 'L', 5); //generacion de k QR
       

        //***********************************
       

    }



    // Dame las 6 primeras farmacias guardias
    for ($i=0; $i < $total_guardias ; $i++) { 



        // Asignar farmacias cercanas 
        $tpl_info_farmacia->newBlock("farmacia_listado_total_guardias");
        
        $tpl_info_farmacia->assign(n_farmacia, $farmacias["guardias"][$i]["id"]);
        $tpl_info_farmacia->assign(farmacia_direccion, $farmacias["guardias"][$i]["direccion"]);
        $tpl_info_farmacia->assign(farmacia_titular, $farmacias["guardias"][$i]["nombre"]);
        $tpl_info_farmacia->assign(qr, $id_centro . "-" . $farmacias["guardias"][$i]["id"] . '.png');
        $tpl_info_farmacia->assign(farmacia_latitud, $farmacias["guardias"][$i]["latitud"]);
        $tpl_info_farmacia->assign(farmacia_longitud, $farmacias["guardias"][$i]["longitud"]);
        $tpl_info_farmacia->assign(farmacia_telefono, $farmacias["guardias"][$i]["telefono"]);


        // Creacion del array para el paso a javascript de farmacias cercanas
        $posicion_farmacias_guardias[$i] = array ( 
            'id' =>  $farmacias["guardias"][$i]["id"],
            'farmacia_latitud' => $farmacias["guardias"][$i]["latitud"],
            'farmacia_longitud' => $farmacias["guardias"][$i]["longitud"]
            );  


        //***********************************
        //Apartado QR code Generacion de la foto Qr Code
        $qr = "http://maps.google.es/maps?saddr=" . $coordenadas_hotel['0']['latitud'] . "," . $coordenadas_hotel['0']['longitud'] . "&daddr=" . $farmacias["guardias"][$i]["latitud"] . "," . $farmacias["guardias"][$i]["longitud"];
        QRcode::png($qr, '../../../contenido_proyectos/pacoche/centro_' . $id_centro . '/farmacias_qr/' . $farmacias["guardias"][$i]["id"] . '.png', 'L', 5); //generacion de k QR
          

    }


    // Pasar a json los arrays de farmacias cercanas y guardias
    $posicion_farmacias_cercanas = json_encode($posicion_farmacias_cercanas);
    $posicion_farmacias_guardias = json_encode($posicion_farmacias_guardias);


    // Se asigna el json con todas las farmacias al DOM para js
    $tpl_info_farmacia->assignGlobal(JSON_posicion_farmacia_cercanas, $posicion_farmacias_cercanas);
    $tpl_info_farmacia->assignGlobal(JSON_posicion_farmacia_guardias, $posicion_farmacias_guardias);


    $tpl_info_farmacia->assignGlobal("qr_mensaje", LANG_GLOBAL_QRMSG);
    $tpl_info_farmacia->assignGlobal("centro_id", $id_centro);

     //**************************************************************************** Aqui debe ir el id_lugar (session o config)

    // Asignamos a "PELO" la carpeta donde se encuentran las imágenes de las farmacias (debido a los requerimientos impuestos)

    $tpl_info_farmacia->assignGlobal("centro_cod_prov", $id_lugar);


    $tpl_info_farmacia->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);

    $imagen = "farmacias.jpg";

    $resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;
    $resultado['datos'] = $tpl_info_farmacia->getOutputContent();

    echo json_encode($resultado);

} else {
    $coordenadas_hotel = coordenadas_hotel($id_centro);

    $tpl_info_cajero = new TemplatePower("plantillas/seccion_farmacias1.html", T_BYFILE);
    $tpl_info_cajero->prepare();

    $tpl_info_cajero->assign(hotel_latitud, $coordenadas_hotel['0']['latitud']);
    $tpl_info_cajero->assign(hotel_longitud, $coordenadas_hotel['0']['longitud']);

    $tpl_info_cajero->assign("cajeros_title", LANG_INFO_FARMACIAS_TITLE);
    $tpl_info_cajero->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

    //En el caso de que sea el cabo san lucas
    if($id_centro == '248' ){
        $tpl_info_cajero-> assign('leyenda_farmacia',LANG_INFO_FARMACIAS_SANLUCAS_LEYENDA);
    }else{
        $tpl_info_cajero-> assign('a_comentario','<!--');
        $tpl_info_cajero-> assign('c_comentario','-->');
    }



//registrarLog("informacion", "cajeros");

    $tpl_info_cajero->assign("lang_farmacias_text", LANG_INFO_FARMACIAS_TITLE);
    $tpl_info_cajero->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);
    $tpl_info_cajero->assignGlobal("qr_mensaje", LANG_GLOBAL_QRMSG);

//    $resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;


    $resultado['datos'] = $tpl_info_cajero->getOutputContent();

    echo json_encode($resultado);

}


?>