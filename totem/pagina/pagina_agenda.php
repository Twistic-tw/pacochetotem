<?php

/* este fichero se incluye desde index.php; tiene acceso a $tpl_index para asignarle valor
 * a {content_contenido}. Se maneja fundamentalmente desde ajax. (agendaRequest.php)
 */
$tpl_agenda = new TemplatePower("plantillas/pagina_agenda.html", T_BYFILE);
$tpl_agenda->prepare();

$tpl_agenda->assign("agenda_disponible", LANG_AGENDA_DISPONIBLE);

$tpl_agenda->assign("agenda_title", LANG_AGENDA_TITLE);
$tpl_agenda->assign("lang_agendaHotel", LANG_AGENDA_HOTEL);

$isla = "Gran Canaria";


//Dame todos los dias desde hoy hasta 14dias mas
for ($i = 0; $i <= 6; $i++) {

    $tpl_agenda->newBlock("listado_dias");

    $tiempo = mktime(0, 0, 0, date("m"), date("d")+$i, date("Y"));

    $dia = date( "d", $tiempo  );
    $mes = strftime( "%b", $tiempo );
    $dia_nombre =  utf8_encode( substr( strftime("%A",$tiempo) ,0,2 ) );

    $dia_nombre = str_replace('Ã','A',$dia_nombre);

    $fecha = date( "d-m-Y", $tiempo );

    $tpl_agenda->assign("fecha", $fecha);
    $tpl_agenda->assign("dia", $dia);
    $tpl_agenda->assign("mes", $mes);
    $tpl_agenda->assign("dia_nombre", $dia_nombre);
    $tpl_agenda->assign("i", $i);
}


$tpl_agenda->assign("isla_nombre", $isla);

$tpl_index->assign("content_contenido", $tpl_agenda->getOutputContent());
$tpl_index->assign("content_class", "agendaTabs");              
?>

