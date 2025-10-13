<?php

$blokeIndex = 1;
$tpl_informacion = new TemplatePower("plantillas/pagina_informacion.html", T_BYFILE);
$tpl_informacion->prepare();

$tpl_informacion->assign("informacion_title", LANG_INFORMACION_TITLE);

$id_centro = $_SESSION['id_centro'];
$id_lugar =  $_SESSION['id_lugar'];

/** a dia de hoy debemos calcular el numero de elementos para en funcion de eso asignarle 
 * las clases debidas para que quede centrado. (las clases al element_class)
 */
$configuracion_totem = configuracion_totem($_SESSION['id_centro']);

$secciones = totem_getSectionsForHotel(1);
// $numeroSecciones = count($secciones);  //devuelve un array asociativo y secuencia y luego 3 secciones fijas
if($id_centro=='247'){$numeroSecciones = 4;}else{$numeroSecciones = 3;}
 		//Secciones iniciales en informacion para los riu
if ($configuracion_totem['farmacia'] == '1') $numeroSecciones++;
if ($configuracion_totem['cajero'] == '1') $numeroSecciones++;
if ($configuracion_totem['gaolinera'] == '1') $numeroSecciones++;
// if ($configuracion_totem['mareas'] == '1') $numeroSecciones++; // MAREAS OCULTO
$blokeIndex = 1;

// echo $numeroSecciones;

// Fitur
if ($numeroSecciones>=5 and $id_centro!="2999"){
	$rowsClass = "tworow ";

	$blockClass = "block_" . ceil( $numeroSecciones/2 );
}
else {
	$rowsClass = "onerow ";
	$blockClass = "block_" . $numeroSecciones;
}
//Puesto a pelo xq queda mas bonito en bloque de 4, para 8 secciones que son las que hay

    $blockClass = "block_4";



if ($id_lugar !="21" && $id_lugar !="22")
{
$tpl_informacion->newBlock("contentBlockLink");
$tpl_informacion->assign("element_id","");
$tpl_informacion->assign("element_class","$rowsClass $blockClass");
$tpl_informacion->assign("element_href","getInfo_general");
$tpl_informacion->assign("element_href_class","loadAjax");
$tpl_informacion->assign("element_icono","icon-telefonoEmergencia");
$tpl_informacion->assign("element_text", LANG_INFO_TELEFONOS_TITLE);
$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}

// Cabo verde no tiene información sobre farmacias ni guaguas
if ($id_lugar !="21" && $id_lugar !="22")
{
// Bloque para informacion sobre el transporte publico
$tpl_informacion->newBlock("contentBlockLink");
$tpl_informacion->assign("element_id","");
$tpl_informacion->assign("element_class","$rowsClass $blockClass");
$tpl_informacion->assign("element_href","getInfo_transportePublico");
$tpl_informacion->assign("element_href_class"," loadAjax");
$tpl_informacion->assign("element_icono","icon-autobus");
$tpl_informacion->assign("element_text", LANG_INFO_TRANSPORTE_TITLE);
$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}

// Bloque para información sobre los vuelos
// $tpl_informacion->newBlock("contentBlockLink");
// $tpl_informacion->assign("element_id","");
// $tpl_informacion->assign("element_class","$rowsClass $blockClass");
// $tpl_informacion->assign("element_href","getInfo_vuelos");
// $tpl_informacion->assign("element_href_class"," loadAjax");
// $tpl_informacion->assign("element_icono","icon-vuelos");
// $tpl_informacion->assign("element_text", LANG_INFO_VUELOS_TITLE);
// $tpl_informacion->assign("index", "informacion".$blokeIndex++);

// Bloque comentado - No hay información disponible para aeropuerto RMU
/*
$tpl_informacion->newBlock("contentBlockLink");
$tpl_informacion->assign("element_id","");
$tpl_informacion->assign("element_class","$rowsClass $blockClass");
$tpl_informacion->assign("element_href","getInfo_aeropuertos_new");
$tpl_informacion->assign("element_href_class"," loadAjax");
$tpl_informacion->assign("element_icono","icon-vuelos");
$tpl_informacion->assign("aeropuerto_doble","RMU");
$tpl_informacion->assign("element_text", LANG_INFO_VUELOS_TITLE);
$tpl_informacion->assign("index", "informacion".$blokeIndex++);
*/

// Bloque para información sobre las farmacias

// if ($_SESSION['id_centro'] != 22){



if ($configuracion_totem['farmacia'] == '1') {
	$tpl_informacion->newBlock("contentBlockLink");
	$tpl_informacion->assign("element_id","");
	$tpl_informacion->assign("element_class","$rowsClass $blockClass");
	$tpl_informacion->assign("element_href","getInfo_farmacias");
	$tpl_informacion->assign("element_href_class"," loadAjax");
	$tpl_informacion->assign("element_icono","icon-farmacias");
	$tpl_informacion->assign("element_text", LANG_INFO_FARMACIAS_TITLE);
	$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}

if ($configuracion_totem['cajero'] == '1') {
	// Bloque para información sobre los cajeros
	$tpl_informacion->newBlock("contentBlockLink");
	$tpl_informacion->assign("element_id","");
	$tpl_informacion->assign("element_class","$rowsClass $blockClass");
	$tpl_informacion->assign("element_href","getInfo_cajeros");
	$tpl_informacion->assign("element_href_class"," loadAjax");
	$tpl_informacion->assign("element_icono","icon-cajero");
	$tpl_informacion->assign("element_text", LANG_INFO_CAJEROS_TITLE);
	$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}
	
if ($configuracion_totem['gasolinera'] == '1') {
	// Bloque para información sobre los gasolineras
	$tpl_informacion->newBlock("contentBlockLink");
	$tpl_informacion->assign("element_id","");
	$tpl_informacion->assign("element_class","$rowsClass $blockClass");
	$tpl_informacion->assign("element_href","getInfo_gasolineras");
	$tpl_informacion->assign("element_href_class"," loadAjax");
	$tpl_informacion->assign("element_icono","icon-gasolinera");
	$tpl_informacion->assign("element_text", LANG_INFO_GASOLINERAS_TITLE);
	$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}

// MAREAS - OCULTO
/*
if ($configuracion_totem['mareas'] == '1') {
$tpl_informacion->newBlock("contentBlockLink");
$tpl_informacion->assign("element_id","");
$tpl_informacion->assign("element_class","$rowsClass $blockClass");
$tpl_informacion->assign("element_href","getInfo_mareas");
$tpl_informacion->assign("element_href_class"," loadAjax");
$tpl_informacion->assign("element_icono","icon-mareas");
$tpl_informacion->assign("element_text", LANG_INFO_MAREAS_TITLE);
$tpl_informacion->assign("index", "informacion".$blokeIndex++);
}
*/

    // Bloque comentado - Botón consulados oculto
    /*
    $tpl_informacion->newBlock("contentBlockLink");
    $tpl_informacion->assign("element_id","");
    $tpl_informacion->assign("element_class","$rowsClass $blockClass");
    $tpl_informacion->assign("element_href","getInfo_consulados");
    $tpl_informacion->assign("element_href_class"," loadAjax");
    $tpl_informacion->assign("element_icono","icon-consulados");
    $tpl_informacion->assign("element_text", LANG_INFO_CONSULADOS_TITLE);
    $tpl_informacion->assign("index", "informacion".$blokeIndex++);
    */


$tpl_index->assign("content_contenido", $tpl_informacion->getOutputContent());
?>
