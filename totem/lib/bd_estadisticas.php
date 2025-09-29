<?php

    if(isset($_GET['numero_fila'])){

        $numero_fila = $_GET['numero_fila'];

        $config = parse_ini_file( '../../../../config/config.ini', true);

        $host = $config['database']['host'];
        $bd_local = $config['database']['db_local'];
        $usuario = $config['database']['user'];
        $pass = $config['database']['pwd'];

        $usuario_alemania = $config['database_alemania']['user'];
        $pass_alemania = $config['database_alemania']['pwd'];
        $host_alemania = $config['database_alemania']['host'];

        $url_directorio = '/var/www/html/twistic/bd_estadisticas/';
        $url_archivo = $url_directorio.$bd_local.'.sql';

        if(!file_exists($url_directorio)){
            mkdir($url_directorio, 0777);
        }

        if(file_exists($url_archivo)){
            chmod($url_archivo, 0777);
            unlink($url_archivo);
        }

        chmod($url_directorio, 0777);

        $command = 'mysqldump --skip-add-drop-table --skip-tz-utc --no-create-info -h' .$host .' -u' .$usuario .' -p' .$pass .' ' .$bd_local .' log --where="id > '.$numero_fila.'" > ' .$url_archivo;
        exec($command,$output=array(),$worked);

        switch($worked){
            case 0:

                $command_import = 'mysql -h '.$host_alemania.' -u '.$usuario_alemania.' -p'.$pass_alemania.' ' . $bd_local . ' < ' .$url_archivo;
                exec($command_import,$output=array(),$worked_import);

                    switch($worked_import){
                        case 0:
                            echo 'ok';
                            break;
                        case 1:
                            echo 'error';
                            break;
                        case 2:
                            echo 'error';
                            break;
                    }

                    return;

                break;
            case 1:
                echo 'error';
                break;
            case 2:
                echo 'error';
                break;
        }

        return;

    }else{
        echo 'error';
        return;
    }

?>
