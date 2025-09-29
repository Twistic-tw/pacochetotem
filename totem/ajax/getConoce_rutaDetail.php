<?php

//me llega el codigo de la ruta.
$rutaId = mysql_real_escape_string( $_GET[ 'ruta' ] ); //realmente ES el id del sitio_interes
$idioma = $_SESSION[ 'idioma' ];

registrarLog("conoce", "ruta", $rutaId);

$tpl_conoce = new TemplatePower( "plantillas/seccion_conoce_rutaDetail.html", T_BYFILE );
$tpl_conoce->prepare();

$rutas_datos = totem_getRutas( NULL, $rutaId );

foreach ( $rutas_datos as $rutas_datosDetail )
{

    $tpl_conoce->assign( 'imagen', '../../../contenido_proyectos/vistaflor/_general/conoce_sitios/' . $rutas_datosDetail[ 'id' ] . '/' . $rutas_datosDetail[ 'imagen' ] );
    $tpl_conoce->assign( "nombre", $rutas_datosDetail[ 'nombre' ] );
    $tpl_conoce->assign( "cerrar", LANG_CONOCE_ATRAS );
    $tpl_conoce->assign( "sitios_label", LANG_CONOCE_INTERES );

    $tpl_conoce->newBlock( "rutas_datos" );

    $tpl_conoce->assign( "lang_punto_inicial", LANG_RUTAS_PUNTOINICIAL );
    $tpl_conoce->assign( "lang_punto_final", LANG_RUTAS_PUNTOFINAL );
    $tpl_conoce->assign( "lang_itinerario", LANG_RUTAS_ITINERARIO );
    $tpl_conoce->assign( "lang_como_llegar", LANG_RUTAS_COMOLLEGAR );
    $tpl_conoce->assign( "lang_observaciones", LANG_RUTAS_OBSERVACIONES );
    $tpl_conoce->assign( "lang_caracteristicas", LANG_RUTAS_CARACTERISTICAS );


    $tpl_conoce->assign( "lang_nivel", LANG_RUTAS_NIVEL );
    $tpl_conoce->assign( "lang_duracion", LANG_RUTAS_DURACION );
    $tpl_conoce->assign( "lang_recorridos", LANG_RUTAS_RECORRIDO );
    $tpl_conoce->assign( "lang_desniveles", LANG_RUTAS_DESNIVELES );
    $tpl_conoce->assign( "lang_descubrir", LANG_RUTAS_DESCUBRIR );


    $tpl_conoce->assign( "lang_pista_tierra", LANG_RUTAS_PISTA_TIERRA );
    $tpl_conoce->assign( "lang_sendero_tierra", LANG_RUTAS_SENDERO_TIERRA );
    $tpl_conoce->assign( "lang_ruta_principal", LANG_RUTAS_RUTA_PRINCIPAL );
    $tpl_conoce->assign( "lang_red_caminos", LANG_RUTAS_RED_CAMINOS );
    $tpl_conoce->assign( "lang_otros_caminos", LANG_RUTAS_OTROS_CAMINOS );
    $tpl_conoce->assign( "lang_degollada", LANG_RUTAS_DEGOLLADA );
    $tpl_conoce->assign( "land_mirador", LANG_RUTAS_MIRADOR );


    $tpl_conoce->assign( "como_llegar", $rutas_datosDetail[ 'como_llegar' ] );
    $tpl_conoce->assign( "itinerario", $rutas_datosDetail[ 'itinerario' ] );
    $tpl_conoce->assign( "punto_inicial", $rutas_datosDetail[ 'inicio' ] );
    $tpl_conoce->assign( "punto_final", $rutas_datosDetail[ 'final' ] );
    $tpl_conoce->assign( "observaciones", $rutas_datosDetail[ 'observaciones' ] );
    $tpl_conoce->assign( "características", $rutas_datosDetail[ 'características' ] );
    $tpl_conoce->assign( "que_descubrir", $rutas_datosDetail[ 'que_descubrir' ] );
    $tpl_conoce->assign( "rutaid", $rutaId );

    $tpl_conoce->assign( "nivel", $rutas_datosDetail[ 'nivel' ] );
    $tpl_conoce->assign( "duracion", $rutas_datosDetail[ 'duracion' ] );
    $tpl_conoce->assign( "recorrido", $rutas_datosDetail[ 'recorrido' ] );
    $tpl_conoce->assign( "desniveles", $rutas_datosDetail[ 'desniveles' ] );

    if ( $rutas_datosDetail[ 'mapa' ] != "" )
    {
        $tpl_conoce->newBlock( "mapa" );
        $tpl_conoce->assign( "lang_mapa", LANG_RUTAS_MAPA );
        $tpl_conoce->assign( "lang_leyenda", LANG_RUTAS_LEYENDA );
        $tpl_conoce->assign( "mapa", $rutas_datosDetail[ 'mapa' ] );
    }
    $tpl_conoce->assignGlobal( "rutaid", $rutaId );
}


$tpl_conoce->gotoBlock( "_ROOT" );

/*   $n_item=1; //Variable que enumera mis fotos para su posterior uso en la galeria*/
//ahora debemos diferenciar de si es para un municipio o todas en general.

//$tpl_conoce->newBlock("blq_galeria");
$tpl_conoce->newBlock( "blq_galeria_full" );

//  while ($datos = $db->fetch_assoc($result))
//{
$dirPath = '../../../contenido_proyectos/vistaflor/_general/conoce_sitios/' . $rutas_datosDetail[ 'id' ] . '/';

if ( is_dir( $dirPath ) )
{
    $dirHandler = opendir( $dirPath );
    while ( $file = readdir( $dirHandler ) )
    {
        if ( is_file( $dirPath . $file ) && $file != "." && $file != ".." && $file != "Thumbs.db" )
        {
            $rutaImg = $dirPath . $file;

            //Seccion para el visor a pantalla completa
            $tpl_conoce->newBlock( "blq_foto_full" );

            $tpl_conoce->assign( "h", "405" );
            $tpl_conoce->assign( "w", "720" );
            $tpl_conoce->assign( "url", utf8_encode( $rutaImg ) );
            $tpl_conoce->assign( "n_item", $n_item );
            /* $n_item++;     */
        }
    }
}
//  }


$conoce_json[ 'datos' ] = $tpl_conoce->getOutputContent();

echo json_encode( $conoce_json );
exit();


/****************************************************************************
 * Codigo antiguo
 */

$tpl_conoce = new TemplatePower( "plantillas/seccion_conoce_rutaDetail.html", T_BYFILE );
$tpl_conoce->prepare();

$tpl_conoce->assign( "lang_", "" );
$tpl_conoce->assign( "lang_atras", LANG_GLOBAL_ATRAS );
$tpl_conoce->assign( "lang_datos", LANG_CONOCE_DATOSGENERALES );
$tpl_conoce->assign( "lang_descubrir", LANG_CONOCE_DESCUBRIR );
$tpl_conoce->assign( "lang_mapa", LANG_CONOCE_MAPA );


$tpl_conoce->assign( "lang_nivel", LANG_RUTAS_NIVEL );
$tpl_conoce->assign( "lang_duracion", LANG_RUTAS_DURACION );
$tpl_conoce->assign( "lang_recorrido", LANG_RUTAS_RECORRIDO );
$tpl_conoce->assign( "lang_desniveles", LANG_RUTAS_DESNIVELES );
$tpl_conoce->assign( "lang_comollegar", LANG_RUTAS_COMOLLEGAR );
$tpl_conoce->assign( "lang_itinerario", LANG_RUTAS_ITINERARIO );
$tpl_conoce->assign( "lang_punto_inicial", LANG_RUTAS_PUNTOINICIAL );
$tpl_conoce->assign( "lang_punto_final", LANG_RUTAS_PUNTOFINAL );
$tpl_conoce->assign( "lang_observaciones", LANG_GLOBAL_OBSERVACIONES );


//me llega el codigo de la ruta.
$rutaId = mysql_real_escape_string( $_GET[ 'ruta' ] ); //realmente ES el id del sitio_interes
$idioma = $_SESSION[ 'idioma' ];

$rutaDetail = totem_getRutaDetail( $rutaId );
//var_dump($rutaDetail);

//asignamos los valores de tipo generico
$tpl_conoce->assign( "title", $rutaDetail[ 'nombre' ] );
$tpl_conoce->assign( "lat", $rutaDetail[ 'lat' ] );
$tpl_conoce->assign( "long", $rutaDetail[ 'long' ] );
$tpl_conoce->assign( "id", $rutaDetail[ 'id' ] );
$tpl_conoce->assign( "id-sitio", $rutaDetail[ 'id_sitio' ] );

//detalles generales de la ruta
$tpl_conoce->assign( "nivel", $rutaDetail[ 'nivel' ] );
$tpl_conoce->assign( "duracion", $rutaDetail[ 'duracion' ] );
$tpl_conoce->assign( "recorrido", $rutaDetail[ 'recorrido' ] );
$tpl_conoce->assign( "desniveles", $rutaDetail[ 'desniveles' ] );

//datos especificos de la ruta
$tpl_conoce->assign( "itinerario", $rutaDetail[ 'itinerario' ] );
$tpl_conoce->assign( "como_llegar", $rutaDetail[ 'como_llegar' ] );
$tpl_conoce->assign( "punto_inicial", $rutaDetail[ 'inicio' ] );
$tpl_conoce->assign( "punto_final", $rutaDetail[ 'final' ] );
$tpl_conoce->assign( "observaciones", $rutaDetail[ 'observaciones' ] );

//asignamos la imagen.
$tpl_conoce->assign( "id_sitio", $rutaId );
$tpl_conoce->assign( "imagen", $rutaDetail[ 'imagen' ] );

//secciones internas que son solo html puro.
$tpl_conoce->assign( "descubrir_contenido", $rutaDetail[ 'que_descubrir' ] );
$tpl_conoce->assign( "mapa_contenido", $rutaDetail[ 'mapa' ] );

$conoce_json[ 'datos' ] = $tpl_conoce->getOutputContent();

echo json_encode( $conoce_json );

?>
