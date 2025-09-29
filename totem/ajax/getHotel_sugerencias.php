<?php

//registrarLog("hotel", "sugerencias");
    
$tpl_sugerencia = new TemplatePower("plantillas/seccion_hotel_sugerencias.html", T_BYFILE);
$tpl_sugerencia->prepare();

$tpl_sugerencia->assign("lang_title", LANG_SUGERENCIAS_TITLE);
$tpl_sugerencia->assign("lang_atras", LANG_GLOBAL_ATRAS);
$tpl_sugerencia->assign("lang_enviar", LANG_GLOBAL_ENVIAR);
$tpl_sugerencia->assign("lang_sugerencias", LANG_SUGERENCIAS_TITLE);
$tpl_sugerencia->assign("lang_sugerencias_habitaciones", LANG_SUGERENCIAS_HABITACIONES);
$tpl_sugerencia->assign("lang_sugerencias_num_habitaciones", LANG_SUGERENCIAS_NUM_HABITACIONES);
$tpl_sugerencia->assign("lang_sugerencias_gracias", LANG_SUGERENCIAS_GRACIAS);



$idioma = $_SESSION['idioma'];

//Funcion que llama a la base de datos y devuelve el texto
 $texto_sugerencia = get_texto_sugerencia ($idioma);

$tpl_sugerencia->assign("texto_sugerencia", $texto_sugerencia['0']);

$tpl_sugerencia->assign("lang_gracias", LANG_GLOBAL_FORM_SUG);
// $resultado['datos'] = $tpl_sugerencia->getOutputContent();
// echo json_encode($resultado);
// exit();


// $db = new MySQL();
// //
// //mysql_connect("localhost", "root", "");
// //mysql_select_db("cuestionario");


// $consulta=$db->consulta("SELECT  cliente_cuestionario.id_cuestionario, cuestionario_seccion.id_cuestionario, cuestionario_seccion.id_seccion, cuestionario_seccion.nombre , cuestionario_preguntas.pregunta, cuestionario_preguntas.id_pregunta, cuestionario_preguntas.codigo 
// 						FROM  cliente_cuestionario , cuestionario_seccion, cuestionario_preguntas 
// 						WHERE cliente_cuestionario.id_cuestionario = cuestionario_seccion.id_cuestionario
// 						AND cuestionario_seccion.id_seccion = cuestionario_preguntas.id_seccion ORDER BY cuestionario_preguntas.orden");
	
// $j = 0;
// while ($fila = $db->fetch_array($consulta)) {
//     $array1[$j] = $fila;
//     $j++;
// }

// /* * ************asignamos el id al formulario ******************* */
// $tpl_sugerencia->assign(id_form, $array1['0']['id_cuestionario']);

// /* * *** Esta consulta devulve el nombre de las distintas secciones con su id ************* */
// $nombre_Seccion_ID = $db->consulta("SELECT cuestionario_seccion.nombre, cuestionario_seccion.id_seccion FROM cuestionario_seccion ORDER BY cuestionario_seccion.orden");
// $j = 0;
// while ($fila = $db->fetch_array($nombre_Seccion_ID)) 
// {
//     $array2[$j] = $fila;
//     $tpl_sugerencia->newBlock("pestanas");
//     $tpl_sugerencia->assign(n_id, $fila['id_seccion']);
//     $tpl_sugerencia->assign(n_pregunta, $fila['nombre']);
//     $j++;
// }
// foreach ($array2 as $seccion) 
// {
//     $tpl_sugerencia->newBlock("pestanas_content");
//     $tpl_sugerencia->assign(n_id, $seccion['id_seccion']);
//     $tpl_sugerencia->assign(n_pregunta, $seccion['nombre']);
//     $i = 1;

//     foreach ($array1 as $pregunta) 
//     {
//         if ($pregunta['id_seccion'] == $seccion['id_seccion']) 
//         {
//             if ($i % 2 != 0) 
//             {
//                 $tpl_sugerencia->newBlock("pregunta");
//             } 
//             else 
//             {
//                 $tpl_sugerencia->newBlock("preDerecho");
//             }
//             $i++;
//             //$tpl_sugerencia->assign(pregunta, $pregunta['pregunta']);				
//             $tpl_sugerencia->assign(codigo, str_replace('{id_pregunta}', $pregunta['id_pregunta'], $pregunta['codigo']));
//             //$tpl_sugerencia->assign(id_pregunta, $pregunta['id_pregunta']);
//         }
//     }
// }

$resultado['datos'] = $tpl_sugerencia->getOutputContent();

$id_centro = $_SESSION['id_centro'];
$imagen = "cuestionario_calidad.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;


echo json_encode($resultado);

?>
