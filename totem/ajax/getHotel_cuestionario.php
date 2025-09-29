<?php


// include 'lib_totem.php';

registrarLog("hotel", "cuestionario");

session_start();
$id_idioma = $_SESSION['idioma'];


    
// $tpl_cuestionario = new TemplatePower("plantillas/seccion_hotel_cuestionario_static_OLD.html", T_BYFILE);
$tpl_cuestionario = new TemplatePower("plantillas/seccion_hotel_cuestionario_static.html", T_BYFILE);
$tpl_cuestionario->prepare();


// Ruta para las imagenes
$path = "../../../contenido_proyectos/pacoche/_general/cuestionario/";

// Obtener todas las secciones del cuestionario
$array_secciones = get_secciones_cuestionario($id_idioma);


// Asignaciones Comunes
$tpl_cuestionario->assign("lang_title", LANG_CUESTIONARIO_TITLE);
$tpl_cuestionario->assign("lang_atras", LANG_GLOBAL_ATRAS);
$tpl_cuestionario->assign("lang_siguiente", LANG_GLOBAL_SIGUIENTE);
$tpl_cuestionario->assign("lang_enviar", LANG_GLOBAL_ENVIAR);
$tpl_cuestionario->assign("lang_gracias", LANG_GLOBAL_FORM_REGIST);


// Se recorre las secciones (nombre, imagen, observaciones-visible o no-) y se crean las preguntas por seccion
foreach ($array_secciones as $seccion) {

    $tpl_cuestionario->newBlock("bloque_cuestionario");
    $tpl_cuestionario->assign("nombre_bloque_cuestionario", $seccion["nombre"]);
    $tpl_cuestionario->assign("bloque_imagen", $path.$seccion["imagen"]);

    // Si la seccion tiene el campo observaciones a 0 no se muestra
    if ($seccion["obs_visible"] == 0)
    $tpl_cuestionario->assign("obervaciones_visible", "displayNone");
    // Si se muestra, entonces se busca en la base de datos el nombre del campo observaciones de la seccion y la id que le corresponde
    else
    {

        $array_observaciones = get_nombre_observacion_seccion($id_idioma,$seccion["id_seccion"]);

        $tpl_cuestionario->assign("observaciones_nombre", $array_observaciones["0"]["nombre"]);
        $tpl_cuestionario->assign("observaciones_id", $array_observaciones["0"]["id_pregunta"]);

    }

    $array_seccion_preguntas = get_secciones_cuestionario_preguntas($id_idioma,$seccion["id_seccion"]);

    foreach ($array_seccion_preguntas as $seccion_preguntas) 
    {
        $tpl_cuestionario->newBlock("linea_cuestionario");
        $tpl_cuestionario->assign("linea_cuestionario", $seccion_preguntas["nombre"]);
        $tpl_cuestionario->assign("numero_linea", $seccion_preguntas["id_pregunta"]);
    }


}

$tpl_cuestionario->gotoBlock( "_ROOT" );

$idioma = $_SESSION['idioma'];

$tpl_cuestionario->assign("lang_sugerencias", LANG_SUGERENCIAS_TITLE);
$tpl_cuestionario->assign("lang_sugerencias_habitaciones", LANG_SUGERENCIAS_HABITACIONES);
$tpl_cuestionario->assign("lang_sugerencias_num_habitaciones", LANG_SUGERENCIAS_NUM_HABITACIONES);
$tpl_cuestionario->assign("lang_sugerencias_gracias", LANG_SUGERENCIAS_GRACIAS);

$texto_sugerencia = get_texto_sugerencia ($idioma);

$tpl_cuestionario->assign("texto_sugerencia", $texto_sugerencia['0']);

$tpl_cuestionario->assign("lang_gracias", LANG_GLOBAL_FORM_SUG);




$resultado['datos'] = $tpl_cuestionario->getOutputContent();
echo json_encode($resultado);
exit();



?>
