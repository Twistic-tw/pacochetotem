<?php
/**
 * Created by IntelliJ IDEA.
 * User: ruben
 * Date: 22/9/15
 * Time: 16:00
 */
/*
 * Si en la función no se da ningún parametro, devuelve la información del hotel.
 */
function get_tiempo($ids = false) {
    $db = new MySQL();

    $total_id = count(explode(',', $ids));

    if  ($total_id > 0 && $ids!= false)
    {
        // Si preguntamos por una sola localidad le damos los datos disponibles desde hoy para adelante
        if ($total_id == 1)
        {
            $aux = "SELECT * FROM `localidades_prevision`  as t1 JOIN `localidades_aemet` as t2 on t1.id_localidad = t2.cod_local  WHERE `id_localidad` =$ids AND fecha >='".date('Y-m-d'). "'" ;
        }
        else
        {
            $aux = "SELECT * FROM `localidades_prevision`  as t1 JOIN `localidades_aemet` as t2 on t1.id_localidad = t2.cod_local  WHERE `id_localidad` IN ".(($ids)? ((count(explode(',', $ids)) > 1)?"($ids) AND fecha LIKE '".date('Y-m-d')." ".(((date('H') <= '12') && (date('H') > '06'))?"09:00:00'":(((date('H') <= '21') && (date('H') > '12'))?"15:00:00'":((((date('H') <= '24') && (date('H') > '21')) ||  (date('H') <= '06'))?"21:00:00'":""))):"($ids)"):"(".$_SESSION['id_localidad'].") AND fecha > '".(date('Y-m-d H:i:s', strtotime('- 3 hours')))."'");
        }

        $aux2 = $aux. " ORDER BY FIELD(orden, 0 ), orden";
        $result = $db->consulta($aux2);
        while ($row = $db->fetch_assoc($result))
        {
            $datos[] = $row;
        }
    }
    return $datos;
}

function get_tiempo_mex($ids = false) {
    $db = new MySQL();

    $total_id = count(explode(',', $ids));

    if  ($total_id > 0 && $ids!= false)
    {
        // Si preguntamos por una sola localidad le damos los datos disponibles desde hoy para adelante
        if ($total_id == 1)
        {
            $aux = "SELECT * FROM `localidades_prevision`  as t1 JOIN `localidades_aemet` as t2 on t1.id_localidad = t2.cod_local  WHERE `id_localidad` =$ids AND fecha >='".date('Y-m-d'). "'" ;
        }
        else
        {
            $aux = "SELECT * FROM `localidades_prevision`  as t1 JOIN `localidades_aemet` as t2 on t1.id_localidad = t2.cod_local  WHERE `id_localidad` IN ".(($ids)? ((count(explode(',', $ids)) > 1)?"($ids) AND fecha LIKE '".date('Y-m-d')." ".(((date('H') <= '12') && (date('H') > '06'))?"15:00:00'":(((date('H') < '21') && (date('H') > '12'))?"21:00:00'":((((date('H') <= '24') && (date('H') >= '21')) ||  (date('H') <= '06'))?"09:00:00'":""))):"($ids)"):"(".$_SESSION['id_localidad'].") AND fecha > '".(date('Y-m-d H:i:s', strtotime('- 3 hours')))."'");
        }

        $aux2 = $aux. " ORDER BY FIELD(orden, 0 ), orden";
        $result = $db->consulta($aux2);
        while ($row = $db->fetch_assoc($result))
        {
            $datos[] = $row;
        }
    }
    return $datos;
}

function ciudades_tiempo_aux(){
    $db = new MySQL();
    $result = $db->consulta("SELECT `cod_local` FROM `localidades_aemet`  ORDER BY FIELD(orden, 0 ), orden");
    while ($row = $db->fetch_assoc($result)) $datos[] = $row['cod_local'];
    return $datos;
}


function viento_dirección ($value) {
    $value = floatval($value);
    switch ($_SESSION['idioma']) {
        case "1":
            if ($value < 22.5) {
                return "N";
            } elseif ($value < 67.5) {
                return "NE";
            } elseif ($value < 112.5) {
                return "E";
            } elseif ($value < 157.5) {
                return "SE";
            } elseif ($value < 202.5) {
                return "S";
            } elseif ($value < 247.5) {
                return "SO";
            } elseif ($value < 292.5) {
                return "O";
            } elseif ($value < 337.5) {
                return "NO";
            } else {
                return "N";
            }
            break;
        case "2":
            if ($value < 22.5) {
                return "N";
            } elseif ($value < 67.5) {
                return "NE";
            } elseif ($value < 112.5) {
                return "E";
            } elseif ($value < 157.5) {
                return "SE";
            } elseif ($value < 202.5) {
                return "S";
            } elseif ($value < 247.5) {
                return "SW";
            } elseif ($value < 292.5) {
                return "W";
            } elseif ($value < 337.5) {
                return "NW";
            } else {
                return "N";
            }
            break;
        case "3":
            if ($value < 22.5) {
                return "N";
            } elseif ($value < 67.5) {
                return "NO";
            } elseif ($value < 112.5) {
                return "O";
            } elseif ($value < 157.5) {
                return "SO";
            } elseif ($value < 202.5) {
                return "S";
            } elseif ($value < 247.5) {
                return "SW";
            } elseif ($value < 292.5) {
                return "W";
            } elseif ($value < 337.5) {
                return "NW";
            } else {
                return "N";
            }
            break;
    }

}

function ciudades_tiempo(){
    $db = new MySQL();
    $result = $db->consulta("SELECT `cod_local` FROM `localidades_aemet` order by `orden` DESC");
    while ($row = $db->fetch_assoc($result)) $datos[] = $row['cod_local'];
    $resultado = implode(',', $datos);
    return $resultado;
}



function nombre_municipio($cod_local){
    $db = new MySQL();
    $result = $db->consulta("SELECT `nombre` FROM `localidades_aemet` where cod_local = '$cod_local' ");

    $datos = array();
    while ($row = $db->fetch_assoc($result))
    {
        $datos = $row;
    }
    return $datos;
}



function actualizado(){
    $db = new MySQL();
    $result = $db->consulta("SELECT fecha FROM `localidades_prevision` ORDER BY `fecha` DESC LIMIT 1  ");
    $rows = mysql_num_rows( $result);
    if ($rows > 0) {
        $aux =  $db->fetch_assoc($result);
        if (strtotime($aux['fecha']) >= strtotime('+1 day')) return true;
    }
    return false;
}

function addtime($data) {
    $db = new MySQL();
    if ($data == 'clean') {
        $db->consulta("DELETE FROM `localidades_prevision` WHERE 1=1");
    } else {
        //-------------
        //$consulta = "INSERT INTO `localidades_prevision` (`id`, `id_localidad`, `localidad_name`, `fecha`, `json_basico`, `json_total`) VALUES $data";
        $db->consulta($data);
        //-------------
    }
}

function preguntar_tiempo_hoy(){
    $db = new MySQL();
    $result = $db->consulta("SELECT fecha FROM `localidades_prevision` where fecha >= NOW() ORDER BY `fecha` DESC LIMIT 1  ");
    $rows = mysql_num_rows( $result);
    if ($rows > 0) {
         return true;
    }
    return false;
}
