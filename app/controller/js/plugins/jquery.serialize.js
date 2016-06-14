// JavaScript Document

(function ( $ ){

    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if(this.name.substr(0,5)=="crypt"){
                this.value = md5(this.value);
            }
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(base64_encode(this.value || ''));
            } else {
                o[this.name] = base64_encode(this.value || '');
            }
        });
        return o;
    };
    
}(jQuery));

