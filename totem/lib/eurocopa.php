<?php

session_start();

$idioma = $_SESSION['idioma'];

$zona_horaria_actual = date_default_timezone_get();
//$zona_horaria_actual = 'America/New_York';
$ruta_archivos = '../../../../eurocopa/';
$timezone = timezone_list();
$diferencia_horaria = 2 - $timezone[$zona_horaria_actual];
$diferencia_horaria = -$diferencia_horaria;


if(!file_exists('../../../../eurocopa/')){
    mkdir("../../../../eurocopa", 0777);
}


/* Comprobamos si tenemos que actualizar los archivos */

if(isset($_GET['url'])) {

    switch ($_GET['url']) {

        case 'grupo1':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_a";
            $nombre_archivo = 'grupo_sa_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo2':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_b";
            $nombre_archivo = 'grupo_sb_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo3':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_c";
            $nombre_archivo = 'grupo_sc_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo4':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_d";
            $nombre_archivo = 'grupo_sd_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo5':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_e";
            $nombre_archivo = 'grupo_se_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo6':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_f";
            $nombre_archivo = 'grupo_sf_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo7':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_g";
            $nombre_archivo = 'grupo_sg_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'grupo8':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/grupos_h";
            $nombre_archivo = 'grupo_sh_' . $_SESSION['idioma'] . '.txt';
            $archivo_css = 'grupos.css';

            break;

        case 'eliminatorias':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/final_a";
            $archivo_css = 'eliminatorias.css';
            $nombre_archivo = 'eliminatoriass_' . $_SESSION['idioma'] . '.txt';

            break;

        case 'calendario':

            $url = "https://resultados.as.com/resultados/futbol/mundial/2018/calendario/dias/";
            $archivo_css = 'calendario.css';
            $nombre_archivo = 'calendarios_' . $_SESSION['idioma'] . '.txt';

            break;

        case 'semifinal':

            $url = "http://resultados.as.com/resultados/futbol/eurocopa/2016/calendario/grupos_a";
            $nombre_archivo = 'semifinals_' . $_SESSION['idioma'] . '.txt';

            break;

        case 'final':

            $url = "http://resultados.as.com/resultados/futbol/eurocopa/2016/calendario/grupos_a";
            $nombre_archivo = 'finals_' . $_SESSION['idioma'] . '.txt';

            break;

    }

    $lang = $_SESSION['idioma'];
    $arrayIdiomas = array(1 => "es", 2 => "en", 3 => "de", 4 => "fr");

    $langFilePath = '../lang/definiciones_' . $arrayIdiomas[$lang] . '.php';
    if (is_file($langFilePath)) {
        include_once $langFilePath;
    } else {
        include_once 'lang/definiciones_es.php';
    }

    $array_dias = array('lunes' => LANG_EUROCOPA_LUNES,
        'martes' => LANG_EUROCOPA_MARTES,
        'miercoles' => LANG_EUROCOPA_MIERCOLES,
        'jueves' => LANG_EUROCOPA_JUEVES,
        'viernes' => LANG_EUROCOPA_VIERNES,
        'sabado' => LANG_EUROCOPA_SABADO,
        'domingo' => LANG_EUROCOPA_DOMINGO);

    $array_paises = array('rusia' => LANG_EUROCOPA_RUSIA,
        'arabia' => LANG_EUROCOPA_ARABIA,
        'egipto' => LANG_EUROCOPA_EGIPTO,
        'uruguay' => LANG_EUROCOPA_URUGUAY,
        'portugal' => LANG_EUROCOPA_PORTUGAL,
        'espana' => LANG_EUROCOPA_ESPANA,
        'marruecos' => LANG_EUROCOPA_MARRUECOS,
        'iran' => LANG_EUROCOPA_IRAN,
        'francia' => LANG_EUROCOPA_FRANCIA,
        'australia' => LANG_EUROCOPA_AUSTRALIA,
        'argentina' => LANG_EUROCOPA_ARGENTINA,
        'peru' => LANG_EUROCOPA_PERU,
        'dinamarca' => LANG_EUROCOPA_DINAMARCA,
        'nigeria' => LANG_EUROCOPA_NIGERIA,
        'croacia' => LANG_EUROCOPA_CROACIA,
        'islandia' => LANG_EUROCOPA_ISLANDIA,
        'brasil' => LANG_EUROCOPA_BRASIL,
        'suiza' => LANG_EUROCOPA_SUIZA,
        'costa' => LANG_EUROCOPA_COSTA,
        'serbia' => LANG_EUROCOPA_SERBIA,
        'alemania' => LANG_EUROCOPA_ALEMANIA,
        'mexico' => LANG_EUROCOPA_MEXICO,
        'suecia' => LANG_EUROCOPA_SUECIA,
        'corea' => LANG_EUROCOPA_COREA,
        'belgica' => LANG_EUROCOPA_BELGICA,
        'panama' => LANG_EUROCOPA_PANAMA,
        'tunez' => LANG_EUROCOPA_TUNEZ,
        'inglaterra' => LANG_EUROCOPA_INGLATERRA,
        'polonia' => LANG_EUROCOPA_POLONIA,
        'senegal' => LANG_EUROCOPA_SENEGAL,
        'colombia' => LANG_EUROCOPA_COLOMBIA,
        'japon' => LANG_EUROCOPA_JAPON);


    $nombre_archivo = $ruta_archivos . $nombre_archivo;

    $fechas = obtener_fechas_partidos($diferencia_horaria, $zona_horaria_actual);


    if (actualizar_archivo($fechas, $nombre_archivo)) {

        //echo 'si hay que actualizar'; die();

        /* Solo cambiamos el archivo con el contenido de la eurocopa si hay internet */
        if (testInternet1()) {
            /*echo 'si hay internet! yujuuuuuu';
            die;*/

            $codigohtml = obtenerhtml($url);

            /* No no hemos pasado un archivo css, se pone en blanco para que no de error */
            if (!isset($archivo_css)) {
                $archivo_css = '';
            }

            $head_completo = '><link href="../css/fonts/roboto.css" rel="stylesheet">
                                        <link href="../css/eurocopa/base.css" rel="stylesheet">
                                        <link href="../css/eurocopa/comunes.css?f=20180220" rel="stylesheet">
                                        <link href="../css/eurocopa/icons.css" rel="stylesheet">
                                        <link rel="stylesheet" href="../css/eurocopa/header.css" />
                                        <link href="../css/eurocopa/agenda.css" rel="stylesheet" />
                                        <link href="../css/eurocopa/modules.css" rel="stylesheet">
                                        <link href="../css/eurocopa/articulo.css" rel="stylesheet">
                                        <link href="../css/eurocopa/directos-sistemas.css" rel="stylesheet">
                                        <link href="../css/eurocopa/directos.css?u=201804111626" rel="stylesheet">
                                        <link href="../css/eurocopa/as-datos.css" rel="stylesheet">
                                        <link href="../css/eurocopa/calendario-dias.css" rel="stylesheet">
                                        <link href="../css/eurocopa.css" rel="stylesheet">
                                        <link href="../css/eurocopa/' . $archivo_css . '" rel="stylesheet"><';

            /*$texto = str_replace("/resultados/", "http://resultados.as.com/resultados/", $codigohtml);*/
            $codigohtml = str_replace("</head>", "<link href='../css/eurocopa.css' rel='stylesheet'></head>", $codigohtml);
            $codigohtml = str_replace(".js", " ", $codigohtml);
            $codigohtml = replace_between($codigohtml, '<head', 'head>', $head_completo);


            $texto = $codigohtml;
            $texto = str_replace("17:00", "-17:00", $texto);
            $texto = str_replace("14:00", "-14:00", $texto);
            $texto = str_replace("12:00", "-12:00", $texto);
            $texto = str_replace("18:00", "-18:00", $texto);
            $texto = str_replace("20:00", "-20:00", $texto);
            $texto = str_replace("15:00", "-15:00", $texto);
            $texto = str_replace("21:00", "-21:00", $texto);
            $texto = str_replace("16:00", "-16:00", $texto);

            $texto = str_replace("-17:00", hora_correcta_partido_iframe("17:00", $diferencia_horaria), $texto);
            $texto = str_replace("-14:00", hora_correcta_partido_iframe("14:00", $diferencia_horaria), $texto);
            $texto = str_replace("-12:00", hora_correcta_partido_iframe("12:00", $diferencia_horaria), $texto);
            $texto = str_replace("-18:00", hora_correcta_partido_iframe("18:00", $diferencia_horaria), $texto);
            $texto = str_replace("-20:00", hora_correcta_partido_iframe("20:00", $diferencia_horaria), $texto);
            $texto = str_replace("-15:00", hora_correcta_partido_iframe("15:00", $diferencia_horaria), $texto);
            $texto = str_replace("-21:00", hora_correcta_partido_iframe("21:00", $diferencia_horaria), $texto);
            $texto = str_replace("-16:00", hora_correcta_partido_iframe("16:00", $diferencia_horaria), $texto);
            $texto = str_replace("//as01.epimg.net/img/comunes/fotos/fichas/paises/svg/", '/../../../contenido_proyectos/pacoche/_general/eurocopa/iconos-paises/', $texto);
            $texto = str_replace("http://as01.epimg.net/img/", '../img/escudos/', $texto);
            $texto = str_replace("http://as01.epimg.net/img/carrusel/escudos/banderas/21_24/", '../img/escudos/', $texto);//
            $texto = str_replace('src="', 'src="..', $texto);
            $texto = str_replace("Clasificación", LANG_EUROCOPA_CLASIFICACION, $texto);
            $texto = str_replace("Junio", LANG_EUROCOPA_JUNIO, $texto);
            $texto = str_replace("Julio", LANG_EUROCOPA_JULIO, $texto);
            $texto = str_replace("Jornada de descanso", LANG_EUROCOPA_DESCANSO, $texto);
            $texto = str_replace("Puesto", LANG_EUROCOPA_PUESTO, $texto);
            $texto = str_replace("L-", "", $texto);
            $texto = str_replace("M-", "", $texto);
            $texto = str_replace("X-", "", $texto);
            $texto = str_replace("J-", "", $texto);
            $texto = str_replace("V-", "", $texto);
            $texto = str_replace("S-", "", $texto);
            $texto = str_replace("D-", "", $texto);
            $texto = str_replace("Fase de Grupos", LANG_EUROCOPA_FASE_DE_GRUPOS, $texto);
            $texto = str_replace("Grupo", LANG_EUROCOPA_GRUPO, $texto);
            $texto = str_replace("Jornada", LANG_EUROCOPA_JORNADA, $texto);
            $texto = str_replace("Octavos", LANG_EUROCOPA_OCTAVOS, $texto);
            $texto = str_replace("Cuartos", LANG_EUROCOPA_CUARTOS, $texto);
            $texto = str_replace("Semifinal", LANG_EUROCOPA_FINAL, $texto);
            $texto = str_replace("Final", LANG_EUROCOPA_FINAL, $texto);
            $texto = str_replace("Lunes", $array_dias['lunes'], $texto);
            $texto = str_replace("Martes", $array_dias['martes'], $texto);
            $texto = str_replace("Miercoles", $array_dias['miercoles'], $texto);
            $texto = str_replace("Jueves", $array_dias['jueves'], $texto);
            $texto = str_replace("Viernes", $array_dias['viernes'], $texto);
            $texto = str_replace("Sabado", $array_dias['sabado'], $texto);
            $texto = str_replace("Domingo", $array_dias['domingo'], $texto);

            $texto = str_replace("Rusia", $array_paises['rusia'], $texto);
            $texto = str_replace("Arabia Saudi", $array_paises['arabia'], $texto);
            $texto = str_replace("Egipto", $array_paises['egipto'], $texto);
            $texto = str_replace("Uruguay", $array_paises['uruguay'], $texto);

            $texto = str_replace("Portugal", $array_paises['portugal'], $texto);
            $texto = str_replace("España", $array_paises['espana'], $texto);
            $texto = str_replace("Marruecos", $array_paises['marruecos'], $texto);
            $texto = str_replace("Irán", $array_paises['iran'], $texto);

            $texto = str_replace("Francia", $array_paises['francia'], $texto);
            $texto = str_replace("Australia", $array_paises['australia'], $texto);
            $texto = str_replace("Perú", $array_paises['peru'], $texto);
            $texto = str_replace("Dinamarca", $array_paises['dinamarca'], $texto);

            $texto = str_replace("Argentina", $array_paises['argentina'], $texto);
            $texto = str_replace("Nigeria", $array_paises['nigeria'], $texto);
            $texto = str_replace("Croacia", $array_paises['croacia'], $texto);
            $texto = str_replace("Islandia", $array_paises['islandia'], $texto);

            $texto = str_replace("Brasil", $array_paises['brasil'], $texto);
            $texto = str_replace("Suiza", $array_paises['suiza'], $texto);
            $texto = str_replace("Costa Rica", $array_paises['costa'], $texto);
            $texto = str_replace("Serbia", $array_paises['serbia'], $texto);

            $texto = str_replace("Alemania", $array_paises['alemania'], $texto);
            $texto = str_replace("Suecia", $array_paises['suecia'], $texto);
            $texto = str_replace("México", $array_paises['mexico'], $texto);
            $texto = str_replace("Corea del sur", $array_paises['corea'], $texto);

            $texto = str_replace("Bélgica", $array_paises['belgica'], $texto);
            $texto = str_replace("Panamá", $array_paises['panama'], $texto);
            $texto = str_replace("Túnez", $array_paises['tunez'], $texto);
            $texto = str_replace("Inglaterra", $array_paises['inglaterra'], $texto);

            $texto = str_replace("Polonia", $array_paises['polonia'], $texto);
            $texto = str_replace("Senegal", $array_paises['senegal'], $texto);
            $texto = str_replace("Colombia", $array_paises['colombia'], $texto);
            $texto = str_replace("Japón", $array_paises['japon'], $texto);

            escribir_archivo($nombre_archivo,$texto);

            echo $texto;

        }else{

            if(file_exists($nombre_archivo)){
                $texto_final = obtener_archivo($nombre_archivo);
                echo $texto_final;
            }else{
                echo '';
            }

        }

    }else{

        $texto_final = obtener_archivo($nombre_archivo);
        echo $texto_final;

    }

}

// Inicio de obtener archivo
function obtener_archivo($nombre_archivo){

    $fichero_texto = fopen ($nombre_archivo, "r");
    $contenido_archivo = fread($fichero_texto, filesize($nombre_archivo));
    return $contenido_archivo;

}
// Fin de obtener el archivo

// Inicio de escribir archivo
function escribir_archivo($nombre_archivo,$texto){

    $file = fopen($nombre_archivo, "w") or die("Problemas");
    fwrite($file, $texto . PHP_EOL);
    fclose($file);
    return true;

}
// Fin de escribir archivo

// Inicio de obtener fechas de los partidos
function obtener_fechas_partidos($diferencia_horaria,$zona_horaria_actual){

    $fechas = array('2018-06-14 17:00:00','2018-06-15 14:00:00','2018-06-15 17:00:00','2018-06-15 20:00:00','2018-06-16 12:00:00','2018-06-16 18:00:00','2018-06-16 15:00:00','2018-06-16 21:00:00','2018-06-17 14:00:00','2018-06-17 17:00:00','2018-06-17 20:00:00','2018-06-18 14:00:00','2018-06-18 17:00:00','2018-06-18 20:00:00','2018-06-19 20:00:00','2018-06-19 17:00:00','2018-06-19 14:00:00','2018-06-20 17:00:00','2018-06-20 14:00:00','2018-06-20 20:00:00','2018-06-21 14:00:00','2018-06-21 17:00:00','2018-06-21 20:00:00','2018-06-22 17:00:00','2018-06-22 14:00:00','2018-06-22 20:00:00','2018-06-23 17:00:00','2018-06-23 20:00:00','2018-06-23 14:00:00','2018-06-24 14:00:00','2018-06-24 17:00:00','2018-06-24 20:00:00','2018-06-25 16:00:00','2018-06-25 20:00:00','2018-06-26 16:00:00','2018-06-26 20:00:00','2018-06-27 16:00:00','2018-06-27 20:00:00','2018-06-28 16:00:00','2018-06-28 20:00:00','2018-06-30 16:00:00','2018-06-30 20:00:00','2018-07-01 16:00:00','2018-07-01 20:00:00','2018-07-02 16:00:00','2018-07-02 20:00:00','2018-07-03 16:00:00','2018-07-03 20:00:00','2018-07-06 16:00:00','2018-07-06 20:00:00','2018-07-07 16:00:00','2018-07-07 20:00:00','2018-07-10 20:00:00','2018-07-11 20:00:00','2018-07-14 16:00:00','2018-07-15 17:00:00');

    if($zona_horaria_actual == 'Europe/Berlin'){

        return $fechas;

    }else{

        foreach ($fechas as $row){
            $fechas_actuales[] = hora_correcta_partido($row,$diferencia_horaria);
        }

        return $fechas_actuales;

    }

}
// Fin del obtener fechas de los partidos

function actualizar_archivo($fechas,$nombre_archivo){

    if(file_exists($nombre_archivo)){
        $fecha_act = stat($nombre_archivo);
    }else{
        $fecha_act = false;
    }

    if(!$fecha_act){

        return true;

    }else{

        $fecha_actualizacion = date("Y-m-d H:i:s",$fecha_act['mtime']);
        $fecha_actual = date("Y-m-d H:i:s");
        //$fecha_actual = '2018-06-14 20:00:00';

        foreach ($fechas as $fecha_partido){

            if($fecha_actual >= $fecha_partido AND $fecha_actual <= hora_correcta_partido($fecha_partido,3)){
                return true;
            }

            /* Con esto actualizamos el archivo en caso de que haya partidos sin actualizar */
            if($fecha_actualizacion <= sumar_horas($fecha_partido) AND $fecha_actualizacion <= $fecha_actual AND $fecha_partido <= $fecha_actual){
                return true;
            }
        }

        return false;

    }

}


/* función para poner de forma correcta las hora de los partidos */
function hora_correcta_partido($date,$diferencia_horaria){
    return date("Y-m-d H:i:s", strtotime ($diferencia_horaria . 'hour', strtotime($date)));
}

function hora_correcta_partido_iframe($date,$diferencia_horaria){
    return date("H:i", strtotime ($diferencia_horaria . 'hour', strtotime($date)));
}

/* Función para saber los times zones */

function timezone_list() {
    static $timezones = null;

    if ($timezones === null) {
        $timezones = [];
        $offsets = [];
        $now = new DateTime();
        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            $timezones[$timezone] = /*'(' . */format_GMT_offset($offset) /*. ') ' . format_timezone_name($timezone)*/;
        }

        array_multisort($offsets, $timezones);
    }

    return $timezones;
}

function format_GMT_offset($offset) {
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return /*'' . ($offset ? */sprintf('%+03d'/*':%02d'*/, $hours, $minutes)/* : '')*/;
}

/* fin de las times zones */

/* Con esto obtenemos todo el contenido de la url */
function obtenerhtml($url){
    $fo= fopen("$url","r") or die ("No se ha encontrado la pagina.");
    $codigohtml = '';
    while (!feof($fo)) {
        $codigohtml .= fgets($fo, 4096);
    }
    fclose ($fo);
    return $codigohtml;
}
/* Fin de obtener el html */


function obtenerCadena($contenido,$inicio,$fin){
    $r = explode($inicio, $contenido);
    if (isset($r[1])){
        $r = explode($fin, $r[1]);
        return $r[0];
    }else{
        return '';
    }
}


function sumar_horas($fecha){

    $nuevafecha = strtotime ( '+3 hour' , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( "Y-m-d H:i:s" , $nuevafecha );

    return $nuevafecha;

}

/********* Nuevo **********/

function replace_between($str, $needle_start, $needle_end, $replacement) {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);

    $pos = strpos($str, $needle_end, $start);
    $end = $start === false ? strlen($str) : $pos;

    return substr_replace($str,$replacement,  $start, $end - $start);
}


function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function testInternet1(){

    //Parche para que siempre diga que hay internet (demos)
    //return true;

    $host = '';
    $port = 80;
    $waitTimeoutInSeconds = 1;

    if( $fp = fsockopen("apiservicios.twisticdigital.com",$port,$errCode,$errStr,$waitTimeoutInSeconds) ){
        $t = true;
    }else{
        $t = false;
    }

    fclose($fp);
    return $t;
}

?>