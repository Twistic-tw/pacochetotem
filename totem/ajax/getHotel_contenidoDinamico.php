<?php
ob_start();
// Log fatal errors on shutdown to understand empty outputs
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        error_log('[getHotel_contenidoDinamico][FATAL] '. $e['message'] .' in '. $e['file'] .':'. $e['line']);
    }
    $len = ob_get_length();
    if ($len === 0) {
        $payload = array('error' => true, 'mensaje' => 'Salida vacía del endpoint', 'fatal' => $e ? $e['message'] : null);
        if (isset($_GET['debug']) && $_GET['debug']) {
            $payload['debug'] = array(
                'php_errors' => isset($GLOBALS['__ghcd_errors']) ? $GLOBALS['__ghcd_errors'] : array(),
                'sql' => isset($GLOBALS['__ghcd_sql']) ? $GLOBALS['__ghcd_sql'] : array(),
            );
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
    }
});

// Capture warnings/notices to include in debug payload when requested
$__ghcd_errors = array();
set_error_handler(function($severity, $message, $file, $line) use (&$__ghcd_errors) {
    // Respect @ operator
    if (!(error_reporting() & $severity)) {
        return false;
    }
    $entry = '['. $severity .'] '. $message .' in '. $file .':'. $line;
    $GLOBALS['__ghcd_errors'][] = $entry;
    error_log('[getHotel_contenidoDinamico][PHP] '. $entry);
    return false; // allow normal handling too
});
// Helper para logueo consistente
if (!function_exists('ghcd_log')) {
    function ghcd_log($msg) {
        error_log('[getHotel_contenidoDinamico] ' . $msg);
    }
}
if (isset($_GET['debug']) && $_GET['debug']) {
    ghcd_log('ENTER file='.__FILE__.' dir='.__DIR__.' cwd='.(function_exists('getcwd')?getcwd():'n/a'));
}
// Debug no intrusivo: activar con &debug=1 para registrar sin romper JSON
if (isset($_GET['debug']) && $_GET['debug']) {
    ghcd_log('params='.json_encode($_GET));
}

$contenidoId = $_GET['contenidoId'];
$forceDisplay = isset($_GET['force'])? $_GET['force'] : 0;
if (isset($_GET['debug']) && $_GET['debug']) {
    ghcd_log('session id_centro='.(isset($_SESSION['id_centro'])?$_SESSION['id_centro']:'null').' idioma='.(isset($_SESSION['idioma'])?$_SESSION['idioma']:'null'));
    ghcd_log('parsed params contenidoId='.$contenidoId.' force='.$forceDisplay);
}

if ($contenidoId) {
    
    
    if ( $forceDisplay )
    {
        if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('CALL totem_getContenidoEspecifico('.$contenidoId.')'); }
        registrarLog("hotel", false, $contenidoId, "Se esta accediendo al contenido propio de la categoria");
        $contenido = totem_getContenidoEspecifico($contenidoId);    //pillo solo los datos del contenido concreto.
    }
    else 
    {
        if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('CALL totem_getContenido('.$contenidoId.')'); }
        registrarLog("hotel", $contenidoId, false, "Se esta accediendo al conteido general de la categoria");
        $contenido = totem_getContenido($contenidoId); //pido los datos 
    }
    if (isset($_GET['debug']) && $_GET['debug']) {
        $cnt = is_array($contenido) ? count($contenido) : 0;
        ghcd_log("datos recibidos filas=$cnt");
        if ($cnt>0) {
            $sample = $contenido[0];
            ghcd_log('primer registro keys='.implode(',', array_keys($sample)));
        }
    }
        
    /*Si el tamaño de contenido es 1 significa que sólamente tiene contenido por sí mismo o que posee
    * un solo hijo por lo tanto lo muestre 
    */
    if ( is_array($contenido) && count($contenido) == 1) 
    { 
        if (isset($_GET['debug']) && $_GET['debug']) {
            ghcd_log('branch=UNICO_CONTENIDO');
            ghcd_log('class_exists(TemplatePower)='.(class_exists('TemplatePower')?'1':'0'));
            $seccionTpl = __DIR__ . "/plantillas/seccion_hotel_contenido_dinamico.html";
            ghcd_log('tpl path seccion_hotel_contenido_dinamico='. $seccionTpl .' exists='.(file_exists($seccionTpl)?'1':'0'));
        }
        $tpl_hotel_contenido_dinamico = new TemplatePower("plantillas/seccion_hotel_contenido_dinamico.html", T_BYFILE);
        $tpl_hotel_contenido_dinamico->prepare();
        if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('template seccion prepared'); }
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
        if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('banner path='.$resultado['banner_superior']); }
    }
    else
    {
        //Aquí me llega un array que se convertirá en bloques de menú
        if (!is_array($contenido) || count($contenido) == 0) {
            $resultado = array(
                'error' => true,
                'mensaje' => 'Sin resultados para la categoría/contenido solicitado',
            );
            if (isset($_GET['debug']) && $_GET['debug']) {
                error_log("[getHotel_contenidoDinamico] sin resultados contenidoId=$contenidoId force=$forceDisplay");
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($resultado);
            exit;
        }
        
        if (isset($_GET['debug']) && $_GET['debug']) {
            ghcd_log('branch=LISTADO_CONTENIDOS');
            $paginaTpl = __DIR__ . "/plantillas/pagina_hotel.html";
            ghcd_log('tpl path pagina_hotel='. $paginaTpl .' exists='.(file_exists($paginaTpl)?'1':'0'));
        }
        $tpl_hotel_contenido_dinamico = new TemplatePower("plantillas/pagina_hotel.html", T_BYFILE);
        $tpl_hotel_contenido_dinamico->prepare();
        if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('template pagina prepared'); }
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
    if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('HTML datos length='.strlen($resultado['datos'])); }
    
    if (isset($_GET['debug']) && $_GET['debug']) {
        $resultado['debug'] = array(
            'contenido_count' => is_array($contenido) ? count($contenido) : 0,
            'force' => (int)$forceDisplay,
            'id_centro' => isset($_SESSION['id_centro']) ? $_SESSION['id_centro'] : null,
            'idioma' => isset($_SESSION['idioma']) ? $_SESSION['idioma'] : null,
            'php_errors' => isset($GLOBALS['__ghcd_errors']) ? $GLOBALS['__ghcd_errors'] : array(),
            'sql' => isset($GLOBALS['__ghcd_sql']) ? $GLOBALS['__ghcd_sql'] : array(),
        );
    }
    $jsonOut = json_encode($resultado);
    if (isset($_GET['debug']) && $_GET['debug']) { ghcd_log('JSON length='.strlen($jsonOut).' preview='.substr($jsonOut,0,300)); }
    header('Content-Type: application/json; charset=utf-8');
    echo $jsonOut;
    exit;
}
else {
    $resultado = array('error' => true, 'mensaje' => 'contenidoId requerido');
    if (isset($_GET['debug']) && $_GET['debug']) {
        $resultado['debug'] = array('php_errors' => isset($GLOBALS['__ghcd_errors']) ? $GLOBALS['__ghcd_errors'] : array());
        error_log('[getHotel_contenidoDinamico] falta contenidoId');
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($resultado);
    exit;
}
?>
