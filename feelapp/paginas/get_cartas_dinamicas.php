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

    /* Parche para la imagen de la promo */
    if($_GET['id_carta'] == 3){
        if(date('N') == 5){
            if(file_exists('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg')){
                $fecha_imagen_promo = date("YmdHis", filectime('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg'));
                $imagen_promo = '<img style="width: 100%; height: auto" src="https://view.twisticdigital.com/roommate/contenido/centro_'.$_SESSION['id_centro'].'/promos_cartas/promo_villa_del_conde_viernes.jpg?v='.$fecha_imagen_promo.'">';
            }
        }
    }
    /* Fin del parche para las imagen de la promo */

    $categorias = get_categorias_carta($_GET['id_carta'], $idioma);
    $suplementos = get_suplementos_carta($_GET['id_carta'], $idioma);


    if($imagen_promo){
        $html .= $imagen_promo;
    }

    foreach ($categorias as $categoria) {
        $datos = get_carta_dinamica($categoria['id_categoria'], $idioma);

        //$datos = get_carta_dinamica($categoria['id_categoria'], $idioma);

//        echo "<pre>";
//        print_r($datos);die;
        $html .= '<button id="" class="accordion-new">'.$categoria['nombre'].'</button>
                  <div class="panel">';
        foreach ($datos as $item) {

            if ($id_subcategoria != $item['id_subcategoria'] && $item['id_subcategoria'] > 0) {
                $id_subcategoria = $item['id_subcategoria'];

                $subcategoria = get_subcategorias_carta($item['id_subcategoria'], $idioma);

                $html .= '<div class="subcategoria_cartas">'.$subcategoria[0]['nombre'].'</div>';
            }
            $html .= '
                      <div class="divTable" data-id_articulo="'.$item['id_articulo'].'">
                        <div class="divTableRow">
                            <div class="divTableCell css_menu_tamano_fijo">
                                <span class="css_menu_titulo">'.$item['nombre_articulo'].'</span> 
                                <span class="css_menu_subtitulo">'.$item['descripcion'].'</span>
                                <span class="css_menu_subtitulo">'.$item['uva'].'</span> 
                                <span class="css_menu_subtitulo">'.$item['denominacion_origen'].'</span> 
                            </div>';


//            if ($item['capacidad'] != '') {
//                $html .= '<div class="divTableCell css_menu_precio">'.$item['capacidad'].'</div>';
//            }
//
//            $html .= '<div class="divTableCell css_menu_precio">'.$item['precio'].$moneda.'</div>';


            $contenido_capacidad = null;
            $contenido_precio = null;
            $contenido_descuento = null;

            if($item['capacidad_precio']){
                foreach($item['capacidad_precio'] as $capacidad_precio){
                    //                            [capacidad] => 75cl
                    //                            [precio] => 25,50
                    //                            [precio_descuento] =>
                    $contenido_capacidad .= '<div>'.$capacidad_precio['capacidad'].'</div>';
                    if($capacidad_precio['precio']){
                        $contenido_precio .= '<div>'.trim($capacidad_precio['precio']).$moneda.'</div>';
                    }
                    $contenido_descuento .= '<div>'.$capacidad_precio['precio_descuento'].'</div>';
                }
            }

            if($contenido_capacidad || $contenido_precio){
                $html .= '<div class="divTableCell css_menu_precio">'.$contenido_capacidad.'</div>';
                $html .= '<div class="divTableCell css_menu_precio">'.$contenido_precio.''.$contenido_descuento.'</span></div>';
            }

            $html .= '</div>
                      </div>';

            if($suplementos[$item['id_articulo']]){

                foreach($suplementos[$item['id_articulo']] AS $datos_suplementos){

                    $html .= '
                      <div class="divTable">
                        <div class="divTableRow">
                            <div class="divTableCell css_menu_tamano_fijo"> 
                                <span class="css_menu_subtitulo">* '.$datos_suplementos['nombre_suplemento'].'</span> 
                                <span class="css_menu_subtitulo">'.$datos_suplementos['descripcion_suplemento'].'</span> 
                            </div>';

                    if($datos_suplementos['capacidad']){
                        $html .= '<div class="divTableCell css_menu_precio">'.$datos_suplementos['capacidad'].'</div>';
                    }
                    if($datos_suplementos['precio']){
                        $html .= '<div class="divTableCell css_menu_precio">+ '.trim($datos_suplementos['precio']).$moneda.'</div>';
                    }

                    $html .= '</div>
                      </div>';

                }
            }

            $html .= '<div style="width: 100%">
                         <div class="css_separador_cartas"></div>
                      </div>';
        }
        $html .= '</div>';
    }
} else {
    $html = "No existe la carta";
}

echo json_encode($html);
