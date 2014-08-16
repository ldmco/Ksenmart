var swfu_image;

window.onload = function() {
    var settings_image = {
        flash_url: "components/com_ksenmart/js/swfupload/swfupload.swf",
        flash9_url: "components/com_ksenmart/js/swfupload/swfupload_fp9.swf",
        upload_url: "index.php?option=com_ksenmart&task=media.upload_image&" + session_name + "=" + session_id + "&" + token + "=1&to=" + upload_to + "&folder=" + upload_folder,
        post_params: {
            "PHPSESSID": session_id
        },
        file_size_limit: "10 MB",
        file_types: "*.jpg;*.png;*.bmp;*.gif",
        file_types_description: "Images Files",
        file_upload_limit: 100,
        file_queue_limit: 0,
        custom_settings: {
            progressTarget: "fsUploadProgress",
            cancelButtonId: "btnCancel"
        },
        debug: false,
        button_width: "140",
        button_height: "40",
        button_placeholder_id: "spanButtonPlaceHolder",
        button_image_url: "components/com_ksenmart/js/swfupload/images/upload.png",
        button_text: '<span class="theFont">' + Joomla.JText._('KSM_UPLOAD') + '</span>',
        button_text_style: '.theFont { font-size: 15px;color:#ffffff;font-family:"Trebuchet MS";text-align:center;}',
        button_text_left_padding: 0,
        button_text_top_padding: 7,

        // The event handler functions are defined in handlers.js
        swfupload_preload_handler: preLoad,
        swfupload_load_failed_handler: loadFailed,
        file_queued_handler: fileQueued,
        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_start_handler: uploadStart,
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: uploadSuccess,
        upload_complete_handler: uploadComplete
        //queue_complete_handler : queueComplete	// Queue plugin event

    };
    swfu_image = new SWFUpload(settings_image);
};

jQuery(document).ready(function() {

    photos_sortable();
    jQuery('.photos .photo:first').addClass('active');

    jQuery('.photo .image-preview').on('click', function() {
        jQuery('body').append('<div id="popup-overlay_imageparams"></div>');
        jQuery(this).parent().next().show();
    });

    jQuery('.popupForm .displace label').on('click', function() {
        var displace_val = jQuery(this).attr('displace_val');
        jQuery(this).parent().find('label').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).parent().parent().find('input[type="hidden"]').val(displace_val);
        return false;
    });

    jQuery('.popupForm .watermark label').on('click', function() {
        var watermark_val = jQuery(this).attr('watermark_val');
        jQuery(this).parent().find('label').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).parent().parent().find('input[type="hidden"]').val(watermark_val);
        return false;
    });

    jQuery('.photo .del-img').on('click', function() {
        if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
            var photo = jQuery(this).parents('.photo');
            var filename = photo.find('.filename').val();
            var url = 'index.php?option=com_ksenmart&task=media.delete_photo&filename=' + filename + '&folder=' + upload_folder;
            jQuery.ajax({
                url: url,
                success: function(data) {
                    photo.remove();
                }
            });
        }
        return false;
    });

});

function appendImgForm(data) {
    var amount = (-jQuery('.photos .photo').length - 1) + '';
    var html = data.html + '';
    html = html.split('{id}').join(amount);
    jQuery('.photos .photos-row').append(html);
    photos_sortable();
    jQuery('#uploadform  .progressBar').hide();
    jQuery('#uploadform  .file').hide();
    cuSel({
        changedEl: "select.sel"
    });
    photos_reordering();
}

function photos_sortable() {
    jQuery('.photos .photos-row').sortable({
        items: '.photo',
        handle: '.image-preview',
        stop: function() {
            photos_reordering();
        }
    });
}

function photos_reordering() {
    var ordering = 1;
    jQuery('.form .photo').removeClass('active');
    jQuery('.form .photo').each(function() {
        if (ordering == 1)
            jQuery(this).addClass('active');
        jQuery(this).find('.ordering').val(ordering);
        ordering++;
    });
}

function iparamClose(obj) {
    jQuery('#popup-overlay_imageparams').remove();
    jQuery(obj).parents('.popupForm').hide();
}