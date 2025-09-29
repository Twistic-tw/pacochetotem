<?php

$id_idioma = $_SESSION['idioma'];

$tipo = $_GET['tipo'];

$id_lugar = $_GET['lugar'];

$id_cadena = $_SESSION['id_cadena'];

$url_base = $_SESSION["url_comun"];

$url_base_lugares = $url_base . 'destinos/lugares/';


switch ($tipo) {

    //Info General
    case '1':
        
        $tpl_lugar = new TemplatePower("plantillas/seccion_lugar_info_solo.html", T_BYFILE);
        $tpl_lugar->prepare();

        $tpl_lugar->assignGlobal("url_base_lugares", $url_base_lugares );

        $tpl_lugar -> assign('id_lugar', $id_lugar);

        $datos_lugar = obtener_info_general_lugar($id_lugar, $idioma);

        $tpl_lugar->assign("imagen-fondo", $datos_lugar['0']['fondo']);
        $tpl_lugar->assign("imagen_arriba", $url_base_lugares.$datos_lugar['0']['id_lugar'].'/'.$datos_lugar['0']['imagen']);


        $tpl_lugar->assign("cerrar", LANG_CONOCE_ATRAS);

        $tpl_lugar->assign("info_label", LANG_LUGARES_INFO_GENERAL);
        $tpl_lugar->assign("interes_label", LANG_LUGARES_SITIOS_INTERES);
        $tpl_lugar->assign("galeria_label", LANG_CONOCE_GALERIA);
        $tpl_lugar->assign("que_hacer_label", LANG_CONOCE_QUEHACER);

        $tpl_lugar->assign("datos_pais", LANG_LUGARES_PAIS);
        $tpl_lugar->assign("demografia", LANG_LUGARES_DEMOGRAFIA);
        $tpl_lugar->assign("info_util", LANG_LUGARES_INFO_UTIL);
        $tpl_lugar->assign("datos_geo", LANG_LUGARES_LOCAL_GEO);

        //Variables estaticas de info general (los titulos)

        $tpl_lugar->assign("pais",LANG_LUGARES_INFO_PAIS);
        $tpl_lugar->assign("zona_horaria",LANG_LUGARES_INFO_ZONA_HORARIA);
        $tpl_lugar->assign("capital",LANG_LUGARES_INFO_CAPITAL);
        $tpl_lugar->assign("idiomas",LANG_LUGARES_INFO_IDIOMAS);

        $tpl_lugar->assign("superficie",LANG_LUGARES_INFO_SUPERFICIE);
        $tpl_lugar->assign("pto_alto",LANG_LUGARES_INFO_PUNTO_ALTO);
        $tpl_lugar->assign("t_verano",LANG_LUGARES_INFO_C_VERANO);
        $tpl_lugar->assign("t_invierno",LANG_LUGARES_INFO_C_INVIERNO);

        $tpl_lugar->assign("poblacion",LANG_LUGARES_INFO_POBLACION);
        $tpl_lugar->assign("densidad",LANG_LUGARES_INFO_DENSIDAD);
        $tpl_lugar->assign("gentilicio",LANG_LUGARES_INFO_GENTILICIO);

        $tpl_lugar->assign("moneda",LANG_LUGARES_INFO_MONEDA);
        $tpl_lugar->assign("electricidad",LANG_LUGARES_INFO_ELECTRICIDAD);
        $tpl_lugar->assign("enchufe",LANG_LUGARES_INFO_ENCHUFE);
        $tpl_lugar->assign("vacunas",LANG_LUGARES_INFO_VACUNAS);

        //Datos de la base de datos
        $tpl_lugar->assign("pais_dato",$datos_lugar['0']['pais']);
        $tpl_lugar->assign("zona_horaria_dato",$datos_lugar['0']['zona_horaria']);
        $tpl_lugar->assign("capital_dato",$datos_lugar['0']['capital']);
        $tpl_lugar->assign("idiomas_dato",$datos_lugar['0']['idiomas']);

        $tpl_lugar->assign("superficie_dato",$datos_lugar['0']['superficie']);
        $tpl_lugar->assign("pto_alto_dato",$datos_lugar['0']['punto_mas_alto']);
        $tpl_lugar->assign("t_verano_dato",$datos_lugar['0']['temperatura_verano']);
        $tpl_lugar->assign("t_invierno_dato",$datos_lugar['0']['temperatura_invierno']);

        $tpl_lugar->assign("poblacion_dato",$datos_lugar['0']['poblacion']);
        $tpl_lugar->assign("densidad_dato",$datos_lugar['0']['densidad_poblacion']);
        $tpl_lugar->assign("gentilicio_dato",$datos_lugar['0']['gentilicio']);

        $tpl_lugar->assign("moneda_dato",$datos_lugar['0']['moneda']);
        $tpl_lugar->assign("electricidad_dato",$datos_lugar['0']['electricidad']);
        $tpl_lugar->assign("enchufe_dato",$datos_lugar['0']['enchufe']);
        $tpl_lugar->assign("vacunas_dato",$datos_lugar['0']['vacunas']);
        $tpl_lugar->assign("bandera_pais",$datos_lugar['0']['bandera_pais']);

        $tpl_lugar->assign("idioma", $idioma );

        break;

    //Sitios de interes    
    case '2':
    case '4':
    case '5':
        $tpl_lugar = new TemplatePower("plantillas/seccion_lugar_interes_solo.html", T_BYFILE);
        $tpl_lugar->prepare();

        $tpl_lugar->assignGlobal("url_base_lugares", $url_base_lugares );

        $tpl_lugar -> assign('id_lugar', $id_lugar);

        if ($tipo == '2'){
            $tipo_info= '0';
        }else{
            $tipo_info= '1';

        }

        if($tipo =='5'){$tipo_info= '2';}

        //Muestro los que son solo para el id 99947 (dunas)
//        $datos_lugar_solo = obtener_sitios_lugar('99947', $idioma, $tipo_info, $id_cadena);
//
//        foreach ($datos_lugar_solo as $sitio_interes_solo) {
//
//            $tpl_lugar->newBlock("sitio");
//            $tpl_lugar -> assign('id_lugar', $id_lugar);
//
//            foreach ($sitio_interes_solo as $sitio) {
//
//                $tpl_lugar-> assign('tipo',$sitio_interes_solo['nombre_clasificacion']);
//                $tpl_lugar-> assign('icono',$sitio_interes_solo['icono']);
//                $tpl_lugar-> assign('color',$sitio_interes_solo['color']);
//
//                $tpl_lugar-> assign('foto',$sitio_interes_solo['foto']);
//                $tpl_lugar-> assign('nombre',$sitio_interes_solo['nombre']);
//                $tpl_lugar-> assign('email',$sitio_interes_solo['email']);
//                $tpl_lugar-> assign('web',$sitio_interes_solo['web']);
//                $tpl_lugar-> assign('zona',$sitio_interes_solo['zona']);
//                $tpl_lugar-> assign('descripicion',$sitio_interes_solo['descripicion']);
//
//            }
//
//        }


        //El 0 determina que es un sitio de interes
        $datos_lugar = obtener_sitios_lugar($id_lugar, $idioma, $tipo_info, $id_cadena);

        foreach ($datos_lugar as $sitio_interes) {

            $tpl_lugar->newBlock("sitio");
            $tpl_lugar -> assign('id_lugar', $id_lugar);

            foreach ($sitio_interes as $sitio) {
                
                $tpl_lugar-> assign('tipo',$sitio_interes['nombre_clasificacion']);
                $tpl_lugar-> assign('icono',$sitio_interes['icono']);
                $tpl_lugar-> assign('color',$sitio_interes['color']);

                $tpl_lugar-> assign('foto',$sitio_interes['foto']);
                $tpl_lugar-> assign('nombre',$sitio_interes['nombre']);
                $tpl_lugar-> assign('email',$sitio_interes['email']);
                $tpl_lugar-> assign('web',$sitio_interes['web']);
                $tpl_lugar-> assign('zona',$sitio_interes['zona']);
                $tpl_lugar-> assign('descripicion',$sitio_interes['descripicion']);

            }

        }

        break;

    //Galeria
    case '3':
            //Cambiar por el de galeria
        $tpl_lugar = new TemplatePower("plantillas/seccion_lugar_galeria_solo.html", T_BYFILE);
        $tpl_lugar->prepare();

         $dirPath = $url_base_lugares.$id_lugar."/galeria/";
         
         $n_item=1; //Variable que enumera mis fotos para su posterior uso en la galeria

        $tpl_lugar->newBlock("blq_galeria");
        $tpl_lugar->newBlock("blq_galeria_full");

         $numero_imagenes = 0;

        if (is_dir($dirPath) )
        {
            $dirHandler = opendir($dirPath);
            while ( $file = readdir($dirHandler) )
            {
                if( is_file($dirPath.$file) && $file != "." && $file != ".." && $file != "Thumbs.db"){
                    $rutaImg = $dirPath.$file;
                    $img_attr = getimagesize ( $rutaImg );

                    if ( $img_attr[0] + $img_attr[1] <= 1000 )
                    {
                        continue;
                    }

                    $numero_imagenes++;

                    $size_class="";
                    $h="142";
                    $w="142";
                    if ( rand ( 0 , 3 ) == 1){
                        if( $img_attr[0] > $img_attr[1]*2){   //2xMas ancho que alto
                            $size_class="width2";     
                            $w="284";
                            $h="142";
                        }
                        else if($img_attr[1] > $img_attr[0]*2){ //2xMas alto que ancho
                            $size_class="height2";   
                            $w="142";
                            $h="284";
                        }
                        else if($img_attr[1]>300){ //se acota el rango 0,7 para que aparezcan mas fotos grandes
                            $size_class="height2 width2";  //Caso normal
                            $w="284";
                            $h="284";
                        }
                    }
                    //Seccion para el visor isotope
                    $tpl_lugar->newBlock("blq_foto");
                    $tpl_lugar->assign("size_class",  $size_class);
                    $tpl_lugar->assign("h",  $h);
                    $tpl_lugar->assign("w",  $w);
                    $tpl_lugar->assign("url",  utf8_encode($rutaImg));
                    $tpl_lugar->assign("n_item",  $n_item);
                    //Seccion para el visor a pantalla completa
                    $tpl_lugar->newBlock("blq_foto_full");
                    $tpl_lugar->assign("size_class",  $size_class);
                    $tpl_lugar->assign("h",  "1920");
                    $tpl_lugar->assign("w",  "1080");
                    $tpl_lugar->assign("url",  utf8_encode($rutaImg));
                    $tpl_lugar->assign("n_item",  $n_item);
                    $n_item++;                           
                }
            }
        }

        //print_r($dirPath);

        break;

}


//El valor que se pinta
$datos['datos'] = $tpl_lugar->getOutputContent();

echo json_encode($datos);
