<?php 

header('Content-Type: text/html; charset=UTF-8'); 
session_start();
error_reporting(2);

die;

if(isset($_POST['id_idioma'])){
    $_SESSION['idioma'] = $_POST['id_idioma'];
    return;
}else{
    if(!isset($_SESSION['idioma'])){
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
$arrayIdiomas = array(1=>"es", 2=>"en", 3=>"de", 4=>"fr", 5=>"fr", 6=>"ch");


$langFilePath = 'lang/definiciones_' . $arrayIdiomas[$_SESSION['idioma']] . '.php';
if (is_file($langFilePath))
{
    include_once $langFilePath;
}
else
{
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

/* Controlamos el contenido que vamos a cargar por ajax */
if(isset($_GET['pagina'])){
	include "paginas/".$_GET['pagina'].".php";
	return;
}

if(isset($_GET['id_hotel'])){
    $id_hotel = $_GET['id_hotel'];
}else{
    /*if(isset($_SESSION['id_centro'])){
        $id_hotel = $_SESSION['id_centro'];
    }else{*/
        $id_hotel = 1901;
    /*}*/
}

//$id_hotel = 1801;

$hotel = get_hotel($id_hotel);
//print_r($hotel); die;
/* Momentaneo solo para hacer unas pruebas */
$_SESSION['fichero_config'] = $hotel[0]['config_file'];
$config = parse_ini_file( "../../../config/".$_SESSION['fichero_config'], true);
/* Fin de las pruebas */

$_SESSION['id_centro'] = $config['centro']['id_centro'];
$_SESSION['id_lugar'] = $config['lugar']['id_lugar'];
$_SESSION['id_cadena'] = $config['cadena']['id_cadena'];
$_SESSION['id_localidad'] = $config['localidad']['id_localidad'];
$_SESSION['url_comun'] = $config['url_comun']['url_comun'];
$_SESSION['proyecto'] = $hotel[0]['proyect_name'];


$tpl_index = new TemplatePower("plantillas/index.html", T_BYFILE);
$tpl_index->prepare();

$tpl_index->assignGlobal('id_hotel',$id_hotel);
//$tpl_index->assignGlobal('cadena_hotel',$hotel[0]['proyecto']);

$tpl_index->assignGlobal('id_cache',date('YmdHis'));

if($_SESSION['proyecto'] == 'oasis'){
    $tpl_index->assign('logo','http://admin.twisticdigital.com/contenido_proyectos/dunas/contenido/centro_'.$_SESSION['id_centro'].'/feelapp/logo_arriba2.png');
}else{
    $tpl_index->assign('logo','http://admin.twisticdigital.com/contenido_proyectos/dunas/contenido/_general/logo_arriba2.png');
}

/* Para las secciones que están puestas de forma manaul */

$tpl_index->assign('nombre_sostenibilidad',LANG_FEELAPP_SOSTENIBILIDAD);
$tpl_index->assign('nombre_destinos',LANG_FEELAPP_DESTINOS);
$tpl_index->assign('nombre_entretenimiento',LANG_FEELAPP_ENTRETENIMIENTO);
$tpl_index->assign('nuestros_hoteles',LANG_FEELAPP_NUESTROS_HOTELES);

$tpl_index->assign('nombre_todo_incluido',LANG_FEELAPP_TODO_INCLUIDO);
$tpl_index->assign('nombre_restaurantes',LANG_FEELAPP_RESTAURANTES);
$tpl_index->assign('nombre_bares',LANG_FEELAPP_BARES);
$tpl_index->assign('nombre_promociones',LANG_FEELAPP_PROMOCIONES);
$tpl_index->assign('nombre_mapa_hotel',LANG_FEELAPP_MAPA_HOTEL);
$tpl_index->assign('nombre_excursiones',LANG_FEELAPP_EXCURSIONES);


$tpl_index->assignGlobal('id_hotel_idioma', $_SESSION['id_centro']);

/* Fin de las secciones manuales */


/* Ocultar secciones */

if($_SESSION['proyecto'] == 'oasis'){
    $tpl_index->assign('ocultar_oasis','displaynone');
}


$datos_hotel = datos_hotel();

$tpl_index->assign('nombre_hotel_menu',$datos_hotel['nombre']);

$lista_contenidos = get_lista_contenidos(1);

foreach($lista_contenidos as $contenido){

    //id_cat, nombre, class_icon

    if($contenido['id_cat'] != 52 && $contenido['id_cat'] != 115){
        $lista_contenidos_sub = get_lista_contenidos($contenido['id_cat']);



        /* Cuando la categoria no tiene subcategorias la ponemos como principal */
        if(check_contenido($contenido['id_cat'])){

            $tpl_index->newBlock('menu_lateral');
            $tpl_index->assign('nombre_categoria',$contenido['nombre']);
            $tpl_index->assign('id_categoria','categoria-' . $contenido['id_cat']);//
            $tpl_index->assign('class_icon','categoria-' . $contenido['class_icon']);
            $tpl_index->assign('class_icon',$contenido['class_icon']);

            if($contenido['id_cat'] == 81){
                $tpl_index->assign('displaynone','displaynone');
            }

        }else{

            /* Cuando tiene solo una subseccion ponemos dicha subseccion como principal */
            if(count($lista_contenidos_sub) == 1){

                $tpl_index->newBlock('menu_lateral');
                $tpl_index->assign('nombre_categoria',$contenido['nombre']);
                $tpl_index->assign('id_categoria','categoria-' . $lista_contenidos_sub[0]['id_cat']);
                $tpl_index->assign('class_icon',$contenido['class_icon']);

                //echo $contenido['nombre'] . ' - '.$lista_contenidos_sub[0]['id_cat'].'<br>';
            }elseif(count($lista_contenidos_sub) > 1){
                /* Si tienes más de una ponemos todas las subcategorias */
                //echo $contenido['nombre'] . ' - '.$contenido['id_cat'].'<br>';

                $tpl_index->newBlock('menu_lateral');
                $tpl_index->assign('nombre_categoria',$contenido['nombre']);
                $tpl_index->assign('id_categoria','#');
                $tpl_index->assign('clase_apertura','data-sub="sidebar-sub-' . $contenido['id_cat'] . '"');
                $tpl_index->assign('class_icon',$contenido['class_icon']);

                //data-sub="sidebar-sub-1"

                $tpl_index->newBlock('contenedor_subcategorias');
                $tpl_index->assign('id_categoria_bloque',$contenido['id_cat']);
                $tpl_index->assign('class_icon',$contenido['class_icon']);

                    $contenido_total = 0;
                    foreach ($lista_contenidos_sub as $subcontenido){
                        //echo ' ------ ' . $subcontenido['nombre'] . ' - '.$subcontenido['id_cat'].'<br>';

                        if(check_contenido($subcontenido['id_cat'])){

                            $tpl_index->newBlock('menu_lateral_sub');
                            $tpl_index->assign('nombre_categoria',$subcontenido['nombre']);
                            $tpl_index->assign('id_categoria','categoria-' . $subcontenido['id_cat']);
                            $contenido_total++;

                        }

                    }$tpl_index->gotoBlock('menu_lateral');


                if($contenido_total == 0){
                    $tpl_index->assign('ocultar_categoria_menu','displayNone');
                }

            }

        }


    }


}$tpl_index->gotoBlock('ROOT');//die;

/* Bloques para los idiomas de la app */


if($_SESSION['proyecto'] == 'oasis'){
    $array_idiomas = array(1=>"español", 2=>"english");
}else{
    $array_idiomas = array(1=>"es", 2=>"en", 3=>"de", 4=>"fr");
}
$total_idiomas = count($array_idiomas);

foreach($array_idiomas as $key=>$idioma) {
    $tpl_index->newBlock("idiomas");
    $tpl_index->assign("idioma_icono_src", "../contenido_proyectos/".$_SESSION['proyecto']."/contenido/_general/iconos/idiomas/".$idioma.".svg");              //bandera del idioma
    $tpl_index->assign("id_idioma", $key);                        //id del idioma
    if ($_SESSION['idioma'] == $key){
        $tpl_index->assign("active", "active_idioma");                   //lo marcamos como activo
    }
    $tpl_index->assign('ancho_idioma','numero_idiomas_' . $total_idiomas);
    $tpl_index->assign('nombre_idioma',$idioma);
}

$tpl_index->gotoBlock('ROOT');


$tpl_index->printToScreen();

include "paginas/portada.php";

include "index_footer.php";


?>
