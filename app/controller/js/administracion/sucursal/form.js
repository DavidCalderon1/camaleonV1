// JavaScript Document
/**
* @autor : Yordy Gelvez, Ronaldo Gonecheche, Juan Camilo
* @editor : Sublime Text 3
* @metodo : JavaScript con jQuery
* @descripcion : Controlador de form.html de sucursal
*/
$(document).ready(function(){
	menu("hide");

	$(".fieldbox.textbox").animateTextbox();
	$(".fieldbox.select").animateSelect();

	autosize($(".fieldbox.textbox").find(".field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});

	var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };
    
    var parameters = {url:"/app/controller/php/localizacion.php", next: $("#dep").parent() ,beforeSend: beforeSend, complete: complete};
    $("#pais").parent().select(parameters);
    parameters.next =  $("#cdd").parent();
    $("#dep").parent().select(parameters);
    parameters.next =  null;
    $("#cdd").parent().select(parameters);
    
    $("#pais").trigger("load");
	
	var reload = function(result, msg)
	{
		if (result) 
		{
			if (msg != "") 
			{
				alert("info","icon-confirmar",msg);
				$("#contenedor").trigger("load", {url: '/app/view/html/administracion/sucursal/view.html', name: 'View Sucursal'});
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

    var beforeSend = function(){
        
        $(".cover").addClass("show");
        $("#sucursal").find(".button").prop("disabled", true);
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#sucursal").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#sucursal").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

});