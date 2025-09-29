
<?php

// $categorias = get_categorias(7);
// $ubicaciones = get_ubicaciones(7);

if (!isset($_SESSION['filtros_categorias']) || $_SESSION['filtros_categorias'] == '') {
    $actividades = get_actividades3(7);
} else {
    $actividades = get_actividades3(7, $_SESSION['filtros_categorias']);
}

$categorias = get_categorias(7);
$colores_categorias = get_colores_categorias();




// echo "<pre>";
// print_r($imagenes);
// die;

$hora_anterior = '';

$tpl = new TemplatePower("templates/layout_week.html", T_BYFILE);
$tpl->prepare();

$dias_semana = [
    "1" => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
    "2" => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    "3" => ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag']
];


$cerrado = [
    "1" => 'Cerrado',
    "2" => 'Closed',
    "3" => 'Geschlossen'
];

$dia_actual = date('N');

$array_actividades_party = [];

$id_categoria = [
    'riuart' => 'riu_art.png',
    'riufit' => 'riu_fit.png',
    'riufun' => 'riu_fun.png',
    'riuland' => 'riuland.png',
    'riustage' => 'logo-stage.png',
    'riu4u' => 'riu_4u.png',
    'riuparty' => 'logo-party.png',

];

$array_imagenes_party = [
    'riuparty-RIU2021.jpg',
    'riuparty-white-riu2021.jpg',
    'riuparty-neon-riu2021.jpg',
    'riuparty-jungle-riu2021.jpg',
    'riuparty-pink-riu2021.jpg'
];


if ($actividades['riu_party']) {

    $descripcion_fiestas = descripcion_fiestas();

    $logo_src =  "./assets/categories/" . $id_categoria['riuparty'];

    $categorias[] = [
        'id_cat' => '99999',
        'identificador' => 'riuparty',
        'color' => '#e31671',
        'title' => 'RiuParty',
        'content' => str_replace("'", '"', $descripcion_fiestas[$_SESSION['idioma']]['riuparty-RIU2021.jpg']),
        'archivo' => '',
        'hora_ini' => '<img class="w-16 h-auto absolute top-2 right-5" src="' . $logo_src . '">'
    ];

    $colores_categorias['riuparty'] = '#e31671';
}

// echo "<pre>";
// print_r($categorias);
// die;

if (count($actividades['actividades']) > 0) {

    $tpl->newBlock("mostrar_actividades");

    for ($i = 0; $i < count($dias_semana[$_SESSION['idioma']]); $i++) {
        $tpl->newBlock("dias_semana");

        if ($dia_actual > count($dias_semana[$_SESSION['idioma']])) {
            $dia_actual = 1;
        }

        $tpl->assign("dia", $dias_semana[$_SESSION['idioma']][$dia_actual - 1]);
        $dia_actual++;
    }

    foreach ($actividades['actividades'] as $key_horas => $horas) {
        foreach ($horas as $key_fecha => $cat) {
            foreach ($cat as $key_cat => $datos_categorias) {
                $tpl->newBlock("bloque_actividades");

                $tpl->assign("horario", $key_horas);

                if ($hora_anterior == $key_horas) {
                    $tpl->assign("ocultar", 'hidden');
                }

                $tpl->assign("id_category", $key_fecha);
                $hora_anterior = $key_horas;

                foreach ($datos_categorias as $actividad) {

                    $tpl->newBlock("actividades");
                    if ($actividad['status'] == 'error') {

                        $actividad['identificador_lugar'] = $actividad['id_lugar'];

                        $tpl->assign("fondo_texto", "#FFF");
                        $tpl->assign("size_img", "w-36");
                        $tpl->assign("ocultar_hora", "hidden");


                        $tpl->assign("img", './assets/categories/' .  $id_categoria[$key_fecha]);
                        // $tpl->assign("nombre",  mb_strtoupper($cerrado[$_SESSION['idioma']]));
                        // $tpl->assign("ocultar_texto",  "hidden");
                    } else {


                        $title = explode(' | ', $actividad['title']);

                        if (count($title) > 1) {

                            $tpl->assign("nombre", mb_strtoupper($title[1]));
                            $tpl->assign("categoria", mb_strtoupper($title[0]));
                        } else {

                            $tpl->assign("nombre", mb_strtoupper($title[0]));
                            $tpl->assign("categoria", mb_strtoupper($actividad['identificador_lugar']));
                        }

                        //COMPROBAR SI EXISTEN LAS IMÁGENES
                        if (!in_array(trim(limpiar_caracteres_especiales($actividad['foto_evento'])), $imagenes) || empty($actividad['foto_evento'])) {
                            $tpl->assign("img", './assets/categories/' .  $id_categoria[$key_fecha]);
                            $tpl->assign("size_img", "w-40");
                        } else {
                            $tpl->assign("size_img", "w-full");
                            $tpl->assign("img", $actividad['ruta_img'] . $actividad['foto_evento']);
                        }

                        $tpl->assign("hora_ini", $actividad['hora_ini']);

                        // RIU PARTY EN UN NUEVO BLOQUE
                        if (in_array($actividad['foto_evento'], $array_imagenes_party)) {
                            $array_actividades_party[$actividad['fecha'] . ' ' . $actividad['hora_ini']] = $actividad;
                            $tpl->assign("img-top", 'top-0');
                        }

                        $tpl->assign("fondo_texto", $colores_categorias[mb_strtolower($actividad['identificador_lugar'])]);
                    }

                    $tpl->assign("rounded", 'rounded-t-xl');

                    if (strtolower($actividad['identificador_lugar']) == 'riustage') {
                        $tpl->assign("alto_caja", 'p-1/40');
                    } else {
                        $tpl->assign("alto_caja", 'p-1/100');
                    }


                    $tpl->assign("info_hidden", json_encode($actividad, true));

                    // die;
                }
            }
        }
    }
} else {
    $tpl->assignGlobal("sin_actividades", 'No hay actividades programadas');
}


ksort($array_actividades_party);
foreach ($array_actividades_party as $actividad) {

    $tpl->newBlock("riuparty");

    if (!file_exists($actividad['ruta_img'] . $actividad['foto_evento']) || empty($actividad['foto_evento'])) {
        $tpl->assign("img", './assets/categories/' .  $id_categoria[$key_fecha]);
        $tpl->assign("size_img", "w-36");
    } else {
        $tpl->assign("size_img", "w-full");
        $tpl->assign("img", $actividad['ruta_img'] . $actividad['foto_evento']);
    }


    if (file_exists($actividad['video_evento']) && !empty($actividad['video_evento'])) {
        $tpl->assign('play', '<div class="playBtn z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="btnplay" width="100" height="100" viewBox="0 0 26 26">
                                        <polygon fill="#fff" class="play-btn__svg" points="9.33 6.69 9.33 19.39 19.3 13.04 9.33 6.69"/>
                                        <path fill="#fff" class="play-btn__svg" d="M26,13A13,13,0,1,1,13,0,13,13,0,0,1,26,13ZM13,2.18A10.89,10.89,0,1,0,23.84,13.06,10.89,10.89,0,0,0,13,2.18Z"/>
                                    </svg> 
                               </div>');
    }




    $tpl->assign("nombre", mb_strtoupper($actividad['title']));
    $tpl->assign("fecha", $dias_semana[$_SESSION['idioma']][date('N', strtotime($actividad['fecha'])) - 1]);

    $tpl->assign("info_hidden", json_encode($actividad, true));
    $tpl->assign("fondo_texto", $actividad['color']);
    $tpl->assign("alto_caja", 'p-1/40');
    $tpl->assign("rounded", 'rounded-t-xl');
    $tpl->assign("hora_ini", $actividad['hora_ini']);
    $tpl->assign("hora_fin", $actividad['hora_fin']);

    $tpl->gotoBlock("actividades");
}

// die;



// echo "<pre>";
// print_r($categorias);die;



foreach ($categorias as $clave => $categoria) {
    $tpl->newBlock("categorias");
    $logo_src =  "./assets/categories/" . $id_categoria[strtolower($categoria['identificador'])];

    $tpl->assign("logo", $logo_src);
    $tpl->assign("nombre",  mb_strtoupper($categoria['nombre']));

    if ($categoria['identificador'] == 'riuparty') {
        $tpl->assign("id_cat",  'riuparty');
    } else {
        $tpl->assign("id_cat",  strtolower($categoria['identificador']));
    }

    $categoria['title'] = '<p class="text-[' . $categoria['color'] . ']">' . $categoria['title'] . '</p>';
    $categoria['hora_ini'] = '<img class="w-16 h-auto absolute top-2 right-5" src="' . $logo_src . '">';

    if (file_exists("../../../contenido_proyectos/vistaflor/_general/videos_animacion/" . $categoria['archivo']) && !empty($categoria['archivo'])) {
        $categoria['video_evento'] = "../../../contenido_proyectos/vistaflor/_general/videos_animacion/" . $categoria['archivo'];
    }


    $tpl->assign("info_hidden", json_encode($categoria, true));
}


?>