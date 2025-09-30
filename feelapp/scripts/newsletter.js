$( document ).ready(function() {

    $('body').on('click','.slider.round',function (e) {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).parent().children('input').prop('checked', false);
        }else{
            $(this).addClass('active');
            $(this).parent().children('input').prop('checked', true);
        }
    });

    $('body').on('change','.checkbox > .formbuilder-checkbox',function(e){

        var div =  $('.slider.round');

        if(div.hasClass('active')){
            div.removeClass('active');
            div.parent().children('input').prop('checked', false);
        }else{
            div.addClass('active');
            div.parent().children('input').prop('checked', true);
        }

    });

});