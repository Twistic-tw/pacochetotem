
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {
    //Selector esferas inferior hoy, mañana, pasado
    $('.selector_dia div').click(function(){
        $('.selector_dia .selected').removeClass('selected');
        $(this).addClass('selected');
        $('#owl-tiempo').trigger("owl.goTo", $(this).data('owlid'));
    });

});
