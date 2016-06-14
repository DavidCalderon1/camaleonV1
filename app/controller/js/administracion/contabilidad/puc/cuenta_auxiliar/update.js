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
        
        $("#scnt_id").val(data.scnt_id);
        $("#scnt_nombre").val(data.scnt_nombre);
        $("#id").val(data.id);
        $("#nombre").val(data.nombre);
        $("#descripcion").html(data.descripcion);
        $("#ajuste").val(data.ajuste);
        $("#reqta").val("" + data.reqta + "");
        
        if(data.estado){
            $("#std_activo").attr('checked', 'checked');;
        }else{
            $("#std_inactivo").attr('checked', 'checked');;
        }
        
        
        $(".fieldbox.textbox").find(".field").trigger("focusout");
        $(".fieldbox.select").find("select").trigger("selection");
        autosize($(".fieldbox.textbox").find(".field"));
        
    };
    
    var ready = function(){$("#cuenta_auxiliar").loadForm({operation: load, beforeSend: beforeSend, complete: complete});};
    
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
        $("#cuenta_auxiliar").find(".button").prop("disabled", true);
        
    };
    
    var complete_a = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#cuenta_auxiliar").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#cuenta_auxiliar").sendForm({operation:reload, beforeSend: beforeSend_a, complete: complete_a});
    
});