<?php


/** hub central para controlar el acceso y la distribucion de la información del totem. Las peitionces pueden
  *  seguir alguno de los protocolos siguientes:
  *  index.php?ajax=pagina[&opciones...]  se hace un include (en caso de existir el file) del fichero ajax/pagina y este procesa la info
  *  index.php?gestion=pagina[&opciones...]  se hace un include (en caso de existir el file) del fichero gestion/pagina y este procesa la info
  *  index.php?pagina=pagina[&opciones...]  se hace un include (en caso de existir el file) del fichero pagina/pagina y este procesa la info
  * 
  *  ** PENDIENTE **
  *  esta pendiente las comprobaciones de seguridad y se se va a utilizar un objeto global para las conexiones sql y demas
  */
header('Content-Type: text/html; charset=UTF-8'); 
session_start();
error_reporting(0);
///setlocale(LC_ALL,"es_ES.UTF-8");


//echo strftime("%A %d de %B del %Y");
//setlocale(LC_TIME,"es_ES@euro","es_ES","esp");
//
//echo strftime("%A %d de %B del %Y");
//
//phpinfo();exit();
//template power no se va a tocar nunca mientras se use template power

require_once 'lib/class.TemplatePower.inc.php';

/*Esta parte del codigo se encargará de controlar si hay alguna actualizacion en curso para q no se toque nada del totem
 mientras el fichero cerrojo no desaparezca no se podrá hacer nada
*/
if (file_exists("lock.php")){
    //Caso para las peticiones Ajax
    if(!empty($_GET)){
        echo "actualizacion";
        exit();
    }
    //Caso para las peticiones normales de php
    $tpl_index = new TemplatePower("plantillas/pagina_actualizacion.html", T_BYFILE);
    $tpl_index->prepare();
    $tpl_index->printToScreen();
    exit();
}

require_once '../../../config/MySQL.php';
require_once '../../../config/MySQL_local.php';
require_once '../../../config/MySQL_comun.php';


require_once '../../../contenido_proyectos/vistaflor/_comun/lib_php/libreria.php';

include_once 'lib/qr/qrlib.php';
include_once 'lib/config.php';
include_once 'lib/lib_conoce.php';
//Libreria para los destinos
include_once 'lib/lib_destinos.php';

//Libreria para el lugar
include_once 'lib/lib_lugar.php';

require_once 'lib/lib_totem.php';
require_once 'lib/class.phpmailer.php';
include_once 'class/class.farmacia.php';
include_once 'class/class.cliente.php';
include_once 'config.php';


$config = parse_ini_file( "../../../config/config.ini", true);         
        
$_SESSION['id_centro'] = $config['centro']['id_centro'];


$_SESSION['id_lugar'] = $config['lugar']['id_lugar'];

$_SESSION['id_cadena'] = $config['cadena']['id_cadena'];

$_SESSION['id_localidad'] = $config['localidad']['id_localidad'];

//$_SESSION['lang_lugar'] = $config['lugar']['lugar_lang'];


if (isset($_GET['idioma'])){
    $_SESSION['idioma'] = $_GET['idioma'];
    
    header("Location: index.php");
    exit();
}

if ( isset($_GET['idi']) ) {
    $_SESSION['idioma'] = $_GET['idi'];
}

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
    include_once $langFilePath;
}
else 
{
    include_once 'lang/definiciones_es.php';
}


//include_once 'lang/definiciones_es.php';


if ( isset($_GET['ajax']) ){
    $filePath = "ajax/".$_GET['ajax'].".php";
    
    if ( is_file($filePath) ){
        include_once $filePath;
    }
    exit();             //es una peticion ajax, solo es necesaria la información concreta
}
elseif ( isset($_GET['gestion']) ){
    $filePath = "gestion/".$_GET['gestion'].".php";
    
    if ( is_file($filePath) ){
        include_once $filePath;
    }
    else
    {
        echo $_GET['gestion'];
    }
    exit();             //se asumen que los submits y demas son peticiones ajax
}
elseif ( isset($_GET['pagina']) ){
    $filePath = "pagina/".$_GET['pagina'].".php";
    
    if ( is_file($filePath) ){
        include_once $filePath;
    }
    exit();             //al solicitar una pagina tambien se hace mediante una peticion ajax      
}

//por defecto mostramos la información general del totem
include_once 'totem.php';



?>