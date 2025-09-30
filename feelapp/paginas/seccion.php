<?php

$datos_post = http_build_query(
    array(
        'id_idioma' => $_SESSION['idioma'],
        'continue' => 'ok'
    )
);

$datos_post = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $datos_post
    )
);

$datos_post  = stream_context_create($datos_post);


if($id_cont = $_GET['id_cont']){

    $url_seccion = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_seccion = explode('/',$url_seccion);
    array_pop($url_seccion);
    $url_seccion = implode("/", $url_seccion);

    $url_completa = html_entity_decode('https://'.$url_seccion.'/index.php?pagina=contenidos&id_contenido='.$id_cont.'');

    $página_seccion = file_get_contents($url_completa,false,$datos_post);
    echo $página_seccion;

}

if($id_ruta = $_GET['id_ruta']){

    $url_seccion = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_seccion = explode('/',$url_seccion);
    array_pop($url_seccion);
    $url_seccion = implode("/", $url_seccion);

    $url_completa = html_entity_decode('https://'.$url_seccion.'/index.php?pagina=rutas&id_ruta='.$id_ruta.'');

    $página_seccion = file_get_contents($url_completa,false,$datos_post);
    echo $página_seccion;

}

if($id_checkin = $_GET['id_checkin']){

    $url_seccion = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_seccion = explode('/',$url_seccion);
    array_pop($url_seccion);
    $url_seccion = implode("/", $url_seccion);

    $url_completa = html_entity_decode('https://'.$url_seccion.'/index.php?pagina=iframe');

    $página_seccion = file_get_contents($url_completa,false,$datos_post);
    echo $página_seccion;

}


if($id_newsletter = $_GET['id_newsletter']){

    $url_seccion = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_seccion = explode('/',$url_seccion);
    array_pop($url_seccion);
    $url_seccion = implode("/", $url_seccion);

    $url_completa = html_entity_decode('https://'.$url_seccion.'/index.php?pagina=newsletter');

    $página_seccion = file_get_contents($url_completa,false,$datos_post);
    echo $página_seccion;

}


if($id_guesscard = $_GET['id_guesscard']){

    $url_seccion = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_seccion = explode('/',$url_seccion);
    array_pop($url_seccion);
    $url_seccion = implode("/", $url_seccion);

    $url_completa = html_entity_decode('https://'.$url_seccion.'/index.php?pagina=panel_usuario_formulario&contenido_print=1');

    $página_seccion = file_get_contents($url_completa,false,$datos_post);
    echo $página_seccion;

}

?>