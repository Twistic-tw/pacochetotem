<?php

function get_hotel($id_hotel){

    $db = new MySQL_cli();

    $query = "SELECT t1.*, t2.proyect_name FROM bd_twistic_sat.clients_centers AS t1 
    INNER JOIN bd_twistic_sat.clients AS t2 ON t1.id_client = t2.id 
    WHERE t1.id = '" . $id_hotel . "' AND t1.active IN (1,-1)";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;

}

function get_contenidos($id_contenido){
    $db = new MySQL();

    $query = "SELECT * FROM contenidos WHERE activo = 1 AND id_categoria = ".$id_contenido." AND id_idioma = ".$_SESSION['idioma']."";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}

function get_lista_contenidos($id_padre){
    $db = new MySQL();

    //$query = "SELECT * FROM contenidos as c LEFT JOIN contenido_categorias as cc ON c.id_categoria = cc.id_cat WHERE c.id_idioma = ".$_SESSION['idioma']." AND cc.id_idioma = 1 AND cc.padre = ".$id_padre;
    $query = "SELECT * FROM contenido_categorias WHERE id_idioma = ".$_SESSION['idioma']." AND padre = ".$id_padre;

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }

    return $datos;
}

function check_contenido($id_categoria){

    $db = new MySQL();

    //$query = "SELECT * FROM contenidos as c LEFT JOIN contenido_categorias as cc ON c.id_categoria = cc.id_cat WHERE c.id_idioma = 1 AND cc.id_idioma = 1 AND cc.padre = ".$id_padre;
    $query = "SELECT id_contenido FROM contenidos WHERE id_idioma = ".$_SESSION['idioma']." AND id_categoria = ".$id_categoria . " AND activo = 1";

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }

    return $datos;

}


function fechas_calendario($fecha){
    switch ($_SESSION['idioma']) {
        case '1':

            $fecha_i = $fecha;
            $fecha = strftime("%A %d de %B %Y",strtotime($fecha));
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                $fecha = utf8_encode(strftime("%A %d de %B %Y",strtotime($fecha_i)));
            break;

        case '2':

            $fecha = strftime("%A %B %d, %Y",strtotime($fecha));

            break;

        case '3':

            $fecha = strftime("%A %d. %B %Y",strtotime($fecha));

            break;

        //El polaco esta en ingles
        case '4':

            $fecha = strftime("%A %B %d, %Y",strtotime($fecha));

            break;
    }

    return $fecha;

}

function datos_hotel(){

    $db = new MySQL();
    $query = 'SELECT * FROM centro WHERE id_centro = '.$_SESSION['id_centro'];

    $result = $db->consulta($query);

    $datos = null;
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }

    return $datos[0];

}

?>
