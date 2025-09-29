<?php

$db = new MySQL_local();

//mysql_select_db("cuestionario");

//registrarLog("hotel", "formulario", "enviado");

$res = $_POST['texto_sugerencia'];
$idioma = $_SESSION['idioma'];
$num_habitacion = $_POST['num_habitacion'];
$consulta = "INSERT INTO `sugerencias_respuestas`( `id_idioma`, `respuesta` , `num_habitacion`) VALUES ('$idioma','$res','$num_habitacion')";

$db->consulta($consulta);


?>


