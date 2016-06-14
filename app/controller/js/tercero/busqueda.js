$(document).ready(function(){

	$(".fieldbox.textbox").animateTextbox();

	autosize($(".fieldbox.textbox").find("field"));

	$(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    $('.result').empty();
    
    $("#filtro").find(".placeholder").click(function(){
        $("#filtro").find(".option").fadeToggle();
    });
    
    $("#filtro").find(".option").fadeOut();

	$('.natural').fadeIn();
    $('.juridica').fadeIn();
    $("#opc_uno").attr('checked', 'checked');
    
    $('input[name=tipo]').click(function(){
        if($(this).val() == 'natural'){
            $('.juridica').fadeOut(function(){
                $('.natural').fadeIn();
            });

        }
        else if($(this).val() == 'juridica'){
            $('.natural').fadeOut(function(){
                $('.juridica').fadeIn();
            });
        }
        else
        {
            $('.natural').fadeIn();
            $('.juridica').fadeIn();
        }
    });

    var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };

    var reload = function(result, msg)
    {
        if (result) 
        {
            if (msg != "") 
            {
                alert("info","icon-confirmar",msg);
                $("#contenedor").trigger("load", {url: '/app/view/html/tercero/view.html', name: 'View Tercero'});
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
                if(item.hasOwnProperty('empr_nit'))
                {
                    var frm = $('<form class="infobox tercero juridica" method="post" action="/app/controller/php/tercero/tercero.php"></form>');
                    frm.append('<input name="instanciar" type="hidden" value="true"/>');
                    frm.append('<input type="hidden" name="id" value="' + item.trc_id + '"/>');
                    var subform = $('<div class="subform"></div>');
                    subform.append('<div class="espacio left"><h2>' + item.empr_rs + '</h2><p>' + item.empr_nit + '</p></div>');
                    subform.append('<div class="espacio right"><a class="link" href="#"><i class="icon-ver"></i></a></div>');
                    frm.append(subform);
                }
                else if(item.hasOwnProperty('prn_numdoc'))
                {
                    var frm = $('<form class="infobox tercero natural" method="post" action="/app/controller/php/tercero/tercero.php"></form>');
                    frm.append('<input name="instanciar" type="hidden" value="true"/>');
                    frm.append('<input type="hidden" name="id" value="' + item.trc_id + '"/>');
                    var subform = $('<div class="subform"></div>');
                    subform.append('<div class="espacio left"><h2>' + item.prn_nombre + ' ' + item.prn_apellido + '</h2><p>' + item.prn_numdoc + '</p></div>');
                    subform.append('<div class="espacio right"><a class="link" href="#"><i class="icon-ver"></i></a></div>');
                    frm.append(subform);
                }

                frm.sendForm({operation:reload, beforeSend: beforeSend, complete: complete});

                frm.find(".link").click(function(){frm.submit();});

                $('.result').append(frm);
            });
            
            alert("info","icon-confirmar",message);
            
        }else{
            
            alert("error","icon-cerrar_a",message);
            
        }
		
    };

    $("#tercero").sendForm({operation:load, beforeSend: beforeSend, complete: complete});

});  
    


