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

	$("#contenedor").trigger("closePrev", "Form Item");
	$("#contenedor").trigger("closePrev", "Update Item");

	var beforeSend = function(){
		$(".cover").addClass("show");
	};

	var complete = function(){
		$(".cover").removeClass("show");
	};

	var load = function(result, message, data) {
		var scntDiv = $('#dynamicDiv');
		scntDiv.empty();
		$("input[name=referencia]").val(data.referencia);
		$("input[name=nombre]").val(data.nombre);
		$("input[name=descripcion]").val(data.descripcion);
		$("input[name=unidad]").val(data.unidad);
		$("input[name=iva]").val(data.iva);
		var item;
        var input1;
        var input2;
        $.each(data.caracteristica, function (i, feature) {
            var item = $('<div class=""></div>');
            var input1 = $('<div class="fieldbox textbox width_3"></div>');
            var input2 = $('<div class="fieldbox textbox width_4"></div>');
            input1.append('<label>NOMBRE</label><input class="field" type="text" name="c_nombre" value="' + feature[0] + '"/>');
            input2.append('<label>DESCRIPCIÓN</label><input class="field" type="text" name="c_descripcion" value="' + feature[1] + '"/>');
            item.append(input1);
            item.append(input2);
            scntDiv.append(item);
        });

		$(".fieldbox.textbox").find(".find").trigger("focusout");
		autosize($(".fieldbox.textbox").find(".field"));
	};

  	$('#dynamicDiv').on('click', '.remInput', function () {
		$(this).parent('div').remove();
	});

  	var ready = function(){

  		$("#item").loadForm({operation:load, beforeSend: beforeSend, complete: complete});

  	};

  	setTimeout(ready, 0);
	
});
