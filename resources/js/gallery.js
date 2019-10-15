humhub.module('gallery', function (module, require, $) {

    var client = require('client');
    var Widget = require('ui.widget').Widget;

    module.initOnPjaxLoad = true;

    var init = function (isPjax) {
        if ($('#gallery-media-upload').length) {
           Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function (evt, response) {
               // Workaround until HumHub 1.2.1, then we can use client.reload()
               $.pjax.reload({container: '#layout-content', timeout : 5000});
           });
        }

        var $root = $('#gallery-container');

        if($root.length) {
            $root.find('.gallery-img').one("load", function() {
                $(this).fadeIn();
            }).each(function() {
                if(this.complete) {
                    $(this).trigger('load');
                }
            });
        }
    };

    module.export({
        init: init,
    });
});
