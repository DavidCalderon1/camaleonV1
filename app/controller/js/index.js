$(document).ready(function(){
    
    $(".notifications").cNotification();
    
    window.alert = function(type, icon, message){
        
        $(".notifications").trigger("show", {type:"msg", subtype:type, icon:icon, message:message});
        
    };
    
    window.confirm = function(type, icon, message, affirmative, negative){
        
        $(".notifications").trigger("show", {type:"confirm", subtype:type, icon:icon, message:message, affirmative:affirmative, negative:negative});
        
    };
                    
    $("#contenedor").contentManagement({cover: $(".cover")});
    
    $("#session").session({container: $("#contenedor"), login: '/app/view/html/usuario/login.html'});

    var action = function(){

        $("#contenedor").trigger("load", {url: '/app/view/html/home.html', name: 'Home'});

    };

    $("#session").trigger("validation", action);
    
    var session_validation = function(){$("#session").trigger("validation", null);};
    
    //setInterval(session_validation, 10000);

});