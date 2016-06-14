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

    var parameters = {url:"/app/controller/php/administracion/sucursal/sucursal.php", beforeSend: beforeSend, complete: complete};
    $("#sucursal").parent().select(parameters);
    
    $("#sucursal").trigger("load");

    var load = function(result, message, data)
    {
        $('#detalle').val(data.detalle);
        $('#sucursal').val(data.sucursal);
        if (data.req == "tercero") {
            $('#req_tercero').attr('checked', 'checked');
        }else{
            $('#req_activo').attr('checked', 'checked');
        };
        $('#codtoa').val(data.codtoa);
        $('#cuenta').val(data.cuenta);
        $('#debe').val(data.debe);
        $('#haber').val(data.haber);

        $(".fieldbox.textbox").find(".field").trigger("focusout");
        autosize($(".fieldbox.textbox").find(".field"));
    };
    
    var ready = function()
    {
        $("#movimiento").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
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

    $("#movimiento").sendForm({operation:reload, beforeSend: beforeSend, complete: complete});
});