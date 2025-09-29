$(document).ready(function() {
    $("body").click(function() {
        if ($('#imagen-grande').is(':visible')) {
            $('#imagen-grande').hide();
        }
    });

    $("body").on("click", ".imagen-galeria", function(event) {
        event.stopPropagation();
        event.preventDefault();
        
        if ( $(this).hasClass("imagen-element") ) {
            $('#imagen-grande').show();

            source = $(this).data("url");
            imagen = '<img src="' + source + '" width="1024px" />';
            $('#imagen-grande').html(imagen);
        }
        else {
            $('#imagen-grande').show();

            imagen = $(this).find('img');
            source = imagen.attr("src");
            imagen = '<img src="' + source + '" width="1024px" />';

            $('#imagen-grande').html(imagen);
        }
        

    });
//                $(".tabs").tabs({
//                });
    $(".tabs").tabs();
});

function rotar_fotos_galeria() {
    $(".imagen-galeria").each(function() {
        grados = 1 + Math.floor(Math.random() * 20);
        positivo = 1 + Math.floor(Math.random() * 2);
        if (positivo > 1)
            grados = -1 * grados;

        $(this).css('transform', 'rotate(' + grados + 'deg)');
    });
}