// JavaScript Document
(function($){
    
    $.fn.sendForm = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                form: $(this),
                operation: "",
                beforeSend: "",
                complete: "",
                timeout: 10000
            }, parameters);
            
            // envia datos formulario
            
            var action = function(event){
                
                event.preventDefault();
                
                $.ajax({
                    url: parameter.form.attr("action"),
                    type: parameter.form.attr("method"),
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(parameter.form.serializeObject()),
                    success: function(result){
                        
                        if(result.ejecution==false){
                            
                            console.error("Ejecution Error - " + result.error);
                            alert("error", "icon-cerrar_a", "Error de ejecución.");
                            
                        }else if(result.ejecution==true){
                            
                            parameter.operation(result.result, result.message);
                            
                        }
                        
                    },
                    error: function(request, errorType, errorMessage){
                        
                        console.error('Error: ' + errorType + '\nMessage: ' + errorMessage);
                        alert("error", "icon-cerrar_a", "Error.");
                        
                    },
                    timeout: parameter.timeout,
                    beforeSend: parameter.beforeSend,
                    complete: parameter.complete
                });
                
            };
            
            parameter.form.on("submit", action);
            
        });
    
    }
    
    //carga datos del formulario
    
    $.fn.loadForm = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                form: $(this),
                operation: "",
                beforeSend: "",
                complete: "",
                timeout: 10000
            }, parameters);
            
            var action = function(event){
                event.preventDefault();
                $.ajax({
                    url: parameter.form.attr("action"),
                    type: parameter.form.attr("method"),
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({ loadData: true }),
                    success: function(result){
                        
                        if(result.ejecution==false){
                            
                            console.error("Ejecution Error - " + result.error);
                            
                        }else if(result.ejecution==true){
                            
                            parameter.operation(result.result, result.message, result.data);
                            
                        }
                        
                    },
                    error: function(request, errorType, errorMessage){
                        
                        console.error('Error: ' + errorType + '\nMessage: ' + errorMessage);
                        
                    },
                    timeout: parameter.timeout,
                    beforeSend: parameter.beforeSend,
                    complete: parameter.complete
                });
                
            }
            
            parameter.form.on("load", action);
            parameter.form.trigger("load");
            
        });
    
    }    

}(jQuery));