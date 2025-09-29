<?php


$accion = "";
$municipioCodigo = "";
$rutas_datos == array();

$accion = $_GET['accion'];
$municipioCodigo = $_GET['municipio'];
$idioma = $_SESSION['idioma'];

if($accion == 'getEurocopa_info'){
    registrarLog("mundial2018", null, null);
}else{
    registrarLog("conoce", "municipio_info", $municipioCodigo);
}

$localidad_filtro = "IS NULL";
if ( $municipioCodigo != "")
{
    $localidad_filtro = "= ( SELECT id_local FROM localidades_aemet WHERE cod_local = $municipioCodigo )";
    
}

switch ($accion)
{
    case "informacion":
        $query = "SELECT contenido FROM conoce_contenido_secciones 
                            WHERE id_seccion=1 
                            AND id_localidades $localidad_filtro
                            AND id_idioma = $idioma";
        break;

    case "hacer":
        $query = "SELECT contenido FROM conoce_contenido_secciones 
                            WHERE id_seccion=2 
                            AND id_localidades $localidad_filtro
                            AND id_idioma = $idioma";
        break;

    case "galeria":
        $query = "SELECT cod_local FROM localidades_aemet";
        break;

    case "sitios":
        $rutas_datos = totem_getRutas($municipioCodigo == "" ? null : $municipioCodigo);
        break;

    default:
        break;
}

$tpl_conoce = new TemplatePower("plantillas/seccion_conoce_municipio.html", T_BYFILE);
$tpl_conoce->prepare();




$db = new MySQL();

//En primer lugar debemos diferenciar si se desea ver galeria o alguna otra sección.
if ( $accion == "galeria" )
{
    
    $n_item=1; //Variable que enumera mis fotos para su posterior uso en la galeria
    //ahora debemos diferenciar de si es para un municipio o todas en general.
    if ( $municipioCodigo ) 
    {
        $query .= " WHERE cod_local = $municipioCodigo";
    }
    $result = $db->consulta($query);
    $tpl_conoce->newBlock("blq_galeria");
    $tpl_conoce->newBlock("blq_galeria_full");
    
    while ($datos = $db->fetch_assoc($result))
    {
        $dirPath = "../../../contenido_proyectos/vistaflor/_general/conoce_imagenes/".$datos['cod_local']."/";
        $numero_imagenes = 0;

        if (is_dir($dirPath) )
        {
            $dirHandler = opendir($dirPath);
            while ( $file = readdir($dirHandler) )
            {
                if( is_file($dirPath.$file) && $file != "." && $file != ".." && $file != "Thumbs.db"){
                    $rutaImg = $dirPath.$file;
                    $img_attr = getimagesize ( $rutaImg );

                    if ( $img_attr[0] + $img_attr[1] <= 1000 )
                    {
                        continue;
                    }

                    $numero_imagenes++;

                    $size_class="";
                    $h="142";
                    $w="142";
                    if ( rand ( 0 , 3 ) == 1){
                        if( $img_attr[0] > $img_attr[1]*2){   //2xMas ancho que alto
                            $size_class="width2";     
                            $w="284";
                            $h="142";
                        }
                        else if($img_attr[1] > $img_attr[0]*2){ //2xMas alto que ancho
                            $size_class="height2";   
                            $w="142";
                            $h="284";
                        }
                        else if($img_attr[1]>300){ //se acota el rango 0,7 para que aparezcan mas fotos grandes
                            $size_class="height2 width2";  //Caso normal
                            $w="284";
                            $h="284";
                        }
                    }
                    //Seccion para el visor isotope
                    $tpl_conoce->newBlock("blq_foto");
                    $tpl_conoce->assign("size_class",  $size_class);
                    $tpl_conoce->assign("h",  $h);
                    $tpl_conoce->assign("w",  $w);
                    $tpl_conoce->assign("url",  utf8_encode($rutaImg));
                    $tpl_conoce->assign("n_item",  $n_item);
                    //Seccion para el visor a pantalla completa
                    $tpl_conoce->newBlock("blq_foto_full");
                    $tpl_conoce->assign("size_class",  $size_class);
                    $tpl_conoce->assign("h",  "1920");
                    $tpl_conoce->assign("w",  "1080");
                    $tpl_conoce->assign("url",  utf8_encode($rutaImg));
                    $tpl_conoce->assign("n_item",  $n_item);
                    $n_item++;                           
                }
            }
        }

        if ( $numero_imagenes == 0 )
        {
            //GENERAR UN BLOQUE CON INFORMACIÓN DE QUE NO HAY IMAGENES.
        }
    }
    
}
elseif ( $accion == "sitios") 
{
    foreach ($rutas_datos as $rutasDetail)
    {
        $tpl_conoce->newBlock("rutas");

        $tpl_conoce->assign("tipo", $rutasDetail['tipo']);
        $tpl_conoce->assign("nombre", $rutasDetail['nombre']);
        $tpl_conoce->assign("direccion", $rutasDetail['direccion']);
        $tpl_conoce->assign("descripcion", $rutasDetail['descripcion']);
        $tpl_conoce->assign("imagen", '../../../contenido_proyectos/vistaflor/_general/conoce_sitios/'.$rutasDetail['id'].'/'.$rutasDetail['imagen']);

        if ($rutasDetail['tipo'] == 'ruta'){
            $tpl_conoce->assign("info", '+ info');
            $tpl_conoce->assign("href", 'getConoce_rutaDetail&ruta=' . $rutasDetail['id']);
        }
    }
}
else
{   
    $result = $db->consulta($query);
    
    if ( $municipioCodigo ) 
    {
        $tpl_conoce->newBlock("titulo");
        $tpl_conoce->assign("titulo", "Municipio $municipioCodigo");
    }

    while ($datos = $db->fetch_assoc($result))
    {
        $tpl_conoce->newBlock("card");
        $tpl_conoce->assign("card_class", "card-4x4");
        $tpl_conoce->assign("card_content", $datos['contenido']);
    }
}


$conoce_json['datos'] = $tpl_conoce->getOutputContent();
echo json_encode($conoce_json);

exit();





/***********************************************************************************************
 *                                              codigo antiguo
 */

$tpl_conoce->assign("lang_inicio", LANG_CONOCE_INICIO);
$tpl_conoce->assign("lang_fiestas", LANG_CONOCE_FIESTAS);
$tpl_conoce->assign("lang_fotos", LANG_CONOCE_FOTOS);
$tpl_conoce->assign("lang_cultura", LANG_CONOCE_CULTURA);
$tpl_conoce->assign("lang_gastronomia", LANG_CONOCE_GASTRONOMIA);
$tpl_conoce->assign("lang_quehacer", LANG_CONOCE_QUEHACER);
$tpl_conoce->assign("lang_interes", LANG_CONOCE_SITIOSINTERES);
$tpl_conoce->assign("lang_atras", LANG_GLOBAL_ATRAS);

//me llega el codigo de municipio.
$municipioCodigo = mysql_real_escape_string($_GET['municipio']);
$idioma = $_SESSION['idioma'];
//$idioma = 1;

registrarLog("conoce", "", $municipioCodigo);

//obtenemos toda la información del municipio seleccionado.
$query = "SELECT t1.*, t2.nombre_seccion, t3.nombre as nombre_localidad
            FROM conoce_contenido_secciones as t1, 
                conoce_secciones as t2, 
                localidades_aemet as t3
                    WHERE t3.cod_local = '$municipioCodigo'
                    AND t3.id_local = t1.id_localidades
                    AND t1.id_idioma = '$idioma'
                    AND t1.id_seccion = t2.id_seccion";

$db = new MySQL();
$result = $db->consulta($query);

while ($datos = $db->fetch_assoc($result))
{
    $tpl_conoce->gotoBlock(TP_ROOTBLOCK);
    $tpl_conoce->assign("title", $datos['nombre_localidad']);
    
    $tpl_conoce->newBlock($datos['nombre_seccion']);
    $tpl_conoce->assign("contenido", strip_tags ( $datos['contenido'] , "<img><p><h2>" ));
}

$sitiosInteresArray = totem_getSitiosInteres($municipioCodigo);
foreach ($sitiosInteresArray as $sitioDetail) {
    switch ($sitioDetail['tipo']) {
        case "ruta":
            $tpl_conoce->newBlock("sitio_interes_ruta");
            break;

        default:
            $tpl_conoce->newBlock("sitio_interes");
            break;
    }
    $tpl_conoce->assign("tipo_sitio", $sitioDetail['tipo']);
    $tpl_conoce->assign("direccion", $sitioDetail['direccion']);
    $tpl_conoce->assign("id", $sitioDetail['id']);
    $tpl_conoce->assign("lat", $sitioDetail['lat']);
    $tpl_conoce->assign("long", $sitioDetail['long']);
    $tpl_conoce->assign("icon_class", $sitioDetail['icon_class']);
    $tpl_conoce->assign("nombre", $sitioDetail['nombre']);
    $tpl_conoce->assign("descripcion", $sitioDetail['descripcion']);
    $tpl_conoce->assign("imagen", $sitioDetail['imagen']);
    $conoce_json['datos_sitio'][] = $sitioDetail;
}

//falta la galeria de fotos. /totem/img/conoce_imagenes/[codigo]/

$dirPath = "../../../contenido_proyectos/vistaflor/_general/conoce_imagenes/".$municipioCodigo."/";

if (is_dir($dirPath) )
{
    $dirHandler = opendir($dirPath);
    while ( $file = readdir($dirHandler) )
    {
        if( is_file($dirPath.$file) && $file != "." && $file != ".." && $file != "Thumbs.db"){
            $tpl_conoce->newBlock("img");
            
            $rutaImg = $dirPath.$file;
            $tpl_conoce->assign("imgSrc",  utf8_encode($rutaImg));
        }
    }
}
$conoce_json["t"] = $sitiosInteresArray;

//ahora vamos asignando los datos correspondientes.
///totem/img/conoce_imagenes/[codigo]/destacado
$tpl_conoce->assignGlobal("lang_info", LANG_GLOBAL_INFO);
$conoce_json['datos'] = $tpl_conoce->getOutputContent();
echo json_encode($conoce_json);

exit();

?>
