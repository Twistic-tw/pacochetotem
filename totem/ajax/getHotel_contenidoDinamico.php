<?php

$contenidoId = $_GET['contenidoId'];
$forceDisplay = isset($_GET['force'])? $_GET['force'] : 0;

if ($contenidoId) {
    
    
    if ( $forceDisplay )
    {
        registrarLog("hotel", false, $contenidoId, "Se esta accediendo al contenido propio de la categoria");
        $contenido = totem_getContenidoEspecifico($contenidoId);    //pillo solo los datos del contenido concreto.
    }
    else 
    {
        registrarLog("hotel", $contenidoId, false, "Se esta accediendo al conteido general de la categoria");
        $contenido = totem_getContenido($contenidoId); //pido los datos 
    }
        
    /*Si el tamaño de contenido es 1 significa que sólamente tiene contenido por sí mismo o que posee
    * un solo hijo por lo tanto lo muestre 
    */
    if ( count($contenido) == 1) 
    { 
        $tpl_hotel_contenido_dinamico = new TemplatePower("plantillas/seccion_hotel_contenido_dinamico.html", T_BYFILE);
        $tpl_hotel_contenido_dinamico->prepare();
        $tpl_hotel_contenido_dinamico->assign("informacion_title", $contenido[0]['nombre']);
        $tpl_hotel_contenido_dinamico->assign("lang_atras", LANG_GLOBAL_ATRAS);

        //Poner la calidad del agua en la seccion piscinas de don gregory
        if ( ($_SESSION['id_centro'] == '1901') && ($contenidoId == '45') )  {

            $piscinas = get_piscinas();

            foreach ($piscinas as $piscina){

                $tpl_hotel_contenido_dinamico->newBlock("piscinas");

                $tpl_hotel_contenido_dinamico->assignGlobal("tph", LANG_PISCINAS_PH);
                $tpl_hotel_contenido_dinamico->assignGlobal("ttemperatura", LANG_PISCINAS_TEMPERATURA);

                $tpl_hotel_contenido_dinamico->assign("id_centro", $_SESSION['id_centro']);

                $tpl_hotel_contenido_dinamico->assign("nombre", $piscina['nombre']);
                $tpl_hotel_contenido_dinamico->assign("id_piscina", $piscina['id_piscina']);
                $tpl_hotel_contenido_dinamico->assign("ph", $piscina['ph']);
                $tpl_hotel_contenido_dinamico->assign("temperatura", $piscina['temperatura']);
                if ($piscina['id_piscina']=="3"){
                    $tpl_hotel_contenido_dinamico->assign("tcloro", "Br");
                }else{
                    $tpl_hotel_contenido_dinamico->assign("tcloro", LANG_PISCINAS_CLORO);
                }
                $tpl_hotel_contenido_dinamico->assign("cloro", $piscina['cloro']);


            }

            $tpl_hotel_contenido_dinamico->gotoBlock(TP_ROOTBLOCK);

        }else{
            $tpl_hotel_contenido_dinamico->assignGlobal("a_calidad_agua", '<!--');
            $tpl_hotel_contenido_dinamico->assignGlobal("c_calidad_agua", '-->');
        }
    
        // contenidoId 36 es foto galeria

        $contenidos_id_filtrado = array(12,14,40,44,45,46,47,48,36);

        if ( !in_array($contenidoId, $contenidos_id_filtrado) ) {
//            $contenido[0]['content'] = cambia_src2($contenido[0]['content'], $dir);
//            $contenido[0]['content'] = cambia_hr($contenido[0]['content']);
        }

        $tpl_hotel_contenido_dinamico->assign("content",$contenido[0]['content']);
        $tpl_hotel_contenido_dinamico->assign("clase_extra", $contenido[0]['clase_extra']);
        

//        $dir = "http://192.168.251.3/itourism_servidor/administracion/images/contenidos/cabecera/";
        $idCentro = $_SESSION['id_centro'];
        $dir = "../../../contenido_proyectos/pacoche/centro_$idCentro/imagenes/cabecera/";
        $resultado['banner_superior'] = $dir . $contenido[0]['foto_cabecera'];
    }
    else
    {
        //Aquí me llega un array que se convertirá en bloques de menú
        
        $tpl_hotel_contenido_dinamico = new TemplatePower("plantillas/pagina_hotel.html", T_BYFILE);
        $tpl_hotel_contenido_dinamico->prepare();
        $tpl_hotel_contenido_dinamico->assign("hotel_title", $contenido[0]['nombre_padre']);
        $tpl_hotel_contenido_dinamico->assign("extraClass", "closeWrapper");
        $tpl_hotel_contenido_dinamico->newBlock("back");
        $tpl_hotel_contenido_dinamico->assign("lang_atras", LANG_GLOBAL_ATRAS);
        $tpl_hotel_contenido_dinamico->gotoBlock(TP_ROOTBLOCK);
    
        $blokeIndex = 1;
        $nombreId = time();

        $numeroSecciones = count($contenido);
        if ($numeroSecciones>5)
        {
            $rowsClass = "tworow ";
            $blockClass = "block_" . ceil($numeroSecciones/2);
        }
        else 
        {
            $rowsClass = "onerow ";
            $blockClass = "block_" . $numeroSecciones;
        }

        for ( $i=0; $i < count($contenido); $i++ ) 
        {
            $tpl_hotel_contenido_dinamico->newBlock("contentBlockLink");

            $tpl_hotel_contenido_dinamico->assign("element_id", "");
            
            if ($contenido[$i]['id_cat'] == $contenido[$i]['id_categoria']) 
            {
                $tpl_hotel_contenido_dinamico->assign("element_href", "getHotel_contenidoDinamico&contenidoId=".$contenido[$i]['id_contenido']."&force=1");
            }
            else 
            {
                $tpl_hotel_contenido_dinamico->assign("element_href", "getHotel_contenidoDinamico&contenidoId=".$contenido[$i]['id_cat']);
            }
            
            $tpl_hotel_contenido_dinamico->assign("element_class", "$rowsClass $blockClass");
            $tpl_hotel_contenido_dinamico->assign("element_href_class", "loadAjax");
            $tpl_hotel_contenido_dinamico->assign("element_icono", $contenido[$i]['class_icon']);
            $tpl_hotel_contenido_dinamico->assign("element_text", $contenido[$i]['nombre']);
            $tpl_hotel_contenido_dinamico->assign("index", $nombreId."-".$blokeIndex++);
        }
        
        $resultado['extra'] = $contenido;
    }

    $resultado['datos'] = $tpl_hotel_contenido_dinamico->getOutputContent();
    
    echo json_encode($resultado);
}
?>
