$(document).ready(function() {
    var galeriaImagenes = [];
    var currentImageIndex = 0;

    // Cerrar galería al hacer click en el fondo
    $("body").on("click", "#imagen-grande", function(event) {
        if (event.target.id === 'imagen-grande') {
            $('#imagen-grande').hide();
        }
    });

    // Cerrar galería con botón X
    $("body").on("click", "#galeria-close", function(event) {
        event.stopPropagation();
        $('#imagen-grande').hide();
    });

    // Abrir imagen en grande
    $("body").on("click", ".imagen-galeria", function(event) {
        event.stopPropagation();
        event.preventDefault();

        // Obtener todas las imágenes de la galería activa (pestaña visible)
        var galeriaActiva = $(this).closest('[id^="galeria_"]');
        galeriaImagenes = [];

        console.log('Galería activa:', galeriaActiva.attr('id'));

        galeriaActiva.find('.imagen-galeria').each(function() {
            var imgSrc = $(this).find('img').attr('src');
            console.log('Imagen encontrada:', imgSrc);
            if (imgSrc) {
                galeriaImagenes.push(imgSrc);
            }
        });

        console.log('Total imágenes:', galeriaImagenes.length);
        console.log('Array de imágenes:', galeriaImagenes);

        // Encontrar el índice de la imagen clickeada
        var imagenClickeada;
        if ($(this).hasClass("imagen-element")) {
            imagenClickeada = $(this).data("url");
        } else {
            imagenClickeada = $(this).find('img').attr("src");
        }

        console.log('Imagen clickeada:', imagenClickeada);

        currentImageIndex = galeriaImagenes.indexOf(imagenClickeada);
        if (currentImageIndex === -1) currentImageIndex = 0; // Fallback a la primera imagen

        console.log('Índice actual:', currentImageIndex);

        // Mostrar la galería
        mostrarImagenGrande(currentImageIndex);
    });

    // Navegación anterior
    $("body").on("click", "#galeria-prev", function(event) {
        event.stopPropagation();
        currentImageIndex = (currentImageIndex - 1 + galeriaImagenes.length) % galeriaImagenes.length;
        mostrarImagenGrande(currentImageIndex);
    });

    // Navegación siguiente
    $("body").on("click", "#galeria-next", function(event) {
        event.stopPropagation();
        currentImageIndex = (currentImageIndex + 1) % galeriaImagenes.length;
        mostrarImagenGrande(currentImageIndex);
    });

    // Navegación con teclado
    $(document).on("keydown", function(e) {
        if ($('#imagen-grande').is(':visible')) {
            if (e.keyCode === 37) { // Flecha izquierda
                $('#galeria-prev').click();
            } else if (e.keyCode === 39) { // Flecha derecha
                $('#galeria-next').click();
            } else if (e.keyCode === 27) { // ESC
                $('#imagen-grande').hide();
            }
        }
    });

    function mostrarImagenGrande(index) {
        if (!galeriaImagenes || galeriaImagenes.length === 0) {
            console.error('No hay imágenes en la galería');
            return;
        }

        if (index < 0 || index >= galeriaImagenes.length) {
            index = 0;
        }

        var imagenHTML = '<div id="galeria-close">✕</div>' +
                        '<div id="galeria-prev">‹</div>' +
                        '<div id="galeria-next">›</div>' +
                        '<img src="' + galeriaImagenes[index] + '" />' +
                        '<div id="galeria-counter">' + (index + 1) + ' / ' + galeriaImagenes.length + '</div>';

        $('#imagen-grande').html(imagenHTML).css('display', 'flex').show();
    }

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