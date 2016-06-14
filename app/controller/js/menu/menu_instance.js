// JavaScript Document

var menu = function(action){

    var menu = $(".box").children(".menu");
    
    menu.menu({btn_menu: $("#btn_menu"), content: $("#contenedor")});
    
    if(action == "show"){
        
        menu.trigger("loadMenu");
    
    }else if(action == "hide"){
    
        menu.trigger("btnMenuHide");
    
    }

};
