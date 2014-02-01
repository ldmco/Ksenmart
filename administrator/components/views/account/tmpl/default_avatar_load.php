<div class="form" id="avatar_load">
    <div class="spinner" role="spinner"><div class="spinner-icon"></div></div>
    <div class="desc">Размер файла не должен привышать 8 мб.</div>
    <div class="row-fluid">
        <label class="inputname">Аватар</label>
        <div class="avatar_preview"><?php echo $this->user_info->avatar_full; ?></div>
        <input type="file" name="filename" class="inputbox" accept="image/jpeg,image/png,image/gif" />
    </div>
    <div class="preview-pane">
        <div class="preview-container">
            <?php echo $this->user_info->avatar_full; ?>
        </div>
    </div>
    <input type="hidden" name="elid" value="<?php echo $this->user_info->elid; ?>" />
    <input type="hidden" name="x1" value="0" />
    <input type="hidden" name="y1" value="0" />
    <input type="hidden" name="h" value="0" />
    <input type="hidden" name="w" value="0" />

    <input type="hidden" name="boundx" value="0" />
    <input type="hidden" name="boundy" value="0" />
</div>
<script>

    var $pimg, jcrop_api;

    function cropImg(class_j){
        var 
            boundx,
            boundy,

        // Grab some information about the preview pane
        $preview    = jQuery('.preview-pane'),
        $pcnt       = jQuery('.preview-pane .preview-container'),
        

        xsize = $pcnt.width(),
        ysize = $pcnt.height();

        jQuery(class_j).Jcrop({
            onChange: updatePreview,
            onSelect: updatePreview,
            minSize: [80, 80],
            bgFade: true,
            aspectRatio: xsize / ysize
        },function(){
            // Use the API to get the real image size
            var bounds = this.getBounds();
            boundx = bounds[0];
            boundy = bounds[1];
            // Store the API in the jcrop_api variable
            jcrop_api = this;

            jcrop_api.setSelect([0,0,80,80]);
            // Move the preview into the jcrop container for css positioning
            $preview.appendTo(jcrop_api.ui.holder);
        });

        function updatePreview(c){
            if (parseInt(c.w) > 0){
                var rx = xsize / c.w;
                var ry = ysize / c.h;

                jQuery('input[name="x1"]').val(c.x);
                jQuery('input[name="y1"]').val(c.y);
                jQuery('input[name="h"]').val(c.h);
                jQuery('input[name="w"]').val(c.w);
                jQuery('input[name="boundx"]').val(boundx);
                jQuery('input[name="boundy"]').val(boundy);

                $pimg.css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        };
    }

    jQuery(document).ready(function(){

        jQuery('.spinner').fadeOut(400);

        $pimg           = jQuery('.preview-pane .preview-container img');
        var pre_pane    = '<div class="preview-pane"><div class="preview-container"><img alt="" class="jcrop-preview" /></div></div>';

        var flag = true;


        var current_popup   = jQuery('.popup.avatar_load');
        var form            = current_popup.find('#avatar_load');

        form.attr('enctype', 'multipart/form-data');

        $pimg = jQuery('.preview-pane .preview-container img');
        $pimg.removeClass('target');
        $pimg.addClass('jcrop-preview');

        cropImg('.target');
        
        form.find('input[name="filename"]').on('change', function(){

            flag = false;

            var oFile = jQuery(this)[0].files[0];

            var avatar_preview      = jQuery('.avatar_preview');
            var avatar_preview_img  = avatar_preview.children('img');
            
            //avatar_preview.find('.jcrop-holder').remove();
            avatar_preview_img.removeAttr('style');

            if (typeof jcrop_api != 'undefined') {
                jcrop_api.destroy();
            }

            var oImage = avatar_preview_img[0];


            $pimg = jQuery('.preview-pane .preview-container img');
            
            if($pimg.length == 0){
                avatar_preview.after(pre_pane);
                $pimg = jQuery('.preview-pane .preview-container img');
            }

            var oReader = new FileReader();

            oReader.onload = function(e) {

                oImage.src   = e.target.result;

                oImage.onload = function () {
                    $pimg[0].src = e.target.result;
                    avatar_preview_img.attr('class', 'target');
                    cropImg('.target');
                };
            };
            oReader.readAsDataURL(oFile);
        });

        
        current_popup.parent().on('submit', function(e){
            
            e.preventDefault();
            NProgress.start();
            
            var elid = form.find('input[name="elid"]').val();
            var x1   = form.find('input[name="x1"]').val();
            var y1   = form.find('input[name="y1"]').val();
            var h   = form.find('input[name="h"]').val();
            var w   = form.find('input[name="w"]').val();

            var boundx   = form.find('input[name="boundx"]').val();
            var boundy   = form.find('input[name="boundy"]').val();

            var data        = new FormData();
            var error       = '';

            jQuery.each(form.find('input[name="filename"]')[0].files, function(i, file) {
                data.append('file-'+i, file);
                NProgress.inc();
            });

            data.append('elid', <?php echo $this->user_info->elid; ?>);

            data.append('x1', x1);
            data.append('h', h);
            data.append('y1', y1);
            data.append('w', w);

            data.append('resize', true);
            data.append('boundx', boundx);
            data.append('boundy', boundy);

            if(flag){
                data.append('flag', flag);
                data.append('avatar_full', '<?php echo $this->user_info->avatar_full_patch; ?>');
            }

            jQuery.ajax({
                url: root+'index.php?option=com_ksenmart&tmpl=ksenmart&task=account.loadImages',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                beforeSend: function(){
                    NProgress.inc();
                    jQuery('.spinner').fadeIn(400);
                },
                success: function(data){
                    flag = true;
                    if(data == ''){
                        var popup       = current_popup;
                        var popup_class = popup.attr('class').replace('popup ', '');
            
                        popup.fadeOut(400, function(){
                            jQuery(this).parent().remove();
                            jQuery('.overlay.'+popup_class).fadeOut(400, function(){
                                jQuery(this).remove();
                                location.reload();
                            });
                        });
                    }else{
                        createPopupNotice(data, '.settings');
                    }
                    NProgress.done();
                }
            });
        });
        
    });
</script>