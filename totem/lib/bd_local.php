<?php

session_start();
require_once __DIR__ . '/error_logging.php';


$config = parse_ini_file('../../../../config/config.ini', true);

$db_host = $config['database']['host'];
$db_user = $config['database']['user'];
$db_pass = $config['database']['pwd'];
$db_normal = $config['database']['db'];


if(isset($_GET['host'])){
    if(!file_exists('../../../../bd_local/')){
        mkdir('../../../../bd_local/', 0775, true);
        fopen("../../../../bd_local/".$_GET['bd'].".sql","w+");
    }
    exportar_bd_local($db_host,$db_user,$db_pass,$_GET['bd']);
}else{
    actualizar_archivo();
}


function exportar_bd_local($host,$user,$pass,$name,  $tables=false, $backup_name=false){

    $id_log = $_GET['log'];
    $id_rating = $_GET['rating'];

    $connect = mysql_connect($host, $user, $pass);
    mysql_select_db($name,$connect) or die('No se pudo seleccionar la base de datos 2');
    $query = 'SELECT * FROM log WHERE id > '.$id_log.'';
    //$query = 'SELECT * FROM log';
    $result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
    $query2 = 'SELECT * FROM rating WHERE id > '.$id_rating.'';
    //$query2 = 'SELECT * FROM rating';
    $result2 = mysql_query($query2) or die('Consulta fallida: ' . mysql_error());

    $file = fopen("../../../../bd_local/".$_GET['bd'].".sql", 'w+');


    if($result){
        //$log = 'INSERT INTO log VALUES ';
        $log = '';
        $cont = 0;
        while ($fila = mysql_fetch_assoc($result)) {
            if($cont == 0){
                $log = '';
                $log .= 'INSERT INTO log VALUES ';
            }else{
                $log .= '("'.$fila['id'].'","'.$fila['id_centro'].'","'.$fila['datetime'].'","'.$fila['idioma'].'","'.$fila['seccion'].'","'.$fila['subseccion'].'","'.$fila['identificador'].'","'.$fila['observaciones'].'"),';
            }
            $cont++;
            if($cont == 749){
                $log = substr($log, 0, -1);;
                $log .= ';';
                $cont = 0;
                fwrite($file, $log . PHP_EOL);
            }

        }

        $log = substr($log, 0, -1);;
        $log .= ';';
        //$cont = 0;
        fwrite($file, $log . PHP_EOL);

    }

    if($result2){

        $log = '';
        $cont = 0;
        while ($row = mysql_fetch_assoc($result2)) {
            if($cont == 0){
                $log = '';
                $log .= 'INSERT INTO rating VALUES ';
            }else{
                $log .= '("'.$row['id'].'","'.$row['valor'].'","'.$row['timestamp'].'","'.$row['objeto_evaluado'].'","'.$row['id_objeto'].'","'.$row['id_idioma'].'"),';
            }
            $cont++;
            if($cont == 749){
                $log = substr($log, 0, -1);;
                $log .= ';';
                $cont = 0;
                fwrite($file, $log . PHP_EOL);
            }

        }

        $log = substr($log, 0, -1);;
        $log .= ';';
        $cont = 0;
        fwrite($file, $log . PHP_EOL);


    }

    fclose($file);


    return;

}


function actualizar_archivo(){

    if($fecha_act = stat("../../../../bd_local/".$_GET['bd'].".sql")){
        $fecha_actualizacion = date("Y-m-d",$fecha_act['mtime']);
    }else{
        $fecha_actualizacion = 0;
    }

    $fecha_actual = date("Y-m-d");

    if($fecha_actual == $fecha_actualizacion){
        echo 'no';
        return false;
    }else{
        echo 'si';
        return true;
    }

}