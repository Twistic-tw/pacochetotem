<?php
function crear_imagen_piscina($imagen, $id_centro)
{

    unlink('../../../contenido_proyectos/vistaflor/centro_'.$id_centro.'/banner_footer/final_'.$imagen);

    $imagen_final = '../../../contenido_proyectos/vistaflor/centro_'.$id_centro.'/banner_footer/'.$imagen;

    $img = @imagecreatefromjpeg($imagen_final);
// Comun
    $white_color = imagecolorallocate($img, 255, 255, 255);
    $font_path = 'css/fonts/open.ttf';
    $size=12;
    $angle=0;

    $piscinas = get_piscinas();

    foreach ($piscinas as $piscina){

        $ph= $piscina['ph'];
        $temp= $piscina['temperatura'];
        $cloro= $piscina['cloro'];
        $left= $piscina['left'];
        $top= $piscina['top'];

        imagettftext($img, $size,$angle,$left,$top, $white_color, $font_path, $cloro);
        imagettftext($img, $size,$angle,$left,$top+24, $white_color, $font_path, $ph);
        imagettftext($img, $size,$angle,$left,$top+48, $white_color, $font_path, $temp);

    }

    imagejpeg($img, '../../../contenido_proyectos/vistaflor/centro_'.$id_centro.'/banner_footer/final_'.$imagen, 100);


}


?>