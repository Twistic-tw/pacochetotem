<?php

$db = new MySQL_local();

session_start();
$id_idioma = $_SESSION['idioma'];


registrarLog("hotel", "formulario", "enviado");

$consulta = "INSERT INTO cuestionario_respuesta (id_idioma) VALUES ($id_idioma)";
$db->consulta($consulta);

/* Selecionamos el ultimo valor insertado en la tabla cuestionario-respuestas para insertarlo en la tabla respuesta-valores */
$last_insert_cuestionario_respuesta = MySQL_local::getLastInsertedId();

$consulta = "INSERT INTO cuestionario_respuesta_contenido (id_respuesta, id_pregunta, valor)";


foreach ($_POST as $indice => $valor) {
    $values .= "('$last_insert_cuestionario_respuesta', '$indice', '$valor'), ";
}
$values = trim($values, ", ");
$consulta .= " VALUES $values";



$db->consulta($consulta);


?>


