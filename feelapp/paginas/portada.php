<?php 

$tpl_portada = new TemplatePower("plantillas/portada.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('fondo_principal','https://view.twisticdigital.com/contenido_proyectos/pacoche/centro_'.$_SESSION['id_centro'].'/feelapp/fondo.jpg');

$tpl_portada->assign('nombre_sostenibilidad',LANG_FEELAPP_SOSTENIBILIDAD);
$tpl_portada->assign('nombre_destinos',LANG_FEELAPP_DESTINOS);
$tpl_portada->assign('nombre_entretenimiento',LANG_FEELAPP_ENTRETENIMIENTO);

$tpl_portada->assign('restaurantes',LANG_FEELAPP_RESTAURANTES);
$tpl_portada->assign('sultan',LANG_FEELAPP_SULTAN);
$tpl_portada->assign('excursiones',LANG_FEELAPP_EXCURSIONES);
$tpl_portada->assign('guias',LANG_FEELAPP_GUIAS);
$tpl_portada->assign('spa',LANG_FEELAPP_SPA);
$tpl_portada->assign('gastronomia',LANG_FEELAPP_GASTRONOMIA);
$tpl_portada->assign('nuestros_hoteles',LANG_FEELAPP_NUESTROS_HOTELES);

$iconos_portada = null;
if($_SESSION['id_centro'] == 19010){
//para reactivar pre check in quitar el último cero 
//    $iconos_portada .= '<a data-enlace="calendario" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono css_png_portada" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/animacion_calendario.svg"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_ENTRETENIMIENTO.'</div>  </a>';
    $iconos_portada .= '<a data-url="spa" data-titulo="Spa" data-enlace="categoria-91" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/spa.png"> <div class="css_titulo_menu_hexagono">Spa</div>  </a>';
    $iconos_portada .= '<a data-url="gastronomia" data-titulo="Gastronomía" data-enlace="categoria-78" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/res.svg"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_GASTRONOMIA.'</div>  </a>';
    $iconos_portada .= '<a data-url="pre-check-in" data-titulo="Pre Check-In" data-enlace="iframe" href="#"   class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/recepcion.svg"> <div class="css_titulo_menu_hexagono">Pre Check-In</div>  </a>';
    $iconos_portada .= '<a data-url="upgrade" data-titulo="Upgrade" data-enlace="categoria-52" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/star.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_UPGRADE.'</div>  </a>';
    $iconos_portada .= '<a data-url="nuestros-hoteles" data-titulo="Nuestros hoteles" target="_blank" href="https://www.hotelesdunas.com/es/" class="menu-item item-2"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/dunas_hotel.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_NUESTROS_HOTELES.'</div>  </a>';
    $iconos_portada .= '<a data-url="room-service" data-titulo="Room Service" data-enlace="categoria-118" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/shab.png"> <div class="css_titulo_menu_hexagono" style="font-size:7px;"> '.LANG_FEELAPP_SERVICIOS_HAB.'</div>  </a>';

}else{

    if($_SESSION['id_centro'] != 19029){
        // $iconos_portada .= '<a data-url="actividades" data-titulo="Calendario de actividades" data-enlace="calendario" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono css_png_portada" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/animacion_calendario.svg"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_ENTRETENIMIENTO.'</div>  </a>';
    } else {
        $iconos_portada .= '<a data-url="upgrade" data-titulo="Upgrade" data-enlace="categoria-113" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/icon_tabaqueria_45.png"> <div class="css_titulo_menu_hexagono"> Bike Center</div>  </a>';
    }
    $iconos_portada .= '<a data-url="gastronomia" data-titulo="Gastronomía" data-enlace="categoria-78" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/res.svg"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_GASTRONOMIA.'</div>  </a>';
    // $iconos_portada .= '<a data-url="dunas-club" data-titulo="Dunas Club" target="_blank" href="https://www.hotelesdunas.com/es/dunas-club/" class="menu-item item-2"> <img style="width: 70%;margin-left: 12%;margin-top: 11%;" class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/dunas_club.png"> <div class="css_titulo_menu_hexagono"> '.LANG_VENTAJAS_EXCLUSIVAS.'</div>  </a>';
    if($_SESSION['id_centro'] != 19029){
        $iconos_portada .= '<a data-url="upgrade" data-titulo="Upgrade" data-enlace="categoria-52" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/star.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_UPGRADE.'</div>  </a>';
    } else {
        $iconos_portada .= '<a data-url="upgrade" data-titulo="Piscinas" data-enlace="categoria-42" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/iconos/iconos_svg//azules/hamaca.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_PISCINAS.'</div>  </a>';
    }
    // $iconos_portada .= '<a data-url="nuestros-hoteles" data-titulo="Nuestros hoteles" target="_blank" href="https://www.hotelesdunas.com/es/" class="menu-item item-2"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/dunas_hotel.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_NUESTROS_HOTELES.'</div>  </a>';
    $iconos_portada .= '<a data-url="nuestros-hoteles" data-titulo="Nuestras habitaciones" data-enlace="categoria-77" href="#" class="menu-item item-2 enlace-pagina"> <img style="width: 40%;margin-left: 30%;" class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/cama.png"> <div class="css_titulo_menu_hexagono"> '.LANG_NUESTRAS_HABITACIONES.'</div>  </a>';
    // $iconos_portada .= '<a data-url="mapa" data-titulo="Mapa" data-enlace="mapa" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/icon_mapahotel_45.png"> <div class="css_titulo_menu_hexagono"> '.LANG_FEELAPP_MAPA_HOTEL.'</div>  </a>';

}

if(get_programa_navidad()){
    $iconos_portada .= '<a data-titulo="programa_navidad" data-enlace="programa_navidad" href="#" class="menu-item item-2 enlace-pagina"> <img class="css_imagen_menu_hexagono" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/arbol_navidad.svg"><div class="css_titulo_menu_hexagono"> '.LANG_TEXTO_NAVIDAD.'</div>  </a>';
    $iconos_portada .= '<a data-titulo="programa_navidad" data-enlace="programa_navidad" href="#" class="menu-item item-2 enlace-pagina enlace-icono-navidad"> <img class="sombrero_navidad" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/iconos/iconos_svg/circulos/sombrero.png">
    </a>';
}

$tpl_portada->assign('iconos_portada',$iconos_portada);


$tpl_portada->assign('shab',LANG_FEELAPP_SERVICIOS_HAB);

$datos_hotel = datos_hotel();

$tpl_portada->assign('nombre_hotel',$datos_hotel['nombre']);
$tpl_portada->assign('descripcion_hotel',$datos_hotel['descripcion']);

if($_SESSION['proyecto'] == 'oasis'){
    $tpl_portada->assign('inicio_ocultar_riu','<!--');
    $tpl_portada->assign('fin_ocultar_riu','-->');
}elseif($_SESSION['proyecto'] == 'riucanarias'){
    $tpl_portada->assign('inicio_ocultar_oasis','<!--');
    $tpl_portada->assign('fin_ocultar_oasis','-->');
}

$tpl_portada->printToScreen();

?>
