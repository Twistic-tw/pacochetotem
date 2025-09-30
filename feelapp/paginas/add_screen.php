<?php

$tpl_portada = new TemplatePower("plantillas/add_screen.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',ADD_SCREEN);

$sistema_operativo = $_GET['sistema_operativo']; //WindowsPhone,Android,iOS
$tipo_navegador = $_GET['tipo_navegador'];

//if($_SESSION['idioma'] == 1){
//
//    if($tipo_navegador == 'Safari'){
//        $texto_add = '<div class="texto_add_screen"> 1 - Botón Compartir <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/agregar.svg" alt=""> en la (parte superior o inferior) del navegador</div>
//<div class="texto_add_screen"> 2 - Desplácese (si es necesario) para encontrar el botón Agregar a la pantalla de inicio <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/plus.svg" alt=""></div>';
//    }elseif($tipo_navegador == 'Chrome'){
//        $texto_add = '<div class="texto_add_screen"> 1 - Botón más <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/menu_puntos.svg" alt=""> en la (parte superior o inferior) del navegador</div>
//<div class="texto_add_screen"> 2 - Desplácese (si es necesario) para encontrar el botón Añadir a la pantalla de inicio </div>';
//    }elseif($tipo_navegador == 'Firefox'){
//        $texto_add = '<div class="texto_add_screen"> 1 - Botón más <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/menu_puntos.svg" alt=""> en la (parte superior o inferior) del navegador</div>
//<div class="texto_add_screen"> 2 - Desplácese (si es necesario) para encontrar el botón Página y después Añadir acceso directo a la página </div>';
//    }else{
//        $texto_add = '';
//    }
//
//}else{
//
//    if($tipo_navegador == 'Safari'){
//        $texto_add = '<div class="texto_add_screen"> 1 - Share button <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/agregar.svg" alt=""> at the (top or bottom) of the browser</div>
//<div class="texto_add_screen"> 2 - Scroll (if needed) to find the Add to Home Screen button Add to Home Screen button Image <img src="https://view.twisticdigital.com/contenido/_general/feelapp/iconos/plus.svg" alt=""></div>';
//    }elseif($tipo_navegador == 'Chrome'){
//        $texto_add = '<div class="texto_add_screen"> 1 - More buttom <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/menu_puntos.svg" alt=""> at the (top or bottom) of the browser</div>
//<div class="texto_add_screen"> 2 - Scroll (if needed) to find the Add button to the home screen </div>';
//    }elseif($tipo_navegador == 'Firefox'){
//        $texto_add = '<div class="texto_add_screen"> 1 -More buttom  <img src="https://view.twisticdigital.com/twistic/contenido/_general/feelapp/iconos/menu_puntos.svg" alt=""> at the (top or bottom) of the browser</div>
//<div class="texto_add_screen"> 2 - Scroll (if needed) to find the Page button and then Add Shortcut to the page </div>';
//    }else{
//        $texto_add = '';
//    }
//
//}


if($tipo_navegador == 'Safari'){
    $texto_add = LANG_BOTON_COMPARTIR;
}elseif($tipo_navegador == 'Chrome'){
    $texto_add = LANG_BOTON_CHROME;
}elseif($tipo_navegador == 'Firefox'){
    $texto_add = LANG_BOTON_FIREFOX;
}else{
    $texto_add = '';
}

$tpl_portada->assign('texto_add',$texto_add);

$tpl_portada->printToScreen();

?>