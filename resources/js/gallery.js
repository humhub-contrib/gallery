humhub.module('gallery', function (module, require, $) {

    var client = require('client');
    var Widget = require('ui.widget').Widget;

    var showMore = function(evt) {
        if(!$('#gallery-list').length) {
            return;
        }

        var page = evt.$trigger.data('nextPage') || 1;
        client.get(evt, {data: {page:page}}).then(function(response) {
            $('#gallery-media-container').append(response.html);

            if(response.isLast) {
                evt.$trigger.hide();
            }

            evt.$trigger.data('nextPage', ++page);

            setTimeout(fadeIn, 100);
        }).catch(function(e) {
            module.log.error(e, true);
        });
    };

    var init = function (isPjax) {
        if ($('#gallery-media-upload').length) {
           Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function (evt, response) {
               // Workaround until HumHub 1.2.1, then we can use client.reload()
               client.reload();
           });
        }

       fadeIn();
    };

    var fadeIn = function() {
        var $root = $('#gallery-container');

        if(!$root.length) {
            return;
        }

        $root.imagesLoaded().progress(function(instance, image) {
            var $img =  $(image.img);
            if(image.isLoaded) {
                $img.fadeIn('slow');
            } else {
                $img.attr('src', module.config.fallbackImageUrl).show()
                    .parent().attr('data-ui-gallery', null)
                    .css('cursor', 'not-allowed')
                    .attr('title', module.text('error.loadImageError'))
                    .attr('href', null)
                    .find('.overlay').remove();
            }
        });
    };

    module.export({
        init: init,
        initOnPjaxLoad: true,
        showMore: showMore
    });
});
