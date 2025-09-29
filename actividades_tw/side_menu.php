<?php

$tpl_menu = new TemplatePower("templates/side_menu.html", T_BYFILE);
$tpl_menu->prepare();


if (!isset($_GET['page'])) {
    $tpl_menu->assign('active_day', 'bg-gray-400');
} else {
    switch ($_GET['page']) {
        case 'day':
            $tpl_menu->assign('active_day', 'bg-gray-400');
            break;
        case 'week':
            $tpl_menu->assign('active_week', 'bg-gray-400');
            break;
        case 'weather':
            $tpl_menu->assign('active_weather', 'bg-gray-400');
            break;
        default:
            $tpl_menu->assign('active_day', 'bg-gray-400');
            break;
    }
}


$idiomas = get_idiomas();


foreach ($idiomas as $key => $idioma) {
    $tpl_menu->newBlock("idiomas");

    if ($_SESSION['idioma'] != trim($idioma['id_idioma'])) {
        $tpl_menu->assign("opacity", "opacity-50");
    }

    $tpl_menu->assign("id_idioma", $idioma['id_idioma']);
    $tpl_menu->assign("nombre", $idioma['nombre']);
    $tpl_menu->assign("idioma_iso", $idioma['idioma_iso']);
}
