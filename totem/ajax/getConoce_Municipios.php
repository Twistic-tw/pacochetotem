<?php

/* 
 * Este es el fichero para el NUEVO listado de municipios (2.0).
 * A este fichero se llega directamente para obtener el listado de municipios. Dicho listado se hace cargando, inicialmente,
 * el contenido generico de la isla.
 */

registrarLog("conoce", "", "");

$tpl_conoce = new TemplatePower("plantillas/seccion_conoce_listado.html", T_BYFILE);
$tpl_conoce->prepare();
$tpl_conoce->assign("imagen-fondo", "gran-canaria.png");
$tpl_conoce->assign("selector", LANG_CONOCE_SELECCIONAR_ZONAS);
$tpl_conoce->assign("cerrar", LANG_CONOCE_ATRAS);

$tpl_conoce->assign("info_label", LANG_CONOCE_INFORMACION);
$tpl_conoce->assign("sitios_label", LANG_CONOCE_INTERES);
$tpl_conoce->assign("hacer_label", LANG_CONOCE_QUEHACER);
$tpl_conoce->assign("galeria_label", LANG_CONOCE_GALERIA);

$tpl_conoce->assign("ver_zona", LANG_VERZONA);
$tpl_conoce->assign("ver_nombre", LANG_VERNOMBRE);

$active = "informacion";
if ( isset($_GET['accion']) )
{
    $active = $_GET['accion'];
}
$tpl_conoce->assign($active, "active");


$map = conoce_dame_datos_cliente($_SESSION['id_centro']);
$municipios = conoce_listado_municipios( $map['id_mapa'] );

$i = 1;
foreach ($municipios as $municipio) {
    
    if ( $i == 1 )
    {
        $tpl_conoce->newBlock("listado_municipios");
        $tpl_conoce->newBlock("municipio_1");
    }
    else
    { 
        $tpl_conoce->newBlock("municipio_2");
    }
    $i++;
    $i = $i % 2;
            
    $tpl_conoce->assign( "nombre_municipio", $municipio['nombre']);
    $tpl_conoce->assign( "id_municipio", $municipio['cod_local']);
}


//generamos ahora el contenido del municipio
$tpl_conoce->gotoBlock(TP_ROOTBLOCK);
$idioma = $_SESSION['idioma'];

$query = "SELECT contenido FROM conoce_contenido_secciones 
                            WHERE id_seccion=1 
                            AND id_localidades IS NULL
                            AND id_idioma = $idioma";

$db = new MySQL();
$result = $db->consulta($query);

while ($datos = $db->fetch_assoc($result))
{
    $tpl_conoce->newBlock("card");
    $tpl_conoce->assign("card_class", "card-4x4");
    $tpl_conoce->assign("card_content", $datos['contenido']);
}


$datos['get'] = $_GET;
$datos['datos'] = $tpl_conoce->getOutputContent();

echo json_encode($datos);
