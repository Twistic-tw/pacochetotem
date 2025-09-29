<?php
//Comentario

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

if (!(preguntar_tiempo_hoy()))
{

    if (!testInternet())
    {

            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            exit();

    }

}



$array_localidades_frias = array('2510558', '2517717', '2511150', '2511202');
$array_localidades_calientes = array('2513798', '6354969');


$array_lugares_mexico = array(10,11,12,13,14,15,16,17,18,19,20,23,24,30);
$array_lugares_mauricio = array(38);

$idioma_actual=$_SESSION['idioma'];
$id_localidad = $_SESSION['id_localidad'];
$id_centro = $_SESSION['id_centro'];
$id_lugar =  $_SESSION['id_lugar'];

//pedir la localidad por base de datos
$codLocalidad = (isset($_GET['municipio']) && is_numeric($_GET['municipio'])) ? $_GET['municipio'] : $id_localidad;
header('Access-Control-Allow-Origin: *');


registrarLog("meteo", $codLocalidad, false);


$idioma = $_SESSION['idioma'];

$url = URL_TOTEMPATH;
$url = URL_SERVER;

$url_api = "http://apiservicios.twisticdigital.com";


//echo "inicio: "; echo time();

//echo " / ";


if (!actualizado()) {
    addtime('clean');

    $resultados_json = file_get_contents($url_api."/meteo/getinfo/".ciudades_tiempo());

   // echo " / get: "; echo time();
    if  (isJson($resultados_json))
    {
        $resultados =  json_decode($resultados_json, true);
       // echo "decode OK ";
       // echo " / decode: "; echo time();
       //-------------
        $consulta = "INSERT INTO `localidades_prevision` (`id`, `id_localidad`, `localidad_name`, `fecha`, `json_basico`, `json_total`) VALUES ";

        foreach ($resultados as $value) {
            $consulta.= "('".implode("','", $value)."'),";
        }
        $consulta= substr_replace ($consulta, ";" , -1, 1);
        addtime( $consulta);
        //-------------
    }
    else
        exit();

}
//echo " / final: "; echo time();

//echo time();


function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}


//Procedo a comprobar localmente si tengo los datos actualizados. Considero que un dato esta actualizado si la fecha es de hace menos de 4horas.
//if ( !tiempo_verificar_actualizado($codLocalidad) )
//{
////	el tiempo  NO esta actualizado, debo obtener los datos de la api directamente.
//	$datos = file_get_contents($url_api . "/tiempo/$codLocalidad");
//
//	$datos_array = json_decode($datos, 1);
//	if ( $datos == "" || !$datos_array)
//	{
//		//error, no se pudo obtener los datos o estos no son un json.
//		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
//    	exit();
//    }
//    $datos_array = tiempo_obtener_datos_actualizados($codLocalidad);
//    $datos_array = get_tiempo();
//    echo "<pre>";
//    print_r(json_decode($datos_array[0]['json_basico']));
//    exit();
//    $tiempo['extra'] = $datos_array;
//    $tiempo['fuente'] = "local";
//
//}
//else
//{
//	$datos_array = tiempo_obtener_datos_actualizados($codLocalidad);
//    $datos_array = get_tiempo();
//    echo "<pre>";
//    print_r(json_decode($datos_array['json_basico']));
//    exit();
//    $tiempo['extra'] = $datos_array;
//    $tiempo['fuente'] = "local";
//}
$datos_array = get_tiempo($codLocalidad);

if ($datos_array==null )
{
    $localidades = ciudades_tiempo_aux();

    foreach ($localidades as $localidad)
    {
        $datos_array=get_tiempo($localidad);
        if ($datos_array !=null) break;
    }
}


$tiempo['extra'] = $datos_array;
$tiempo['fuente'] = "local";
//echo "<pre>";
//print_r($datos_array);
//exit();

//foreach ($datos_array as $i => $value) {
//	$tiempo[ $value['dia'] ] = $value;
//	$tiempo[ $value['dia'] ]['iconos'] = totem_getIconoFromClimateCode($value, 1);
//    $tiempo['localidad'] = $value['nombre_local'];
//}

$tiempo['titulo'] = "Temperaturas";


$tpl_meteoHtml = new TemplatePower("plantillas/tiempo-maqueta.html", T_BYFILE);
$tpl_meteoHtml->prepare();


$nombre_municipio = nombre_municipio($codLocalidad);

$tpl_meteoHtml->assign('municipio_lugar',$nombre_municipio['nombre']);

//Esto es para generar un error a posta para maquetar cuando no vienen valores
 //unset($tiempo);
/////////////////////////////////////////////////////////////////////

//Hoy
$fecha = getFechaActual_no_year();
$fecha2 = getFecha2_no_year();
$fecha3 = getFecha3_no_year();

$tpl_meteoHtml->assign('fecha', $fecha );

$tpl_meteoHtml->assign('fecha2', $fecha2 );

$tpl_meteoHtml->assign('fecha3', $fecha3 );

$tpl_meteoHtml->assign('dia_hoy', ucfirst(getFechaActual_no_year(true)));
$tpl_meteoHtml->assign('dia_mñn', ucfirst(getFecha2_no_year(true)));
$tpl_meteoHtml->assign('dia_pasado', ucfirst(getFecha3_no_year(true)));


//Carpeta de los iconos y el fondo
$url_icono = "../../../contenido_proyectos/vistaflor/_general/tiempo/icono/";
$url_fondo = "../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/";


////Hora actual
$hora_actual = date("H");

 $tpl_meteoHtml->assign('prueba',$hora_actual);





//DE 3 a 6
if ( ($hora_actual <= '12') && ($hora_actual > '06') ){
    // $vactual_temp = 'temp_06';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $tiempos = json_decode($datos_array[1]['json_basico']);
    }
    else $tiempos = json_decode($datos_array[0]['json_basico']);

    $actual_icono = $tiempos->icono;
}
//DE 12 a 18
if ( ($hora_actual < '21') && ($hora_actual > '12') ){
    // $vactual_temp = 'temp_12';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $tiempos = json_decode($datos_array[2]['json_basico']);
    }
    else $tiempos = json_decode($datos_array[1]['json_basico']);

    // Tardes de noche en mauricio
    if (in_array($id_lugar,$array_lugares_mauricio)) $actual_icono = str_replace("_noche","", $tiempos->icono);
    else $actual_icono = $tiempos->icono;

}

//de 18  a 13
if ( (($hora_actual <= '24') && ($hora_actual >= '21')) ||  ($hora_actual <= '06')){
   // $vactual_temp = 'temp_18';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $tiempos = json_decode($datos_array[0]['json_basico']);
    }
    else $tiempos = json_decode($datos_array[2]['json_basico']);

    $actual_icono = $tiempos->icono;
}





$tpl_meteoHtml->assign('viento_dir',viento_dirección($tiempos->direccion));
$tpl_meteoHtml->assign('viento_class',viento_dirección($tiempos->direccion)."_".$idioma);
$tpl_meteoHtml->assign('viento_vel',$tiempos->viento);
$tpl_meteoHtml->assign('humedad',$tiempos->humedad);



////de 18 a 3
//if ( (($hora_actual <= '24') && ($hora_actual > '18')) ||  ($hora_actual <= '03')){
//    // $vactual_temp = 'temp_24';
//    $tpl_meteoHtml->assign('viento_dir',$tiempo['hoy']['viento_dir_18_24']);
//    $tpl_meteoHtml->assign('viento_vel',$tiempo['hoy']['viento_vel_18_24']);
//    $tpl_meteoHtml->assign('humedad',$tiempo['hoy']['hum_24']);
//
//    $actual_icono = $tiempo['tomorrow']['iconos']['0'];
//
//}
$nombre_localidad = $tiempo['extra'][0]['nombre'];
$tpl_meteoHtml->assign('municipio',$nombre_localidad);

$localidad = $tiempo['extra'][0]['id_localidad'];

//Localidad



if ($tiempos->temperatura_maxima != ''){

        if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('hoy_maxima',floor($tiempos->temperatura_maxima-2)."ºC");
        else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('hoy_maxima',ceil($tiempos->temperatura_maxima +1)."ºC");
        else $tpl_meteoHtml->assign('hoy_maxima',floor($tiempos->temperatura_maxima)."ºC");

}else{
    $tpl_meteoHtml->assign('hoy_maxima', '-');
}


if ($tiempos->temperatura_minima != ''){

    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('hoy_maxima',floor($tiempos->temperatura_minima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('hoy_minima',ceil($tiempos->temperatura_minima +1)."ºC");
    else $tpl_meteoHtml->assign('hoy_minima',floor($tiempos->temperatura_minima)."ºC");

}else{
    $tpl_meteoHtml->assign('hoy_minima', '-');
}


//Icono de hoy en la hora actual

if($actual_icono == '')
    {
        $actual_icono = 'despejado_error.svg';
        $tpl_meteoHtml->assign('displayNone','displayNone');
        $tpl_meteoHtml->assign('error_conexion_texto','error_conexion_texto');
    }

$tpl_meteoHtml->assign('hoy_icono', $url_icono . $actual_icono);

//Imagen de fondo de hoy en la hora actual
$fondo = $url_fondo . substr($actual_icono, 0, -3) . "jpg";

//$fondo = '../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/nublado_parcial_noche.jpg' ;

$tpl_meteoHtml->assign('imagen-fondo',$fondo);


//Dependiendo del icono, muestro la frase concreta
$nombre_icono = substr($actual_icono, 0, -4);

switch ($nombre_icono) {

    case 'despejado':
    case 'despejado_noche':
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_DESPEJADO);
        break;

    case 'nublado_parcial':
    case 'nublado_parcial_noche' :
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_PARCIAL_NUBLADO);
        break;

    case 'nublado':
    case 'nublado_noche':
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_NUBLADO);
        break;

    case 'tormenta':
    case 'tormenta_noche':
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_TORMENTA);
        break;

    case 'lluvioso':
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_LLUVIA);
        break;

    case 'lluvioso_nevar':
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_NIEVE);
        break;
    default:
        $tpl_meteoHtml->assign('descripcion_tiempo',LANG_TIEMPO_ERROR);
        break;

}

///////////////////////////////////////////FIn de ahora

//////////////////////////////////////////////////////////////////////////////
//Valores de hoy por la mañana
if (in_array($id_lugar,$array_lugares_mexico))
{
    $mañana = json_decode($datos_array[1]['json_basico']);
    $tarde = json_decode($datos_array[2]['json_basico']);
    $noche = json_decode($datos_array[0]['json_basico']);

}

else {
    $mañana = json_decode($datos_array[0]['json_basico']);
    $tarde = json_decode($datos_array[1]['json_basico']);
    $noche = json_decode($datos_array[2]['json_basico']);
}


$tpl_meteoHtml->assign('titulo_vertical_12',LANG_GLOBAL_DIA_MAÑANA);

if ($mañana->temperatura_maxima != ''){
    //Es en New York y esta en ingles, por lo que los grados son en Farenheit
    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('hoy12_maxima',floor($mañana->temperatura_minima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('hoy12_maxima',ceil($mañana->temperatura_minima +1)."ºC");
    else $tpl_meteoHtml->assign('hoy12_maxima',floor($mañana->temperatura_minima)."ºC");


}

if ($mañana->icono == '')   $mañana->icono  = 'despejado_error.svg';

$tpl_meteoHtml->assign('icono12', $url_icono . $mañana->icono);
/////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
//Valores de hoy por la tarde
$tpl_meteoHtml->assign('titulo_vertical_18',LANG_GLOBAL_DIA_TARDE);

if ($tarde->temperatura_maxima != ''){
    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('hoy18_maxima',floor($mañana->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('hoy18_maxima',ceil($mañana->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('hoy18_maxima',floor($mañana->temperatura_maxima)."ºC");


}

if ($tarde->icono == '')   $tarde->icono  = 'despejado_error.svg';


// Tardes de noche en mauricio
if (in_array($id_lugar,$array_lugares_mauricio)) $icono_final = str_replace("_noche","", $tarde->icono);
else $icono_final = $tarde->icono;

$tpl_meteoHtml->assign('icono18', $url_icono . $icono_final);
/////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
//Valores de hoy por la noche
$tpl_meteoHtml->assign('titulo_vertical_24',LANG_GLOBAL_DIA_NOCHE);

if ($noche->temperatura_maxima != ''){

    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('hoy24_maxima',floor($noche->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('hoy24_maxima',ceil($noche->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('hoy24_maxima',floor($noche->temperatura_maxima)."ºC");

}

if ($noche->icono == '')   $noche->icono  = 'despejado_noche.svg';

$tpl_meteoHtml->assign('icono24', $url_icono . $noche->icono);
/////////////////////////////////////////////////////////////////////////////

//Selector de mierda de los dias siguientas
$tpl_meteoHtml->assign('hoy_day', strftime("%A", strtotime('today') ) );

//////////////////////////////////////////////////////////////////////////////
//Valores de tomorrow

if (in_array($id_lugar,$array_lugares_mexico))
{
    $dia1 = json_decode($datos_array[4]['json_basico']);
    $dia1t = json_decode($datos_array[5]['json_basico']);
    $dia1n = json_decode($datos_array[3]['json_basico']);

}

else {
    $dia1 = json_decode($datos_array[3]['json_basico']);
    $dia1t = json_decode($datos_array[4]['json_basico']);
    $dia1n = json_decode($datos_array[5]['json_basico']);
}




$tpl_meteoHtml->assign('tomorrow_day', strftime("%A", strtotime('+ 1day') ) );



if ($dia1->temperatura_maxima != ''){

    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('tomorrow_maxima',floor($dia1->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('tomorrow_maxima',ceil($dia1->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('tomorrow_maxima',floor($dia1->temperatura_maxima)."ºC");

}else{
    $tpl_meteoHtml->assign('tomorrow_maxima', '-');
}

if ($dia1->temperatura_minima != ''){

    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('tomorrow_minima',floor($dia1->temperatura_minima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('tomorrow_minima',ceil($dia1->temperatura_minima +1)."ºC");
    else $tpl_meteoHtml->assign('tomorrow_minima',floor($dia1->temperatura_minima)."ºC");

}else{
    $tpl_meteoHtml->assign('tomorrow_minima', '-');
}

if ($dia1->icono == '')    $dia1->icono  = 'despejado.svg';


$tpl_meteoHtml->assign('tomorrow_icono', $url_icono . $dia1->icono);

$tpl_meteoHtml->assign('tomorrow_humedad', $dia1->humedad);
$tpl_meteoHtml->assign('tomorrow_viento_vel', $dia1->viento);
$tpl_meteoHtml->assign('tomorrow_viento_dir', viento_dirección($dia1->direccion));
$tpl_meteoHtml->assign('tomorrow_viento_class', viento_dirección($dia1->direccion)."_$idioma");

//$tpl_meteoHtml->assign('tomorrow12_maxima', floor($dia1->temperatura_maxima));

if ($dia1->temperatura_maxima != ''){


    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('tomorrow12_maxima',floor($dia1->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('tomorrow12_maxima',ceil($dia1->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('tomorrow12_maxima',floor($dia1->temperatura_maxima)."ºC");

}

if ($dia1t->temperatura_maxima != ''){
    //Es en New York y esta en ingles, por lo que los grados son en Farenheit
    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('tomorrow18_maxima',floor($dia1->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('tomorrow18_maxima',ceil($dia1->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('tomorrow18_maxima',floor($dia1->temperatura_maxima)."ºC");


}

if ($dia1n->temperatura_maxima != ''){


    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('tomorrow24_maxima',floor($dia1->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('tomorrow24_maxima',ceil($dia1->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('tomorrow24_maxima',floor($dia1->temperatura_maxima)."ºC");



}

$tpl_meteoHtml->assign('tomorrowicono12', $url_icono . $dia1->icono);

if ($dia1t->icono == '')   $dia1t->icono  = 'despejado.svg';

// Tardes de noche en mauricio
if (in_array($id_lugar,$array_lugares_mauricio)) $icono_final = str_replace("_noche","", $tarde->icono);
$tpl_meteoHtml->assign('tomorrowicono18', $url_icono .  $icono_final);
//aaaaaaaaaaaaaaaaaa////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($dia1n->icono == '')   $dia1n->icono  = 'despejado_noche.svg';

$tpl_meteoHtml->assign('tomorrowicono24', $url_icono .  $dia1n->icono);

//////////////////////////////////////////////////////////////////////////////////////////////////////////

$fondo1 = $url_fondo . substr($dia1->icono, 0, -3) . "jpg";
//$fondo1 = '../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/soleado.jpg' ;
$tpl_meteoHtml->assign('imagen-fondo1',$fondo1);
/////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
//Valores de pasado
$tpl_meteoHtml->assign('pasado_day', strftime("%A", strtotime('+ 2day') ) );
$pasado = json_decode($datos_array[6]['json_basico']);

if ($pasado->temperatura_maxima != '') {

    if (in_array($localidad, $array_localidades_frias)) $tpl_meteoHtml->assign('pasado_maxima',floor($dia1->temperatura_maxima-2)."ºC");
    else if (in_array($localidad, $array_localidades_calientes)) $tpl_meteoHtml->assign('pasado_maxima',ceil($dia1->temperatura_maxima +1)."ºC");
    else $tpl_meteoHtml->assign('pasado_maxima',floor($dia1->temperatura_maxima)."ºC");

}else{
    $tpl_meteoHtml->assign('pasado_maxima', '-');
}

/*if ($pasado->temperatura_minina != '') {
    //Es en New York y esta en ingles, por lo que los grados son en Farenheit
    if (($id_centro=='270' || $id_centro=='256') && ( $idioma_actual='1' || $idioma_actual='2' )){

        $tpl_meteoHtml->assign("pasado_minima", ( floor($pasado->temperatura_minima) * 1.8) + 32 ."ºF");

    }else{
        $tpl_meteoHtml->assign('pasado_minima',floor($pasado->temperatura_minima)."ºC");
    }
}else{
   // $tpl_meteoHtml->assign('pasado_minima', '-');
}*/

if ($pasado->icono == '')    $pasado->icono  = 'despejado.svg';

// Si está en américa se ajusta el día de pasado para que no salga de noche
if (in_array($id_lugar,$array_lugares_mexico))
{

$icono_final = str_replace("_noche","", $pasado->icono);

$tpl_meteoHtml->assign('pasado_icono', $url_icono . $icono_final);

$fondo_final =   str_replace("_noche","", substr($pasado->icono, 0, -2));

$fondo2 = $url_fondo .  $fondo_final  . "jpg";

}
else
{
    $tpl_meteoHtml->assign('pasado_icono', $url_icono .  $pasado->icono);
    $fondo2 = $url_fondo .  substr($pasado->icono, 0, -3)  . "jpg";

}


$tpl_meteoHtml->assign('imagen-fondo2',$fondo2);

/////////////////////////////////////////////////////////////////////////////


//Texto de seleccionar el municipio
$tpl_meteoHtml->assign('selector',LANG_TIEMPO_SELECTOR);

$tpl_meteoHtml->assign( 'cerrar', LANG_GLOBAL_ATRAS );


$tpl_meteoHtml->assign( 'ver_nombre', LANG_VERNOMBRE );
$tpl_meteoHtml->assign( 'ver_zona', LANG_VERZONA );



$tpl_meteoHtml->assign( 'img_sitio_hotel', '../../../contenido_proyectos/vistaflor/centro_'.$id_centro.'/logos/lugar_hotel.png' );

/////////////////todos los municipios

if (in_array($id_lugar,$array_lugares_mexico))
{
    $nuevomunicipios = get_tiempo_mex(ciudades_tiempo());
}
else $nuevomunicipios = get_tiempo(ciudades_tiempo());
//echo "<pre>";
//print_r($nuevomunicipios);
//die();
//$municipios = conoce_listado_municipios();


//Tengo que ir iterando de dos en dos
$i = 0;

foreach ($nuevomunicipios as $municipio) {
    if ($i == 0 ){
      $tpl_meteoHtml->newBlock("listado_municipios");  
    }

    $tpl_meteoHtml->newBlock("b$i");

    // Para poner el nombre que aparece en localidades aemet y no el de openweather
    $nombre_municipio = nombre_municipio($municipio['id_localidad']);
    $tpl_meteoHtml->assign( "nombre_municipio",$nombre_municipio['nombre']);

//    $tpl_meteoHtml->assign( "nombre_municipio", $municipio['localidad_name']);
    $tpl_meteoHtml->assign( "id_municipio", $municipio['id_localidad']);
    $json = json_decode($municipio['json_basico']);


    $icono_final_municipio =  $url_icono.$json->icono;

    if (in_array($id_lugar,$array_lugares_mauricio))
    {
            // tardes de Le morne fix
            if ( ($hora_actual < '21') && ($hora_actual > '12') )
            {
                $icono_final_municipio = str_replace("_noche","", $url_icono.$json->icono);
            }
    }

    $tpl_meteoHtml->assign( "icono_lugar", $icono_final_municipio);

    if (in_array($municipio['id_localidad'], $array_localidades_frias)) $tpl_meteoHtml->assign('temp_lugar',floor($json->temperatura-2)."ºC");
    else if (in_array($municipio['id_localidad'], $array_localidades_calientes)) $tpl_meteoHtml->assign('temp_lugar',ceil($dia1->temperatura +1)."ºC");
    else $tpl_meteoHtml->assign('temp_lugar',floor($json->temperatura)."ºC");

    if ($municipio['id_localidad'] == $codLocalidad)
    {
        $tpl_meteoHtml->assign('selected', 'selected');
    }
    $i ++;
    $i = $i%2;
}

/////////////////////////////////////CERRAR


$tiempo["meteoHTML"] = $tpl_meteoHtml->getOutputContent();

//$tiempo["meteoHTML"] = utf8_encode($tiempo["meteoHTML"]);

echo ( json_encode($tiempo, TRUE) );

?>

