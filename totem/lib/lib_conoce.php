<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function conoce_listado_municipios(){
    $db = new MySQL();
    $queryString = "SELECT * FROM localidades_aemet 
                        ORDER BY orden DESC, nombre ASC";
    $query = $db->consulta($queryString);
    
    $datos;
    while ($tArray = $db->fetch_assoc($query)){
        $datos[] = $tArray;
    }
    return $datos;
    
}

/** esta funcion obtiene el mapa asociado a un centro. Se le pasa id_centro.*/
/*function conoce_dame_datos_cliente($id_centro){
    $db = new MySQL();
    $queryString = "SELECT * FROM cliente_mapa
                        WHERE id_cliente = '$id_centro'";
                        

    $query = $db->consulta($queryString);
    
    return $db->fetch_assoc($query);
}*/

