// JavaScript DOcument

$(document).ready(function(){

    $('.submenu.a').children('a').click(function(){
        $(this).parent().children('nav').slideToggle();
        
        if($(this).find("span").hasClass("icon-circle-right")){
            $(this).find("span").removeClass("icon-circle-right");
            $(this).find("span").addClass("icon-circle-down");
        }else if($(this).find("span").hasClass("icon-circle-down")){
            $(this).find("span").removeClass("icon-circle-down");
            $(this).find("span").addClass("icon-circle-right");
        }
        if($(this).find("span").hasClass("icon-arrow-right2")){
            $(this).find("span").removeClass("icon-arrow-right2");
            $(this).find("span").addClass("icon-arrow-down-right2");
        }else if($(this).find("span").hasClass("icon-arrow-down-right2")){
            $(this).find("span").removeClass("icon-arrow-down-right2");
            $(this).find("span").addClass("icon-arrow-right2");
        }
       
    });
    
    $("#logout").on("click", function(){
        
        var action = function(){

            $(".menu").trigger("btnMenuHide");

        };

        $("#session").trigger("logout", action);
        
    });
     
    $('.submenu.b').children('a').click(function(){
        $(this).parent().children('div').addClass('open');
        $(".menu").trigger("btnMenuHide");
    });
    
    $('.submenu.b').find('.volver').click(function(){
        $(this).parent().parent().removeClass('open');
        $(".menu").trigger("btnMenuShow");
    });
    
});

