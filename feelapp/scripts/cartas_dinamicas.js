$(document).ready(function () {
  $('body').on('click', '.js_info_dinamica', function (event) {
    event.stopPropagation()
    var obj = $(this)
    var id_carta = $(this).attr('data-id')
    var title = $(this).attr('data-titulo')

    var nombre_carta = $(this).find('.texto_cartas').html()

    if (!nombre_carta) {
      nombre_carta = $(this).html()
    }

    if (nombre_carta == undefined) {
      nombre_carta = ''
    }

    if (title == undefined) {
      title = ''
    }

    nombre_carta = nombre_carta.replace(/<[^>]*>?/g, '')

    var url_pagina = 'gastronomia/carta'
    var titulo_pagina = 'Carta ' + title + ' ' + nombre_carta

    add_page_analytics(url_pagina, titulo_pagina)

    $.ajax({
      type: 'POST',
      url: './index.php?pagina=get_cartas_dinamicas&id_carta=' + id_carta,
      success: function (res) {
        var contenido_carta = JSON.parse(res)
        // console.log(datos)
        console.log('click carta vinos')

        $('meta[name=viewport]').attr(
          'content',
          'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0'
        )

        var texto_html =
          '<div style="display: none" class="fondo-popup-zoom"><div class="content-popup content-img-zoom">' +
          contenido_carta +
          '</div>' +
          '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/iconos/atras.png" alt=""></div>' +
          '</div>'

        $('.fondo-popup-zoom').remove()
        $('body').append(texto_html)
        $('.fondo-popup-zoom').fadeIn(500)


        //SI SOLO TIENE UNA CATEGORÍA LA CARTA DINÁMICA SE DESPLIEGA POR DEFECTO
        if ($('.accordion-new').length == 1) {
          $('.accordion-new').click()
        }

        // obj.parents(".content").popup({
        //     title: title,
        //     titleClass: "eventoDetailTitle",
        //     contenido: datos,
        //     contenidoClass: "eventoDetailContent",
        //     contenedorClass: "overlayWrapper popup-element demo_popup",
        //     renderCallback: function () {
        //         $(obj).parents(".menuContentWrapper").click(function (event) {
        //             if ($(".demo_popup").size() > 0) {
        //                 event.stopPropagation();
        //                 $(this).removeClass("filter1");
        //                 $(this).find(".filter1").removeClass("filter1");
        //                 $(".demo_popup").fadeOut(function () {
        //                     $(".overlayBackground").remove();
        //                     $(this).remove();
        //
        //                 })
        //             }
        //         });
        //     }
        // });
      },
    })
  })

  $('body').on('click', '.js_info_dinamica_v2', function (event) {
    event.stopPropagation()
    var obj = $(this)
    var id_carta = $(this).attr('data-id')
    var title = $(this).attr('data-titulo')

    var nombre_carta = $(this).find('.texto_cartas').html()

    if (!nombre_carta) {
      nombre_carta = $(this).html()
    }

    nombre_carta = nombre_carta.replace(/<[^>]*>?/g, '')

    if (nombre_carta == undefined) {
      nombre_carta = ''
    }

    if (title == undefined) {
      title = ''
    }

    var url_pagina = 'gastronomia/carta'
    var titulo_pagina = 'Carta ' + title + ' ' + nombre_carta

    add_page_analytics(url_pagina, titulo_pagina)

    $.ajax({
      type: 'POST',
      url: './index.php?pagina=get_cartas_dinamicas_fondo&id_carta=' + id_carta,
      success: function (res) {
        var contenido_carta = JSON.parse(res)
        // console.log(datos)
        console.log('click carta vinos')

        $('meta[name=viewport]').attr(
          'content',
          'user-scalable=yes, initial-scale=1.0, maximum-scale=3.0'
        )

        var texto_html =
          '<div style="display: none" class="fondo-popup-zoom"><div class="content-popup content-img-zoom">' +
          contenido_carta +
          '</div>' +
          '<div class="back-popup"><img src="https://view.twisticdigital.com/contenido_proyectos/dunas/_general/iconos/atras.png" alt=""></div>' +
          '</div>'

        $('.fondo-popup-zoom').remove()
        $('body').append(texto_html)
        $('.fondo-popup-zoom').fadeIn(500)

        // obj.parents(".content").popup({
        //     title: title,
        //     titleClass: "eventoDetailTitle",
        //     contenido: datos,
        //     contenidoClass: "eventoDetailContent",
        //     contenedorClass: "overlayWrapper popup-element demo_popup",
        //     renderCallback: function () {
        //         $(obj).parents(".menuContentWrapper").click(function (event) {
        //             if ($(".demo_popup").size() > 0) {
        //                 event.stopPropagation();
        //                 $(this).removeClass("filter1");
        //                 $(this).find(".filter1").removeClass("filter1");
        //                 $(".demo_popup").fadeOut(function () {
        //                     $(".overlayBackground").remove();
        //                     $(this).remove();
        //
        //                 })
        //             }
        //         });
        //     }
        // });
      },
    })
  })

  $('body').on('click', '.accordion-new', function () {
    var div_actual = $(this).next()

    if ($(this).next().is(':visible')) {
      var visible = 1
    } else {
      var visible = 2
    }

    // if ($(this).next().hasClass('panel')) {
    //     if ($(this).next().is(':visible')) {
    //         $(this).removeClass('active');
    //         $(this).next().addClass('active').hide('slow');
    //     } else {
    //         $(this).addClass('active');
    //         $(this).next().addClass('active').show('slow')
    //     }
    // }

    $('.accordion-new').removeClass('active')
    $('.panel').addClass('active').hide()

    if (div_actual.hasClass('panel')) {
      if (visible == 1) {
        $(this).removeClass('active')
        $(this).next().addClass('active').hide()
      } else {
        $(this).addClass('active')
        $(this).next().addClass('active').show()
      }
    }

    $('.content-general-carta-dinamica').scrollTop(150)
  })
})
