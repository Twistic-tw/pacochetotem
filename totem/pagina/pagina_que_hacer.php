<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$url_comun = $config['url_comun']['url_comun'];



$tpl_queHacer = new TemplatePower("plantillas/pagina_que_hacer.html", T_BYFILE);
$tpl_queHacer->prepare();

$tpl_queHacer->assign("queHacer_title", LANG_QUE_HACER_TITLE);

//Todas las categorias que tienen algun comercio asociado que no este caducada la programacion
//$categorias_que_hacer = get_categorias_con_quehacer($idioma);
$categorias_que_hacer = get_categorias_con_quehacer2($_SESSION['id_centro'],$idioma);

//Titulo del filtro todos
$tpl_queHacer->assign("todos_title", LANG_QUE_HACER_TODOS);

foreach ($categorias_que_hacer as $categoria) {
    $tpl_queHacer->newBlock("filtros");
    $tpl_queHacer->assign("filtro_nombre", $categoria['nombre_categoria']);

    $tipo = str_replace(' ', '_', $categoria['nombre_categoria'] );
    $tipo = str_replace('&', '', $tipo);

    $tpl_queHacer->assign("filtro", $tipo );
}

/*$tpl_queHacer->assign("compras_title", LANG_COMPRAS_TITLE);
$tpl_queHacer->assign("ocio_title", LANG_COMEROCIO_TITLE);
$tpl_queHacer->assign("act_title", LANG_ACTIVIDAD_TITLE);
$tpl_queHacer->assign("alquiler_title", LANG_ALQUILER_TITLE);*/

//$secciones = get_todos_comercios();

$secciones = get_todos_comercios2($_SESSION['id_centro']);


if ( count($secciones) > '6' ){
    $tpl_queHacer->assignGlobal("double_size_que_hacer", "double_size");
}

foreach ($secciones as $seccionDetail) {
    $tpl_queHacer->newBlock("contentBlockLink");

    $tpl_queHacer->assign("nombre", $seccionDetail['nombre']);
    $tpl_queHacer->assign("direccion", $seccionDetail['direccion']);
    $tpl_queHacer->assign("telefono", $seccionDetail['telefono']);

    $tpl_queHacer->assign("web", $seccionDetail['web']);


    //Pedimos la categoria a la que pertenece el comercio
    //$categoria = get_categoria_comercio($seccionDetail['id_seccion'],$idioma);
    $categoria = get_categoria_comercio2($seccionDetail['id_seccion'],$idioma);

    //Con la categoria hacemos el filtro
    $tipo_que_hacer = str_replace(' ', '_', $categoria['0']['nombre_categoria'] );
    $tipo_que_hacer = str_replace('&', '', $tipo_que_hacer );

    $tpl_queHacer->assign("nombre_categoria", $categoria['0']['nombre_categoria'] );

    $filtro_isotope = "filtro_".$tipo_que_hacer;
    $tpl_queHacer->assign("filtro_isotope", $filtro_isotope);

    $tpl_queHacer->assign("tipo_que_hacer", 'color_'.$tipo_que_hacer);

    $tpl_queHacer->assign("imagen", $url_comun .'/comercios/'.$seccionDetail['id_comercio'].'/'.$seccionDetail['logo']);

    if ($seccionDetail['tipo'] == 'superdestacado'){
        $tpl_queHacer->assign("loadComercioDetalles", 'loadComercioDetalles');
        $tpl_queHacer->assign("info", '+ info');
        $tpl_queHacer->assign("href", "getComercioDetail&comercioId=".$seccionDetail['id_comercio_centro']);


    }else{

        /*        $tpl_queHacer->assign("icono_dentro_quehacer", 'icono_dentro_quehacer');
                $tpl_queHacer->assign("icono_clase_quehacer", $categoria['0']['class_icon']);
                $tpl_queHacer->assign("icono_svg", "icon-svg");
                $tpl_queHacer->assign("icono_comercio_normal", "icono_comercio_normal");
                $tpl_queHacer->assign("abre_comentario", "<!--");
                $tpl_queHacer->assign("cierra_comentario", "-->");*/

    }
}


//nuevo salva
// $rutas = totem_getRutas();

// foreach ($rutas as $rutasDetail) {

//     $tpl_queHacer->newBlock("rutas");

//     $tpl_queHacer->assign("tipo", $rutasDetail['tipo']);
//     $tpl_queHacer->assign("nombre", $rutasDetail['nombre']);
//     $tpl_queHacer->assign("direccion", $rutasDetail['direccion']);
//     $tpl_queHacer->assign("descripcion", $rutasDetail['descripcion']);
//     $tpl_queHacer->assign("imagen", '../../../contenido_proyectos/pacoche/_general/conoce_sitios/'.$rutasDetail['id'].'/'.$rutasDetail['imagen']);

//     if ($rutasDetail['tipo'] == 'ruta'){ $tpl_queHacer->assign("info", '+ info'); }
// }

// //nuevo salva
// $rutas_datos = totem_getRutas(null, 2);

// foreach ($rutas_datos as $rutas_datosDetail) {

//     $tpl_queHacer->newBlock("rutas_datos");
//     $tpl_queHacer->assign("como_llegar", $rutas_datosDetail['como_llegar']);
//     $tpl_queHacer->assign("itinerario", $rutas_datosDetail['itinerario']);
//     $tpl_queHacer->assign("punto_inicial", $rutas_datosDetail['inicio']);
//     $tpl_queHacer->assign("punto_final", $rutas_datosDetail['final']);
//     $tpl_queHacer->assign("observaciones", $rutas_datosDetail['observaciones']);
//     $tpl_queHacer->assign("características", $rutas_datosDetail['características']);

// }


$tpl_index->assign("content_contenido", $tpl_queHacer->getOutputContent());

?>
