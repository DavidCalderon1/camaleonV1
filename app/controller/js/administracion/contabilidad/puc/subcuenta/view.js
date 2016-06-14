// JavaScript Document

$(document).ready(function(){
    
    menu("hide");
    
     $(".link").link({container: $("#contenedor")});
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Subcuenta");
    $("#contenedor").trigger("closePrev", "Update Subcuenta");
    //$("#contenedor").trigger("closePrev", "Delete Cuenta Auxiliar");
    
    var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };
    
    var load = function(result, message, data){
        
        $("#cnt_id").val(data.cnt_id);
        $("#cnt_nombre").val(data.cnt_nombre);
        $("#id").val(data.id);
        $("#nombre").val(data.nombre);
        $("#descripcion").html(data.descripcion);
        $("#ajuste").val(data.ajuste);
        
        autosize($(".fieldbox.textbox").find(".field"));
        
    };
    
    var ready = function(){$("#subcuenta").loadForm({operation: load, beforeSend: beforeSend, complete: complete});};
    
    setTimeout(ready, 0);
    
});