// reload page after an upload to display uploaded images
humhub.modules.ui.widget.Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function (evt, response) {
    humhub.modules.client.reload(); 
});