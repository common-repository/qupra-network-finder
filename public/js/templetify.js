(function($) {
    'use strict';

    $.fn.extend({

        templateify: function(template, props, eventHandler) {

            var elem = this;
            elem.template = template;
            elem.props = $.extend({}, props);
            elem.data = {};

            elem.setData = function(data) {
                elem.data = {
                    ...elem.data,
                    ...data
                };
                elem.render();
            };

            elem.prepareRenderData = function() {
                return elem.data;
            };

            elem.render = function() {
                var $template = $(wp.template(elem.template)(elem.prepareRenderData()));
                if (typeof eventHandler == "function") {
                    eventHandler.bind(elem)($template);
                }
                $(elem).html($template);
            }

            return elem;
        }

    });

})(jQuery);