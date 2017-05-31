humhub.module('gallery', function (module, require, $) {

    var client = require('client');
    var Widget = require('ui.widget').Widget;

    module.initOnPjaxLoad = true;

    var init = function (isPjax) {
        if ($('#gallery-media-upload').length) {
           Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function (evt, response) {
               setTimeout(function(){ client.reload() }, 200); ;
           });
        }
    };

    module.export({
        init: init
    });
});
