<?php

function get_restaurantes($tipo){


    $id_idioma = $_SESSION['idioma'];

    $db = new MySQL();

    $query = 'SELECT r.id_restaurante, r.dias_apertura, r.reserva, r.imagen, r.release, rti.nombre, rti.subtitulo, rti.descripcion, rti.observaciones, rt.id AS id_restaurante_turno, rt.activo AS activo_turno, rt.icono, rtti.nombre AS nombre_turno, rth.id AS id_restaurante_turno_horario, rth.hora_inicio, rth.hora_fin, rth.activo AS activo_turno_horario 
                    FROM restaurantes AS r
                    INNER JOIN restaurantes_tiene_idiomas AS rti ON r.id_restaurante = rti.id_restaurante
                    INNER JOIN restaurantes_turnos_horarios AS rth ON r.id_restaurante = rth.id_restaurante
                    INNER JOIN restaurantes_turnos AS rt ON rth.id_restaurante_turno = rt.id
                    INNER JOIN restaurantes_turnos_tiene_idiomas AS rtti ON rt.id = rtti.id_restaurante_turno
                    WHERE r.activo =1
                    AND rt.activo =1
                    AND rth.activo =1
                    AND rti.id_idioma = "'.$id_idioma.'" 
                    AND rtti.id_idioma = "'.$id_idioma.'"  
                    AND r.tipo = "'.$tipo.'" ORDER BY r.id_restaurante, rth.hora_inicio ASC ';

    $consulta = $db->consulta($query);

    $resultado = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        /* Para no tener que hacer varias llamadas y tener los datos controlados */
        $resultado[$fila['id_restaurante']]['id_restaurante'] = $fila['id_restaurante'];
        $resultado[$fila['id_restaurante']]['dias_apertura'] = $fila['dias_apertura'];
        $resultado[$fila['id_restaurante']]['reserva'] = $fila['reserva'];
        $resultado[$fila['id_restaurante']]['imagen'] = $fila['imagen'];
        $resultado[$fila['id_restaurante']]['release'] = $fila['release'];
        $resultado[$fila['id_restaurante']]['nombre'] = $fila['nombre'];
        $resultado[$fila['id_restaurante']]['subtitulo'] = $fila['subtitulo'];
        $resultado[$fila['id_restaurante']]['descripcion'] = $fila['descripcion'];
        $resultado[$fila['id_restaurante']]['observaciones'] = $fila['observaciones'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['id_restaurante_turno'] = $fila['id_restaurante_turno'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['activo_turno'] = $fila['activo_turno'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['icono'] = $fila['icono'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['nombre_turno'] = $fila['nombre_turno'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['id_restaurante_turno_horario'] = $fila['id_restaurante_turno_horario'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['hora_inicio'] = $fila['hora_inicio'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['hora_fin'] = $fila['hora_fin'];
        $resultado[$fila['id_restaurante']]['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['activo_turno_horario'] = $fila['activo_turno_horario'];

    }

    if($resultado){
        return $resultado;
    }else{
        return false;
    }

}

function get_restaurante($id_restaurante,$tipo){


    $id_idioma = $_SESSION['idioma'];

    $db = new MySQL();

    $query = 'SELECT r.id_restaurante, r.tipo_reserva, r.dias_apertura, r.reserva, r.imagen, r.release, rti.nombre, rti.subtitulo, rti.descripcion, rti.observaciones, rt.id AS id_restaurante_turno, rt.activo AS activo_turno, rt.icono, rtti.nombre AS nombre_turno, rth.id AS id_restaurante_turno_horario, rth.hora_inicio, rth.hora_fin, rth.activo AS activo_turno_horario 
                        FROM restaurantes AS r
                        INNER JOIN restaurantes_tiene_idiomas AS rti ON r.id_restaurante = rti.id_restaurante
                        INNER JOIN restaurantes_turnos_horarios AS rth ON r.id_restaurante = rth.id_restaurante
                        INNER JOIN restaurantes_turnos AS rt ON rth.id_restaurante_turno = rt.id
                        INNER JOIN restaurantes_turnos_tiene_idiomas AS rtti ON rt.id = rtti.id_restaurante_turno
                        WHERE r.activo =1 
                        AND r.id_restaurante = "'.$id_restaurante.'" 
                        AND rt.activo =1
                        AND rth.activo =1
                        AND rti.id_idioma = "'.$id_idioma.'" 
                        AND rtti.id_idioma = "'.$id_idioma.'"  
                        AND r.tipo = "'.$tipo.'" ORDER BY r.id_restaurante, rth.hora_inicio ASC';

    $consulta = $db->consulta($query);

    $resultado = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        /* Para no tener que hacer varias llamadas y tener los datos controlados */
        $resultado['id_restaurante'] = $fila['id_restaurante'];
        $resultado['dias_apertura'] = $fila['dias_apertura'];
        $resultado['reserva'] = $fila['reserva'];
        $resultado['imagen'] = $fila['imagen'];
        $resultado['release'] = $fila['release'];
        $resultado['nombre'] = $fila['nombre'];
        $resultado['subtitulo'] = $fila['subtitulo'];
        $resultado['descripcion'] = $fila['descripcion'];
        $resultado['observaciones'] = $fila['observaciones'];
        $resultado['tipo_reserva'] = $fila['tipo_reserva'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['id_restaurante_turno'] = $fila['id_restaurante_turno'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['activo_turno'] = $fila['activo_turno'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['icono'] = $fila['icono'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['nombre_turno'] = $fila['nombre_turno'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['id_restaurante_turno_horario'] = $fila['id_restaurante_turno_horario'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['hora_inicio'] = $fila['hora_inicio'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['hora_fin'] = $fila['hora_fin'];
        $resultado['turnos'][$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']]['activo_turno_horario'] = $fila['activo_turno_horario'];

    }

    if($resultado){
        return $resultado;
    }else{
        return false;
    }

}

function insert_reserva_restaurante($post){

    //echo "<pre>";
    //print_r($post);

    if(!$post['num_habitacion'] || $post['num_habitacion'] == 0){
        $post['num_habitacion'] = 100;
    }

    if(!$post['nombre_usuario']){
        $post['nombre_usuario'] = 'Rafael Izquierdo';
    }

    //echo "<pre>";
    //print_r($post);

    //Por ahora ponemos el id de usuario a 1
    $id_usuario = 1;
    $id_restaurante = $post['id_restaurante'];
    $fecha = $post['fecha_reserva'];
    $hora_normal = $post['horario_hora'];
    $hora = $post['horario_hora'].':00';
    $total_comensales = $post['total_comensales'];
    $total_comensales_original = $post['total_comensales'];

    $datos_turno_horario = explode('_',$post['id_horario']);
    $id_restaurante_turno = $datos_turno_horario[0];
    $id_restaurante_turno_horario = $datos_turno_horario[1];

    $dias_cerrado = restaurantes_dias_cerrados($id_restaurante);

    if($dias_cerrado[$fecha]){
        return array('error_code' => 9,'mensaje' => 'No se puede realizar la reserva en la fecha seleccionada');
    }

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_reservas WHERE id_usuario LIKE "%'.$id_usuario.'%" AND id_restaurante = "'.$id_restaurante.'" AND fecha = "'.$fecha.'" AND hora = "'.$hora.'"';
    $consulta = $db->consulta($query);
    $consulta = $db->getAllArray($consulta);

    if($consulta){
        //Ya hay una reserva en el mismo dia y horario
        return array('error_code' => 1,'mensaje' => 'Ya hay una reserva asignada para ese día y hora');
    }else{

        //comprobamos que existe el turno y la hora
        $query = 'SELECT * FROM restaurantes_turnos_horarios WHERE id_restaurante = "'.$id_restaurante.'" AND id = "'.$id_restaurante_turno_horario.'" AND activo = "1"';
        $consulta = $db->consulta($query);
        $consulta = $db->getAllArray($consulta);

        if($consulta){

            $total_mesas_restaurantes = get_restaurante_mesas($id_restaurante);
            $total_mesas_usadas = get_restaurantes_reservas($id_restaurante,$fecha,$hora);
            $array_mesas_libres = null;
            $total_comensales_disponibles = 0;
            $array_numero_mesas = null;

            //Ahora_calculamos el total de mesas libres por cada tipo
            foreach($total_mesas_restaurantes as $comensales => $mesas){

                $mesas_libres = $mesas['cantidad'] - $total_mesas_usadas[$comensales]['cantidad'];
                $total_mesas_libres[$comensales]['tipo_mesa'] = $comensales;
                $total_mesas_libres[$comensales]['cantidad'] = $mesas_libres;

                $total_comensales_disponibles = $total_comensales_disponibles + ($comensales * $mesas_libres);

                $ml = 1;
                while ($ml <= $mesas_libres) {
                    $array_numero_mesas[] = $comensales;
                    $ml++;
                }

            }

            if($array_numero_mesas){
                natsort($array_numero_mesas);
                $array_numero_mesas = array_reverse($array_numero_mesas);
            }

//            print_r($total_mesas_restaurantes);
//            print_r($total_mesas_usadas);
//            print_r($total_mesas_libres);
//            print_r($array_numero_mesas);


            if($total_comensales_disponibles >= $total_comensales){

                //array con las posibles mesas, tenemos
                $array_posibles_mesas = calcular_mesas($array_numero_mesas, $total_comensales);

                //Si no nos da ninguana mesa, sumamos 1 porque puede que las mesas sean para par o impar

                if(count($array_posibles_mesas) == 0){
                    //Es el numero máximo de comensales que podemos sumar para que nos devuelvan las mesas
                    //Vamos sumando comensales hasta un máximo de la mesa más grande disponible
                    $nmcd = max($array_numero_mesas);
                    if($nmcd){
                        $nc = 0;
                        while ($nc <= $nmcd) {
                            $nc++;
                            $total_comensales++;
                            $array_posibles_mesas = calcular_mesas($array_numero_mesas, $total_comensales);
                            if($array_posibles_mesas){
                                break;
                            }
                        }
                    }
                }

                if(count($array_posibles_mesas) > 0){

                    $array_mesas_final = null;
                    foreach($array_posibles_mesas[0] as $mp){
                        $array_mesas_final[$mp]++;
                    }

                    if($array_mesas_final){

                        $numero_habitacion = $post['num_habitacion'];
                        $reserva_restaurante = insert_reserva_restaurante_datos($id_usuario,$id_restaurante,$fecha,$hora,$total_comensales_original,$array_mesas_final,$numero_habitacion,$post['nombre_usuario']);

                        if($reserva_restaurante){
                            return array('error_code' => 0, 'mensaje' => 'Reserva realizada correctamente');
                        }else{
                            //error de insertado de datos
                            return array('error_code' => 7, 'mensaje' => 'Error de insertado');
                        }

                    }else{
                        //error de insertado de datos
                        return array('error_code' => 7, 'mensaje' => 'Error de insertado');
                    }

                }else{
                    //No hay mesas disponibles
                    return array('error_code' => 6, 'mensaje' => 'No hay mesas disponibles');
                }

            }else{
                //No hay mesas suficientes para los comensales
                $restaurantes_disponibles = comprobar_disponibilidad_restaurante($total_comensales_original,$id_restaurante,$fecha,$hora_normal);
                return array('error_code' => 2, 'mensaje' => 'No hay disponibilidad, restaurantes sugeridos', 'restaurantes_disponibles' => $restaurantes_disponibles);
            }

        }else{
            // No existe el turno seleccionado
            return array('error_code' => 5, 'mensaje' => 'No existe el turno seleccionado');
        }

    }

    //return global
    return array('error_code' => 8, 'mensaje' => 'Return en blanco');

}

function get_restaurantes_reservas($id_restaurante,$fecha,$hora){

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_reservas AS rr INNER JOIN restaurantes_reservas_mesas AS rrm ON rr.id = rrm.id_reserva 
                  WHERE id_restaurante = "'.$id_restaurante.'" AND fecha = "'.$fecha.'" AND "'.$hora.'" AND rr.activo = 1';

    $consulta = $db->consulta($query);

    $resultado = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        /* Para no tener que hacer varias llamadas y tener los datos controlados */
        //$resultado[] = array('tipo_mesa' => $fila['id_tipo_mesa'], 'cantidad' => $fila['cantidad']);
        $resultado[$fila['id_tipo_mesa']]['tipo_mesa'] = $fila['id_tipo_mesa'];
        $resultado[$fila['id_tipo_mesa']]['cantidad'] += $fila['cantidad'];

    }

    return $resultado;


}

function get_restaurante_mesas($id_restaurante){

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_tiene_mesas 
                  WHERE id_restaurante = "'.$id_restaurante.'" AND activo = 1 ORDER BY numero_comensales DESC';

    $consulta = $db->consulta($query);

    $resultado = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        $resultado[$fila['numero_comensales']]['tipo_mesa'] = $fila['numero_comensales'];
        $resultado[$fila['numero_comensales']]['cantidad'] = $fila['cantidad'];

    }

    return $resultado;

}


function insert_reserva_restaurante_datos($id_usuario,$id_restaurante,$fecha,$hora,$total_comensales_original,$array_mesas_final,$numero_habitacion,$nombre_usuario){

    $db = new MySQL();

    $query = 'INSERT INTO restaurantes_reservas (id_usuario,nombre_usuario,num_habitacion,id_restaurante,numero_comensales,fecha,hora,activo) VALUES ("'.$id_usuario.'","'.$nombre_usuario.'","'.$numero_habitacion.'","'.$id_restaurante.'","'.$total_comensales_original.'","'.$fecha.'","'.$hora.'","1");';
    $consulta = $db->consulta($query);

    $id_reserva = $db->getLastInsertedId();

    if($id_reserva){

        $texto_values = null;
        foreach ($array_mesas_final as $key => $mesas){

            $texto_values .= '("'.$id_reserva.'","'.$key.'","'.$mesas.'"),';

        }

        if($texto_values){
            $texto_values = substr($texto_values, 0, -1);
            $query_productos = 'INSERT INTO restaurantes_reservas_mesas (id_reserva,id_tipo_mesa,cantidad) VALUES ';
            $query_productos .= $texto_values.';';

            $consulta = $db->consulta($query_productos);

            if($consulta){
                return true;
            }else{
                delete_restaurantes_reserva($id_reserva);
                return false;
            }

        }else{
            delete_restaurantes_reserva($id_reserva);
            return false;
        }

    }else{
        delete_restaurantes_reserva($id_reserva);
        return false;
    }


}


function delete_restaurantes_reserva($id_reserva){

    $db = new MySQL();
    $query = 'DELETE FROM restaurantes_reservas WHERE id = "'.$id_reserva.'"';
    $consulta = $db->consulta($query);

    $query = 'DELETE FROM restaurantes_reservas_mesas WHERE id_reserva = "'.$id_reserva.'"';
    $consulta = $db->consulta($query);

    return;

}


function comprobar_disponibilidad_restaurante($total_comensales_original,$id_restaurante,$fecha,$hora){

    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i');

    $db = new MySQL();

    $query = 'SELECT r.id_restaurante, r.dias_apertura, r.imagen, r.release, rti.nombre, rth.hora_inicio, rth.hora_fin
                    FROM restaurantes AS r
                    INNER JOIN restaurantes_tiene_idiomas AS rti ON r.id_restaurante = rti.id_restaurante
                    INNER JOIN restaurantes_turnos_horarios AS rth ON r.id_restaurante = rth.id_restaurante
                    WHERE r.activo = 1
                    AND rti.id_idioma = 1
                    AND rth.activo = 1
                    AND r.tipo = 1
                    AND r.reserva = 1
                    AND rth.hora_inicio = "'.$hora.'"';

    $consulta = $db->consulta($query);

    $restaurantes = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        if($fecha == $fecha_actual){
            $diferencia_minutos = diferencia_horas_reserva($hora_actual,$hora) . '<br>';
            if($fila['release'] > $diferencia_minutos){
                $restaurantes[] = $fila;
            }
        }else{
            $restaurantes[] = $fila;
        }

    }

    $restaurantes_disponibles = null;
    foreach($restaurantes as $key => $restaurante){

        if($id_restaurante != $restaurante['id_restaurante']){

            $dias_cerrado = restaurantes_dias_cerrados($restaurante['id_restaurante']);

            if($dias_cerrado[$fecha]){
                continue;
            }

            $numero_total_mesas_restaurante = get_restaurante_mesas($restaurante['id_restaurante']);
            $numero_mesas_comensales_reservadas = get_restaurantes_reservas($restaurante['id_restaurante'],$fecha,$hora);

            $numero_comensales_disponibles = 0;
            foreach($numero_total_mesas_restaurante as $key_mesa => $mesas){

                $numero_total_mesas_restaurante[$key_mesa]['cantidad'] -= $numero_mesas_comensales_reservadas[$key_mesa]['cantidad'];

                $numero_comensales_disponibles_mesa = $numero_total_mesas_restaurante[$key_mesa]['cantidad'] * $numero_total_mesas_restaurante[$key_mesa]['tipo_mesa'];
                $numero_comensales_disponibles = $numero_comensales_disponibles + $numero_comensales_disponibles_mesa;

            }

            if($total_comensales_original <= $numero_comensales_disponibles){
                $restaurantes_disponibles[$restaurante['id_restaurante']] = $restaurante;
            }


        }

    }

    //HAY QUE TENER EN CUENTA EL RELEASE Y LOS DIAS QUE ESTÁN CERRADOS, LOS NORMALES Y LOS EXTRAORDINARIOS
    //restaurantes_dias_cerrados id_restaurante, fecha

    return $restaurantes_disponibles;

}


function diferencia_horas_reserva($hora_actual,$hora){

    $apertura = strtotime($hora_actual);
    $cierre = strtotime($hora);
    return round(abs($apertura - $cierre) / 60,2);

}


function calcular_mesas($numbers, $target, $part=null){

    // we assume that an empty $part variable means this
    // is the top level call.
    $toplevel = false;
    if($part === null) {
        $toplevel = true;
        $part = array();
    }

    $s = 0;
    foreach($part as $x)
    {
        $s = $s + $x;
    }

    // we have found a match!
    if($s == $target)
    {
        sort($part); // ensure the numbers are always sorted
        return array(implode('|', $part));
    }

    // gone too far, break off
    if($s >= $target)
    {
        return null;
    }

    $matches = array();
    $totalNumbers = count($numbers);

    for($i=0; $i < $totalNumbers; $i++)
    {
        $remaining = array();
        $n = $numbers[$i];

        for($j = $i+1; $j < $totalNumbers; $j++)
        {
            $remaining[] = $numbers[$j];
        }

        $part_rec = $part;
        $part_rec[] = $n;

        $result = calcular_mesas($remaining, $target, $part_rec);
        if($result)
        {
            $matches = array_merge($matches, $result);
        }
    }

    if(!$toplevel)
    {
        return $matches;
    }

    // this is the top level function call: we have to
    // prepare the final result value by stripping any
    // duplicate results.
    $matches = array_unique($matches);
    $result = array();
    foreach($matches as $entry)
    {
        $result[] = explode('|', $entry);
    }

    return $result;

}

function restaurantes_dias_cerrados($id_restaurante){

    $dia_actual = date('Y-m-d');

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_dias_cerrados WHERE id_restaurante = "'.$id_restaurante.'" AND fecha >= "'.$dia_actual.'"';

    $consulta = $db->consulta($query);

    $fechas = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        $fechas[$fila['fecha']] = $fila['fecha'];

    }

    return $fechas;


}


function restaurantes_dias_cerrados_new($id_restaurante){

    $dia_actual = date('Y-m-d');

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_dias_cerrados WHERE id_restaurante = "'.$id_restaurante.'" AND fecha >= "'.$dia_actual.'"';

    $consulta = $db->consulta($query);

    $fechas = null;
    while ($fila = mysql_fetch_assoc($consulta)) {

        $fechas[] = $fila;

    }

    return $fechas;


}

function get_numero_dias_disponibles($id_restaurante = null){

    $db = new MySQL();

    if($id_restaurante){

        $query = 'SELECT numero_dias FROM restaurantes WHERE id_restaurante = "'.$id_restaurante.'"';
        $consulta = $db->consulta($query);

        $datos = $db->getAllArray($consulta);
        return $datos[0]['numero_dias'];

    }else{
        return false;
    }


}

function get_dias_clausura_restaurantes($id_restaurante = null){

    $db = new MySQL();

    if($id_restaurante){

        $query = 'SELECT fecha FROM restaurantes_dias_cerrados WHERE id_restaurante = "'.$id_restaurante.'" AND fecha >= CURDATE()';
        $consulta = $db->consulta($query);

        $fechas = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $fechas[] = $fila['fecha'];
        }

        return $fechas;

    }else{
        return false;
    }


}

function get_restaurantes_restricciones($tipo = null){

    if($tipo){

        $db = new MySQL();

        $query = 'SELECT * FROM restaurantes_restricciones WHERE tipo_restriccion = "'.$tipo.'" AND activo = 1';
        $consulta = $db->consulta($query);

        $fechas = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $fechas[$fila['id_restaurante']] = $fila;
        }

        return $fechas;


    }else{
        return false;
    }

}

function get_reservas_gratuitas($id_reserva = null, $ids_restaurantes_restricciones = null){

    if($id_reserva && $ids_restaurantes_restricciones){

        $db = new MySQL();

        $query = 'SELECT * FROM restaurantes_reservas 
        WHERE id_restaurante IN ('.$ids_restaurantes_restricciones.') AND id_usuario LIKE "%'.$id_reserva.'%" AND activo = 1 AND gratuito = 2';
        $consulta = $db->consulta($query);

        $fechas = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $fechas[] = $fila;
        }

        return $fechas;


    }else{
        return false;
    }

}

function get_dias_reservas($id_restaurante = null){

//    $id_reserva = $_SESSION['id_reserva'];
    $id_reserva = $_COOKIE['id_reserva'];
    $array_datos_usuarios = get_datos_reserva($id_reserva);

    if($id_restaurante){

        $numero_dias = get_numero_dias_disponibles($id_restaurante);
        $dias_cerrado = get_dias_clausura_restaurantes($id_restaurante);

        $fecha_inicial = date('Y-m-d');

        if ($numero_dias) {

            $array_fechas = null;

            $dia_inicial = 0;
            while ($dia_inicial < $numero_dias) {

                if ($dia_inicial != 0) {

                    //echo $fecha_inicial . ' más ' . $dia_inicial . ' días <br>';

                    $fecha_real = date("Y-m-d", strtotime($fecha_inicial . "+ 1 days"));
                    $fecha_inicial = $fecha_real;
                }

                if (!in_array($fecha_inicial, $dias_cerrado)) {

                    if(($fecha_inicial >= $array_datos_usuarios['checkin']) && ($fecha_inicial <= $array_datos_usuarios['checkout'])){

                        $fecha_formateada = date("d-m-Y", strtotime($fecha_inicial));

                        $array_fechas[$fecha_inicial] = [
                            'fecha' => $fecha_inicial,
                            'fecha_formateada' => $fecha_formateada
                        ];

                    }

                }

                $dia_inicial++;
            }

            return $array_fechas;
        } else {
            return false;
        }

    }else{
        return false;
    }

    //$fecha_real = date("Y-m-d", strtotime($fecha_inicial."+ ".($numero_dia_semana - 1)." days"));

}

function get_turnos_franjas_restaurantes($id_restaurante = null, $fecha)
{

    if ($id_restaurante && $fecha) {

        $datos_cupo = get_cupo_restaurantes($id_restaurante, $fecha);
        $datos_clausura = get_franjas_cerradas($id_restaurante, $fecha);

        $id_idioma = $_SESSION['idioma'];

        $db = new MySQL();

        $query = "SELECT t1.id AS id_restaurante_turno, t2.nombre AS nombre_turno, t1.icono, t3.id AS id_restaurante_turno_horario,
                    t4.hora_inicio, t4.hora_fin, t4.release, t4.apertura  
                    FROM restaurantes_turnos AS t1
                    INNER JOIN restaurantes_turnos_tiene_idiomas AS t2 ON t1.id = t2.id_restaurante_turno
                    INNER JOIN restaurantes_turnos_horarios AS t3 ON t2.id_restaurante_turno = t3.id_restaurante_turno
                    INNER JOIN restaurantes_turnos_horarios_horas AS t4 ON t3.id = t4.id_restaurante_turno_horario
                    WHERE t2.id_idioma = '" . $id_idioma . "'
                    AND t3.id_restaurante = '" . $id_restaurante . "'
                    AND t3.activo = 1
                    AND t4.activo = 1
                    AND t4.fecha_inicio <= '" . $fecha . "'
                        AND (
                        t4.fecha_fin = NULL 
                        OR t4.fecha_fin IS NULL
                        OR t4.fecha_fin >= '" . $fecha . "'
                        ) ORDER BY t3.id_restaurante_turno ASC, t4.fecha_inicio ASC, t4.hora_inicio ASC, t4.hora_fin ASC";


        //Unir con el cupo y con las reservas

        $consulta = $db->consulta($query);

        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {

            // echo "<pre>";
            // print_r($fila);


            if ($datos_clausura[$fila['id_restaurante_turno_horario']]) {
                continue;
            }

            if (!$datos_cupo[$fila['id_restaurante_turno_horario']]) {
                continue;
            }

            if ($fecha == date('Y-m-d') && ($fila['release'] == '00:00' || $fila['release'] == '24')) {
                continue;
            }

            if ($fecha == date('Y-m-d')) {


                $hora_inicio_release = $fila['hora_inicio'];


                if (strlen($fila['release']) <= 4 && $fila['release'] != '0') {

                    if ($fila['release'] > 0) {

                        $hora_inicio_release = restar_horas_restaurantes($fila['hora_inicio'], $fila['release']);
                    }
                } else {

                    if (strlen($fila['release']) > 4) {

                        if ($fila['release'] < date("H:i")) {
                            continue;
                        }
                    }
                }

                if ($hora_inicio_release < date('H:i')) {
                    continue;
                }
            }

            if($fila['apertura']){

                $id_dia_busqueda = date("N", strtotime($fecha)) - 1;
                if($fila['apertura'][$id_dia_busqueda] == 0){
                    continue;
                }
                
            }

            if (!$datos[$fila['id_restaurante_turno']]) {
                $datos[$fila['id_restaurante_turno']] = [
                    'id_restaurante_turno' => $fila['id_restaurante_turno'],
                    'icono' => $fila['icono'],
                    'nombre_turno' => $fila['nombre_turno'],
                    'horarios_turno' => [
                        $fila['id_restaurante_turno_horario'] => [
                            'id_restaurante_turno_horario' => $fila['id_restaurante_turno_horario'],
                            'hora_inicio' => $fila['hora_inicio'],
                            'hora_fin' => $fila['hora_fin'],
                            'release' => $fila['release'],
                            'apertura' => $fila['apertura'],
                            'cupo' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo'],
                            'cupo_actual' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_actual'],
                            'cupo_restante' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_restante'],
                            'reservas_total' => $datos_cupo[$fila['id_restaurante_turno_horario']]['reservas_total']
                        ]
                    ],
                ];
            } else {

                $datos[$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']] = [
                    'id_restaurante_turno_horario' => $fila['id_restaurante_turno_horario'],
                    'hora_inicio' => $fila['hora_inicio'],
                    'hora_fin' => $fila['hora_fin'],
                    'release' => $fila['release'],
                    'apertura' => $fila['apertura'],
                    'cupo' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo'],
                    'cupo_actual' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_actual'],
                    'cupo_restante' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_restante'],
                    'reservas_total' => $datos_cupo[$fila['id_restaurante_turno_horario']]['reservas_total']
                ];
            }
        }

        return $datos;

        //Array
        //(
        //    [1] => Array
        //        (
        //            [id_restaurante_turno] => 1
        //            [activo_turno] => 1
        //            [icono] => horarior.svg
        //            [nombre_turno] => Desayuno
        //            [horarios_turno] => Array
        //                (
        //                    [1] => Array
        //                        (
        //                            [id_restaurante_turno_horario] => 1
        //                            [hora_inicio] => 07:00
        //                            [hora_fin] => 11:00
        //                            [activo_turno_horario] => 1
        //                        )
        //
        //                )
        //
        //        )
        //
        //    [2] => Array
        //        (
        //            [id_restaurante_turno] => 2
        //            [activo_turno] => 1
        //            [icono] => horarior.svg
        //            [nombre_turno] => Almuerzo
        //            [horarios_turno] => Array
        //                (
        //                    [2] => Array
        //                        (
        //                            [id_restaurante_turno_horario] => 2
        //                            [hora_inicio] => 13:00
        //                            [hora_fin] => 15:30
        //                            [activo_turno_horario] => 1
        //                        )
        //
        //                )
        //
        //        )
        //
        //    [3] => Array
        //        (
        //            [id_restaurante_turno] => 3
        //            [activo_turno] => 1
        //            [icono] => horarior.svg
        //            [nombre_turno] => Cena
        //            [horarios_turno] => Array
        //                (
        //                    [3] => Array
        //                        (
        //                            [id_restaurante_turno_horario] => 3
        //                            [hora_inicio] => 18:00
        //                            [hora_fin] => 19:00
        //                            [activo_turno_horario] => 1
        //                        )
        //
        //                    [4] => Array
        //                        (
        //                            [id_restaurante_turno_horario] => 4
        //                            [hora_inicio] => 19:00
        //                            [hora_fin] => 21:30
        //                            [activo_turno_horario] => 1
        //                        )
        //
        //                )
        //
        //        )
        //
        //)

    } else {
        return false;
    }
}

function get_datos_franja_rest($id_franja_turno){

    $db = new MySQL();

    $query = "SELECT * FROM restaurantes_turnos_horarios WHERE id = '".$id_franja_turno."'";

    $consulta = $db->consulta($query);

    $datos = null;
    while ($fila = mysql_fetch_assoc($consulta)) {
        $datos[] = $fila;
    }

    return $datos[0];

}

// function get_turnos_franjas_restaurantes($id_restaurante = null,$fecha){

//     if($id_restaurante && $fecha){

//         $datos_cupo = get_cupo_restaurantes($id_restaurante,$fecha);
//         $datos_clausura = get_franjas_cerradas($id_restaurante,$fecha);

//         $id_idioma = $_SESSION['idioma'];

//         $db = new MySQL();

//         $query = "SELECT t1.id AS id_restaurante_turno, t2.nombre AS nombre_turno, t1.icono, t3.id AS id_restaurante_turno_horario,
// t4.hora_inicio, t4.hora_fin, t4.release FROM restaurantes_turnos AS t1
// INNER JOIN restaurantes_turnos_tiene_idiomas AS t2 ON t1.id = t2.id_restaurante_turno
// INNER JOIN restaurantes_turnos_horarios AS t3 ON t2.id_restaurante_turno = t3.id_restaurante_turno
// INNER JOIN restaurantes_turnos_horarios_horas AS t4 ON t3.id = t4.id_restaurante_turno_horario
// WHERE t2.id_idioma = '".$id_idioma."'
// AND t3.id_restaurante = '".$id_restaurante."'
// AND t3.activo = 1
// AND t4.activo = 1
// AND t4.fecha_inicio <= '".$fecha."'
//     AND (
//     t4.fecha_fin = ''
//     OR t4.fecha_fin IS NULL
//     OR t4.fecha_fin >= '".$fecha."'
//     ) ORDER BY t3.id ASC, t4.fecha_inicio ASC";


//         //Unir con el cupo y con las reservas

//         $consulta = $db->consulta($query);

//         $datos = null;
//         while ($fila = mysql_fetch_assoc($consulta)) {

//             if($datos_clausura[$fila['id_restaurante_turno_horario']]){
//                 continue;
//             }

//             if(!$datos_cupo[$fila['id_restaurante_turno_horario']]){
//                 continue;
//             }

//             if($fecha == date('Y-m-d')){

//                 if($fila['release'] > 0){
//                     $hora_inicio_release = restar_horas_restaurantes($fila['hora_inicio'],$fila['release']);
//                 }else{
//                     $hora_inicio_release = $fila['hora_inicio'];
//                 }

//                 if($hora_inicio_release < date('H:i')){
//                     continue;
//                 }

//             }

//             if(!$datos[$fila['id_restaurante_turno']]){
//                 $datos[$fila['id_restaurante_turno']] = [
//                     'id_restaurante_turno' => $fila['id_restaurante_turno'],
//                     'icono' => $fila['icono'],
//                     'nombre_turno' => $fila['nombre_turno'],
//                     'horarios_turno' => [
//                         $fila['id_restaurante_turno_horario'] => [
//                             'id_restaurante_turno_horario' => $fila['id_restaurante_turno_horario'],
//                             'hora_inicio' => $fila['hora_inicio'],
//                             'hora_fin' => $fila['hora_fin'],
//                             'release' => $fila['release'],
//                             'cupo' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo'],
//                             'cupo_actual' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_actual'],
//                             'cupo_restante' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_restante']
//                         ]
//                     ],
//                 ];
//             }else{

//                 $datos[$fila['id_restaurante_turno']]['horarios_turno'][$fila['id_restaurante_turno_horario']] = [
//                     'id_restaurante_turno_horario' => $fila['id_restaurante_turno_horario'],
//                     'hora_inicio' => $fila['hora_inicio'],
//                     'hora_fin' => $fila['hora_fin'],
//                     'release' => $fila['release'],
//                     'cupo' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo'],
//                     'cupo_actual' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_actual'],
//                     'cupo_restante' => $datos_cupo[$fila['id_restaurante_turno_horario']]['cupo_restante']
//                 ];

//             }


//         }

//         return $datos;

//         //Array
//         //(
//         //    [1] => Array
//         //        (
//         //            [id_restaurante_turno] => 1
//         //            [activo_turno] => 1
//         //            [icono] => horarior.svg
//         //            [nombre_turno] => Desayuno
//         //            [horarios_turno] => Array
//         //                (
//         //                    [1] => Array
//         //                        (
//         //                            [id_restaurante_turno_horario] => 1
//         //                            [hora_inicio] => 07:00
//         //                            [hora_fin] => 11:00
//         //                            [activo_turno_horario] => 1
//         //                        )
//         //
//         //                )
//         //
//         //        )
//         //
//         //    [2] => Array
//         //        (
//         //            [id_restaurante_turno] => 2
//         //            [activo_turno] => 1
//         //            [icono] => horarior.svg
//         //            [nombre_turno] => Almuerzo
//         //            [horarios_turno] => Array
//         //                (
//         //                    [2] => Array
//         //                        (
//         //                            [id_restaurante_turno_horario] => 2
//         //                            [hora_inicio] => 13:00
//         //                            [hora_fin] => 15:30
//         //                            [activo_turno_horario] => 1
//         //                        )
//         //
//         //                )
//         //
//         //        )
//         //
//         //    [3] => Array
//         //        (
//         //            [id_restaurante_turno] => 3
//         //            [activo_turno] => 1
//         //            [icono] => horarior.svg
//         //            [nombre_turno] => Cena
//         //            [horarios_turno] => Array
//         //                (
//         //                    [3] => Array
//         //                        (
//         //                            [id_restaurante_turno_horario] => 3
//         //                            [hora_inicio] => 18:00
//         //                            [hora_fin] => 19:00
//         //                            [activo_turno_horario] => 1
//         //                        )
//         //
//         //                    [4] => Array
//         //                        (
//         //                            [id_restaurante_turno_horario] => 4
//         //                            [hora_inicio] => 19:00
//         //                            [hora_fin] => 21:30
//         //                            [activo_turno_horario] => 1
//         //                        )
//         //
//         //                )
//         //
//         //        )
//         //
//         //)

//     }else{
//         return false;
//     }

// }


function get_cupo_restaurantes($id_restaurante, $fecha)
{

    if ($id_restaurante && $fecha) {

        $db = new MySQL();

        $query = "SELECT t1.id_restaurante, t1.id_franja_turno, t1.cupo, t1.fecha_inicio, t1.fecha_fin, 
 (SELECT SUM(numero_comensales) FROM restaurantes_reservas AS t2 
 WHERE t2.id_restaurante = '" . $id_restaurante . "' AND t2.fecha = '" . $fecha . "' AND t2.activo = 1 AND t2.id_franja_turno = t1.id_franja_turno) AS cupo_actual, 
 (SELECT COUNT(numero_comensales) FROM restaurantes_reservas AS t2 
 WHERE t2.id_restaurante = '" . $id_restaurante . "' AND t2.fecha = '" . $fecha . "' AND t2.activo = 1 AND t2.id_franja_turno = t1.id_franja_turno) AS reservas_total 
 FROM restaurantes_tiene_cupo AS t1 
WHERE t1.id_restaurante = '" . $id_restaurante . "' 
AND t1.activo = 1
AND t1.fecha_inicio <= '" . $fecha . "'
        AND (
        t1.fecha_fin = NULL 
        OR t1.fecha_fin IS NULL
        OR t1.fecha_fin >= '" . $fecha . "'
) ORDER BY t1.id_franja_turno ASC, t1.fecha_inicio ASC";

        $consulta = $db->consulta($query);

        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {

            if (!$fila['cupo_actual']) {
                $fila['cupo_actual'] = 0;
            }

            if (!$fila['reservas_total']) {
                $fila['reservas_total'] = 0;
            }

            $fila['cupo_restante'] = $fila['cupo'] - $fila['cupo_actual'];

            if ($fila['cupo_restante'] < 0) {
                $fila['cupo_restante'] = 0;
            }

            $datos[$fila['id_franja_turno']] = $fila;
        }

        return $datos;
    } else {
        return false;
    }
}


// function get_cupo_restaurantes($id_restaurante,$fecha){

//     if($id_restaurante && $fecha){

//         $db = new MySQL();

//         $query = "SELECT t1.id_restaurante, t1.id_franja_turno, t1.cupo, t1.fecha_inicio, t1.fecha_fin, 
//  (SELECT SUM(numero_comensales) FROM restaurantes_reservas AS t2 
//  WHERE t2.id_restaurante = '".$id_restaurante."' AND t2.fecha = '".$fecha."' AND t2.activo = 1 AND t2.id_franja_turno = t1.id_franja_turno) AS cupo_actual 
//  FROM restaurantes_tiene_cupo AS t1 
// WHERE t1.id_restaurante = '".$id_restaurante ."' 
// AND t1.activo = 1
// AND t1.fecha_inicio <= '".$fecha."'
//         AND (
//         t1.fecha_fin = ''
//         OR t1.fecha_fin IS NULL
//         OR t1.fecha_fin >= '".$fecha."'
// ) ORDER BY t1.id_franja_turno ASC, t1.fecha_inicio ASC";

//         $consulta = $db->consulta($query);

//         $datos = null;
//         while ($fila = mysql_fetch_assoc($consulta)) {

//             if(!$fila['cupo_actual']){
//                 $fila['cupo_actual'] = 0;
//             }

//             $fila['cupo_restante'] = $fila['cupo'] - $fila['cupo_actual'];

//             if($fila['cupo_restante'] < 0){
//                 $fila['cupo_restante'] = 0;
//             }

//             $datos[$fila['id_franja_turno']] = $fila;
//         }

//         return $datos;

//     }else{
//         return false;
//     }

// }

function restar_horas_restaurantes($hora,$tiempo){

    $fecha = date('Y-m-d');
    $fecha_hora = $fecha . ' ' . $hora;
    $nuevafecha = strtotime ( '-'.$tiempo.' minute' , strtotime ($fecha_hora)) ;
    $nuevafecha = date('H:i' , $nuevafecha);

    return $nuevafecha;

}

function get_franjas_cerradas($id_restaurante,$fecha){

    if($id_restaurante && $fecha){

        $db = new MySQL();

        $query = "SELECT * FROM restaurantes_turnos_franjas_clausura 
WHERE id_restaurante = '".$id_restaurante."' AND fecha = '".$fecha."'";

        $consulta = $db->consulta($query);

        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $datos[$fila['id_franja_turno']] = $fila;
        }

        return $datos;

    }else{
        return false;
    }

}

function get_datos_reserva($id_reserva){

    if($id_reserva){

        $fecha_actual = date('Y-m-d');

        $db = new MySQL();

        $query = "SELECT * FROM reserva_usuarios WHERE id_reserva = '".$id_reserva."'";

        $consulta = $db->consulta($query);

        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            if(($fila['checkin'] <= $fecha_actual) && ($fila['checkout'] >= $fecha_actual)){
                $datos[] = $fila;
            }
        }

        return $datos[0];

    }else{
        return false;
    }

}

 function get_reservas_restaurates($id_restaurante,$fecha){

//    $id_reserva = $_SESSION['id_reserva'];
    $id_reserva = $_COOKIE['id_reserva'];

    if($id_restaurante && $fecha && $id_reserva){

        $db = new MySQL();

        $query = "SELECT t1.*, t2.id_restaurante_turno FROM restaurantes_reservas AS t1 
INNER JOIN restaurantes_turnos_horarios AS t2 ON t1.id_franja_turno = t2.id
WHERE t1.id_usuario LIKE '%".$id_reserva."%' 
AND t1.id_restaurante = '".$id_restaurante."' 
AND t1.fecha = '".$fecha."'";

        $consulta = $db->consulta($query);

        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $datos[$fila['id_franja_turno']] = $fila;
        }

        return $datos;

    }else{
        return false;
    }

}

function get_reservas_restaurates_franjas($id_restaurante,$fecha,$id_reserva){
    
        if($id_restaurante && $fecha && $id_reserva){
    
            $db = new MySQL();
    
            $query = "SELECT t1.*, t2.id_restaurante_turno FROM restaurantes_reservas AS t1 
    INNER JOIN restaurantes_turnos_horarios AS t2 ON t1.id_franja_turno = t2.id
    WHERE t1.id_usuario LIKE '%".$id_reserva."%' 
    AND t1.id_restaurante = '".$id_restaurante."' 
    AND t1.fecha = '".$fecha."'";
    
            $consulta = $db->consulta($query);
    
            $datos = null;
            while ($fila = mysql_fetch_assoc($consulta)) {
                $datos[$fila['id_restaurante_turno']] = $fila['id_restaurante_turno'];
            }
    
            return $datos;
    
        }else{
            return false;
        }
    
    }


function get_insert_reserva_restaurante($query = null){

    if($query){

        $db = new MySQL();

        $consulta = $db->consulta($query);

        if($consulta){
            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }
}

function check_reserva_restaurante($array_datos_usuarios,$id_reserva){

    if($array_datos_usuarios){

        $db = new MySQL();

        $query = 'SELECT * FROM restaurantes_reservas WHERE id_usuario LIKE "%'.$array_datos_usuarios['id_reserva'].'%" AND id = "'.$id_reserva.'"';

        $consulta = $db->consulta($query);

        return $db->getAllArray($consulta)[0];

    }else{
        return false;
    }

}

function delete_reserva_restaurante($id_reserva){

    if($id_reserva){

        $db = new MySQL();

        $query = 'DELETE FROM restaurantes_reservas WHERE id = "'.$id_reserva.'"';

        $consulta = $db->consulta($query);

        if($consulta){
            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }

}

function get_reservas_restaurates_usuario($id_reserva){

    $idioma = $_SESSION['idioma'];

    if($id_reserva){

        $db = new MySQL();

        $query = 'SELECT t1.*, t3.nombre, t4.nombre as nombre_restaurante FROM restaurantes_reservas as t1, restaurantes_turnos_horarios as t2, restaurantes_turnos_tiene_idiomas as t3, restaurantes_tiene_idiomas as t4 
                  WHERE t1.id_usuario LIKE "%'.$id_reserva.'%" 
                  AND t1.activo = 1 
                  AND t4.id_restaurante = t1.id_restaurante 
                  AND t4.id_idioma = "'.$idioma.'" 
                  AND t1.id_franja_turno = t2.id
                  AND t2.id_restaurante_turno = t3.id_restaurante_turno
                  AND t3.id_idioma = "'.$idioma.'"
                  AND t1.fecha >= CURDATE()
                  ORDER BY t1.fecha, t1.hora_inicio 
                  ';

        $consulta = $db->consulta($query);

        return $db->getAllArray($consulta);

    }else{
        return false;
    }

}

function get_restaurante_restricciones($id_restaurante){

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_restricciones WHERE id_restaurante = "'.$id_restaurante.'" AND activo = 1';

    $consulta = $db->consulta($query);

    return $db->getAllArray($consulta)[0];

}

function get_total_reservas_restaurantes($id_restaurante,$id_reserva){

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_reservas 
    WHERE id_restaurante = "'.$id_restaurante.'" AND id_usuario LIKE "%'.$id_reserva.'%" AND activo = 1';

    $consulta = $db->consulta($query);

    return $db->getAllArray($consulta);

}

function get_reserva_detalle_restaurante($id_reserva){

    $db = new MySQL();

    $query = 'SELECT * FROM restaurantes_reservas 
    WHERE id = "'.$id_reserva.'"';

    $consulta = $db->consulta($query);

    return $db->getAllArray($consulta)[0];

}

function get_diferencia_dias_fechas($fecha_inicial,$fecha_final){

    if($fecha_inicial && $fecha_final){
        $fecha1= new DateTime($fecha_final);
        $fecha2= new DateTime($fecha_inicial);
        $diff = $fecha1->diff($fecha2);
    
        return $diff->days;
    }else{
        return false;
    }

}

function actualiza_datos_reserva_new($id_reserva,$numero_habitacion,$checkout_general,$checkin_general,$numero_huespedes,$regimen_general,$adults,$childs,$babies)
{

    $db = new MySQL();

    $query = "REPLACE INTO reserva_usuarios (id_reserva, numero_habitacion,checkout,checkin,numero_huespedes,adultos,ninos,bebes,regimen) 
              VALUES('$id_reserva','$numero_habitacion','$checkout_general','$checkin_general','$numero_huespedes','".$adults."','".$childs."','".$babies."','$regimen_general')";

    //print_r($query);

    $result = $db->consulta($query);

    return $result;
}

function get_franjas_reservas_usuarios($id_reserva){

    if($id_reserva){

        $db = new MySQL();

        $query = 'SELECT t2.id_restaurante_turno, t1.fecha  
        FROM restaurantes_reservas AS t1 INNER JOIN restaurantes_turnos_horarios AS t2 ON t1.id_franja_turno = t2.id 
        WHERE t1.id_usuario LIKE "%'.$id_reserva.'%"';
    
        $consulta = $db->consulta($query);
    
        $datos = null;
        while ($fila = mysql_fetch_assoc($consulta)) {
            $datos[$fila['fecha']][$fila['id_restaurante_turno']] = $fila;
        }
    
        return $datos;

    }else{
        return false;
    }

}

function update_reserva_restaurante($id_reserva,$id_reserva_restaurante,$hora_inicio,$hora_fin){

    if($id_reserva && $id_reserva_restaurante && $hora_inicio && $hora_fin){

        $db = new MySQL();

        $query = 'UPDATE restaurantes_reservas SET id_franja_turno = "'.$id_reserva_restaurante.'", hora_inicio = "'.$hora_inicio.'", hora_fin = "'.$hora_fin.'" WHERE id = "'.$id_reserva.'"';
    
        $consulta = $db->consulta($query);

        if($consulta){
            return 'ok';
        }else{
            return false;
        }

    }else{
        return false;
    }

}

?>