<?php

function get_hotel($id_hotel)
{

    $db = new MySQL_cli();

    $query = "SELECT t1.*, t2.proyect_name FROM bd_twistic_sat.clients_centers AS t1 
    INNER JOIN bd_twistic_sat.clients AS t2 ON t1.id_client = t2.id 
    WHERE t1.id = '" . $id_hotel . "' AND t1.active IN (1,-1)";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;

}

function get_contenidos($id_contenido)
{
    $db = new MySQL();

    $query = "SELECT * FROM contenidos WHERE id_categoria = " . $id_contenido . " AND id_idioma = " . $_SESSION['idioma'] . "";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;
}

function get_lista_contenidos($id_padre)
{
    $db = new MySQL();

    //$query = "SELECT * FROM contenidos as c LEFT JOIN contenido_categorias as cc ON c.id_categoria = cc.id_cat WHERE c.id_idioma = ".$_SESSION['idioma']." AND cc.id_idioma = 1 AND cc.padre = ".$id_padre;
    //$query = "SELECT * FROM contenido_categorias WHERE id_idioma = ".$_SESSION['idioma']." AND padre = ".$id_padre;
    $query = "SELECT * FROM contenido_categorias WHERE id_idioma = " . $_SESSION['idioma'] . " AND padre = " . $id_padre . " ORDER BY orden ASC";

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos;
}

function check_contenido($id_categoria)
{

    $db = new MySQL();

    //$query = "SELECT * FROM contenidos as c LEFT JOIN contenido_categorias as cc ON c.id_categoria = cc.id_cat WHERE c.id_idioma = 1 AND cc.id_idioma = 1 AND cc.padre = ".$id_padre;
    //$query = "SELECT id_contenido FROM contenidos WHERE id_idioma = ".$_SESSION['idioma']." AND id_categoria = ".$id_categoria . " AND activo = 1";
    $query = "SELECT id_contenido, activo, id_categoria FROM contenidos WHERE id_idioma = " . $_SESSION['idioma'] . " AND id_categoria = '" . $id_categoria . "'";

    $result = $db->consulta($query);

    if ($_SESSION['id_centro'] == 1901 || $_SESSION['id_centro'] == 1904 || $_SESSION['id_centro'] == 1903) {
        $array_secciones_ocultas = [120, 121, 122];
    } else {
        $array_secciones_ocultas = null;
    }

    $datos = null;
    while ($row = $db->fetch_assoc($result)) {
        if (($_SESSION['id_centro'] == 1901 || $_SESSION['id_centro'] == 1904 || $_SESSION['id_centro'] == 1903) && in_array($row['id_categoria'], $array_secciones_ocultas)) {
            $datos[] = $row;
        } else {
            if ($row['activo'] == 1) {
                $datos[] = $row;
            }
        }
    }

    return $datos;


//    $datos = null;
//    while ($row = $db->fetch_assoc($result))
//    {
//        $datos[] = $row;
//    }
//
//    return $datos;

}


function fechas_calendario($fecha)
{
    switch ($_SESSION['idioma']) {
        case '1':

            $fecha_i = $fecha;
            $fecha = strftime("%A %d de %B %Y", strtotime($fecha));
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                $fecha = utf8_encode(strftime("%A %d de %B %Y", strtotime($fecha_i)));
            break;

        case '2':

            $fecha = strftime("%A %B %d, %Y", strtotime($fecha));

            break;

        case '3':

            $fecha = strftime("%A %d. %B %Y", strtotime($fecha));

            break;

        //El polaco esta en ingles
        case '4':

            $fecha = strftime("%A %B %d, %Y", strtotime($fecha));

            break;
    }

    return $fecha;

}

function datos_hotel()
{

    $db = new MySQL();
    $query = 'SELECT * FROM centro WHERE id_centro = ' . $_SESSION['id_centro'];

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos[0];

}

function get_timezone_feelapp()
{

    $db = new MySQL_Cli();

    $query = 'SELECT * FROM ft_configuracion.dt_centro WHERE id = "' . $_SESSION['id_centro'] . '" LIMIT 1';

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    if ($datos[0]['zona_horaria']) {
        date_default_timezone_set($datos[0]['zona_horaria']);
    }

    return;

}

function google_analytics()
{

    $db = new MySQL();
    $query = 'SELECT * FROM ft_google_analytics.hoteles WHERE activo = 1 AND id_feel = ' . $_SESSION['id_centro'];

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos[0];

}

function get_piscinas()
{

    $db = new MySQL();

    $query = "SELECT * FROM `piscinas` WHERE 1";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }
    return $datos;

}

function actualiza_datos_reserva($id_reserva,$numero_habitacion,$checkout_general,$checkin_general,$numero_huespedes,$regimen_general,$numero_adultos = 0,$numero_ninos = 0,$numero_bebes = 0,$nombre_cliente)
{

    $db = new MySQL();

    $query = "REPLACE INTO reserva_usuarios (id_reserva,nombre_cliente,numero_habitacion,checkout,checkin,numero_huespedes,regimen,adultos,ninos,bebes) 
              VALUES('".$id_reserva."','".$nombre_cliente."','".$numero_habitacion."','".$checkout_general."','".$checkin_general."','".$numero_huespedes."','".$regimen_general."','".$numero_adultos."','".$numero_ninos."','".$numero_bebes."')";

    //print_r($query);

    $result = $db->consulta($query);

    return $result;

}


function get_carta_dinamica($id_categoria, $idioma)
{
    $db = new MySQL();

    //$query = "SELECT t2.nombre as nombre_articulo, t2.id_subcategoria, t2.denominacion, t2.lugar,
    //          t3.precio, t3.capacidad, t3.precio_descuento
    //          FROM cartas_categorias as t1
    //          INNER JOIN cartas_articulos as t2 ON t1.id_categoria = t2.id_categoria
    //          INNER JOIN cartas_articulos_precio as t3 ON t2.id = t3.id_articulo
    //          WHERE t1.id_idioma = ".$idioma."
    //          AND t1.id_categoria = ".$id_categoria;

//    $query = "SELECT t4.nombre_articulo, t2.id_subcategoria, t4.descripcion, t4.denominacion_origen,
//              t3.precio, t3.capacidad, t3.precio_descuento, t2.id AS id_articulo , t2.infantil
//              FROM cartas_categorias as t1
//              INNER JOIN cartas_articulos as t2 ON t1.id_categoria = t2.id_categoria
//              INNER JOIN cartas_articulos_precio as t3 ON t2.id = t3.id_articulo
//              INNER JOIN cartas_articulos_tiene_idiomas as t4 ON t2.id = t4.id_articulo
//              WHERE t1.id_idioma = ".$idioma."
//              AND t4.id_idioma = ".$idioma."
//              AND t1.id_categoria = ".$id_categoria." ORDER BY t2.id_categoria, t2.id_subcategoria, t2.infantil, t2.nombre";


//    if($_SESSION['id_centro'] == 3601){


//    $query = "SELECT t4.nombre_articulo, t2.id_subcategoria, t4.descripcion, t4.denominacion_origen,
//              t3.precio, t3.capacidad, t3.precio_descuento, t2.id AS id_articulo , t2.infantil
//              FROM cartas_categorias as t1
//              LEFT JOIN cartas_articulos as t2 ON t1.id_categoria = t2.id_categoria
//              LEFT JOIN cartas_articulos_precio as t3 ON t2.id = t3.id_articulo
//              LEFT JOIN cartas_articulos_tiene_idiomas as t4 ON t2.id = t4.id_articulo
//              WHERE t1.id_idioma = " . $idioma . "
//              AND t4.id_idioma = " . $idioma . "
//              AND t2.activo = 1
//              AND t1.id_categoria = " . $id_categoria . " ORDER BY t2.orden ASC, t2.id_categoria, t2.id_subcategoria, t2.id, t2.infantil, t2.nombre DESC, t3.capacidad ASC";



    $query = "SELECT t4.nombre_articulo, t2.id_subcategoria, t4.descripcion, t4.denominacion_origen, t4.uva,  
              t3.precio, t3.capacidad, t3.precio_descuento, t2.id AS id_articulo , t2.infantil, 
              (SELECT orden FROM cartas_subcategorias AS t5 WHERE t5.id_subcategoria = t2.id_subcategoria AND t5.id_idioma = 1) AS orden_sub  
              FROM cartas_categorias as t1
              LEFT JOIN cartas_articulos as t2 ON t1.id_categoria = t2.id_categoria  
              LEFT JOIN cartas_articulos_precio as t3 ON t2.id = t3.id_articulo
              LEFT JOIN cartas_articulos_tiene_idiomas as t4 ON t2.id = t4.id_articulo 
              WHERE t1.id_idioma = " . $idioma . "
              AND t4.id_idioma = " . $idioma . " 
              AND t2.activo = 1 
              AND t1.id_categoria = " . $id_categoria . " ORDER BY orden_sub, t2.orden ASC, t2.id_categoria, t2.id_subcategoria, t2.id, t2.infantil, t2.nombre DESC, t3.id ASC";



//    $query = "SELECT t4.nombre_articulo, t2.id_subcategoria, t4.descripcion, t4.denominacion_origen,
//              t3.precio, t3.capacidad, t3.precio_descuento, t2.id AS id_articulo , t2.infantil
//              FROM cartas_categorias as t1
//              LEFT JOIN cartas_articulos as t2 ON t1.id_categoria = t2.id_categoria
//              LEFT JOIN cartas_articulos_precio as t3 ON t2.id = t3.id_articulo
//              LEFT JOIN cartas_articulos_tiene_idiomas as t4 ON t2.id = t4.id_articulo
//              LEFT JOIN cartas_subcategorias AS t5 ON t5.id_subcategoria = t2.id_subcategoria
//              WHERE t1.id_idioma = " . $idioma . "
//              AND t4.id_idioma = " . $idioma . "
//              AND t2.activo = 1
//              AND t1.id_categoria = " . $id_categoria . " ORDER BY t2.orden, t5.orden ASC, t2.id_categoria, t2.id_subcategoria, t2.id, t2.infantil, t2.nombre DESC, t3.capacidad ASC";
//

//    }

    $result = $db->consulta($query);

    $datos = [];
    while ($row = $db->fetch_assoc($result)) {

        $array_capicidad_precio = array('capacidad' => $row['capacidad'], 'precio' => $row['precio'], 'precio_descuento' => $row['precio_descuento']);

        if ($datos[$row['id_articulo']]) {
            $datos[$row['id_articulo']]['capacidad_precio'][] = $array_capicidad_precio;
        } else {
            $row['capacidad_precio'][] = $array_capicidad_precio;
            $datos[$row['id_articulo']] = $row;
        }

        //$datos[] = $row;

    }

    return $datos;
}

function get_categorias_carta($id_carta, $idioma, $merge_cartas = null)
{
    $db = new MySQL();

    if ($merge_cartas) {
        $query_carta = " AND t1.id_carta IN (" . $merge_cartas . ")";
    } else {
        $query_carta = " AND t1.id_carta = '" . $id_carta . "'";
    }

    $query = "SELECT *  
              FROM cartas_categorias as t1
              WHERE t1.id_idioma = " . $idioma . "
              AND t1.activo = 1 " . $query_carta . " ORDER BY orden, id_categoria ASC";

//    echo $query . "<br>";


    $result = $db->consulta($query);

    $datos = [];
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos;
}

function get_datos_cartas($id_carta)
{
    $db = new MySQL();

    $query = "SELECT *  
              FROM cartas 
              WHERE activo > '0' 
              AND id = '" . $id_carta . "'";


    $result = $db->consulta($query);

    $datos = [];
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos;

}

function get_suplementos_carta($id_carta, $idioma)
{
    $db = new MySQL();

    $query = "SELECT cats.id_suplemento, casti.nombre_suplemento, casti.descripcion_suplemento, cas.capacidad, cas.precio, cats.id_articulo 
FROM cartas_articulos_tiene_suplementos AS cats 
LEFT JOIN cartas_articulos_suplementos AS cas ON cats.id_suplemento = cas.id
LEFT JOIN cartas_articulos_suplementos_tiene_idiomas AS casti ON cas.id = casti.id_articulo_suplemento
WHERE cas.activo > 0 
AND casti.id_idioma = '" . $idioma . "'  
AND cats.activo > 0 ";

    //echo $query . "<br>";


    $result = $db->consulta($query);

    $datos = [];
//    while ($row = $db->fetch_assoc($result)) {
//        $datos[] = $row;
//    }

    while ($row = $db->fetch_assoc($result)) {
        $datos[$row['id_articulo']][$row['id_suplemento']] = $row;
    }

    return $datos;
}


function get_subcategorias_carta($id_subcategoria, $idioma)
{
    $db = new MySQL();

    $query = "SELECT t1.nombre
              FROM cartas_subcategorias as t1
              WHERE t1.id_idioma = " . $idioma . "
              AND t1.id_subcategoria = " . $id_subcategoria;

    //echo $query . "<br>";


    $result = $db->consulta($query);

    $datos = [];
    while ($row = $db->fetch_assoc($result)) {
        $datos[] = $row;
    }

    return $datos;
}

function datos_cartas_dinamicas($id_carta)
{

    $datos = "";
    $html = "";
    $id_subcategoria = null;
    $idioma = $_SESSION['idioma'];

    if (isset($id_carta)) {

        $categorias = get_categorias_carta($id_carta, $idioma);
        //echo "<pre>";
        //print_r($categorias);

        foreach ($categorias as $categoria) {
            $datos = get_carta_dinamica($categoria['id_categoria'], $idioma);
            //echo "<pre>";
            //print_r($datos);
            $html .= '<button id="" class="accordion">' . $categoria['nombre'] . '</button>
                  <div class="panel">';
            foreach ($datos as $item) {

                if ($id_subcategoria != $item['id_subcategoria'] && $item['id_subcategoria'] > 0) {
                    $id_subcategoria = $item['id_subcategoria'];
                    $subcategoria = get_subcategorias_carta($item['id_subcategoria'], $idioma);
                    $html .= '<div class="subcategoria_cartas">' . $subcategoria[0]['nombre'] . '</div>';
                }
                $html .= '
                      <div class="divTable">
                        <div class="divTableRow">
                            <div class="divTableCell css_menu_tamano_fijo">
                                <span class="css_menu_titulo">' . $item['nombre_articulo'] . '</span> 
                                <span class="css_menu_subtitulo">' . $item['denominacion'] . '.</span> 
                                <span class="css_menu_subtitulo">' . $item['lugar'] . '</span> 
                            </div>
                            <div class="divTableCell css_menu_precio">' . $item['precio'] . '</div>';

                if ($item['capacidad'] != '') {
                    $html .= '<div class="divTableCell css_menu_precio">' . $item['capacidad'] . '</div>';
                }

                $html .= '  </div>
                      </div>
                      <div class="css_separador_cartas"></div>';
            }
            $html .= '</div>';
        }
    } else {
        $html = "No existe la carta";
    }

    return $html;

}


function get_all_contenidos(){

    $db = new MySQL();

    $query = "SELECT * 
              FROM contenido_categorias
              WHERE id_idioma = 1";

    $result = $db->consulta($query);

    $datos = [];
    while ($row = $db->fetch_assoc($result)) {

        $title = eliminar_tildes_url($row['nombre']);
        $title = trim(strtolower($title));
        $title_url = str_replace(' ','-',$title);

        $datos[$row['id_cat']]['titulo'] = $row['nombre'];
        $datos[$row['id_cat']]['url'] = $title_url;

    }

    return $datos;

}

function eliminar_tildes_url($cadena){

    $cadena = str_replace('á','a',$cadena);
    $cadena = str_replace('Á','A',$cadena);

    $cadena = str_replace('é','e',$cadena);
    $cadena = str_replace('É','E',$cadena);

    $cadena = str_replace('í','i',$cadena);
    $cadena = str_replace('Í','I',$cadena);

    $cadena = str_replace('ó','o',$cadena);
    $cadena = str_replace('Ó','O',$cadena);

    $cadena = str_replace('ú','u',$cadena);
    $cadena = str_replace('Ú','U',$cadena);

    $cadena = str_replace('ñ','n',$cadena);
    $cadena = str_replace('Ñ','n',$cadena);

    return $cadena;

}

function get_programa_navidad(){

    $url_base = '../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/programa_navidad/';

    $array_idiomas = [
        1 => 'es',
        2 => 'en',
        3 => 'de',
        4 => 'fr'
    ];

    $idioma_iso = $array_idiomas[$_SESSION['idioma']];

    $array_programa_compare = null;
    $array_programa_final = [];

    $array_programa['programa-navidad'] = [
        'id' => 1,
        'imagen' => 'programa-navidad_'.$idioma_iso.'.png',
        'texto' => LANG_TEXTO_PROGRAMA_NAVIDAD
    ];
    
    $array_programa['programa-newyear'] = [
        'id' => 2,
        'imagen' => 'programa-findeano_'.$idioma_iso.'.png',
        'texto' => LANG_TEXTO_PROGRAMA_NEWYEAR
    ];
    
    $array_programa['menu-cenanavidad'] = [
        'id' => 3,
        'imagen' => 'menu-cenanavidad_'.$idioma_iso.'.png',
        'texto' => LANG_TEXTO_MENU_NAVIDAD
    ];
    
    $array_programa['menu-cenanewyear'] = [
        'id' => 4,
        'imagen' => 'menu-cenafindeano_'.$idioma_iso.'.png',
        'texto' => LANG_TEXTO_MENU_NEWYEAR
    ];

    $archivos = scandir($url_base);
    foreach($archivos as $archivo){

        $archivo_base = end(explode('=',$archivo));

        $explode = explode('.',$archivo_base);
        $explode_2 = explode('_',$explode[0]);

        $fechas = explode('-',$explode_2[2]);
        $fecha_actual = date('Ymd');
        $fecha_inicial = $fechas[0];
        $fecha_final = $fechas[1];

        // if($array_programa[$explode_2[0]] && !$array_programa_compare[$explode_2[0]]){
        if($array_programa[$explode_2[0]]){

            // if(date('Ymd') <= $explode_2[2] && $idioma_iso == $explode_2[1]){
            if(($fecha_actual >= $fecha_inicial) && ($fecha_actual <= $fecha_final) && $idioma_iso == $explode_2[1]){ 
                $array_programa[$explode_2[0]]['archivo'][] = $url_base.$archivo;
                $array_programa_compare[$explode_2[0]] = $array_programa[$explode_2[0]];
                $array_programa_final[$array_programa[$explode_2[0]]['id']] = $array_programa[$explode_2[0]];
            }

        }

    }
    
    if($array_programa_compare){
        ksort($array_programa_final);
    }

    return $array_programa_final;

}

?>
