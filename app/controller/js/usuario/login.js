// JavaScript Document

$(document).ready(function(){

    $(".fieldbox").animateTextbox();
    
    $("#session").click(function(){
        $("#login").fadeToggle('slow','swing');
    });
    
    menu("hide");
    
    // result action
    var reload = function(result, msg){
        
        if(result){
            
            //if(msg != ""){alert(msg)}
            document.location.href='/';
            
        }else{
            
            alert("error","icon-cerrar_a",msg);
            
        }
        
    };
    
    // form send
    var beforeSend = function(){
        
        $(".cover").addClass("show");
        $("#login").find(".button").prop("disabled", true);
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#login").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 8000);
        
    };
    
	$("#login").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

});