/* aqui se aglutinan las distintas funciones de animaciones personalizadas */
 

/** esta funcion se utiliza para desplegar el wrapper de los detalles meteorologicos */
$.fn.animateEffect1 = function(animateSpeed){
    
    animateSpeed = animateSpeed || 300;
    console.log($("#meteoWrapper").offset().top);
    
    if ( $("#meteoWrapper").offset().top > 1000) 
    {    
        if ($(this).is(":visible")){
            $(this).finish().animate({
                top: "+=10px",
                left: "-=10px",
                opacity: "0"
            },animateSpeed-100,function(){
                $(this).css("display", "none").toggleClass("clickActive");
            });
        }
        else {
            $(this).css("display", "block");
            $(this).finish().animate({
                top: "-=10px",
                left: "+=10px",
                opacity: "1"
            },animateSpeed, function(){
                $(this).toggleClass("clickActive");
            });
        }
    }
    else 
    {   
        //debo desplegarlo hacia abajo
        if ($(this).is(":visible"))
        {
            $(this).finish().animate({
                top: "-=10px",
                left: "+=10px",
                opacity: "0"
            },animateSpeed-100,function(){
                $(this).css("display", "none").toggleClass("clickActive");
            });
        }
        else 
        {
            $(this).css("display", "block");
            $(this).finish().animate({
                top: "+=10px",
                left: "-=10px",
                opacity: "1"
            },animateSpeed, function(){
                $(this).toggleClass("clickActive");
            });
        }
    }
}


/** esta función se emplea en index para desplegar el menu de languaje switch */
$.fn.animateEffect2 = function(animateSpeed){
    var elementHeight = 0;
    $(this).children().each(function(){
        elementHeight += $(this).height();
    });
    
    animateSpeed = animateSpeed || 300;
    
    if ($("#langSwitch").offset().top > 1000) 
    { 
        if ($(this).height()>0){
            //es visible

            $(this).finish().animate({
                height : "0px",
                bottom : "-=10px",
                paddingBottom: "0px"

            }, animateSpeed-100 , function(){
                $(this).toggleClass("clickActive");
            });
        }
        else {
            //NO ES VISIBLE
            $(this).finish().animate({
                height: ""+elementHeight+"px",
                bottom: "+=10px",
                paddingBottom: "20px"
            }, animateSpeed , function(){
                $(this).toggleClass("clickActive");;
            });
        }
    }
    else
    {
        if ($(this).height()>0){
            //es visible
            $(this).finish().animate({
                height : "0px",
                top : "-=30px",
                paddingTop: "0px"

            }, animateSpeed-100 , function(){
                $(this).toggleClass("clickActive");
            });
        }
        else {
            //NO ES VISIBLE
            $(this).finish().animate({
                height: ""+elementHeight+"px",
                top: "+=30px",
                paddingTop: "20px"
            }, animateSpeed , function(){
                $(this).toggleClass("clickActive");;
            });
        }
    }
}




