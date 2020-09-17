jQuery(document).ready(function () {
    var url = "index.php?option=com_ksen&task=media.upload_image&extension=" + KS.extension +"&" + session_name + "=" + session_id + "&" + token + "=1&to=" + upload_to + "&folder=" + upload_folder;
    var uploadButton = jQuery('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = jQuery(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });

    jQuery('#fileupload').fileupload({
        url: url,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        //dropZone: jQuery(window.parent.document),
        progressall: function (e, data) {
            console.log(data);
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        },
        dragover: function (e, data) {
            //if (jQuery('.images-drag-block').is(':visible')) return false;
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var dataTransfer = e.dataTransfer;
            if (dataTransfer && $.inArray('Files', dataTransfer.types) !== -1 &&
                this._trigger(
                    type,
                    $.Event(type, {delegatedEvent: e})
                ) !== false) {
                e.preventDefault();
                dataTransfer.dropEffect = 'copy';
            }
            jQuery('.images-drag-block').show();
            jQuery('.images-drag-text').show();
        },
        /*dragleave: function (e, data) {
            //if (jQuery('.images-drag-block').is(':visible')) return false;
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var dataTransfer = e.dataTransfer;
            if (dataTransfer && $.inArray('Files', dataTransfer.types) !== -1 &&
                this._trigger(
                    type,
                    $.Event(type, {delegatedEvent: e})
                ) !== false) {
                e.preventDefault();
            }
            jQuery('.images-drag-block').hide();
            jQuery('.images-drag-text').hide();
        },*/
        /*dragenter: function (e, data) {
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var dataTransfer = e.dataTransfer;
            if (dataTransfer && $.inArray('Files', dataTransfer.types) !== -1 &&
                this._trigger(
                    type,
                    $.Event(type, {delegatedEvent: e})
                ) !== false) {
                e.preventDefault();
            }
            jQuery('.images-drag-block').hide();
            jQuery('.images-drag-text').hide();
        },*/
        drop: function (e) {
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var that = this,
                dataTransfer = e.dataTransfer,
                data = {};
            if (dataTransfer && dataTransfer.files && dataTransfer.files.length) {
                e.preventDefault();
                this._getDroppedFiles(dataTransfer).always(function (files) {
                    data.files = files;
                    if (that._trigger(
                            'drop',
                            $.Event('drop', {delegatedEvent: e}),
                            data
                        ) !== false) {
                        that._onAdd(e, data);
                    }
                });
            }
            jQuery('.images-drag-block').hide();
            jQuery('.images-drag-text').hide();
        },
        done: function (e, data) {
            appendImgForm(JSON.parse(data._response.result));
        },
        formData: function (form) {

        },
        submit: function (e, data) {
            //data.form = {};
            //data.formData = {};
            //console.log(data);
        }
    });

    jQuery('.images-drag-block, .images-drag-text').on('click', function (e) {
        e.preventDefault();
        jQuery('.images-drag-block').hide();
        jQuery('.images-drag-text').hide();
    });

    photos_sortable();
    jQuery('.photos .photo:first').addClass('active');

    jQuery('.photo .image-preview').on('click', function() {
        jQuery('body > .form > .edit').append('<div id="popup-overlay_imageparams"></div>');
        jQuery(this).parent().next().show();
    });

    jQuery('.popupForm .displace label').on('click', function() {
        var displace_val = jQuery(this).data().value;
        jQuery(this).parent().find('label').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).parent().parent().find('input[type="hidden"]').val(displace_val);
        return false;
    });

    jQuery('.popupForm .watermark label').on('click', function() {
        var watermark_val = jQuery(this).data().value;
        jQuery(this).parent().find('label').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(this).parent().parent().find('input[type="hidden"]').val(watermark_val);
        return false;
    });

    jQuery('.ksm-slidemodule-images .remove').on('click', function(e){
        e.preventDefault();

        if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
            jQuery(this).closest('.ksm-slidemodule-images').find('.photo').each(function(){
                var photo = jQuery(this);
                var filename = photo.find('.filename').val();
                var url = 'index.php?option=com_ksen&task=media.delete_photo&extension=' + KS.extension + '&filename=' + filename + '&folder=' + upload_folder;
                jQuery.ajax({
                    url: url,
                    success: function(data) {
                        photo.remove();
                    }
                });
            });
        }
    });

    jQuery('.photo .del-img').on('click', function() {
        if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
            var photo = jQuery(this).parents('.photo');
            var filename = photo.find('.filename').val();
            var url = 'index.php?option=com_ksen&task=media.delete_photo&extension=' + KS.extension + '&filename=' + filename + '&folder=' + upload_folder;
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