
$(document).ready(function(){
    
    menu("hide");

	$(".fieldbox.textbox").animateTextbox();

	autosize($(".fieldbox.textbox").find("field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    //$('.result').empty();

    var beforeSend = function(){
        $(".cover").addClass("show");
    };
    
    var complete = function(){
        $(".cover").removeClass("show");
    };
    
    var parameters = {url:"/app/controller/php/item/item.php", beforeSend: beforeSend, complete: complete};
    $("#item").parent().select(parameters);    
    
    $("#item").trigger("load");

    var reload = function(result, msg)
    {
        if (result) 
        {
            if (msg != "") 
            {
                alert("info","icon-confirmar",msg);
                $("#contenedor").trigger("load", {url: '/app/view/html/inventario/view.html', name: 'View Inventario'});
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
            
            $('.result').empty();
            var sumcan = 0;
            var promedio = 0;
        
            $.each(result, function(i, item){                

                var tr = $('<tr></tr>');
                var td = $('<td></td>');
                var td2 = $('<td></td>');
                var td3 = $('<td></td>');
                var td4 = $('<td></td>');
                td.append(item.iv_fecha);                
                td2.append(item.iv_entradasalida);                
                td3.append(item.iv_cantidad);                
                td4.append(item.iv_valorunitario);
                tr.append(td);
                tr.append(td2);
                tr.append(td3);
                tr.append(td4);

                if(item.iv_entradasalida == "ENTRADA"){
                    sumcan += parseInt(item.iv_cantidad); 
                    promedio += parseInt(item.iv_valorunitario); 
                }else{
                    sumcan -= parseInt(item.iv_cantidad);
                    promedio -= parseInt(item.iv_valorunitario); 
                }

                //frm.sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

                //frm.find(".link").click(function(){frm.submit();});

                $('.result').append(tr);
            });
            $('.result').append('<tr><th>Cantidad Total</th><td>'+ sumcan +'</td></tr>');            
            promedio = promedio / sumcan;
            $('.result').append('<tr><th>Valor Promedio</th><td>'+ promedio +'</td></tr>');
            
            alert("info","icon-confirmar",message);
            
        }else{
            
            alert("error","icon-cerrar_a",message);
            
        }
		
    };

    $("#inventario").sendForm({operation:load, beforeSend: beforeSend, complete: complete});

});  