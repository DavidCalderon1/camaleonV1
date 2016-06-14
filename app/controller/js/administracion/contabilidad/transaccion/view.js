$(document).ready(function(){
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $(".link").link({container: $("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Transaccion");
    $("#contenedor").trigger("closePrev", "Update Transaccion");
    
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
                $("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/transaccion/movimiento/view.html', name: 'View Transaccion'});
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
        var debe = 0;
        var haber = 0;
        var movimientos = data.movimientos;
        $('#tipodoc').val(data.tipodoc);
        $('#fecha').val(data.fecha);
        $('#descripcion').val(data.descripcion);
        if (movimientos.length > 0) {
            $('.result').empty();
            $('.resumenmov').empty();
            $.each(movimientos, function (i,item) {
                var frm = $('<form class="infobox movimiento" method="post" action="/app/controller/php/administracion/contabilidad/transaccion/movimiento.php"></form>');
                frm.append('<input name="instanciar" type="hidden" value="true"/>');
                frm.append('<input type="hidden" name="id_movimiento" value="' + item.id + '"/>');
                var subform = $('<div class="subform"></div>');
                var espacio_a = $('<div class="espacio a"></div>');
                var espacio_b = $('<div class="espacio b"></div>');
                espacio_a.append('<div class="subespacio"><a class="link" href="#"><i class="icon-ver"></i></a></div>');
                espacio_a.append('<div class="campo vab"><label>SUCURSAL</label><div class="text">'+ item.sucursal +'</div></div>');
                espacio_a.append('<div class="campo vab"><label>'+item.req+'</label><div class="text">'+ item.codtoa +'</div></div>');
                espacio_b.append('<div class="campo vab"><label>CUENTA</label><div class="text">'+ item.cuenta +'</div></div>');
                espacio_b.append('<div class="campo"><label>DEBE</label><div class="text">'+ parseFloat(item.debe) +'</div></div>');
                espacio_b.append('<div class="campo"><label>HABER</label><div class="text">'+ parseFloat(item.haber) +'</div></div>');
                subform.append(espacio_a);
                subform.append(espacio_b);
                frm.append(subform);
                frm.sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

                frm.find(".link").click(function(){frm.submit();});

                debe += parseFloat(item.debe);
                haber += parseFloat(item.haber);

                $('.result').append(frm);
            });
            var totaldebehaber = $('.resumenmov');
            totaldebehaber.append('<h3>TOTAL</h3>');
            var campodebe = $('<div class="campo"></div>');
            campodebe.append('<label>DEBE</label><div class="text">'+ debe +'</div>');
            var campohaber = $('<div class="campo"></div>');
            campohaber.append('<label>HABER</label><div class="text">'+ haber +'</div>');
            totaldebehaber.append(campodebe);
            totaldebehaber.append(campohaber);

        };
        autosize($(".field.text").find(".field"));
    };
    
    var ready = function()
    {
        $("#transaccion").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
    };
    
    setTimeout(ready, 0);
});