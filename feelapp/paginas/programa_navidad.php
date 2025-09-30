<?php

$tpl_navidad = new TemplatePower("plantillas/programa_navidad.html", T_BYFILE);
$tpl_navidad->prepare();

$tpl_navidad->assign('titulo_seccion',LANG_TEXTO_NAVIDAD);

$data_programa = get_programa_navidad();


foreach($data_programa as $row){

    $tpl_navidad->newBlock('bloques_navidad');
    $tpl_navidad->assign('imagen','https://view.twisticdigital.com/contenido_proyectos/dunas/_general/navidad/'.$row['imagen']);

    $array_a = [];
    foreach($row['archivo'] as $row){
        $array_a[] = end(explode('/',$row));
    }
    $lista_archivos = implode(',',$array_a);
    $tpl_navidad->assign('nombre_archivo',$lista_archivos);
    // $tpl_navidad->assign('nombre_archivo',end(explode('/',$row['archivo'])));

    // if(end(explode('.',$row['archivo'])) == 'pdf' || end(explode('.',$row['archivo'])) == 'PDF'){
    //     $tpl_navidad->assign('class','js_contenidos_masinfo_scroll_navidad_pdf');
    // }else{
        $tpl_navidad->assign('class','js_contenidos_masinfo_scroll_navidad');
    //}

    $tpl_navidad->assign('archivo',implode(',',$row['archivo']));
    $tpl_navidad->assign('texto',$row['texto']);

}$tpl_navidad->gotoBlock('_ROOT');

$tpl_navidad->printToScreen();

?>