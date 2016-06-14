// JavaScript Document
/*
* @autor : Yordy Gelvez y Steven Medina
* @editor : Sublime Text 3
* @metodo : JavaScript con jQuery
* @descripcion : controlador js de view.html para item
*/
$(document).ready(function(){
	menu("hide");

	$(".fieldbox.textbox").animateTextbox();

	autosize($(".fieldbox.textbox").find("field"));

	$(".link").link({container: $("#contenedor")});

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});

	var beforeSend = function(){
        $(".cover").addClass("show");
    };
    
    var complete = function(){
        $(".cover").removeClass("show");
    };
    
    var parameters = {url:"/app/controller/php/item/item.php", beforeSend: beforeSend, complete: complete};
    $("#item").parent().select(parameters);    
    
    $("#item").trigger("load");

	var load = function(result, message, data) {

		$('#fecha').val(data.fecha);
		$('#tipo').val(data.tipo);
		$('#item').val(data.item);
		$('#cantidad').val(data.cantidad);
		$('#valorunit').val(data.valorunit);
		
		$(".fieldbox.textbox").find(".field").trigger("focusout");
        $(".fieldbox.select").find("select").trigger("selection");
        autosize($(".fieldbox.textbox").find(".field"));
	};

  	var ready = function(){
  		$("#inventario").loadForm({operation:load, beforeSend: beforeSend, complete: complete});
  	};

  	setTimeout(ready, 100);


  	//envio del formulario

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

    var beforeSend = function(){
        
        $(".cover").addClass("show");
        $("#inventario").find(".button").prop("disabled", true);
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#inventario").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#inventario").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});
	
});
