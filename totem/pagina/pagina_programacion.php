<?php

/* este fichero se incluye desde index.php; tiene acceso a $tpl_index para asignarle valor
 * a {content_contenido}. Se maneja fundamentalmente desde ajax. (agendaRequest.php)
 */
$tpl_programacion = new TemplatePower("plantillas/pagina_programacion.html", T_BYFILE);
$tpl_programacion->prepare();

$tpl_programacion->assign('titulo_pagina',LANG_PROGRAMACION_TITLE);

//$fecha = date('Y-m-d', strtotime("+4 day"));

$fecha = date('Y-m-d');

$datos = totem_getActividades7dias($_SESSION['id_centro'],$fecha, $_SESSION['idioma']);


// Mensaje si no existen eventos
$tpl_programacion->assign('LANG_AGENDA_NO',LANG_AGENDA_NO);
if ($datos)  $tpl_programacion->assign('displayNone','displayNone');

$url= '../../../contenido_proyectos/vistaflor/centro_'.$_SESSION['id_centro'].'/imagenes/agenda/';

foreach ($datos as $tipo) {

	foreach ($tipo as $dia => $valor ) 
	{
		$tpl_programacion->newBlock("dia");

		$dia_semana =  strftime('%A  %d/%m/%Y ', strtotime($dia));

		$tpl_programacion->assign('fecha',$dia_semana);
		//$tpl_programacion->assign('titulo',$evento[$val]['titulo_evento']);
		//$tpl_programacion->assign('contenido',$evento[$val]['contenido']);

		$numero_eventos = count($valor);

		foreach ($valor as $evento)
		{
			$tpl_programacion->newBlock("evento");
			$tpl_programacion->assign('titulo',$evento['titulo_evento']);
			$tpl_programacion->assign('contenido',$evento['contenido']);
			$tpl_programacion->assign('hora',$evento['hora_ini']);

			$tpl_programacion->assign('idioma',$_SESSION['idioma']);


			$tpl_programacion->assign('foto_evento',$url.$evento['foto_evento']);
		
			switch ($numero_eventos) {
				case '1':
					$tpl_programacion->assign('tamano_tarjeta','programacion_bloque_1');
					break;
				case '2':
					$tpl_programacion->assign('tamano_tarjeta','programacion_bloque_2');
					break;

                case '4':
                    $tpl_programacion->assign('tamano_tarjeta','programacion_bloque_4');
                    break;

                //Por defecto esta el de 3
				default:
					$tpl_programacion->assign('tamano_tarjeta','');
					break;
			}

		}


	}

	//$i++;


}

$tpl_index->assign("content_contenido", $tpl_programacion->getOutputContent());

//echo json_encode($datos);

?>

