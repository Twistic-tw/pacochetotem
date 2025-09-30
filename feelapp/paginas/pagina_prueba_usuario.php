<?php

    //datos de ejemplo
    $numero_habitacion = '706';
    $documento_identidad = '47437793V';


    // 1901 => Gregory
    // 1902 => Villas
    // 1903 => Mirador
    // 1904 => Maspalomas

    $array_id_hotel_api = ['1901' => 4, '1904' => 2, '1902' => 1, '1903' => 3];

    $id_hotel_api = $array_id_hotel_api[$_SESSION['id_centro']];


    if (($numero_habitacion && $documento_identidad) || ($identificador_usuario)) {

        //408 y 4

        $url_api = 'http://apidunas.twisticdigital.com/consultas.php';

        $datos_post = http_build_query(
            array(
                'funcion' => 'consulta_reserva',
                'datos' => array('numero_habitacion' => $numero_habitacion,
                    'documento_identidad' => $documento_identidad,
                    'id_hotel' => $id_hotel_api,
                    'identificador_usuario' => $identificador_usuario)
            )
        );

        $datos_post = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $datos_post
            )
        );

        $datos_post = stream_context_create($datos_post);

        $contenido = file_get_contents($url_api, false, $datos_post);
        $contenido = json_decode($contenido, true);


        $codigo = $contenido['codigo'];

        $respuesta = $contenido['respuesta']['FindReservationsResult'];
        $status_code = $respuesta['StatusCode'];
        $status_message = $respuesta['StatusMessage'];


//DATOS GENERALES DE LA RESERVA
//        [ReservationCode] => 9066/2020
//        [ReseCode] => 9066
//        [ReseYear] => 2020
//        [RoomCode] => 127
//        [Adults] => 2
//        [Childs] => 0
//        [Babies] => 0
//        [CheckIn] => 2020-10-24T19:00:42Z
//        [CheckOut] => 2020-10-31T12:00:00Z
//        [DeadLineDate] =>
//        [CreationDate] => 2020-09-29T00:00:00
//        [RegisterDate] => 2020-09-29T00:00:00
//        [EntityCode] => 22008
//        [EntityDescription] => WELCOMEBEDS VARIOS
//        [OperatorCode] => 999
//        [OperatorDescription] => VALORADA EN DINGUS
//        [PriceRateCode] => 99999
//        [PriceRateDescription] => TARIFA FREE
//        [RatePlanCode] => 3
//        [RatePlanDescription] => MP
//        [CurrencyCode] => EUR
//        [CurrencyDescription] => EURO
//        [ArrivalFlightCode] =>
//        [DepartureFlightCode] =>
//        [RoomTypeCode] => DOB
//        [RoomTypeOccupedCode] => DOB
//        [ComplexRatePlanOcupation] =>
//        [RoomTypeDescription] => Doble A V.Jardín
//        [RoomTypeOccupedDescription] => Doble A V.Jardín
//        [TotalPrice] =>
//        [ReservationState] => 2
//        [ConfirmationState] => 1
//        [Voucher] => Y4F39R
//        [Guests] => stdClass Object

        //$reservas contiene los datos de la reserva (respuesta de la API)

        $reservas = $respuesta['Reservations']['Reservation'];
        $nombre_cliente = $reservas['Guests']['Guest'][0]['LastName'];

        //En reserva tengo los valores que harán falta

        //Actualizo la BD con los datos de la reserva

        $id_reserva = $reservas['ReservationCode'];
        $numero_habitacion = $reservas['RoomCode'];
        $checkin_general = $reservas['CheckIn'];
        $checkout_general = $reservas['CheckOut'];
//        $numero_huespedes = $reservas['Adults'] + $reservas['Childs'] + $reservas['Babies'];
        $numero_huespedes = $reservas['Adults'] + $reservas['Childs'];
        $regimen_general = $reservas['RatePlanDescription'];

        $numero_adultos = $reservas['Adults'];
        $numero_ninos = $reservas['Childs'];
        $numero_bebes = $reservas['Babies'];


        actualiza_datos_reserva($id_reserva,$numero_habitacion,$checkout_general,$checkin_general,$numero_huespedes,$regimen_general,$numero_adultos,$numero_ninos,$numero_bebes,$nombre_cliente);

        echo'meti los datos en BD';

        exit();

        $room_code = $reservas['RoomCode'];

        $fecha_creacion = $reservas['CreationDate'];
        $fecha_registro = $reservas['RegisterDate'];

        $checkin_general = $reservas['CheckIn'];
        $checkout_general = $reservas['CheckOut'];
        //$regimen_general = $reservas['RatePlanCode'];
        $regimen_general = $reservas['RatePlanDescription'];


        $datos_reserva = $reservas['Guests']['Guest'];

        if (!$datos_reserva[0]) {
            $datos_reserva = array($datos_reserva);
        }

        if ($documento_identidad) {

            $lista_documentos = array_column($datos_reserva, 'DocumentNumber');
            $reserva_key = array_search($documento_identidad, $lista_documentos);

            $reserva_individual = $datos_reserva[$reserva_key];

        } elseif ($identificador_usuario) {

            $lista_documentos = array_column($datos_reserva, 'GuestIdentifier');
            $reserva_key = array_search($identificador_usuario, $lista_documentos);

            $reserva_individual = $datos_reserva[$reserva_key];

        } else {
            $reserva_individual = null;
        }

        // $reserva_individual contiene los datos del Huesped

        if ($reserva_individual) {

            //print_r($reserva_individual);

            if (($reserva_individual['DocumentNumber'] == $documento_identidad) || ($reserva_individual['GuestIdentifier'] == $identificador_usuario)) {

                //Agregamos al array de la reserva individual, los datos de la reserva general
                $reserva_individual['fecha_creacion'] = $fecha_creacion;
                $reserva_individual['fecha_registro'] = $fecha_registro;
                $reserva_individual['checkin_general'] = $checkin_general;
                $reserva_individual['checkout_general'] = $checkout_general;
                $reserva_individual['numero_habitacion'] = $room_code;
                $reserva_individual['regimen_general'] = $regimen_general;

                $fecha_checkout = $reserva_individual['CheckOutDate'];

                if ($checkout_general) {
                    if (date('Y-m-d') >= substr($checkout_general, 0, 10)) {
                        $respuesta = array('error_code' => 1, 'error_texto' => '', 'mensaje' => LANG_RESERVA_FECHA_CHECKOUT);
                        echo json_encode($respuesta);
                        die;
                    }
                }

                if ($fecha_checkout) {
                    if (date('Y-m-d') >= substr($fecha_checkout, 0, 10)) {
                        $respuesta = array('error_code' => 1, 'error_texto' => '', 'mensaje' => LANG_RESERVA_FECHA_CHECKOUT);
                        echo json_encode($respuesta);
                        die;
                    }
                }

                mostrar_datos_reserva($reserva_individual, $identificador_usuario);

                $idCliente = $reserva_individual['GuestIdentifier'];
                $idCliente = '4c8f7845-39b3-964b-859d-9671729ff6c7';
                $fecha_cumple = datos_cliente_dunas($id_hotel_api, $idCliente);
                $fecha_cumple = substr($fecha_cumple, 5, 5);

                if ($fecha_cumple) {
                    //Si tenemos fecha de cumpleaños

                    $fecha_actual_mes = date('m-d');

                    if ($fecha_cumple == $fecha_actual_mes) {
                        //Si la fecha de cumple es hoy
                    } else {
                        //No es el cumple
                    }

                    if (($fecha_cumple >= substr($checkin_general, 5, 5)) && ($fecha_cumple <= substr($checkout_general, 5, 5))) {
                        //El cumpleaños entra
                    } else {
                        //El cumpleaños no entra
                    }

                }

                return;

            } else {
                $respuesta = array('error_code' => 1, 'error_texto' => '', 'mensaje' => LANG_ERROR_RESERVA_HOTEL);
                echo json_encode($respuesta);
            }

        } else {
            $respuesta = array('error_code' => 1, 'error_texto' => '', 'mensaje' => LANG_ERROR_RESERVA_HOTEL);
            echo json_encode($respuesta);
        }

        die;

    } else {
        $respuesta = array('error_code' => 1, 'error_texto' => '', 'mensaje' => LANG_ERROR_FORMULARIO);
        echo json_encode($respuesta);
    }



return;


function mostrar_datos_reserva($reserva, $identificador_usuario)
{

//DATOS DEL USUARIO EN CONCRETO
//    [CountryCode] => ESP
//    [CountryISOCode] => ES
//    [CountryDescription] => ESPAÑA
//    [LoyaltyCode] => 832730
//    [FiscalNumber] => 15367545A
//    [DocumentType] => 1
//    [DocumentNumber] => 15367545A
//    [GuestCode] => 2
//    [GuestIdentifier] => 05955855-b962-314b-b36c-491d2eb37f32
//    [FirstName] => JOSEBA MIKEL
//    [LastName] => KORTABARRIA ETXEZARRAGA
//    [GuestType] => 1
//    [IsClient] => 1
//    [IsTitular] =>
//    [Age] => 58
//    [GuestState] => 2
//                    [Client] => Array
//                    (
//                        [CountryCode] => HUN
//                        [CountryISOCode] => HU
//                        [CountryDescription] => HUNGRIA
//                        [CountryAddressCode] => HUN
//                        [CountryAddressISOCode] => HU
//                        [CountryAddressDescription] => HUNGRIA
//                        [FiscalNumber] => 837174HE
//                        [ContactPhone] => +36-704561880
//                        [ContactEMail] => toroktamas74@gmail.com
//                        [DocumentType] => 1
//                        [DocumentNumber] => 837174HE
//                        [LanguageISOCode] => BG
//                        [LanguageDescription] => INGLÉS
//                        [ClientIdentifier] => a30bf3df-9122-6444-9475-7e67d58aeef5
//                        [ClientCode] => 832825
//                        [Gender] => Male
//                        )
//    [GuestCategory] => Normal
//    [CheckInDate] => 2020-10-24T00:00:00
//    [CheckOutDate] =>
//    [EntryDate] =>
//    [EntryBorderCode] =>
//    [OutDate] =>
//    [OutBorderCode] =>
//    [fecha_creacion] => 2020-09-29T00:00:00
//    [fecha_registro] => 2020-09-29T00:00:00
//    [checkin_general] => 2020-10-24T19:00:42Z
//    [checkout_general] => 2020-10-31T12:00:00Z
//    [numero_habitacion] => 127

    $tpl_portada = new TemplatePower("plantillas/panel_usuario_datos.html", T_BYFILE);
    $tpl_portada->prepare();

    $tpl_portada->assign('titulo_seccion', LANG_DATOS_HUESPED);
    $tpl_portada->assign('identificador_usuario', $reserva['GuestIdentifier']);


    $tpl_portada->assign('cerrar_sesion', LANG_CERRAR_SESION);

    $tpl_portada->assign('numero_habitacion', LANG_NUMERO_HABITACION);
    $tpl_portada->assign('dato_numero_habitacion', $reserva['numero_habitacion']);

    $tpl_portada->assign('regimen_habitacion', LANG_REGIMEN_HABITACION);

    $array_ragimenes = array('AL' => LANG_REGIMEN_ACOMODACION, 'AD' => LANG_REGIMEN_DESAYUNO, 'MP' => LANG_REGIMEN_MEDIA_PENSION, 'PC' => LANG_REGIMEN_COMPLETO, 'TI' => LANG_TODO_INCLUIDO);
    //$tpl_portada->assign('dato_regimen_habitacion', $reserva['regimen_general']);
    $tpl_portada->assign('dato_regimen_habitacion', $array_ragimenes[$reserva['regimen_general']]);

    $tpl_portada->assign('fecha_salida', LANG_FECHA_SALIDA);
    $tpl_portada->assign('dato_fecha_salida', date('d/m/Y', strtotime($reserva['checkout_general'])));

    //$tpl_portada->assign('documento_identidad', LANG_DOCUMENTO_IDENTIDAD);
    //$tpl_portada->assign('dato_documento_identidad', $reserva['DocumentNumber']);

    $tpl_portada->assign('nombre_titulo', LANG_DOCUMENTO_NOMBRE);


    $tpl_portada->assign('nombre_usuario', strtolower($reserva['FirstName']));
    $tpl_portada->assign('apellidos_usuario', strtolower($reserva['LastName']));


    $tpl_portada->assign('logo_hotel', 'https://view.twisticdigital.com/dunas/feelapp/images/logo4.png');


    $array_mensajes_bienvenida = ['1901' => LANG_DATOS_BIENVENIDA2_GREGORY,
        '1904' => LANG_DATOS_BIENVENIDA2_MASPALOMAS,
        '1902' => LANG_DATOS_BIENVENIDA2_VILLAS,
        '1903' => LANG_DATOS_BIENVENIDA2_MIRADOR];


    $tpl_portada->assign('mensaje_bienvenida1', LANG_DATOS_BIENVENIDA1);
    $tpl_portada->assign('mensaje_bienvenida2', $array_mensajes_bienvenida[$_SESSION['id_centro']]);
    $tpl_portada->assign('mensaje_despedida1', LANG_DATOS_CHECKOUT);
    $tpl_portada->assign('url_trip', LANG_DATOS_URLTRIP);

    if ($_SESSION['idioma'] == 3) {
        $tpl_portada->assign('mensaje_despedida2', LANG_DATOS_CHECKOUT2);
    }

    //$reserva['checkout_general'] = '2020-11-16T12:00:00Z';


    //control si es la "primera vez", cuando el usuario introduce los datos y si es el día del checkout para mostrar despedida
    if ($identificador_usuario != NULL) {

        $tpl_portada->assign('css_bienvenida', 'displaynone');

        if ((date('d-m-Y', strtotime($reserva['checkout_general']))) == (date("d-m-Y", time()))) {
            $tpl_portada->assign('css_despedida', 'displayblock');
        } else {
            $tpl_portada->assign('css_despedida', 'displaynone');
        }
    } else {

        if ((date('d-m-Y', strtotime($reserva['checkout_general']))) == (date("d-m-Y", time()))) {
            $tpl_portada->assign('css_despedida', 'displayblock');
            $tpl_portada->assign('css_bienvenida', 'displaynone');
        } else {
            $tpl_portada->assign('css_despedida', 'displaynone');
            $tpl_portada->assign('css_bienvenida', 'displayblock');
        }

    }


    $array_pushtech['silver'] = array('codigo' => 'silver', 'nombre' => 'Dunas Club', 'imagen' => 'silver.svg');
    $array_pushtech['gold'] = array('codigo' => 'gold', 'nombre' => 'Dunas Club Love', 'imagen' => 'gold.svg');
    $array_pushtech['platinum'] = array('codigo' => 'platinum', 'nombre' => 'Dunas Club Passion', 'imagen' => 'platinum.svg');

    $email = $reserva['ContactEMail'];
    //$email = 'mchernandez@villademoya.es';

    if ($email) {

        $url_api = 'http://apidunas.twisticdigital.com/consultas.php';

        $datos_post = http_build_query(
            array(
                'funcion' => 'consulta_niveles',
                'datos' => array('email' => $email)
            )
        );

        $datos_post = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $datos_post
            )
        );

        $datos_post = stream_context_create($datos_post);

        $datos_pushtech = file_get_contents($url_api, false, $datos_post);
        $datos_pushtech = json_decode($datos_pushtech, true);


        $datos_pushtech = $datos_pushtech['contacts'][0];

//Array
//(
//    [contacts] => Array(
//        [0] => Array
//    (
//    [id] => 5fa1191411e254740aa8d4fc
//    [name_first] => SERGIO
//    [list_subscriptions] => Array(
//    )
//    [name_last] => RAMOS QUESADA
//    [phone_countrycode] =>
//    [phone_number] =>
//    [user_id] => mchernandez@villademoya.es
//    [country] => ES
//    [region] =>
//    [city] =>
//    [zip] =>
//    [full_address] =>
//    [last_latitude] =>
//    [last_longitude] =>
//    [gender] =>
//    [language] =>
//    [facebook_id] =>
//    [facebook_friends] =>
//    [google_id] =>
//    [twitter_id] =>
//    [twitter_followers] =>
//    [born_date] =>
//    [email] => mchernandez@villademoya.es
//    [gdpr_marketing_consent] =>
//    [gdpr_accept_terms] =>
//    [gdpr_date] =>
//    [gdpr_remote_ip] =>
//    [gdpr_link_terms] =>
//    [gdpr_link_privacy] =>
//    [country_code_description] =>
//    [tags] => Array(
//        )
//    [email_verified_external] =>
//    [email_verified_external_result] =>
//    [email_verified_external_response] =>
//    [address] =>
//    [time_arrival] =>
//    [athlete] =>
//    [active_discount] =>
//    [check_out] =>
//    [check_in] =>
//    [client_comment] =>
//    [loyalty_member] =>
//    [connection_DATE] =>
//    [is_cyclist] =>
//    [dunas_club_antiguo] =>
//    [favorite_destination] =>
//    [id_number] =>
//    [last_hotel_name] =>
//    [level] =>
//    [loyalty_card] =>
//    [id_loyalty] =>
//    [member_since] =>
//    [mobile_phone] =>
//    [newsletter] =>
//    [origen_alta] =>
//    [wp_other_login] =>
//    [phone_custom] => 656186352
//    [postal_code] => 35420
//    [previous_visit] =>
//    [province] =>
//    [register_project] =>
//    [register_url] =>
//    [reservation_date] =>
//    [reservation_type] =>
//    [residente_canario] =>
//    [returning_guest] =>
//    [review_pro] =>
//    [room_number_limit] =>
//    [wp_type_social] =>
//    [travel_for_business] =>
//    [travel_in_couple] =>
//    [travel_with_children] =>
//    [url_alta] =>
//    [user_discount] =>
//    [vacation] =>
//    [channels_subscription] => Array(
//        [0] => Array(
//            [channel] => sms
//            [blacklisted] =>
//        )
//        [1] => Array(
//            [channel] => email
//            [blacklisted] =>
//        )
//)
//    [email_verified_internal] =>
//    [email_verified_internal_result] =>
//    [email_verified_internal_response] =>
//    )
//)
//)

        $nivel = $datos_pushtech['level'];

        if ($datos_nivel = $array_pushtech[$nivel]) {
            $tpl_portada->assign('url_img_nivel', 'https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/niveles/' . $datos_nivel['imagen']);
        } else {
            $tpl_portada->assign('ocultar_niveles', 'displayNone');
        }

    } else {
        $tpl_portada->assign('ocultar_niveles', 'displayNone');
    }

    //$tpl_portada->printToScreen();
    $respuesta = array('error_code' => 0, 'error_texto' => '', 'mensaje' => $tpl_portada->getOutputContent(), 'id_identificador_usuario' => $reserva['GuestIdentifier']);

    echo json_encode($respuesta);

}

function datos_cliente_dunas($id_hotel, $idCliente)
{

    $url_api = 'http://apidunas.twisticdigital.com/consultas.php';

    $datos_post = http_build_query(
        array(
            'funcion' => 'consulta_cliente',
            'datos' => array('id_hotel' => $id_hotel,
                'idCliente' => $idCliente)
        )
    );

    $datos_post = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $datos_post
        )
    );

    $datos_post = stream_context_create($datos_post);

    $datos_consulta = file_get_contents($url_api, false, $datos_post);
    $datos_consulta = json_decode($datos_consulta, true);

    $datos_cliente = $datos_consulta['respuesta']['FindClientsResult']['Clients']['Client'];

    $fecha_cumple = substr($datos_cliente['BirthdayDate'], 0, 10);

    return $fecha_cumple;

    //[respuesta] => Array
    //        (
    //            [FindClientsResult] => Array
    //                (
    //                    [StatusCode] => 0
    //                    [StatusMessage] => Successfully
    //                    [Time] => 0
    //                    [Clients] => Array
    //                        (
    //                            [Client] => Array
    //                                (
    //                                    [ClientIdentifier] => 4c8f7845-39b3-964b-859d-9671729ff6c7
    //                                    [HotelCode] => DDG
    //                                    [MarritalStatus] => NotInformed
    //                                    [Gender] => Male
    //                                    [Habitual] =>
    //                                    [NotDesired] =>
    //                                    [Active] => 1
    //                                    [FirstName] => NICOLAS
    //                                    [LastName] => BERNARD
    //                                    [SecondSurname] => VALCANERAS
    //                                    [LanguageCode] => 1
    //                                    [BornLocal] => ESNIL ST DENIS
    //                                    [BirthdayDate] => 1968-08-29T00:00:00
    //                                    [AtencionCode1] =>
    //                                    [AtencionCode2] =>
    //                                    [AtencionCode3] =>
    //                                    [ConfidentialStay] => 1
    //                                    [SendDataToOthers] => 1
    //                                    [AllowReceiveSMS] => 1
    //                                    [AllowReceiveEmails] => 1
    //                                    [AllowReceiveOffers] => 1
    //                                    [ClientTypeCode] => 1
    //                                    [ClientCode] => 833172
    //                                    [HotelName] => HOTEL DUNAS DON GREGORY
    //                                    [BornCountryDescription] => ESPAÑA
    //                                    [BornCountryISOCode] => ES
    //                                    [LanguageDescription] => ESPAÑOL
    //                                    [LanguageISOCode] => ES
    //                                    [ClientTypeDescription] => STANDARD
    //                                    [ResidenceInformation] => Array
    //                                        (
    //                                            [PhoneNumber] => 6115127329
    //                                            [Email] => drafforo@hotmail.com
    //                                            [MailNotInformed] =>
    //                                            [Address] => BALLESTA 10
    //                                            [ResidenceCountryCode] => ESP
    //                                            [ResidenceCountryDescription] => ESPAÑA
    //                                            [ResidenceCountryISOCode] => ES
    //                                        )
    //
    //                                    [DocumentInformation] => Array
    //                                        (
    //                                            [DocumentTypeCode] => 1
    //                                            [DocumentNumber] => 43074377T
    //                                            [EmissionDate] => 2013-02-27T00:00:00
    //                                            [ValidationDate] => 2023-02-27T00:00:00
    //                                            [NotExpire] =>
    //                                            [HasChilds] =>
    //                                            [LastChildBirthdayDate] =>
    //                                        )
    //
    //                                    [EntityInformation] => Array
    //                                        (
    //                                        )
    //
    //                                )
    //
    //                        )
    //
    //                )
    //
    //        )

}

