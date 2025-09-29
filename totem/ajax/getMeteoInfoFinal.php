<?php


if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}

$tiempo = array();

/*******    ID del cliente, se obtendra de la sesion    *******/
$id_centro = $_SESSION['id_centro'];
$datosXMLCorruptos = false;
$actualizar = false;

header('Access-Control-Allow-Origin: *');

/**   ID de la localidad, pasada por queryString  */

///////////////////////////////////////////
//cojo el idioma que me viene por GET
$idioma = $_GET['idi'];
$_SESSION['idioma'] = $idioma;

///////////////////////////Para los textos en varios idiomas
//cargamos el fichero de traduccion segun el idioma especificado en $_SESSION

if ( !isset($_SESSION['idioma']) ) {
    $_SESSION['idioma'] = 1;
}
$lang = $_SESSION['idioma'];
//if ($lang==3)$lang=2;
$arrayIdiomas = array(1=>"es", 2=>"en", 3=>"de", 4=>"pl");

$langFilePath = 'lang/definiciones_' . $arrayIdiomas[$lang] . '.php';

if (is_file($langFilePath))
{
    $tiempo[o] = 1;
    include_once $langFilePath;
}
else 
{
    include_once 'lang/definiciones_es.php';
}


///////////////////////////////////////////////////////////////


///////////////////////////////////////////

$id_localidad = $_SESSION['id_localidad'];

$codLocalidad = is_numeric($_GET['municipio']) ? addslashes($_GET['municipio']) : $id_localidad;
$falsear = false;

//Parche para artenara porque falla la presentacion eñ 10 de Julio
/*if ($codLocalidad == "35019" || $codLocalidad == "'35019'" || $codLocalidad == "35005" ||  $codLocalidad == "'35005'" ){
    
    $localidad_falsa = $codLocalidad;

    $codLocalidad = "35012";
    $falsear = true;
}*/

$db = new MySQL();
//esto nos devuelve un array en formato datetime (necesario para actualizaciones)
$date = date('Y-m-d H:i:s');

//Comprobar los regustros que llevan mas de una semana y borrarlos (el historico sera de una semana tan solo)
$queryD = "DELETE FROM tiempo_actualizaciones 
                WHERE HOUR(TIMEDIFF('$date', act_time)) > 168";
$db->consulta($queryD);

//comprobar si es la primera vez que se conecta y estan vacias las tablas
//$consulta2 = "SELECT * FROM tiempo_actualizaciones";
//
//$rec_con2 = $db->consulta($consulta2);
//$result_consulta2 = $db->fetch_array($rec_con2);


//Comprobar que la ultima consulta para esa loclidad se realizo hace mas de 6 horas, 
// obtengo la ultima actualización para la localidad seleccionada y luego compruebo si
// tiene fecha de hace mas de 6 horas.
//$consulta = "SELECT id_act FROM ( SELECT id_act, act_time FROM tiempo_actualizaciones, localidades_aemet 
//                                    WHERE localidades_aemet.cod_local = '$codLocalidad'
//                                    AND localidades_aemet.id_local = tiempo_actualizaciones.id_loc
//                                    ORDER BY id_act DESC LIMIT 1
//                                ) as t1
//                WHERE HOUR(TIMEDIFF('$date', act_time)) > 6";

$consulta = "SELECT id_act, act_time FROM tiempo_actualizaciones, localidades_aemet 
                WHERE localidades_aemet.cod_local = '$codLocalidad'
                AND localidades_aemet.id_local = tiempo_actualizaciones.id_loc
                ORDER BY id_act DESC LIMIT 1";

$rec_con = $db->consulta($consulta);
$result_consulta = $db->fetch_array($rec_con);
$id_act_ultima = "";

//si no existen datos que satisfagan la consulta ES que NO existen datos para ese municipio
if ( empty($result_consulta) ){
    $actualizar = true;
}
else{
    
    $id_act_ultima = $result_consulta['id_act'];
    //analizo la fecha
    $fechaActualizacion = new DateTime($result_consulta['act_time']);
    $fechaActual = new DateTime($date);
    
    //si la fecha almacenada para la actualizacion es de hace mas de 12 horas (dicha fecha + 6h < fecha actual)
    if ($fechaActualizacion->format("U") + (60*60*6) < $fechaActual->format("U") ){
        $actualizar = true;
    }
    else 
    {
        //ahora obtenemos los datos de la ultima actualizacion para comprobar que no esten vacios
        $query = "SELECT * FROM tiempo
                    WHERE tiempo.id_act = ( SELECT id_act FROM tiempo_actualizaciones, localidades_aemet
                                                WHERE localidades_aemet.cod_local = '$codLocalidad'
                                                AND localidades_aemet.id_local = tiempo_actualizaciones.id_loc
                                                ORDER BY id_act DESC LIMIT 1
                                           )";

        $rec = $db->consulta($query);
        if ($db->num_rows($rec) == 0)
        {
            $query = "DELETE FROM tiempo_actualizaciones WHERE id_act = '$id_act_ultima'";
            $db->consulta($query);
            $actualizar = true;
        };
    }
}

//Fuerzo a actualizar de momento
//$actualizar = true;
    
if ( $actualizar ) {
    //si entra es porque o bien es la primera vez o han pasado mas de 6 horas desde la ultima actualizacion
        
    //Se hace la petición a la appi con los parámetros correspondientes
    //Se debe obtener el centro actual para poder consultar las localidades relacionadas a este
    //Aqui se establece como ejemplo id_centro = 3 para obtener la localidad de ejemplo que será Agaete

/*    $query = "SELECT localidades_aemet.id_local, localidades_aemet.nombre, localidades_aemet.cod_local
                FROM cliente_localidad, localidades_aemet 
                    WHERE cliente_localidad.id_loc = localidades_aemet.id_local
                    AND cliente_localidad.id_centro = '$id_centro'";

    $rec = $db->consulta($query);
    $result = $db->fetch_array($rec);*/

    /// obtengo el id de la localidad asi como los demas datos, para trabajar sobre ellos
    $query = "SELECT * FROM localidades_aemet
                WHERE localidades_aemet.cod_local = '$codLocalidad'";
    $rec = $db->consulta($query);
    $result = $db->fetch_array($rec);

    //Para que la base de datos no crezca demasiado se consultarán los registros que llevan mas de 1 semana y se eliminan
    //realizamos la peticion del archivo xml a la web del aemet http://www.aemet.es/xml/municipios/localidad_{cod_local}.xml

    $str_tiempo = "http://www.aemet.es/xml/municipios/localidad_" . $codLocalidad . ".xml";
    $peticion = file_get_contents($str_tiempo);
    $datos_array = json_decode(json_encode((array) simplexml_load_string($peticion)), 1);

    if ( !$peticion ){
        exit();
    }

    if ( !$datos_array ) {
        print_r ( array("error"=>"Error conectando al servidor de aemet") );
        exit();
    } 
    
    //Analizo el array para ver si hay algun dato incoherente. 
    //  Evaluo la humedad
    //  Evaluo las temperaturas, por si hubiese alguna incogruencia
    foreach ($datos_array['prediccion']['dia'][0]['humedad_relativa']['dato'] as $humedadRelativa) {
        if ( $humedadRelativa>100 )
        {            //ocurrio un error.
            $datosXMLCorruptos = true;
            break;
        }
    }
    foreach ($temperatura = $datos_array['prediccion']['dia'][0]['temperatura']['dato'] as $temperatura) {
        if ( $temperatura > 60 )
        {
            $datosXMLCorruptos = true;
            break;
        }
    }

    if ( !$datosXMLCorruptos )     //solo almaceno la actualizacion si NO hay datos corruptos
    {
        // almacenamos un log de la fecha de la actualizacion
        $id_loc = $result['id_local'];
        $query = "INSERT INTO tiempo_actualizaciones (id_act, act_time, id_loc) VALUES(NULL, '$date', '$id_loc')";
        $rec = $db->consulta($query);

        $query = "SELECT LAST_INSERT_ID() as last_id;";
        $rec = $db->consulta($query);
        $result_act = $db->fetch_assoc($rec);

        $id_act = $result_act['last_id'];



        $nombre_local = $datos_array['nombre'];

        //////////////////////////Predicción hoy será i=0 mañana i=1 pasado i=2///////////////////////////////// 
        //       OBTENEMOS PRIMERO EL ESTADO DEL CIELO, VIENTO, TEMPERATURAS Y HUMEDAD       //
        ///////////////////////////////////////////////////////////////////////////////////////

        for ($i = 0; $i < 3; $i++) {
            $fecha = $datos_array['prediccion']['dia'][$i]['@attributes']['fecha'];

            //direccion del viendo según periodo horario
            $viento = $datos_array['prediccion']['dia'][$i]['viento'];

            $viento_dir_00_06 = is_array( $viento['3']['direccion'] ) ? "NE" : $viento['3']['direccion'];
            $viento_dir_06_12 = is_array( $viento['4']['direccion'] ) ? "NE" : $viento['4']['direccion'];
            $viento_dir_12_18 = is_array( $viento['5']['direccion'] ) ? "NE" : $viento['5']['direccion'];
            $viento_dir_18_24 = is_array( $viento['6']['direccion'] ) ? "NE" : $viento['6']['direccion'];

            //velocidad del viento según periodo horario
            $viento_vel_00_06 = is_array( $viento['3']['velocidad'] ) ? 2 : $viento['3']['velocidad'];
            $viento_vel_06_12 = is_array( $viento['4']['velocidad'] ) ? 5 : $viento['4']['velocidad'];
            $viento_vel_12_18 = is_array( $viento['5']['velocidad'] ) ? 5 : $viento['5']['velocidad'];
            $viento_vel_18_24 = is_array( $viento['6']['velocidad'] ) ? 1 : $viento['6']['velocidad'];

            //temperatura
            $temperatura = $datos_array['prediccion']['dia'][$i]['temperatura'];        
            $temp_max = is_array( $temperatura['maxima'] ) ? 28 : $temperatura['maxima'];
            $temp_min = is_array( $temperatura['minima'] ) ? 20 : $temperatura['minima'];

            $temp_06 = is_array( $temperatura['dato']['0'] ) ? 20 : $temperatura['dato']['0'];
            $temp_12 = is_array( $temperatura['dato']['1'] ) ? 25 : $temperatura['dato']['1'] ;
            $temp_18 = is_array( $temperatura['dato']['2'] ) ? 27 : $temperatura['dato']['2'] ;
            $temp_24 = is_array( $temperatura['dato']['3'] ) ? 20 : $temperatura['dato']['3'] ;

            //humedad
            $humedad = $datos_array['prediccion']['dia'][$i]['humedad_relativa'];
            $hum_max = is_array( $humedad['maxima'] ) ? 85 : $humedad['maxima'];
            $hum_min = is_array( $humedad['minima'] ) ? 60 : $humedad['minima'];

            $hum_06 = is_array( $humedad['dato']['0'] ) ? 75 : $humedad['dato']['0'];
            $hum_12 = is_array( $humedad['dato']['1'] ) ? 60 : $humedad['dato']['1'] ;
            $hum_18 = is_array( $humedad['dato']['2'] ) ? 70 : $humedad['dato']['2'] ;
            $hum_24 = is_array( $humedad['dato']['3'] ) ? 80 : $humedad['dato']['3'] ;


            if ($i == 0) {
                $dia = "hoy";
            } 
            elseif($i == 1) {
                //al servidor no le gusta la ñ de mañana, este campo se puede quitar de la tabla, lo puse por más seguridad y para no andar comprobando en caso de que
                //fuera necesario hacer consultas las fechas del tiempo sino que a partir del id de conexion, distinguir el tiempo de hoy y mañana por ese campo
                $dia = "tomorrow";
            } 
            else {
                $dia = "pasado";
            }

            //cielos
            $cielo = $datos_array['prediccion']['dia'][$i]['estado_cielo'];

            if ($i>1)
            {//si es el dia correspondinete a "pasado" ($i==2) solo obtengo los estados del cielo en las dos franjas de los indices 1 y 2 (00-12 y 12-24)
                //@update cojo los datos del cielo anterior para la franja de 00_06.
                $cielo_00_06 = is_array( $cielo['3'] ) ? "11" : $cielo['1'];
//                $cielo_00_06 = $cielo_18_24;
                $cielo_06_12 = is_array( $cielo['4'] ) ? "11" : $cielo['1'];
                $cielo_12_18 = is_array( $cielo['5'] ) ? "11" : $cielo['2'];
                $cielo_18_24 = is_array( $cielo['6'] ) ? "11" : $cielo['2'];
                
            }
            else 
            {   
                $cielo_00_06 = is_array( $cielo['3'] ) ? "11n" : $cielo['3'];       //11 es despejado. 11n despejado noche
                $cielo_06_12 = is_array( $cielo['4'] ) ? "11" : $cielo['4'];
                $cielo_12_18 = is_array( $cielo['5'] ) ? "11" : $cielo['5'];
                $cielo_18_24 = is_array( $cielo['6'] ) ? "11" : $cielo['6'];
            }


            $query = "INSERT INTO tiempo (id_tiempo, id_act, nombre_local, fecha, dia, cielo_00_06, 
                      cielo_06_12, cielo_12_18, cielo_18_24, viento_vel_00_06, viento_vel_06_12, 
                      viento_vel_12_18, viento_vel_18_24, viento_dir_00_06, viento_dir_06_12, viento_dir_12_18, 
                      viento_dir_18_24, temp_max, temp_min, temp_06, temp_12, temp_18, temp_24, hum_max, hum_min,
                      hum_06, hum_12, hum_18, hum_24, json_total) 
                      VALUES(NULL, '$id_act', '$nombre_local', '$fecha', '$dia', '$cielo_00_06', '$cielo_06_12', '$cielo_12_18', '$cielo_18_24', '$viento_vel_00_06', 
                      '$viento_vel_06_12', '$viento_vel_12_18', '$viento_vel_18_24', '$viento_dir_00_06', '$viento_dir_06_12', '$viento_dir_12_18', 
                      '$viento_dir_18_24', '$temp_max', '$temp_min', '$temp_06', '$temp_12', '$temp_18', '$temp_24', '$hum_max', '$hum_min', '$hum_06', 
                      '$hum_12', '$hum_18', '$hum_24', '" . json_encode($datos_array) . "')";
            // echo $query;

            $rec = $db->consulta($query);
        }
    }
}

//ahora obtenemos los datos de la ultima actualizacion
$query = "SELECT * FROM tiempo
            WHERE tiempo.id_act = ( SELECT id_act FROM tiempo_actualizaciones, localidades_aemet
                                        WHERE localidades_aemet.cod_local = '$codLocalidad'
                                        AND localidades_aemet.id_local = tiempo_actualizaciones.id_loc
                                        ORDER BY id_act DESC LIMIT 1
                                   )";
$rec = $db->consulta($query);

//$result = mysql_fetch_assoc($rec);


if ( !$datosXMLCorruptos )
{
    while ($result = $db->fetch_assoc($rec)) {
        $tiempo[ $result['dia'] ] = $result;
        $tiempo[ $result['dia'] ]['iconos'] = totem_getIconoFromClimateCode($result, 1);
        $tiempo['localidad'] = $result['nombre_local'];
    }
    //Parche para artenara porque falla la presentacion eñ 10 de Julio
    if ($falsear) {
        if ( $localidad_falsa == "35005" ||  $localidad_falsa == "'35005'" ) $tiempo['localidad'] = "Artenara";
        else $tiempo['localidad'] = "San Bartolomé de Tirajana";
    }
}
else 
{
    //si hubieron datos corruptos en el xml debo "falsear" los datos, el query devuelve los datos con la ultima
    //actualización de ayer.
    // 1º Descarto los datos del primer indice del fetch_assoc (son los del dia de "ayer"
    $db->fetch_assoc($rec);
    
    //2º genero los resultados de "hoy" (utilziando el segundo registro del array) y de "mañana" (utilizando los registros de pasado mañana).
    $result = $db->fetch_assoc($rec);
    $tiempo[ "hoy" ] = $result;
    $tiempo[ "hoy" ]['iconos'] = totem_getIconoFromClimateCode($result);
        
    $result = $db->fetch_assoc($rec);
    $tiempo[ "tomorrow" ] = $result;
    $tiempo[ "tomorrow" ]['iconos'] = totem_getIconoFromClimateCode($result);
    $tiempo['localidad'] = $result['nombre_local'];
    
    
    //3º ahora debería falsear los datos de pasado mañana.
    $tiempo[ "pasado" ] = $result;
    $tiempo[ "pasado" ]['iconos'] = totem_getIconoFromClimateCode($result);
    $tiempo['localidad'] = $result['nombre_local'];
    
    if ($falsear) {
        $tiempo['localidad'] = "San Bartolomé de Tirajana";
    }
    else {
        $tiempo['localidad'] = $result['nombre_local'];
    }
    
}

//añado un campo titutlo para indicar el titulo de la tabla
$tiempo['titulo'] = "Temperaturas";

/*$tpl_meteoHtml = new TemplatePower("plantillas/rutas-maqueta.html", T_BYFILE);*/

$tpl_meteoHtml = new TemplatePower("plantillas/tiempo-maqueta.html", T_BYFILE);
$tpl_meteoHtml->prepare();

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




//Carpeta de los iconos y el fondo
$url_icono = "../../../contenido_proyectos/vistaflor/_general/tiempo/icono/";
$url_fondo = "../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/";

////Hora actual
$hora_actual = date("H");

 $tpl_meteoHtml->assign('prueba',$hora_actual);

//DE 0 a 6
if ( $hora_actual <= '6' ){
    // $vactual_temp = 'temp_06';
    $tpl_meteoHtml->assign('viento_dir',$tiempo['hoy']['viento_dir_00_06']);
    $tpl_meteoHtml->assign('viento_vel',$tiempo['hoy']['viento_vel_00_06']);
    $tpl_meteoHtml->assign('humedad',$tiempo['hoy']['hum_06']);

    $actual_icono = $tiempo['hoy']['iconos']['0'];
}


//DE 6 a 12
if ( ($hora_actual <= '12') && ($hora_actual > '06') ){
    // $vactual_temp = 'temp_12';
    $tpl_meteoHtml->assign('viento_dir',$tiempo['hoy']['viento_dir_06_12']);
    $tpl_meteoHtml->assign('viento_vel',$tiempo['hoy']['viento_vel_06_12']);
    $tpl_meteoHtml->assign('humedad',$tiempo['hoy']['hum_12']);

    $actual_icono = $tiempo['hoy']['iconos']['6'];
    
}

//de 12  a 18
if ( ($hora_actual <= '18') && ($hora_actual > '12') ){
   // $vactual_temp = 'temp_18';
    $tpl_meteoHtml->assign('viento_dir',$tiempo['hoy']['viento_dir_12_18']);
    $tpl_meteoHtml->assign('viento_vel',$tiempo['hoy']['viento_vel_12_18']);
    $tpl_meteoHtml->assign('humedad',$tiempo['hoy']['hum_18']);

    // $actual_icono = $tiempo['hoy']['iconos']['12'];
    $actual_icono = $tiempo['hoy']['iconos']['6'];
}

//de 18 a 24
if ( ($hora_actual <= '24') && ($hora_actual > '18') ){
    // $vactual_temp = 'temp_24';
    $tpl_meteoHtml->assign('viento_dir',$tiempo['hoy']['viento_dir_18_24']);
    $tpl_meteoHtml->assign('viento_vel',$tiempo['hoy']['viento_vel_18_24']);
    $tpl_meteoHtml->assign('humedad',$tiempo['hoy']['hum_24']);

    $actual_icono = $tiempo['tomorrow']['iconos']['0'];
    
}

//Localidad
$tpl_meteoHtml->assign('municipio',$tiempo['localidad']);

//Temperatura maxima y minima del dia de hoy
$tpl_meteoHtml->assign('hoy_maxima',$tiempo['hoy']['temp_max']);
$tpl_meteoHtml->assign('hoy_minima',$tiempo['hoy']['temp_min']);

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

$tpl_meteoHtml->assign('titulo_vertical_12',LANG_GLOBAL_DIA_MAÑANA);
$tpl_meteoHtml->assign('hoy12_maxima',$tiempo['hoy']['temp_06']);

$tpl_meteoHtml->assign('icono12', $url_icono . $tiempo['hoy']['iconos']['6']);
/////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
//Valores de hoy por la tarde
$tpl_meteoHtml->assign('titulo_vertical_18',LANG_GLOBAL_DIA_TARDE);
$tpl_meteoHtml->assign('hoy18_maxima',$tiempo['hoy']['temp_12']);

$tpl_meteoHtml->assign('icono18', $url_icono . $tiempo['hoy']['iconos']['12']);
/////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
//Valores de hoy por la noche
$tpl_meteoHtml->assign('titulo_vertical_24',LANG_GLOBAL_DIA_NOCHE);
$tpl_meteoHtml->assign('hoy24_maxima',$tiempo['hoy']['temp_24']);

$tpl_meteoHtml->assign('icono24', $url_icono . $tiempo['tomorrow']['iconos']['0']);
/////////////////////////////////////////////////////////////////////////////

//Selector de mierda de los dias siguientas
$tpl_meteoHtml->assign('hoy_day', strftime("%A", strtotime('today') ) );

//////////////////////////////////////////////////////////////////////////////
//Valores de tomorrow
$tpl_meteoHtml->assign('tomorrow_day', strftime("%A", strtotime('+ 1day') ) );

$tpl_meteoHtml->assign('tomorrow_maxima',$tiempo['tomorrow']['temp_max']);
$tpl_meteoHtml->assign('tomorrow_minima',$tiempo['tomorrow']['temp_min']);
$tpl_meteoHtml->assign('tomorrow_icono', $url_icono . $tiempo['tomorrow']['iconos']['12']);

$tpl_meteoHtml->assign('tomorrow_humedad', $tiempo['tomorrow']['hum_18']);
$tpl_meteoHtml->assign('tomorrow_viento_vel', $tiempo['tomorrow']['viento_vel_12_18']);
$tpl_meteoHtml->assign('tomorrow_viento_dir', $tiempo['tomorrow']['viento_dir_12_18']);

$tpl_meteoHtml->assign('tomorrow12_maxima', $tiempo['tomorrow']['temp_12']);
$tpl_meteoHtml->assign('tomorrow18_maxima', $tiempo['tomorrow']['temp_18']);
$tpl_meteoHtml->assign('tomorrow24_maxima', $tiempo['tomorrow']['temp_24']);

$tpl_meteoHtml->assign('tomorrowicono12', $url_icono . $tiempo['tomorrow']['iconos']['6']);
$tpl_meteoHtml->assign('tomorrowicono18', $url_icono . $tiempo['tomorrow']['iconos']['12']);
//aaaaaaaaaaaaaaaaaa////////////////////////////////////////////////////////////////////////////////////////////////////////

$tpl_meteoHtml->assign('tomorrowicono24', $url_icono . $tiempo['tomorrow']['iconos']['0']);

//////////////////////////////////////////////////////////////////////////////////////////////////////////

$fondo1 = $url_fondo . substr($tiempo['tomorrow']['iconos']['12'], 0, -3) . "jpg"; 
//$fondo1 = '../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/soleado.jpg' ; 
$tpl_meteoHtml->assign('imagen-fondo1',$fondo1);
/////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
//Valores de pasado
$tpl_meteoHtml->assign('pasado_day', strftime("%A", strtotime('+ 2day') ) );

$tpl_meteoHtml->assign('pasado_maxima',$tiempo['pasado']['temp_max']);
$tpl_meteoHtml->assign('pasado_minima',$tiempo['pasado']['temp_min']);
$tpl_meteoHtml->assign('pasado_icono', $url_icono . $tiempo['pasado']['iconos']['12']);

$fondo2 = $url_fondo .  substr($tiempo['pasado']['iconos']['12'], 0, -3)  . "jpg"; 
//$fondo2 = '../../../contenido_proyectos/vistaflor/_general/tiempo/fondo/tormenta.jpg' ; 
$tpl_meteoHtml->assign('imagen-fondo2',$fondo2);

/////////////////////////////////////////////////////////////////////////////


//Texto de seleccionar el municipio
$tpl_meteoHtml->assign('selector',LANG_TIEMPO_SELECTOR);

$tpl_meteoHtml->assign( 'cerrar', LANG_GLOBAL_ATRAS );


$tpl_meteoHtml->assign( 'img_sitio_hotel', '../../../contenido_proyectos/vistaflor/centro_'.$id_centro.'/logos/lugar_hotel.png' );

/////////////////todos los municipios


$municipios = conoce_listado_municipios();


//Tengo que ir iterando de dos en dos
$i = 0;

foreach ($municipios as $municipio) {
    if ($i == 0 ){
      $tpl_meteoHtml->newBlock("listado_municipios");  
    }

    $tpl_meteoHtml->newBlock("b$i");
    $tpl_meteoHtml->assign( "nombre_municipio", $municipio['nombre']);
    $tpl_meteoHtml->assign( "id_municipio", $municipio['cod_local']);
    $i ++;
    $i = $i%2;
}

/////////////////////////////////////CERRAR


$tiempo["meteoHTML"] = $tpl_meteoHtml->getOutputContent();

echo ( json_encode($tiempo, TRUE) );


?>