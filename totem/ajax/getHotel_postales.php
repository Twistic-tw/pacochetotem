<?php

//Si no hay internet, no entramos en postales

if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}

$t = 1;

$idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

registrarLog("hotel", "postal");
    

$tpl_postales = new TemplatePower("plantillas/seccion_hotel_postales.html", T_BYFILE);
$tpl_postales->prepare();

$db = new MySQL();

$tpl_postales->assign("title", LANG_POSTALES_TITLE);

$tpl_postales->assign("lang_fondoText", LANG_POSTALES_FONDOTEXT);
$tpl_postales->assign("lang_fotoText", LANG_POSTALES_FOTOTEXT);
$tpl_postales->assign("lang_textoText", LANG_POSTALES_TEXTOTEXT);
$tpl_postales->assign("lang_textoMostrar", LANG_POSTALES_TEXTOMOSTRAR);
$tpl_postales->assign("lang_textoPersonalize", LANG_POSTALES_TEXTOPERSONALIZE);
$tpl_postales->assign("lang_textoTamaño", LANG_POSTALES_TEXTOSIZE);
$tpl_postales->assign("lang_atras", LANG_GLOBAL_ATRAS);
$tpl_postales->assign("lang_introducirEmail", LANG_GLOBAL_INTRODUCIREMAIL);
$tpl_postales->assign("lang_enviar", LANG_GLOBAL_ENVIAR);

$dirPath = "../../../contenido_proyectos/vistaflor/centro_$id_centro/postales_contenido/";

$resultado['dir'] = $dirPath;
if (is_dir($dirPath."fondos/") )
{
    $dirHandler = opendir($dirPath."fondos/");
    while ( false !== ( $file = readdir($dirHandler) ) )
    {
        if( $file != "." && $file != ".." && $file != "Thumbs.db" && is_file( $dirPath . "fondos/" . $file ) ){
            $tpl_postales->newBlock("fondo_imagen");
            $tpl_postales->assign("imgSrc", "../../../contenido_proyectos/vistaflor/centro_$id_centro/postales_contenido/fondos/$file"); 
        }
    }
}

if (is_dir($dirPath."fotos/") )
{
    $dirHandler = opendir($dirPath."fotos/");
    while ( false !== ( $file = readdir($dirHandler) ) )
    {
        if( $file != "." && $file != ".." && $file != "Thumbs.db" && is_file( $dirPath . "fotos/" . $file ) ){
            $tpl_postales->newBlock("foto_imagen");
            $tpl_postales->assign("imgSrc", "../../../contenido_proyectos/vistaflor/centro_$id_centro/postales_contenido/fotos/$file"); 
        }
    }
}

$resultado['datos'] = $tpl_postales->getOutputContent();

$logoSrc = "";
$logoInfSrc = "";
$claseExtra = "";

//obtenemos el logo en fncion del id del centro
$filePath = "../../../contenido_proyectos/vistaflor/centro_$id_centro/logos/sello.jpg";
if (is_file($filePath))
{
    
    $logoSrc = "../../../contenido_proyectos/vistaflor/centro_$id_centro/logos/sello.jpg";
    //comprobamos a ver si el alto o el ancho es mayor.
    $datosImg = getimagesize($filePath);
    if ($datosImg['height'] > $datosImg['width'])
    {
        //imagen mas alta que ancha
        $claseExtra = "apaisado";
    }
        
    $imagenLogoCentro = "<div id='logoHotel' class='$claseExtra'><img src='$logoSrc'/></div>";
    //echo json_encode($datosImg);
}

//obtenemos el logo inferior.
$filePath = "../../../contenido_proyectos/vistaflor/centro_$id_centro/logos/logo_postal.png";
if (is_file($filePath))
{
    $logoInfSrc = "../../../contenido_proyectos/vistaflor/centro_$id_centro/logos/logo_postal.png";
}

$postalesDesingHTML = "
<div id='postalesDesignWrapper' class='overlayBannerHeader hidden'>
    <div id='postal'>
        <div id='sello' class=''><img src='../../../contenido_proyectos/vistaflor/_general/imagenes/postal_sello.png'/></div> 
        $imagenLogoCentro
        <div id='cuño' class=''><img src='../../../contenido_proyectos/vistaflor/_general/imagenes/postal_cuño.png'/></div> 
        <div id='logoCentro' class=''><img src='$logoInfSrc'/></div>
        <div id='fotoWrapper' class='elemento'><img/></div> 
        <div id='fondoWrapper' class='elemento'><img/></div>
        <div id='division'></div>
        <div id='textoPostal' class='elemento fontSize3'></div>
    </div>
</div>";
$resultado['postalesDesingWrapperHTML'] = $postalesDesingHTML;

echo json_encode($resultado);
?>
