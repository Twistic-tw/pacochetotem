<?php

$tpl_portada = new TemplatePower("plantillas/rutas.html", T_BYFILE);
$tpl_portada->prepare();

$id_ruta = $_GET['id_ruta'];

switch ($id_ruta) {
    case 'ruta-1':
        $tpl_portada->assign('titulo_seccion','Running');

        $array_rutas = array(array('nombre' => 'San Agustin-brco del toro- san agustín','ruta' => 'https://es.wikiloc.com/rutas-carrera/san-agustin-brco-del-toro-san-agustin-37034699'),
                             array('nombre' => 'San Agustín-Maspalomas (Gran Canaria)','ruta' => 'https://es.wikiloc.com/rutas-carrera/san-agustin-maspalomas-gran-canaria-19-12-15-12236123'),
                             array('nombre' => 'Barranco de los Guinchos','ruta' => 'https://es.wikiloc.com/rutas-carrera/barranco-de-los-guinchos-9339381'),
                             array('nombre' => 'San Agustin a maspalomas','ruta' => 'https://es.wikiloc.com/rutas-carrera/san-agustin-a-maspalomas-5137845'),
                             array('nombre' => 'San Agustin - Meloneras','ruta' => 'https://es.wikiloc.com/rutas-carrera/san-agustin-meloneras-42214682'));

        break;
    case 'ruta-2':
        $tpl_portada->assign('titulo_seccion',LANG_SENDERISMO);

        $array_rutas = array(array('nombre' => 'Barranco del TORO. GRAN CANARIA','ruta' => 'https://es.wikiloc.com/rutas-senderismo/barranco-del-toro-gran-canaria-63866748'),
            array('nombre' => 'Muro de la Vega de Amurga','ruta' => 'https://es.wikiloc.com/rutas-senderismo/20-04-2019-muro-de-la-vega-de-amurga-38601484'),
            array('nombre' => 'Las Burras - Barranco de los Guinchos - Barranco del Toro - Barranco de la Fuente - Las Burras','ruta' => 'hhttps://es.wikiloc.com/rutas-senderismo/circular-las-burras-barranco-de-los-guinchos-barranco-del-toro-barranco-de-la-fuente-las-burras-33753018'),
            array('nombre' => 'Barranco del Toro','ruta' => 'https://es.wikiloc.com/rutas-senderismo/barranco-del-toro-43783472'),
            array('nombre' => 'Barranco del Aguila','ruta' => 'https://es.wikiloc.com/rutas-senderismo/barranco-del-aguila-8354615'));

        break;
    case 'ruta-3':
    default:
        $tpl_portada->assign('titulo_seccion',LANG_PASEO);

    $array_rutas = array(array('nombre' => 'Playa del Ingles ida y vuelta','ruta' => 'https://es.wikiloc.com/rutas-a-pie/playa-del-ingles-ida-y-vuelta-2289184'),
        array('nombre' => 'Playa de San Agustín - Faro de Maspalomas','ruta' => 'https://es.wikiloc.com/rutas-a-pie/playa-de-san-agustin-faro-de-maspalomas-4300810'),
        array('nombre' => 'Paseo por San Agustín','ruta' => 'https://es.wikiloc.com/rutas-a-pie/paseo-por-san-agustin-33313192'),
        array('nombre' => 'San Agustin - Meloneras: Dunas de Maspalomas ','ruta' => 'https://es.wikiloc.com/rutas-a-pie/san-agustin-meloneras-dunas-de-maspalomas-45626006'));

        break;
}


foreach($array_rutas as $row){

    $tpl_portada->newBlock('rutas');
    $tpl_portada->assign('nombre',$row['nombre']);
    $tpl_portada->assign('rutas',$row['ruta']);
    $tpl_portada->assign('ver_ruta',LANG_VER_RUTA);

}$tpl_portada->gotoBlock('_ROOT');


$tpl_portada->printToScreen();

?>