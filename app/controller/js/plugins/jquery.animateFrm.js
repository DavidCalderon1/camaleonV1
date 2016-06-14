// JavaScript Document

(function($){
    
    $.fn.animateTextbox = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                textbox: $(this)
            }, parameters);
            
            var focus = function(){
                
                parameter.textbox.find(".field").focus();
                
                focusin();
                
            };
            
            var focusin = function(){
                
                var textbox = parameter.textbox.find(".field");
                
                textbox.addClass("active");
                
                if(textbox.val()!=""){
                    textbox.parent().find("label").removeClass("hastext");
                    textbox.parent().find("label").addClass("active");
                    
                }else{
                    if(!(textbox.parent().find("label").hasClass("focusin"))){
                        textbox.parent().find("label").addClass("focusin");
                    }
                }
                
            };
            
            var focusout = function(){
                
                var textbox = parameter.textbox.find(".field");
                
                textbox.removeClass("active");
                
                textbox.parent().find("label").removeClass("focusin");
                textbox.parent().find("label").removeClass("active");
                
                if(textbox.val()!=""){
                    textbox.parent().find("label").addClass("hastext");
                }else{
                    textbox.parent().find("label").removeClass("hastext");
                }
                
            };
            
            parameter.textbox.find("label").on("click", focus);
            
            parameter.textbox.find(".field").on("focusin", focusin);
            parameter.textbox.find(".field").on("focusout", focusout);
            
        });
        
    }
    
    $.fn.animateSelect = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                select: $(this)
            }, parameters);
            
            var selection = function(){
            
                parameter.select.find(".field").addClass("selection");
            
            };
            
            var normal = function(){
            
                parameter.select.find(".field").removeClass("selection");
            
            };
            
            var change = function(){
            
                if(parameter.select.find(".field").val() == ""){
                
                    normal();
                    
                }else if(parameter.select.find(".field").val() != ""){
                
                    selection();
                    
                }
            
            };
            
            parameter.select.find(".field").on("selection", selection);
            parameter.select.find(".field").on("normal", normal);
            parameter.select.find(".field").on("change", change);
            
        });
        
    }
    
}(jQuery));