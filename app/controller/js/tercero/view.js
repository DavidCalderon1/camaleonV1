
$(document).ready(function(){
    
    menu("hide");

    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();

    autosize($(".fieldbox.textbox").find("field"));

    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $(".link").link({container: $("#contenedor")});
    
    $("#contenedor").trigger("closePrev", "Form Tercero");
    $("#contenedor").trigger("closePrev", "Update Tercero");
    
    var beforeSend = function()
    {
        $(".cover").addClass("show");
    };
    
    var complete = function()
    {
        $(".cover").removeClass("show");
    };
    
    var load = function(result, message, data)
    { 

        if(data.tipo == "persona"){

            $(".juridica").hide(function(){
                $(".persona").show();
            }); 

            $("#persona").attr('checked', 'checked');
            $("select[name=regimen]").val(data.regimen);
            if(data.gc){
                $("#si").attr('checked', 'checked');
            }
            else{
                $("#no").attr('checked', 'checked');
            }

            $("input[name=nombre]").val(data.nombre);
            $("input[name=apellido]").val(data.apellido);
            $("select[name=tipo_documento]").val(data.tipo_documento)
            $("input[name=numero_documento]").val(data.numero_documento);
            $("select[name=ciudad]").html('<option value="' + data.ciudad_id + '">' + data.ciudad_nombre + '</option>');
            $("select[name=departamento]").html('<option value="' + data.departamento_id + '">' + data.departamento_nombre + '</option>');
            $("select[name=pais]").html('<option value="' + data.pais_id + '">' + data.pais_nombre + '</option>');
            $("input[name=direccion]").val(data.direccion);
            $("input[name=telefono]").val(data.telefono);
        }
        else if(data.tipo == "empresa"){

            $(".persona").hide(function() {
                $(".juridica").show();
            });

            $("#juridica").attr('checked', 'checked');
            $("select[name=regimen]").val(data.regimen);
            if(data.gc){
                $("#si").attr('checked', 'checked');
            }
            else{
                $("#no").attr('checked', 'checked');
            }

            $("input[name=nit]").val(data.nit);
            $("input[name=razon_social]").val(data.razon_social);
            $("select[name=naturaleza]").val(data.naturaleza);
            $("input[name=fecha]").val(data.fechaconst);
            $("select[name=ciudad]").html('<option value="' + data.ciudad_id + '">' + data.ciudad_nombre + '</option>');
            $("select[name=departamento]").html('<option value="' + data.departamento_id + '">' + data.departamento_nombre + '</option>');
            $("select[name=pais]").html('<option value="' + data.pais_id + '">' + data.pais_nombre + '</option>');
            $("input[name=direccion]").val(data.direccion);
            $("input[name=telefono]").val(data.telefono);

        }  
        autosize($(".fieldbox.textbox").find(".field"));
    };
    
    var ready = function()
    {
        $("#tercero").loadForm({operation: load, beforeSend: beforeSend, complete: complete});
    };
    
    setTimeout(ready, 0);
    
});