<?php

$tpl_chekin;
$url = "https://www.hdhotels.com/hdhotelsproxy/proxy.php";

if ( isset($_POST['cheking']) )
{
    //es un guardar, es decir, estamos en el segundo paso.
    $tpl_chekin = new TemplatePower("plantillas/pagina_checkin_confirmacion.html", T_BYFILE);
    $tpl_chekin->prepare();
    
    
    //obtenemos los datos del post y generamos el fichero XML para enviar remotamente
    $fecha = $_POST['fecha'];
    $localizador = $_POST['localizador'];
    
    $data;  //contiene los datos a enviar.
    $data = "<Reserva><Cabecera><reserva_id>" . $_POST['reserva_id'] . "</reserva_id><reservas_id>" . $_POST['reservas_id']."</reservas_id>";
    
    //enviamos el fichero.
    $ch = curl_init($url);


    $xml = "<?xml version='1.0' encoding='UTF-16'?>
    <ACTION VERSION='1.0' LANGUAGE='es' STATUS='OK' ACTION='updateHDreserva' BONO='$localizador' FECHA_LLEGADA='$fecha' DATA='$data'></ACTION>";
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);	
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ( !$error )
    {
        $datos_reservas = simplexml_load_string( utf8_encode($response ) );
    }
    else
    {
        //Se produjo un error...
        
    }
}
else
{
    //es un nuevo precheking
    
    // Inicialización del tpl
    $tpl_chekin = new TemplatePower("plantillas/pagina_checkin_paso2.html", T_BYFILE);
    $tpl_chekin->prepare();

    // Aqui va el codigo
    $localizador = $_POST['localizador_input'];
    $fecha = $_POST['fecha_checkin'];

    $resultado['datos'] = "<h1>Localizador: $localizador</h1><h2>Fecha: $fecha</h2>";

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

    if ( !$error )
    {
        $datos_reservas = simplexml_load_string( utf8_encode($response ) );
    }
    else
    {
        //Se produjo un error...
        
    }
    // Asign tpl

    $tpl_chekin->assign("checkin_title",LANG_CHECKIN_TITLE);

    $tpl_chekin->assign("razon_huesped_1","Pepe");
    $tpl_chekin->assign("nacionalidad_huesped_1","España");
    $tpl_chekin->assign("fecha_nacimiento_huesped_1","17-04-1970");
    $tpl_chekin->assign("email_huesped_1","pepe@gmail.com");

    foreach ( $datos_reservas->Huesped as $index => $huesped)
    {
        //new block. 
    }

    //generamos los bloques de las banderas
    foreach ($datos_reservas->Naciones as $nacion)
    {
        if ( $nacion->nacion == "Other" ) continue;

        $tpl_chekin->newBlock("nacion");

        if ( isset( $nacion->pais_ista ) )
        {
            $tpl_chekin->assign("nacion_value", $nacion->nacion_id);
            $tpl_chekin->assign("nacion_label",  $nacion->pais_ista);
        }
        else
        {
            $tpl_chekin->assign("nacion_value", 1);
            $tpl_chekin->assign("nacion_label",  "ESP");
        }
        $tpl_chekin->assign("nacion_nombre", $nacion->nacion);
    }
    
}   // cerramos el if de cheking-guardar

$resultado['datos'] = $tpl_chekin->getOutputContent();

echo json_encode($resultado);
exit();

?>


