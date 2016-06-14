$(document).ready(function(){
	menu("hide");

	$(".fieldbox.textbox").animateTextbox();

	autosize($(".fieldbox.textbox").find("field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});

	var beforeSend = function(){
		$(".cover").addClass("show");
	};

	var complete = function(){
		$(".cover").removeClass("show");
	};

	var scntDiv = $('#dynamicDiv');
	$('#addInput').on('click', function () {
		var item = $('<div class=""></div>');
		var input1 = $('<div class="fieldbox textbox width_3"></div>');
		var input2 = $('<div class="fieldbox textbox width_4"></div>');
		input1.append('<label>NOMBRE</label><input class="field" type="text" name="c_nombre"/>');
		input2.append('<label>DESCRIPCIÓN</label><input class="field" type="text" name="c_descripcion"/>');
		item.append(input1);
		item.append(input2);
		item.append('<a href="javascript:void(0);" class="remInput"><i class="icon-cerrar_a"></i></a>');
		scntDiv.append(item);
		$(".fieldbox.textbox").animateTextbox();
 	});

  	$('#dynamicDiv').on('click', '.remInput', function () {
		$(this).parent('div').remove();
	});

	var reload = function(result, msg)
	{
		if (result)
		{
			if (msg != "")
			{
				alert("info","icon-confirmar",msg);
				$("#contenedor").trigger("load", {url: '/app/view/html/item/view.html', name: 'View item'});
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

   var beforeSend_t = function(){
		$(".cover").addClass("show");
		$("#item").find(".button").prop("disabled", true);
   };

   var complete_t = function(){
		$(".cover").removeClass("show");
		var btn_enable = function(){$("#item").find(".button").prop("disabled", false);};
		setTimeout(btn_enable, 5000);
   };

	$("#item").sendForm({operation:reload, beforeSend: beforeSend_t, complete: complete_t});

});
