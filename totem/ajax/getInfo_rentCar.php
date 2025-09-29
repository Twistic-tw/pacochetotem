<?php

$tpl_info_rentCar = new TemplatePower("plantillas/seccion_info_rentCar.html", T_BYFILE);
$tpl_info_rentCar->prepare();

registrarLog("informacion", "rentcar");

$resultado = array();
$id_centro = $_SESSION['id_centro'];

$tpl_info_rentCar->assign("rentCar_title", LANG_INFO_ALQUILER_TITLE);


$tpl_info_rentCar->assign("rentCar_titulo", "Namcar");
$tpl_info_rentCar->assign("rentCar_telefono", "(+34) 928 14 08 56");

switch ($_SESSION['idioma']) {
    case 1:
        $desc = "NAMCAR es una empresa joven, surgida del compromiso de un grupo de personas por implementar un proyecto largamente meditado de alquiler de coches y furgonetas. Un proyecto que se sabía exigente desde el comienzo, por el nivel y la competitividad del mercado turístico canario.";
        break;
    
    case 2:
        $desc = "NAMCAR is a young company emerging from the commitment of a group of people by implementing a well thought rental cars and vans project. A demanding project from the beginning, guided by the level and competitiveness of the tourism market in Canary Island.";
        break;
    
    case 3:
        $desc = "NAMCAR ist eine junge Firma, die sich aus dem Engagement einer Gruppe von Menschen durch die Umsetzung ein gut durchdachtes Mietwagen und Lieferwagen-Projekt. Eine anspruchsvolle Projekt von Anfang an, von der Höhe und der Wettbewerbsfähigkeit des Tourismus-Markt in Canary Island geführt.";
        break;

    default:
        $desc = "NAMCAR es una empresa joven, surgida del compromiso de un grupo de personas por implementar un proyecto largamente meditado de alquiler de coches y furgonetas. Un proyecto que se sabía exigente desde el comienzo, por el nivel y la competitividad del mercado turístico canario.";
        break;
}
$tpl_info_rentCar->assign("rentCar_desc", $desc);


$tpl_info_rentCar->assign("rentCar_logoSrc", "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/logoNamcar.gif");
$tpl_info_rentCar->assign("rentCar_imgSrc", "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/imgNamcar.jpg");

$tpl_info_rentCar->assign("lang_atras", LANG_GLOBAL_ATRAS);

$resultado['datos'] = $tpl_info_rentCar->getOutputContent();


$imagen = "rent_a_car.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;


echo json_encode($resultado);
?>
