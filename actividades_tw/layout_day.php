<?php

$tpl = new TemplatePower("templates/layout_day.html", T_BYFILE);
$tpl->prepare();

$actividades = get_actividades_hoy(1);

// unset($actividades['riuart']);
$num_categorias = count($actividades);


$logo_categoria = [
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


// echo "<pre>";
// print_r($actividades);
// die;

if (count($actividades) > 0) {

    $existe_stage = false;
    $padding = 0;

    $tpl->newBlock('content_activities');

    foreach ($actividades as $nombre_categoria => $actividades) {

        //SI ES RIU STAGE
        if ($nombre_categoria == 'riustage') {
            $existe_stage = true;

            $tpl->assignGlobal('logo_stage', './assets/categories/' . $logo_categoria[$nombre_categoria]);
            foreach ($actividades as $actividad) {

                if (in_array($actividad['foto_evento'], $array_imagenes_party)) {
                    $existe_riuparty = true;
                }


                $tpl->newBlock('riu_stage');

                if (file_exists($actividad['ruta_img'] . $actividad['foto_evento'])) {
                    $tpl->assign("img_stage", $actividad['ruta_img'] . $actividad['foto_evento']);
                } else {
                    $tpl->assign("img_stage", '');
                }

                $tpl->assign("nombre", mb_strtoupper($actividad['title']));
                $tpl->assign("fondo_texto", $actividad['color']);
                $tpl->assign("hora_ini", $actividad['hora_ini']);
            }
            continue;
        }

        //SI ES RIU ART O 4U
        if ($nombre_categoria == 'riuart' || $nombre_categoria == 'riu4u') {
            $padding = 4;
        }


        $tpl->newBlock('categorias');
        $tpl->assign('logo_categoria', './assets/categories/' . $logo_categoria[$nombre_categoria]);


        if ($num_categorias < 4) {
            $tpl->assign('ancho_dinamico', 'w-1/4');
        } else {
            $tpl->assign('ancho_dinamico', 'w-1/' . $num_categorias);
        }


        if ($padding > 0) {
            $tpl->assignGlobal("gap", "px-" . $padding);
        }

        foreach ($actividades as $actividad) {

            if (in_array($actividad['foto_evento'], $array_imagenes_party)) {
                $existe_riuparty = true;
            }

            $tpl->newBlock('actividades');

            if ($num_categorias > 5) {
                $tpl->assign('size_text', 'text-sm');
                $tpl->assign('padding_hora', 'py-1 px-2');
                $tpl->assign('margin_act', 'mx-4 mt-3');
            } else {
                $tpl->assign('padding_hora', 'py-2 px-4');
                $tpl->assign('margin_act', 'mx-6 mt-5');
            }


            $title = explode(' | ', $actividad['title']);


            // if (mb_strtolower($nombre_categoria) == 'riuland') {
            if (count($title) > 1) {
                $tpl->assign("nombre", mb_strtoupper($title[1]));
                $tpl->assign("categoria", mb_strtoupper($title[0]));
            } else {
                $tpl->assign("nombre", mb_strtoupper($title[0]));
                $tpl->assign("categoria", mb_strtoupper($nombre_categoria));
            }
            // }

            if (!in_array(trim(limpiar_caracteres_especiales($actividad['foto_evento'])), $imagenes) || empty($actividad['foto_evento'])) {
                $tpl->assign("img", './assets/categories/' . $logo_categoria[$nombre_categoria]);
                $tpl->assign("p_categoria", "p-2");
            } else {
                $tpl->assign("img", $actividad['ruta_img'] . $actividad['foto_evento']);
            }


            $tpl->assign("hora_ini", $actividad['hora_ini']);
            $tpl->assign("fondo_texto", $actividad['color']);

            $tpl->assign("info_hidden", json_encode($actividad, true));
        }
    }


    if ($existe_riuparty) {
        $tpl->newBlock('logo_party');
        $tpl->assign('logo', "./assets/categories/".$logo_categoria['riuparty']);
        $tpl->assignGlobal('padding-r', "pr-28");
    }


} else {
    $tpl->assignGlobal("sin_actividades", 'No hay actividades programadas');
}

if (!$existe_stage) {
    $tpl->assignGlobal("ocultar_stage", 'hidden');
}
