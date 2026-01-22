<?php

header('Content-Type: text/html; charset=UTF-8');
session_start();
error_reporting(2);

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

date_default_timezone_set("Atlantic/Canary");

//if (isset($_POST['id_idioma'])) {
//    $_SESSION['idioma'] = $_POST['id_idioma'];
//
//    return;
//} else {
//    if (! isset($_SESSION['idioma'])) {
//        $_SESSION['idioma'] = 1;
//    }
//}

if (isset($_POST['id_idioma'])) {
    $_SESSION['idioma'] = $_POST['id_idioma'];
    if (!isset($_POST['continue'])) {
        return;
    }
} else {
    if (!isset($_SESSION['idioma'])) {
        $_SESSION['idioma'] = 1;
    }
}

switch ($_SESSION['idioma']) {
    case '1':
        setlocale(LC_ALL, "es_ES.UTF-8");
        define("CHARSET", "UTF-8");
        setlocale(LC_TIME, 'spanish');

        break;

    case '2':
        setlocale(LC_ALL, "en_EN.UTF-8");
        define("CHARSET", "UTF-8");
        setlocale(LC_TIME, "english");

        break;

    case '3':
        setlocale(LC_ALL, "de_DE.UTF-8");
        define("CHARSET", "UTF-8");
        setlocale(LC_TIME, 'german');

        break;

    //El polaco esta en ingles
    case '4':
        setlocale(LC_ALL, "fr_FR.UTF-8");
        define("CHARSET", "UTF-8");
        setlocale(LC_TIME, "french");

        break;
}

/* Idiomas */
$arrayIdiomas = array(1 => "es", 2 => "en", 3 => "de", 4 => "fr", 5 => "fr", 6 => "ch");

$langFilePath = 'lang/definiciones_' . $arrayIdiomas[$_SESSION['idioma']] . '.php';
if (is_file($langFilePath)) {
    include_once $langFilePath;
} else {
    include_once 'lang/definiciones_es.php';
}

require_once 'lib/class.TemplatePower.inc.php';

require_once '../../../config/MySQL.php';
require_once '../../../config/MySQL_local.php';
require_once '../../../config/MySQL_comun.php';
require_once '../../../config/MySQL_clientes.php';

require_once 'lib/lib_app.php';
require_once 'lib/lib_actividades.php';
require_once 'lib/lib_destinos.php';

require_once 'lib/lib_restaurantes.php';

if (isset($_POST["config"])) {
    $config = parse_ini_file("../../../config/" . $_POST["config"], true);
    if ($config['centro']['id_centro']) {
        $_SESSION['id_centro'] = $config['centro']['id_centro'];
    }
}

if (isset($_GET['id_hotel'])) {
    $_SESSION['id_centro'] = $_GET['id_hotel'];
} else {
    if (isset($_SESSION['id_centro'])) {
        $id_hotel = $_SESSION['id_centro'];
    } else {
        $_SESSION['id_centro'] = 1901;
    }
}

$url_online = $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];

$require_https = 0;
$maintenance = false;

//TODO: Remover los subdominios que no son de este proyecto

if (strstr($url_online, 'dongregory.hotelesdunas')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1901;
} elseif (strstr($url_online, 'dunas.hotelesdunas')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1904;
} elseif (strstr($url_online, 'suite.hotelesdunas')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1902;
} elseif (strstr($url_online, 'mirador.hotelesdunas')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1903;
} elseif (strstr($url_online, 'villas.hotelesdunas')) { //TODO: Revisar subdominio
    $require_https = 1;
    $_SESSION['id_centro'] = 19029;
    $maintenance = true;

}

if (strstr($url_online, 'dongregory.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1901;
} elseif (strstr($url_online, 'dunas.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1904;
} elseif (strstr($url_online, 'suite.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1902;
} elseif (strstr($url_online, 'mirador.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 1903;
} elseif (strstr($url_online, 'maspalomasvillas.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 19029;
}

if (strstr($url_online, 'pacoche.twistic')) {
    $require_https = 1;
    $_SESSION['id_centro'] = 950;
}


//* Condicion para que no se muestre la pagina de mantenimiento
// if($_SESSION['id_centro'] == 19029 && $maintenance) { //! IMPORTANTE MODIFICAR LA PAGINA DE MANTENIMIENTO
//     $tpl_index = new TemplatePower("plantillas/1905.html", T_BYFILE);
//     $tpl_index->prepare();
//     $tpl_index->printToScreen();
//     exit();
// }

if($require_https == 1){
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
        $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
        exit;
    }
}

$id_hotel = $_SESSION['id_centro'];

$hotel = get_hotel($id_hotel);
//print_r($hotel); die;
/* Momentaneo solo para hacer unas pruebas */
$_SESSION['fichero_config'] = $hotel[0]['config_file'];
$config = parse_ini_file("../../../config/" . $_SESSION['fichero_config'], true);
/* Fin de las pruebas */

$_SESSION['id_centro'] = $config['centro']['id_centro'];
$_SESSION['id_lugar'] = $config['lugar']['id_lugar'];
$_SESSION['id_cadena'] = $config['cadena']['id_cadena'];
$_SESSION['id_localidad'] = $config['localidad']['id_localidad'];
$_SESSION['url_comun'] = $config['url_comun']['url_comun'];
$_SESSION['proyecto'] = $hotel[0]['proyect_name'];

//Obtenemos la zona horaria
get_timezone_feelapp();

/* Controlamos el contenido que vamos a cargar por ajax */

//if (isset($_GET['pagina'])) {
//    include "paginas/".$_GET['pagina'].".php";
//
//    return;
//}

$array_paginas_principales = ['buffet', 'seccion'];
if (isset($_GET['pagina'])) {

    if ($_GET['pagina'] == 'error') {
        header('Location: ./');
    }

    if (!in_array($_GET['pagina'], $array_paginas_principales)) {
        include "paginas/" . $_GET['pagina'] . ".php";

        return;
    }
}

$tpl_index = new TemplatePower("plantillas/index.html", T_BYFILE);
$tpl_index->prepare();

/* POPUP PROMO PORTADA */
if ($_SESSION['id_centro'] == 1901 && false) { // && false para que la codicion no aparezca nunca
    $hora_despues = date('Y-m-d H:i:s', strtotime($_SESSION['promo_portada']) + 3600);
    // Mostramos el popup cada 60 minutos
    if (!$_SESSION['promo_portada'] || $_SESSION['promo_portada'] > $hora_despues) {
        $url_imagen_popup = 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/mas_info/banner_cenabufe_'.$_SESSION['idioma'].'.png?v=' . date("YmdHis");

        $html_popup_portada = '<div class="fondo-popup-zoom"><div class="content-popup"><img class="img-popup" src="' . $url_imagen_popup . '"></div>';
        $html_popup_portada .= '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/back.svg" alt=""></div></div>';

        $tpl_index->assign('popup_portada', $html_popup_portada);

        $_SESSION['promo_portada'] = date('Y-m-d H:i:s');
    }
}

$dias_ofertas_san_valentin = ['2021-02-13', '2021-02-14'];
if (in_array(date('Y-m-d'), $dias_ofertas_san_valentin)) {
    $tpl_index->assign('css_ofertas', '<link rel="stylesheet" type="text/css" href="styles/ofertas_carta.css?v=' . date('YmdHis') . '">');
}


$datos_analytics = google_analytics();

if ($datos_analytics) {

    $tpl_index->assignGlobal('google_analytics', $datos_analytics['analytics']);
    $tpl_index->assignGlobal('tags_head', $datos_analytics['tags_head']);
    $tpl_index->assignGlobal('tags_body', $datos_analytics['tags_body']);

    $tpl_index->assignGlobal('url_web_input','https://' . $_SERVER['HTTP_HOST']);
    $_SESSION['url_web'] = 'https://' . $_SERVER['HTTP_HOST'];
    $tpl_index->assignGlobal('id_analytics',$datos_analytics['id_analytics']);

}

$tpl_index->assignGlobal('id_hotel', $id_hotel);
$tpl_index->assign('id_centro_global', $_SESSION['id_centro']);
//$tpl_index->assignGlobal('cadena_hotel',$hotel[0]['proyecto']);

$tpl_index->assignGlobal('id_cache', date('YmdHis'));


$tpl_index->assign('logo', 'https://view.twisticdigital.com/contenido_proyectos/pacoche/centro_950/logos/logo-alpha.png');
//$tpl_index->assign('logo', 'https://view.twisticdigital.com//contenido_proyectos/dunas/_general/logo_arriba2.png');

$tpl_index->assign('fondo_secundario', 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_' . $_SESSION['id_centro'] . '/feelapp/fondo2.jpg');

/* Para las secciones que están puestas de forma manaul */

$tpl_index->assign('nombre_sostenibilidad', LANG_FEELAPP_SOSTENIBILIDAD);
$tpl_index->assign('nombre_destinos', LANG_FEELAPP_DESTINOS);
$tpl_index->assign('nombre_entretenimiento', LANG_FEELAPP_ENTRETENIMIENTO);
$tpl_index->assign('nuestros_hoteles', LANG_FEELAPP_NUESTROS_HOTELES);

$tpl_index->assign('nombre_todo_incluido', LANG_FEELAPP_TODO_INCLUIDO);
$tpl_index->assign('nombre_restaurantes', LANG_FEELAPP_RESTAURANTES);
$tpl_index->assign('nombre_bares', LANG_FEELAPP_BARES);
$tpl_index->assign('nombre_promociones', LANG_FEELAPP_PROMOCIONES);
$tpl_index->assign('nombre_mapa_hotel', LANG_FEELAPP_MAPA_HOTEL);
$tpl_index->assign('nombre_excursiones', LANG_FEELAPP_EXCURSIONES);
$tpl_index->assign('add_screen_texto', ADD_SCREEN);


$tpl_index->assignGlobal('id_hotel_idioma', $_SESSION['id_centro']);

/* Secciones individual por hotel */
if ($_SESSION['id_centro'] == 1902) {
    $secciones = '<a data-url="mapa" data-titulo="Mapa" data-enlace="mapa" href="#" class="enlace-pagina"><i class="css_icon_menu_izq icon-mapahotel"></i><span>' . LANG_FEELAPP_MAPA_HOTEL . '</span><i class="ion-record"></i></a>';
    $tpl_index->assign('secciones', $secciones);
}


/*     $tpl_index->assignGlobal('texto_checkin', '<a {clase_apertura} data-url="pre-checkin-in" data-titulo="Pre Check-In" class="enlace-pagina {displaynone} " data-enlace="iframe" href="#"><i class="css_icon_menu_izq icon-check-in"></i><span>Pre Check-In</span><i class="ion-record"></i></a>');
 */    // $tpl_index->assign('datos_usuario', '<a data-url="guest-card" data-titulo="Guest Card" class="enlace-datos-reserva " data-enlace="panel_usuario_formulario" href="#"><i class="css_icon_menu_izq icon-datos_usuario "></i><span>' . LANG_DATOS_HUESPED . '</span><i class="ion-record"></i></a>');



/* Fin de las secciones manuales */

if ($_SESSION['id_centro'] == 1901 && date('Y-m-d') == '2020-12-31') {
    $tpl_index->assign('fin_ano', '<a data-url="image-new-year" data-titulo="Imagen Fin de año" class="js_contenidos_masinfo_scroll_app" data-imagen="programa-fin-ano_es" href="#"><i class="css_icon_menu_izq icon-finano"></i><span>' . LANG_NEW_YEAR . '<span><i class="ion-record"></i></a>');
}

if(get_programa_navidad()){
    $tpl_index->assign('programa_navidad', '<a data-titulo="programa_navidad" data-enlace="programa_navidad" class="enlace-pagina" href="#"><i class="css_icon_menu_izq icon-programa_navidad"></i><span>'.LANG_TEXTO_NAVIDAD.'</span><i class="ion-programa_navidad"></i></a>');
}

/* Ocultar secciones */

if ($_SESSION['proyecto'] == 'oasis') {
    $tpl_index->assign('ocultar_oasis', 'displaynone');
}

if ($_SESSION['id_centro'] != 1901) {
    $tpl_index->assign('ocultar_excu', 'displayNone');
}

$datos_hotel = datos_hotel();

$tpl_index->assign('nombre_hotel_menu', $datos_hotel['nombre']);
// $tpl_index->assign('enlace_newsletter', '<a data-url="newsletter" data-titulo="Newsletter" class="enlace-pagina" data-enlace="newsletter" href="#"><i class="css_icon_menu_izq icon-boletin"></i><span class="menu_lateral_doble">' . LANG_NEWSLETTER . '</span><i class="ion-record menu_lateral_doble"></i></a>');

//creamos la sita de las redes sociales por hoteles
switch ($id_hotel){
    case 1901:
        $array_rs[] = ['nombre' => 'Facebook', 'icono' => 'facebook.svg', 'url' => 'https://www.facebook.com/dongregorybydunas'];
        $array_rs[] = ['nombre' => 'Instagram', 'icono' => 'instagram.svg', 'url' => 'https://www.instagram.com/dongregorybydunas/'];
        break;

    case 1902:
        $array_rs[] = ['nombre' => 'Facebook', 'icono' => 'facebook.svg', 'url' => 'https://www.facebook.com/suitesandvillasbydunas'];
        $array_rs[] = ['nombre' => 'Instagram', 'icono' => 'instagram.svg', 'url' => 'https://www.instagram.com/dunashotels/'];
        break;

    case 1903:
        $array_rs[] = ['nombre' => 'Facebook', 'icono' => 'facebook.svg', 'url' => 'https://www.facebook.com/miradormaspalomasbydunas'];
        $array_rs[] = ['nombre' => 'Instagram', 'icono' => 'instagram.svg', 'url' => 'https://www.instagram.com/dunashotels/'];
        break;

    case 1904:
        $array_rs[] = ['nombre' => 'Facebook', 'icono' => 'facebook.svg', 'url' => 'https://www.facebook.com/maspalomasresortbydunas'];
        $array_rs[] = ['nombre' => 'Instagram', 'icono' => 'instagram.svg', 'url' => 'https://www.instagram.com/dunashotels/'];
        break;


    case 19029:
        $array_rs[] = ['nombre' => 'Facebook', 'icono' => 'facebook.svg', 'url' => 'https://www.facebook.com/dunashotelsandresorts/?locale=es_ES'];
        $array_rs[] = ['nombre' => 'Instagram', 'icono' => 'instagram.svg', 'url' => 'https://www.instagram.com/dunashotels/'];
        break;
}

if($array_rs) {
    foreach ($array_rs as $rs) {
        $tpl_index->newBlock('redes_sociales');
        $tpl_index->assign('redes_sociales_url', $rs['url']);
        $tpl_index->assign('redes_sociales_img', $rs['icono']);
        $tpl_index->assign('redes_sociales_nombre', $rs['nombre']);
    }
}

$tpl_index->gotoBlock('ROOT');

$lista_contenidos = get_lista_contenidos(1);


$array_contenidos_insertados = null;

if ($_SESSION['id_centro'] == 1901) {
//    $array_contenidos_insertados['108'] = array(array('<a class="enlace-pagina" data-enlace="calendario" href="#"><i class="css_icon_menu_izq icon-entretenimiento"></i><span>'.LANG_FEELAPP_ENTRETENIMIENTO.'</span><i class="ion-record"></i></a>',
//                                                     '<a class="enlace-pagina" data-enlace="categoria-115" href="#"><i class="css_icon_menu_izq icon-excursiones"></i><span>'.LANG_FEELAPP_EXCURSIONES.'</span><i class="ion-record"></i></a>'));


    $contenido_enlace_rutas = '<a data-sub="sidebar-sub-999999" class="enlace-pagina" data-enlace="#" href="#"><i class="css_icon_menu_izq icon-rutas"></i><span>' . LANG_NOS_VAMOS_DE_RUTA . '</span><i class="ion-record"></i></a>';
    $contenido_enlace_rutas .= '<div class="submenu" id="sidebar-sub-999999" style="height: 0px;">';
    $contenido_enlace_rutas .= '<a data-url="rutas-running" data-titulo="Rutas Running" class="enlace-pagina" data-enlace="rutas" id="ruta-1" href="#"><span>Running</span></a>';
    $contenido_enlace_rutas .= '<a data-url="rutas-senderismo" data-titulo="Rutas Senderismo" class="enlace-pagina" data-enlace="rutas" id="ruta-2" href="#"><span>' . LANG_SENDERISMO . '</span></a>';
    $contenido_enlace_rutas .= '<a data-url="rutas-paseo" data-titulo="Rutas Paseo" class="enlace-pagina" data-enlace="rutas" id="ruta-3" href="#"><span>' . LANG_PASEO . '</span></a>';
    $contenido_enlace_rutas .= '</div>';

    $tpl_index->assign('enlace_rutas', $contenido_enlace_rutas);


} else {
    $array_contenidos_insertados['108'] = array(array('<a data-url="actividades" data-titulo="Calendario de actividades" class="enlace-pagina" data-enlace="calendario" href="#"><i class="css_icon_menu_izq icon-entretenimiento"></i><span>' . LANG_FEELAPP_ENTRETENIMIENTO . '</span><i class="ion-record"></i></a>'));
}

if (isset($_GET['prueba'])) {
    $array_contenidos_insertados['108'] = array(array('<a data-url="prueba-usuario" data-titulo="Prueba Usuario" class="enlace-pagina" data-enlace="pagina_prueba_usuario" href="#"><i class="css_icon_menu_izq icon-entretenimiento"></i><span>Prueba USUARIO</span><i class="ion-record"></i></a>'));

}


if(isset($_GET['prueba_res']) && $_SESSION['id_centro'] == 1904){
    $tpl_index->assignGlobal('prueba_restaurante','<a data-url="prueba-restaurante" data-titulo="Prueba Restaurante" class="enlace-pagina" data-enlace="restaurantes" href="#"><i class="css_icon_menu_izq icon-restaurantes"></i><span>Prueba Restaurantes</span><i class="ion-record"></i></a>');
}

$datos_analytics_contenido = get_all_contenidos();

// echo '<pre>';
// print_r($datos_analytics_contenido); die;

foreach ($lista_contenidos as $contenido) {

    //id_cat, nombre, class_icon
    if ($_SESSION['id_centro'] == 1902 || $_SESSION['id_centro'] == 1904) {
        if ($contenido['id_cat'] == 37) {
            continue;
        }
    }


    $tpl_index->newBlock('menu_lateral_content');

    /* Secciones insertadas manualmente */
    if ($array_contenidos_insertados[$contenido['id_cat']]) {
        foreach ($array_contenidos_insertados[$contenido['id_cat']] as $menu_manual) {
            foreach ($menu_manual as $seccion_manual) {
                $tpl_index->newBlock('menu_lateral_manual');
                $tpl_index->assign('seccion_manual', $seccion_manual);
            }
        }
        $tpl_index->gotoBlock('menu_lateral_content');
        unset($array_contenidos_insertados[$contenido['id_cat']]);
    }

    if ($contenido['id_cat'] != 52 && $contenido['id_cat'] != 115) {
        $lista_contenidos_sub = get_lista_contenidos($contenido['id_cat']);


        /* Cuando la categoria no tiene subcategorias la ponemos como principal */
        if (check_contenido($contenido['id_cat'])) {

            $tpl_index->newBlock('menu_lateral');
            $tpl_index->assign('nombre_categoria', $contenido['nombre']);

            /* Analytics */
            $tpl_index->assign('url_categoria', $datos_analytics_contenido[$contenido['id_cat']]['url']);
            $tpl_index->assign('titulo_categoria', $datos_analytics_contenido[$contenido['id_cat']]['titulo']);

            $tpl_index->assign('id_categoria', 'categoria-' . $contenido['id_cat']);//
            $tpl_index->assign('class_icon', 'categoria-' . $contenido['class_icon']);
            $tpl_index->assign('class_icon', $contenido['class_icon']);

            if ($contenido['id_cat'] == 81) {
                $tpl_index->assign('displaynone', 'displaynone');
            }

        } else {

            /* Cuando tiene solo una subseccion ponemos dicha subseccion como principal */
            if (count($lista_contenidos_sub) == 1) {

                $tpl_index->newBlock('menu_lateral');
                $tpl_index->assign('nombre_categoria', $contenido['nombre']);
                $tpl_index->assign('id_categoria', 'categoria-' . $lista_contenidos_sub[0]['id_cat']);

                /* Analytics */
                $tpl_index->assign('url_categoria', $datos_analytics_contenido[$lista_contenidos_sub[0]['id_cat']]['url']);
                $tpl_index->assign('titulo_categoria', $datos_analytics_contenido[$lista_contenidos_sub[0]['id_cat']]['titulo']);

                $tpl_index->assign('class_icon', $contenido['class_icon']);

                //echo $contenido['nombre'] . ' - '.$lista_contenidos_sub[0]['id_cat'].'<br>';
            } elseif (count($lista_contenidos_sub) > 1) {
                /* Si tienes más de una ponemos todas las subcategorias */
                //echo $contenido['nombre'] . ' - '.$contenido['id_cat'].'<br>';

                $tpl_index->newBlock('menu_lateral');
                $tpl_index->assign('nombre_categoria', $contenido['nombre']);
                $tpl_index->assign('id_categoria', '#');
                $tpl_index->assign('clase_apertura', 'data-sub="sidebar-sub-' . $contenido['id_cat'] . '"');
                $tpl_index->assign('class_icon', $contenido['class_icon']);

                //data-sub="sidebar-sub-1"

                $tpl_index->newBlock('contenedor_subcategorias');
                $tpl_index->assign('id_categoria_bloque', $contenido['id_cat']);
                $tpl_index->assign('class_icon', $contenido['class_icon']);

                /* Analytics */
                $tpl_index->assign('url_categoria', $datos_analytics_contenido[$contenido['id_cat']]['url']);
                $tpl_index->assign('titulo_categoria', $datos_analytics_contenido[$contenido['id_cat']]['titulo']);

                $contenido_total = 0;
                foreach ($lista_contenidos_sub as $subcontenido) {
                    //echo ' ------ ' . $subcontenido['nombre'] . ' - '.$subcontenido['id_cat'].'<br>';

                    if (check_contenido($subcontenido['id_cat'])) {

                        $tpl_index->newBlock('menu_lateral_sub');
                        $tpl_index->assign('nombre_categoria', $subcontenido['nombre']);

                        /* Analytics */
                        $tpl_index->assign('url_categoria', $datos_analytics_contenido[$subcontenido['id_cat']]['url']);
                        $tpl_index->assign('titulo_categoria', $datos_analytics_contenido[$subcontenido['id_cat']]['titulo']);

                        $tpl_index->assign('id_categoria', 'categoria-' . $subcontenido['id_cat']);
                        $contenido_total++;

                    }

                }
                $tpl_index->gotoBlock('menu_lateral');


                if ($contenido_total == 0) {
                    $tpl_index->assign('ocultar_categoria_menu', 'displayNone');
                }

            }

        }


    }


}
$tpl_index->gotoBlock('ROOT');//die;

if ($array_contenidos_insertados) {
    foreach ($array_contenidos_insertados[$contenido['id_cat']] as $menu_manual) {
        foreach ($menu_manual as $seccion_manual) {
            $tpl_index->newBlock('menu_lateral');
            $tpl_index->assign('seccion_manual', $seccion_manual);
        }
    }
    $tpl_index->gotoBlock('ROOT');//die;
}

/* Bloques para los idiomas de la app */


if ($_SESSION['proyecto'] == 'oasis') {
    $array_idiomas = array(1 => "español", 2 => "english");
} else {
    //$array_idiomasx = array(1 => "es", 2 => "en", 3 => "de", 4 => "fr");
    $array_idiomasx = array(1 => "es", 2 => "en");
}
$total_idiomas = count($array_idiomas);

foreach ($array_idiomas as $key => $idioma) {
    $tpl_index->newBlock("idiomas");
    $tpl_index->assign("idioma_icono_src", "../../contenido_proyectos/" . $_SESSION['proyecto'] . "/contenido/_general/iconos/idiomas/" . $idioma . ".svg");              //bandera del idioma
    $tpl_index->assign("id_idioma", $key);                        //id del idioma
    if ($_SESSION['idioma'] == $key) {
        $tpl_index->assign("active", "active_idioma");                   //lo marcamos como activo
    }
    $tpl_index->assign('ancho_idioma', 'numero_idiomas_' . $total_idiomas);
    $tpl_index->assign('nombre_idioma', $idioma);
}

$tpl_index->gotoBlock('ROOT');

$tpl_index->printToScreen();

if (in_array($_GET['pagina'], $array_paginas_principales)) {
    include "paginas/" . $_GET['pagina'] . ".php";
} else {
    include "paginas/portada.php";
}

include "index_footer.php";


?>
