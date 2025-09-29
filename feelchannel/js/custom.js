$(document).ready(function () {

    launchIntoFullscreen(document.documentElement);

    comprobarHoraScreensaver();

    setInterval(function () {
        launchIntoFullscreen(document.documentElement);
        comprobarHoraScreensaver();
    }, 15000);


    //Tiempo de espera para que salga de nuevo la publicidad a fullscreen (screensaver.web)
    setInterval(function () {
        $('#nombre_video_destacado').val("")
    }, 600000); //10 minutos


    // setInterval(function () {
    //     $('#nombre_video_destacado').val("")
    // }, 60000);


    video_list = $('#contenido_videos').val();
    video_list = JSON.parse(video_list);
    // console.log(video_list);


    // etiquetas_list = $('#etiquetas_videos').val();
    // etiquetas_list = etiquetas_list.split(",");

    video_index = 0;
    indice_etiquetas = -1;
    contador_etiquetas = 0;
    video_player = document.getElementById("idle_video");

    onload();
    carousel();

    //Mostrar el tiempo
    setInterval(function () {
        sacarTiempoAjax();
    }, 1800000);

    //Mostrar animacion
    setInterval(function () {
        sacarAnimacionAjax();
    }, 15000);

    //Mostrar Normas en el div de animacion
    // setInterval(function () {
    //     mostrarNormas();
    //     $('#idioma_actual').val(1);
    // }, 60000);


});

function comprobarHoraScreensaver() {

    var id_centro_p = $('#id_centro').val();

    if(id_centro_p != 1901){
        return;
    }

    var fecha = new Date();
    var hora = fecha.getHours() + ":" + fecha.getMinutes();

    console.log("Entrando en la funcion 'Comprobar Hora', son las " + hora);

    if (hora >= '12:00' && hora <= '15:00' || hora >= '17:00' && hora <= '22:00') {
        console.log("Dentro del horario");

        var nombre_video_destacado = $('#nombre_video_destacado').val();

        if (nombre_video_destacado != "screensaver.webm") {

            var ruta_screensaver = "../../../contenido_proyectos/vistaflor/centro_1901/feelchannel/videos/screensaver.webm";

            setTimeout(function () {
                $("#idle_video").attr("src", ruta_screensaver);
                $('#idle_video').addClass("videoDestacado");
                $('#nombre_video_destacado').val("screensaver.webm");
            }, 100);

            $("#idle_video").on('ended', function () {
                $('#idle_video').removeClass("videoDestacado");
            });
        }

    } else {
        console.log("Fuera del horario");
    }
}


function onload() {
    video_index = 0;

    console.log('body loaded');

    var extension = video_list[video_index].src.substr(-4);
    $('#idle_video').hide();

    $('#idle_video').fadeIn(1500);
    if (extension == ".jpg") {

        $('#idle_video').attr("src", "");
        $('#idle_video').attr("poster", video_list[video_index].src);
        $('#idle_video').css('height', 'auto');

        // $('#nombre_video_destacado').val("");


        var intervalo = setInterval(function () {
            onVideoEnded();
            clearInterval(intervalo);
        }, 15000);

    } else {
        onVideoEnded();
    }

    // video_index++;

    sacarAnimacionAjax();

}

function mostrarNormas() {

    var animacion = "#animacion";
    var normas = "#normas";

    $(animacion).hide();
    $(normas).show();

    var intervaloNormas = setInterval(function () {
        $(normas).hide();
        $(animacion).show();
        clearInterval(intervaloNormas);
    }, 16000);


}


function onVideoEnded() {
    indice_etiquetas++;
    console.log("video ended");

    $('#idle_video').fadeIn(1000);

    if (video_index == video_list.length) {
        video_index = 0;
        indice_etiquetas = 0;
    }


    var extension = video_list[video_index].src.substr(-4);

    // console.log(video_list);
    // console.log(video_index);


    if (extension == ".jpg") {
        $('#idle_video').attr("src", "");
        $('#idle_video').attr("poster", video_list[video_index].src);
        $('#idle_video').css('height', 'auto');

        // $('#nombre_video_destacado').val("");


        var intervalo = setInterval(function () {
            onVideoEnded();
            clearInterval(intervalo);
        }, 15000);


    } else {

        $('#idle_video').attr("poster", '');
        $('#idle_video').css('height', '');

        // $("#etiqueta").html(etiquetas_list[video_index]);
        // console.log(video_list[video_index].etiquetas[0]);

        // console.log(video_list[indice_etiquetas].etiquetas.length);


        if (video_list[indice_etiquetas].etiquetas.length > 0) {

            // video_player.pause();

            var duracion = ((video_list[indice_etiquetas].duracion - 1000) / video_list[indice_etiquetas].etiquetas.length);

            var cont = 0;

            $('#etiqueta').remove();
            $("#videos").append(video_list[indice_etiquetas].etiquetas[cont]);
            $('#etiqueta').hide();
            $('#etiqueta').fadeIn(700);

            cont++;

            var intervalo_etiquetas = setInterval(function () {

                $('#etiqueta').fadeOut(700, function () {
                    $(this).remove();
                });

                $("#videos").append(video_list[indice_etiquetas].etiquetas[cont]);
                $('#etiqueta').hide();


                cont++;

                if (cont > video_list[indice_etiquetas].etiquetas.length) {
                    clearInterval(intervalo_etiquetas);
                    cont = 0;
                }

            }, duracion);


        } else {
            console.log("dentro else etiquetasssss");
            $('#etiqueta').fadeOut(1000, function () {
                $(this).remove();
            });
        }


        if (video_list[video_index].destacado == 1) {
            $('#idle_video').addClass("videoDestacado");

        } else {
            $('#idle_video').removeClass("videoDestacado");
            // $('#nombre_video_destacado').val("");
        }

        video_player.setAttribute("src", video_list[video_index].src);
        video_player.play();


    }

    video_index++;
}


var indice = 0;

function carousel() {
    var i;
    var x = $(".slider");

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    indice++;
    if (indice > x.length) {
        indice = 1
    }
    x[indice - 1].style.display = "block";

    var segundos = $('#' + (indice - 1)).val();

    if (segundos == null) {
        segundos = 10;
    }
    segundos = segundos * 1000;

    setTimeout(carousel, segundos); //
}


function sacarAnimacionAjax() {

    var parametro = {
        "funcion": "sacarAnimacion"
    };

    $.ajax({
        data: parametro,
        url: 'index.php',
        type: 'post',
        success: function (response) {

            var id_idioma = $('#idioma_actual').val();
            // console.log(id_idioma);
            var resultado = JSON.parse(response);
            $('#animacion').html(resultado);

            $('.programa_animacion').hide();

            $('.idioma-' + parseInt(id_idioma)).fadeIn(1000);

            if (id_idioma == 4) {
                id_idioma = 1;
                $('#idioma_actual').val(parseInt(id_idioma));
            } else {
                $('#idioma_actual').val(parseInt(id_idioma) + 1);

            }

        }
    });
}


function sacarTiempoAjax() {

    var parametro = {
        "funcion": "sacarTiempo"
    };

    $.ajax({
        data: parametro,
        url: 'index.php',
        type: 'post',
        success: function (response) {

            console.log(response);
            var resultado = JSON.parse(response);
            $('#tiempo').html(resultado);

        }
    });
}


function getCurrentElementInFullScreen(element) {

    console.log('asdasd');

    if (element.fullscreenElement)
        return element.fullscreenElement;
    if (element.webkitFullscreenElement)
        return element.webkitFullscreenElement;
    if (element.mozFullScreenElement)
        return element.mozFullScreenElement;
    if (element.msFullscreenElement)
        return element.msFullscreenElement;

    return null;

}


function launchIntoFullscreen(element) {
    console.log('fullscreen');
    //if(element.requestFullscreen) {
    //    element.requestFullscreen();
    //} else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
    //} else if(element.webkitRequestFullscreen) {
    //    element.webkitRequestFullscreen();
    //} else if(element.msRequestFullscreen) {
    //    element.msRequestFullscreen();
    //}
}
