// JavaScript Document

$(document).ready(function(){
    
    menu("hide");
    
     $(".link").link({container: $("#contenedor")});
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Cuenta Auxiliar");
    $("#contenedor").trigger("closePrev", "Update Cuenta Auxiliar");
    
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
        
        autosize($(".fieldbox.textbox").find(".field"));
        
    };
    
    var ready = function(){$("#cuenta_auxiliar").loadForm({operation: load, beforeSend: beforeSend, complete: complete});};
    
    setTimeout(ready, 0);
    
});