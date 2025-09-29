<?php

//SACAR ANIMACION

function mostrarAnimacion($id_idioma)
{

    $db = new MySQL();

    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    $queryString = "SELECT eventos_contenido.title AS nombre, eventos_lugares_tiene_idiomas.nombre AS lugar, eventos_categorias.id_cat, eventos_categorias.nombre AS categoria, eventos.hora_ini AS hora_inicio, eventos.hora_fin AS hora_final
                    FROM eventos_contenido
                    INNER JOIN eventos ON eventos_contenido.id_evento = eventos.id_evento
                    INNER JOIN eventos_categorias ON eventos.id_cat = eventos_categorias.id_cat
                    INNER JOIN eventos_ocurrencias ON eventos.id_evento = eventos_ocurrencias.id_evento
                    INNER JOIN eventos_lugares ON eventos_contenido.id_lugar = eventos_lugares.id_lugar
                    INNER JOIN eventos_lugares_tiene_idiomas ON eventos_contenido.id_lugar = eventos_lugares_tiene_idiomas.id_lugar
                    WHERE fecha = '".$fecha_actual."'
                    AND eventos_contenido.id_idioma = '".$id_idioma."'
                    AND eventos_categorias.id_idioma = ".$id_idioma." 
                    AND eventos_lugares_tiene_idiomas.id_idioma = ".$id_idioma." 
                    AND eventos.estado > 0
                    AND eventos.hora_ini >= '".$hora_actual."'
                    ORDER BY eventos.hora_ini ASC LIMIT 3";

    $query = $db->consulta($queryString);
    $tArray = $db->getAllArray($query);

    if (count($tArray) < 3) {
        $tArray_aux = incluirAnimacion($id_idioma, $tArray);
        if (count($tArray_aux) > 0) {
            $tArray = $tArray_aux;
        }
    }

    //echo '<pre>';
    //print_r($tArray);
    //
    //die;

    return $tArray;
}

function incluirAnimacion($id_idioma, $tArray)
{
    $db = new MySQL();

    $fecha_actual = date('Y-m-d', strtotime('+1 day'));

    $limite = 3 - count($tArray);

    //echo $limite . " limite";

    //echo $fecha_actual; die();

    $queryString = "SELECT eventos_contenido.title AS nombre, eventos.hora_ini AS hora_inicio, eventos.hora_fin AS hora_final, SUBSTRING(eventos_ocurrencias.fecha,6) AS fecha
                    FROM eventos_contenido
                    INNER JOIN eventos ON eventos_contenido.id_evento = eventos.id_evento
                    INNER JOIN eventos_ocurrencias ON eventos.id_evento = eventos_ocurrencias.id_evento
                    INNER JOIN eventos_lugares ON eventos_contenido.id_lugar = eventos_lugares.id_lugar
                    WHERE fecha = '".$fecha_actual."'
                    AND eventos_contenido.id_idioma = '".$id_idioma."'
                    AND eventos.estado > 0
                    ORDER BY eventos.hora_ini ASC LIMIT $limite";

    //echo $queryString . "<br>";
    //die();
    //echo $tArray; die();

    $query = $db->consulta($queryString);
    $array_aux = $db->getAllArray($query);

    if (count($tArray) > 0) {
        $tArray = array_merge($tArray, $array_aux);
    } else {
        $tArray = $array_aux;
    }

    //print_r($tArray);

    return $tArray;
}

//CARTELES SHOW
function mostrarCartelesShow()
{
    $db = new MySQL();

    $queryString = "SELECT eventos_lugares.lugar AS categoria, eventos_contenido.title AS nombre, eventos.hora_ini AS hora_inicio, 
                    eventos.hora_fin AS hora_final, eventos_ocurrencias.fecha AS fecha, eventos_contenido.foto_evento AS foto
                    FROM eventos_contenido
                    INNER JOIN eventos ON eventos_contenido.id_evento = eventos.id_evento
                    INNER JOIN eventos_ocurrencias ON eventos.id_evento = eventos_ocurrencias.id_evento
                    INNER JOIN eventos_lugares ON eventos_contenido.id_lugar = eventos_lugares.id_lugar
                    WHERE fecha = '".date('Y-m-d')."'
                    AND eventos_contenido.id_idioma = '2'
                    AND eventos.estado > 0
                    AND (
                        eventos_lugares.lugar = 'Música en vivo'
                        OR eventos_lugares.lugar = 'Espectáculos'
                    )
                    ORDER BY eventos.hora_ini ASC ";

//echo $queryString;

    $query = $db->consulta($queryString);

    while ($arrayImagenesShow = $db->fetch_assoc($query)) {

        $datos[] = $arrayImagenesShow;
    }

    return $datos;
}

function isJson($string)
{
    json_decode($string);

    return (json_last_error() == JSON_ERROR_NONE);
}

function mostrarTiempo()
{


    $url_api = "http://apiservicios.twisticdigital.com";

    if (! actualizado()) {
        addtime('clean');

        $resultados_json = file_get_contents($url_api."/meteo/getinfo/".ciudades_tiempo());

        // echo " / get: "; echo time();

        if ($resultados_json) {
            if (isJson($resultados_json)) {
                $resultados = json_decode($resultados_json, true);
                // echo "decode OK ";
                // echo " / decode: "; echo time();
                //-------------
                $consulta = "INSERT INTO `localidades_prevision` (`id`, `id_localidad`, `localidad_name`, `fecha`, `json_basico`, `json_total`) VALUES ";

                foreach ($resultados as $value) {
                    $consulta .= "('".implode("','", $value)."'),";
                }
                $consulta = substr_replace($consulta, ";", -1, 1);
                addtime($consulta);
                //-------------
            } else {
                exit();
            }
        }
    }

    $config = parse_ini_file("../../../config/".$_SESSION['fichero_config'], true);
    $id_localidad = $config['localidad']['id_localidad'];

    $fechaHora = date('Y-m-d H:i:s');

    if (strtotime($fechaHora) >= strtotime(date('Y-m-d 09:00:00')) && strtotime($fechaHora) < strtotime(date('Y-m-d 15:00:00'))) {
        $fechaHora = date('Y-m-d 09:00:00');
    } else {
        if (strtotime($fechaHora) >= strtotime(date('Y-m-d 15:00:00')) && strtotime($fechaHora) < strtotime(date('Y-m-d 21:00:00'))) {
            $fechaHora = date('Y-m-d 15:00:00');
        } else {
            if (strtotime($fechaHora) >= strtotime(date('Y-m-d 21:00:00')) || strtotime($fechaHora) < strtotime(date('Y-m-d 09:00:00'))) {
                if (strtotime($fechaHora) >= strtotime(date('Y-m-d 00:00:00'))) {
                    if (strtotime($fechaHora) >= strtotime(date('Y-m-d 07:00:00'))) {
                        $fechaHora = date('Y-m-d 09:00:00');
                    } else {
                        $fechaHora = date('Y-m-d 21:00:00', strtotime('-1 day'));
                    }
                } else {
                    $fechaHora = date('Y-m-d 21:00:00');
                }
            }
        }
    }

    $db = new MySQL();

    $queryString = "SELECT json_basico 
                FROM localidades_prevision 
                WHERE id_localidad = '".$id_localidad."'
                AND fecha = '".$fechaHora."' LIMIT 1";

//echo $queryString;

    $query = $db->consulta($queryString);

    while ($json_tiempo = $db->fetch_assoc($query)) {
        $datos = $json_tiempo;
    }

    return $datos;
}

function leerImagenesDb()
{

    $db = new MySQL();
    $datos = "";
    $fechaHora = date('Y-m-d H:i:s');

    $queryString = "SELECT * FROM tv_programacion_banners 
                    WHERE inicio <= '".$fechaHora."'
                    AND fin >= '".$fechaHora."'
                    AND activo = 1
                    ORDER BY orden ASC";

    $query = $db->consulta($queryString);

    while ($arrayContenido = $db->fetch_assoc($query)) {

        $datos[] = $arrayContenido;
    }

    return $datos;
}

function leerVideosDb()
{

    $db = new MySQL();
    $datos = "";
    $fechaHora = date('Y-m-d H:i:s');

    $queryString = "SELECT * FROM tv_programacion_videos as tv
                    WHERE tv.inicio <= '".$fechaHora."'
                    AND tv.fin >= '".$fechaHora."'
                    AND tv.activo = 1 
                    ORDER BY tv.orden ASC";
    //
    //echo $queryString;
    //die;

    $query = $db->consulta($queryString);

    while ($arrayContenido = $db->fetch_assoc($query)) {

        $datos[] = $arrayContenido;
    }

    return $datos;
}

function insertarVideosDb()
{

    $db = new MySQL();

    $videos_header = scandir("../../../contenido_proyectos/vistaflor/centro_".$_SESSION['id_centro']."/feelchannel/videos/");
    unset($videos_header[0], $videos_header[1]);
//print_r($videos_header); die;

    $lista_videos = null;
    $orden = 1;
    foreach ($videos_header as $video) {

        $queryString = "INSERT INTO tv_programacion_videos (contenido, inicio, fin, orden, activo, tipo, destacado)
                        VALUES ('".$video."', '2018-09-24 00:00:00', '2019-12-31 00:00:00', $orden, 1, 'video', 0); ";

        //echo $queryString;

        $db->consulta($queryString);

        $orden++;
    }
    //echo "OK";
}

function insertarBannersDb()
{

    $db = new MySQL();

    $imagenes_header = scandir("../../../contenido_proyectos/vistaflor/centro_".$_SESSION['id_centro']."/feelchannel/banner_lateral/");
    unset($imagenes_header[0], $imagenes_header[1]);
//print_r($videos_header); die;

    $lista_imagenes = null;
    $orden = 1;
    foreach ($imagenes_header as $imagen) {

        $queryString = "INSERT INTO tv_programacion_banners (contenido, inicio, fin, orden, activo, tipo, duracion)
                        VALUES ('".$imagen."', '2018-09-24 00:00:00', '2019-12-31 00:00:00', $orden, 1, 'jpg', 5); ";

        //echo $queryString;

        $db->consulta($queryString);

        $orden++;
    }
    //echo "OK";
}


