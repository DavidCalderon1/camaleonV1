// JavaScript Document

(function($){
    
    $.fn.link = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                object: $(this),
                container: null,
                afterAction: null
            }, parameters);
            
            var openLink = function(){
                
                if(parameter.afterAction != null){
                    parameter.afterAction();
                }
                
                var action = function(){
                    parameter.container.trigger("load", {url: parameter.object.data("url"), name: parameter.object.data("name")});
                };
                
                action();
                
            };
            
            parameter.object.on("click", openLink);
            
        });
        
    }
    
}(jQuery));