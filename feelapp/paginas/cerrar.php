<?php

    $_COOKIE['id_reserva'] = null;
    setcookie("id_reserva", null, time()-1000);

    return;