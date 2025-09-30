<?php

$tpl_portada = new TemplatePower("plantillas/pre_checkin.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion', 'Pre Check-In');

// 1901 => Gregory
// 1902 => Villas
// 1903 => Mirador
// 1904 => Maspalomas

$array_url_iframe = ['1901' => 'https://kiosk.newhotelcloud.com/#/welcome?ht=5A38F47D15E7441BB6FA21CD0DE53F64',
    '1904' => 'https://kiosk.newhotelcloud.com/#/welcome?ht=881C448E68C04E7E8D942D18F91A36B4',
    '1902' => 'https://kiosk.newhotelcloud.com/#/welcome?ht=B585E91DEBA349158CEA5ECF42889B77',
    '1903' => 'https://kiosk.newhotelcloud.com/#/welcome?ht=33133D8F763F46B295A443DC1FB79FF3'];

$tpl_portada->assign('url_iframe', $array_url_iframe[$_SESSION['id_centro']]);


$tpl_portada->printToScreen();

?>