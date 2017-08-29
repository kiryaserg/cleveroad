/**
 * Created by administrator on 01.03.17.
 */

(function ($) {
    'use strict';

    $.fn.charsCalculator = function (params) {
        params = params || {};
        var self = this;
        var options = {
            maxLength: params['maxLength'] || self.attr('max_length') || 200,
            message: params['message'] ||  self.attr('message') || "You've typed {current} characters, and have {left} left.",
            messageContainer: params['messageContainer'] || self.attr('message_container') || ""
        };

        function calculateCharsInElement()
        {
            if(self.val().length <= options.maxLength){
                changeMessage();
            }else{
                cutValueToMaxLength();
            }
        }

        function changeMessage()
        {
            var left = options.maxLength -  self.val().length;
            var message = options.message.replace('{current}', self.val().length).replace('{left}', left);
            $(options.messageContainer).html(message);
        }

        function cutValueToMaxLength()
        {
            self.val( self.val().substr(0, options.maxLength));
        }

        self.on('keyup', calculateCharsInElement);

        calculateCharsInElement();

        return self;
    };

})(jQuery);
