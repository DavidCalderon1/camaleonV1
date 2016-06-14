// JavaScript Document

$(document).ready(function(){
    
    menu("hide");
    
    $(".fieldbox.textbox").animateTextbox();
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
     var beforeSend = function(){
        
        $(".cover").addClass("show");
        
    };
    
    var complete = function(){
        
        $(".cover").removeClass("show");
        
    };
    
     var reload_cntaux = function(result, msg){

        if(result){
            
            $('.resultbox').empty();            
            $("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/puc/cuenta_auxiliar/view.html', name: 'View Cuenta Auxiliar'});

        }

    };

    var reload_scnt = function(result, msg){

        if(result){

            $('.resultbox').empty();
            $("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/puc/subcuenta/view.html', name: 'View Subcuenta'}); 

        }

    };
    
    var disposal = function(item){
        
        var rbx_aa = $('<div class="resultbox_aa"></div>');
        var rbx_aaa = $('<div class="resultbox_aaa"></div>');
        if(item.subentity){rbx_aaa.append('<i class="icon icon-plus_a"></i>');}
        if(!(item.nativa)){
            if(item.tipo === "subcuenta"){
                var form = $('<form method="post" action="/app/controller/php/administracion/contabilidad/subcuenta.php"></form>');
                form.sendForm({operation:reload_scnt, beforeSend: beforeSend, complete: complete});
            }else if(item.tipo === "cuenta_auxiliar"){
                var form = $('<form method="post" action="/app/controller/php/administracion/contabilidad/cuenta_auxiliar.php"></form>');
                form.sendForm({operation:reload_cntaux, beforeSend: beforeSend, complete: complete});
            }
            form.append('<input type="hidden" name="instanciar" value="true">');
            form.append('<input type="hidden" name="id" value="' + item.id + '">');
            form.append('<span class="id"> ' + item.id + '</span>');
            form.append('<span class="name"> ' + item.nombre + '</span>');
            form.find(".name").on("click", function(){
                form.submit();
            });
            rbx_aaa.append(form);
            rbx_aa.append(rbx_aaa);
        }else if(item.nativa){
            rbx_aaa.append('<span class="id"> ' + item.id + '</span>');
            rbx_aaa.append('<span class="name"> ' + item.nombre + '</span>');
            rbx_aa.append(rbx_aaa);
            rbx_aa.append('<div class="descripcion"></div>')
            rbx_aa.find(".descripcion").append('<p>' + item.descripcion + '</p>');
            rbx_aa.find(".descripcion").description({content: $(".contentDescrip")});
        }
        
        return rbx_aa;
        
    };
    
    var parameters = {
        shot: $("#puc").find("button"), 
        url:"/app/controller/php/administracion/contabilidad/puc.php", 
        disposal: disposal, 
        beforeSend: beforeSend, 
        complete: complete
    };
    
    var rbx= $('<div class="resultbox_a" data-entity="clase"></div>');
    rbx.rowdespl(parameters);
    
    $('.resultbox').append(rbx);
    
    setTimeout(function(){$('.resultbox_a').trigger("slide");}, 0);
       
});
