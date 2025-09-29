<?php


include 'gestion/banner_piscinas.php';
//Comentario

$fecha = getFechaActual();

/*phpinfo();exit();*/
 
$idioma_actual=$_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];
$id_lugar = $_SESSION['id_lugar'];

/** para establecer los colores de los iconos del menu */
//Colores
$_mainMenuIconColors = array(
    "agenda" => "#002b49",              //#CB4F24
    "hotel" => "#002b49",               //#4570b4
    "check-in" => "#002b49",            //#B71351
    "informacion" => "#002b49",         //#56A099
    "conoce" => "#002b49",              //#68AB6A
    "ocio" => "#002b49",                //#c7362f
    "actividades" => "#002b49",         //#e7b142
    "comer" => "#002b49",               //#80217e
    "que_hacer" => "#002b49",            //#80217e
    "destinos" =>"#002b49",
    "lugares" =>"#002b49",
    "sostenibilidad"=> "#002b49",
    "eurocopa" => "#002b49"
);


//Pregunta si este totem tiene o no Que Hacer

$configuracion_totem = configuracion_totem($id_centro);

if($configuracion_totem['que_hacer'] == '0'){

    if($configuracion_totem['agenda'] == '-1'){
          $classElementosMenuPrincipal = "one_sixth";

    }else{

        $classElementosMenuPrincipal = "one_fith";
    }

}else{

    if($configuracion_totem['agenda'] == '-1'){
        $classElementosMenuPrincipal = "one_sixth";
    }else{
        $classElementosMenuPrincipal = "one_seventh";
    }
}

$classElementosMenuPrincipal = "one_fith";

//////////////////////////////////////////////////


//date_default_timezone_set("Europe/London");

$tpl_index = new TemplatePower("plantillas/totem.html", T_BYFILE);
$tpl_index->prepare();


$teclado_sistema = strtolower($_SERVER['HTTP_SEC_CH_UA']);
if (strpos($teclado_sistema, "chromium") !== false) {
    $tpl_index->assignGlobal('teclado_sistema', '<link type="text/css" rel="stylesheet" href="css/teclado.css?v='.date('YmdHis').'">');
    $tpl_index->assignGlobal('input_teclado', 'no');
}


if ($id_centro == '269'){
    $tpl_index->assignGlobal('oculta_tiempo', 'style=display:none !important;');
}



/*****     definimos caracteristicas generales de la pagina     ****/
$tpl_index->newBlock("htmlHead");
$tpl_index->assign("charset", "UTF-8");                             //charset para la codificacion
$tpl_index->assign("page_title", "Twistic");                       //titulo de la página (esto sobra pero bueno)

$tpl_index->assign("version", time());                              //versionado

/******************************************************************
 *****     generamos el bloque general                         ****/
$tpl_index->newBlock("htmlBody");


/*****     Añadimos el legal disclaimer                ****/
$tpl_index->assign("lang_legal_title", LANG_GLOBAL_DISCLAIMER_TITLE);
$tpl_index->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);




/******************************************************************
 *****     establecemos varios datos generales                *****/
$datosCentro = totem_getDatosCentro();
//almaceno en session para tenerlas disponibles más tarde
$_SESSION['centroLatitud']=$datosCentro['latitud'];
$_SESSION['centroLongitud']=$datosCentro['longitud'];

$tpl_index->assign("centroLatitud", $datosCentro['latitud']);
$tpl_index->assign("centroLongitud", $datosCentro['longitud']);

$tpl_index->assign("id_centro", $id_centro);


/******************************************************************
 *****     generamos el bloque de screen saver                ****/
$screensaver_info = get_screensaver($id_centro);

//echo json_encode($screensaver_info);exit();

if ($screensaver_info['screensaver_activo'] != 0 && isset($screensaver_info['screensaver']))
{
    $tpl_index->newBlock("screenSaver"); //dailos & aysal
    $tpl_index->assign("screensaver_time", $screensaver_info['screensaver_time']);  
    $tpl_index->assign("screensaver_time_duration", $screensaver_info['screensaver_time_duration']);         
    $tpl_index->newBlock("video");                                      //para asignarle un video como salvapantalla
    $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_$id_centro/".$screensaver_info['screensaver']);//indicamos la ruta

    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/tematico_miercoles.webm") && date('N') == 3){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/tematico_miercoles.webm");//indicamos la ruta
    }

    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/tematico_sabado.webm") && date('N') == 6){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/tematico_sabado.webm");//indicamos la ruta
    }

    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/29_05.webm") && date('Y-m-d') == '2024-05-29'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/29_05.webm");//indicamos la ruta
    }
    
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/30_05.webm") && date('Y-m-d') == '2024-05-30'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/30_05.webm");//indicamos la ruta
    }
        
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/31_05.webm") && date('Y-m-d') == '2024-05-31'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/31_05.webm");//indicamos la ruta
    }
        
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/01_06.webm") && date('Y-m-d') == '2024-06-01'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/01_06.webm");//indicamos la ruta
    }
        
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/02_06.webm") && date('Y-m-d') == '2024-06-02'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/02_06.webm");//indicamos la ruta
    }
         
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/timbachata2905_0306.webm") && date('Y-m-d') == '2024-06-03'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/timbachata2905_0306.webm");//indicamos la ruta
    }
      
    if(file_exists("../../../contenido_proyectos/pacoche/centro_".$id_centro."/imagen_festival.webm") && date('Y-m-d') <= '2025-06-29'){
        $tpl_index->assign("video_source", "../../../contenido_proyectos/pacoche/centro_".$id_centro."/imagen_festival.webm");//indicamos la ruta
    }
}

$tpl_index->newBlock("slider_imagenes");                            //podriamos crear una pase de diapositivas

/******************************************************************
 *  Bloque para la franja superior                             ****/
$tpl_index->newBlock("pageHeader");
$tpl_index->assign("logo_filename_src", "itourism2.svg");            //logo de la aplicacion
$tpl_index->assign("logo_alt", "Logo de itourism");                 //alt del logo
$tpl_index->assign("logo_title", "Lorem ipsum sit ena");            //titulo del logo

$tpl_index->assign("fecha", $fecha );
$tpl_index->assign("time", date("H.i") );



$tpl_index->assign("id_centro", $id_centro);


/******************************************************************
 *  Bloque para la franja del banner, video, etc               ****/
$tpl_index->newBlock("pageBanner");             // este bloque tiene varios sub-bloques definidos
$tpl_index->newBlock("hotelBlock");             // en función del tipo de página en el que se este
                                                //y lo que se quiera mostrar.
$tpl_index->assign("hotelBannerWrapper_class", "");
$tpl_index->assign("hotel_banner_src", "dunas3.png");

$banner_superior = totem_obtener_listado_banner("superior");
if ($banner_superior[0]['tipo_banner']=="video")
    $tpl_index->newBlock("hotelBannerSuperiorVideo");                     //cargamos el video o imagen superior
else
    $tpl_index->newBlock("hotelBannerSuperiorImagen"); 

$tpl_index->assign("contenido", "../../../contenido_proyectos/pacoche/centro_$id_centro/banner_header/".$banner_superior[0]['file']);      

/******************************************************************
 *  Bloques de contenido.                                      ****/

$tpl_index->newBlock("pageContent");

/* instancio a la vez el bloque del elemento del menu y el bloque del elemento de contenido
 * asociado al elemento del menu */


/////////////////////////          hotel
$tpl_index->newBlock("mainMenu_element");
$tpl_index->assign("element_id", "elmt2");

// Si no existe programación hotel es activo si no será actividades
if ($configuracion_totem['agenda'] == -1 || $configuracion_totem['agenda'] == 1  ) $tpl_index->assign("element_class", "active $classElementosMenuPrincipal ");
else $tpl_index->assign("element_class", " $classElementosMenuPrincipal ");

$tpl_index->assign("element_icono", "iconoHotel");            
$tpl_index->assign("element_color", $_mainMenuIconColors['hotel']);
$tpl_index->assign("element_href", "#hotelWrapper");
$tpl_index->assign("element_text", LANG_HOTEL_TITLE);

$tpl_index->newBlock("menuContent");
$tpl_index->assign("contentWrapper_id", "hotelWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
$tpl_index->assign("contentWrapper_class", "");                 //para asignarle una clase al wrapper de contenido

include "pagina/pagina_hotel.php";


/////////////////////////          informacion
$tpl_index->newBlock("mainMenu_element");       
$tpl_index->assign("element_id", "elmt1");
$tpl_index->assign("element_class", "$classElementosMenuPrincipal");     
$tpl_index->assign("element_icono", "iconoInformacion");             
$tpl_index->assign("element_color", $_mainMenuIconColors['informacion']);
$tpl_index->assign("element_href", "#informacionWrapper");
$tpl_index->assign("element_text", LANG_INFORMACION_TITLE);

$tpl_index->newBlock("menuContent");
$tpl_index->assign("contentWrapper_id", "informacionWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
$tpl_index->assign("contentWrapper_class", "");                       //para asignarle una clase al wrapper de contenido

include "pagina/pagina_informacion.php";

/////////////////////////          mapa
$tpl_index->newBlock("mainMenu_element");
$tpl_index->assign("element_id", "");
$tpl_index->assign("element_href_class", " foto_banner_vertical");
$tpl_index->assign("element_icono", "iconomapa");
$tpl_index->assign("element_color", $_mainMenuIconColors['informacion']);
$tpl_index->assign("element_href", "mapa");
$tpl_index->assign("element_text", LANG_MAPA_TITLE);

$tpl_index->newBlock("menuContent");
$tpl_index->assign("contentWrapper_id", "");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
$tpl_index->assign("contentWrapper_class", "");                       //para asignarle una clase al wrapper de contenido




///Tipo de Agenda

switch ($configuracion_totem['agenda']) {
    case '1':
        $tpl_index->newBlock("mainMenu_element");       
        $tpl_index->assign("element_id", "elmt1");
        $tpl_index->assign("element_class", "$classElementosMenuPrincipal");       //clase para el wrapper del elemento
        $tpl_index->assign("element_icono", "iconoAgenda");     //clase que indica que icono se superpone a la "lagrima"
        $tpl_index->assign("element_color", $_mainMenuIconColors['agenda']);         //color con el que se rellena el svg
        $tpl_index->assign("element_href", "#programacionWrapper");   //id del elemento que despliega (aconsejable utilizar wrapper)
        $tpl_index->assign("element_href_class", "");           //en caso de que deseemos que el link funcione poner validLink
        $tpl_index->assign("element_text", LANG_PROGRAMACION_TITLE);           //texto que aparece debajo
        $tpl_index->assign("img_header", "");

        $tpl_index->newBlock("menuContent");                        //   este bloque define el wrapper que contendra la informacion del objeto 
        $tpl_index->assign("contentWrapper_id", "programacionWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
        $tpl_index->assign("contentWrapper_class", "");                  //para asignarle una clase al wrapper de contenido

        include "pagina/pagina_programacion.php";         //<- en este fichero se llena de contenido la variable content_contenido

    break;

    case '2':
        $tpl_index->newBlock("mainMenu_element");       
        $tpl_index->assign("element_id", "elmt1");

        // Parche para contemporaneo (agenda tipo salones por defecto)
        if ($id_centro == "111") $tpl_index->assign("element_class", "active $classElementosMenuPrincipal"); 
        else  $tpl_index->assign("element_class", "active $classElementosMenuPrincipal");      //clase para el wrapper del elemento
        
        $tpl_index->assign("element_icono", "iconoAgenda");     //clase que indica que icono se superpone a la "lagrima"
        $tpl_index->assign("element_color", $_mainMenuIconColors['agenda']);         //color con el que se rellena el svg
        $tpl_index->assign("element_href", "#programacionWrapper2");   //id del elemento que despliega (aconsejable utilizar wrapper)
        $tpl_index->assign("element_href_class", "");           //en caso de que deseemos que el link funcione poner validLink
        $tpl_index->assign("element_text", LANG_PROGRAMACION_TITLE);           //texto que aparece debajo
        $tpl_index->assign("img_header", "");

        $tpl_index->newBlock("menuContent");                        //   este bloque define el wrapper que contendra la informacion del objeto 
        $tpl_index->assign("contentWrapper_id", "programacionWrapper2");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
        $tpl_index->assign("contentWrapper_class", "");                  //para asignarle una clase al wrapper de contenido

        include "pagina/pagina_programacion2.php";         //<- en este fichero se llena de contenido la variable content_contenido

    break;

    case '-1':
        break;
    
    default:
     /////////////////////////          Programacion version calendario largo
        $tpl_index->newBlock("mainMenu_element");       
        $tpl_index->assign("element_id", "elmt1");
        $tpl_index->assign("element_class", "active $classElementosMenuPrincipal");       //clase para el wrapper del elemento
        $tpl_index->assign("element_icono", "iconoAgenda");     //clase que indica que icono se superpone a la "lagrima"
        $tpl_index->assign("element_color", $_mainMenuIconColors['agenda']);         //color con el que se rellena el svg
        $tpl_index->assign("element_href", "#agendaWrapper");   //id del elemento que despliega (aconsejable utilizar wrapper)
        $tpl_index->assign("element_href_class", "");           //en caso de que deseemos que el link funcione poner validLink
        $tpl_index->assign("element_text", LANG_AGENDA_TITLE);           //texto que aparece debajo
        $tpl_index->assign("img_header", "");

        $tpl_index->newBlock("menuContent");                        //   este bloque define el wrapper que contendra la informacion del objeto 
        $tpl_index->assign("contentWrapper_id", "agendaWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
        $tpl_index->assign("contentWrapper_class", "");                  //para asignarle una clase al wrapper de contenido

        include "pagina/pagina_agenda.php";         //<- en este fichero se llena de contenido la variable content_contenido
    
    break;

}

/////////////////////////          Conoce el lugar - Riu


$lang_lugar = get_nombre_lugar($_SESSION['id_lugar'],$idioma_actual);

$lang_lugar =  $lang_lugar['0']['nombre_lugar'];

$tpl_index->newBlock("mainMenu_element");       
$tpl_index->assign("element_id", "elmt3");
$tpl_index->assign("element_class", "$classElementosMenuPrincipal");     
$tpl_index->assign("element_icono", "iconoConoce");            
$tpl_index->assign("element_color", $_mainMenuIconColors['lugares']);
$tpl_index->assign("element_href", "#lugaresWrapper");
$tpl_index->assign("element_text", $lang_lugar );

$tpl_index->newBlock("menuContent");
$tpl_index->assign("contentWrapper_id", "lugaresWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
$tpl_index->assign("contentWrapper_class", "");

include "pagina/pagina_lugares.php";


///////////////////Pagina que hacer
//$tpl_index->newBlock("mainMenu_element");
//$tpl_index->assign("element_id", "elmt3");
//$tpl_index->assign("element_class", "$classElementosMenuPrincipal");
//$tpl_index->assign("element_icono", "iconoActividades");
//$tpl_index->assign("element_color", $_mainMenuIconColors['que_hacer']);
//$tpl_index->assign("element_href", "#quehacerWrapper");
//$tpl_index->assign("element_text", LANG_QUE_HACER_TITLE);
//
//$tpl_index->newBlock("menuContent");
//$tpl_index->assign("contentWrapper_id", "quehacerWrapper");        //para asignarle un id al bloque de contenido. MISMO ID QUE EL element_href del bloque superior
//$tpl_index->assign("contentWrapper_class", "");
//
//include "pagina/pagina_que_hacer.php";

/******************************************************************
 *  Bloque del contenido del footer. 
 * Este bloque tiene varios bloques predefinidos asi como 
 * una variable por si se quiere importar contenido especifico    */
$tpl_index->newBlock("pageFooter");

$tpl_index->assign("pageFooter_class", "");         //para asignale una clae al pageFooterWrapper
$tpl_index->assign("pageFooter_text_class", "");    //define la clase por si queremos que aparesca algun titulo
$tpl_index->assign("pageFooter_text", "");          //por si queremos que aparesca un texto en el footer

$tpl_index->newBlock("pageFooter_slider");
$tpl_index->assign("footerSlider_title", "");             //un titulo general y fijo sobre las imagenes del slider
$tpl_index->assign("slider_class", "");                   //para asignarle una clase al slider
$tpl_index->assign("slider_options", "");                 //opciones del slider. objeto json que recibe javascript y utliza


$banners_inferiores= totem_obtener_listado_banner("inferior");

foreach ($banners_inferiores as $banner) {
    $tpl_index->newBlock("slider_element");               //vamos añadiendo imagenes
    $tpl_index->assign("slider_elmement_class", str_replace(",", " ", $banner['clase']) );      //clase para el elemento wrapper de la imagen
    $tpl_index->assign("slider_elmement_href", $banner['enlace']);      //enlace del elemento
    $tpl_index->assign("slider_element_title_class", ""); //clase para el elemento titulo
    $tpl_index->assign("slider_element_title", "");       //en caso de que deseemos que el elemento del slider tenga un texto
    $tpl_index->assign("slider_img_src", "../../../contenido_proyectos/pacoche/centro_$id_centro/banner_footer/".$banner["file"]);

    if ( $banner['path'] != "" ) {
        $tpl_index->assign("data_path", "data-path='" . $banner['path'] . "'");
    }
    
}



//Banner de la calidad del agua en don gregory

//if ($id_centro == '1901') {
//    crear_imagen_piscina('piscinas_dinamico_'.$idioma_actual.'.jpg', $id_centro);
//    $tpl_index->newBlock("slider_element");               //vamos añadiendo imagenes
//    $tpl_index->assign("slider_img_src", '../../../contenido_proyectos/pacoche/centro_'.$id_centro.'/banner_footer/final_piscinas_dinamico_'.$idioma_actual.'.jpg');
//
//}


/******************************************************************
 *   Bloque del menu inferior                                  ****/
$tpl_index->newBlock("pageBottom");
$tpl_index->assign("tiempo_icono_src", "sol.png");              //icono para el tiempo
$tpl_index->assign("menu_prev","#");                            //link al elemento anterior
$tpl_index->assign("lang_tiempo", LANG_TOTEM_TIEMPO);



$tpl_index->assign("fuente", "");       //de donde salen los datos


$ruta_icono_widget = '../../../contenido_proyectos/pacoche/_general/tiempo/icono/';

$codLocalidad = $_SESSION['id_localidad'];

$array_lugares_mexico = array(10,11,12,13,14,15,16,17,18,19,20,23,24,30);
$array_lugares_mauricio = array(38);

$tiempo = get_tiempo($codLocalidad);

$hora_actual = date("H", time());


//DE mañana
if ( ($hora_actual <= '12') && ($hora_actual > '06') ){
    // $vactual_temp = 'temp_06';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $temp = json_decode($tiempo[1]['json_basico']);
    }
    else $temp = json_decode($tiempo[0]['json_basico']);

    $icono = $temp->icono;
}
//DE tarde
if ( ($hora_actual < '21') && ($hora_actual > '12') ){
    // $vactual_temp = 'temp_12';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $temp = json_decode($tiempo[2]['json_basico']);
    }
    else $temp = json_decode($tiempo[1]['json_basico']);

    if (in_array($id_lugar,$array_lugares_mauricio)) $icono = str_replace("_noche","", $temp->icono);
}

// noche
if ( (($hora_actual <= '24') && ($hora_actual >= '21')) ||  ($hora_actual <= '06')){
    // $vactual_temp = 'temp_18';
    if (in_array($id_lugar,$array_lugares_mexico))
    {
        $temp = json_decode($tiempo[0]['json_basico']);
    }
    else $temp = json_decode($tiempo[2]['json_basico']);

    $icono = $temp->icono;
}


//$temp = json_decode($tiempo[0]['json_basico']);



if  ($temp->temperatura != '')
{
    //Es en New York y esta en ingles, por lo que los grados son en Farenheit
    if (($id_centro=='270' || $id_centro=='256') && ( $idioma_actual=='1' || $idioma_actual=='2' )){

        $tpl_index->assign("temperatura_tiempo_widget", ( round( $temp->temperatura) * 1.8) + 32 ."ºF");

    }else{
        $tpl_index->assign("temperatura_tiempo_widget", round($temp->temperatura)."ºC");
    }
}



if  ($icono == '')  $ruta_icono_widget.$icono ="despejado.svg";

$tpl_index->assign("icono_tiempo_widget", $ruta_icono_widget.$icono);


/*
 * Fin parte del tiempo Ruben
 */
//generamos el contenido de la informacion meteorologica
$tpl_meteoInfo = new TemplatePower("plantillas/meteo_info.html", T_BYFILE);
$tpl_meteoInfo->prepare();
$tpl_meteoInfo->assign("meteoInfo_charValues");
$tpl_meteoInfo->assign("fecha_meteo", getFechaActual());

$tpl_meteoInfo->assign("lang_hoy", LANG_GLOBAL_HOY);
$tpl_meteoInfo->assign("lang_mañana", LANG_GLOBAL_MAÑANA);

$arrayIdiomasDias = array (LANG_GLOBAL_DIA_MAÑANA, LANG_GLOBAL_DIA_TARDE, LANG_GLOBAL_DIA_NOCHE);
for ($i=0; $i<3; $i++){
    // Bloque para mostrar los resumenes de las temperaturas
    $tpl_meteoInfo->newBlock("meteoInfo_resumen");
    $tpl_meteoInfo->assign("resumen_iconNubosidadSrc");
    $tpl_meteoInfo->assign("resumen_dia", $arrayIdiomasDias[$i]);
    $tpl_meteoInfo->assign("resumen_temperatura", 25);
    $tpl_meteoInfo->assign("resumen_viento", 55);
    $tpl_meteoInfo->assign("resumen_humedad", 69);

    $tpl_meteoInfo->assign("lang_viento", LANG_METEO_VIENTO);
    $tpl_meteoInfo->assign("lang_humedad", LANG_METEO_HUMEDAD);
}

for ($i=0; $i<4; $i++){
    // Bloque para mostrar las estimaciones de los dias posterioreslang_tiempo
    $tpl_meteoInfo->newBlock("meteoInfo_estimacion");
    $tpl_meteoInfo->assign("meteoInfo_estimacionTempMax", "");
    $tpl_meteoInfo->assign("meteoInfo_estimacionTempMin", "");

}

for ($i=0; $i<2; $i++){
    $tpl_meteoInfo->newBlock("meteoInfo_estimacionDia");
}

$tpl_index->assign("meteoInfo_value", $tpl_meteoInfo->getOutputContent());


//idioma actual

$idioma_actual = $_SESSION['idioma'];

$tpl_index->assign("id_idioma_actual", $idioma_actual);                        //id del idioma


//listado de idiomas
$tpl_index->newBlock("idioma");
$tpl_index->assign("idioma", "ES");              //bandera del idioma
$tpl_index->assign("idioma_icono_src", "../../../contenido_proyectos/pacoche/_general/iconos/idiomas/es.svg");
//$tpl_index->assign("idioma", "Español");                        //nombre del idioma
$tpl_index->assign("id_idioma", "1");                        //id del idioma
if ($idioma_actual==1)
	$tpl_index->assign("active", "idioma_actual");                   //lo marcamos como activo



$tpl_index->newBlock("idioma");
$tpl_index->assign("idioma", "EN");              //bandera del idioma
$tpl_index->assign("idioma_icono_src", "../../../contenido_proyectos/pacoche/_general/iconos/idiomas/en.svg");
$tpl_index->assign("id_idioma", "2");                        //id del idioma
//$tpl_index->assign("idioma", "English");                       //nombre del idioma
if ($idioma_actual==2)
	$tpl_index->assign("active", "idioma_actual");                   //lo marcamos como activo

//$tpl_index->newBlock("idioma");
//$tpl_index->assign("idioma", "DE");              //bandera del idioma
//$tpl_index->assign("id_idioma", "3");                        //id del idioma
////$tpl_index->assign("idioma", "Deutsch");                        //nombre del idioma
//if ($idioma_actual==3)
//	$tpl_index->assign("active", "idioma_actual");                   //lo marcamos como activo

//$tpl_index->newBlock("idioma");
//$tpl_index->assign("idioma", "FR");              //bandera del idioma
//$tpl_index->assign("id_idioma", "4");                        //id del idioma
////$tpl_index->assign("idioma", "Deutsch");                        //nombre del idioma
//if ($idioma_actual==4)
//    $tpl_index->assign("active", "idioma_actual");                   //lo marcamos como activo
//


$tpl_index->printToScreen();
?>
