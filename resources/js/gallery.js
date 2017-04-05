humhub.modules.ui.widget.Widget.instance('#gallery-media-upload').on('humhub:file:uploadEnd', function() {location.reload();});


///**
// * Append log message(s) to a log container.
// * 
// * @param messages
// *            string | array&lt;string&gt; - the messages.
// * @param container
// *            jQuery Object - the container object.
// */
//function appendLogMessages(messages, container) {
//    if ($.isArray(messages)) {
//        $.each(messages, function (index, message) {
//            container.append('<li>' + message + '</li>');
//        });
//    } else if (!jQuery.isEmptyObject(messages)) {
//        container.append('<li>' + messages + '</li>');
//    }
//}
///**
// * Update and show the log container.
// * 
// * @param messages
// *            string | array&lt;string&gt; - the messages.
// */
//function updateLog(messages) {
//    appendLogMessages(messages, $('#logContainer .alert-danger'));
//    $('#logContainer').show();
//}
///**
// * Clear and hide the log container.
// */
//function clearLog() {
//    $('#logContainer .alert-danger').empty();
//    $('#logContainer').hide();
//}
//
//$(function () {
//
//    /**
//     * Install uploader (blueimp basic jquery plugin)
//     */
////    $uploader = $('#gallery-media-upload');
////    $uploader.fileupload({
////        url: galleryMediaUploadUrl,
////        dataType: 'json',
////        done: function (e, data) {
////            $('#gallery-content').html(data.result.galleryHtml);
////            if (!jQuery.isEmptyObject(data.result.errors)) {
////                updateLog(data.result.errors);
////            }
////        },
////        fail: function (e, data) {
////            console.log(data.jqXHR.responseJSON);
////            updateLog(data.jqXHR.responseJSON.message);
////        },
////        start: function (e, data) {
////            clearLog();
////        },
////        progressall: function (e, data) {
////            var progress = parseInt(data.loaded / data.total * 100, 10);
////            if (progress != 100) {
////                $('#progress').show();
////                $('#progress .progress-bar').css('width', progress + '%');
////            } else {
////                $('#progress').hide();
////            }
////        }
////    })
////    $uploader.prop('disabled', !$.support.fileInput);
////    $uploader.parent().addClass($.support.fileInput ? undefined : 'disabled');
//});