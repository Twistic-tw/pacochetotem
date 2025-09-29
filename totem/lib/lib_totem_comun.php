<?php

function do_post_request($url, $data, $optional_headers = null)
{
    $params = array(
        'http' => array(
            'method' => 'POST',
            'content' => $data
        )
    );
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);

    if (!$fp) {
        throw new Exception("No me dejo abrir la url: $url");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url");    //, $php_errormsg
    }
    return $response;
}



/******                 alian, 24-11-2014 */

/**
 * Funcion que verifica si existe una actualizacion de tiempo para la localidad definida y que este en el tiempo
 * estipulado.
 *
 * @param int $codigo_localida indica el codigo de la localidad que se quiere verificar
 * @param int $horas_verificar (opcional, default 6) indica el umbral de horas para considerar que el tiempo esta actualizado.
 *
 * @return bool devuelve true si existe una actualizacion para esa localidad o false si no.
 *
 */
function tiempo_verificar_actualizado($codigo_localidad, $horas_verificar = 6)
{
    $db = new MySQL();
    $query = "SELECT * FROM tiempo_actualizaciones 
                WHERE HOUR(TIMEDIFF(NOW(), act_time)) < $horas_verificar
                AND id_loc = (select id_local from localidades_aemet where cod_local = $codigo_localidad)
                ORDER BY id_act DESC LIMIT 1";

    $consulta = $db->consulta($query);
    $t = $db->fetch_array($consulta);

    return (is_array($t) && isset($t['id_act']));
}


/**
 * funcion para obtener los datos del tiempo de la base de datos del centro, se suponen ya actualizados
 */
function tiempo_obtener_datos_actualizados($codigo_localidad, $horas_verificar = 6)
{
    $db = new MySQL();
    $query = "SELECT * FROM tiempo 
                WHERE id_act = (
                                SELECT id_act FROM tiempo_actualizaciones 
                                    WHERE HOUR(TIMEDIFF(NOW(), act_time)) < $horas_verificar
                                    AND id_loc = (select id_local from localidades_aemet where cod_local = $codigo_localidad)
                                    ORDER BY id_act DESC LIMIT 1
                                )";

    $consulta = $db->consulta($query);

    $datos = array();

    while ($t = $db->fetch_array($consulta)) {
        $datos[] = $t;
    }

    return $datos;
}

function get_nombre_aeropuerto($cod)
{

    $db = new MySQL();

    $query = "select cod, nombre from vuelos_aeropuertos where cod = '$cod'";

    $rec = $db->consulta($query);
    $result = $db->fetch_array($rec);
    return $result;
}


function vuelos_datos_actualizados($id_cliente)
{
    $db = new MySQL();

    $date = date('Y-m-d H:i:s');

    //obtenemos el aeropuerto
    $queryA = "SELECT vuelos_aeropuertos.cod, vuelos_aeropuertos.id
               FROM vuelos_aeropuertos";
    $rec = $db->consulta($queryA);
    $result = $db->fetch_array($rec);

    $aeropuerto_cod = $result['cod'];
    $aeropuerto_id = $result['id'];

    //verifico si esta actualizado
    $query = "SELECT id FROM vuelos_conexiones
                WHERE id = (
                            SELECT id FROM vuelos_conexiones 
                                WHERE id_aero='$aeropuerto_id' ORDER BY id DESC  LIMIT 1 
                            ) 
                AND fecha > (NOW() - INTERVAL 30 MINUTE)";
    $rec = $db->consulta($query);
    $result = $db->fetch_array($rec);

    if ($result && is_array($result) && isset($result['id']))
        return true;
    else
        return false;
}


function vuelos_get_datos_locales($id_centro)
{
    $db = new MySQL();


    //obtenemos el aeropuerto a partir del cliente
    $queryA = "SELECT vuelos_aeropuertos.cod, vuelos_aeropuertos.id
               FROM vuelos_aeropuertos";
    $rec = $db->consulta($queryA);
    $result = $db->fetch_array($rec);

    $aeropuerto_cod = $result['cod'];
    $aeropuerto_id = $result['id'];

    //obtenemos la ultima conexion registrada para el id del aeropuerto del cliente
    $queryL = "SELECT * FROM vuelos_conexiones WHERE id_aero='$aeropuerto_id' ORDER BY id DESC LIMIT 1";
    $rec = $db->consulta($queryL);
    $result_con = $db->fetch_array($rec);
    $id_conexion = $result_con['id'];

    //obtenemos los datos que se han de mostrar.
    $query = "SELECT *  FROM vuelos_conexiones, vuelos_aeropuertos, vuelos_estados
                WHERE vuelos_conexiones.id = '$id_conexion'
                AND vuelos_conexiones.id_aero = vuelos_aeropuertos.id
                AND vuelos_conexiones.id = vuelos_estados.id_con
                ORDER BY hora";

    $rec = $db->consulta($query);

    $vuelos = array();
    $i = 0;

    while ($dato = $db->fetch_assoc($rec)) {
        $aeropuerto_codigo = $dato['cod'];
        $tipo_vuelo = $dato['tipo'];

        $vuelos[$aeropuerto_codigo][$tipo_vuelo][$i]['hora'] = $dato['hora'];
        $vuelos[$aeropuerto_codigo][$tipo_vuelo][$i]['vuelo'] = $dato['vuelo'];
        $vuelos[$aeropuerto_codigo][$tipo_vuelo][$i]['origen_destino'] = $dato['origen_destino'];
        $vuelos[$aeropuerto_codigo][$tipo_vuelo][$i]['company'] = $dato['company'];
        $vuelos[$aeropuerto_codigo][$tipo_vuelo][$i]['estado'] = $dato['estado'];

        $i++;
    }
    return $vuelos;
    /*
    array( 
            LPA => array(
                        "llegada" => [origen_destino, company, vuelo, hora, estado],
                                     [origen_destino, company, vuelo, hora, estado]
                        "salida"  => [origen_destino, company, vuelo, hora, estado],
                                     [origen_destino, company, vuelo, hora, estado]
                        ),
            MDA => array(
                        "llegada" => [origen_destino, company, vuelo, hora, estado],
                                     [origen_destino, company, vuelo, hora, estado]
                        "salida"  => [origen_destino, company, vuelo, hora, estado],
                                     [origen_destino, company, vuelo, hora, estado]
                        ),
                      )
    */
}



function get_farmacias_cercanas()
{
    $db = new MySQL();

    //el id se utiliza par ala foto, no se si debo utilziar el id o el n_farmacia
    $query = "SELECT farmacias.id, farmacias.telefono, farmacias.direccion, 
                    farmacias.titular as nombre, farmacias.map_latitud as latitud, farmacias.map_longitud as longitud,
                    ABS( map_latitud - centro.latitud ) AS f_latitud, 
                    ABS( map_longitud - centro.longitud ) AS f_longitud
                FROM farmacias, centro
                ORDER BY f_latitud + f_longitud
                    LIMIT 4";

    $rec = $db->consulta($query);

    $farmacias = array();

    while ($t = $db->fetch_assoc($rec)) {
        $farmacias[] = $t;
    }

    return $farmacias;
}


function get_farmacias_guardia($fecha = false)
{
    $db = new MySQL();

    if ($fecha) {
        $fecha = date("Y-m-d", strtotime($fecha));
    } else {
        $fecha = date("Y-m-d");
    }

    $query = "SELECT farmacias.id, farmacias.telefono, farmacias.direccion, 
                    farmacias.titular as nombre, farmacias.map_latitud as latitud, farmacias.map_longitud as longitud,
                    ABS( map_latitud - centro.latitud ) AS f_latitud, 
                    ABS( map_longitud - centro.longitud ) AS f_longitud
                FROM farmacias_guardias as t1 , farmacias, centro
                    WHERE t1.fecha = '$fecha' 
                    AND t1.id_farmacia = farmacias.n_farmacia 
                    ORDER BY f_latitud + f_longitud
                    LIMIT 2";

    $rec = $db->consulta($query);

    $farmacias = array();

    while ($t = $db->fetch_assoc($rec)) {
        $farmacias[] = $t;
    }

    return $farmacias;
}

/**************************************************************************/


/** funcion para obtener el lsitado de días con actividades. La funcion devuelve un array en el que estan agrupadas las 
 * actividades por hotel y por fecha. Dentro cada uno de estos indice contiene una matriz de arrays con campos tales como fehca inicio, fecha fin...
 * @param string $fecha Fecha para la que deseamos obtener el listaod. Formato MM-DD-YYYY
 * @return json devuelve un json con los datos. En caso de no haberlos el array estará vacio */
function totem_listado_actividades_agenda_json()
{

    $db = new MySQL();
    $idioma = $_SESSION['idioma'];
    $id_centro = $_SESSION['id_centro'];

    $year = date("Y");

    $date_ini = date("Y-m-d", strtotime("today - 6 month")); //$year . "-01-01"; //agenda anual
    $date_fin = date("Y-m-d", strtotime("today + 6 month")); //$year2 . "-12-31";

    $sql = "SELECT DISTINCT t2.fecha, t3.id_cat  
                    FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t2 
                        ON t1.id_evento = t2.id_evento
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t3 
                        ON t2.id_evento = t3.id_evento
                    WHERE t2.fecha BETWEEN '" . $date_ini . "' AND '" . $date_fin . "'";

    /*    echo $sql;
    exit();*/
    if (isset($_GET['debug']) && $_GET['debug']) {
        $GLOBALS['__ghcd_sql'][] = $sql;
    }
    $consulta = $db->consulta($sql);

    $fechas = array();

    while ($fila = $db->fetch_array($consulta)) {
        $fechas['hotel'][date("j/n/Y", strtotime($fila['fecha']))] = true;
    }

    /* for($j=28; $j<30; $j++) {
      $fechas['hotel'][ "$j/8/2013"] = true;
      } */

    return json_encode($fechas);
}

/** funcion para generar la string de la fecha actual en funcion del idioma.
 * @return string fecha actual segun el formato.
 */
function getFechaActual()
{
    switch ($_SESSION['idioma']) {
        case 1:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = strftime("%A %d/%m/%Y");
            break;

        case 2:
            setlocale(LC_ALL, "en_EN.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, "english");
            $fecha = strftime("%A %d/%m/%Y");
            break;

        case 3:
            setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'german');
            $fecha = strftime("%A %d/%m/%Y");
            break;

        case 4:
            setlocale(LC_ALL, "fr_FR.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'french');
            $fecha = strftime("%A %d/%m/%Y");
            break;

        default:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = utf8_encode(strftime("%A %d de %B %Y"));
            break;
    }
    return $fecha;
}

function getFechaActual_no_year($no_month = false)
{
    switch ($_SESSION['idioma']) {
        case 1:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = strftime((($no_month) ? "%A" : "%A %d de %B"));
            break;

        case 2:
            setlocale(LC_ALL, "en_EN.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, "english");
            $fecha = strftime((($no_month) ? "%A" : "%A %B %d"));
            break;

        case 3:
            setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'german');
            $fecha = strftime((($no_month) ? "%A" : "%A %d. %B"));
            break;

        case 4:
            setlocale(LC_ALL, "fr_FR.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'french');
            $fecha = strftime((($no_month) ? "%A" : "%A %d %B"));
            break;

        default:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = utf8_encode(strftime((($no_month) ? "%A" : "%A %d de %B")));
            break;
    }
    return $fecha;
}

function getFecha2_no_year($no_month = false)
{
    switch ($_SESSION['idioma']) {
        case 1:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = strftime((($no_month) ? "%A" : "%A %d de %B"), strtotime('+ 1day'));
            break;

        case 2:
            setlocale(LC_ALL, "en_EN.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, "english");
            $fecha = strftime((($no_month) ? "%A" : "%A %B %d"), strtotime('+ 1day'));
            break;

        case 3:
            setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'german');
            $fecha = strftime((($no_month) ? "%A" : "%A %d. %B"), strtotime('+ 1day'));
            break;

        case 4:
            setlocale(LC_ALL, "fr_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'french');
            $fecha = strftime((($no_month) ? "%A" : "%A %d %B"), strtotime('+ 1day'));
            break;

        default:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = utf8_encode(strftime((($no_month) ? "%A" : "%A %d de %B"), strtotime('+ 1day')));
            break;
    }
    return $fecha;
}

function getFecha3_no_year($no_month = false)
{
    switch ($_SESSION['idioma']) {
        case 1:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = strftime((($no_month) ? "%A" : "%A %d de %B"), strtotime('+ 2day'));
            break;

        case 2:
            setlocale(LC_ALL, "en_EN.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, "english");
            $fecha = strftime((($no_month) ? "%A" : "%A %B %d"), strtotime('+ 2day'));
            break;

        case 3:
            setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'german');
            $fecha = strftime((($no_month) ? "%A" : "%A %d. %B"), strtotime('+ 2day'));
            break;

        default:
            setlocale(LC_ALL, "es_ES.UTF-8");
            define("CHARSET", "UTF-8");

            setlocale(LC_TIME, 'spanish');
            $fecha = utf8_encode(strftime((($no_month) ? "%A" : "%A %d de %B"), strtotime('+ 2day')));
            break;
    }
    return $fecha;
}

/** funcion para obtener el lsitado de actividades para el dia concreto. La funcion devuelve un array en el que estan agrupadas las 
 * actividades por hotel y por fecha. Dentro cada uno de estos indice contiene una matriz de arrays con campos tales como fehca inicio, fecha fin...
 * @param string $fecha Fecha para la que deseamos obtener el listaod. Formato MM-DD-YYYY
 * @param int $idioma Indica el idioma para el que se solicita el contenido. Es un valor numerico.
 * @return array devuelve un array con los datos. En caso de no haberlos el array estará vacio */
function totem_listado_actividades_info($fecha, $idioma = 1)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];

    //    if($id_centro == 289){
    //            $id_centro = 288;
    //            $bd_conexion = $db->getConexionID();
    //            mysql_select_db('bd_feeltourist_riu_palacemaldivas', $bd_conexion);
    //    }


    $fecha = date("Y-m-d", strtotime($fecha));

    if ($id_centro == "273" && $idioma == 5) {
        $idioma = 2;
    }

    if (in_array($id_centro,[278,288,289]) && $idioma == 4) {
        $idioma = 9;
    }

    $sql = "SELECT  t1.id_evento, t1.image as foto_evento,
                    t2.titulo as title, t2.descripcion as content,
                    t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
                    t8.id_cat, t8.class_icon as icon, t8.color, 
                    t5.id_lugar, 
                    t9.nombre as cat_lugar, t9.nombre as lugar, 
                    t9.nombre       
                FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
                        ON t1.id_evento = t2.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
                        ON t2.id_evento = t3.id_evento
                    LEFT JOIN eventos_tiene_lugares as t4 
                        ON t3.id_evento = t4.id_evento
                    LEFT JOIN eventos_lugares as t5 
                        ON t4.id_lugar = t5.id_lugar
                    LEFT JOIN eventos_lugares_tiene_idiomas as t6 
                        ON t5.id_lugar = t6.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
                        ON t3.id_evento = t7.id_evento
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias as t8 
                        ON t7.id_cat = t8.id_cat
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias_tiene_idiomas as t9 
                        ON t8.id_cat = t9.id_cat                           
                WHERE t3.fecha = '$fecha'
                    AND t2.id_idioma = '$idioma'                    
                    AND (t6.id_idioma = '$idioma' OR t6.id_idioma IS NULL)
                    AND t9.id_idioma = '$idioma'
                    AND t1.estado > 0 
                    AND t8.id_cat NOT IN (7,8)  
                    AND t3.hora_ini IS NOT NULL                 
                    AND t3.hora_fin IS NOT NULL                 
                    GROUP BY t3.id  ORDER BY t3.hora_ini ASC";

    if (isset($_GET['debug']) && $_GET['debug']) {
        $GLOBALS['__ghcd_sql'][] = $sql;
    }
    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][] = array(
            "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'],
            "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'], "titulo_evento" => $fila['title'], "contenido" => $fila['content'],
            "lugar" => $fila['lugar'], "foto_evento" => $fila['foto_evento'], "icon" => $fila['icon'], "color" => $fila['color'], "cat_lugar" => $fila['cat_lugar'], "categoria" => $fila['categoria'], "id_evento" => $fila['id_evento']
        );
    }

    return $fechas;
}

function totem_listado_actividades_info_OLD($fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha = date("Y-m-d", strtotime($fecha));
    $sql = "SELECT  t1.id_evento, t1.image as foto_evento,
                    t2.titulo as title, t2.descripcion as content,
                    t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
                    t8.id_cat, t8.class_icon as icon, t8.color, 
                    t5.id_lugar, 
                    t9.nombre as cat_lugar, t9.nombre as lugar, 
                    t9.nombre       
                FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
                        ON t1.id_evento = t2.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
                        ON t2.id_evento = t3.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_tiene_lugares as t4 
                        ON t3.id_evento = t4.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares as t5 
                        ON t4.id_lugar = t5.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares_tiene_idiomas as t6 
                        ON t5.id_lugar = t6.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
                        ON t3.id_evento = t7.id_evento
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias as t8 
                        ON t7.id_cat = t8.id_cat
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias_tiene_idiomas as t9 
                        ON t8.id_cat = t9.id_cat                           
                WHERE t3.fecha = '$fecha'
                    AND t2.id_idioma = '$idioma'                    
                    AND t6.id_idioma = '$idioma'
                    AND t9.id_idioma = '$idioma'
                    AND t1.estado = '1'     
                    AND t3.hora_ini IS NOT NULL                 
                    AND t3.hora_fin IS NOT NULL                 
                    ORDER BY t3.hora_ini ASC";

    // echo "<pre>";
    // echo $sql;


    if (isset($_GET['debug']) && $_GET['debug']) {
        $GLOBALS['__ghcd_sql'][] = $sql;
    }
    $consulta = $db->consulta($sql);
    $fechas = array();

    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][] = array(
            "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'],
            "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'], "titulo_evento" => $fila['title'], "contenido" => $fila['content'],
            "lugar" => $fila['lugar'], "foto_evento" => $fila['foto_evento'], "icon" => $fila['icon'], "color" => $fila['color'], "cat_lugar" => $fila['cat_lugar'], "lugar_nuevo" => $fila['nombre'], "id_lugar" => $fila['id_lugar']
        );
    }

    return $fechas;
}



function obtener_evento_destacado($fecha, $idioma = 1)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];

    $fecha = date("Y-m-d", strtotime($fecha));

    $sql = "SELECT  t1.id_evento, t1.image as foto_evento,
                    t2.titulo as title, t2.descripcion as content,
                    t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
                    t8.id_cat, t8.class_icon as icon, t8.color, 
                    t9.nombre                    
                    FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                        INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
                            ON t1.id_evento = t2.id_evento
                        INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
                            ON t2.id_evento = t3.id_evento 
                        INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
                            ON t3.id_evento = t7.id_evento
                        INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias as t8 
                            ON t7.id_cat = t8.id_cat
                        INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias_tiene_idiomas as t9 
                            ON t8.id_cat = t9.id_cat                           
                    WHERE   t3.fecha = '$fecha'
                            AND t2.id_idioma = '$idioma'
                            AND t9.id_idioma = '$idioma'
                            AND t8.id_cat = '3' 
                            AND t3.hora_ini IS NOT NULL                 
                            AND t3.hora_fin IS NOT NULL                 
                    ORDER BY t3.hora_ini ASC";


    // $sql = "SELECT  t1.id_evento, t1.image as foto_evento,
    //                 t2.titulo as title, t2.descripcion as content,
    //                 t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
    //                 t8.id_cat, t8.class_icon as icon, t8.color, 
    //                 t5.id_lugar, 
    //                 t6.nombre as cat_lugar, t6.nombre as lugar, 
    //                 t9.nombre                    
    //                 FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
    //                     INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
    //                         ON t1.id_evento = t2.id_evento
    //                     INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
    //                         ON t2.id_evento = t3.id_evento
    //                     INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_tiene_lugares as t4 
    //                         ON t3.id_evento = t4.id_evento
    //                     INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares as t5 
    //                         ON t4.id_lugar = t5.id_lugar
    //                     INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares_tiene_idiomas as t6 
    //                         ON t5.id_lugar = t6.id_lugar
    //                     INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
    //                         ON t3.id_evento = t7.id_evento
    //                     INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias as t8 
    //                         ON t7.id_cat = t8.id_cat
    //                     INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_categorias_tiene_idiomas as t9 
    //                         ON t8.id_cat = t9.id_cat                           
    //                 WHERE   t3.fecha = '$fecha'
    //                         AND t2.id_idioma = '$idioma'                    
    //                         AND t6.id_idioma = '$idioma'
    //                         AND t9.id_idioma = '$idioma'
    //                         AND t8.id_cat = '3' 
    //                         AND t3.hora_ini IS NOT NULL                 
    //                         AND t3.hora_fin IS NOT NULL                 
    //                 ORDER BY t3.hora_ini ASC";


    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][] = array(
            "id" => $fila['id_evento'], "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'],
            "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'], "titulo_evento" => $fila['title'], "contenido" => $fila['content'],
            "lugar" => $fila['lugar'], "foto_evento" => $fila['foto_evento'], "icon" => $fila['icon'], "color" => $fila['color'], "cat_lugar" => $fila['cat_lugar'], "lugar_nuevo" => $fila['nombre']
        );
    }

    return $fechas;
}




/*Funcion que devuelve las actividades de 3 dias**/

function totem_getActividades3dias($id_centro, $fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha_ini = date("Y-m-d", strtotime($fecha));
    $fecha_fin = date("Y-m-d", (strtotime($fecha) + (2 * 24 * 60 * 60)));


    $sql = "SELECT  t1.id_evento, t1.image as foto_evento, t1.video as video_evento,
                    t2.titulo as title, t2.descripcion as content,
                    t3.fecha, t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
                    t7.id_cat, t8.class_icon as icon_lugar, t8.color as color_lugar,
                    t5.id_lugar, 
                    t6.nombre as nombre_lugar
            FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
                        ON t1.id_evento = t2.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
                        ON t2.id_evento = t3.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_tiene_lugares as t4 
                        ON t3.id_evento = t4.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares as t5 
                        ON t4.id_lugar = t5.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares_tiene_idiomas as t6 
                        ON t5.id_lugar = t6.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
                        ON t4.id_evento = t7.id_evento
            WHERE   t3.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                    AND t2.id_idioma = '$idioma'                    
                    AND t6.id_idioma = '$idioma'
                    AND t3.hora_ini IS NOT NULL                 
                    AND t3.hora_fin IS NOT NULL 
            ORDER BY t3.hora_ini ASC";



    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][$fila['fecha']][$fila['lugar']][] =
            array(
                "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'], "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'],
                "titulo_evento" => $fila['title'], "contenido" => $fila['content'], "foto_evento" => $fila['foto_evento'], "video_evento" => $fila['video_evento'], "id_evento" => $fila['id_evento'],
                "id_lugar" => $fila['id_lugar'], "lugar" => $fila['lugar'], "icon_lugar" => $fila['icon_lugar'], "color_lugar" => $fila['color_lugar'], "nombre_lugar" => $fila['nombre_lugar']
            );
    }

    return $fechas;
}


/*Funcion que devuelve las actividades de 7 dias**/

function totem_getActividades7dias($id_centro, $fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha_ini = date("Y-m-d", strtotime($fecha));
    $fecha_fin = date("Y-m-d", (strtotime($fecha) + (6 * 24 * 60 * 60)));

    $sql = "SELECT DISTINCT t1.fecha,
                    t4.title, t4.content, t4.foto_evento, t4.video_evento,
                    t2.hora_ini, t2.hora_fin, t2.repeticiones, t2.fecha_ini, t2.fecha_fin, t2.id_evento, t2.id_cat, 
                    t6.id_lugar, t6.lugar, t6.icon as icon_lugar, t6.color as color_lugar,
                    t7.nombre as nombre_lugar
                FROM
                    eventos_ocurrencias t1,
                    eventos t2,
                    eventos_contenido t4,
                    eventos_lugares t6,
                    eventos_lugares_tiene_idiomas t7
                WHERE t1.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                    AND t1.id_centro = $id_centro                         
                    AND t1.id_evento = t4.id_evento
                    AND t4.id_idioma = '$idioma'                    
                    AND t1.id_evento = t2.id_evento 
                    AND t4.id_lugar = t6.id_lugar
                    AND t7.id_lugar = t6.id_lugar
                    AND t7.id_idioma = '$idioma'
                        
                    ORDER BY t1.fecha, t2.hora_ini ASC";



    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][$fila['fecha']][] =
            array(
                "lugar" => $fila['lugar'], "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'], "icon_cat" => $fila['icon_cat'],
                "icon_subcat" => $fila['icon_subcat'], "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'], "nombre_cat" => $fila['nombre_padre'],
                "id_categoria" => $fila['id_cat'], "padre" => $fila['padre'], "titulo_evento" => $fila['title'], "contenido" => $fila['content'], "foto_evento" => $fila['foto_evento'], "video_evento" => $fila['video_evento'], "id_evento" => $fila['id_evento'],
                "id_lugar" => $fila['id_lugar'], "lugar" => $fila['lugar'], "icon_lugar" => $fila['icon_lugar'], "color_lugar" => $fila['color_lugar'], "nombre_lugar" => $fila['nombre_lugar']
            );
    }
    return $fechas;
}


/* Funcion que devuelve todas las ubicaciones segun el id_idioma**/

function totem_getubicaciones($id_centro, $idioma = 1)
{

    $db = new MySQL();

    $query = "SELECT * FROM eventos_lugares_tiene_idiomas as t1, eventos_lugares as t2
            WHERE id_idioma = $idioma
            AND t1.id_lugar = t2.id_lugar";

    $result = $db->consulta($query);

    $datos = array();

    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}

/* Funcion que devuelve todas las ubicaciones segun el id_idioma**/

function totemgeteventoubicacion($id_ubicacion, $idioma = 1, $fecha)
{

    $fecha = date("Y-m-d", strtotime($fecha));

    $db = new MySQL();

    $query = "SELECT * FROM eventos AS t1, eventos_contenido as t2, eventos_ocurrencias as t3
            WHERE t2.id_idioma = $idioma
            AND t2.id_lugar = $id_ubicacion
            AND t3.fecha = '$fecha'
            AND t1.id_evento = t3.id_evento
            AND t2.id_evento = t3.id_evento";

    // echo $query;


    $result = $db->consulta($query);

    $datos = array();

    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}


///Actividades hoy
/*Funcion que devuelve las actividades de 7 dias**/

function totem_getActividadeshoy($id_centro, $fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha_ini = date("Y-m-d", strtotime($fecha));
    $fecha_fin = date("Y-m-d", strtotime($fecha));

    $sql = "SELECT DISTINCT t1.fecha,
                    t4.title, t4.content, t4.foto_evento, t4.video_evento,
                    t2.hora_ini, t2.hora_fin, t2.repeticiones, t2.fecha_ini, t2.fecha_fin, t2.id_evento, t2.id_cat, 
                    t6.id_lugar, t6.lugar, t6.icon as icon_lugar, t6.color as color_lugar,
                    t7.nombre as nombre_lugar
                FROM
                    eventos_ocurrencias t1,
                    eventos t2,
                    eventos_contenido t4,
                    eventos_lugares t6,
                    eventos_lugares_tiene_idiomas t7
                WHERE t1.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                    AND t1.id_centro = $id_centro                         
                    AND t1.id_evento = t4.id_evento
                    AND t4.id_idioma = '$idioma'                    
                    AND t1.id_evento = t2.id_evento 
                    AND t4.id_lugar = t6.id_lugar
                    AND t7.id_lugar = t6.id_lugar
                    AND t7.id_idioma = '$idioma'
                        
                    ORDER BY t2.hora_ini ASC";



    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][$fila['fecha']][$fila['lugar']][] =
            array(
                "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'], "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'],
                "titulo_evento" => $fila['title'], "contenido" => $fila['content'], "foto_evento" => $fila['foto_evento'], "video_evento" => $fila['video_evento'], "id_evento" => $fila['id_evento'],
                "id_lugar" => $fila['id_lugar'], "lugar" => $fila['lugar'], "icon_lugar" => $fila['icon_lugar'], "color_lugar" => $fila['color_lugar'], "nombre_lugar" => $fila['nombre_lugar']
            );
    }

    return $fechas;
}


/** Funcion para obtener las actividades/eventos de una semana en adelante a partir 
 * de la fecha pasada por parametro.
 * 
 * @param string $fecha fecha en formato dd-mm-aaaa
 * @param int $idioma indica el idioma, por defecto se asume el 1 (español)
 * @return array devuelve un array multinivel con la estructura:<br>
    array[ 'hotel' ][ 'fecha' ][ 'lugar' ] = array(evento1, evento2)<br>
    array[ 'isla' ][ 'fecha' ][ 'lugar' ] = array(evento1, evento2)<br>
 */


function totem_getActividadesSemanas($id_centro, $fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha_ini = date("Y-m-d", strtotime($fecha));
    $fecha_fin = date("Y-m-d", (strtotime($fecha) + (6 * 24 * 60 * 60)));


    $sql = "SELECT  t1.id_evento, t1.image as foto_evento, t1.video as video_evento,
                    t2.titulo as title, t2.descripcion as content,
                    t3.fecha, t3.fecha as fecha_ini, t3.fecha as fecha_fin, t3.hora_ini, t3.hora_fin, 
                    t7.id_cat, t8.class_icon as icon_lugar, t8.color as color_lugar,
                    t5.id_lugar, 
                    t6.nombre as nombre_lugar
            FROM " . $_SESSION['bbdd_comun'] . ".eventos as t1
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_idiomas as t2 
                        ON t1.id_evento = t2.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_ocurrencias as t3 
                        ON t2.id_evento = t3.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_tiene_lugares as t4 
                        ON t3.id_evento = t4.id_evento
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares as t5 
                        ON t4.id_lugar = t5.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_normal'] . ".eventos_lugares_tiene_idiomas as t6 
                        ON t5.id_lugar = t6.id_lugar
                    INNER JOIN " . $_SESSION['bbdd_comun'] . ".eventos_tiene_categorias as t7 
                        ON t4.id_evento = t7.id_evento
            WHERE   t3.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                    AND t2.id_idioma = '$idioma'                    
                    AND t6.id_idioma = '$idioma'
                    AND t3.hora_ini IS NOT NULL                 
                    AND t3.hora_fin IS NOT NULL 
            ORDER BY t3.hora_ini ASC";



    $consulta = $db->consulta($sql);


    $fechas = array();


    while ($fila = $db->fetch_array($consulta)) {

        $fechas['hotel'][$fila['fecha']][$fila['lugar']][] =
            array(
                "repeticiones" => $fila['repeticiones'], "fecha_ini" => $fila['fecha_ini'], "fecha_fin" => $fila['fecha_fin'], "hora_ini" => $fila['hora_ini'], "hora_fin" => $fila['hora_fin'],
                "titulo_evento" => $fila['title'], "contenido" => $fila['content'], "foto_evento" => $fila['foto_evento'], "video_evento" => $fila['video_evento'], "id_evento" => $fila['id_evento'],
                "id_lugar" => $fila['id_lugar'], "lugar" => $fila['lugar'], "icon_lugar" => $fila['icon_lugar'], "color_lugar" => $fila['color_lugar'], "nombre_lugar" => $fila['nombre_lugar'], "categoria" => $fila['id_cat']
            );
    }

    return $fechas;
}

function nombre_cat($id_cat, $idioma)
{
    $db = new MySQL();

    $query = "select nombre from eventos_categorias where id_cat = '$id_cat' and id_idioma = '$idioma'";
    $consulta = $db->consulta($query);



    while ($fila = $db->fetch_array($consulta)) {
        $datos = $fila['nombre'];
    }

    return $datos;
}


function totem_getNumeroActividadesPorHora($id_centro, $fecha, $idioma = 1)
{
    $db = new MySQL();

    $fecha_ini = date("Y-m-d", strtotime($fecha));
    $fecha_fin = date("Y-m-d", (strtotime($fecha) + (6 * 24 * 60 * 60)));

    $query = "SELECT MAX( cantidad ) as repeticiones , hora_ini
                   FROM (
                        SELECT DISTINCT COUNT( t4.id ) AS cantidad, hora_ini
                             FROM eventos_ocurrencias AS t4, eventos AS t5
                                  WHERE t4.fecha BETWEEN '$fecha_ini'
                                  AND '$fecha_fin'
                                  AND t4.id_centro = $id_centro
                                  AND t4.id_evento = t5.id_evento
                                  AND t4.id_evento IN ( SELECT DISTINCT id_evento FROM eventos_contenido )
                             GROUP BY fecha, hora_ini
                             ORDER BY hora_ini, cantidad DESC
                   ) AS t1
              GROUP BY t1.hora_ini";

    $consulta = $db->consulta($query);
    $datos;

    while ($fila = $db->fetch_array($consulta)) {
        $datos[$fila['hora_ini']] = $fila['repeticiones'];
    }

    return $datos;
}



/** funcion para obtener el texto asociado a un estado codificado del tiempo, por ejemplo el 13 se corresponde con intervalos nubosos.
 * La funcion discrimina en funcion del idioma y dle codigo para devolver una string con dicho codigo. 
 * @param string $estado indica el estado (el valor alfanumerico) para el que deseamos obtener el titulo. Funciona en base a
 *  los codigos que obtenemos del aemet.
 * @param string $idioma indica el idioma para el cual deseamos obtener el titulo del estado. Por defecto español.
 * @return string devuelve una string con dicho texto
 */
function totem_tiempoCodesToString($estado)
{

    $idioma = $_SESSION['idioma']; //"es";
    //$tiempoIdiomas = array("es", "en", "de", "ru");
    $tiempoIdiomas = array("1", "2", "3", "4");
    $tiempoIdiomas["1"] = array(
        "11" => "Despejado", "11n" => "Despejado noche",
        "12" => "Poco nuboso", "12n" => "Poco nuboso noche",
        "13" => "Intervalos nubosos", "13n" => "Intervalos nubosos noche",
        "14" => "Nuboso", "14n" => "Nuboso noche",
        "15" => "Muy nuboso", "15n" => "Muy nuboso noche",
        "16" => "Cubierto", "16n" => "Cubierto noche",
        "17" => "Nubes altas", "17n" => "Nubes altas noche",
        "43" => "Intervalos nubosos con lluvia escasa", "43n" => "Intervalos nubosos con lluvia escasa noche",
        "44" => "Cubierto con lluvia escasa", "44n" => "Cubierto con lluvia escasa noche",
        "45" => "Muy nuboso con lluvia escasa", "45n" => "noche",
        "46" => "Cubierto con lluvia escasa", "46n" => "Cubierto con lluvia escasa noche",
        "23" => "Intervalos nubosos con lluvia", "23n" => "Intervalos nubosos con lluvia noche",
        "24" => "Nuboso con lluvia", "24n" => "Nuboso con lluvia noche",
        "25" => "Muy nuboso con lluvia", "25n" => "Muy nuboso con lluvia noche",
        "51" => "Intervalos nubosos con tormenta", "51n" => "Intervalos nubosos con tormenta noche",
        "52" => "Nuboso con tormenta", "52n" => "Nuboso con tormenta noche",
        "53" => "Muy nuboso con tormenta", "53n" => "Muy nuboso con tormenta noche",
        "54" => "Cubierto con tormenta", "54n" => "Cubierto con tormenta noche",
        "61" => "Intervalos nubosos con tormenta y lluvia escasa", "61n" => "Intervalos nubosos con tormenta y lluvia escasa noche",
        "62" => "Nuboso con tormenta y lluvia escasa", "62n" => "Nuboso con tormenta y lluvia escasa noche",
        "63" => "Muy nuboso con tormenta y lluvia escasa", "63n" => "Muy nuboso con tormenta y lluvia escasa noche",
        "64" => "Cubierto con tormenta y lluvia escasa", "64n" => "Cubierto con tormenta y lluvia escasa noche",
        "71" => "Intervalos nubosos con nieve escasa", "71n" => "Intervalos nubosos con nieve escasa noche",
        "72" => "Nuboso con nieve escasa", "72n" => "Nuboso con nieve escasa noche",
        "73" => "Muy nuboso con nieve escasa", "73n" => "Muy nuboso con nieve escasa noche",
        "74" => "Cubierto con nieve escasa", "74n" => "Cubierto con nieve escasa noche",
        "33" => "Intervalos nubosos con nieve", "33n" => "Intervalos nubosos con nieve noche",
        "34" => " Nuboso con nieve", "34n" => "Nuboso con nieve noche",
        "35" => "Muy nuboso con nieve", "35n" => "Muy nuboso con nieve noche",
        "36" => "Cubierto con nieve", "36n" => "Cubierto con nieve noche"
    );

    $tiempoIdiomas["2"] = array(
        "11" => "Cloudless", "11n" => "Cloudless night",
        "12" => "Few clouds", "12n" => "Few clouds night",
        "13" => "Few clouds", "13n" => "Few clouds night",
        "14" => "Cloudy", "14n" => "Cloudy night",
        "15" => "Very cloudy", "15n" => "Very cloudy night",
        "16" => "Overcast", "16n" => "Overcast night",
        "17" => "High clouds", "17n" => "High clouds night",
        "43" => "Cloudy intervals with low rainfall", "43n" => "Cloudy intervals with low rainfall night",
        "44" => "Cloudy with rain scarce", "44n" => "Cloudy with rain scarce night",
        "45" => "Cloudy with rain scarce", "45n" => "Cloudy with rain scarce night",
        "46" => "Cloudy with rain scarce", "46n" => "Cloudy with rain scarce night",
        "23" => "Cloudy with rain intervals", "23n" => "Cloudy with rain intervals night",
        "24" => "Cloudy with rain", "24n" => "Cloudy with rain night",
        "25" => "Very cloudy with rain", "25n" => "Very cloudy with rain night",
        "51" => "Cloudy intervals with storm", "51n" => "Cloudy intervals with storm night",
        "52" => "Nuboso con tormenta", "52n" => "Cloudy with thunderstorms night",
        "53" => "Cloudy with thunderstorms", "53n" => "Cloudy with thunderstorms night",
        "54" => "Overcast with thunderstorms", "54n" => "Overcast with thunderstorms night",
        "61" => "Cloudy intervals with little rain storm", "61n" => "Cloudy intervals with little rain storm night",
        "62" => "Cloudy intervals with little rain storm", "62n" => "Cloudy intervals with little rain storm night",
        "63" => "Cloudy with rain storm and poor", "63n" => "Cloudy with rain storm and poor night",
        "64" => "Overcast with thunderstorms and rain scarce", "64n" => "Overcast with thunderstorms and rain scarce night",
        "71" => "Cloudy intervals with scant snow", "71n" => "Cloudy intervals with scant snow night",
        "72" => "Cloudy with snow scarce", "72n" => "Cloudy with snow scarce night",
        "73" => "Cloudy with snow scarce", "73n" => "Cloudy with snow scarce night",
        "74" => "Covered with snow scarce", "74n" => "Covered with snow scarce night",
        "33" => "Cloudy intervals with snow", "33n" => "Cloudy intervals with snow night",
        "34" => "Cloudy with snow", "34n" => "Cloudy with snow night",
        "35" => "Very cloudy with snow", "35n" => "Very cloudy with snow night",
        "36" => "Covered with snow", "36n" => "Covered with snow night"
    );

    $tiempoIdiomas["3"] = array(
        "11" => "Cloudless", "11n" => "Cloudless night",
        "12" => "Few clouds", "12n" => "Few clouds night",
        "13" => "Few clouds", "13n" => "Few clouds night",
        "14" => "Cloudy", "14n" => "Cloudy night",
        "15" => "Very cloudy", "15n" => "Very cloudy night",
        "16" => "Overcast", "16n" => "Overcast night",
        "17" => "High clouds", "17n" => "High clouds night",
        "43" => "Cloudy intervals with low rainfall", "43n" => "Cloudy intervals with low rainfall night",
        "44" => "Cloudy with rain scarce", "44n" => "Cloudy with rain scarce night",
        "45" => "Cloudy with rain scarce", "45n" => "Cloudy with rain scarce night",
        "46" => "Cloudy with rain scarce", "46n" => "Cloudy with rain scarce night",
        "23" => "Cloudy with rain intervals", "23n" => "Cloudy with rain intervals night",
        "24" => "Cloudy with rain", "24n" => "Cloudy with rain night",
        "25" => "Very cloudy with rain", "25n" => "Very cloudy with rain night",
        "51" => "Cloudy intervals with storm", "51n" => "Cloudy intervals with storm night",
        "52" => "Nuboso con tormenta", "52n" => "Cloudy with thunderstorms night",
        "53" => "Cloudy with thunderstorms", "53n" => "Cloudy with thunderstorms night",
        "54" => "Overcast with thunderstorms", "54n" => "Overcast with thunderstorms night",
        "61" => "Cloudy intervals with little rain storm", "61n" => "Cloudy intervals with little rain storm night",
        "62" => "Cloudy intervals with little rain storm", "62n" => "Cloudy intervals with little rain storm night",
        "63" => "Cloudy with rain storm and poor", "63n" => "Cloudy with rain storm and poor night",
        "64" => "Overcast with thunderstorms and rain scarce", "64n" => "Overcast with thunderstorms and rain scarce night",
        "71" => "Cloudy intervals with scant snow", "71n" => "Cloudy intervals with scant snow night",
        "72" => "Cloudy with snow scarce", "72n" => "Cloudy with snow scarce night",
        "73" => "Cloudy with snow scarce", "73n" => "Cloudy with snow scarce night",
        "74" => "Covered with snow scarce", "74n" => "Covered with snow scarce night",
        "33" => "Cloudy intervals with snow", "33n" => "Cloudy intervals with snow night",
        "34" => "Cloudy with snow", "34n" => "Cloudy with snow night",
        "35" => "Very cloudy with snow", "35n" => "Very cloudy with snow night",
        "36" => "Covered with snow", "36n" => "Covered with snow night"
    );



    return $tiempoIdiomas[$idioma][$estado];
}


/** esta funcion devuelve la imagen asociada a cada estado del cielo. se le pasa
 * el array resultante de la consulta para obtener los datos meteorologicos y dentor
 * se realiza un switch que recorre los estados para las distintas franjas horarias.
 * @param array $datos array con todos los datos devueltos por la consulta sql
 * @param boolean $tomorrow Indica si estamos en el dia de mñn. Esto es necesario para poder indicar que el icono
 * del tiempo que hace alusión a la noche es en formato noche (n), ya que no hay otra forma de obtener dicho dato.
 * @return array devuelve un array con un indice que refleja la hora y tiene el nombre de la imagen a mostrar */
function totem_getIconoFromClimateCode($datos, $tomorrow = false)
{
    $resultados = array();

    for ($i = 0; $i < 24; $i += 6) {
        if ($i == 0) {
            $index = "cielo_00_06";
        } elseif ($i == 6) {
            $index = "cielo_06_12";
        } else {
            $index = "cielo_$i" . "_" . ($i + 6);
        }

        if ($tomorrow && $index == "cielo_18_24") {
            $datos[$index] .= "n";
        }

        switch ($datos[$index]) {

            case "11":
                $resultados[$i] = "despejado.svg";
                break;
            case "11n":
                $resultados[$i] = "despejado_noche.svg";
                break;

            case "12":
            case "13":
            case "14":
                $resultados[$i] = "nublado_parcial.svg";
                break;

            case "12n":
            case "13n":
            case "14n":
                $resultados[$i] = "nublado_parcial_noche.svg";
                break;

            case "15":
            case "16":
            case "17":
            case "23":
            case "24":
                $resultados[$i] = "nublado.svg";
                break;

            case "15n":
            case "16n":
            case "17n":
            case "23n":
            case "24n":
                $resultados[$i] = "nublado_noche.svg";
                break;


            case "25":
            case "25n":
            case "26":
            case "26n":
            case "43":
            case "43n":
            case "44":
            case "44n":
            case "45":
            case "45n":
            case "46":
            case "46n":
            case "54":
            case "54n":
                $resultados[$i] = "lluvioso.svg";
                break;


            case "33":
            case "33n":
            case "34":
            case "34n":
            case "35":
            case "36":
            case "71n":
            case "72":
            case "72n":
            case "73":
            case "73n":
            case "74":
            case "74n":
                $resultados[$i] = "lluvioso_nevar.svg";
                break;

            case "51":
            case "52":
            case "53":
            case "54":
            case "61":
            case "62":
            case "63":
            case "64":
                $resultados[$i] = "tormenta.svg";
                break;

            case "51n":
            case "52n":
            case "53n":
            case "54n":
            case "61n":
            case "62n":
            case "63n":
            case "64n":
                $resultados[$i] = "tormenta_noche.svg";
                break;

            default:
                break;
        }
    }

    return $resultados;
}


/** index indica el index que estamos trabajando del array global. Pendiente.*/
function recursiveDatos($index, $datosArray)
{

    if ($datosArray[$index]['padre'] == 0) {
        //condicion de parada
        return array($datosArray[$index]['id_cat'] => array(0 => $datosArray[$index]));
    } else {

        $resultArray = recursiveDatos($index + 1, $datosArray);
    }
}



/** funcion para traducir el estado a una string identificativa. Se traduce según el idioma.
 * @param string $estado Codigo del estado tal cual viene de la peticion
 * @return string Devuelve una string con el texto asociado al codigo para el estado 
 */
function vuelosEstado($estado)
{

    $estadosName['es'] = array("S" => "Programado", 'A' => 'Activo', 'C' => 'Cancelado', 'D' => 'Desviado', 'DN' => 'Pendiente de información', 'L' => 'En tierra', 'NO' => 'No operativo', 'R' => 'Redireccionado', 'U' => 'Desconocido');
    $estadosName['en'] = array("S" => "", 'A' => '', 'C' => '', 'D' => '', 'DN' => '', 'L' => '', 'NO' => '', 'R' => '', 'U' => '');
    $estadosName['gr'] = array("S" => "", 'A' => '', 'C' => '', 'D' => '', 'DN' => '', 'L' => '', 'NO' => '', 'R' => '', 'U' => '');
    $estadosName['rs'] = array("S" => "", 'A' => '', 'C' => '', 'D' => '', 'DN' => '', 'L' => '', 'NO' => '', 'R' => '', 'U' => '');

    return $estadosName['es'][$estado];
}

/** obtenemos las categorias (un array) a partir de la sectionId 
 * @param string $categoriaId identificador de la seccion
 * @param string $conComercios Indica si se deben devolver solo las secciones que tengan comercios
 * @return array Devuelve un array con los datos de las seciones pertenecientes a la categoria dada. 
 *               El array tiene la estructura array(id_seccion, id_categoria, nombre, idioma)
 */
function totem_getSectionsInCategoria($categoriaId, $conComercios = false)
{
    $idioma = $_SESSION['idioma'];

    if ($conComercios) {   //solo la ssecciones definidsa y con comercios asociados.
        $queryString = "SELECT DISTINCT comercio_seccion.* 
                            FROM comercio_seccion, comercio, 
                                 comercio_centro as t3,
                                 comercio_centro_contrato as t4
                                WHERE comercio_seccion.id_categoria='$categoriaId'
                                AND comercio_seccion.idioma='$idioma'
                                AND comercio.id_seccion=comercio_seccion.id_seccion
                                AND comercio.id_comercio = t3.id_comercio
                                AND t3.id = t4.id_comercio_centro
                                AND t4.valido = 1
                                AND t4.fecha_fin > NOW()";
    } else {   //devolvemos todas las secciones definidas tengan o no comercios asociados
        $queryString = "SELECT * FROM comercio_seccion 
                        WHERE id_categoria='$categoriaId'
                        AND idioma='$idioma'";
    }

    $db = new MySQL();

    $rec = $db->consulta($queryString);
    $datos;
    while ($tArray = $db->fetch_array($rec)) {
        $datos[] = $tArray;
    }
    return $datos;
}

/**Nueva seccion que hacer generica Aythami

/** obtenemos las categorias (un array)  
 * @param string $conComercios Indica si se deben devolver solo las secciones que tengan comercios
 * @return array Devuelve un array con los datos de todas las seciones
 *               El array tiene la estructura array(id_seccion, id_categoria, nombre, idioma)
 */
function totem_getAllSections()
{
    $idioma = $_SESSION['idioma'];

    $queryString = "SELECT DISTINCT comercio.* , t4.tipo, nombre_categoria, logo 
                            FROM comercio_seccion, comercio, comercio_categoria, 
                                 comercio_centro as t3,
                                 comercio_centro_contrato as t4
                            WHERE comercio_seccion.idioma='$idioma'
                            AND comercio_seccion.id_categoria = comercio_categoria.id_categoria
                            AND comercio.id_seccion=comercio_seccion.id_seccion
                            AND comercio.id_comercio = t3.id_comercio
                            AND t3.id = t4.id_comercio_centro
                            AND t4.valido = 1
                            AND t4.fecha_fin > NOW()
                            order by t4.tipo DESC, comercio.nombre";
    $db = new MySQL();

    $rec = $db->consulta($queryString);
    $datos;

    while ($tArray = $db->fetch_assoc($rec)) {
        $datos[] = $tArray;
    }
    return $datos;
}

/**Nueva seccion que hacer generica Salva

/** obtenemos las rutas (un array)  
 * @param string 
 * @return array toda la información de las rutas
 */
function totem_getRutas($id_municipio = null, $id_ruta = null)
{

    $idioma = $_SESSION['idioma'];

    $queryString = "SELECT DISTINCT conoce_sitios_interes.* ,   conoce_sitios_rutas_contenido.*, conoce_sitios_rutas.*, conoce_sitios_interes_contenido.*
                        FROM conoce_sitios_interes, conoce_sitios_rutas_contenido, conoce_sitios_rutas, conoce_sitios_interes_contenido
                        where conoce_sitios_interes.id = conoce_sitios_rutas.id_sitio
                        AND conoce_sitios_rutas.id_sitio=conoce_sitios_rutas_contenido.id_sitio_ruta
                        AND conoce_sitios_interes_contenido.id_sitio=conoce_sitios_rutas.id_sitio";

    if ($id_ruta != null) $queryString .=  " AND conoce_sitios_interes.id = $id_ruta";
    if ($id_municipio != null) $queryString .=  " AND conoce_sitios_interes.id_localidad = $id_municipio";


    $db = new MySQL();

    $rec = $db->consulta($queryString);
    $datos;

    while ($tArray = $db->fetch_assoc($rec)) {
        $datos[] = $tArray;
    }
    return $datos;
}






/** 
 * 
 * @param type $padreId
 * @return type array 
 *  obtenemos las categorias (un array) que esten presentes en los contenidos disponibles del hotel
 */
function totem_getSectionsForHotel($padreId)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    $id_idioma = $_SESSION['idioma']; //aplico el id_centro para asegurar que borra de su centro

    $sql = "
        SELECT t1.* FROM 
            contenido_categorias as t1, 
            contenidos as t2 
                WHERE  t1.`id_idioma` = $id_idioma 
                AND t1.`id_centro` = $id_centro 
                AND t1.`padre` = $padreId 
                AND t1.`id_cat` = t2.id_categoria
                AND t2.activo > 0
        UNION
        SELECT DISTINCT t4.*
            FROM `contenido_categorias` AS t4, 
            contenidos AS t5, 
            contenido_categorias AS t6
                WHERE t4.`id_idioma` = $id_idioma
                AND t4.`id_centro` = $id_centro
                AND t5.activo > 0
                AND t4.id_cat = t6.padre
                AND t4.padre <> 0
                AND t6.id_cat = t5.id_categoria
                AND t5.activo > 0
                ORDER BY orden ASC";

    //    echo $sql;
    $consulta = $db->consulta($sql);

    $i = 0;
    while ($fila = $db->fetch_array($consulta)) {
        $datos[$i] = $fila;
        $i++;
    }
    return $datos;
}

/** 
 * funcion para obtener todos los comercios que pertenen a una secion, se obtienen
 * a partir del id de dicha seccion.
 * @param string $seccionId id de la seccion
 * @return array un array con los campos de la tabla comercio_secciones (id, id_comercio, id_seccion, id_idioma)
 */
function totem_getComerciosForSection($seccionId)
{
    $db = new MySQL();

    //$id_centro = $_SESSION['id_centro'];
    $id_idioma = $_SESSION['idioma']; //aplico el id_centro para asegurar que borra de su centro
    $id_centro = $_SESSION['id_centro'];

    $sql = "SELECT t1.*, 
                   t2.nombre_seccion, t2.class_icon,
                   t3.logo, t3.baner, t3.fondo,
                   t4.tipo
                FROM comercio as t1, 
                     comercio_seccion as t2, 
                     comercio_centro as t3,
                     comercio_centro_contrato as t4
                WHERE t1.id_seccion='$seccionId'
                AND t2.id_seccion='$seccionId'
                AND t2.idioma='$id_idioma'
                AND t1.id_comercio = t3.id_comercio
                AND t3.id_centro = '$id_centro'
                AND t4.id_comercio_centro = t3.id
                AND t4.valido = 1
                AND t4.fecha_fin > NOW()
                ";
    //echo $sql;
    $consulta = $db->consulta($sql);

    $datos = array();
    while ($tArray = $db->fetch_array($consulta)) {
        $datos[] = $tArray;
    }
    return $datos;
}

/** * @param type $contenidoId
 * @return type
 * Funcion que devuelve los contenidos de una categoria, mas adelante tendremos que modificarla para que liste todos los contenidos
 */
/* ESTA FUNCION DEVUELVE UN LISTADO DE BLOQUES QUE SERÁN HIJOS DE CONTENIDOID + SU PROPIO CONTENIDO
 * EN CASO DE TENERLO a partir de esta funcion se decidira que hacer para el caso de un solo elemento o varios
 */
function totem_getContenido($contenidoId)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    $id_idioma = $_SESSION['idioma']; //aplico el id_centro para asegurar que borra de su centro

    //    $sql = "SELECT id_cat, nombre, content, class_icon, foto_cabecera, id_contenido, id_categoria
    //                FROM `contenidos` as t1, `contenido_categorias` as t2 
    //                WHERE t1.id_categoria=$contenidoId AND (t1.id_categoria=t2.id_cat OR t1.id_categoria=t2.padre )
    //                AND t1.id_idioma=t2.id_idioma AND t1.id_centro=$id_centro AND t1.id_idioma=$id_idioma";

    $sql = "SELECT  id_cat, nombre, content, class_icon, foto_cabecera, id_contenido, id_categoria, t1.nombre as nombre_padre,  t2.clase_extra
            FROM contenido_categorias as t1, 
                 contenidos as t2 
                    WHERE  t1.`id_idioma` = $id_idioma 
                    AND t1.`id_centro` = $id_centro
                    AND t2.`id_idioma` = $id_idioma 
                    AND t2.`id_centro` = $id_centro
                    AND t1.`id_cat` = '$contenidoId'
                    AND t2.id_categoria = t1.id_cat
                    AND t2.activo <> 0
        UNION
        SELECT *
            FROM ( 
                SELECT DISTINCT t6.id_cat, t6.nombre, content, t6.class_icon, t5.foto_cabecera, t5.id_contenido, t5.id_categoria, t4.nombre as nombre_padre, t5.clase_extra 
                    FROM `contenido_categorias` AS t4, contenidos AS t5, contenido_categorias AS t6
                            WHERE t5.`id_idioma` = $id_idioma
                            AND t5.`id_centro` = $id_centro
                            AND t6.id_idioma = $id_idioma
                            AND t6.id_centro = $id_centro
                            AND t4.id_idioma = $id_idioma
                            AND t4.id_centro = $id_centro
                            AND t4.id_cat = $contenidoId
                            AND t4.id_cat = t6.padre
                            AND t4.padre <> 0
                            AND t6.id_cat = t5.id_categoria
                            AND t5.activo <> 0
                            ORDER BY t6.orden
            ) as table1";


    //echo $sql;
    //$sql = "SELECT * FROM `contenidos` WHERE id_categoria=$contenidoId AND id_centro=$id_centro AND id_idioma=$id_idioma";
    //echo $sql;
    $consulta = $db->consulta($sql);
    if (isset($_GET['debug']) && $_GET['debug']) {
        error_log("[totem_getContenido] contenidoId=".$contenidoId." id_centro=".$id_centro." id_idioma=".$id_idioma);
    }

    $datos;
    while ($tArray = $db->fetch_array($consulta)) {
        $datos[] = $tArray;
    }
    if (isset($_GET['debug']) && $_GET['debug']) {
        $cnt = is_array($datos) ? count($datos) : 0;
        error_log("[totem_getContenido] filas=".$cnt);
    }

    return $datos;
}


function totem_getContenidoEspecifico($contenidoId)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    $id_idioma = $_SESSION['idioma']; //aplico el id_centro para asegurar que borra de su centro

    $sql = "SELECT id_cat, nombre, content, class_icon, foto_cabecera, t1.clase_extra
                FROM `contenidos` as t1, `contenido_categorias` as t2 
                    WHERE t1.id_contenido=$contenidoId 
                    AND activo=1
                    AND t1.id_categoria=t2.id_cat
                    AND t1.id_centro=$id_centro
                    AND t1.id_idioma=$id_idioma
                    AND t1.id_idioma=t2.id_idioma ";
    //    echo $sql;

    //echo $sql;
    //$sql = "SELECT * FROM `contenidos` WHERE id_categoria=$contenidoId AND id_centro=$id_centro AND id_idioma=$id_idioma";
    //echo $sql;
    $consulta = $db->consulta($sql);
    if (isset($_GET['debug']) && $_GET['debug']) {
        error_log("[totem_getContenidoEspecifico] contenidoId=".$contenidoId." id_centro=".$id_centro." id_idioma=".$id_idioma);
    }

    $datos;
    while ($tArray = $db->fetch_array($consulta)) {
        $datos[] = $tArray;
    }
    if (isset($_GET['debug']) && $_GET['debug']) {
        $cnt = is_array($datos) ? count($datos) : 0;
        error_log("[totem_getContenidoEspecifico] filas=".$cnt);
    }

    return $datos;
}


function totem_getContenidoEspecifico_nuevo($contenidoId)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    $id_idioma = $_SESSION['idioma']; //aplico el id_centro para asegurar que borra de su centro

    $sql = "SELECT id_cat, nombre, content, class_icon, foto_cabecera, t1.clase_extra
                FROM `contenidos` as t1, `contenido_categorias` as t2
                    WHERE t1.id_contenido=$contenidoId
                    AND t1.id_categoria=t2.id_cat
                    AND t1.id_centro=$id_centro
                    AND t1.id_idioma=$id_idioma
                    AND t1.id_idioma=t2.id_idioma ";
    //    echo $sql;

    //echo $sql;
    //$sql = "SELECT * FROM `contenidos` WHERE id_categoria=$contenidoId AND id_centro=$id_centro AND id_idioma=$id_idioma";
    //echo $sql;
    $consulta = $db->consulta($sql);
    if (isset($_GET['debug']) && $_GET['debug']) {
        error_log("[totem_getContenidoEspecifico_nuevo] contenidoId=".$contenidoId." id_centro=".$id_centro." id_idioma=".$id_idioma);
    }

    $datos;
    while ($tArray = $db->fetch_array($consulta)) {
        $datos[] = $tArray;
    }
    if (isset($_GET['debug']) && $_GET['debug']) {
        $cnt = is_array($datos) ? count($datos) : 0;
        error_log("[totem_getContenidoEspecifico_nuevo] filas=".$cnt);
    }

    return $datos;
}




/**
 * 
 * @param int $limite Indica el numero de elementos que deseamos obtener. Para obtenerlas todas solo 
 *                      poner un numero lo suficientemente grande. (default 3)
 * @param string $tipo Indica el tipo de galeria que deseamos obtener. Debe ser un tipo definido
 *                      en la tabla galerias. (default false)
 * @param int $visible Indica si se desean obtener solo las galerias visibles o todas en general.
 *                      (default true)
 * @return array Devuelve un array multidimensional con los datos solicitados.
 */
function totem_getGalerias($limite = "5", $tipo = false, $visible = true)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    $idioma = $_SESSION['idioma'];

    $query = "SELECT t1.id, t2.nombre FROM
                galeria as t1, galeria_idioma as t2
                WHERE t1.id_centro='$id_centro'
                AND t1.id = t2.id_galeria
                AND t2.id_idioma = '$idioma'";

    if ($tipo) {
        $query .= " AND t1.tipo='$tipo'";
    }

    if ($visible) {
        $query .= " AND visible=1";
    }

    $query .= " ORDER BY tipo_especifico, id ASC LIMIT $limite";

    $result = $db->consulta($query);
    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}

/**
 * 
 * @param string $seccion indica la seccion sobre la que se ha echo click.
 * @param string $subseccion indica la subseccion sobre la que se ha echo click.
 * @param string $identificador identificador del tercer nivel para la seccion sobre la que se ha echo click.
 */
function registrarLog($seccion, $subseccion = false, $identificador = false, $obs = false)
{

    if (file_exists('/var/www/html/twistic/config/no_add_click.ini')) {
        return;
    }

    $db_local = new MySQL_local();
    $id_centro = $_SESSION['id_centro'];
    $idioma = $_SESSION['idioma'];

    $subseccion = $subseccion ? $subseccion : "";
    $identificador = $identificador ? $identificador : "";
    $obs = $obs ? $obs : "";
    $query = "INSERT INTO log (id,id_centro,idioma,seccion,subseccion,identificador, observaciones) 
        VALUES ( NULL, $id_centro, $idioma, '$seccion', '$subseccion', '$identificador', '$obs')";

    return $db_local->consulta($query);
}




/** funcion para obtener los datos del banner superior. Puede ser una imagen o un video según especificado 
 * en el servidor. Esta funcion utiliza como parametro el tipo de banner y la seccion, asi como el id del centro
 * @param string $ubicacion Indica si el banner es de tipo superior o inferior
 * @param int (opcional) $activo indica si el listado de banners debe ser solo los activos o no. Por defecto solo los activos.
 * @return array devuelve un array secuencial con los datos de banner  
 */
function totem_obtener_listado_banner($ubicacion, $activo = 1)
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro']; //variable que controla el centro necesaria en todos los casos
    $idioma = $_SESSION['idioma'];

    $query = "SELECT t1.clase, t1.enlace, t1.tipo_banner, t1.path, t2.file FROM
                banner as t1, banner_idioma as t2
                    WHERE t1.ubicacion = '$ubicacion'
                    AND t1.id_centro = $id_centro
                    AND t1.activo = $activo
                    AND t1.id = t2.id_banner
                    AND t2.id_idioma = $idioma
                    AND t1.date_start < NOW()
                    AND t1.date_end > NOW()
                    ORDER BY orden ASC";
    $result = $db->consulta($query);
    while ($fila = $db->fetch_array($result)) {
        $datos[] = $fila;
    }

    return $datos;
}

function totem_getDatosCentro()
{
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro']; //variable que controla el centro necesaria en todos los casos
    $idioma = $_SESSION['idioma'];

    $query = "SELECT * FROM centro WHERE id_centro='$id_centro'";

    $datos = array();

    $result = $db->consulta($query);

    while ($fila = $db->fetch_array($result)) {
        $datos = $fila;
    }


    return $datos;
}

/**
 *  funcion encargada de obtener todos los datos de sitios de interes que estan presentes ne el municipio pasado por parametro
 * @param type $cod indica el codigo del municipio, tal como aparece en la tabla
 * @return array devuelve un array con la estructura: <br>
 * t[] = array('id', 'nombre', 'descripcion', 'lat', 'long', 'tipo', 'icon_class', 'imagen', 'direccion');
 */
function totem_getSitiosInteres($cod)
{
    $datos = array();
    $db = new MySQL();
    $idioma = $_SESSION['idioma'];
    $cod = $cod;

    $query = "SELECT t1.*, t2.nombre, t2.descripcion  FROM conoce_sitios_interes as t1, conoce_sitios_interes_contenido as t2
                WHERE t1.id_localidad = '$cod'
                  AND t1.id = t2.id_sitio
                  AND t2.id_idioma=$idioma";

    $result = $db->consulta($query);

    while ($fila = $db->fetch_array($result)) {
        $datos[] = array(
            "id" => $fila['id'],
            "nombre" => $fila['nombre'],
            "descripcion" => $fila['descripcion'],
            "lat" => $fila['lat'],
            "long" => $fila['long'],
            "tipo" => $fila['tipo'],
            "icon_class" => $fila['icon_class'],
            "direccion" => $fila['direccion'],
            "imagen" => $fila['imagen']
        );
    }

    return $datos;
}

/**
 * funcion encargada de obtener todos los detalles para una ruta indicada por parametro.
 * @param type $rutaId
 * @return array con la estructura:<br>
 * array[] = array (lat, long,nombre,descripcion,id (id de la ruta), id_sitio,itinerario,nivel,duracion,recorrido,desniveles,que_descrubir, mapa);
 */
function totem_getRutaDetail($rutaId)
{
    $datos = array();

    $db = new MySQL();
    $idioma = $_SESSION['idioma'];

    $query = "SELECT t1.lat, t1.long, t1.imagen,
                     t2.nombre, t2.descripcion,
                     t3.*,
                     t4.*
                FROM conoce_sitios_interes as t1, 
                     conoce_sitios_interes_contenido as t2,
                     conoce_sitios_rutas as t3,
                     conoce_sitios_rutas_contenido as t4
                WHERE t1.id=$rutaId
                  AND t3.id_sitio = t1.id
                  AND t3.id_sitio = t2.id_sitio
                  AND t2.id_idioma = $idioma
                  AND t3.id = t4.id_sitio_ruta
                  AND t4.id_idioma = $idioma";

    $result = $db->consulta($query);

    $datos = array();
    while ($fila = $db->fetch_array($result)) {
        $datos = $fila;
    }

    return $datos;
}


function cambia_src($cadena, $dir)
{
    return str_replace('src="', 'src="' . $dir, $cadena);
}

function cambia_src2($cadena, $dir)
{
    return str_replace('src="', 'src="../../' . $dir, $cadena);
}

function cambia_hr($cadena)
{
    return str_replace('<hr>', '<br/><br/></br><hr>', $cadena);
}


/****************************************************************/
//Funcion que devuelve el texto de sugerencias y comentarios

function get_texto_sugerencia($id_idioma = 1)
{

    $db = new MySQL_local();

    $query = "SELECT contenido 
          FROM sugerencias_preguntas
          WHERE id_idioma = $id_idioma";

    $result = $db->consulta($query);

    return $db->fetch_array($result);
}


/********************* Cuestionario *******************************************/


function get_secciones_cuestionario($id_idioma = 1)
{

    $db = new MySQL();

    $query = "SELECT *
              FROM cuestionario_seccion as t1,
              cuestionario_seccion_idioma as t2
              WHERE id_idioma = $id_idioma
              and t1.id_seccion = t2.id_seccion
              AND activa ='1'
              ORDER BY orden";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}


function get_secciones_cuestionario_preguntas($id_idioma = 1, $id_seccion)
{

    $db = new MySQL();

    $query = "SELECT *
              FROM cuestionario_preguntas as t1,
              cuestionario_preguntas_idioma as t2
              WHERE id_idioma = $id_idioma
              and t1.id_seccion = $id_seccion
              and t1.id_pregunta = t2.id_pregunta 
              and t1.formato = 0
              ORDER BY orden, formato";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}


function get_nombre_observacion_seccion($id_idioma = 1, $id_seccion)
{

    $db = new MySQL();

    $query = "SELECT t3.nombre, t2.id_pregunta
              FROM cuestionario_seccion as t1,
              cuestionario_preguntas as t2,
              cuestionario_preguntas_idioma as t3
              WHERE t3.id_idioma = $id_idioma
              and t1.id_seccion = $id_seccion
              and t1.id_seccion = t2.id_seccion
              and t2.id_pregunta = t3.id_pregunta
              AND t2.formato ='1'";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}



//Funcion auxiliar para convertir la clase stdobject en array

function objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

//funcion que devuelve las coordenadas del hotel
function coordenadas_hotel($id_centro)
{
    $db = new MySQL();

    $query = "SELECT latitud , longitud FROM centro WHERE id_centro = $id_centro";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}

//Devuelve el nombre del lugar para ponerlo en la pantalla principal del totem
function get_nombre_lugar($id_lugar, $id_idioma = 1)
{
    $db = new MySQL_comun();

    $query = "SELECT nombre_lugar FROM `lugares_info_general` WHERE `id_lugar` = $id_lugar AND `id_idioma` = $id_idioma ";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}



/***********************************************************/
/************************** Informacion*********************/
/***********************************************************/




function get_info($id_idioma = 1, $id_tipo = 1)
{
    $db = new MySQL();

    $query = "SELECT contenido FROM `informacion` WHERE `id_tipo` = $id_tipo AND `id_idioma` = $id_idioma ";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}



/***********************************************************/
/************************** Screensaver *********************/
/***********************************************************/



function get_screensaver($id_centro)
{
    $db = new MySQL();

    $query = "SELECT * FROM `centro` WHERE `id_centro` = $id_centro";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos = $row;
    }
    return $datos;
}



///////////////////////////////////////////////////////////////////
// Comprueba si este totem tiene que hacer //

function configuracion_totem($id_centro)
{
    $db = new MySQL();

    $query = "SELECT * FROM `centro` WHERE `id_centro` = $id_centro";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos = $row;
    }
    return $datos;
}

function get_piscinas()
{
    $db = new MySQL();

    $query = "SELECT * FROM `piscinas` WHERE 1";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}
