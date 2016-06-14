// JavaScript Document

$(document).ready(function(){
    
    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();
    
    menu("hide");
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };
    
    var load = function(result, message, data){
        
        $("#cnt_id").val(data.cnt_id);
        $("#id").val(data.id);
        $("#nombre").val(data.nombre);
        $("#descripcion").html(data.descripcion);
        $("#ajuste").val(data.ajuste);       
        
        $(".fieldbox.textbox").find(".field").trigger("focusout");
        $(".fieldbox.select").find("select").trigger("selection");
        autosize($(".fieldbox.textbox").find(".field"));
        
    };
    
    var ready = function(){$("#subcuenta").loadForm({operation: load, beforeSend: beforeSend, complete: complete});};
    
    setTimeout(ready, 0);
    
    // result action
    var reload = function(result, msg){
        
        if(result){
            
            if(msg != ""){
                
                alert("info","icon-confirmar",msg);
                
                $("#contenedor").trigger("close");
                
            }
            
        }else{
        
            if(msg != ""){
                
                alert("error","icon-cerrar_a",msg);
                
            }
            
        }
        
    };
    
    // form send
    var beforeSend_a = function(){
        
        $(".cover").addClass("show");
        $("#subcuenta").find(".button").prop("disabled", true);
        
    };
    
    var complete_a = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#subcuenta").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#subcuenta").sendForm({operation:reload, beforeSend: beforeSend_a, complete: complete_a});
    
});