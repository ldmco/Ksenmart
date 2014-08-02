var swfu_video,swfu_image,swfu_file;

window.onload = function() {
	swfu_image =new SWFUpload({
		flash_url : URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/swfupload.swf",
		flash9_url : URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/swfupload_fp9.swf",
		upload_url: URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/upload.php?width="+production_form_width+"&height="+production_form_width,
		post_params: {"PHPSESSID" : session_id},
		file_size_limit : "1 MB",
		file_types : "*.jpg;*.png;*.bmp;*.gif",
		file_types_description : "Images Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,
			// Button settings
		//button_image_url: "/administrator/components/com_ksenmart/js/swfupload/images/button.png",
		button_width: "140",
		button_height: "40",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_image_url: URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/images/upload.png",
		button_text: '<span class="theFont">'+JText_upload+'</span>',
		button_text_style: '.theFont { font-size: 15px;color:#ffffff;font-family:"Trebuchet MS";text-align:center;}',
		button_text_left_padding: 0,
		button_text_top_padding: 7,
		
		// The event handler functions are defined in handlers.js
		swfupload_preload_handler : preLoad,
		swfupload_load_failed_handler : loadFailed,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete
		//queue_complete_handler : queueComplete	// Queue plugin event
		
	});
	
	swfu_video = new SWFUpload({
		flash_url : "components/com_ksenmart/js/swfupload/swfupload.swf",
		flash9_url : "components/com_ksenmart/js/swfupload/swfupload_fp9.swf",
		upload_url: "components/com_ksenmart/js/swfupload/upload_video.php",
		post_params: {"PHPSESSID" : session_id},
		file_size_limit : "10 MB",
		file_types : "*.flv",
		file_types_description : "Video Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress_video",
			cancelButtonId : "btnCancel_video"
		},
		debug: false,
			// Button settings
		//button_image_url: "/administrator/components/com_ksenmart/js/swfupload/images/button.png",
		button_width: "140",
		button_height: "40",
		button_placeholder_id: "spanButtonPlaceHolder_video",
		button_image_url: "components/com_ksenmart/js/swfupload/images/upload.png",
		button_text: '<span class="theFont">'+JText_upload+'</span>',
		button_text_style: '.theFont { font-size: 15px;color:#ffffff;font-family:"Trebuchet MS";text-align:center;}',
		button_text_left_padding: 0,
		button_text_top_padding: 7,
		
		// The event handler functions are defined in handlers.js
		swfupload_preload_handler : preLoad,
		swfupload_load_failed_handler : loadFailed,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccessVideo,
		upload_complete_handler : uploadComplete,
		//queue_complete_handler : queueComplete	// Queue plugin event
		
		swfupload_element_id : "flashUI2",		
		degraded_element_id : "degradedUI2"
	});
	
	swfu_file =new SWFUpload({
		flash_url : URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/swfupload.swf",
		flash9_url : URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/swfupload_fp9.swf",
		upload_url: URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/upload_file.php",
		post_params: {"PHPSESSID" : session_id},
		file_size_limit : "1 MB",
		file_types : "*",
		file_types_description : "Images Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress_file",
			cancelButtonId : "btnCancel_file"
		},
		debug: false,
			// Button settings
		//button_image_url: "/administrator/components/com_ksenmart/js/swfupload/images/button.png",
		button_width: "140",
		button_height: "40",
		button_placeholder_id: "spanButtonPlaceHolder_file",
		button_image_url: URI_ROOT+"administrator/components/com_ksenmart/js/swfupload/images/upload.png",
		button_text: '<span class="theFont">'+JText_upload+'</span>',
		button_text_style: '.theFont { font-size: 15px;color:#ffffff;font-family:"Trebuchet MS";text-align:center;}',
		button_text_left_padding: 0,
		button_text_top_padding: 7,
		
		// The event handler functions are defined in handlers.js
		swfupload_preload_handler : preLoad,
		swfupload_load_failed_handler : loadFailed,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccessFile,
		upload_complete_handler : uploadComplete
		//queue_complete_handler : queueComplete	// Queue plugin event
		
	});	
};
