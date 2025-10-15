<?php
//aqui debemos procesar el envio de la imagen generada
$filteredData = substr($_POST['foto'], strpos($_POST['foto'], ",") + 1);
$email = $_POST['email'];

registrarLog("hotel", "postal", "generada");

$centro = $_SESSION['id_centro'];
$idioma = $_SESSION['idioma'];


$nombre_del_hotel =  configuracion_totem($centro);
$nombre_del_hotel = str_replace(" ","_",$nombre_del_hotel['nombre']);


$postdata = http_build_query(
    array(
        'id_centro' => $centro,
        'id_idioma' => $idioma,
        'n' => $nombre_del_hotel,
        "email" => $_POST['email'],
        "foto" => $_POST['foto']
    )
);

$opts = array('http' =>
                  array(
                      'method'  => 'POST',
                      'header'  => 'Content-type: application/x-www-form-urlencoded',
                      'content' => $postdata
                  )
);

$context  = stream_context_create($opts);

//esto es para local
//$url = "http://localhost/twistic/contenido_proyectos/riucanarias/totem/gestion/enviarPostalFinal.php";

//esto es para alemania - OLD (servidor roto)
//$url = "https://visor.twisticdigital.com/pacoche/totem/gestion/enviarPostalFinal.php";

//Nuevo endpoint actualizado
$url = "https://postales.twisticdigital.com/postales.php";

// Activar manejo de errores
$result = @file_get_contents($url, false, $context);

// Capturar información del error HTTP
$http_response = "";
if (isset($http_response_header)) {
    $http_response = $http_response_header[0];
}


/*


//Esto es para local
//$url = "localhost/twistic/contenido_proyectos/riucanarias/totem/index.php?gestion=enviarPostalFinal&id_centro=$centro&id_idioma=$idioma&n=$nombre_del_hotel";

$post_field = array( "email" => $_POST['email'],
                     "foto" => $_POST['foto']);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

*/

// Mostrar resultado con información de diagnóstico
if ($result === false) {
    echo "ERROR: No se pudo conectar al servidor de postales. ";
    echo "HTTP Response: " . $http_response . " ";
    echo "URL: " . $url;
} else if (empty($result)) {
    echo "ERROR: El servidor de postales no devolvió respuesta. ";
    echo "HTTP Response: " . $http_response;
} else {
    echo $result;
}

exit();

?>
