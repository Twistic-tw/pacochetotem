<?php

//////////////////////////////////////////////Funciones para comercios nuevo
function get_categorias_con_quehacer2($id_centro,$idioma){
    $db = new MySQL_comun();
    $hoy = date('Y-m-d');
    $query = "SELECT t1.id_categoria, t1.nombre_categoria From
                comercio_categoria as t1,
                comercio as t2,
                comercio_seccion as t3,
                comercio_centro as t4
                where t4.id_centro = $id_centro
                AND t4.id_comercio = t2.id_comercio
                AND t2.id_seccion = t3.id_seccion
                AND t3.id_categoria = t1.id_categoria
                AND t1.idioma = $idioma
                AND t4.valido = '1'
                AND t4.fecha_inicio <= '$hoy'
                AND t4.fecha_fin >= '$hoy'
                group by id_categoria";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}


function get_todos_comercios2($id_centro){
    /* $idioma = $_SESSION['idioma'];*/
    $db = new MySQL_comun();
    $hoy = date('Y-m-d');

    $queryString = "SELECT * from comercio_centro as t1,
                    comercio as t2
                    WHERE t1.id_centro = $id_centro
                    AND t1.id_comercio = t2.id_comercio
                    AND t1.fecha_inicio <= '$hoy'
                    AND t1.fecha_fin >= '$hoy'
                    AND t1.valido = '1'
                    ORDER BY t1.orden DESC";

    $rec = $db->consulta($queryString);
    $datos = array();

    while ($tArray = $db->fetch_assoc($rec)){
        $datos[] = $tArray;
    }
    return $datos;
}

function get_categoria_comercio2($id_seccion,$idioma){
    $db = new MySQL_comun();

    $queryString = "SELECT * from comercio_categoria as t1,
                    comercio_seccion as t2
                    WHERE t2.id_seccion = $id_seccion
                    AND t2.id_categoria = t1.id_categoria
                    AND t1.idioma = $idioma
                    AND t2.idioma = $idioma";


    $rec = $db->consulta($queryString);
    $datos = array();

    while ($tArray = $db->fetch_assoc($rec)){
        $datos[] = $tArray;
    }
    return $datos;
}

function get_comercio_detalles2($comercioId, $idioma){
    $db = new MySQL_comun();
    $query = "SELECT t1.*,  t2.tipo_contenido, t2.contenido

            FROM comercio_centro as t1, comercio_contenido as t2
                WHERE t1.id_comercio_centro='$comercioId'
                AND t2.id_comercio_centro = '$comercioId'
                AND t2.id_idioma = '$idioma'";

    $result = $db->consulta($query);

    // echo $query;
    // exit;

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}


function get_comercio_id_general($comercioId,$id_centro){
    $db = new MySQL_comun();

    $queryString = "SELECT id_comercio from comercio_centro as t1
                    WHERE t1.id_centro = $id_centro
                    AND t1.id_comercio_centro = $comercioId";

    $rec = $db->consulta($queryString);


    while ($tArray = $db->fetch_assoc($rec)){
        $datos[] = $tArray;
    }
    return $datos;
}


function log_quehacer($id_comercio,$id_centro,$id_idioma)
{
    $db = new MySQL_alemania();

    $query = "INSERT INTO comercio_log (id_comercio,id_centro,id_idioma,fecha)
        VALUES ($id_comercio, $id_centro, $id_idioma, NOW() )";

    return $db->consulta($query);

}



////////////////////////////////////////////////////////////////Funciones viejas

function get_categorias_con_quehacer($idioma){
    $db = new MySQL();
    $hoy = date('Y-m-d');
    $query = "SELECT t1.id_categoria, t1.nombre_categoria From 
                comercio_categoria as t1, 
                comercio as t2,
                comercio_seccion as t3,
                comercio_centro_contrato as t4,
                comercio_centro as t5 
                where t2.id_seccion = t3.id_seccion 
                AND t3.id_categoria = t1.id_categoria
                AND t4.id_comercio_centro = t5.id
                AND t5.id_comercio = t2.id_comercio
                AND t1.idioma = $idioma
                AND t4.valido = '1'
                AND t4.fecha_inicio <= '$hoy'
                AND t4.fecha_fin >= '$hoy'
                group by id_categoria";

     $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) 
    {
        $datos[] = $row;
    }
    return $datos;
}


function get_comercio_detalles($comercioId, $idioma){
    $db = new MySQL();
    $query = "SELECT t1.*,  t2.tipo_contenido, t2.contenido, 
                 t3.logo, t3.baner, t3.fondo, t3.color
            FROM comercio as t1, comercio_contenido as t2, comercio_centro as t3
                WHERE t1.id_comercio='$comercioId'
                AND t2.id_comercio = '$comercioId'
                AND t3.id_comercio = '$comercioId'
                AND t2.id_idioma = '$idioma'";

     $result = $db->consulta($query);

     // echo $query;
     // exit;

    $datos = array();
    while ($row = $db->fetch_assoc($result)) 
    {
        $datos[] = $row;
    }
    return $datos;
}


function get_todos_comercios(){
   /* $idioma = $_SESSION['idioma'];*/
    $db = new MySQL();
    $hoy = date('Y-m-d');
    
    $queryString = "SELECT * from comercio as t1,
                    comercio_centro as t2,
                    comercio_centro_contrato as t3
                    WHERE t1.id_comercio = t2.id_comercio
                    AND t2.id = t3.id_comercio_centro
                    AND t3.fecha_inicio <= '$hoy'
                    AND t3.fecha_fin >= '$hoy'
                    AND t3.valido = '1' 
                    ORDER BY t3.orden DESC";
    
    
    $rec = $db->consulta($queryString);
    $datos = array();

    while ($tArray = $db->fetch_assoc($rec)){
        $datos[] = $tArray;
    }
    return $datos;
}


function get_categoria_comercio($id_seccion,$idioma){
    $db = new MySQL();

        $queryString = "SELECT * from comercio_categoria as t1,
                    comercio_seccion as t2
                    WHERE t2.id_seccion = $id_seccion
                    AND t2.id_categoria = t1.id_categoria
                    AND t1.idioma = $idioma
                    AND t2.idioma = $idioma";
    
    
    $rec = $db->consulta($queryString);
    $datos = array();

    while ($tArray = $db->fetch_assoc($rec)){
        $datos[] = $tArray;
    }
    return $datos;

}




