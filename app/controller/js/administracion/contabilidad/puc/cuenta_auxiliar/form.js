$(document).ready(function(){
    
    menu("hide");
    
    $(".fieldbox.textbox").animateTextbox();
    $(".fieldbox.select").animateSelect();
    //$(".fieldbox .radiobutton").animateRadiobutton();
    
    autosize($(".fieldbox.textbox").find(".field"));
    
    $(".btnCloseContent").btnCloseContent({content:$("#contenedor")});
    
    // result action
    var reload = function(result, msg){
        
        if(result){
            
            if(msg != ""){
                
                alert("info","icon-confirmar",msg);
                
                $("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/puc/cuenta_auxiliar/view.html', name: 'View Cuenta Auxiliar'});
                
            }
            
        }else{
        
            if(msg != ""){
                
                alert("error","icon-cerrar_a",msg);
                
            }
            
        }
        
    };
    
    // form send
    var beforeSend_a = function(){
        
        $(".cover").addClass("show");
        $("#cuenta_auxiliar").find(".button").prop("disabled", true);
        
    };
    
    var complete_a = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#cuenta_auxiliar").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#cuenta_auxiliar").sendForm({operation:reload, beforeSend: beforeSend_a, complete: complete_a});
    
});

