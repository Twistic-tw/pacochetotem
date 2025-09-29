<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once 'lib/class.TemplatePower.inc.php';
require_once '../../../config/MySQL.php';
require_once '../../../config/MySQL_local.php';

session_start();

$post = file_get_contents('php://input');
$data = json_decode($post, true);


// ----------------------------------------- CONFIG -----------------------------------------------
if (isset($_POST["config"])) {
    $_SESSION['fichero_config'] = $_POST["config"];
} else {
    if (!isset($_SESSION['fichero_config'])) {
        $_SESSION['fichero_config'] = 'config.ini';
    }
}

$config = parse_ini_file("../../../config/" . $_SESSION['fichero_config'], true);
$id_centro = $config['centro']['id_centro'];
$id_cadena = $config['cadena']['id_cadena'];
$_SESSION['id_centro'] = $id_centro;
$_SESSION['id_cadena'] = $id_cadena;



// ------------------------- BLOQUE BD COMUN --------------------------
$db = new MySQL();

$query = "SELECT *
          FROM bd_feeltourist_comun.bd_comun
          WHERE active = 1
          AND id_cadena = " . $_SESSION['id_cadena'];

$result = $db->consulta($query);
$bd_comun = $db->getAllArray($result)[0];

if (!empty($bd_comun)) {
    $centros = explode(',', $bd_comun['centros_activos']);
    if (in_array($_SESSION['id_centro'], $centros) || empty($bd_comun['centros_activos']) || $bd_comun['centros_activos'] == null) {
        // echo "MODO COMUN <br>";
        $_SESSION['bbdd_normal'] = $config['database']['db'];
        $_SESSION['bbdd_comun'] = $bd_comun['bd_name'];
        require_once 'lib/lib_general_comun.php';
    } else {
        // echo "MODO NORMAL <br>";
        require_once 'lib/lib_general.php';
    }
} else {
    // echo "MODO NORMAL <br>";
    require_once 'lib/lib_general.php';
}
// ------------------------- BLOQUE BD COMUN --------------------------




/* Post para agregar estadisticas */
if ($data['agregar_estadisticas']) {
    registrarLog($data);
    return;
}

if (!isset($_SESSION['idioma'])) {
    $_SESSION['idioma'] = 1;
}

if (isset($data)) {
    $_SESSION['idioma'] = $data['id_idioma'];

    registrarLog([
        'seccion' => $data['page'] == 'day' ? 'actividades_diaria' : 'actividades_semanal',
        'subseccion' => 'cambio_idioma',
        'observaciones' => 'Cambio de idioma.'
    ]);
}



if (!isset($_SESSION['idioma_iso'])) {
    $_SESSION['idioma_iso'] = 'es';
}

if (isset($_GET['ids_categoria']) && $_GET['ids_categoria'] != '') {
    $_SESSION['filtros_categorias'] = $_GET['ids_categoria'];
} else {
    unset($_SESSION['filtros_categorias']);
}


$tplIndex = new TemplatePower("templates/index.html", T_BYFILE);
$tplIndex->prepare();



$directorio = '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . "/imagenes/agenda/";
$files  = scandir($directorio);
$imagenes = array_values($files);


$imagenes = limpiar_caracteres_especiales($imagenes);


//MENU LATERAL
include_once('side_menu.php');
$tplIndex->assignGlobal('menu_lateral', $tpl_menu->getOutputContent());

$tplIndex->assignGlobal('version', date("YmdHis"));
$tplIndex->assignGlobal('idioma',  $_SESSION['idioma']);


if (!isset($_GET['page'])) {
    include('layout_day.php');
} else {
    switch ($_GET['page']) {
        case 'day':
            include('layout_day.php');

            registrarLog([
                'seccion' => 'actividades_semanal',
                'subseccion' => 'cambio_panel',
                'identificador' => 'actividades_diaria',
                'observaciones' => 'Cambio de panel.'
            ]);

            break;

        case 'week':
            include('layout_week.php');

            registrarLog([
                'seccion' => 'actividades_diaria',
                'subseccion' => 'cambio_panel',
                'identificador' => 'actividades_semanal',
                'observaciones' => 'Cambio de panel.'
            ]);

            break;

        default:
            include('layout_day.php');

            registrarLog([
                'seccion' => 'actividades_semanal',
                'subseccion' => 'cambio_panel',
                'identificador' => 'actividades_diaria',
                'observaciones' => 'Cambio de panel.'
            ]);

            break;
    }
}



$screensavers = get_screensavers_activos('ACTIVIDADES')[0];
$screensaver_config = get_screensavers_config('ACTIVIDADES')[0];

// echo "<pre>";
// print_r($screensavers);
// print_r($screensaver_config);
// die;

$ruta_screensaver = '../../../contenido_proyectos/vistaflor/centro_' . $id_centro . "/screensaver/actividades/";

if (file_exists($ruta_screensaver . $screensavers['nombre']) && !empty($screensavers['nombre'])) {
    $tplIndex->newBlock('screensaver');
    $tplIndex->assign('screensaver', $ruta_screensaver . $screensavers['nombre']);
    $tplIndex->assign('screensaver_duracion', $screensaver_config['duracion']);
    $tplIndex->assign('screensaver_espera', $screensaver_config['espera']);
}

$tplIndex->assignGlobal('contenido', $tpl->getOutputContent());

$tplIndex->printToScreen();
