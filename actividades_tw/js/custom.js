const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

var popup_categorias = ''

document.addEventListener("DOMContentLoaded", function () {

    const modalContent = document.getElementById('modalContent');
    const modal = document.getElementById('modal');
    var tiny_slider = []
    var limpiar_filtros = []


    if (document.getElementById('mas_info')) {
        document.getElementById('mas_info').addEventListener('click', (event) => {
            show_popup(true);
        })
    }


    //EVENTO FILTRO CATEGORIAS
    document.querySelectorAll('.boton').forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();

            if (item.getAttribute('data-id') == 'riuparty') {
                document.getElementById('animacion-semanal').classList.toggle('hidden')
                document.getElementById('riuparty').classList.toggle('hidden')
            } else {
                document.getElementById('animacion-semanal').classList.remove('hidden')
                document.getElementById('riuparty').classList.add('hidden')

                let btn_party = document.querySelector('[data-id="riuparty"]')
                if (btn_party) {
                    btn_party.classList.add('boton')
                    btn_party.classList.remove('btn_pulsado')
                }

            }

            setTimeout(() => {
                avanzar_scroll_vista_semanal();
            }, 500);


            add_estadisticas_js({
                seccion: 'actividades_semanal',
                subseccion: 'cambio_categoria',
                identificador: item.dataset.id,
                observaciones: 'Se ha pulsado en botón filtrar por categoría.'
            });


            document.getElementById('mas_info').classList.remove('translate-x-full', 'opacity-0')

            efecto_btn_filtro_pulsado(item);
            filtro_tabla_semanal();

            clearInterval(limpiar_filtros);

            limpiar_filtros = setInterval(() => {
                document.querySelectorAll('.btn_pulsado').forEach(element => {
                    element.classList.remove('btn_pulsado')
                    element.classList.add('boton')
                });
                filtro_tabla_semanal();
                document.getElementById('animacion-semanal').classList.remove('hidden')
                document.getElementById('riuparty').classList.add('hidden')
            }, 300000); // 5 minutos

        });

    });




    //EVENTO ABRIR POPUP
    document.querySelectorAll('.open_popup').forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();


            let info_actividad = JSON.parse(item.querySelectorAll('input')[0].value)

            if (info_actividad.status != 'error') {
                generate_popup(item, false);
                show_popup(false);

                add_estadisticas_js({
                    seccion: item.classList.contains('li_actividades') ? 'actividades_diaria' : 'actividades_semanal',
                    subseccion: 'detalle',
                    identificador: info_actividad.id_evento,
                    observaciones: 'Se ha abierto el popup de la actividad.'
                });
            }


        });
    });



    // EVENTO CERRAR POPUP
    document.querySelectorAll('.overlayModal').forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();

            setTimeout(() => {
                modal.classList.add('scale-50')
                modal.classList.add('opacity-0')
                modalContent.classList.remove('opacity-0')
            }, 100);

            setTimeout(() => modalContent.classList.add('hidden'), 500);
        });
    });


    //EVENTO CAMBIAR IDIOMA
    document.querySelectorAll('.language').forEach((language) => {
        language.addEventListener('click', (e) => {
            e.preventDefault();

            document.querySelectorAll('.language').forEach(item => {
                if (item != e.target) {
                    item.classList.add('opacity-50');
                } else {
                    e.target.classList.remove('opacity-50')
                    cambiar_idioma(e.target.getAttribute('data-id'))
                }
            });

        });
    });

    // RELOJ Y FECHA
    const clock = document.querySelector('.clock');
    const date = document.querySelector('.date');
    const date2 = document.querySelector('.date2');


    const tick = () => {
        const now = new Date();
        let h = now.getHours();
        let m = now.getMinutes();
        let s = now.getSeconds();

        if (h.toString().length == 1) {
            h = '0' + h
        }

        if (m.toString().length == 1) {
            m = '0' + m
        }

        if (s.toString().length == 1) {
            s = '0' + s
        }

        const day = now.getDay();
        const num_day = now.getDate();
        const month = now.getMonth();

        const html_clock = `
            <span>${h}</span> :
            <span>${m}</span> :
            <span>${s}</span>
        `;

        const html_date = `
            <span>${days[day].toUpperCase()}</span>
            
        `;

        const html_date2 = `
            <span>${num_day}</span>        
            <span>${months[month]}</span>   
        `;

        clock.innerHTML = html_clock;
        date.innerHTML = html_date;
        date2.innerHTML = html_date2;

    };

    if (clock) {
        tick();
        setInterval(tick, 1000);
    }



    //------------------------- SCREENSAVER --------------------------- //

    let screensaver_html = document.getElementById('screensaver');

    if (screensaver_html) {

        let screensaver_espera = screensaver_html.getAttribute('data-espera')
        let screensaver_duracion = screensaver_html.getAttribute('data-duracion')

        let timeouts = []
        screensaver(screensaver_html, screensaver_espera, screensaver_duracion, timeouts);


        // CLICK CERRAR SCREENSAVER
        document.getElementById('screensaver').addEventListener('click', (event) => {
            event.preventDefault();

            clear_timeouts(timeouts);
            ocultar_screensaver(screensaver_html);

            screensaver(screensaver_html, screensaver_espera, screensaver_duracion, timeouts);

        });

        //RESETEAR TIEMPO SCREENSAVER SI SE USA LA PANTALLA
        document.addEventListener('click', event => {
            clear_timeouts(timeouts);
            screensaver(screensaver_html, screensaver_espera, screensaver_duracion, timeouts);

        })

    }


    document.addEventListener('click', event => {
        clearInterval(limpiar_filtros);
        limpiar_filtros = setInterval(() => {
            document.querySelectorAll('.btn_pulsado').forEach(element => {
                element.classList.remove('btn_pulsado')
                element.classList.add('boton')
            });
            filtro_tabla_semanal();
        }, 300000); // 5 minutos
    })

    document.querySelectorAll('.btnplay').forEach((item) => {
        item.addEventListener('click', (e) => {


            item.classList.add('play');

            setTimeout(() => {
                item.classList.remove('play');
            }, 500);
        });
    });


}); //FIN DEL READY


function clear_timeouts(timeouts) {
    for (let index = 0; index < timeouts.length; index++) {
        clearTimeout(timeouts[index]);
    }
}


function ocultar_screensaver(screensaver_html) {

    screensaver_html.classList.add('opacity-0');

    const src = screensaver_html.querySelector('video').src;

    setTimeout(() => {
        screensaver_html.classList.add('hidden');
        screensaver_html.querySelector('video').pause();

        add_estadisticas_js({
            seccion: urlParams.get('page'),
            subseccion: 'screensaver',
            identificador: 'Cerrar',
            observaciones: src.substring(src.lastIndexOf('/') + 1)
        });
    }, 500);

}


function screensaver(screensaver_html, screensaver_espera, screensaver_duracion, timeouts) {

    //LIMPIAR TIMEOUTS
    clear_timeouts(timeouts);

    const src = screensaver_html.querySelector('video').src;

    //ACTIVAR SCREENSAVER
    timeouts[0] = setTimeout(() => {
        screensaver_html.classList.remove('hidden');

        add_estadisticas_js({
            seccion: urlParams.get('page'),
            subseccion: 'screensaver',
            identificador: 'Abrir',
            observaciones: src.substring(src.lastIndexOf('/') + 1)
        });

        timeouts[1] = setTimeout(() => {
            screensaver_html.classList.remove('opacity-0');
            screensaver_html.querySelector('video').play();
        }, 500);

        timeouts[2] = setTimeout(() => {
            screensaver_html.classList.add('opacity-0');

            timeouts[3] = setTimeout(() => {
                screensaver_html.classList.add('hidden');
                screensaver_html.querySelector('video').pause();

                add_estadisticas_js({
                    seccion: urlParams.get('page'),
                    subseccion: 'screensaver',
                    identificador: 'Cerrar',
                    observaciones: src.substring(src.lastIndexOf('/') + 1)
                });

                screensaver(screensaver_html, screensaver_espera, screensaver_duracion, timeouts);

            }, 500);

        }, screensaver_duracion);

    }, screensaver_espera);


}

function efecto_btn_filtro_pulsado(item) {
    if (item.classList.contains('btn_pulsado')) {
        item.classList.remove('btn_pulsado')
        item.classList.add('boton')

    } else {
        item.classList.add('btn_pulsado')
        item.classList.remove('boton')


    }


}

function cambiar_idioma(id_idioma) {

    fetch('index.php', {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'id_idioma': id_idioma,
                'page': urlParams.get('page')
            })
        })
        .then(() => location.reload())
        .catch(error => console.log(error));

}

function slider(elementClass) {

    tns({
        container: '#' + elementClass,
        loop: true,
        items: 1,
        slideBy: 'page',
        nav: false,
        autoplay: true,
        controls: false,
        speed: 1000,
        autoplayTimeout: 9000,
        autoplayButtonOutput: false,
        mouseDrag: true,
        lazyload: true,
        "swipeAngle": false,
    });
}



function efecto_inicial_vista_diaria() {


    let milliseconds = 100;

    document.querySelectorAll('.ul_actividades').forEach((ul) => {

        ul.scroll({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });

        ul.querySelectorAll('.li_actividades').forEach((li, count) => {
            //EFECTO ENTRADA LATERAL PARA LOS 5 PRIMEROS ELEMENTOS DE CADA CATEGORIA
            if (count < 5) {
                setTimeout(() => {
                    li.classList.remove('opacity-0')
                    li.classList.remove('translate-x-full');
                }, (milliseconds * count));
            } else {
                li.classList.remove('opacity-0')
                li.classList.remove('translate-x-full');
            }
        })
    })
}


function efecto_inicial_vista_semanal() {


    let milliseconds = 50;

    document.getElementById('tbody_animation').scroll({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });

    document.querySelectorAll('.td_actividades').forEach((actividad, count) => {
        if (count < 28) {
            setTimeout(() => {
                actividad.classList.remove('opacity-0')
                actividad.classList.remove('translate-y-full');
            }, (milliseconds * count));
        } else {
            actividad.classList.remove('opacity-0')
            actividad.classList.remove('translate-y-full');
        }
    });
}


function avanzar_scroll_vista_diaria() {

    const now = new Date();
    let h = now.getHours();
    let m = now.getMinutes();

    let hora_actual = new Date('1/1/1990 ' + (h + ':' + m));
    let coordenadas = null


    document.querySelectorAll('.ul_actividades').forEach((ul) => {
        ul.querySelectorAll('.li_actividades').forEach((li, count) => {
            let datos_actividad = JSON.parse(li.querySelector('input').value)
            let hora_actividad = new Date('1/1/1990 ' + datos_actividad.hora_ini)

            if (hora_actual.getTime() > hora_actividad.getTime()) {
                // MOVER EL SCROLL HASTA LA HORA ANTERIOR
                coordenadas = li.offsetTop
            }

        });

        ul.scroll({
            top: Math.round(coordenadas - 10),
            left: 0,
            behavior: 'smooth'
        });
    });


}


function avanzar_scroll_vista_semanal() {

    // console.log(document.querySelectorAll('.td_horarios'))

    const now = new Date();
    let h = now.getHours();
    let m = now.getMinutes();

    if (h.toString().length == 1) {
        h = '0' + h
    }

    if (m.toString().length == 1) {
        m = '0' + m
    }

    let hora_actual = new Date('01/01/1990 ' + (h + ':' + m).trim());
    let coordenadas = null

    setTimeout(() => {

        document.querySelectorAll('.td_horarios').forEach((actividad) => {

            //LOS TD DE HORARIO QUE NO ESTÁN OCULTOS
            if (!actividad.parentNode.parentNode.classList.contains('hidden') && !actividad.classList.contains('hidden')) {

                let hora_actividad = new Date('01/01/1990 ' + (actividad.dataset.hora).trim())

                if (hora_actual.getTime() > hora_actividad.getTime()) {
                    coordenadas = actividad.parentNode.offsetTop
                }
            }

        });

        document.getElementById('tbody_animation').scroll({
            top: Math.round(coordenadas - 40),
            left: 0,
            behavior: 'smooth'
        });

    }, 100);
}

function mostrar_ocultar_circulos_horarios_semanal(tr_visibles) {

    let btn_hora = ''
    tr_visibles.forEach((horario, clave) => {
        let div_horario = horario.children[0].children[0]
        let hora = new Date('1/1/1990 ' + div_horario.getAttribute('data-hora'))

        if (clave == 0) {
            btn_hora = ''
            div_horario.classList.remove('hidden')
            btn_hora = hora;
        } else {
            if (btn_hora.getTime() == hora.getTime()) {
                div_horario.classList.add('hidden')
            } else {
                btn_hora = hora
                div_horario.classList.remove('hidden')
            }
        }
    })

}

function filtro_tabla_semanal() {
    const array_btn_pulsados = document.querySelectorAll('.btn_pulsado')
    const btn_pulsados = []
    const actividades_array = Array.prototype.slice.call(document.querySelectorAll('.tr_actividades'));
    const tr_visibles = []


    if (array_btn_pulsados.length > 0) {
        document.querySelectorAll('.tr_actividades').forEach((actividad) => {
            actividad.classList.add('hidden');
        });

        array_btn_pulsados.forEach((btn) => {
            btn_pulsados.push(btn.getAttribute('data-id'))
        });

        actividades_array.some(function (actividad) {
            if (btn_pulsados.includes(actividad.getAttribute('data-category'))) {
                actividad.classList.remove('hidden')
                tr_visibles.push(actividad)
            }
        })

        generate_popup(array_btn_pulsados, true)


    } else {

        document.querySelectorAll('.tr_actividades').forEach((actividad) => {
            actividad.classList.remove('hidden');
            tr_visibles.push(actividad)
        });

        document.getElementById('mas_info').classList.add('translate-x-full', 'opacity-0')
        popup_categorias = ''
    }

    mostrar_ocultar_circulos_horarios_semanal(tr_visibles)

}

function add_estadisticas_js(parametros) {

    // console.log(parametros)

    parametros['agregar_estadisticas'] = 1;

    fetch('index.php', {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(parametros)
        })
        .then(() => console.log('add estadisticas'))
        .catch(err => console.log(err));

    return;

}

function generate_popup(items, guardar_popup) {

    //LIMPIAR CONTENIDO DEL POPUP
    modal.innerHTML = ''


    // if (tiny_slider.length > 0) {
    //     if (tiny_slider[0].version != null) {
    //         tiny_slider[0].destroy()
    //         tiny_slider = []
    //     }
    // }


    if (items.length == undefined) {
        let array_aux = []
        array_aux.push(items)
        items = array_aux
    }

    let html = ''

    items.forEach(item => {

        let info_actividad = JSON.parse(item.querySelectorAll('input')[0].value)

        if (info_actividad.status == 'error') {
            return;
        }

        console.log(info_actividad)

        let slider_popup = ''
        let size_popup = 'sm:max-w-lg'
        let size_video = 'h-72'

        let imgs_riuparty = [
            'riuparty-RIU2021.jpg',
            'riuparty-white-riu2021.jpg',
            'riuparty-neon-riu2021.jpg',
            'riuparty-jungle-riu2021.jpg',
            'riuparty-pink-riu2021.jpg'
        ];

        if (info_actividad.video_evento) {
            if (imgs_riuparty.includes(info_actividad.foto_evento)) {
                console.log('existe')
                size_popup = 'sm:max-w-4xl'
                size_video = 'h-full'
            }
            slider_popup = '<video class="' + size_video + ' mx-auto" muted autoplay loop controls src="' + info_actividad.video_evento + '" alt="' + info_actividad.video_evento + '" title="' + info_actividad.video_evento + '"></video>'
        } else if (info_actividad.foto_evento) {
            slider_popup = '<img class="rounded-xl overflow-hidden" src="' + info_actividad.ruta_img + info_actividad.foto_evento + '" alt="' + info_actividad.foto_evento + '" title="' + info_actividad.foto_evento + '" />'
        }


        let description = ''

        if (info_actividad.content) {
            description = info_actividad.content
        }


        html += `
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:m-4 sm:w-full ${size_popup} sm:max-h-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">

                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="title text-lg leading-6 text-gray-900 font-bold truncate w-5/6 uppercase" id="modal-title">${info_actividad.title}</h3>
                                    <h4 class="title text-base leading-6 text-gray-900 font-medium" id="modal-time">${info_actividad.hora_ini}</h4>
                                </div>

                                <div id="modal-body" lass="mt-2 flex flex-col">
                                    <div id="slider-popup" class="w-full h-auto">${slider_popup}</div>
                                    <div id="description-popup" class="w-full py-2 text-justify">${description}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>`


        // if (slider_popup != '') {
        //     tiny_slider.push(tns({
        //         container: '#slider-popup',
        //         loop: false,
        //         items: 1,
        //         slideBy: 'page',
        //         nav: false,
        //         autoplay: false,
        //         controls: false,
        //         mouseDrag: true,
        //         lazyload: true,
        //         "swipeAngle": false,
        //     }));
        // }

    });

    modal.innerHTML = modal.innerHTML + html



    if (guardar_popup == true) {
        popup_categorias = modal.innerHTML
    }


}

function show_popup(isCategory) {

    if (isCategory == true && popup_categorias != '') {
        modal.innerHTML = popup_categorias
    }

    modalContent.classList.remove('hidden')

    setTimeout(() => {
        modalContent.classList.remove('opacity-0')
        modal.classList.remove('opacity-0')
        modal.classList.remove('scale-50')
    }, 100);

}