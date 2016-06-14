// JavaScript Document

(function($){
    
    $.fn.select = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                select: $(this),
                value: "",
                next: null,
                url: "",
                beforeSend: "",
                complete: "",
                timeout: 10000
            }, parameters);
            
            var loadData = function(data, operation){
                
                $.ajax({
                    url: parameter.url,
                    type: "post",
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(data),
                    success: function(result){
                        
                        if(result.ejecution==false){
                            
                            console.error("Ejecution Error - " + result.error);
                            alert("error", "icon-cerrar_a", "Error de ejecución.");
                            
                        }else if(result.ejecution==true){
                            
                            operation(result.result);
                            
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
            
            var load = function(event, id){
            
                var select = parameter.select.find("select");
                
                var value = parameter.value;
                
                var operation = function(result){
                    
                    select.find(".opt").remove();
                    
                    var listItems = $.map(result, function(item, index){
                        
                        var option = $('<option value="' + item.id + '" class="opt">' + item.nombre + '</option>');
                        
                        if(value != ""){
                        
                            if(value == item.id){
                            
                                option.attr("selected", "");
                            
                            }
                        
                        }
        
                        return option;
                        
                    });

                    select.append(listItems);
                
                };
                
                var data = {entity: select.data("entity")};
                
                if( id > 0 ){data.id = id}
                
                loadData(data, operation);
                
                parameter.select.addClass("show");
            
            };
            
            var loadNext = function(){
            
                var select = parameter.select.find("select");
                
                if( parameter.next != null ){
                    
                    parameter.next.find("select").val("");
                
                    if( parameter.value != "" ){
                        
                        parameter.next.find("select").trigger("load", parameter.value);
                        
                    }else if( select.val() != "" ){

                        parameter.next.find("select").trigger("load", select.val());

                    }else{

                        parameter.next.removeClass("show");

                    }
                    // permite ejecutar y activar el select anidado
                    parameter.next.find("select").trigger("change");
                
                }
                
                parameter.value = "";
                
            };
            
            parameter.select.find("select").on("load", load);
            parameter.select.find("select").on("change", loadNext);
            
        });
        
    }
    
}(jQuery));