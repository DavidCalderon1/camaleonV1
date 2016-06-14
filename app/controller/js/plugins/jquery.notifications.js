// JavaScript Document

(function ( $ ){
    
    $.fn.notification = function(parameters) {
        
        this.each(function(){
        
            var parameter = $.extend({
                object: $(this),
                timeout: 0,
                aAction: null,
                nAction: null
            }, parameters);
            
            var open = function(){
                
                parameter.object.fadeIn();
                
                if(parameter.timeout != 0){
                    
                    setTimeout(close, parameter.timeout);
                    
                };
            
            };
            
            var close = function(before){
                
                parameter.object.fadeOut(function(){
                    
                    if(parameter.object.parent().hasClass("widescreen")){
                
                        parameter.object.parent().removeClass("widescreen");
                
                    }
                    
                    if (typeof before == 'function') {
                    
                        before();
                    
                    }
                    
                    parameter.object.remove();
                    
                });
            
            };
            
            var aFunction = function(){
                
                close(parameter.aAction);
            
            };
            
            var nFunction = function(){
            
                close(parameter.nAction);
            
            };
            
            parameter.object.on("close", close);
            parameter.object.on("open", open);
            
            parameter.object.trigger("open");
            parameter.object.find(".close").on("click", close);
            
            parameter.object.find("#acept").on("click", aFunction);
            parameter.object.find("#cancel").on("click", nFunction);
            
        });
        
    };

    $.fn.cNotification = function(parameters) {
        
        this.each(function(){
        
            var parameter = $.extend({
                container: $(this)
            }, parameters);
            
            var data = null;
            
            var closePrev = function(){
            
                parameter.container.find(".notification").trigger("close");
                
            };
            
            var msg = function(){
                
                var element = $('<div class="notification msg"></div>');
                element.append('<div class="close"><i class="icon-cerrar_a"></i></div>');
                element.append('<div class="icon"><i class="' + data.subtype + ' ' + data.icon + '"></i></div>');
                element.append('<div class="message">' + data.message + '</div>');
                
                element.notification({timeout: 8000});
                
                parameter.container.append(element);
                
            };
            
            var confirm = function(){
                
                parameter.container.addClass("widescreen");
                
                var element = $('<div class="notification confirm"></div>');
                element.append('<div class="close"><i class="icon-cerrar_a"></i></div>');
                element.append('<div class="icon"><i class="' + data.subtype + ' ' + data.icon + '"></i></div>');
                element.append('<div class="message">' + data.message + '</div>');
                element.append('<div class="btns"></div>');
                element.find(".btns").append('<button id="acept">ACEPTAR</button>');
                element.find(".btns").append('<button id="cancel">CANCELAR</button>');
                
                element.notification({aAction: data.affirmative, nAction: data.negative});
                
                parameter.container.append(element);
                
            };
            
            var show = function(event, dt){
                
                data = dt;
                
                closePrev();
                
                if(data.type == "msg"){
                
                    setTimeout(msg, 500); 
                
                }else if(data.type == "confirm"){
                
                    setTimeout(confirm, 500);
                
                }    
            
            };
            
            parameter.container.on("show", show);
            
        });
        
    };
    
}(jQuery));