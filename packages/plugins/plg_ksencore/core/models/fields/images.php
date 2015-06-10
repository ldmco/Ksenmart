<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldImages extends JFormField {

    protected $type = 'Images';

    public function getInput() {
        $document = JFactory::getDocument();
        $document->addScript(JURI::base() . 'components/com_ksen/assets/js/swfupload/swfupload.js');
        $document->addScript(JURI::base() . 'components/com_ksen/assets/js/swfupload/swfupload.queue.js');
        $document->addScript(JURI::base() . 'components/com_ksen/assets/js/swfupload/js/fileprogress.js');
        $document->addScript(JURI::base() . 'components/com_ksen/assets/js/swfupload/js/handlers.js');
        $document->addScript(JURI::base() . 'components/com_ksen/assets/js/swfupload/config_image.js');
        $extension = (string)$this->element['extension'];
        
        $html = '';
        $keys = array_keys($this->value);
        if(count($keys) == 0 || $keys[0] > 0) {
            $html .= '<div class="thumb-img photos">';
            $html .= '  <div class="photos-row">';
        }
        foreach($this->value as $file) {
            $cparams = JComponentHelper::getParams($extension);
            $source_filename = JPATH_ROOT . DS . 'media' . DS . $extension . DS . $file->media_type . 's' . DS . $file->folder . DS . 'original' . DS . $file->filename;

            JForm::addFormPath(JPATH_ADMINISTRATOR.'/components/com_ksen/models/forms/');
            $form = JForm::getInstance('com_ksen.image' . $file->id, 'image', array('control' => $this->name . '[' . $file->id . '][params]'));
            $params = (array )json_decode($file->params);

            $keys = array(
                'title',
                'watermark',
                'displace',
                'halign',
                'valign');
            foreach($keys as $k) {
                if(array_key_exists($k, $params)) {
                    $v = $params[$k];
                } else {
                    $v = $cparams->get($k, null);
                }
                if($v !== null) {
                    $form->setValue($k, null, $v);
                }
            }

            $thumb_url = KSMedia::resizeImage($source_filename, $file->folder, $cparams->get('admin_product_medium_image_width', 120), $cparams->get('admin_product_medium_image_height', 120), $params, $extension);
            $dst_filename = basename($thumb_url);
            $html .= '<div class="photo">';
            $html .= '<span class="del-img"></span>';
            $html .= '
                <div>
                    <img class="image-preview" src="' . $thumb_url . '">
                </div>
            ';
            $html .= '<div class="popupForm">
                        <div class="form">
                            <div class="heading">
                                <h3>Параметры изображения</h3>
                                <div class="save-close">
                                    <input type="button" class="close" onclick="iparamClose(this);return false;" />
                                </div>
                            </div>
                            <div class="edit">
                                <table width="100%">
                                    <tr>
                                        <td class="onecol" id="iparamscontent">';
            $html .= '
            <div id="images-preview">
                <div class="leftcol">';
            foreach($keys as $k) {
                $html .= '<div class="row">' . $form->getLabel($k) . $form->getInput($k) . '</div>';
            }
            $html .= '
                </div>
                <div class="rightcol">
                    <div class="preview">
                        <table>
                            <tr>
                                <td style="text-align: center;vertical-align: middle;">
                                    <img src="' . $thumb_url . '" alt="" class="previewxa" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>';
            $html .= '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>';
            $html .= '</div>';
            $html .= '<input type="hidden" value="' . $file->ordering . '" class="ordering" name="' . $this->name . '[' . $file->id . '][ordering]" />';
            $html .= '<input type="hidden" class="task" value="save"  name="' . $this->name . '[' . $file->id . '][task]" >';
            $html .= '<input type="hidden" class="task filename" value="' . basename($file->filename) . '"  name="' . $this->name . '[' . $file->id . '][filename]" >';

            $html .= '</div>';


        }
        $keys = array_keys($this->value);
        if(count($keys) == 0 || $keys[0] > 0) {
            $html .= '  </div>';
            $html .= '</div>';
            $html .= '<br clear="both">';
            $html .= '<div class="uploadButtons">';
            $html .= '  <span id="spanButtonPlaceHolder"></span>';
            $html .= '  <input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />';
            $html .= '</div>';
            $html .= '<div class="fieldset flash" id="fsUploadProgress">';
            $html .= '</div>';
            $html = KSSystem::wrapFormField('slidemodule', $this->element, $html);
        }
        $session = JFactory::getSession();
        $script = '
            var session_id="' . $session->getId() . '";
            var session_name="' . $session->getName() . '";
            var token="' . JSession::getFormToken() . '";
            var upload_to="' . $this->element['upload_to'] . '";
            var upload_folder="' . $this->element['upload_folder'] . '";
        ';
        $document->addScriptDeclaration($script);
        return $html;
    }
}