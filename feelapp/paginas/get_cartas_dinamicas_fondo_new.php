<?php

$_GET['carta'] = $_GET['id_carta'];
$nombre_cartas_global = $_GET['id_carta'];

$array_fechas_semana_inicial = [
    1 => '2021-09-20',
    2 => '2021-09-21',
    3 => '2021-09-22',
    4 => '2021-09-23',
    5 => '2021-09-24',
    6 => '2021-09-25',
    7 => '2021-09-26',
];

$id_idioma = $_SESSION['idioma'];

switch ($id_idioma) {
    case 1:
        $array_dias_semanas_cartas = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        break;
    case 3:
        $array_dias_semanas_cartas = [
            1 => 'Montag',
            2 => 'Dienstag',
            3 => 'Mittwoch',
            4 => 'Donnerstag',
            5 => 'Freitag',
            6 => 'Samstag',
            7 => 'Sonntag'
        ];
        break;
    case 4:
        $array_dias_semanas_cartas = [
            1 => 'Lundi',
            2 => 'mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        break;
    case 5:
        $array_dias_semanas_cartas = [
            1 => 'Понедельник',
            2 => 'вторник',
            3 => 'Среда',
            4 => 'четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            7 => 'Воскресенье»'
        ];
        break;
    case 2:
    default:
        $array_dias_semanas_cartas = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];
        break;
}


$fecha_actual = date('Y-m-d');
$dia_semana = date("N");

$fecha_inicial = $array_fechas_semana_inicial[$dia_semana];

$diferencia_dias = get_semana_actual_cartas($fecha_inicial, $fecha_actual);
$semanas = $diferencia_dias / 7;

if ($semanas % 2 == 0) {
    $semana_actual = 2;
} else {
    $semana_actual = 1;
}


$array_cartas_lopesan_tematicos[1] = [
    1 => '22',
    2 => '23',
    3 => '24',
    4 => '25',
    5 => '26',
    6 => '27',
    7 => '28',
];

$array_cartas_lopesan_tematicos[2] = [
    1 => '29',
    2 => '30',
    3 => '31',
    4 => '32',
    5 => '33',
    6 => '34',
    7 => '35',
];

$texto_categorias = null;
$lista_cartas = null;
if ($_GET['carta'] == 'carta_global') {

    $_GET['carta'] = $array_cartas_lopesan_tematicos[$semana_actual][$dia_semana];
    //$texto_categorias = $array_dias_semanas_cartas[$dia_semana] . ' - ';

//    echo $_GET['carta'];
//    die;


    $pagina_inicio = $_GET['carta'];
    $id_pagina = 1;
    $dia_semana_while = $dia_semana;

    while ($id_pagina <= 7) {

//            echo $id_pagina . '<br>';
//            echo $pagina_inicio . '<br>';

        if ($pagina_inicio > 35) {
            $pagina_inicio = 22;
        }

        $lista_cartas .= $pagina_inicio . ',';


        if ($dia_semana_while > 7) {
            $dia_semana_while = 1;
        }

        $texto_categorias[$id_pagina] = $array_dias_semanas_cartas[$dia_semana_while];

        $dia_semana_while++;
        $pagina_inicio++;
        $id_pagina++;

    }

    $lista_cartas = trim($lista_cartas, ',');
    //echo $lista_cartas; die;

}



//echo $semanas; die;

$tpl_cartas = new TemplatePower("plantillas/cartas_dinamicas_fondo.html", T_BYFILE);
$tpl_cartas->prepare();


if (!isset($_GET['sin_cabecera'])) {
    $tpl_cartas->newBlock('cabecera');
    $tpl_cartas->gotoBlock('_ROOT');
    $tpl_cartas->newBlock('footer');
    $tpl_cartas->gotoBlock('_ROOT');
}

$array_cabeceras = [
    '1' => LANG_DESAYUNO_TEXTO,
    '2' => LANG_COMIDA,
    '3' => LANG_MENU_CENA,
    '4' => LANG_BEBIDAS,
];

$id_subcategoria = null;
$idioma = $_SESSION['idioma'];

if ($_SESSION['id_centro'] == 3601) {
    $moneda = "$";
} else {
    $moneda = "€";
}

$contador_btn_new = 1;
$contador_div = 1;

//$_GET['carta'] = '2_1-3_4';
//    $_GET['carta'] = '2';

$explode_cartas = explode('-', $_GET['carta']);


if (count($explode_cartas) > 1) {

    $tpl_cartas->newBlock('botones_cartas');


    foreach ($explode_cartas as $e_carta) {

        $datos_cartas = explode('_', $e_carta);

        $id_carta = $datos_cartas[0];

        $id_franja = $datos_cartas[1];

        $tpl_cartas->newBlock('btn_carta');

        if ($contador_btn_new == 1) {
            $tpl_cartas->assign('active', 'active');
        }

        $contador_btn_new++;

        $tpl_cartas->assign('nombre_carta', $array_cabeceras[$id_franja]);
        $tpl_cartas->assign('id_carta', $id_carta);

    }

}
$tpl_cartas->gotoBlock('_ROOT');


$dia_cartas_global = 1;
foreach ($explode_cartas as $carta_general) {

    $e_cartas = explode('_', $carta_general);


    $id_carta = $e_cartas[0];
    $id_franja = $e_cartas[1];

    if ($nombre_cartas_global == 'global') {//parche hasta poner todo con plantillas
        $id_carta = 'global';
    }

    $datos_cartas = get_datos_cartas($id_carta);

    $class_nombre = $datos_cartas[0]['class_nombre'];
    $class_descripcion = $datos_cartas[0]['class_descripcion'];
    $class_denominacion = $datos_cartas[0]['class_denominacion'];
    $class_capacidad_precio = $datos_cartas[0]['class_capacidad_precio'];
    $class_suplemento = $datos_cartas[0]['class_suplemento'];
    $class_accordion = $datos_cartas[0]['class_accordion'];
    $fondo_carta = $datos_cartas[0]['fondo_carta'];
    $img_cabecera = $datos_cartas[0]['cabecera'];

    $tpl_cartas->assignGlobal('fondo_carta', 'https://view.twisticdigital.com/lopesan/contenido/_general/fondos_cartas_dinamicas/' . $fondo_carta);

    if (!$class_accordion) {
        $class_accordion = 'accordion abrir_accordion ';
    }

    if (!$class_nombre) {
        $class_nombre = 'carta-nombre-superior';
    }

    //$class_accordion = 'accordion';
    $ids_cartas = explode(',', $lista_cartas);



    $categorias = array();

    foreach ($ids_cartas as $carta_id) {
        $categorias_aux = get_categorias_carta($carta_id, $idioma);
        $categorias[] = $categorias_aux[0];
    }



    if (!$categorias) {
        $idioma = 2;
        $categorias = get_categorias_carta($id_carta, $idioma, $lista_cartas);
    }

    if ($categorias) {

        if (count($categorias) == 1) {
            $categoria_principal = 1;
        } else {
            $categoria_principal = null;
        }

        $tpl_cartas->newBlock('cartas');

        if (!isset($_GET['sin_cabecera'])) {
            $tpl_cartas->newBlock('cabecera_panel');
            $tpl_cartas->gotoBlock('cartas');
            $tpl_cartas->newBlock('footer_panel');
            $tpl_cartas->gotoBlock('cartas');
        }


        $tpl_cartas->assign('id_carta', $id_carta);

        if ($contador_div != 1) {
            $tpl_cartas->assign('active', 'displayNone');
        }

        $contador_div++;

        $suplementos = get_suplementos_carta($id_carta, $idioma);

        foreach ($categorias as $categoria) {

            $datos = get_carta_dinamica($categoria['id_categoria'], $idioma);

            if ($datos) {

                $tpl_cartas->newBlock('categorias');

                if ($texto_categorias) {
                    $texto_dia_categoria = $texto_categorias[$dia_cartas_global] . ' - ';
                    $dia_cartas_global++;
                }

                $tpl_cartas->assign('nombre_categoria', $texto_dia_categoria . $categoria['nombre']);
                $tpl_cartas->assign('class_accordion', $class_accordion);

                if ($categoria_principal == 1) {
                    $tpl_cartas->assign('accordion_active', 'active');
                    $tpl_cartas->assign('display_block', "style='display: block'");
                }

                foreach ($datos as $item) {

                    if ($id_subcategoria != $item['id_subcategoria'] && $item['id_subcategoria'] > 0) {
                        $id_subcategoria = $item['id_subcategoria'];
                        $subcategoria = get_subcategorias_carta($item['id_subcategoria'], $idioma);

                        if ($subcategoria) {
                            $tpl_cartas->newBlock('subcategorias');
                            $tpl_cartas->assign('nombre_subcategorias', $subcategoria[0]['nombre']);
                            $tpl_cartas->gotoBlock('categorias');
                        }

                    }

                    $tpl_cartas->newBlock('articulos');

                    $tpl_cartas->assign('id_articulo', $item['id_articulo']);
                    $tpl_cartas->assign('class_nombre', $class_nombre);
                    $tpl_cartas->assign('class_nombre', $class_nombre);
                    $tpl_cartas->assign('nombre_articulo', $item['nombre_articulo']);

                    if($item['denominacion_origen']){
                        $item['denominacion_origen'] = str_replace('(','',$item['denominacion_origen']);
                        $item['denominacion_origen'] = str_replace(')','',$item['denominacion_origen']);
                        $item['denominacion_origen'] = str_replace('D.O. ','',$item['denominacion_origen']);
                        $item['denominacion_origen'] = str_replace('D.O ','',$item['denominacion_origen']);
                    }

                    if($item['descripcion']){
                        $item['descripcion'] = trim($item['descripcion']);
                        $item['descripcion'] = str_replace('(','',$item['descripcion']);
                        $item['descripcion'] = str_replace(')','',$item['descripcion']);
                    }

                    $tpl_cartas->assign('denominacion_origen', $item['denominacion_origen']);

                    if ($item['infantil'] == 1) {
                        $tpl_cartas->assign('logo_infantil', '<img class="logo-panchi-carta" src="https://view.twisticdigital.com/lopesan/contenido/_general/feelapp/iconos/panchi.png" />');
                    }

                    $tpl_cartas->assign('class_descripcion', $class_descripcion);
                    $tpl_cartas->assign('descripcion', $item['descripcion']);


                    foreach ($item['capacidad_precio'] as $capacidad_precio) {

                        $tpl_cartas->newBlock('capacidad_precio');

                        if ($capacidad_precio['precio']) {
                            $capacidad_precio['precio'] = str_replace(',', '.', $capacidad_precio['precio']);
                            $contenido_precio_final = number_format($capacidad_precio['precio'], 2, ',', '') . $moneda;
                        } else {
                            $contenido_precio_final = null;
                        }

                        $tpl_cartas->assign('class_capacidad_precio', $class_capacidad_precio);
                        $tpl_cartas->assign('capacidad', $capacidad_precio['capacidad']);
                        $tpl_cartas->assign('contenido_precio_final', $contenido_precio_final);
                        $tpl_cartas->gotoBlock('articulos');

                    }


                    if ($suplementos[$item['id_articulo']]) {

                        foreach ($suplementos[$item['id_articulo']] AS $datos_suplementos) {

                            $datos_suplementos['precio'] = str_replace(',', '.', $datos_suplementos['precio']);

                            $tpl_cartas->newBlock('suplementos');

                            $tpl_cartas->assign('class_suplemento', $class_suplemento);

                            $tpl_cartas->assign('nombre_suplemento', $datos_suplementos['nombre_suplemento']);
                            $tpl_cartas->assign('descripcion_suplemento', $datos_suplementos['descripcion_suplemento']);
                            $tpl_cartas->assign('capacidad', $datos_suplementos['capacidad']);
                            $tpl_cartas->assign('precio', number_format($datos_suplementos['precio'], 2, ',', '') . $moneda);

                            $tpl_cartas->gotoBlock('articulos');

                        }
                    }

                }

            }

        }

    }


}


$tpl_cartas->gotoBlock('ROOT');

if (!isset($_GET['sin_cabecera'])) {
    $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => $tpl_cartas->getOutputContent());
    echo json_encode($respuesta);
} else {
    $tpl_cartas->printToScreen();
}


?>