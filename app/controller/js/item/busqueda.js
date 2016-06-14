$(document).ready(function(){

$(".fieldbox.textbox").animateTextbox();
	autosize($(".fieldbox.textbox").find("field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
	$("#filtro").find(".placeholder").click(function(){
		$("#filtro").find(".option").fadeToggle();
	});
	$("#filtro").find(".option").fadeOut();

	var beforeSend = function(){
		$(".cover").addClass("show");
	};
	var complete = function(){
		$(".cover").removeClass("show");
	};
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
	};
	var load = function(result, message){
		if(result.length>0){
		var tipo = $('input[name=tipo]').val();
		if (tipo == 'materia_prima') {
			tipo = 'Materia Prima';
		}else if(tipo == 'producto_terminado'){
			tipo = 'Producto Terminado';
		}else{
			tipo = 'Producto Procesado';
		}

		$('.result').empty();

		$.each(result, function(i, item){
			var frm = $('<form class="infobox item" method="post" action="/app/controller/php/item/item.php"></form>');
	        frm.append('<input type="hidden" name="instanciar" value="true"/>');
	        frm.append('<input type="hidden" name="id" value="' + item.itm_id + '"/>');
	        var subform = $('<div class="subform"></div>');
	        subform.append('<div class="espacio left"><h2>' + item.mp_nombre + '</h2><p>' + tipo + '</p></div>');
	        subform.append('<div class="espacio right"><a class="link" href="#"><i class="icon-ver"></i></a></div>');
	        frm.append(subform);
	        
		    frm.sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

		    frm.find(".link").click(function(){frm.submit();});

		    $('.result').append(frm);
		});

		alert("info","icon-confirmar",message);

		}else{

			alert("error","icon-cerrar_a",message);

		}

	};

	$("#item").sendForm({operation:load, beforeSend: beforeSend, complete: complete});

});
