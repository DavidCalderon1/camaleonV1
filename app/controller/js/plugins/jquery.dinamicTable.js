// JavaScript Document

(function($){
    
    $.fn.rowdespl = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                
                object: $(this),
                shot: null,
                url: "",
                disposal: null,
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
            
            var changeIcon = function(){
            
                if(parameter.object.parent().children(".resultbox_aaa").children(".icon").hasClass("icon-plus_a")){
                    parameter.object.parent().children(".resultbox_aaa").children(".icon").removeClass("icon-plus_a");
                    parameter.object.parent().children(".resultbox_aaa").children(".icon").addClass("icon-minus");
                }else if(parameter.object.parent().children(".resultbox_aaa").children(".icon").hasClass("icon-minus")){
                    parameter.object.parent().children(".resultbox_aaa").children(".icon").removeClass("icon-minus");
                    parameter.object.parent().children(".resultbox_aaa").children(".icon").addClass("icon-plus_a")
                }
            
            };
            
            var expandir = function(){
                
                var operation = function(result){
                    
                    var listItems = $.map(result, function(item, index){
                        
                        var rbx_aa = parameter.disposal(item);
                        
                        if(item.subentity){
                            
                            var rbx_a = $('<div class="resultbox_a" data-entity="' + item.subentity + '" data-id="' + item.id + '"></div>');

                            var subparameters = {shot: rbx_aa.find(".icon"), url: parameter.url, disposal: parameter.disposal, beforeSend: parameter.beforeSend, complete: parameter.complete};
                            rbx_a.rowdespl(subparameters);
                            
                            rbx_aa.append(rbx_a);
                            
                        }
                        
                        return rbx_aa;
                        
                    });
 
                    parameter.object.append(listItems);
                    
                    changeIcon();
                    
                    parameter.object.slideDown();
                    
                
                };
                
                var data = {entity: parameter.object.data("entity")};
                if(parameter.object.data("id")){data.id=parameter.object.data("id")}
                
                loadData(data, operation);
                
            };
            
            var contraer = function(){
                
                parameter.object.slideUp(function(){
                    parameter.object.empty();
                    changeIcon();
                });
                
            };
            
            var slide = function(){
                
                if(parameter.object.hasClass("expandido")){
                    contraer();
                    parameter.object.removeClass("expandido");
                }else{
                    expandir();
                    parameter.object.addClass("expandido");
                }
                
            };
            
            
            
            parameter.object.on("slide", slide);
            parameter.shot.on("click", slide);
        
        });
        
    }
    
    $.fn.description = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                
                object: $(this),
                content: null
                
            }, parameters);
            
            var close = function(){
            
                parameter.content.removeClass("show");
                
            }
             
            var show = function(){
                
                var text = parameter.object.html();
                var descrip = $('<div class="descrip"></div>');
                descrip.append(text);
                parameter.content.empty();
                parameter.content.append(descrip);
                parameter.content.addClass("show");
                
            };
            
            parameter.object.parent().find(".name").on("click", show);
            parameter.content.on("click", close)
        
        });
        
    }
    
}(jQuery));