<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include_once '../totem/lib/class.TemplatePower.inc.php';
include_once 'lib_funciones.php';
require_once '../../../config/MySQL.php';
include_once 'ajax/ajaxDatos.php';
include_once '../totem/lib/lib_tiempo.php';
include_once 'lib/getid3/getid3.php';

session_start();
date_default_timezone_set('Atlantic/Canary');

if (isset($_POST["config"])) {
    $_SESSION['fichero_config'] = $_POST["config"];
} else {
    if (! isset($_SESSION['fichero_config'])) {
        $_SESSION['fichero_config'] = 'config.ini';
    }
}

$array_idiomas = [1, 2, 3, 4];

$config = parse_ini_file("../../../config/".$_SESSION['fichero_config'], true);
$id_centro = $config['centro']['id_centro'];
$_SESSION['id_centro'] = $id_centro;

if (! isset($_SESSION['idioma'])) {
    $_SESSION['idioma'] = 2;
}
$_SESSION['num_idiomas'] = 2;

$tpl_index = new TemplatePower("inicio.html", T_BYFILE);
$tpl_index->prepare();

$id_cache = date('YmdHis');
$tpl_index->assign('id_cache', $id_cache);
$tpl_index->assign('id_centro', $id_centro);


$tpl_index->assign('nombre_css', 'custom');


if (isset($_POST['funcion'])) {
    if ($_POST['funcion'] == 'sacarAnimacion') {
        getActividades();
        die();
    }

    if ($_POST['funcion'] == 'sacarTiempo') {
        getTiempo();
        die();
    }
}

//INSERTAR VIDEOS Y BANNERS A LA BASE DE DATOS

//insertarVideosDb();
//insertarBannersDb();
//echo "OK";
//die();

//MOSTRAR VIDEOS
$arrayVideos = leerVideosDb();
$dia = date('N');
$contenido_videos = null;

//echo "<pre>";
//print_r($arrayVideos);
//echo "</pre>";
//die;

$getID3 = new getID3;
foreach ($arrayVideos as $videos) {


    $programacion_semanal = explode(',', $videos['programacion_semanal']);

    if (in_array($dia, $programacion_semanal)) {

        $src = '../../../contenido_proyectos/pacoche/centro_'.$id_centro.'/feelchannel/videos/'.$videos['contenido'];

        $file = $getID3->analyze($src);

        if ($videos['etiqueta'] != "") {
            $etiquetas = explode(",", $videos['etiqueta']);
            $contenido_videos[] = [
                "nombre" => $videos['contenido'],
                "src" => $src,
                "etiquetas" => $etiquetas,
                "destacado" => $videos['destacado'],
                "duracion" => $file['playtime_seconds'] * 1000,
            ];
        } else {
            $contenido_videos[] = [
                "nombre" => $videos['contenido'],
                "src" => $src,
                "etiquetas" => "",
                "destacado" => $videos['destacado'],
                "duracion" => $file['playtime_seconds'] * 1000,

            ];
        }
    }
}

//echo "<pre>";
//print_r($contenido_videos);
//echo "</pre>";
//die;

//echo count(explode(",",$lista_videos)) . " ------ " . count(explode(",",$contenido_etiquetas));

$tpl_index->newBlock("videos");
//$tpl_index->assign('lista_videos', $lista_videos);
//$tpl_index->assign('nombre_destacado', $destacado);
$tpl_index->assign('contenido_videos', json_encode($contenido_videos));

$tpl_index->gotoBlock("_ROOT");

//MOSTRAR IMAGENES
$arrayImagenes = leerImagenesDb();
$contador = 0;

foreach ($arrayImagenes as $imagenes) {

    $programacion_semanal_imagenes = explode(',', $imagenes['programacion_semanal']);

    if (in_array($dia, $programacion_semanal_imagenes)) {

        $tpl_index->newBlock("imagenes");
        $lista_imagenes = "../../../contenido_proyectos/pacoche/centro_".$id_centro."/feelchannel/banner_lateral/".$imagenes['contenido'];
        $tpl_index->assign('lista_imagenes', $lista_imagenes);
        $tpl_index->assign('duracion', $imagenes['duracion']);
        $tpl_index->assign('id_hidden', $contador);
        $contador++;
    }
}

//$lista_imagenes = trim($lista_imagenes, ',');

$tpl_index->gotoBlock("_ROOT");

//MOSTRAR CARTELES SHOW
$array_carteles = mostrarCartelesShow();

foreach ($array_carteles as $carteles) {

    if ($carteles['foto'] != null) {
        $tpl_index->newBlock("imagenes_carteles");

        $lista_imagenes = "../../../contenido_proyectos/pacoche/centro_".$id_centro."/imagenes/agenda/".$carteles['foto'];
        $tpl_index->assign('lista_imagenes', $lista_imagenes);
        $tpl_index->assign('estilo_cartel', "css_imagen_cartel");

        setlocale(LC_ALL, "es_ES.UTF-8");
        define("CHARSET", "UTF-8");
        setlocale(LC_TIME, 'spanish');

        $date = date($carteles['fecha']." ".$carteles['hora_inicio']);
        $fecha = strftime("%d %B %H:%M", strtotime($date));
        $tpl_index->assign('fecha_hora', strtoupper($fecha));
        $tpl_index->assign('nombre_show', strtoupper($carteles['nombre']));
    }
}
$tpl_index->gotoBlock("_ROOT");

$array_tomorrow = ['', 'Mañana', 'Tomorrow', 'Morgen', 'Demain'];
$array_hoy = ["", "Hoy", "Today", "Heute", "Aujourd'hui"];

//Mostar Animacion
foreach ($array_idiomas as $id_idioma) {

    $tpl_index->newBlock("contenedor_animacion");
    $tpl_index->assign('id_idioma', $id_idioma);

    $animacion = mostrarAnimacion($id_idioma);

    foreach ($animacion as $row) {

        $tpl_index->newBlock("animacion");
        $tpl_index->assign('nombre_animacion', $row['nombre']);
        $tpl_index->assign('hora_inicio', $row['hora_inicio']);
        $tpl_index->assign('hora_final', $row['hora_final']);

        if ($row['fecha'] != '') {
            $tpl_index->assign('fecha', $array_tomorrow[$id_idioma]);
        } else {
            $tpl_index->assign('fecha', $array_hoy[$id_idioma]);
        }
    }

    $tpl_index->gotoBlock("_ROOT");
}
$tpl_index->gotoBlock("_ROOT");

$tpl_index->assign('idiomaActual', $_SESSION['idioma']);

//Sacar informacion el tiempo
$info_tiempo = mostrarTiempo();
foreach ($info_tiempo as $json_tiempo) {

    $json_tiempo = json_decode($info_tiempo['json_basico'], true);
    $tpl_index->newBlock("tiempo");
    $tpl_index->assign('temperatura_minima', round($json_tiempo['temperatura_minima']));
    $tpl_index->assign('temperatura_maxima', ceil($json_tiempo['temperatura_maxima']));
    $tpl_index->assign('humedad', $json_tiempo['humedad']);
    $tpl_index->assign('icono', $json_tiempo['icono']);

    $tpl_index->assign('viento', 3.60 * round($json_tiempo['viento']));
}
$tpl_index->gotoBlock("_ROOT");

$tpl_index->printToScreen();
