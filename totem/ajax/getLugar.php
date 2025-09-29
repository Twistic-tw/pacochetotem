<?php
$idioma = $_SESSION['idioma'];

$url_base = $_SESSION["url_comun"];

$url_base_lugares = $url_base . 'destinos/lugares/';

$tpl_lugar = new TemplatePower("plantillas/seccion_lugar_principal.html", T_BYFILE);
$tpl_lugar->prepare();


$tpl_lugar->assignGlobal("url_base_lugares", $url_base_lugares );

//Pido a la base de datos los datos de ese lugar

if ( isset($_GET['lugar']) ) {

	$id_lugar = $_GET['lugar'];
}else{

	$config = parse_ini_file( "../../../../config/config.ini", true);           
        
	// Aqui hay que pedir a la base de datos el lugar donde esta el totem, o del config
	$id_lugar = $_SESSION["id_lugar"];

}

//////////////////////////////////////////////////////////////////Aythami - 11/03/2015
//Comprobacion de que existen las secciones, mostrar las opciones de arriba del menu o no

//Pedimos la variable de cadena
$id_cadena = $_SESSION['id_cadena'];

//Comprobamos que existen sitios de interes
$existe_sitios_interes = obtener_sitios_lugar ($id_lugar, $idioma, 0, $id_cadena);

if (empty ($existe_sitios_interes) ){

	$tpl_lugar->assign("no_sitios_interes", '<!--' );
	$tpl_lugar->assign("no_sitios_interes_cerrar", '-->' );

}

//Comprobamos que existen que hacer
$existe_que_hacer = obtener_sitios_lugar ($id_lugar, $idioma, 1, $id_cadena);

if (empty ($existe_que_hacer) ){

	$tpl_lugar->assign("no_que_hacer", '<!--' );
	$tpl_lugar->assign("no_que_hacer_cerrar", '-->' );

}

////////////////////////////////////////////////////////////////////////////////////////



$tpl_lugar -> assign('id_lugar', $id_lugar);

$datos_lugar = obtener_info_general_lugar($id_lugar, $idioma);


$tpl_lugar->assign("imagen-fondo", $datos_lugar['0']['fondo']);

$tpl_lugar->assign("imagen_arriba", $url_base_lugares.$datos_lugar['0']['id_lugar'].'/'.$datos_lugar['0']['imagen']);


$tpl_lugar->assign("cerrar", LANG_CONOCE_ATRAS);

$tpl_lugar->assign("info_label", LANG_LUGARES_INFO_GENERAL);
$tpl_lugar->assign("interes_label", LANG_LUGARES_SITIOS_INTERES);
$tpl_lugar->assign("galeria_label", LANG_CONOCE_GALERIA);
$tpl_lugar->assign("que_hacer_label", LANG_CONOCE_QUEHACER);
$tpl_lugar->assign("mercadillos_label", LANG_CONOCE_MERCADILLOS);

$tpl_lugar->assign("datos_pais", LANG_LUGARES_PAIS);
$tpl_lugar->assign("demografia", LANG_LUGARES_DEMOGRAFIA);
$tpl_lugar->assign("info_util", LANG_LUGARES_INFO_UTIL);
$tpl_lugar->assign("datos_geo", LANG_LUGARES_LOCAL_GEO);

//Variables estaticas de info general (los titulos)

$tpl_lugar->assign("pais",LANG_LUGARES_INFO_PAIS);
$tpl_lugar->assign("zona_horaria",LANG_LUGARES_INFO_ZONA_HORARIA);
$tpl_lugar->assign("capital",LANG_LUGARES_INFO_CAPITAL);
$tpl_lugar->assign("idiomas",LANG_LUGARES_INFO_IDIOMAS);

$tpl_lugar->assign("superficie",LANG_LUGARES_INFO_SUPERFICIE);
$tpl_lugar->assign("pto_alto",LANG_LUGARES_INFO_PUNTO_ALTO);
$tpl_lugar->assign("t_verano",LANG_LUGARES_INFO_C_VERANO);
$tpl_lugar->assign("t_invierno",LANG_LUGARES_INFO_C_INVIERNO);

$tpl_lugar->assign("poblacion",LANG_LUGARES_INFO_POBLACION);
$tpl_lugar->assign("densidad",LANG_LUGARES_INFO_DENSIDAD);
$tpl_lugar->assign("gentilicio",LANG_LUGARES_INFO_GENTILICIO);

$tpl_lugar->assign("moneda",LANG_LUGARES_INFO_MONEDA);
$tpl_lugar->assign("electricidad",LANG_LUGARES_INFO_ELECTRICIDAD);
$tpl_lugar->assign("enchufe",LANG_LUGARES_INFO_ENCHUFE);
$tpl_lugar->assign("vacunas",LANG_LUGARES_INFO_VACUNAS);

//Datos de la base de datos
$tpl_lugar->assign("nombre_dato",$datos_lugar['0']['nombre_lugar']);
$tpl_lugar->assign("pais_dato",$datos_lugar['0']['pais']);
$tpl_lugar->assign("zona_horaria_dato",$datos_lugar['0']['zona_horaria']);
$tpl_lugar->assign("capital_dato",$datos_lugar['0']['capital']);
$tpl_lugar->assign("idiomas_dato",$datos_lugar['0']['idiomas']);

$tpl_lugar->assign("superficie_dato",$datos_lugar['0']['superficie']);
$tpl_lugar->assign("pto_alto_dato",$datos_lugar['0']['punto_mas_alto']);
$tpl_lugar->assign("t_verano_dato",$datos_lugar['0']['temperatura_verano']);
$tpl_lugar->assign("t_invierno_dato",$datos_lugar['0']['temperatura_invierno']);

$tpl_lugar->assign("poblacion_dato",$datos_lugar['0']['poblacion']);
$tpl_lugar->assign("densidad_dato",$datos_lugar['0']['densidad_poblacion']);
$tpl_lugar->assign("gentilicio_dato",$datos_lugar['0']['gentilicio']);

$tpl_lugar->assign("moneda_dato",$datos_lugar['0']['moneda']);
$tpl_lugar->assign("electricidad_dato",$datos_lugar['0']['electricidad']);
$tpl_lugar->assign("enchufe_dato",$datos_lugar['0']['enchufe']);
$tpl_lugar->assign("vacunas_dato",$datos_lugar['0']['vacunas']);
$tpl_lugar->assign("bandera_pais",$datos_lugar['0']['bandera_pais']);



$tpl_lugar->assign("idioma", $idioma );


$datos['get'] = $_GET;
$datos['datos'] = $tpl_lugar->getOutputContent();

echo json_encode($datos);
