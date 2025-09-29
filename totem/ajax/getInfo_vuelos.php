<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/


if (!testInternet()) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}

registrarLog("informacion", "vuelos");

$id_centro = $_SESSION['id_centro'];
$imagen = "aeropuerto.jpg";
$idioma = $_SESSION['idioma'];

$url_imagenes = $_SESSION["url_comun"] . 'vuelos/logos/';


$url_api = "http://apiservicios.twisticdigital.com";

$datos_vuelos  = array();

if ( vuelos_datos_actualizados($id_centro) )
{
    $datos['fuente'] = "local";
    //generamos nosotros mismos la estructura.
    $datos_vuelos = vuelos_get_datos_locales($id_centro);

    $datos['extra'] = $datos_vuelos;

}
else
{
    //si no estan actualizados los actualizo desde api.
    $datos_vuelos_json = file_get_contents($url_api."/vuelos/$id_centro");
    $datos_vuelos = vuelos_get_datos_locales($id_centro);
}


foreach ($datos_vuelos as $aeropuerto_codigo => $datos_tipo_vuelo) {
    $datos = get_nombre_aeropuerto($aeropuerto_codigo);

    if(($datos['cod'] == "LPA" && $_SESSION['id_lugar'] != 47) || $datos['nombre'] == ""){
        $nombre_aero = LANG_INFO_VUELOS_TITLE;
    }else{
        $nombre_aero = $datos['nombre'];
    }
}


$datos['extra'] = $datos_vuelos;

$tplVuelos = new TemplatePower('plantillas/seccion_vuelos.html', T_BYFILE);
$tplVuelos->prepare();

$tplVuelos->assign("vuelos_title", LANG_INFO_VUELOS_TITLE);
$tplVuelos->assign("lang_llegadasText", LANG_VUELOS_LLEGADASTEXT);
$tplVuelos->assign("lang_salidasText", LANG_VUELOS_SALIDASTEXT);
$tplVuelos->assign("lang_hora", LANG_VUELOS_HORA);
$tplVuelos->assign("lang_vuelo", LANG_VUELOS_VUELO);
$tplVuelos->assign("lang_destino", LANG_VUELOS_DESTINO);
$tplVuelos->assign("lang_origen", LANG_VUELOS_ORIGEN);
$tplVuelos->assign("lang_compañía", LANG_VUELOS_COMPAÑIA);
$tplVuelos->assign("lang_estado", LANG_VUELOS_ESTADO);
$tplVuelos->assign("lang_atras", LANG_GLOBAL_ATRAS);

$tplVuelos->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

//aqui toca rellenar el bloque de tpl.
foreach ($datos_vuelos as $aeropuerto_codigo => $datos_tipo_vuelo) {
    //bloque que recorre los varios aeropuertos si hubiera.

    $tplVuelos->assign("vuelos_title", $nombre_aero);

    foreach ($datos_tipo_vuelo as $tipo_vuelo => $datos_especificos_vuelo) {
        //bloque con los datos_especificos_vuelo siendo un array secuencial con indices apuntando a arrays con dichos datos.

        foreach ($datos_especificos_vuelo as $vuelo_info) {
            if ($tipo_vuelo == "salida")
            {
                $tplVuelos->newBlock("salidas");
            }
            elseif ($tipo_vuelo == "llegada")
            {
                $tplVuelos->newBlock("llegadas");
            }
            else
            {
                continue;
            }
            //echo json_encode($vuelo_info);exit;

            $tplVuelos->assign('hora', substr($vuelo_info['hora'], 0, -3) );
            $tplVuelos->assign('vuelo', $vuelo_info["vuelo"]);
            $tplVuelos->assign('trayecto', $vuelo_info["origen_destino"]);
            $tplVuelos->assign('company', $vuelo_info["company"]);

            //Por si existe alguna compañia con 3 letras en el nombre
            if ( is_numeric (substr($vuelo_info["vuelo"],'3','1') ) ) {

                $nombre_company = substr($vuelo_info["vuelo"],'0','2');
            }else{
                $nombre_company = substr($vuelo_info["vuelo"],'0','3');
            }

            $nombre_company = strtolower($nombre_company);

            $tplVuelos->assign('imagen', $url_imagenes . $nombre_company .'-logo.svg');


            switch ($idioma) {

                case '2':

                    $estado = $vuelo_info["estado"];
                    if ( strpos($estado,'Retrasado') !== FALSE ) { $estado = str_replace('Retrasado', 'Delayed' , $vuelo_info["estado"]); }

                    if ($vuelo_info["estado"] == 'Programado'){ $estado = 'Scheduled'; }
                    if ($vuelo_info["estado"] == 'En tierra'){ $estado = 'Landing'; }
                    if ($vuelo_info["estado"] == 'Cancelado'){ $estado = 'Canceled'; }
                    if ($vuelo_info["estado"] == 'En hora'){ $estado = 'On time'; }

                    if ($vuelo_info["estado"] == 'Desviado'){ $estado = 'Deflected'; }
                    if ($vuelo_info["estado"] == 'Pendiente de información'){ $estado = 'Not info'; }
                    if ($vuelo_info["estado"] == 'No operativo'){ $estado = 'inoperative'; }
                    if ($vuelo_info["estado"] == 'Redireccionado'){ $estado = 'Redirected'; }
                    if ($vuelo_info["estado"] == 'Desconocido'){ $estado = 'Unknown'; }

                    break;

                case '3':

                    $estado = $vuelo_info["estado"];
                    if ( strpos($estado,'Retrasado') !== FALSE ) { $estado = str_replace('Retrasado', 'Verspätet' , $vuelo_info["estado"]); }

                    if ($vuelo_info["estado"] == 'Programado'){ $estado = 'Geplant'; }
                    if ($vuelo_info["estado"] == 'En tierra'){ $estado = 'Dem Land'; }
                    if ($vuelo_info["estado"] == 'Cancelado'){ $estado = 'Annulliert'; }
                    if ($vuelo_info["estado"] == 'En hora'){ $estado = 'Pünktlich'; }

                    if ($vuelo_info["estado"] == 'Desviado'){ $estado = 'Abgelenkt'; }
                    if ($vuelo_info["estado"] == 'Pendiente de información'){ $estado = 'Keine Info'; }
                    if ($vuelo_info["estado"] == 'No operativo'){ $estado = 'Unwirksam'; }
                    if ($vuelo_info["estado"] == 'Redireccionado'){ $estado = 'Weitergeleitet'; }
                    if ($vuelo_info["estado"] == 'Desconocido'){ $estado = 'Unbekannt'; }

                    break;

                default:
                    $estado = $vuelo_info["estado"];
                    break;
            }

            $tplVuelos->assign('estado', $estado);
        }
    }

    break;
    //solo doy soporte a 1 aeropuerto actualmente, no ta la interfaz dsieñada para varios.
}

$datos['datos'] = $tplVuelos->getOutputContent();
$datos['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;

echo json_encode($datos);

?>