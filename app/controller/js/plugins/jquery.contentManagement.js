// JavaScript Document

(function($){
    
    $.fn.contentManagement = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                
                object: $(this),
                cover: null,
                container: []
                
            }, parameters);
            
            var load = function(event, address){
                
                event.preventDefault();
                
                $.ajax({
                    url: address.url,
                    success: function(response){
                        parameter.object.prepend(response);
                    },
                    error: function(request, errorType, errorMessage){
                        console.error('Error: ' + errorType +  '<br> whit message: ' + errorMessage);
                    },
                    timeout: 10000,
                    beforeSend: function(){
                        
                        if((parameter.container.length) >= 1){
                            parameter.container[(parameter.container.length) - 1].content = parameter.object.html();
                        }
                        
                        parameter.object.empty();
                        parameter.cover.addClass("show");
                    },
                    complete:function(){
                        
                        $(document).ready(function(){
                            
                            parameter.container.push({name: address.name, content: parameter.object.html()});
                            
                            parameter.cover.removeClass("show");
                            
                        });
                        console.info(address.name + ' - Content Loaded!');
                        
                    }
                });
                
            };
            
            var closePrevious = function(event, nameContent){
                
                var action = function(){
                
                    console.info(parameter.container[parameter.container.length - 1].name + " - Content Close!");
                    parameter.container.pop();
                
                };
                
                if (typeof nameContent == 'string') {
                    
                    
                    if ( nameContent == parameter.container[parameter.container.length - 1].name ){
                    
                        action();
                    
                    }

                }else{
                    
                    action();
                
                }
                
            }; 
            
            var close = function(){
                
                if(parameter.container.length > 1){
                    
                    parameter.object.empty();
                    console.info(parameter.container[parameter.container.length - 1].name + " - Content Close!");
                    parameter.container.pop();
                    
                    parameter.object.append(parameter.container[(parameter.container.length) - 1].content);
                    console.info(parameter.container[parameter.container.length - 1].name + " - Content Open!");
                    
                }
            
            };
            
            var clear = function(){
            
                parameter.container = [];
                console.info("Container cleared!");
                
            }
            
            parameter.object.on("load", load);
            parameter.object.on("close", close);
            parameter.object.on("clear", clear);
            parameter.object.on("closePrev", closePrevious);
        
        });
        
    }
    
    $.fn.btnCloseContent = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                
                object: $(this),
                content: null
                
            }, parameters);
            
            var close = function(){
            
                parameter.content.trigger("close");
            
            };
            
            parameter.object.on("click", close);
            
        });
        
    }
    
}(jQuery));