<?php

function get_colores_categorias()
{

    $db = new MySQL();
    $datos =  [];

    $queryString = "SELECT *
                    FROM bd_feeltourist_riu_comun.eventos_categorias
                    WHERE activo = 1";

    $query = $db->consulta($queryString);

    while ($fila = $db->fetch_assoc($query)) {
        $datos[mb_strtolower($fila['identificador'])] = $fila['color'];
    }

    return $datos;
}


function get_categorias($dias = 1)
{

    $id_idioma = $_SESSION['idioma'];

    $fecha = date('Y-m-d');
    $fecha_inicial = date("Y-m-d", strtotime($fecha));

    if ($dias > 1) {
        $dias--;
        $fecha_final = date("Y-m-d", (strtotime($fecha) + ($dias * 24 * 60 * 60)));
    } else {
        $fecha_final = date("Y-m-d", strtotime($fecha));
    }

    $db = new MySQL();

    $queryString = "SELECT t1.id_cat, t1.identificador, t1.color, t6.nombre as title, t6.descripcion as content, t6.archivo
                    FROM bd_feeltourist_riu_comun.eventos_categorias AS t1 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_lugares AS t2 ON t1.identificador = t2.lugar
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_contenido AS t3 ON t2.id_lugar = t3.id_lugar 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos AS t4 ON t3.id_evento = t4.id_evento 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_ocurrencias AS t5 ON t4.id_evento = t5.id_evento 
                    INNER JOIN bd_feeltourist_riu_comun.eventos_categorias_tiene_idiomas AS t6 ON t1.id_cat = t6.id_cat
                    WHERE t6.id_idioma = '" . $id_idioma . "' 
                    AND t4.estado > 0 
                    AND t3.id_idioma = '" . $id_idioma . "' 
                    AND t5.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' 
                    GROUP BY t1.orden";


    $query = $db->consulta($queryString);
    return $db->getAllArray($query);
}

function get_ubicaciones($dias = 1)
{

    $id_idioma = $_SESSION['idioma'];

    $fecha = date('Y-m-d');
    $fecha_inicial = date("Y-m-d", strtotime($fecha));

    if ($dias > 1) {
        $dias--;
        $fecha_final = date("Y-m-d", (strtotime($fecha) + ($dias * 24 * 60 * 60)));
    } else {
        $fecha_final = date("Y-m-d", strtotime($fecha));
    }

    $db = new MySQL();

    $queryString = "SELECT t1.* 
        FROM eventos_categorias AS t1  
        INNER JOIN eventos AS t2 ON t1.id_cat = t2.id_cat 
        INNER JOIN eventos_ocurrencias AS t3 ON t2.id_evento = t3.id_evento 
        WHERE t1.id_idioma = '" . $id_idioma . "' 
        AND t2.estado > 0 
        AND t3.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' 
        GROUP BY t1.id_cat";

    $query = $db->consulta($queryString);

    while ($fila = $db->fetch_assoc($query)) {
        $datos[$fila['id_cat']] = $fila;
    }

    return $datos;
}



function get_actividades3($dias = 1, $ids_categorias = null)
{

    $array_imagenes_party = [
        'riuparty-RIU2021.jpg',
        'riuparty-white-riu2021.jpg',
        'riuparty-neon-riu2021.jpg',
        'riuparty-jungle-riu2021.jpg',
        'riuparty-pink-riu2021.jpg'
    ];

    $array_archivos_party = [
        'riuwhiteparty' => [
            'imagen' => 'riuparty-white-riu2021.jpg',
            'video' => 'riuparty-white-2023.webm'
        ],
        'riuneonparty' => [
            'imagen' => 'riuparty-neon-riu2021.jpg',
            'video' => 'riuparty-neon-2023.webm'
        ]
    ];

    $descripcion_fiestas = descripcion_fiestas();

    $result = [];


    if (isset($ids_categorias) && $ids_categorias != null) {
        $filtro_categoria = ' AND t6.id_cat IN (' . $ids_categorias . ') ';
    }

    $id_idioma = $_SESSION['idioma'];

    $fecha = date('Y-m-d');

    $array_dias[] = $fecha;

    if ($dias > 1) {

        for ($i = 1; $i < $dias; $i++) {
            $array_dias[] = date("Y-m-d", strtotime($fecha . "+ " . $i . " days"));
        }
    }

    $fecha_inicial = date("Y-m-d", strtotime($fecha));

    if ($dias > 1) {
        $dias--;
        $fecha_final = date("Y-m-d", (strtotime($fecha) + ($dias * 24 * 60 * 60)));
    } else {
        $fecha_final = date("Y-m-d", strtotime($fecha));
    }

    $db = new MySQL();

    $queryString = "SELECT t3.content, t4.id_evento, t3.title, t3.foto_evento, t3.video_evento, t1.id_cat, t1.identificador AS identificador_lugar, t1.color, t4.id_cat, t5.fecha, t4.hora_ini, t4.hora_fin  
                    FROM bd_feeltourist_riu_comun.eventos_categorias AS t1 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_lugares AS t2 ON t1.identificador = t2.lugar
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_contenido AS t3 ON t2.id_lugar = t3.id_lugar 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos AS t4 ON t3.id_evento = t4.id_evento 
                    INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_ocurrencias AS t5 ON t4.id_evento = t5.id_evento 
                    INNER JOIN bd_feeltourist_riu_comun.eventos_categorias_tiene_idiomas AS t6 ON t1.id_cat = t6.id_cat 
                    WHERE t6.id_idioma = '" . $id_idioma . "'
                    AND t4.estado > 0 
                    AND t3.id_idioma = '" . $id_idioma . "' 
                    AND t5.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' 
                    " . $filtro_categoria . "
                    ORDER BY t4.hora_ini, t4.id_evento ASC";


    $query = $db->consulta($queryString);

    $riu_party = false;

    $ruta_videos = '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/videos/';
    $ruta_videos_general = '../../../contenido_proyectos/vistaflor/_general/banner_header_general/';
    $ruta_img = '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/imagenes/agenda/';

    while ($fila = $db->fetch_assoc($query)) {

        $video_general = false;

        if ($fila['foto_evento'] == 'riuparty-RIU2021.jpg' && $fila['identificador_lugar'] == 'RiuStage') {

            $fecha_inicial_party = new DateTime('2022-03-21');
            $fecha_actual_party = new DateTime($fila['fecha']);
            $diferencia_fechas_party = $fecha_inicial_party->diff($fecha_actual_party);

            $semanas_party = floor($diferencia_fechas_party->format('%a') / 7);
            $dia_semana_party = date('N', strtotime($fila['fecha']));

            $video_general = true;

            if (($_SESSION['id_centro'] == 277 && $dia_semana_party == 3) || ($_SESSION['id_centro'] != 277 && $dia_semana_party == 5)) {
                if (($semanas_party % 2) == 0) {
                    $fila['foto_evento'] = $array_archivos_party['riuwhiteparty']['imagen'];
                    $fila['video_evento'] = $array_archivos_party['riuwhiteparty']['video'];
                } else {
                    $fila['foto_evento'] = $array_archivos_party['riuneonparty']['imagen'];
                    $fila['video_evento'] = $array_archivos_party['riuneonparty']['video'];
                }
            }
        }

        if (in_array($fila['foto_evento'], $array_imagenes_party)) {
            // $fila['identificador_lugar'] = 'riuparty';
            $riu_party = true;
        }

        if ($descripcion_fiesta_final = $descripcion_fiestas[$_SESSION['idioma']][$fila['foto_evento']]) {
            $fila['content'] = $descripcion_fiesta_final;
        }

        if (!$fila['foto_evento']) {
            $fila['foto_evento'] =  '';
        }


        if ($fila['foto_evento'] == 'riuparty-white-riu2021.jpg') {
            $fila['video_evento'] = 'riuparty-white-2023.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-neon-riu2021.jpg') {
            $fila['video_evento'] = 'riuparty-neon-2023.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-pink-riu2021.jpg') {
            $fila['video_evento'] = 'pink-party-RIU2021.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-jungle-riu2021.jpg') {
            $fila['video_evento'] = 'jungle-party-RIU2021.webm';
            $video_general = true;
        }


        if ($video_general && $fila['video_evento'] && file_exists($ruta_videos_general . $fila['video_evento'])) {
            $fila['video_evento'] = $ruta_videos_general . $fila['video_evento'];
        } elseif ($fila['video_evento'] && file_exists($ruta_videos . $fila['video_evento'])) {
            $fila['video_evento'] = $ruta_videos . $fila['video_evento'];
        } else {
            $fila['video_evento'] = '';
        }

        $fila['ruta_img'] = $ruta_img;

        $datos[$fila['hora_ini']][strtolower($fila['identificador_lugar'])][$fila['fecha']][] = $fila;


        // if(in_array($fila['foto_evento'],$array_imagenes_party)) {
        //     $fila['identificador_lugar'] = 'riuparty';
        //     $datos[$fila['hora_ini']][strtolower($fila['identificador_lugar'])][$fila['fecha']][] = $fila;
        //     $riu_party = true;
        // }

    }


    $total_horas = [];

    foreach ($datos as $key_hora => $datos_horas) {
        foreach ($datos_horas as $key_cat => $datos_cat) {
            foreach ($datos_cat as $key_fecha => $datos_fecha) {

                if ($total_horas[$key_hora][$key_cat]) {
                    if (count($datos_fecha) > $total_horas[$key_hora][$key_cat]) {
                        $total_horas[$key_hora][$key_cat] = count($datos_fecha);
                    }
                } else {
                    $total_horas[$key_hora][$key_cat] = count($datos_fecha);
                }
            }
        }
    }


    $array_final = [];
    foreach ($datos as $key_hora => $datos_horas) {

        foreach ($total_horas[$key_hora] as $key_cat => $total_cat) {

            for ($i = 0; $i < $total_cat; $i++) {

                foreach ($array_dias as $fecha_act) {

                    if ($datos[$key_hora][$key_cat][$fecha_act][$i]) {
                        $array_final[$key_hora][$key_cat][$i][$fecha_act] = $datos[$key_hora][$key_cat][$fecha_act][$i];
                    } else {
                        $array_final[$key_hora][$key_cat][$i][$fecha_act] = [
                            'status' => 'error',
                            'id_lugar' => $key_cat
                        ];
                    }
                }
            }
        }
    }



    $result = [
        "actividades" => $array_final,
        "riu_party" => $riu_party
    ];


    return $result;
}



function get_actividades_hoy($dias = 1)
{

    $array_imagenes_party = [
        'riuparty-RIU2021.jpg',
        'riuparty-white-riu2021.jpg',
        'riuparty-neon-riu2021.jpg',
        'riuparty-jungle-riu2021.jpg',
        'riuparty-pink-riu2021.jpg'
    ];

    $array_archivos_party = [
        'riuwhiteparty' => [
            'imagen' => 'riuparty-white-riu2021.jpg',
            'video' => 'riuparty-white-2023.webm'
        ],
        'riuneonparty' => [
            'imagen' => 'riuparty-neon-riu2021.jpg',
            'video' => 'riuparty-neon-2023.webm'
        ]
    ];

    $descripcion_fiestas = descripcion_fiestas();

    $id_idioma = $_SESSION['idioma'];

    // echo $id_idioma;

    $fecha = date('Y-m-d');

    $array_dias[] = $fecha;

    if ($dias > 1) {

        for ($i = 1; $i < $dias; $i++) {
            $array_dias[] = date("Y-m-d", strtotime($fecha . "+ " . $i . " days"));
        }
    }

    $fecha_inicial = date("Y-m-d", strtotime($fecha));

    if ($dias > 1) {
        $dias--;
        $fecha_final = date("Y-m-d", (strtotime($fecha) + ($dias * 24 * 60 * 60)));
    } else {
        $fecha_final = date("Y-m-d", strtotime($fecha));
    }

    $db = new MySQL();

    $queryString = "SELECT t3.content, t4.id_evento, t3.title, t3.foto_evento, t3.video_evento, t1.id_cat, t1.identificador, t1.color, t1.imagen, t4.id_cat, t5.fecha, t4.hora_ini, t4.hora_fin  
        FROM bd_feeltourist_riu_comun.eventos_categorias AS t1 
        INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_lugares AS t2 ON t1.identificador = t2.lugar
        INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_contenido AS t3 ON t2.id_lugar = t3.id_lugar 
        INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos AS t4 ON t3.id_evento = t4.id_evento 
        INNER JOIN " . $_SESSION['nombre_bbdd'] . ".eventos_ocurrencias AS t5 ON t4.id_evento = t5.id_evento 
        INNER JOIN bd_feeltourist_riu_comun.eventos_categorias_tiene_idiomas AS t6 ON t1.id_cat = t6.id_cat 
        WHERE t4.estado > 0 
        AND t3.id_idioma = '" . $id_idioma . "' 
        AND t6.id_idioma = '" . $id_idioma . "' 
        AND t5.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' 
        ORDER BY t1.orden, t4.hora_ini ASC";

    $query = $db->consulta($queryString);

    $ruta_videos = '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/videos/';
    $ruta_videos_general = '../../../contenido_proyectos/vistaflor/_general/banner_header_general/';
    $ruta_img = '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/imagenes/agenda/';

    while ($fila = $db->fetch_assoc($query)) {

        $video_general = false;

        if ($fila['foto_evento'] == 'riuparty-RIU2021.jpg' && $fila['identificador'] == 'RiuStage') {

            $fecha_inicial_party = new DateTime('2022-03-21');
            $fecha_actual_party = new DateTime($fila['fecha']);
            $diferencia_fechas_party = $fecha_inicial_party->diff($fecha_actual_party);

            $semanas_party = floor($diferencia_fechas_party->format('%a') / 7);
            $dia_semana_party = date('N', strtotime($fila['fecha']));

            $video_general = true;

            if (($_SESSION['id_centro'] == 277 && $dia_semana_party == 3) || ($_SESSION['id_centro'] != 277 && $dia_semana_party == 5)) {
                if (($semanas_party % 2) == 0) {
                    $fila['foto_evento'] = $array_archivos_party['riuwhiteparty']['imagen'];
                    $fila['video_evento'] = $array_archivos_party['riuwhiteparty']['video'];
                } else {
                    $fila['foto_evento'] = $array_archivos_party['riuneonparty']['imagen'];
                    $fila['video_evento'] = $array_archivos_party['riuneonparty']['video'];
                }
            }
        }

        if ($descripcion_fiesta_final = $descripcion_fiestas[$_SESSION['idioma']][$fila['foto_evento']]) {
            $fila['content'] = $descripcion_fiesta_final;
        }




        if (!$fila['foto_evento']) {
            $fila['foto_evento'] = '';
        }


        if ($fila['foto_evento'] == 'riuparty-white-riu2021.jpg') {
            $fila['video_evento'] = 'riuparty-white-2023.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-neon-riu2021.jpg') {
            $fila['video_evento'] = 'riuparty-neon-2023.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-pink-riu2021.jpg') {
            $fila['video_evento'] = 'pink-party-RIU2021.webm';
            $video_general = true;
        }

        if ($fila['foto_evento'] == 'riuparty-jungle-riu2021.jpg') {
            $fila['video_evento'] = 'jungle-party-RIU2021.webm';
            $video_general = true;
        }




        if ($video_general && $fila['video_evento'] && file_exists($ruta_videos_general . $fila['video_evento'])) {
            $fila['video_evento'] = $ruta_videos_general . $fila['video_evento'];
        } elseif ($fila['video_evento'] && file_exists($ruta_videos . $fila['video_evento'])) {
            $fila['video_evento'] = $ruta_videos . $fila['video_evento'];
        } else {
            $fila['video_evento'] = '';
        }

        $fila['ruta_img'] = $ruta_img;

        if (in_array($fila['foto_evento'], $array_imagenes_party) && mb_strtolower($fila['identificador']) == 'riustage') {
            $fila['color'] = "#e31671";
        }


        $datos[strtolower($fila['identificador'])][] = $fila;

        if (in_array($fila['foto_evento'], $array_imagenes_party) && mb_strtolower($fila['identificador']) != 'riustage') {
            $fila['identificador'] = 'RiuStage';
            $fila['color'] = "#e31671";
            $datos[strtolower($fila['identificador'])][] = $fila;
        }
    }



    return $datos;



    // $total_horas = [];

    // foreach ($datos as $key_hora => $datos_horas) {
    //     foreach ($datos_horas as $key_cat => $datos_cat) {
    //         foreach ($datos_cat as $key_fecha => $datos_fecha) {

    //             if ($total_horas[$key_hora][$key_cat]) {
    //                 if (count($datos_fecha) > $total_horas[$key_hora][$key_cat]) {
    //                     $total_horas[$key_hora][$key_cat] = count($datos_fecha);
    //                 }
    //             } else {
    //                 $total_horas[$key_hora][$key_cat] = count($datos_fecha);
    //             }
    //         }
    //     }
    // }

    // //print_r($datos); die;

    // $array_final = [];
    // foreach ($datos as $key_hora => $datos_horas) {

    //     foreach ($total_horas[$key_hora] as $key_cat => $total_cat) {

    //         for ($i = 0; $i < $total_cat; $i++) {

    //             foreach ($array_dias as $fecha_act) {

    //                 if ($datos[$key_hora][$key_cat][$fecha_act][$i]) {
    //                     $array_final[$key_hora][$key_cat][$i][$fecha_act] = $datos[$key_hora][$key_cat][$fecha_act][$i];
    //                 } else {
    //                     $array_final[$key_hora][$key_cat][$i][$fecha_act] = [
    //                         'status' => 'error',
    //                         'id_cat' => $key_cat
    //                     ];
    //                 }
    //             }
    //         }
    //     }
    // }

    // return $array_final;
}

function get_idiomas()
{

    $db = new MySQL();

    $sql = "SELECT * FROM idiomas WHERE activo = 1";

    $query = $db->consulta($sql);
    return $db->getAllArray($query);
}


function get_screensavers_activos($tipo_pantalla)
{

    $db = new MySQL();

    $sql = "SELECT *  
            FROM screensaver
            WHERE activo = 1 
            AND lugar = '" . strtoupper($tipo_pantalla) . "' 
            AND fecha_inicio <= NOW()
            AND fecha_fin >= NOW()";

    $query = $db->consulta($sql);
    return $db->getAllArray($query);
}


function get_screensavers_config($tipo_pantalla)
{

    $db = new MySQL();

    $sql = "SELECT espera, duracion 
            FROM screensaver_confi
            WHERE activo = 1 
            AND lugar = '" . strtoupper($tipo_pantalla) . "'";

    $query = $db->consulta($sql);
    return $db->getAllArray($query);
}

function registrarLog($data)
{

    if (file_exists('../../../../no_actualizar.txt')) return;

    $db_local = new MySQL_local();

    $id_centro = $_SESSION['id_centro'];
    $idioma = $_SESSION['idioma'];

    $seccion = $data['seccion'];
    $subseccion = $data['subseccion'] ? $data['subseccion'] : "";
    $identificador = $data['identificador'] ? $data['identificador'] : "";
    $obs = $data['observaciones'] ? $data['observaciones'] : "";

    $query = "INSERT INTO log (id, id_centro, idioma, seccion, subseccion, identificador, observaciones) 
        VALUES ( NULL, $id_centro, $idioma, '$seccion', '$subseccion', '$identificador', '$obs')";

    return $db_local->consulta($query);
}

function descripcion_fiestas()
{

    $descripcion[1] = [
        'riuparty-RIU2021.jpg' => "¿Te gusta disfrutar de las mejores fiestas durante tus vacaciones? ¡RiuParty está hecho para ti! Baila al ritmo de la música de los mejores Dj's y transfórmate con los espectáculos y performance que te ofrece cada fiesta. ¡Aprovecha tu Todo Incluido y disfruta de la mejor diversión con RIU! ",
        'riuparty-white-riu2021.jpg' => "Prepara tu mejor look blanco para tematizarte con estas fiestas y disfruta de increíbles espectáculos de ballet aéreo y muchas sorpresas más. ¡descúbrelas!",
        'riuparty-neon-riu2021.jpg' => "Ven a disfrutar de un mundo lleno de colores fluorescentes que dan luz a la fiesta. ¡contágiate con su magia!",
        'riuparty-pink-riu2021.jpg' => "Todo se tiñe de rosa para conseguir la máxima ambientación en una fiesta que no olvidarás jamás. ¡bailarás como si no hubiera mañana!",
        'riuparty-jungle-riu2021.jpg' => "Te sentirás en la mismísima selva gracias a la ambientación del personal y a los espectáculos. ¡saca tu parte más salvaje!"
    ];

    $descripcion[2] = [
        'riuparty-RIU2021.jpg' => "Do you like to enjoy the best parties during your vacation? RiuParty is made for you! Dance to the rhythm of the music of the best DJs and transform yourself with the shows and performance that each party offers you. Take advantage of your All Inclusive and enjoy the best fun with RIU!",
        'riuparty-white-riu2021.jpg' => "Prepare your best white look to theme these parties and enjoy incredible aerial ballet shows and many more surprises. discover them!",
        'riuparty-neon-riu2021.jpg' => "Come and enjoy a world full of fluorescent colors that light up the party. get in touch with its magic!",
        'riuparty-pink-riu2021.jpg' => "Everything is dyed pink to achieve the maximum atmosphere at a party that does not you will never forget You will dance like there is no tomorrow!",
        'riuparty-jungle-riu2021.jpg' => "You will feel in the jungle itself thanks to the setting of the staff and the shows. bring out your wildest part!"
    ];

    $descripcion[3] = [
        'riuparty-RIU2021.jpg' => "Genießen Sie während Ihres Urlaubs gerne die besten Partys? RiuParty ist für Sie gemacht! Tanzen Sie im Rhythmus der Musik der besten DJs und verwandeln Sie sich mit den Shows und Performances, die Ihnen jede Party bietet. Profitieren Sie von Ihrem All Inclusive und genießen Sie den besten Spaß mit RIU!",
        'riuparty-white-riu2021.jpg' => "Bereiten Sie Ihren besten weißen Look vor, um diese Partys zu thematisieren, und genießen Sie unglaubliche Luftballettshows und viele weitere Überraschungen. entdecke sie!",
        'riuparty-neon-riu2021.jpg' => "Kommen Sie und genießen Sie eine Welt voller fluoreszierender Farben, die die Party zum Leuchten bringen. nimm Kontakt mit seiner Magie auf!",
        'riuparty-pink-riu2021.jpg' => "Alles ist rosa gefärbt, um die maximale Atmosphäre auf einer Party zu erreichen, die dies nicht tut du wirst es nie vergessen Du wirst tanzen, als gäbe es kein Morgen!",
        'riuparty-jungle-riu2021.jpg' => "Sie werden sich dank der Einstellung des Personals und des Personals wie im Dschungel fühlen zeigt an. Bring deinen wildesten Teil zum Vorschein!"
    ];

    $descripcion[4] = [
        'riuparty-RIU2021.jpg' => "Você gosta de curtir as melhores festas durante as suas férias? RiuParty é feito para você! Dance ao ritmo da música dos melhores DJs transforme-se com os espectáculos e actuações que cada festa lhe oferece. Aproveite o seu Tudo Incluído e desfrute da melhor diversão com a RIU! ",
        'riuparty-white-riu2021.jpg' => "Prepare o seu melhor look branco para o tema dessas festas e desfrute de incríveis shows de balé aéreo e muitas outras surpresas. descubra-os!",
        'riuparty-neon-riu2021.jpg' => "Venha desfrutar de um mundo cheio de cores fluorescentes que iluminam a festa. entre em contato com sua magia!",
        'riuparty-pink-riu2021.jpg' => "Tudo é tingido de rosa para atingir o máximo de atmosfera em uma festa que não Você nunca vai esquecer Você vai dançar como se não houvesse amanhã!",
        'riuparty-jungle-riu2021.jpg' => "Você se sentirá na própria selva graças à ambientação da equipe e ao shows. traga para fora sua parte mais selvagem!"
    ];

    $descripcion[5] = [
        'riuparty-RIU2021.jpg' => "Aimez-vous profiter des meilleures fêtes pendant vos vacances? RiuParty est fait pour vous ! Dansez au rythme de la musique des meilleurs DJs et transformez-vous avec les spectacles et performances que chaque soirée vous propose. Profitez de votre All Inclusive et amusez-vous au maximum avec RIU !",
        'riuparty-white-riu2021.jpg' => "Préparez votre plus beau look blanc pour thématiser ces soirées et profitez d'incroyables ballets aériens et bien d'autres surprises. découvrez-les !",
        'riuparty-neon-riu2021.jpg' => "Venez profiter d'un monde plein de couleurs fluo qui illuminent la fête. entrez en contact avec sa magie !",
        'riuparty-pink-riu2021.jpg' => "Tout est teint en rose pour obtenir le maximum d'ambiance lors d'une soirée qui ne tu n'oublieras jamais Tu danseras comme s'il n'y avait pas de lendemain !",
        'riuparty-jungle-riu2021.jpg' => "Vous vous sentirez dans la jungle elle-même grâce au cadre du personnel et à la montre. faites ressortir votre partie la plus folle !"
    ];

    $descripcion[8] = [
        'riuparty-RIU2021.jpg' => "Вы любите наслаждаться лучшими вечеринками во время отпуска? RiuParty создан для вас! Танцуйте в ритме музыки лучших ди-джеев и трансформируйте себя с помощью шоу и выступлений, которые предлагает вам каждая вечеринка.",
        'riuparty-white-riu2021.jpg' => "Подготовьте свой лучший белый образ для тематических вечеринок и насладитесь невероятными воздушными балетными шоу и многими другими сюрпризами. открыть их!",
        'riuparty-neon-riu2021.jpg' => "Приходите и наслаждайтесь миром, полным флуоресцентных цветов, которые освещают вечеринку.соприкоснуться с его магией!",
        'riuparty-pink-riu2021.jpg' => "Все окрашено в розовый цвет для достижения максимальной атмосферы на вечеринке, которая не ты никогда не забудешь Ты будешь танцевать, как будто завтра не наступит!",
        'riuparty-jungle-riu2021.jpg' => "Вы почувствуете себя в самих джунглях благодаря обстановке персонала и показывает. выведи свою самую дикую часть!"
    ];

    $descripcion[9] = [
        'riuparty-RIU2021.jpg' => "你喜欢在假期里享受最好的派对吗？ RiuParty 专为您打造！随着最好的 DJ 的音乐节奏跳舞，并通过每个派对为您提供的节目和表演改变自己。充分利用您的全包服务，享受 RIU 带来的最大乐趣",
        'riuparty-white-riu2021.jpg' => "准备好你最好的白色装扮，为这些派对打造主题，享受令人难以置信的空中芭蕾舞表演和更多惊喜。发现他们",
        'riuparty-neon-riu2021.jpg' => "快来享受一个充满荧光色彩的世界，点亮派对。接触它的魔力",
        'riuparty-pink-riu2021.jpg' => "切都染成粉红色，以在派对上营造最大的气氛 你永远不会忘记你会像没有明天一样跳舞",
        'riuparty-jungle-riu2021.jpg' => "由于工作人员的设置和 显示。展现你最狂野的一面"
    ];

    return $descripcion;
}

function limpiar_caracteres_especiales($imagenes)
{

    $regex = ['amp;', '&', '-', '_'];

    if (is_array($imagenes)) {
        for ($i = 0; $i < count($imagenes); $i++) {
            // $imagenes[$i] = trim(mb_strtolower(replace('([^A-Za-z0-9.])', '', $imagenes[$i])));
            $imagenes[$i] = str_replace($regex, '', $imagenes[$i]);
        }
    } else {
        // $imagenes = trim(mb_strtolower(preg_replace('([^A-Za-z0-9.])', '', $imagenes)));
        $imagenes = str_replace($regex, '', $imagenes);
    }
    return $imagenes;
}


function get_BBDD_comun($id_cadena)
{

    $db = new MySQL();

    $sql = "SELECT *
            FROM bd2_clientes_node.bd_comun
            WHERE active = 1 
            AND id_cadena = " . $id_cadena;

    $query = $db->consulta($sql);
    return $db->getAllArray($query);
}
