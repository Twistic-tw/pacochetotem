<?php

    function actividades_hotel($fecha,$idioma){

        $db = new MySQL();

        $query = "SELECT DISTINCT eca.nombre as nombre_cat, elti.nombre as nombre_lugar, el.icon, el.id_lugar, e.id_evento, e.id_cat, e.hora_ini, e.hora_fin, ec.title, ec.lugar, el.color, ec.content, ec.foto_evento, ec.video_evento, ec.id_lugar, eo.fecha
                    FROM eventos_categorias as eca
                    LEFT JOIN eventos AS e ON eca.id_cat=e.id_cat  
                    LEFT JOIN eventos_ocurrencias AS eo ON e.id_evento = eo.id_evento
                    LEFT JOIN eventos_contenido AS ec ON eo.id_evento = ec.id_evento
                    LEFT JOIN eventos_lugares AS el ON ec.id_lugar=el.id_lugar
                    LEFT JOIN eventos_lugares_tiene_idiomas AS elti ON el.id_lugar= elti.id_lugar 
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

?>