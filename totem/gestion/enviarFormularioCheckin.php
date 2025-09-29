<?php

if ($_POST['tratamiento']=="")
    $_POST['tratamiento'] = "Cliente"; //fuerzo a tratamiento para que cuente

$input_llenos = 0;

registrarLog("cheking", "realizado");

foreach ($_POST as $values) { //compruebo que todos los valores tienen contenido
    if ($values != "")
        $input_llenos++;
}

if ($input_llenos == count($_POST)) {
    inserta_cliente_checkin($_POST);
    echo"<p>Enhorabuena ".$_POST['tratamiento']." ".$_POST['apellidos']."</p>
                <p>Su checking ha sido realizado con éxito.</p><br/>
                <p>Pase por recepción a las 13:15h con su pasaporte para finalizar el proceso y recoger su llave.</p><br/>
                <p>Gracias por usar este servicio y bienvenido a Hoteles Dunas.</p><br/>
                <div class='ancho50_respuesta'> 73 </div>
                <div class='ancho50_respuesta'> <img src='img/qr.png'> </div>
                <div class='clearAll'></div>";
}else{
    echo "Debe rellenar todos los campos para realizar el check-in";
}

?>
