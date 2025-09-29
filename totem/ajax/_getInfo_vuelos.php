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


$url_api = "http://apiservicios.twisticdigital.com";
$datos_vuelos  = array();

if ( vuelos_datos_actualizados($id_centro) )
{
	$datos['fuente'] = "local";
	//generamos nosotros mismos la estructura.
	$datos_vuelos = vuelos_get_datos_locales($id_centro);

	$datos['extra'] = $datos_vuelos;
/*
	echo json_encode($datos);
	exit;*/
	
}
else
{
	//si no estan actualizados los actualizo desde api.
	$datos_vuelos_json = file_get_contents($url_api."/vuelos/$id_centro");	
	$datos_vuelos = json_decode($datos_vuelos_json,1);

	$datos['fuente'] = $url_api."/vuelos/$id_centro";
	$datos['remoto'] = $datos_vuelos;

/*	echo json_encode($datos);
	exit;*/
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

	        switch ($idioma) {

	        	case '2':
	        		if ($vuelo_info["estado"] == 'Activo'){ $estado = 'Active'; }
	        		if ($vuelo_info["estado"] == 'Programado'){ $estado = 'Scheduled'; }
	        		if ($vuelo_info["estado"] == 'En tierra'){ $estado = 'Landing'; }
	        		if ($vuelo_info["estado"] == 'Cancelado'){ $estado = 'Canceled'; }
	        		if ($vuelo_info["estado"] == 'Retrasado'){ $estado = 'Delayed'; }

	        		break;

	        	case '3':
	        		if ($vuelo_info["estado"] == 'Activo'){ $estado = 'Active'; }
	        		if ($vuelo_info["estado"] == 'Programado'){ $estado = 'Geplant'; }
	        		if ($vuelo_info["estado"] == 'En tierra'){ $estado = 'Dem Land'; }
	        		if ($vuelo_info["estado"] == 'Cancelado'){ $estado = 'Annulliert'; }
	        		if ($vuelo_info["estado"] == 'Retrasado'){ $estado = 'Verspätet'; }

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
$datos['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;

echo json_encode($datos);

?>