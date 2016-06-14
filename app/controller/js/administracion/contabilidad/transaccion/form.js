$(document).ready(function(){
	menu("hide");

	$(".fieldbox.textbox").animateTextbox();
	$(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find(".field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $(".link").link({container: $("#contenedor")});

	var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };
    
    var parameters = {url:"/app/controller/php/administracion/contabilidad/transaccion/tipodoc_contable.php", beforeSend: beforeSend, complete: complete};
    $("#tipodoc").parent().select(parameters);
    
    $("#tipodoc").trigger("load");
	
	var reload = function(result, msg)
	{
		if (result) 
		{
			if (msg != "") 
			{
				alert("info","icon-confirmar",msg);
				$("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/transaccion/view.html', name: 'View Transaccion'});
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
        $("#transaccion").find(".button").prop("disabled", true);
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#transaccion").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#transaccion").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

});