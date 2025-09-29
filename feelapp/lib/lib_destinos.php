<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** esta funcion obtiene el mapa asociado a un centro. Se le pasa id_centro.*/
function get_destinos($id_cadena, $id_idioma='1'){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `destinos_tiene_idiomas` as t1,
        `destinos` as t2,
        `destinos_cadena_pais` as t3
        where t1.`id_idioma` = '$id_idioma'
        and t3.`id_cadena` = '$id_cadena'
        and t3.`id_destino` = t2.`id`
        and t1.`id_destino` = t2.`id`
        group by t2.`id`";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}

/** esta funcion obtiene las coordenadas del mapa para un destino. Se le pasa id_destino.*/
function get_destinos_coordenadas($id_destino){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `destinos_tiene_coordenadas`
        where `id_destino` = $id_destino";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}

function get_destinos_paises($id_cadena,$id_destino,$id_idioma='1'){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `destinos_paises_tiene_idiomas` as t1,
        `destinos_cadena_pais` as t2
        where t1.`id_idioma` = '$id_idioma'
        and t2.`id_destino` = '$id_destino'
        and t2.`id_cadena` = '$id_cadena'
        and t1.`id_pais` = t2.`id_pais`";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}


function get_destinos_hoteles($id_pais, $id_cadena, $id_idioma='1'){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `destinos_hoteles_tiene_idiomas` as t1,
        `destinos_hoteles` as t2
        where t1.`id_idioma` = '$id_idioma'
        and t2.`id_pais` = '$id_pais'
        and t2.`id_cadena` = '$id_cadena'
        and t1.`id_hotel` = t2.`id`
        order by t1.`region`";

    $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos[] = $row;
    }
    return $datos;
}

?>