<?php

$datos = "";
$html = "";
$id_subcategoria = null;
$idioma = $_SESSION['idioma'];

if($idioma > 3){
    $idioma = 2;
}

$moneda = "";

$imagen_promo = null;
if (isset($_GET['id_carta'])) {


    $texto_cabecera = null;


    /* Parche para la imagen de la promo */
    if($_GET['id_carta'] == 3){
        if(date('N') == 5){
            if(file_exists('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg')){
                $fecha_imagen_promo = date("YmdHis", filectime('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg'));
                $imagen_promo = '<img style="width: 100%; height: auto" src="https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg?v='.$fecha_imagen_promo.'">';
            }
        }
    }
    /* Fin del parche para las imagen de la promo */

    /* Promo Pili Pili */
    $array_dias_pilipili = ['2021-05-07','2021-05-08','2021-05-09'];
    if($_SESSION['id_centro'] == 3602 && $_GET['id_carta'] == 4){
        if(in_array(date('Y-m-d'),$array_dias_pilipili) && file_exists('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/oferta-burger-pilipili.jpg')){
            $fecha_imagen_promo = date("YmdHis", filectime('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/oferta-burger-pilipili.jpg'));
            $imagen_promo = '<img style="width: 100%; height: auto" src="https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/oferta-burger-pilipili.jpg?v='.$fecha_imagen_promo.'">';
        }
    }



    $datos_cartas = get_datos_cartas($_GET['id_carta']);

    $class_nombre = $datos_cartas[0]['class_nombre'];
    $class_descripcion = $datos_cartas[0]['class_descripcion'];
    $class_denominacion = $datos_cartas[0]['class_denominacion'];
    $class_capacidad_precio= $datos_cartas[0]['class_capacidad_precio'];
    $class_suplemento = $datos_cartas[0]['class_suplemento'];
    $class_accordion = $datos_cartas[0]['class_accordion'];
    $fondo_carta = $datos_cartas[0]['fondo_carta'];
    $img_cabecera = $datos_cartas[0]['cabecera'];

    if(file_exists('../../../contenido_proyectos/dunas/_general/fondos_cartas_dinamicas/'.$fondo_carta)){
        $fecha_imagen = date("YmdHis", filectime('../../../contenido_proyectos/dunas/_general/fondos_cartas_dinamicas/'.$fondo_carta));
        $url_imagen_fondo = 'https://view.twisticdigital.com/contenido_proyectos/dunas/_general/fondos_cartas_dinamicas/'.$fondo_carta.'?v='.$fecha_imagen;
        $style_background = 'background-image: url("'.$url_imagen_fondo.'")';
    }


    if(file_exists('../../../contenido_proyectos/dunas/_general/logos_cartas_dinamicas/'.$img_cabecera) && $img_cabecera){
        $fecha_imagen = date("YmdHis", filectime('../../../contenido_proyectos/dunas/_general/logos_cartas_dinamicas/'.$img_cabecera));
        $url_imagen_logo = 'https://view.twisticdigital.com/contenido_proyectos/dunas/_general/logos_cartas_dinamicas/'.$img_cabecera.'?v='.$fecha_imagen;
        $div_cabecera = '<div class="logo_cabecera_carta"><img src="'.$url_imagen_logo.'" /></div>';
    }


    $categorias = get_categorias_carta($_GET['id_carta'], $idioma);
    $suplementos = get_suplementos_carta($_GET['id_carta'], $idioma);
    //echo "<pre>";
    //print_r($categorias);


    /* Inicio de la carta */
    $html .= "<div class='content-general-carta-dinamica' style='".$style_background."'>";
    if($imagen_promo){
        $html .= $imagen_promo;
    }

    if($div_cabecera){
        $html .= $div_cabecera;
    }

    if($texto_cabecera){
        $html .= $texto_cabecera;
    }

    $class_extra_active = null;
    $class_extra_display_block = null;
    $numero_categoria = 0;

    foreach ($categorias as $categoria) {
        $datos = get_carta_dinamica($categoria['id_categoria'], $idioma);
        //echo "<pre>";
        //print_r($datos);

        $numero_categoria++;
        if($_GET['id_carta'] == 12 && $_SESSION['id_centro'] == 1401 && date('N') == $numero_categoria){
            $class_extra_active = 'active';
            $class_extra_display_block = ' display: block';
        }else{
            $class_extra_active = null;
            $class_extra_display_block = null;
        }

        $html .= '<button id="" class="accordion-new '.$class_accordion.' '.$class_extra_active.'">'.$categoria['nombre'].'</button>
                  <div class="panel" style="'.$class_extra_display_block.'">';
        foreach ($datos as $item) {

            if ($id_subcategoria != $item['id_subcategoria'] && $item['id_subcategoria'] > 0) {
                $id_subcategoria = $item['id_subcategoria'];
                $subcategoria = get_subcategorias_carta($item['id_subcategoria'], $idioma);
                $html .= '<div class="subcategoria_cartas">'.$subcategoria[0]['nombre'].'</div>';
            }
//            $html .= '
//                      <div class="divTable">
//                        <div class="divTableRow">
//                            <div class="divTableCell css_menu_tamano_fijo">
//                                <span class="css_menu_titulo">'.$item['nombre_articulo'].'</span>
//                                <span class="css_menu_subtitulo">'.$item['descripcion'].'.</span>
//                                <span class="css_menu_subtitulo">'.$item['denominacion_origen'].'</span>
//                            </div>';

            //    $class_nombre = $datos_cartas[0]['class_nombre'];
            //    $class_descripcion = $datos_cartas[0]['class_descripcion'];
            //    $class_denominacion = $datos_cartas[0]['class_denominacion'];
            //    $class_capacidad_precio= $datos_cartas[0]['class_capacidad_precio'];
            //    $class_suplemento = $datos_cartas[0]['class_suplemento'];
            //    $fondo_carta = $datos_cartas[0]['fondo_carta'];


            if($item['infantil'] == 1){
                $html_logo_infantil = '<img class="logo-panchi-carta" src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/iconos/panchi.png" />';
            }else{
                $html_logo_infantil = null;
            }


//            $html .= '<div class="divTable"><div class="divTableRow"><div class="content-general-plato-carta">
//    <div class="carta-nombre-superior '.$class_nombre.'">'.$html_logo_infantil.$item['nombre_articulo'].' '.$item['denominacion_origen'].'</div>';

            $html .= '<div class="divTable" data-id_articulo="'.$item['id_articulo'].'"><div class="divTableRow"><div class="content-general-plato-carta">
        <div class="carta-nombre-superior '.$class_nombre.'">'.$html_logo_infantil.$item['nombre_articulo'].'</div>';

            if($item['denominacion_origen']){
                $html .= '<div class="carta-denominacion-superior '.$class_denominacion.'">'.$item['denominacion_origen'].'</div>';
            }

            $html .= '<div class="carta-nombre-inferior '.$class_descripcion.'">'.$item['descripcion'].'</div>';

            if($item['uva']){
                $html .= '<div class="carta-nombre-inferior color-v-dunas '.$class_descripcion.'">'.$item['uva'].'</div>';
            }

            foreach($item['capacidad_precio'] as $capacidad_precio){

                if($capacidad_precio['precio']){
                    $p_final = trim($capacidad_precio['precio']);
                    $p_final = str_replace('Yes','✓',$p_final);
                    $p_final = str_replace('yes','✓',$p_final);
                    $contenido_precio_final = $p_final.$moneda;
                }else{
                    $contenido_precio_final = null;
                }


                $html .= '<div class="carta-precio-dinamico '.$class_capacidad_precio.'"><span class="css_menu_precio_capacidad">'.$capacidad_precio['capacidad'].'</span>'.$contenido_precio_final.'<span class="css_menu_precio_descuento">'.$capacidad_precio['precio_descuento'].'</span></div>';
            }

            //AGREGAR SUPLEMENTOS

            $html .= '</div>';



            if($suplementos[$item['id_articulo']]){

                foreach($suplementos[$item['id_articulo']] AS $datos_suplementos){

                    $html .= '<div class="suplementos_carta_dinamica '.$class_suplemento.'">* '.$datos_suplementos['nombre_suplemento'].' '.$datos_suplementos['descripcion_suplemento'].'</div>';
                    $html .= '<div class="suplementos_carta_dinamica '.$class_suplemento.'">'.$datos_suplementos['capacidad'].' + '.$datos_suplementos['precio'].$moneda.'</div>';

                }
            }


            $html .= '</div>
                      </div>
                      <div style="width: 100%">
                         <div class="css_separador_cartas"></div>
                      </div>';
        }
        $html .= '</div>';
    }

    /* Cerramos el content */
    $html .= '</div>';

} else {
    $html = "No existe la carta";
}

echo json_encode($html);
