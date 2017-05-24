humhub.module('gallery', function (module, require, $) {

    var client = require('client');
    var Widget = require('ui.widget').Widget;

    module.initOnPjaxLoad = true;

    var init = function (isPjax) {
        debugger;
        if ($('#gallery-media-upload').length) {
            Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function (evt, response) {
                client.reload();
            });
        }
    };

    module.export({
        init: init
    });
});
