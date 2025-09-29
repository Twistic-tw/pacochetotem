<?php

require_once '../lib/class.phpmailer.php';
require_once '../lib/class.smtp.php';


//aqui debemos procesar el envio de la imagen generada
$filteredData = substr($_POST['foto'], strpos($_POST['foto'], ",") + 1);
$email = $_POST['email'];

$centro = $_POST['id_centro'];
$idioma = $_POST['id_idioma'];
$nombre = $_POST['n'];

$nombre =  str_replace("_"," ",$nombre);

switch ($idioma) {
    case 1:
        $textoDelMail =  "Usted ha recibido una postal del Hotel ";
        break;
    case 2:
        $textoDelMail =  "You have received a postcard from hotel ";
        break;
    case 3:
        $textoDelMail = "Sie haben eine Postkarte von einem hotel ";
        break;
    default:
        $textoDelMail =  "You have received a postcard from hotel ";
        break;
}

$textoDelMail = $textoDelMail . $nombre;

//Decode the string
$unencodedData = base64_decode($filteredData);
$num = rand();

//Esto es para alemania
$filePath = "/var/www/html/twistic/postales/$centro/";

//Esto es para local
//$filePath = "../../../../contenido_proyectos/pacoche/centro_$centro/postales_creadas/";

if ( !is_dir($filePath) )
{
    mkdir($filePath, 0777);
    echo 'no entra';
}

$filePath .= "postal_$num.jpg";

//la guarda en el directorio
if (file_put_contents($filePath, $unencodedData)) {


    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;

    $mail->Username = "noreply.twisticdigital@gmail.com";
    $mail->Password = "oifvwibcbbpugiyk";
    $mail->SetFrom('noreply.twisticdigital@gmail.com', 'Twistic - Servicio de Postales');
    $mail->AddReplyTo("noreply.twisticdigital@gmail.com","Twistic - Servicio de Postales");
    $mail->Subject = "Envio de Postal";


    $mail->MsgHTML($textoDelMail);

    //Defino la dirección de correo a la que se envía el mensaje

    $mail->AddAddress($email, "");

    //asigno un archivo adjunto al mensaje
    $mail->AddAttachment($filePath);


   // $exito = $mail->Send();

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }
//
//    $intentos=0;
//
//    while ((!$exito) && ($intentos < 5))
//    {
//        sleep(1);
//        //echo $mail->ErrorInfo;
//        $exito = $mail->Send();
//        $intentos=$intentos+1;
//
//        if(!$exito)
//        {
//            $tipo_error=$mail->ErrorInfo;
//            $error=1;
//        }
//        else
//            $error=0;
//
//    }
//
}
//else {
//    //se produjo un error.
//    echo "error desconocido";
//}

exit();
?>
