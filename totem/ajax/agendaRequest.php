<?php

/** en este fichero se obtienen todos los eventos de un dia pasado por parametro ($_GET['fecha']
 * y se debe devolver el lsitado segun la estructura definida abajo */
session_start();

$id_centro = $_SESSION["id_centro"];

$fecha = $_GET['fecha'] ? $_GET['fecha'] : false;
$action = $_GET['request'];


//Para que de tres dias de la semana
//$action = 'dias_eventos';
//$fecha = date('Y-m-d');
$versionagenda = '1';

if ($action == "actividades2"){
    $datos = totem_getActividadesSemanas($fecha, $_SESSION['idioma']);
    echo json_encode($datos);
}

if ($action == "actividades3"){
    $datos = totem_getActividades3dias($_SESSION['id_centro'],$fecha, $_SESSION['idioma']);
    echo json_encode($datos);
}


if ($action == "dias_eventos")
{
    //peticion para todos los eventos de los proximos 6 meses. Se utiliza para pintar el datepicker
    echo totem_listado_actividades_agenda_json();
}
elseif ($action == "actividades")
{
    ///peticion para ver las actividades.
    $centro_id=$_SESSION['id_centro'];
    registrarLog("agenda");

    $datos = totem_listado_actividades_info($fecha,$_SESSION['idioma']);

    $evento_destacado = obtener_evento_destacado($fecha,$_SESSION['idioma']);

//   echo json_encode($evento_destacado);
//   exit();

    //if($versionagenda='0'){
    if ($fecha) {
        //si hay fecha es que deseamos ver el listado de actividades para el dia indicado

        $tpl_actividades_lista_hotel = new TemplatePower("plantillas/agenda_actividades_lista.html", T_BYFILE);
        $tpl_actividades_lista_hotel->prepare();

        $tpl_actividades_lista_hotel->assign('LANG_AGENDA_NO',LANG_AGENDA_NO);

        if ($datos)  $tpl_actividades_lista_hotel->assign('displayNone','displayNone');

        $fecha_actual_servidor = strtotime(date("d-m-Y"));
        $fecha_actual_parametro = strtotime($fecha);

        $hora_actual_servidor = strtotime(date("H:i:s"));


        foreach ($datos['hotel'] as $actividad) {

            $tpl_actividades_lista_hotel->newBlock("actividades_lista");

            //Icono para la categoria niños
            if($actividad['id_lugar']=='31'){
                $tpl_actividades_lista_hotel->assign('icono_actividad','kid.jpg');
                $tpl_actividades_lista_hotel->assign('css_color_kids','css_color_kids');
            }else{
                $tpl_actividades_lista_hotel->assign('icono_actividad','reloj_pinero.svg');
            }

            $tpl_actividades_lista_hotel->assign('ubicacion',LANG_AGENDA_UBICACION);
            $tpl_actividades_lista_hotel->assign('tipo',LANG_AGENDA_TIPO);
            $tpl_actividades_lista_hotel->assign('ubicacion_contenido',$actividad['lugar_nuevo']);
            $tpl_actividades_lista_hotel->assign('tipo_contenido',$actividad['cat_lugar']);




            //echo strtotime( $actividad['fecha_ini']." ".$actividad['hora_ini'])." VS ".strtotime($actividad['fecha_fin']." ".$actividad['hora_fin']);
            //echo "<br>".strtotime($fecha." ".date("H:i:s"));    
            $t_ini = strtotime($actividad['hora_ini']);
            $t_fin = strtotime($actividad['hora_fin']);

            if ($fecha_actual_parametro == $fecha_actual_servidor) { //si coincide el dia actual con el de la consulta pues marco
                if ($hora_actual_servidor >= $t_ini && $hora_actual_servidor <= $t_fin) {
                    $tpl_actividades_lista_hotel->assign("actividad_class", "active");
                }
            }

            if ($actividad['foto_evento'] == ''){ $actividad['foto_evento'] = 'transparente.png'; }

            $tpl_actividades_lista_hotel->assign("actividad_imagen", $actividad['foto_evento']);
            $tpl_actividades_lista_hotel->assign("actividad_time", $actividad['hora_ini']);
            $tpl_actividades_lista_hotel->assign("actividad_desc", $actividad['titulo_evento']);
            $tpl_actividades_lista_hotel->assign("actividad_time_fin", $actividad['hora_fin']);
            $tpl_actividades_lista_hotel->assign('descripcion',$actividad['contenido']);


            // Nuevo de numero habitacion
            $tpl_actividades_lista_hotel->assign("lang_habitacion", LANG_SUGERENCIAS_HABITACIONES);
            $tpl_actividades_lista_hotel->assign("lang_apellidos", LANG_CHEKIN_APELLIDO);
            $tpl_actividades_lista_hotel->assign("lang_enviar", LANG_GLOBAL_ENVIAR);
            $tpl_actividades_lista_hotel->assign("lang_nogracias", LANG_AGENDA_NOGRACIAS);
            $tpl_actividades_lista_hotel->assign("lang_gracias", LANG_MENU_VALORACION);
            $tpl_actividades_lista_hotel->assign("lang_valorar", LANG_AVISO_VALORACION);



            //comprobamos si el details contiene alguna imagen, en ese caso se modifica para que la ruta sea la correcta
            if (strpos($actividad['contenido'], "src") > 0){

                //$imagen_src = '..'.substr($img->src,22);

//                $tArray = explode('/',$_SERVER['PHP_SELF']);                  //puede ser /itourism_servidor o itourism_servidor
//                $scriptBasePath = $tArray[0] ? $tArray[0] : $tArray[1];       //si de entrada es null el primer parametro cojo el segundo
//                
//                $basePath = $scriptBasePath;
//                if ($scriptBasePath != "administracion" && $scriptBasePath != "totem"){
//                    $basePath = '/'.$scriptBasePath.'/administracion/';
//                }
//                else {
//                    $basePath = "/administracion/";
//                }
//                
//               //Aythami - Cambio de las rutas de las imagenes: ruta heredada del redactor 
                $actividad['contenido'] = str_replace('contenido_proyectos/twistic/', "", $actividad['contenido']);


                //echo "LA URL ES:".$actividad['contenido'];
            }


//            $actividad['contenido'] = cambia_src($actividad['contenido'], "/itourism_servidor/administracion/");
//            $tpl_actividades_lista_hotel->assign("actividad_detail", strip_tags($actividad['contenido']), "");
            $tpl_actividades_lista_hotel->assign("actividad_detail", strip_tags($actividad['contenido'], "<p><br><ul><li><ol><b><i><u><img><div>"));


            //echo json_encode($actividad);exit();

            $tpl_actividades_lista_hotel->assign("class_icon", "icon-svg " . $actividad['icon']);
            $tpl_actividades_lista_hotel->assign("class_icon_tipo", $actividad['cat_lugar']);
            $tpl_actividades_lista_hotel->assign("color", $actividad['color']);
            /*
            if ($actividad['icon_subcat']){
                $tpl_actividades_lista_hotel->assign("class_icon", "icon-svg ".$actividad['icon_subcat']);
            }
            else{
                $tpl_actividades_lista_hotel->assign("class_icon", "icon-svg ".$actividad['icon_cat']);
            }
            */

        }

        $n_eventos_destacados = count( $evento_destacado['hotel']);

        for ($i = 0; $i <= ($n_eventos_destacados-1); $i++) {
            //Imagen del show principal
            $tpl_actividades_lista_hotel->newBlock("actividad_destacada");

            $tpl_actividades_lista_hotel->assign("destacado_nombre", $evento_destacado['hotel'][$i]['titulo_evento']);
            $tpl_actividades_lista_hotel->assign("destacado_hora", $evento_destacado['hotel'][$i]['hora_ini']);

            $tpl_actividades_lista_hotel->assign("id_evento", $evento_destacado['hotel'][$i]['id']);

            $url_imagen = '../../../contenido_proyectos/pacoche/centro_'.$id_centro.'/imagenes/agenda/';
            $tpl_actividades_lista_hotel->assign("imagen_destacada", $url_imagen.$evento_destacado['hotel'][$i]['foto_evento']);

            if ($i == 0) {
                //Muestra el primer elemento solo
                $tpl_actividades_lista_hotel->assign("js_visible_destacado", "js_muestra_destacado");
            }else{
                $tpl_actividades_lista_hotel->assign("js_visible_destacado", "js_oculta_destacado");
            }

            if ($n_eventos_destacados > 1){

                //le pongo los thumbs y los css de tamaño

                $tpl_actividades_lista_hotel->assignGlobal("css_imagen_show_destacado_thumbs", "css_imagen_show_destacado_thumbs");

                $tpl_actividades_lista_hotel->newBlock("actividad_destacada_thumbs");

                $tpl_actividades_lista_hotel->assign("destacado_nombre", $evento_destacado['hotel'][$i]['titulo_evento']);
                $tpl_actividades_lista_hotel->assign("destacado_hora", $evento_destacado['hotel'][$i]['hora_ini']);

                $tpl_actividades_lista_hotel->assign("id_evento", $evento_destacado['hotel'][$i]['id']);

                $url_imagen = '../../../contenido_proyectos/pacoche/centro_'.$id_centro.'/imagenes/agenda/';
                $tpl_actividades_lista_hotel->assign("imagen_destacada", $url_imagen.$evento_destacado['hotel'][$i]['foto_evento']);

//                if ($i == 0) {
//                    //Muestra el primer elemento solo
//                    $tpl_actividades_lista_hotel->assign("css_thumb_active", "css_thumb_active");
//                }


            }else{
                $tpl_actividades_lista_hotel->assignGlobal("css_oculta_thumbs", "css_oculta_thumbs");
            }
        }

        /////////////////


        $tpl_actividades_lista_hotel->assignGlobal("centro_id", $centro_id);
        $tpl_actividades_lista_hotel->assignGlobal("lang_info", LANG_GLOBAL_INFO);
        $listaActividades['actividadesHotel'] = $tpl_actividades_lista_hotel->getOutputContent();

        echo json_encode($listaActividades);


    }
}

?>
