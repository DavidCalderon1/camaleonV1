$(document).ready(function(){
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $(".link").link({container: $("#contenedor")});
    
    var beforeSend = function()
    {
        $(".cover").addClass("show");
    };
    
    var complete = function()
    {
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
    };
    
    var load = function(result, message, data)
    {
        $('#tipodoc').val(data.tipodoc);
        $('#fecha').val(data.fecha);
        $('#descripcion').val(data.descripcion);
        $(".fieldbox.textbox").find(".field").trigger("focusout");
        autosize($(".fieldbox.textbox").find(".field"));
    };
    
    var ready = function()
    {
        $("#transaccion").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
    };
    
    setTimeout(ready, 0);

    //send form

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
    
    $("#transaccion").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});



});