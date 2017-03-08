/**
 * SafeCraft plugin for Craft CMS
 *
 * SafeCraft JS
 *
 * @author    QuebecStudio
 * @copyright Copyright (c) 2017 QuebecStudio
 * @link      http://quebecstudio.com
 * @package   SafeCraft
 * @since     1.0.0
 */

$(function() {
	
	
    $('#settings-destination').on('change', function(){
        var sel = $(this).val();
        $('.dest').hide();
        if (sel=='STORAGE')
                $('.dest-storage').show();
        if (sel=='FTP')
                $('.dest-ftp').show();
    });
    $('#settings-destination').trigger('change');

});