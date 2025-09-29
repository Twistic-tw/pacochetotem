<?php

function getActividades()
{
    $tpl_index = new TemplatePower("plantillas/plantillaAnimacion.html", T_BYFILE);
    $tpl_index->prepare();

    $array_tomorrow = ['','Mañana', 'Tomorrow', 'Morgen', 'Demain'];
    $array_hoy = ["","Hoy", "Today", "Heute", "Aujourd'hui"];

//   echo $_SESSION['idioma'];

    if ($_SESSION['idioma'] == $_SESSION['num_idiomas']) {
        $_SESSION['idioma'] = 1;
    } else {
        //$_SESSION['idioma'] = $_SESSION['idioma'] + 1;
    }

    for ($i = 1; $i < $_SESSION['num_idiomas'] + 1; $i++) {

        $tpl_index->newBlock("contenedor_animacion");
        $tpl_index->assign('id_idioma', $i);

        $animacion = mostrarAnimacion($i);

        foreach ($animacion as $row) {
            $tpl_index->newBlock("animacion");
            $tpl_index->assign('nombre_animacion', $row['nombre']);
            $tpl_index->assign('hora_inicio', $row['hora_inicio']);
            $tpl_index->assign('hora_final', $row['hora_final']);

            if($row['fecha'] != ''){
                $tpl_index->assign('fecha', $array_tomorrow[$i]);
            }else{
                $tpl_index->assign('fecha', $array_hoy[$i]);
            }
        }
        $tpl_index->gotoBlock("_ROOT");
    }
    $tpl_index->gotoBlock("_ROOT");

    echo json_encode($tpl_index->getOutputContent());
}

function getTiempo()
{

    //Sacar informacion el tiempo

    $tpl_index = new TemplatePower("plantillas/plantillaTiempo.html", T_BYFILE);
    $tpl_index->prepare();
    $info_tiempo = mostrarTiempo();
    foreach ($info_tiempo as $json_tiempo) {

        $json_tiempo = json_decode($info_tiempo['json_basico'], true);
        $tpl_index->newBlock("tiempo");
        $tpl_index->assign('temperatura_minima', round($json_tiempo['temperatura_minima']));
        $tpl_index->assign('temperatura_maxima', ceil($json_tiempo['temperatura_maxima']));
        $tpl_index->assign('humedad', $json_tiempo['humedad']);
        $tpl_index->assign('icono', $json_tiempo['icono']);

        $tpl_index->assign('viento', 3.60 * round($json_tiempo['viento']));
    }
    $tpl_index->gotoBlock("_ROOT");

    echo json_encode($tpl_index->getOutputContent());
}

