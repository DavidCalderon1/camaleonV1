// JavaScript Document
/*
* @autor : Yordy Gelvez y Steven Medina
* @editor : Sublime Text 3
* @metodo : JavaScript con jQuery
* @descripcion : controlador js de view.html para item
*/
$(document).ready(function(){
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();

    autosize($(".fieldbox.textbox").find("field"));

    $(".link").link({container: $("#contenedor")});

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});

    var beforeSend = function(){
        $(".cover").addClass("show");
    };

    var complete = function(){
        $(".cover").removeClass("show");
    };

    var load = function(result, message, data) {
        var scntDiv = $('#dynamicDiv');
        $("input[name=referencia]").val(data.referencia);
        $("input[name=nombre]").val(data.nombre);
        $("input[name=descripcion]").val(data.descripcion);
        $("input[name=unidad]").val(data.unidad);
        $("input[name=iva]").val(data.iva);
        var item;
        var input1;
        var input2;
        $.each(data.caracteristica, function (i, feature) {
            var item = $('<div class=""></div>');
            var input1 = $('<div class="fieldbox textbox width_3"></div>');
            var input2 = $('<div class="fieldbox textbox width_4"></div>');
            input1.append('<label>NOMBRE</label><input class="field" type="text" name="c_nombre" value="' + feature[0] + '"/>');
            input2.append('<label>DESCRIPCIÓN</label><input class="field" type="text" name="c_descripcion" value="' + feature[1] + '"/>');
            item.append(input1);
            item.append(input2);
            item.append('<a href="javascript:void(0);" class="remInput"><i class="icon-cerrar_a"></i></a>');
            scntDiv.append(item);
            
        });
        $(".fieldbox.textbox").animateTextbox();
        $(".fieldbox.textbox").find(".field").trigger("focusout");
        autosize($(".fieldbox.textbox").find(".field"));
    };

    $('#dynamicDiv').on('click', '.remInput', function () {
        $(this).parent('div').remove();
    });

    var ready = function(){

        $("#item").loadForm({operation:load, beforeSend: beforeSend, complete: complete});

    };

    setTimeout(ready, 0);

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
