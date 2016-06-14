// JavaScript Document
/*
* @autor : Juan Camilo Lara, Yordy Gelvez y Cristian Goyeneche
* @editor : Sublime Text 3
* @metodo : JavaScript con jQuery
* @descripcion : controlador js de view.html
*/

$(document).ready(function(){
    
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".link").link({container: $("#contenedor")});

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Sucursal");
    $("#contenedor").trigger("closePrev", "Update Sucursal");
    
    var beforeSend = function()
    {
        $(".cover").addClass("show");
    };
    
    var complete = function()
    {
        $(".cover").removeClass("show");
    };
         
    var load = function(result, message, data)
    { 
     
        $("input[name=nombre]").val(data.nombre);
        $("select[name=ciudad]").html('<option value="' + data.idCiudad + '">' + data.nomCiudad + '</option>');
        $("select[name=departamento]").html('<option value="' + data.idDepartamento + '">' + data.nomDepartamento + '</option>');
        $("select[name=pais]").html('<option value="' + data.idPais + '">' + data.nomPais + '</option>');
        $("input[name=dir]").val(data.dir);
        $("input[name=tel]").val(data.tel);

        $(".fieldbox.textbox").find(".field").trigger("focusout");
       
        autosize($(".fieldbox.textbox").find(".field"));
    };
    
    var ready = function()
    {
        $("#sucursal").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
    };
    
    setTimeout(ready, 0);
    
});