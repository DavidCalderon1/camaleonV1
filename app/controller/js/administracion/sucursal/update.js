// JavaScript Document
/**
* @autor : Juan Camilo, Yordy Gelvez y Ronaldo Goyeneche
* @editor : Sublime Text 3
* @metodo : JavaScript con jQuery
* @descripcion : Se realiza la codificacion del formulario sucursal.
*/

$(document).ready(function(){
    
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".link").link({container: $("#contenedor")});
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    var beforeSend = function()
    {
        $(".cover").addClass("show");
    };
    
    var complete = function()
    {
        $(".cover").removeClass("show");
    };
    
    var loadSelect = function(pais, dep, cdd) {
        var parameters = {url:"/app/controller/php/localizacion.php",
        value: pais, next: $("#dep").parent(), beforeSend: beforeSend, complete: complete};
        $("#pais").parent().select(parameters);
        parameters.next =  $("#cdd").parent();
        parameters.value = dep;
        $("#dep").parent().select(parameters);
        parameters.next =  null;
        parameters.value = cdd;
        $("#cdd").parent().select(parameters);
        
        $("#pais").trigger("load");
        $("#pais").trigger("change");
    }

    var load = function(result, message, data)
    { 
        $("input[name=nombre]").val(data.nombre);
        loadSelect(data.idPais, data.idDepartamento, data.idCiudad);
        $("input[name=dir]").val(data.dir);
        $("input[name=tel]").val(data.tel);
        
        $(".fieldbox.textbox").find(".field").trigger("focusout");
        $(".fieldbox.select").find("select").trigger("selection");
        autosize($(".fieldbox.textbox").find(".field"));
    };

    var ready = function()
    {
        $("#sucursal").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
    };

    
    setTimeout(ready, 0);

    //Desde estas lineas de codigo es el envio de datos

    var reload = function(result, msg)
    {
        if (result) 
        {
            if (msg != "") 
            {
                alert("info","icon-confirmar",msg);
                $("#contenedor").trigger("close");
            }
        }
        else
        {
            if(msg != "")
            {
                alert("error","icon-cerrar_a",msg);
            }
        }
    }

    var beforeSend_s = function(){
        
        $(".cover").addClass("show");
        $("#sucursal").find(".button").prop("disabled", true);
        
    };
    
    var complete_s = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#sucursal").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
    $("#sucursal").sendForm({operation:reload, beforeSend: beforeSend_s, complete: complete_s});
    
});