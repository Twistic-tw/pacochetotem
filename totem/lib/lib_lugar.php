<?php


//Devuelve toda la informacion de pantalla principal (info general) del lugar donde se encuentra el totem, en el idioma de la session

function obtener_info_general_lugar($id_lugar, $id_idioma){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `lugares_info_general` as t1,
         `lugares` as t2
        where t2.`id` = t1.`id_lugar` 
        and t1.`id_idioma` = '$id_idioma'
        and t1.`id_lugar` = '$id_lugar'
        and t2.`id` = '$id_lugar'";
                        
 $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) 
    {
        $datos[] = $row;
    }
    return $datos;
}

//obtiene los sitios de interes de un lugar en un idioma

function obtener_sitios_lugar($id_lugar, $id_idioma, $tipo, $id_cadena){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `lugares_sitios` as t1,
         `lugares_sitios_tiene_idiomas` as t2,
         `lugares_clasificacion` as t3,
         `lugares_clasificacion_tiene_idiomas` as t4

        where t1.`id_lugar` = '$id_lugar' 
        and  t1.`tipo` = '$tipo'  
        and t2.`id_idioma` = '$id_idioma'
        and t1.`id` = t2.`id_lugar_sitios`
        and t3.`id` = t1.`clasificacion`
        and t3.`id` = t4.`id_clasificacion`
        and t4.`id_idioma` = '$id_idioma'
        AND t1.`id` NOT IN (SELECT `id_sitio` FROM `lugares_sitios_excluir`
                WHERE `id_cadena` = '$id_cadena'
                 )";

                       
 $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) 
    {
        $datos[] = $row;
    }
    return $datos;
}