<?php

$tpl_compras_contenido_dinamico = new TemplatePower("plantillas/seccion_actividad_contenido_dinamico.html", T_BYFILE);
$tpl_compras_contenido_dinamico->prepare();

$seccionId = $_GET['seccion'];

if ($seccionId) {
    
    registrarLog("compras", $seccionId);
    
    $seccion = totem_getComerciosForSection($seccionId); //pido los datos
    
    $tpl_compras_contenido_dinamico->assign("title", $seccion[0]['nombre_seccion']);
    $tpl_compras_contenido_dinamico->assign("lang_atras", LANG_GLOBAL_ATRAS);
    
    
    foreach ($seccion as $comercioDetail) 
    {
        switch ($comercioDetail['tipo']) 
        {
            case "destacado":
                $tpl_compras_contenido_dinamico->newBlock("comercio_destacado");
                $tpl_compras_contenido_dinamico->assign("id", $comercioDetail['id_comercio']);
                $tpl_compras_contenido_dinamico->assign("comercio_nombre", $comercioDetail['nombre']);
                $tpl_compras_contenido_dinamico->assign("comercio_direccion", $comercioDetail['direccion']);
                $tpl_compras_contenido_dinamico->assign("comercio_horario", $comercioDetail['horario']);
                $tpl_compras_contenido_dinamico->assign("comercio_telefono", $comercioDetail['telefono']);
                $tpl_compras_contenido_dinamico->assign("comercio_url", $comercioDetail['web']);
                if ( !empty($comercioDetail['logo']) )
                {
                    $tpl_compras_contenido_dinamico->newBlock("logo2");
                    $tpl_compras_contenido_dinamico->assign("comercio_logo", "../../../contenido_proyectos/vistaflor/_comercios/".$comercioDetail['logo']);
                }
                else
                {
                    $tpl_compras_contenido_dinamico->assign("extra_class", "icon-svg setBgColor " . $comercioDetail['class_icon']);
                }
                
                break;

            case "super destacado":
                $tpl_compras_contenido_dinamico->newBlock("comercio_superdestacado");
                $tpl_compras_contenido_dinamico->assign("id", $comercioDetail['id_comercio']);
                $tpl_compras_contenido_dinamico->assign("comercio_nombre", $comercioDetail['nombre']);
                $tpl_compras_contenido_dinamico->assign("comercio_direccion", $comercioDetail['direccion']);
                $tpl_compras_contenido_dinamico->assign("comercio_horario", $comercioDetail['horario']);
                $tpl_compras_contenido_dinamico->assign("comercio_telefono", $comercioDetail['telefono']);
                $tpl_compras_contenido_dinamico->assign("comercio_url", $comercioDetail['web']);
                if ( !empty($comercioDetail['logo']) )
                {
                    $tpl_compras_contenido_dinamico->newBlock("logo");
                    $tpl_compras_contenido_dinamico->assign("comercio_logo", "../../../contenido_proyectos/vistaflor/_comercios/".$comercioDetail['logo']);
                }
                else
                {
                    $tpl_compras_contenido_dinamico->assign("extra_class", "icon-svg setBgColor " . $comercioDetail['class_icon']);
                }
                
                break;
                
            default:
                $tpl_compras_contenido_dinamico->newBlock("comercio");
                $tpl_compras_contenido_dinamico->assign("comercio_nombre", $comercioDetail['nombre']);
                $tpl_compras_contenido_dinamico->assign("comercio_direccion", $comercioDetail['direccion']);
                $tpl_compras_contenido_dinamico->assign("lang_info", LANG_GLOBAL_INFO);
                break;
        }
    }
    $tpl_compras_contenido_dinamico->assignGlobal("lang_info", LANG_GLOBAL_INFO);
    $resultado['datos'] = $tpl_compras_contenido_dinamico->getOutputContent();


//    $dir = "http://192.168.251.3/itourism_servidor/administracion/images/contenidos/cabecera/";
//    $resultado['banner_superior'] = $dir.$seccion['foto_cabecera'];

    echo json_encode($resultado);
}

?>
