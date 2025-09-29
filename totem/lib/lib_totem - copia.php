<?php


/** funcion para obtener el lsitado de días con actividades. La funcion devuelve un array en el que estan agrupadas las 
  * actividades por hotel y por fecha. Dentro cada uno de estos indice contiene una matriz de arrays con campos tales como fehca inicio, fecha fin...
  * @param string $fecha Fecha para la que deseamos obtener el listaod. Formato MM-DD-YYYY
  * @return json devuelve un json con los datos. En caso de no haberlos el array estará vacio */
function totem_listado_actividades_agenda_json() {

    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];

    $year = date("Y");
    
    $date_ini = $year . "-01-01"; //agenda anual
    $date_fin = $year . "-12-31";

    $sql = "SELECT DISTINCT t1.fecha, t2.id_cat, t3.nombre, t5.nombre as nombre_padre  
                FROM eventos_ocurrencias as t1, eventos as t2, eventos_categorias as t3, eventos_categorias as t5 
                WHERE t1.fecha BETWEEN '" . $date_ini . "' 
                    AND '" . $date_fin . "' 
                    AND t1.id_centro='" . $id_centro . "' 
                    AND t1.id_evento = t2.id_evento 
                    AND t3.id_cat = t2.id_cat
                    
                    AND t5.id_cat = t3.padre
                    AND t5.id_idioma=1
                    AND t5.id_centro=$id_centro";

    $consulta = $db->consulta($sql);

    $fechas = array();

    while ($fila = $db->fetch_array($consulta)) {
        if ( $fila['nombre'] == "Hotel" || $fila['nombre_padre']== "Hotel" ) {
            $fechas['hotel'][date("j/n/Y", strtotime($fila['fecha']))] = true;
        } else {
            $fechas['isla'][date("j/n/Y", strtotime($fila['fecha']))] = true;
            
        }
    }

    /* for($j=28; $j<30; $j++) {
      $fechas['hotel'][ "$j/8/2013"] = true;
      } */

    return json_encode($fechas);
}

/** funcion para obtener el lsitado de actividades para el dia concreto. La funcion devuelve un array en el que estan agrupadas las 
  * actividades por hotel y por fecha. Dentro cada uno de estos indice contiene una matriz de arrays con campos tales como fehca inicio, fecha fin...
  * @param string $fecha Fecha para la que deseamos obtener el listaod. Formato MM-DD-YYYY
  * @param int $idioma Indica el idioma para el que se solicita el contenido. Es un valor numerico.
  * @return array devuelve un array con los datos. En caso de no haberlos el array estará vacio */
function totem_listado_actividades_info($fecha, $idioma=1) {
    $db = new MySQL();
    $id_centro = $_SESSION['id_centro'];
    
    $fecha = date( "Y-m-d", strtotime($fecha) );
    
    $sql = "SELECT  t4.title, t4.content,
                    t2.hora_ini, t2.hora_fin, t2.repeticiones, t2.fecha_ini, t2.fecha_fin, t2.id_evento, t2.id_cat, 
                    t3.padre, t3.nombre, t3.class_icon as icon_subcat, 
                    t5.nombre as nombre_padre, t5.class_icon as icon_cat
                FROM eventos_ocurrencias as t1, eventos as t2, eventos_categorias as t3, eventos_contenido as t4, eventos_categorias as t5
                WHERE t1.fecha = '$fecha' 
                    AND t1.id_centro = $id_centro 
                        
                    AND t1.id_evento = t4.id_evento
                    AND t4.id_idioma = '$idioma'
                    
                    AND t1.id_evento = t2.id_evento 
                    AND t2.id_cat = t3.id_cat 
                    AND t3.padre = t5.id_cat
                    AND t5.id_idioma=1
                    AND t5.id_centro=$id_centro
                        
                    GROUP BY t2.id_evento ORDER BY t2.hora_ini ASC";

    $consulta = $db->consulta($sql);

    $fechas = array();
    while ($fila = $db->fetch_array($consulta)){
        if ( $fila['nombre'] == "Hotel"){
            $fechas['hotel'][] = array ( "repeticiones"=>$fila['repeticiones'], "fecha_ini"=>$fila['fecha_ini'], "fecha_fin"=>$fila['fecha_fin'], "icon_cat"=>$fila['icon_cat'], 
                                      "icon_subcat"=>$fila['icon_subcat'], "hora_ini"=>$fila['hora_ini'], "hora_fin"=>$fila['hora_fin'], "nombre_cat"=>$fila['nombre'], 
                                      "id_categoria"=>$fila['id_cat'], "padre"=>$fila['padre'], "titulo_evento"=>$fila['title'], "contenido"=>$fila['content']);
            
        }
        elseif( $fila['nombre_padre'] == "Hotel"){
            $fechas['hotel'][] = array ( "repeticiones"=>$fila['repeticiones'], "fecha_ini"=>$fila['fecha_ini'], "fecha_fin"=>$fila['fecha_fin'], "icon_cat"=>$fila['icon_cat'], 
                                      "icon_subcat"=>$fila['icon_subcat'], "hora_ini"=>$fila['hora_ini'], "hora_fin"=>$fila['hora_fin'], "nombre_cat"=>$fila['nombre_padre'], 
                                      "id_categoria"=>$fila['id_cat'], "padre"=>$fila['padre'], "titulo_evento"=>$fila['title'], "contenido"=>$fila['content']);
        }
        else {
            $fechas['isla'][] = array ( "repeticiones"=>$fila['repeticiones'], "fecha_ini"=>$fila['fecha_ini'], "fecha_fin"=>$fila['fecha_fin'], "icon_cat"=>$fila['icon_cat'], 
                                      "icon_subcat"=>$fila['icon_subcat'], "hora_ini"=>$fila['hora_ini'], "hora_fin"=>$fila['hora_fin'], "nombre_cat"=>$fila['nombre'], 
                                      "id_categoria"=>$fila['id_cat'], "padre"=>$fila['padre'], "titulo_evento"=>$fila['title'], "contenido"=>$fila['content'] );
        }
        
    }
    //return json_encode($fechas);
    return $fechas;
}


/** funcion para obtener el texto asociado a un estado codificado del tiempo, por ejemplo el 13 se corresponde con intervalos nubosos.
  * La funcion discrimina en funcion del idioma y dle codigo para devolver una string con dicho codigo. 
  * @param string $estado indica el estado (el valor alfanumerico) para el que deseamos obtener el titulo. Funciona en base a
  *  los codigos que obtenemos del aemet.
  * @param string $idioma indica el idioma para el cual deseamos obtener el titulo del estado. Por defecto español.
  * @return string devuelve una string con dicho texto
  */
function totem_tiempoCodesToString($estado) {
    
    $idioma = "es";
    $tiempoIdiomas = array("es", "en", "de", "ru");
    $tiempoIdiomas["es"] = array ("11"=>"Despejado", "11n"=>"Despejado noche",
                                  "12"=>"Poco nuboso", "12n"=>"Poco nuboso noche",
                                  "13"=>"Intervalos nubosos", "13n"=>"Intervalos nubosos noche",
                                  "14"=>"Nuboso", "14n"=>"Nuboso noche",
                                  "15"=>"Muy nuboso", "15n"=>"Muy nuboso noche",
                                  "16"=>"Cubierto", "16n"=>"Cubierto noche",
                                  "17"=>"Nubes altas", "17n"=>"Nubes altas noche",
                                  "43"=>"Intervalos nubosos con lluvia escasa", "43n"=>"Intervalos nubosos con lluvia escasa noche",
                                  "44"=>"Cubierto con lluvia escasa", "44n"=>"Cubierto con lluvia escasa noche",
                                  "45"=>"Muy nuboso con lluvia escasa", "45n"=>"noche",
                                  "46"=>"Cubierto con lluvia escasa", "46n"=>"Cubierto con lluvia escasa noche",
                                  "23"=>"Intervalos nubosos con lluvia", "23n"=>"Intervalos nubosos con lluvia noche",
                                  "24"=>"Nuboso con lluvia", "24n"=>"Nuboso con lluvia noche",
                                  "25"=>"Muy nuboso con lluvia", "25n"=>"Muy nuboso con lluvia noche",
                                  "51"=>"Intervalos nubosos con tormenta", "51n"=>"Intervalos nubosos con tormenta noche",
                                  "52"=>"Nuboso con tormenta", "52n"=>"Nuboso con tormenta noche",
                                  "53"=>"Muy nuboso con tormenta", "53n"=>"Muy nuboso con tormenta noche",
                                  "54"=>"Cubierto con tormenta", "54n"=>"Cubierto con tormenta noche",
                                  "61"=>"Intervalos nubosos con tormenta y lluvia escasa", "61n"=>"Intervalos nubosos con tormenta y lluvia escasa noche",
                                  "62"=>"Nuboso con tormenta y lluvia escasa", "62n"=>"Nuboso con tormenta y lluvia escasa noche",
                                  "63"=>"Muy nuboso con tormenta y lluvia escasa", "63n"=>"Muy nuboso con tormenta y lluvia escasa noche",
                                  "64"=>"Cubierto con tormenta y lluvia escasa", "64n"=>"Cubierto con tormenta y lluvia escasa noche",
                                  "71"=>"Intervalos nubosos con nieve escasa", "71n"=>"Intervalos nubosos con nieve escasa noche",
                                  "72"=>"Nuboso con nieve escasa", "72n"=>"Nuboso con nieve escasa noche",
                                  "73"=>"Muy nuboso con nieve escasa", "73n"=>"Muy nuboso con nieve escasa noche",
                                  "74"=>"Cubierto con nieve escasa", "74n"=>"Cubierto con nieve escasa noche",
                                  "33"=>"Intervalos nubosos con nieve", "33n"=>"Intervalos nubosos con nieve noche",
                                  "34"=>" Nuboso con nieve", "34n"=>"Nuboso con nieve noche",
                                  "35"=>"Muy nuboso con nieve", "35n"=>"Muy nuboso con nieve noche",
                                  "36"=>"Cubierto con nieve", "36n"=>"Cubierto con nieve noche");
                                                                    
    $tiempoIdiomas["en"] = array ("11"=>"", "11n"=>"",
                                  "12"=>"", "12n"=>"",
                                  "13"=>"", "13n"=>"",
                                  "14"=>"", "14n"=>"",
                                  "15"=>"", "15n"=>"",
                                  "16"=>"", "16n"=>"",
                                  "17"=>"", "17n"=>"",
                                  "43"=>"", "43n"=>"",
                                  "44"=>"", "44n"=>"",
                                  "45"=>"", "45n"=>"",
                                  "46"=>"", "46n"=>"",
                                  "23"=>"", "23n"=>"",
                                  "24"=>"", "24n"=>"",
                                  "25"=>"", "25n"=>"",
                                  "51"=>"", "51n"=>"",
                                  "52"=>"", "52n"=>"",
                                  "53"=>"", "53n"=>"",
                                  "54"=>"", "54n"=>"",
                                  "61"=>"", "61n"=>"",
                                  "62"=>"", "62n"=>"",
                                  "63"=>"", "63n"=>"",
                                  "64"=>"", "64n"=>"",
                                  "71"=>"", "71n"=>"",
                                  "72"=>"", "72n"=>"",
                                  "73"=>"", "73n"=>"",
                                  "74"=>"", "74n"=>"",
                                  "33"=>"", "33n"=>"",
                                  "34"=>"", "34n"=>"",
                                  "35"=>"", "35n"=>"",
                                  "36"=>"", "36n"=>"");
                                  
    $tiempoIdiomas["de"] = array ("11"=>"", "11n"=>"",
                                  "12"=>"", "12n"=>"",
                                  "13"=>"", "13n"=>"",
                                  "14"=>"", "14n"=>"",
                                  "15"=>"", "15n"=>"",
                                  "16"=>"", "16n"=>"",
                                  "17"=>"", "17n"=>"",
                                  "43"=>"", "43n"=>"",
                                  "44"=>"", "44n"=>"",
                                  "45"=>"", "45n"=>"",
                                  "46"=>"", "46n"=>"",
                                  "23"=>"", "23n"=>"",
                                  "24"=>"", "24n"=>"",
                                  "25"=>"", "25n"=>"",
                                  "51"=>"", "51n"=>"",
                                  "52"=>"", "52n"=>"",
                                  "53"=>"", "53n"=>"",
                                  "54"=>"", "54n"=>"",
                                  "61"=>"", "61n"=>"",
                                  "62"=>"", "62n"=>"",
                                  "63"=>"", "63n"=>"",
                                  "64"=>"", "64n"=>"",
                                  "71"=>"", "71n"=>"",
                                  "72"=>"", "72n"=>"",
                                  "73"=>"", "73n"=>"",
                                  "74"=>"", "74n"=>"",
                                  "33"=>"", "33n"=>"",
                                  "34"=>"", "34n"=>"",
                                  "35"=>"", "35n"=>"",
                                  "36"=>"", "36n"=>"");
    
    
    
    return $tiempoIdiomas[ $idioma ][ $estado ];
}


/** esta funcion devuelve la imagen asociada a cada estado del cielo. se le pasa
 * el array resultante de la consulta para obtener los datos meteorologicos y dentor
 * se realiza un switch que recorre los estados para las distintas franjas horarias.
 * @param array $datos array con todos los datos devueltos por la consulta sql
 * @return array devuelve un array con un indice que refleja la hora y tiene el nombre de la imagen a mostrar */
function totem_getIconoFromClimateCode($datos){
    $resultados = array();
    
    for ($i=0; $i<24; $i+=6){
        if ($i == 0){
            $index = "cielo_00_06";
        }
        elseif ($i == 6) {
            $index = "cielo_06_12";
        }
        else {
            $index = "cielo_$i"."_".($i+6);
        }
        
        switch ($datos[$index]) {
            case "11":
            case "11n":
                $resultados[$i] = "sol.png";
                break;
            
            case "12":
            case "12n":
            case "13":
            case "13n":
            case "14":
            case "14n":
                $resultados[$i] = "nublado_parcial.png";
                break;
            
            case "15":
            case "15n":
            case "17":
            case "17n":
                $resultados[$i] = "nublado.png";
                break;
            
            case "16":
            case "16n":
                $resultados[$i] = "cubierto.png";
                break;
            
            
            case "54":
            case "54n":
                $resultados[$i] = "lluvia_sol.png";
                break;
            
            
            case "43":
            case "43n":
            case "44":
            case "44n":
            case "45":
            case "45n":
            case "46":
            case "46n":
                $resultados[$i] = "lluvia_poco.png";
                break;
            
            
            case "63":
            case "63n":
            case "64":
            case "64n":
                $resultados[$i] = "tormenta_electrica_lluvia.png";
                break;
            
            
            default:
                break;
        }
    }
    
    return $resultados;
}


/** index indica el index que estamos trabajando del array global. Pendiente.*/
function recursiveDatos($index, $datosArray){
    
    if ( $datosArray[$index]['padre'] == 0){
        //condicion de parada
        return array($datosArray[$index]['id_cat'] => array (0=>$datosArray[$index]) );
    }
    else {
        
        $resultArray = recursiveDatos($index+1, $datosArray);
    }
    
}



/** funcion para traducir el estado a una string identificativa. Se traduce según el idioma.
 * @param string $estado Codigo del estado tal cual viene de la peticion
 * @return string Devuelve una string con el texto asociado al codigo para el estado 
 */
function vuelosEstado($estado) {
    
    $estadosName['es'] = array("S"=>"Programado",'A'=>'Activo','C'=>'Cancelado','D'=>'Desviado','DN'=>'Pendiente de información','L'=>'En tierra','NO'=>'No operativo','R'=>'Redireccionado','U'=>'Desconocido');
    $estadosName['en'] = array("S"=>"",'A'=>'','C'=>'','D'=>'','DN'=>'','L'=>'','NO'=>'','R'=>'','U'=>'');
    $estadosName['gr'] = array("S"=>"",'A'=>'','C'=>'','D'=>'','DN'=>'','L'=>'','NO'=>'','R'=>'','U'=>'');
    $estadosName['rs'] = array("S"=>"",'A'=>'','C'=>'','D'=>'','DN'=>'','L'=>'','NO'=>'','R'=>'','U'=>'');
    
    return $estadosName['es'][$estado];
}

?>
