<?php 

    $post = $_POST;

    if($post['numero_habitacion']){

        if(!$post['fecha_dia_reserva']){
            $respuesta = ['error_code' => 2, 'mensaje' => LANG_TENEMOS_FECHA];
            echo json_encode($respuesta);
            return;
        }

        $id_restaurante = $post['id_restaurante_global'];

        $numero_habitacion = trim($post['numero_habitacion']);

        $array_id_hotel_api = ['1901' => 4, '1904' => 2, '1902' => 1, '1903' => 3];

        $id_hotel_api = $array_id_hotel_api[$_SESSION['id_centro']];

        $url_api = 'http://apidunas.twisticdigital.com/consultas.php';

        $datos_post = http_build_query([
            'funcion' => 'consulta_reserva',
            'datos' => [
                'numero_habitacion' => $numero_habitacion,
                'id_hotel' => $id_hotel_api,
            ],
        ]);

        $datos_post = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $datos_post,
                'timeout' => 5
            ],
        ];

        $datos_post = stream_context_create($datos_post);

        $contenido = file_get_contents($url_api, false, $datos_post);
        $contenido = json_decode($contenido, true);

        if(!$contenido){
            $respuesta = ['error_code' => 1, 'mensaje' => LANG_ERROR_GLOBAL]; 
            echo json_encode($respuesta);
            return;
        }

        $ReservationCode = $contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['ReservationCode'];
        $adults = intval($contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['Adults']);
        $childs = intval($contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['Childs']);
        $babies = intval($contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['Babies']);
        

        $checkin = $contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['CheckIn'];
        $checkout = $contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['CheckOut'];

        $checkin = substr($checkin, 0,10);
        $checkout = substr($checkout, 0,10);

        $regimen_general = $contenido['respuesta']['FindReservationsResult']['Reservations']['Reservation']['RatePlanDescription'];

        $num_comensales = ($adults + $childs);

        $numero_total_comensales = $num_comensales + $post['numero_total_comensales'];

        if($_COOKIE['id_reserva'] == $ReservationCode){
            $respuesta = ['error_code' => 4, 'mensaje' => LANG_EXISTE_HABITACION_RESERVA];
            echo json_encode($respuesta);
            return;
        }



        // $reservas_activas_usuarios = get_reservas_restaurates_franjas($id_restaurante, $post['fecha_dia_reserva'], $ReservationCode);

        // echo '<pre>';
        // print_r($reservas_activas_usuarios); die;



        $restricciones = get_restaurante_restricciones($id_restaurante);

        if($restricciones){
    
            if($restricciones['tipo_restriccion'] == 1){

                $numero_dias_semanas_res = $restricciones['dias_semana'];
                $numero_reservas_res = $restricciones['numero_reservas'];
                $maximo_comensales_res = $restricciones['maximo_comensales'];
    
                if($restricciones['maximo_comensales'] > 0){
    
                    if($numero_total_comensales > $restricciones['maximo_comensales']){
                        $respuesta = ['error_code' => 6, 'mensaje' => LANG_SUPERA_LIMITE];
                        echo json_encode($respuesta);
                        return;
                    }
    
                }

                if($numero_reservas_res > 0){

                    $all_reservas_activas = get_total_reservas_restaurantes($id_restaurante,$ReservationCode);
                    $total_reservas_res = count($all_reservas_activas);

                    if($total_reservas_res >= $numero_reservas_res){
                        $respuesta = ['error_code' => 7, 'mensaje' => LANG_MAXIMO_RESERVAS_RESTAURANTES];
                        echo json_encode($respuesta);
                        return;
                    }

                }
    
            }
    
        }


        if($post['fecha_dia_reserva'] >= $checkin && $post['fecha_dia_reserva'] <= $checkout){
            
            actualiza_datos_reserva_new($ReservationCode, $numero_habitacion, $checkout, $checkin, $num_comensales, $regimen_general,$adults,$childs,$babies);

            $ReservationCode = str_replace('/','-',$ReservationCode);
            $input_habitacion = '<input class="reservas_r_'.$post['fecha_dia_reserva'].'" type="hidden" id="comensales_'.$post['fecha_dia_reserva'].'_'.$ReservationCode.'" name="nueva_habitacion_comensales[]" value="'.$ReservationCode.'_'.$num_comensales.'_'.$numero_habitacion.'">';
            
            //$html_reserva = '<p class="eliminar_habitacion_reserva_restaurante" id="texto_comensales_'.$post['fecha_dia_reserva'].'_'.$ReservationCode.'"><i class="icon-door"></i>'.$numero_habitacion.', <span id="'.$ReservationCode.'" class="span-cancelar-habitacion"> '.LANG_CANCELAR_RESERVA.' </span></p>';
            $html_reserva = '<div id="texto_comensales_'.$post['fecha_dia_reserva'].'_'.$ReservationCode.'" class="eliminar_habitacion_reserva_restaurante">
            <div class="content-habitacion-r t-r-habitacion"><i class="icon-door"></i>'.$numero_habitacion.'</div>
            <div class="content-comensales-r t-r-comensal"><i class="icon-person"></i>'.$num_comensales.'</div>
            <div class="content-habitacion-r t-r-cancelar"><i id="'.$ReservationCode.'" class="icon-cancel span-cancelar-habitacion"></i></div>
        </div>';

            $bebes_html = null;

            if($babies > 0){
                for ($i = 1; $i <= $babies; $i++) {
                    $bebes_html .= '<label class="container_checkbox trona-'.$ReservationCode.'">'.LANG_FEELAPP_SOLICITAR_TRONA.'<input name="tronas[]" value="1" type="checkbox"><span class="checkmark"></span></label>';
                }
            }

            $respuesta = [
                'error_code' => 0,
                'mensaje' => LANG_SE_HA_AGREGADO_LA_HABITACION,
                'comensales' => $num_comensales,
                'bebes' => $babies,
                'id_reserva' => $ReservationCode,
                'fecha' => $post['fecha_dia_reserva'],
                'input' => $input_habitacion,
                'html_reserva' => $html_reserva,
                'html_bebes' => $bebes_html
                        ];

            echo json_encode($respuesta);
            return;

        }else{
            $respuesta = ['error_code' => 3, 'mensaje' => LANG_HABITACION_FUERA_DE_DIA];
            echo json_encode($respuesta);
            return;
        }

        //actualiza_datos_reserva_new($ReservationCode, $numero_habitacion, $checkout, $checkin, $num_comensales, $regimen_general,$adults,$childs,$babies);



//         Array
// (
//     [codigo] => 0
//     [parametros] => Array
//         (
//             [servicio] => consultaReservaHabitacion
//             [usuario] => ****
//             [password] => ****
//             [parametros] => Array
//                 (
//                     [hotel] => 2
//                     [habitacion] => 820
//                 )

//         )

//     [respuesta] => Array
//         (
//             [FindReservationsResult] => Array
//                 (
//                     [StatusCode] => 0
//                     [StatusMessage] => Successfully
//                     [Time] => 0
//                     [Reservations] => Array
//                         (
//                             [Reservation] => Array
//                                 (
//                                     [ReservationCode] => 1705/2022
//                                     [ReseCode] => 1705
//                                     [ReseYear] => 2022
//                                     [RoomCode] => 820
//                                     [Adults] => 4
//                                     [Childs] => 0
//                                     [Babies] => 0
//                                     [CheckIn] => 2022-04-27T13:05:19Z
//                                     [CheckOut] => 2022-05-04T12:30:00Z
//                                     [DeadLineDate] => 
//                                     [CreationDate] => 2021-11-18T00:00:00
//                                     [RegisterDate] => 2021-11-19T00:00:00
//                                     [EntityCode] => 190000
//                                     [EntityDescription] => TUI HOLANDA
//                                     [OperatorCode] => 003
//                                     [OperatorDescription] => CONTRATO BENELUX
//                                     [PriceRateCode] => 00020
//                                     [PriceRateDescription] => TUI HOLANDA
//                                     [RatePlanCode] => 5
//                                     [RatePlanDescription] => TI
//                                     [CurrencyCode] => EUR
//                                     [CurrencyDescription] => EURO
//                                     [ArrivalFlightCode] => 
//                                     [DepartureFlightCode] => 
//                                     [RoomTypeCode] => B2
//                                     [RoomTypeOccupedCode] => B2
//                                     [ComplexRatePlanOcupation] => 
//                                     [RoomTypeDescription] => Bung. 2 DM
//                                     [RoomTypeOccupedDescription] => Bung. 2 DM
//                                     [AllotmentCode] => 0045
//                                     [AllotmentDescription] => TUI BENELUX
//                                     [TotalPrice] => 
//                                     [ReservationState] => 2
//                                     [ConfirmationState] => 1
//                                     [Voucher] => DJWTWV
//                                     [Guests] => Array
//                                         (
//                                             [Guest] => Array
//                                                 (
//                                                     [0] => Array
//                                                         (
//                                                             [CountryCode] => NLD
//                                                             [CountryISOCode] => NL
//                                                             [CountryDescription] => HOLANDA
//                                                             [LoyaltyCode] => 913170
//                                                             [FiscalNumber] => IS8CDK072
//                                                             [DocumentType] => 1
//                                                             [DocumentNumber] => IS8CDK072
//                                                             [GuestCode] => 1
//                                                             [GuestIdentifier] => 4658adba-f4d0-e243-b2c9-5d0caca2ddb6
//                                                             [FirstName] => RONALD
//                                                             [LastName] => PRINS
//                                                             [GuestType] => 1
//                                                             [IsClient] => 1
//                                                             [IsTitular] => 1
//                                                             [Age] => 51
//                                                             [GuestState] => 2
//                                                             [Client] => Array
//                                                                 (
//                                                                     [CountryCode] => NLD
//                                                                     [CountryISOCode] => NL
//                                                                     [CountryDescription] => HOLANDA
//                                                                     [CountryAddressCode] => NLD
//                                                                     [CountryAddressISOCode] => NL
//                                                                     [CountryAddressDescription] => HOLANDA
//                                                                     [FiscalNumber] => IS8CDK072
//                                                                     [ContactPhone] => 0622449238
//                                                                     [ContactEMail] => romon@ziggo.nl
//                                                                     [DocumentType] => 1
//                                                                     [DocumentNumber] => IS8CDK072
//                                                                     [LanguageISOCode] => BG
//                                                                     [LanguageDescription] => INGLÉS
//                                                                     [ClientIdentifier] => 4f58742a-fd05-894d-9481-266f7d79f895
//                                                                     [ClientCode] => 913170
//                                                                     [Gender] => Male
//                                                                 )

//                                                             [GuestCategory] => Normal
//                                                             [CheckInDate] => 2022-04-27T00:00:00
//                                                             [CheckOutDate] => 
//                                                             [EntryDate] => 
//                                                             [EntryBorderCode] => 
//                                                             [OutDate] => 
//                                                             [OutBorderCode] => 
//                                                         )

//                                                     [1] => Array
//                                                         (
//                                                             [CountryCode] => NLD
//                                                             [CountryISOCode] => NL
//                                                             [CountryDescription] => HOLANDA
//                                                             [LoyaltyCode] => 913171
//                                                             [FiscalNumber] => ILK74HD40
//                                                             [DocumentType] => 1
//                                                             [DocumentNumber] => ILK74HD40
//                                                             [GuestCode] => 2
//                                                             [GuestIdentifier] => 73b700c6-574a-aa4b-ad5c-85871608f537
//                                                             [FirstName] => MONIQUE MARIA ADRI
//                                                             [LastName] => HUIJBREGTS
//                                                             [GuestType] => 1
//                                                             [IsClient] => 1
//                                                             [IsTitular] => 
//                                                             [Age] => 50
//                                                             [GuestState] => 2
//                                                             [Client] => Array
//                                                                 (
//                                                                     [CountryCode] => NLD
//                                                                     [CountryISOCode] => NL
//                                                                     [CountryDescription] => HOLANDA
//                                                                     [CountryAddressCode] => NLD
//                                                                     [CountryAddressISOCode] => NL
//                                                                     [CountryAddressDescription] => HOLANDA
//                                                                     [FiscalNumber] => ILK74HD40
//                                                                     [DocumentType] => 1
//                                                                     [DocumentNumber] => ILK74HD40
//                                                                     [LanguageISOCode] => BG
//                                                                     [LanguageDescription] => INGLÉS
//                                                                     [ClientIdentifier] => 711f5570-1259-9f48-8718-7b816e286fea
//                                                                     [ClientCode] => 913171
//                                                                     [Gender] => Female
//                                                                 )

//                                                             [GuestCategory] => Normal
//                                                             [CheckInDate] => 2022-04-27T00:00:00
//                                                             [CheckOutDate] => 
//                                                             [EntryDate] => 
//                                                             [EntryBorderCode] => 
//                                                             [OutDate] => 
//                                                             [OutBorderCode] => 
//                                                         )

//                                                     [2] => Array
//                                                         (
//                                                             [CountryCode] => NLD
//                                                             [CountryISOCode] => NL
//                                                             [CountryDescription] => HOLANDA
//                                                             [LoyaltyCode] => 913172
//                                                             [FiscalNumber] => IU3HFF2C8
//                                                             [DocumentType] => 1
//                                                             [DocumentNumber] => IU3HFF2C8
//                                                             [GuestCode] => 3
//                                                             [GuestIdentifier] => ce80b1ef-6d9b-034f-a253-d070a47836b2
//                                                             [FirstName] => LAURA
//                                                             [LastName] => PRINS
//                                                             [GuestType] => 1
//                                                             [IsClient] => 1
//                                                             [IsTitular] => 
//                                                             [Age] => 22
//                                                             [GuestState] => 2
//                                                             [Client] => Array
//                                                                 (
//                                                                     [CountryCode] => NLD
//                                                                     [CountryISOCode] => NL
//                                                                     [CountryDescription] => HOLANDA
//                                                                     [CountryAddressCode] => NLD
//                                                                     [CountryAddressISOCode] => NL
//                                                                     [CountryAddressDescription] => HOLANDA
//                                                                     [FiscalNumber] => IU3HFF2C8
//                                                                     [DocumentType] => 1
//                                                                     [DocumentNumber] => IU3HFF2C8
//                                                                     [LanguageISOCode] => BG
//                                                                     [LanguageDescription] => INGLÉS
//                                                                     [ClientIdentifier] => 56d34fa8-febf-d341-abc3-ddbbd0b8ca8e
//                                                                     [ClientCode] => 913172
//                                                                     [Gender] => Female
//                                                                 )

//                                                             [GuestCategory] => Normal
//                                                             [CheckInDate] => 2022-04-27T00:00:00
//                                                             [CheckOutDate] => 
//                                                             [EntryDate] => 
//                                                             [EntryBorderCode] => 
//                                                             [OutDate] => 
//                                                             [OutBorderCode] => 
//                                                         )

//                                                     [3] => Array
//                                                         (
//                                                             [CountryCode] => NLD
//                                                             [CountryISOCode] => NL
//                                                             [CountryDescription] => HOLANDA
//                                                             [LoyaltyCode] => 913173
//                                                             [FiscalNumber] => IH4J29CF8
//                                                             [DocumentType] => 1
//                                                             [DocumentNumber] => IH4J29CF8
//                                                             [GuestCode] => 4
//                                                             [GuestIdentifier] => 4cf86097-ad3d-6449-9a4b-fcb4e1f6ba7f
//                                                             [FirstName] => MARK
//                                                             [LastName] => PRINS
//                                                             [GuestType] => 1
//                                                             [IsClient] => 1
//                                                             [IsTitular] => 
//                                                             [Age] => 20
//                                                             [GuestState] => 2
//                                                             [Client] => Array
//                                                                 (
//                                                                     [CountryCode] => NLD
//                                                                     [CountryISOCode] => NL
//                                                                     [CountryDescription] => HOLANDA
//                                                                     [CountryAddressCode] => NLD
//                                                                     [CountryAddressISOCode] => NL
//                                                                     [CountryAddressDescription] => HOLANDA
//                                                                     [FiscalNumber] => IH4J29CF8
//                                                                     [DocumentType] => 1
//                                                                     [DocumentNumber] => IH4J29CF8
//                                                                     [LanguageISOCode] => BG
//                                                                     [LanguageDescription] => INGLÉS
//                                                                     [ClientIdentifier] => 12fd178b-d294-be4d-a1e8-cddc7f2d0aa7
//                                                                     [ClientCode] => 913173
//                                                                     [Gender] => Male
//                                                                 )

//                                                             [GuestCategory] => Normal
//                                                             [CheckInDate] => 2022-04-27T00:00:00
//                                                             [CheckOutDate] => 
//                                                             [EntryDate] => 
//                                                             [EntryBorderCode] => 
//                                                             [OutDate] => 
//                                                             [OutBorderCode] => 
//                                                         )

//                                                 )

//                                         )

//                                     [PaymentValue] => 
//                                     [PaymentBaseValue] => 
//                                     [PaymentToDate] => 
//                                     [PaymentPaid] => 
//                                     [PaymentPaidValue] => 0
//                                     [PaymentBasePaidValue] => 0
//                                     [MarketOriginCode] => 002
//                                     [MarketOriginDescription] => TOUR OPERADOR OFFLINE
//                                     [MarketSegmentCode] => 002
//                                     [MarketSegmentDescription] => VACACIONAL
//                                     [NewHotelUserCode] => PRA002
//                                     [ExternalChannelCode] => 
//                                     [SpecialsCode] => 6
//                                     [AdditionalServices] => Array
//                                         (
//                                         )

//                                     [AdultsFree] => 
//                                     [ChildsFree] => 
//                                     [GuaranteeCode] => 1
//                                     [GuaranteeDescription] => STANDARD
//                                     [TaxSchema] => 0
//                                     [TaxSchemaDescription] => STANDARD
//                                     [Order] => 0
//                                     [HotelCode] => DMAS
//                                     [HotelName] => HOTEL DUNAS MASPALOMAS
//                                 )

//                         )

//                 )

//         )

//     [error] => 
// )




    }else{
        $respuesta = ['error_code' => 1, 'mensaje' => LANG_ERROR_GLOBAL];
    }

    echo json_encode($respuesta);
    return;

?>