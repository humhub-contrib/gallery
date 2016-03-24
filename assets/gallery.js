function appendLogMessages(messages, container) {
	if ($.isArray(messages)) {
		$.each(messages, function(index, message) {
			container.append('<li>' + message + '</li>');
		});
	} else if (!jQuery.isEmptyObject(messages)) {
		container.append('<li>' + messages + '</li>');
	}
}

function updateLog(messages) {
	appendLogMessages(messages, $('#logContainer .alert-danger'));
	$('#logContainer').show();
}

function clearLog() {
	$('#logContainer .alert-danger').empty();
	$('#logContainer').show();
}


$(function() {	
	
//	/**
//	 * Install uploader
//	 */
//	$('#fileupload')
//			.fileupload(
//					{
//						url : cfilesUploadUrl,
//						dataType : 'json',
//						done : function(e, data) {
//							$.each(data.result.files, function(index, file) {
//								$('#fileList').html(file.fileList);
//							});
//							updateLogs(data.result.errormessages,
//									data.result.warningmessages,
//									data.result.infomessages);
//						},
//						fail : function(e, data) {
//							updateLogs(data.jqXHR.responseJSON.message, null,
//									null);
//						},
//						start : function(e, data) {
//							clearLog();
//						},
//						progressall : function(e, data) {
//							var progress = parseInt(data.loaded / data.total
//									* 100, 10);
//							if (progress != 100) {
//								$('#progress').show();
//								$('#progress .progress-bar').css('width',
//										progress + '%');
//							} else {
//								$('#progress').hide();
//								$('#fileupload').parents(".btn-group").click();
//							}
//						}
//					}).prop('disabled', !$.support.fileInput).parent()
//			.addClass($.support.fileInput ? undefined : 'disabled');
});