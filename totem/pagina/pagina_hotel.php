<?php

$id_centro = $_SESSION['id_centro'];


$tpl_hotel = new TemplatePower("plantillas/pagina_hotel.html", T_BYFILE);
$tpl_hotel->prepare();

$tpl_hotel->assign("hotel_title", LANG_HOTEL_TITLE);
$tpl_hotel->assign("id_hotel", "hotelWrapper");

//el array tiene array(id_cat, nombre, class_icon, id_idioma, id_centro
$secciones = totem_getSectionsForHotel(1);
$numeroSecciones = count($secciones) + 3;  //devuelve un array asociativo y secuencia y luego 3 secciones fijas

$blokeIndex = 1;

//echo $numeroSecciones;
        
if ($numeroSecciones>5){
    $rowsClass = "tworow ";
    
    $blockClass = "block_" . ceil( $numeroSecciones/2 );
}
else {
    $rowsClass = "onerow ";
    $blockClass = "block_" . $numeroSecciones;
}

//Puesto a pelo xq queda mas bonito en bloque de 4, para 8 secciones que son las que hay
 $blockClass = "block_4";


foreach ($secciones as $seccionDetail) {

    $tpl_hotel->newBlock("contentBlockLink");
    $tpl_hotel->assign("element_id", "");
    $tpl_hotel->assign("element_class", "$rowsClass $blockClass");
    $tpl_hotel->assign("element_href", "getHotel_contenidoDinamico&contenidoId=".$seccionDetail['id_cat']);
    $tpl_hotel->assign("element_href_class", "loadAjax");
    $tpl_hotel->assign("element_icono", $seccionDetail['class_icon']);
    $tpl_hotel->assign("element_text", $seccionDetail['nombre']);
    $tpl_hotel->assign("index", "hotel".$blokeIndex++);
}

//genero las categorias FIJAS

//postales
$tpl_hotel->newBlock("contentBlockLink");
$tpl_hotel->assign("element_id", "");
$tpl_hotel->assign("element_class", "$rowsClass $blockClass");
$tpl_hotel->assign("element_href", "getHotel_postales");
$tpl_hotel->assign("element_href_class", "loadAjax");
$tpl_hotel->assign("element_icono", "icon-postales");
$tpl_hotel->assign("element_text", LANG_POSTALES_TITLE);
$tpl_hotel->assign("index", "hotel".$blokeIndex++);

//galeria
$tpl_hotel->newBlock("contentBlockLink");
$tpl_hotel->assign("element_id", "");
$tpl_hotel->assign("element_class", "$rowsClass $blockClass");
$tpl_hotel->assign("element_href", "getHotel_galeria");
$tpl_hotel->assign("element_href_class", "loadAjax");
$tpl_hotel->assign("element_icono", "icon-galeria");
$tpl_hotel->assign("element_text", LANG_GALERIA_TITLE);
$tpl_hotel->assign("index", "hotel".$blokeIndex++);


//$html = "";
$tpl_index->assign("content_contenido", $tpl_hotel->getOutputContent());
?>
