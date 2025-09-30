<?php

$tpl_portada = new TemplatePower("plantillas/destinos.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',LANG_FEELAPP_DESTINOS);
$tpl_portada->assign('volver_destinos',LANG_FEELAPP_VOLVER_DESTINOS);

$destinos = get_destinos($_SESSION['id_cadena'], $_SESSION['idioma']);
//print_r($destinos); die;

foreach($destinos as $destino){

    //$destino['id'];
    //$destino['id_destino'];
    //$destino['nombre'];
    //$destino['identidicador];
    //$destino['imagen'];
    //$destino['id_pais']

    $tpl_portada->newBlock('destinos');
    $tpl_portada->assign('id_destino', $destino['id_destino']);
    $tpl_portada->assign('nombre_destino',$destino['nombre']);
    $tpl_portada->assign('imagen_destino',$destino['imagen']);

    $paises = get_destinos_paises($_SESSION['id_cadena'],$destino['id_destino'],$_SESSION['idioma']);

    foreach($paises as $pais){

        //$pais['id'];
        //$pais['id_pais'];
        //$pais['identificador'];
        //$pais['nombre'];

        $tpl_portada->newBlock('paises');
        $tpl_portada->assign('id_pais', $pais['id_pais']);
        $tpl_portada->assign('nombre_pais',$pais['nombre']);
        $tpl_portada->assign('identidicador_pais',$pais['identificador']);

        $hoteles = get_destinos_hoteles($pais['id_pais'], $_SESSION['id_cadena'], $_SESSION['idioma']);

        foreach($hoteles as $hotel){

//            [id] => 134
//            [id_hotel] => 134
//            [id_idioma] => 1
//            [nombre] => Hotel Riu Plaza Miami Beach
//            [region] => Miami Beach
//            [informacion] => Reformado en verano 2014. <br> Al borde de la playa de Miami Beach. <br> 14,5 km hasta Downtown Miami. <br> WiFi gratuito en todo el hotel. <br> Restaurante buffet y snack piscina.
//            [id_pais] => 17
//            [identificador] => Hotel Riu Plaza Miami Beach
//            [categoria] => 0
//            [imagen] => miami_beach.jpg
//            [telefono] => (+1) 305 673 5333
//            [email] => hotel.plazamiamibeach@riu.com
//            [id_lugar] => 20
//            [id_cadena] => 2
//            [id_centro] => 256
//            [dron] => riucabecera.webm


                $tpl_portada->newBlock('hoteles');
                $tpl_portada->assign('id_hotel', $hotel['id_hotel']);
                $tpl_portada->assign('nombre_hotel',$hotel['nombre']);
                $tpl_portada->assign('region',$hotel['region']);
                $tpl_portada->assign('informacion_hotel', $hotel['informacion']);
                $tpl_portada->assign('identificador_hotel', $hotel['identificador']);
                //../../comun/destinos/hoteles/2/7/
                $tpl_portada->assign('imagen_hotel','../../contenido_proyectos/comun/destinos/hoteles/' . $_SESSION['id_cadena'] . '/'.$pais['id_pais'].'/' . $hotel['imagen']);
                $tpl_portada->assign('telefono_hotel',$hotel['telefono']);
                $tpl_portada->assign('email_hotel', $hotel['email']);
                $tpl_portada->assign('id_lugar',$hotel['id_lugar']);
                $tpl_portada->assign('dron_hotel',$hotel['dron_hotel']);
                $tpl_portada->assign('nombre_pais_hotel',$pais['nombre']);

        }

    }


}$tpl_portada->gotoBlock('ROOT');

$tpl_portada->assign('prueba_texto','holaaaa');

$tpl_portada->printToScreen();

?>