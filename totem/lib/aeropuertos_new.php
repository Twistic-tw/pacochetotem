<?php

    // $url = "https://es.airports-worldwide.info/aeropuerto/LPA/llegadas/Llegadas_Aeropuerto_de_Gran_Canaria";

    $url = $_GET['url_aeropuerto'];

    $header = array('Content-Type:application/json');
    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    
    if($result === false)
    {
        echo 'Página no disponible: ' . curl_error($ch);
        die;
    }

    curl_close($ch);

    $result = str_replace("</head>", '<link href="../css/css_aeropuertos.css?v='.date('Ymdhis').'" rel="stylesheet"></head>', $result);
    $result = str_replace("<a ", '<ab ', $result);
    $result = str_replace(".js", '.js2v', $result);
    $result = str_replace("retardado", 'retrasado', $result);
    $result = str_replace("Retardado", 'Retrasado', $result);

    // $result = replace_between($result, '<head>', "</head>", '');
    // $result = str_replace("</head>", '<link href="style.css?v='.date('Ymdhis').'" rel="stylesheet"></head>', $result);
    // $result = replace_between($result, '</head>', "<table class='timetable'>", '');
    // $result = replace_between($result, '<footer>', "</footer>", '');
    // $result = replace_between($result, '</footer>', "</html>", '');



    // $result_table = get_string_between($result, "<table class='timetable'>", "</table>");
    
    // $result = '<html><head><link href="style.css?v='.date('Ymdhis').'" rel="stylesheet"></head><body>';
    // $result .= "<table class='timetable'>".$result_table."</table>";
    // $result .= "</body></html>";

    echo $result;

    return;

    function replace_between($str, $needle_start, $needle_end, $replacement) {
        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);
    
        $pos = strpos($str, $needle_end, $start);
        $end = $start === false ? strlen($str) : $pos;
    
        return substr_replace($str,$replacement,  $start, $end - $start);
    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

?>