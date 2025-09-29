<?php



$url = "https://www.hdhotels.com/hdhotelsproxy/proxy.php";
$ch = curl_init($url);

$fecha = "2014-08-01";
$localizador = "prueba online";

$xml = "<?xml version='1.0' encoding='UTF-16'?>
<ACTION VERSION='1.0' STATUS='OK' ACTION='generaHDreservasXML'  BONO='$localizador' FECHA_LLEGADA='$fecha' ></ACTION>";
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);	
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo "<h1>Test de la api de HD</h1><pre>";

$t2 = simplexml_load_string( utf8_encode($response ) );
print_r($t2);


var_dump(libxml_get_errors());
exit();















$db = new MySQL();
$rec = $db->consulta("SELECT id_tiempo, json_total FROM tiempo");

echo "<pre>";
while ($tArray = $db->fetch_assoc($rec)){
//    echo "<h2>id: " . $tArray['id_tiempo'] . "</h2><pre>";
//    print_r( json_decode($tArray['json_total']));
//    echo "</pre><hr>";
}


$str_tiempo = "http://www.aemet.es/xml/municipios/localidad_" . $codLocalidad . ".xml";
    $peticion = file_get_contents($str_tiempo);
    $datos_array = json_decode(json_encode((array) simplexml_load_string($peticion)), 1);
    
    echo "<pre>";
    print_r($peticion);
    
exit();

?>
