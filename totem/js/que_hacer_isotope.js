$( function() {
  // init Isotope

  // bind filter button click


  $('#filters_quehacer').on( 'click', '.filtro_boton_quehacer', function() {

    var $container = $('.isotope_que_hacer').isotope({
    itemSelector: '.tarjeta_nueva',
    layoutMode: 'fitRows'
    });

    var filterValue = $( this ).attr('data-filter');
    $container.isotope({ filter: filterValue });
  });
  // change is-checked class on buttons


    $('body').on( 'click', '.filtro_boton_quehacer', function() {
      $(".button-group").find('.filtro_activo_quehacer').removeClass('filtro_activo_quehacer');
      $( this ).addClass('filtro_activo_quehacer');
    });


    //Oculto la opcion de abrir el tiempo si tengo un microsite abierto

    $('body').on( 'click', '.loadComercioDetalles', function(){

        setTimeout(function(){
         
          $('#meteoWrapper').addClass('displayNone');
        }, 500);

    });

    //Muestro el contenedor de isotope que tengo oculto
    $('body').on( 'click', '.boton_atras_quehacer', function(){
        setTimeout(function(){
         
          $('#meteoWrapper').removeClass('displayNone');
        }, 200);
    });

  //Oculto el scroll del contedenor isotope que hacer, cuando exista un boton .back (pantallas sobre expuestas)
  $('body').on( 'click', function(){

    setTimeout(function(){

        if ($('.back').length) {
          
            $('.scroll_quehacer').addClass('displayNone');

        }else{
          
           $('.scroll_quehacer').removeClass('displayNone');
        }
      }, 500);

    });

});
