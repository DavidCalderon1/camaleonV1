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
                
                $("#contenedor").trigger("load", {url: '/app/view/html/administracion/contabilidad/puc/subcuenta/view.html', name: 'View Subcuenta'});
                
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
        $("#subcuenta").find(".button").prop("disabled", true);
        
    };
    
    var complete_a = function(){
        
        $(".cover").removeClass("show");
        var btn_enable = function(){$("#subcuenta").find(".button").prop("disabled", false);};
        setTimeout(btn_enable, 5000);
        
    };
    
	$("#subcuenta").sendForm({operation:reload, beforeSend: beforeSend_a, complete: complete_a});
    
});