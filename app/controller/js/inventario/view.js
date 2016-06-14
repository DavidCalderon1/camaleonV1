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

	$("#contenedor").trigger("closePrev", "Form Inventario");
	$("#contenedor").trigger("closePrev", "Update Inventario");

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

		$(".fieldbox.textbox").find(".find").trigger("focusout");
		autosize($(".fieldbox.textbox").find(".field"));
	};

  	var ready = function(){
  		$("#inventario").loadForm({operation:load, beforeSend: beforeSend, complete: complete});
  	};

  	setTimeout(ready, 100);
	
});
