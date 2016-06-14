$(document).ready(function(){
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $(".link").link({container: $("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Movimiento");
    $("#contenedor").trigger("closePrev", "Update Movimiento");
    
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
});