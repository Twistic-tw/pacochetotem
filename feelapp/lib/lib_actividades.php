<?php

    function actividades_hotel($fecha,$idioma, $database = null){

        $db = new MySQL();

        if($database){
            $custom_db = $database . '.';
        } else {
            $custom_db = '';
        }

        $query = "SELECT DISTINCT eca.nombre as nombre_cat, elti.nombre as nombre_lugar, el.icon, el.id_lugar, e.id_evento, e.id_cat, e.hora_ini, e.hora_fin, ec.title, ec.lugar, el.color, ec.content, ec.foto_evento, ec.video_evento, ec.id_lugar, eo.fecha
                    FROM " . $custom_db . "eventos_categorias as eca
                    LEFT JOIN " . $custom_db . "eventos AS e ON eca.id_cat=e.id_cat  
                    LEFT JOIN " . $custom_db . "eventos_ocurrencias AS eo ON e.id_evento = eo.id_evento
                    LEFT JOIN " . $custom_db . "eventos_contenido AS ec ON eo.id_evento = ec.id_evento
                    LEFT JOIN " . $custom_db . "eventos_lugares AS el ON ec.id_lugar=el.id_lugar
                    LEFT JOIN " . $custom_db . "eventos_lugares_tiene_idiomas AS elti ON el.id_lugar= elti.id_lugar 
                    WHERE eo.fecha = '" . $fecha . "' AND ec.id_idioma='" . $idioma . "' AND elti.id_idioma = '" . $idioma . "' AND eca.id_idioma = '" . $idioma . "' ORDER BY e.hora_ini";

        $consulta = $db->consulta($query) or die('lib_actividades, actividades_hotel');

        while ($fila = mysql_fetch_assoc($consulta)) {
            $resultado[] = $fila;
        }

        if($resultado){
            return $resultado;
        }else{
            return false;
        }

    }

    function fechas_7_dias($fecha){

        $array_fechas = array($fecha);
        $count_dias = 0;

        while ($count_dias < 6) {
            $fecha = date("Y-m-d", strtotime($fecha ."+ 1 days"));
            $array_fechas[] = $fecha;
            $count_dias++;
        }

        return $array_fechas;


    }

    function get_datos_actividad($id_evento){

        $id_idioma = $_SESSION['idioma'];

        $db = new MySQL();

        $query = "SELECT e.id_evento, e.hora_ini, e.hora_fin, ec.title, ec.content AS descripcion, ec.foto_evento, ec.video_evento, 
                          eca.nombre AS nombre_categoria, eca.class_icon AS icono_categoria, el.icon AS icono_lugar, el.color AS color_lugar, elti.nombre AS nombre_lugar 
                          FROM eventos AS e INNER JOIN eventos_contenido AS ec ON e.id_evento = ec.id_evento 
                          INNER JOIN eventos_categorias AS eca ON e.id_cat = eca.id_cat 
                          INNER JOIN eventos_lugares AS el ON ec.id_lugar = el.id_lugar 
                          INNER JOIN eventos_lugares_tiene_idiomas AS elti ON el.id_lugar = elti.id_lugar 
                          WHERE ec.id_idioma = '".$id_idioma."' AND elti.id_idioma = '".$id_idioma."' AND eca.id_idioma = '".$id_idioma."' AND e.id_evento = '".$id_evento."'";

        $consulta = $db->consulta($query);

        $resultado = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $resultado[] = $fila;
        }

        if($resultado){
            return $resultado[0];
        }else{
            return false;
        }

    }

?>