jQuery(document).ready(function() {
    jQuery('.js-info').on('click', function() {
        var extension = jQuery(this).attr('rel');
        createPopup('Лицензионное соглашение', 'license', false);

        var popup_license = jQuery('.popup.license');
        var license = null;

        jQuery('.overlay.license').fadeIn(400, function() {

            jQuery.ajax({
                url: 'index.php?option=com_ksen&task=settings.getKSVersion&extension=' + extension,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    popup_license.fadeIn(400, function() {
                        var html = '';
                        html += '<table><tbody>';

                        html += '<tr>';
                        html += '<td>Название программы</td>';
                        html += '<td>' + data.name + '</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Версия</td>';
                        html += '<td>' + data.version + '</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Разработчик</td>';
                        html += '<td>L.D.M. & Co</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Сайт разработчика</td>';
                        html += '<td><a href="http://www.ldmco.ru/" title="" target="_blank">www.ldmco.ru</a></td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Сайт поддержки</td>';
                        html += '<td><a href="http://www.ksenmart.ru/" title="" target="_blank">www.ksenmart.ru</a></td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Страна производитель</td>';
                        html += '<td>Россия</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td>Лицензия</td>';
                        html += '<td><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.ru"><img alt="Лицензия" creative="" commons="" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/80x15.png"></a><br>Произведение «<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Ksenmart</span>» созданное <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.ldmco.ru" target="_blank" property="cc:attributionName" rel="cc:attributionURL">L.D.M. &amp; Co</a>, публикуется на условиях <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.ru">лицензии Creative Commons «Attribution-ShareAlike» («Атрибуция — На тех же условиях») 3.0 Непортированная</a>.<br>Основано на произведении с <a xmlns:dct="http://purl.org/dc/terms/" href="www.joomla.org" rel="dct:source">www.joomla.org</a>.<br>Разрешения, выходящие за рамки данной лицензии, могут быть доступны на странице <a xmlns:cc="http://creativecommons.org/ns#" href="http://ksenmart.ru" target="_blank" rel="cc:morePermissions">www.ksenmart.ru</a></td>';
                        html += '</tr>';

                        html += '</tbody></table>';
                        jQuery(this).children('.body').html(html);
                    });
                }
            });
        });
    });

    jQuery('body').on('click', '.popup .close', function(){
        closePopup(jQuery(this));
    });
});