// JavaScript Document

(function($){
    
    $.fn.menu = function(parameters){
    
        this.each(function(){
        
            var parameter = $.extend({
                object: $(this),
                btn_menu : null,
                content: null
            }, parameters);
            
            var btnMenuShow = function(){
                
                parameter.btn_menu.addClass('icon-menu');
                
            };
            
            var btnMenuHide = function(){
                
                parameter.btn_menu.removeClass('icon-menu');
                
            };
            
            var open_close = function(){
                
                if(!(parameter.object.parent().hasClass("open"))){
                    parameter.object.parent().addClass("open");
                }else{
                    close();
                }
                
            };
            
            var close = function (){
                
                if(parameter.object.parent().hasClass("open")){
                    parameter.object.parent().removeClass("open");
                }
                
            };
            
            var loadMenu = function(event){
                
                event.preventDefault();
                
                if(parameter.object.data("name")!=""){
                    
                    btnMenuHide();
                    
                    parameter.object.empty();
                
                    $.ajax({
                        url: "/app/controller/php/menu.php",
                        type: "POST",
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify({showMenu: true, name: parameter.object.data("name")}),
                        success: function(result){

                            if(result.ejecution){

                                parameter.object.prepend(result.result);
                                btnMenuShow();
                                console.info( parameter.object.data("name") + " - Menu Loaded!");

                                $(".link").link({container: parameter.content, afterAction: close});

                            }else{

                                btnMenuHide();
                                console.warn( "No se encontro el menu a cargar!" );

                            }

                        },
                        error: function(request, errorType, errorMessage){

                            console.error('Error: ' + errorType +  '<br> whit message: ' + errorMessage);
                            btnMenuHide();

                        },
                        timeout: 10000,
                        beforeSend: function(){
                            
                            btnMenuHide();
                            parameter.btn_menu.addClass('icon-loading_b');

                        },
                        complete:function(){

                            parameter.btn_menu.removeClass('icon-loading_b');

                        }
                    });
                
                }
                
            };
            
            parameter.object.on("loadMenu", loadMenu);
            
            parameter.object.on("btnMenuShow", btnMenuShow);
            parameter.object.on("btnMenuHide", btnMenuHide);
            
            parameter.btn_menu.on("click", open_close);
            
            parameter.object.find("a").on("click", close);
            
        });
        
    }
    
}(jQuery));