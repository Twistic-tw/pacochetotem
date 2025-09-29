<?php


//Obtiene el JSON de las mareas de un centro

function get_mareas($id_centro){
    $db = new MySQL_comun();

    $query = "SELECT * FROM `mareas_totem`
        where `id_centro` = '$id_centro'";
                        
 $result = $db->consulta($query);

    $datos = array();
    while ($row = $db->fetch_assoc($result)) 
    {
        $datos[] = $row;
    }
    return $datos;
}


// function getFechaFormato($dt)
// {

//     //echo $dt;

//     switch ($_SESSION['idioma']) {
//         case 1:
//             setlocale(LC_ALL, "es_ES.UTF-8");
//             define("CHARSET", "UTF-8");

//             setlocale(LC_TIME, 'spanish');
//             $fecha = strftime("%A %d %B %Y", $dt );
//            // $fecha = date('l jS \of F Y', $dt );
//             break;

//         case 2:
//             setlocale(LC_ALL, "en_EN.UTF-8");
//             define("CHARSET", "UTF-8");

//             setlocale(LC_TIME, "english");
//             $fecha = strftime("%A %B %d, %Y", $dt );
//             break;

//         case 3:
//             setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
//             define("CHARSET", "UTF-8");

//             setlocale(LC_TIME, 'german');
//             $fecha = strftime("%A %d. %B %Y", $dt );
//             break;

//         case 4:
//             setlocale(LC_ALL, "fr_FR.UTF-8");
//             define("CHARSET", "UTF-8");

//             setlocale(LC_TIME, 'french');
//             $fecha = strftime("%A %d %B %Y", $dt );
//             // $fecha = date('l jS \of F Y', $dt );
//             break;

//         default:
//             setlocale(LC_ALL, "es_ES.UTF-8");
//             define("CHARSET", "UTF-8");

//             setlocale(LC_TIME, 'spanish');
//             $fecha = utf8_encode(strftime("%A %d de %B %Y", time($dt)));
//             break;
//     }

//    // print_r($fecha);
//     return $fecha;
// }



function getFechaFormato($dt)
{

    //echo $dt;

    switch ($_SESSION['idioma']) {
        case 1:

            $array_dias_fecha_local = [
                '1' => 'Lunes',
                '2' => 'Martes',
                '3' => 'Miércoles',
                '4' => 'Jueves',
                '5' => 'Viernes',
                '6' => 'Sábado',
                '7' => 'Domingo'
            ];

            $array_meses_fecha_local = [
                '1' => 'Enero',
                '2' => 'Febrero',
                '3' => 'Marzo',
                '4' => 'Abril',
                '5' => 'Mayo',
                '6' => 'Junio',
                '7' => 'Julio',
                '8' => 'Agosto',
                '9' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre',
            ];

            //setlocale(LC_ALL, "es_ES.UTF-8");
            //define("CHARSET", "UTF-8");

            //setlocale(LC_TIME, 'spanish');
            //$fecha = strftime("%A %d %B %Y", $dt );
            // $fecha = date('l jS \of F Y', $dt );
            break;

        case 2:

            $array_dias_fecha_local = [
                '1' => 'Monday',
                '2' => 'Tuesday',
                '3' => 'Wednesday',
                '4' => 'Thursday',
                '5' => 'Friday',
                '6' => 'Saturday',
                '7' => 'Sunday'
            ];

            $array_meses_fecha_local = [
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December',
            ];

            //setlocale(LC_ALL, "en_EN.UTF-8");
            //define("CHARSET", "UTF-8");

            //setlocale(LC_TIME, "english");
            //$fecha = strftime("%A %B %d, %Y", $dt );
            break;

        case 3:

            $array_dias_fecha_local = [
                '1' => 'Montag',
                '2' => 'Dienstag',
                '3' => 'Mittwoch',
                '4' => 'Donnerstag',
                '5' => 'Freitag',
                '6' => 'Samstag',
                '7' => 'Sonntag'
            ];

            $array_meses_fecha_local = [
                '1' => 'Januar',
                '2' => 'Februar',
                '3' => 'März',
                '4' => 'April',
                '5' => 'Mai',
                '6' => 'Juni',
                '7' => 'Juli',
                '8' => 'August',
                '9' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Dezember'
            ];

            //setlocale(LC_ALL, 'de_DE@euro', 'de_DE.UTF-8', 'de', 'ge');
            //define("CHARSET", "UTF-8");

            //setlocale(LC_TIME, 'german');
            //$fecha = strftime("%A %d. %B %Y", $dt );
            break;

        case 4:

            $array_dias_fecha_local = [
                '1' => 'Segunda-feira',
                '2' => 'Terça-feira',
                '3' => 'Quarta-feira',
                '4' => 'Quinta-feira',
                '5' => 'Sexta-feira',
                '6' => 'Domingo',
                '7' => 'Domingo'
            ];

            $array_meses_fecha_local = [
                '1' => 'Janeiro',
                '2' => 'Fevereiro',
                '3' => 'Março',
                '4' => 'Abril',
                '5' => 'Maio',
                '6' => 'Junho',
                '7' => 'Julho',
                '8' => 'Agosto',
                '9' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro'
            ];

            //setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            //define("CHARSET", "UTF-8");

            //setlocale(LC_TIME, "portuguese");
            //$fecha = strftime("%A %B %d, %Y", $dt );
            break;


        default:

            $array_dias_fecha_local = [
                '1' => 'Lunes',
                '2' => 'Martes',
                '3' => 'Miércoles',
                '4' => 'Jueves',
                '5' => 'Viernes',
                '6' => 'Sábado',
                '7' => 'Domingo'
            ];

            $array_meses_fecha_local = [
                '1' => 'Enero',
                '2' => 'Febrero',
                '3' => 'Marzo',
                '4' => 'Abril',
                '5' => 'Mayo',
                '6' => 'Junio',
                '7' => 'Julio',
                '8' => 'Agosto',
                '9' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre',
            ];

            //setlocale(LC_ALL, "es_ES.UTF-8");
            //define("CHARSET", "UTF-8");

            //setlocale(LC_TIME, 'spanish');
            //$fecha = utf8_encode(strftime("%A %d de %B %Y", time($dt)));
            break;

    }

    //strftime("%A %B %d, %Y", $dt );
    //N dis semana
    //n meses

    $dia_semana_fecha = $array_dias_fecha_local[date("N",$dt)];
    $mes_fecha_n = $array_meses_fecha_local[date("n",$dt)];
    $dia_mes_fecha = date("d",$dt);
    $year_fecha = date("Y",$dt);

    $fecha = $dia_semana_fecha . ' ' . $dia_mes_fecha . ' ' . $mes_fecha_n . ' ' . $year_fecha;

    //miércoles 16 marzo 2022
   // print_r($fecha);
   
    return $fecha;
}