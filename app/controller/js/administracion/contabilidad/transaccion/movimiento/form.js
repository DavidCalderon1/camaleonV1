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
    
    var parameters = {url:"/app/controller/php/administracion/sucursal/sucursal.php", beforeSend: beforeSend, complete: complete};
    $("#sucursal").parent().select(parameters);
    
    $("#sucursal").trigger("load");
	
	var reload = function(result, msg)
	{
		if (result) 
		{
			if (msg != "") 
			{
				alert("info","icon-confirmar",msg);
				$("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/transaccion/movimiento/view.html', name: 'View Movimiento'});
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
        $("#movimiento").find(".button").prop("disabled", true);
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#movimiento").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#movimiento").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

});