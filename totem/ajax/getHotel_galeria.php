<?php

$idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

registrarLog("hotel", "galeria");

$tpl_galeria = new TemplatePower("plantillas/seccion_hotel_galeria.html", T_BYFILE);
$tpl_galeria->prepare();

$db = new MySQL();

$tpl_galeria->assign("title", LANG_GALERIA_TITLE);
$tpl_galeria->assign("lang_atras", LANG_GLOBAL_ATRAS);

$dirPath = "../../../contenido_proyectos/vistaflor/centro_$id_centro/galeria/";

if (is_dir($dirPath) )
{
    $dirHandler = opendir($dirPath);
    $tpl_galeria->newBlock("galeria");      //es el bloque para la primera galeria.
    $tpl_galeria->assign("galeria_id", 1);
    
    while ( false !== ( $file = readdir($dirHandler) ) )
    {
        if( is_file($dirPath.$file) && $file != "." && $file != ".." && $file != "Thumbs.db" ){
            $tpl_galeria->newBlock("galeria_imagen");
            $tpl_galeria->assign("imgSrc", $dirPath.$file); 
        }
    }
}

//comprobamos si existen mas galerias definidas, si es ais genero en primer lugar el primer link del tabs, 
//y luego voy generando las n tabs.

$galeriasFotos = totem_getGalerias();


if ( !empty($galeriasFotos) && count($galeriasFotos) > 0) {
    $tpl_galeria->gotoBlock(TP_ROOTBLOCK);
    $tpl_galeria->newBlock("galeria_link");
    $tpl_galeria->assign("galeria_id", 1);
    $tpl_galeria->assign("galeria_link_text", LANG_TEMP_INSTALACIONES);
    
    $index = 1;
    foreach ($galeriasFotos as $galeria) 
    {
        //recorro todas las galerias. Para cada una abro el directorio que tiene asignado
        //en /contenido/centro_$id/galeria/galeria_$id/ y compruebo que haya al menos 
        //1 imagen. En ese caso genero el bloque de <li> para el tabs y el contenedor
        //de la pestaña. Posteriormente voy generando los bloques de imagenes internos.
        
        $index++;
        $galeriaDir = $dirPath.$galeria['id']."/";
        
       // $resultado['g'][] = $galeriaDir; 

        if (is_dir($galeriaDir) )
        {
            $dirHandler = opendir($galeriaDir);
            $lockForNewLink = true;
            
            while ( false !== ( $file = readdir($dirHandler) ) )
            {
                if( is_file($galeriaDir.$file) && $file != "." && $file != ".." && $file != "Thumbs.db" ){


                    if ($lockForNewLink)
                    {
                        $lockForNewLink = false;
                        $tpl_galeria->newBlock("galeria_link");
                        $tpl_galeria->assign("galeria_id", $index);
                        $tpl_galeria->assign("galeria_link_text", $galeria['nombre']);
                        
                        $tpl_galeria->newBlock("galeria");
                        $tpl_galeria->assign("galeria_id", $index);
                    }
                    
                    $tpl_galeria->newBlock("galeria_imagen");
                    $tpl_galeria->assign("imgSrc", $galeriaDir.$file); 
                }
            }
        }
    }
}

$resultado['datos'] = $tpl_galeria->getOutputContent();

echo json_encode($resultado);
?>
