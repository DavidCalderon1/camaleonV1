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
    };

    var load = function(result, message){
        
        if(result.length>0){
            
            $('.result').empty();
        
            $.each(result, function(i, item){

                var frm = $('<form class="infobox transaccion" method="post" action="/app/controller/php/administracion/contabilidad/transaccion/transaccion.php"></form>');
                frm.append('<input name="instanciar" type="hidden" value="true"/>');
                frm.append('<input type="hidden" name="id_transaccion" value="' + item.trs_id + '"/>');
                var subform = $('<div class="subform"></div>');
                subform.append('<div class="espacio left"><h2>' + item.tdc_descripcion + '</h2><p>' + item.trs_fecha + '</p></div>');
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

    $("#transaccion").sendForm({operation:load, beforeSend: beforeSend, complete: complete});

});