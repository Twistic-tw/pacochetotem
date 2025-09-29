
function inicializa_isotope() {
                
  // init Isotope

  // bind filter button click

      var $container = $('.isotope').isotope({
    itemSelector: '.tarjeta_destinos_hotel',
    masonry: {
      columnWidth: 10
    }
  });


  $('#filters').on( 'click', '.filtro_boton', function() {

    var filterValue = $( this ).attr('data-filter');
    $container.isotope({ filter: filterValue });
  });
  // change is-checked class on buttons


    $('body').on( 'click', '.filtro_boton', function() {
      $('.filtro_boton').removeClass('filtro_activo');
      $(this).addClass('filtro_activo');
    });

//Listener para hacer click en el elemento 


    $('.tarjeta_destinos_hotel').click(function(){

      //Si algun hermano tiene la clase grande se la quitamos y ocultamos el area del mapa
      $(this).siblings().removeClass('vista_detalle_hoteles_destino');
      $(this).siblings().find('.imagen_destino_hotel').removeClass('height_100');
      //$(this).siblings().find('.mapa_destino_hotel').addClass('displayNone');
      $(this).siblings().find('.etiqueta_nombre_destinos_hotel').removeClass('etiqueta_nombre_detalles_hotel');
      $(this).siblings().find('.descripcion_destinos_hotel').addClass('displayNone');

      //Para los iconos en el caso de sitios de interes y que hacer en la pantalla de lugares
      $(this).siblings().find('.imagen_lugar_icono_grande').addClass('imagen_lugar_icono');
      $(this).siblings().find('.imagen_lugar_icono').removeClass('imagen_lugar_icono_grande');



       //Cambiamos la clase de grande a chico o al reves
       $(this).toggleClass('vista_detalle_hoteles_destino');
       //Hacemos la imagen mas pequeña
       $(this).find('.imagen_destino_hotel').toggleClass('height_100');
       //Mostramos su area de mapa o al reves
       //$(this).find('.mapa_destino_hotel').toggleClass('displayNone');

       $(this).find('.descripcion_destinos_hotel').toggleClass('displayNone');

       $(this).find('.etiqueta_nombre_destinos_hotel').toggleClass('etiqueta_nombre_detalles_hotel');

      //Para los iconos en el caso de sitios de interes y que hacer en la pantalla de lugares
       $(this).find('.imagen_lugar_icono').toggleClass('imagen_lugar_icono_grande');
       
       //Llamo a la reorganizaciion del isotope
       $container.isotope('layout');
        
    });
  
};




function inicializa_isotope_galeria() {
  
 var $container=$('.container-isotope').isotope({
                    itemSelector: '.item',
                    layoutMode: 'masonry',
                    masonry: {
                        gutter:1,
                        columnWidth: 140
                    }
                });

              $container.on( 'click', '.item', function() {
                    $('.full_contenido_wrapper').addClass('full_size_gallery');
                     $('.full_contenido_wrapper').removeClass('margen_top_60');
                    //Constructor iosslider
                    
                    $('.iosslider').iosSlider({   
                        desktopClickDrag: true,
                        snapToChildren: true,
                        navNextSelector: $('.iosslider .next'),
                        navPrevSelector: $('.iosslider .prev'),
                        autoSlide: false,
                        autoSlideTimer: 2000,
                        infiniteSlider: true,
                        autoSlideHoverPause: true,
                        autoSlideToggleSelector: $('.iosslider .gallery_play')
                    });

                    $('.iosslider').iosSlider('goToSlide', $(this).attr("id"));
                    $('.container-isotope').addClass("hidden");
                    $('.iosslider').removeClass("hidden");

                });

                //Acciones para cuando se pique en el iosslider
                $(".iosslider").mousedown(function(){
                    $('.iosslider .gallery_play').removeClass("play");
                    $('.iosslider').iosSlider('autoSlidePause');
                });

                //Acciones del boton siguiente iosslider
                $('.iosslider .next').click(function(){
                    $('.iosslider .gallery_play').removeClass("play");
                    $('.iosslider').iosSlider('autoSlidePause');
                });

                //Acciones del boton previo iosslider
                $('.iosslider .prev').click(function(){
                    $('.iosslider .gallery_play').removeClass("play");
                    $('.iosslider').iosSlider('autoSlidePause');
                });

                //Acciones del boton cerrar iosslider
                $('.iosslider .gallery_close').click(function(){
                   $('.iosslider').addClass("hidden");
                   $('.container-isotope').removeClass("hidden");
                   $('.full_contenido_wrapper').removeClass('full_size_gallery');
                   $('.full_contenido_wrapper').addClass('margen_top_60');
                   $('.iosslider').iosSlider('destroy');
                });

                //Acciones del boton play / pause
                $('.iosslider .gallery_play').click(function(event){
                    event.stopPropagation();

                    $(this).toggleClass("play");
                    if($(this).hasClass("play")){
                        $('.iosslider').iosSlider('autoSlidePlay');
                    }
                    else{
                        $('.iosslider').iosSlider('autoSlidePause');
                    }
                });

                $('.iosslider .gallery_play').mousedown(function(event){
                    event.stopPropagation();
                });  
           
};