<?php

// Aqui hay que pedir de session la cadena del hotel
$id_cadena = $_SESSION["id_cadena"];

$id_idioma = $_SESSION['idioma'];

$url_base = $_SESSION["url_comun"];

$url_base_hoteles = $url_base . 'destinos/hoteles/'.$id_cadena.'/';

$url_base_lugares = $url_base . 'destinos/lugares/';



$id_destino = $_GET['zona'];

registrarLog("destinos", "paises", $id_destino);

if ($id_destino == '0') {

    //Aqui tengo que ver como pinto el mapa otra vez

    $tpl_destinos = new TemplatePower("plantillas/seccion_zonas_listado_solo.html", T_BYFILE);
    $tpl_destinos->prepare();

    $tpl_destinos->assignGlobal("url_base_hoteles", $url_base_hoteles );

    $tpl_destinos->assign("selector", LANG_CONOCE_SELECCIONAR_ZONAS);
    $tpl_destinos->assign("selector_mapa", LANG_DESTINOS_SELECTOR);

    $tpl_destinos->assign("info_label", LANG_DESTINOS_TITLE);

    $tpl_destinos->assign("ver_zona", LANG_VERZONA);

    $tpl_destinos->assign("idioma", $id_idioma );

    //$tpl_destinos->assign("url_base_hoteles", $url_base_hoteles );

    //Pido los destinos que esta en la base de datos
    $destinos = get_destinos($id_cadena, $id_idioma);

    //////////////////////////////////////////////////////////////////
    //Lista de las zonas en el mapa

    foreach ($destinos as $destino) {

        $tpl_destinos->newBlock("zona");

        //$tpl_destinos->assign("url_base_hoteles", $url_base_hoteles );

        $tpl_destinos->assign( "nombre_zona", $destino['nombre']);
        $tpl_destinos->assign( "href", $destino['id']);
        $tpl_destinos->assign( "imagen", $destino['imagen']);

        $destinos_coordenadas = get_destinos_coordenadas($destino['id']);

        foreach ($destinos_coordenadas as $coordenadas) {
            $tpl_destinos->newBlock("zona_mapa");
            $tpl_destinos->assign( "coordenadas", $coordenadas['coordenadas']);
            $tpl_destinos->assign( "href", $coordenadas['id_destino']);

        }


    }

    //////////////////////////////////////////////////////////////////////



}else {

    $tpl_destinos = new TemplatePower("plantillas/seccion_zonas_paises.html", T_BYFILE);
    $tpl_destinos->prepare();

    $tpl_destinos->assignGlobal("url_base_hoteles", $url_base_hoteles );


    //Pido los destinos que esta en la base de datos
    $paises = get_destinos_paises($id_cadena,$id_destino,$id_idioma);


        //Si Hay mas de un hotel pinto el filtro de todos los paises 
        if (count($paises) > 1){
            $tpl_destinos->newBlock("pais_todos");
            $tpl_destinos->assign("todo_hoteles",LANG_ACTIVIDADES_TODOS);
        }


    //////////////////////////////////////////////////////////////////
    //Lista de los paises 

    foreach ($paises as $pais) {

        $tpl_destinos->newBlock("pais");
        $tpl_destinos->assign( "nombre_pais", $pais['nombre']);
        $tpl_destinos->assign( "id_pais", $pais['id_pais']);


        $hoteles_paises = get_destinos_hoteles($pais['id_pais'], $id_cadena, $id_idioma);

    	// url de las fotos = contenidos/_general/riu/$id_pais/$id_hotel


        foreach ($hoteles_paises as $hoteles) {
            $tpl_destinos->newBlock("hotel");

            $tpl_destinos->assign( "id_cadena", $id_cadena);


            //Nombre e id del pais - esto sera necesario para isotope
            $tpl_destinos->assign( "id_pais", $pais['id_pais']);
            $tpl_destinos->assign( "nombre_pais", $pais['nombre']);

            $tpl_destinos->assign( "filtro_isotope", 'filtro_'.$pais['id_pais']);

            //Resto de datos del hotel
            $tpl_destinos->assign( "nombre_hotel", $hoteles['nombre']);
            $tpl_destinos->assign( "region_hotel", $hoteles['region']);

            $tpl_destinos->assign( "id_hotel", $hoteles['id']);

            $tpl_destinos->assign( "categoria_hotel", $hoteles['categoria']);

            $tpl_destinos->assign( "regimen", $hoteles['regimen']);

            $tpl_destinos->assign( "imagen_hotel", $hoteles['imagen']);

            //En el caso de que no tengamos el telefono no muestra el icono del telefono
            if (  ($hoteles['telefono'] != '') ||  ($hoteles['telefono'] != NULL)  ){

                $tpl_destinos->assign("bloque_telefono", '<img src="../../../contenido_proyectos/pacoche/_general/iconos/iconos_svg/telefono.svg">');
            }

            $tpl_destinos->assign( "telefono_hotel", $hoteles['telefono']);
            /*$tpl_destinos->assign( "fax_hotel", $hoteles['fax']);*/
            $tpl_destinos->assign( "email_hotel", $hoteles['email']);

            $tpl_destinos->assign( "informacion_hotel", $hoteles['informacion']);
            $tpl_destinos->assign("ver_lugar", LANG_DESTINOS_VER_LUGAR);

            if ($hoteles['id_lugar'] == '0' || $hoteles['id_lugar'] == ''){

                $tpl_destinos->assign("ocultar_ver_zona", "visibility_hidden");

            }else{

                $tpl_destinos->assign( "id_lugar", $hoteles['id_lugar']);
                
            }

            



        }
    }

//////////////////////////////////////////////////////////////////////

}

//El valor que se pinta
$datos['datos'] = $tpl_destinos->getOutputContent();

echo json_encode($datos);
